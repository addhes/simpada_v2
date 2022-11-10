<?php

namespace App\Exports;
use Illuminate\Support\Facades\DB;
use Modules\Submission\Entities\Submission;
use Modules\Submission\Entities\SubmissionDetail;
use Modules\Submission\Entities\Accountability;
use Modules\Submission\Entities\AccountabilityDetail;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithMapping;

use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class SubmissionExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles, WithColumnFormatting, WithMapping, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $tgl_pertama;
    protected $tgl_terakhir;
    protected $status;
    protected $pjb;

    function __construct($tgl_pertama, $tgl_terakhir, $status, $pjb) {
        $this->tgl_pertama = $tgl_pertama;
        $this->tgl_terakhir = $tgl_terakhir;
        $this->status = $status;
        $this->pjb    = $pjb;
      }

    public function collection()
    {

     DB::statement(DB::raw('set @rownum=0'));
     $data = DB::table('submissions as s')
     ->join('users as u', 's.user_id', '=', 'u.id')
     ->leftjoin('channels as c', 's.channel_id', '=', 'c.id')
     ->leftjoin('banks as b', 's.bank_id', '=', 'b.id')
     ->leftjoin('categories as k', 's.category_id', '=', 'k.id')
     ->leftjoin('accountabilities as res', 's.submission_code', '=', 'res.submission_code')
     ->leftjoin('accountabilitydetails as resdetail', 'res.accountability_code', '=', 'resdetail.accountability_code')
     ->select(DB::raw("@rownum  := @rownum  + 1 AS rownum"), 's.submission_code', DB::raw('max(u.name) as username'), DB::raw('max(s.title) as title, max(s.created_at) as created_at, max(s.description) as description, max(c.name) as channel_name, max(s.estimated_price) as estimated_price, SUM(resdetail.nominal) as nominal_real, max(k.name) as category_name, max(s.destination_account) as destination_account, max(s.account_number) as account_number, max(b.name) as bank_name'), DB::raw('(CASE WHEN max(s.finance_app) = 1 THEN "Approved" ELSE CASE WHEN IFNULL(max(s.finance_app), 0) = 0 THEN "No Action" ELSE "Rejected" END END) as isAppFinance'), DB::raw('(CASE WHEN max(s.director_app) = 1 THEN "Approved" ELSE CASE WHEN IFNULL(max(s.director_app),0) = 0 THEN "No Action" ELSE "Rejected" END END) as isAppdirector'))
        // ->whereBetween('s.created_at', [$this->tgl_pertama, $this->tgl_terakhir]);
    ->where('s.company_code', auth()->user()->company_code)
     ->whereBetween(DB::raw('DATE(s.created_at)'), [$this->tgl_pertama, $this->tgl_terakhir]);
      // ->where('s.id', 32);


    if ($this->status == 1) {
      $data = $data->where([['finance_app', 1], ['director_app', 1]]);
    }elseif($this->status == 2){
     $data =  $data->where(function($query) {
      $query->where('finance_app', 2)
      ->orWhere('director_app', 2);
    });
   }

    if ($this->pjb == 1) {
      $data = $data->join('accountabilities', 's.submission_code', 'accountabilities.submission_code');
    }elseif($this->pjb == 2){
      $data = $data->leftJoin('accountabilities', 's.submission_code', 'accountabilities.submission_code')
      ->where('accountabilities.submission_code', null);
    }

   $data =  $data->groupBy('s.submission_code')->orderBy('rownum','ASC');
  
   $results = $data->get();

   return $results;
 }

 public function headings(): array
 {
   return ["No" , "Kode Pengajuan", "User Pemohon", "Pengajuan", "Tgl" , "Deskripsi", "Channel", "Estimasi", "Nominal Real", "Kategori", "Rekening Tujuan", "Nomor Rekeking", "Bank", "Keuangan", "director"];
 }

 public function styles(Worksheet $sheet)
 {
   return [
            // Style the first row as bold text.
    1    => ['font' => ['bold' => true]],
  ];
}

public function map($submission): array
{
 return [
  $submission->rownum,
  $submission->submission_code,
  $submission->username,
  $submission->title,
  Date::stringToExcel($submission->created_at),
  $submission->description,
  $submission->channel_name,
  $submission->estimated_price,
  $submission->nominal_real,
  $submission->category_name,
  $submission->destination_account,
  $submission->account_number,
  $submission->bank_name,
  $submission->isAppFinance,
  $submission->isAppdirector,
];
}

public function columnFormats(): array
{
 return [
  'E' => NumberFormat::FORMAT_DATE_DDMMYYYY,
  'H' => '"Rp "#,##0_-',
  'I' => '"Rp "#,##0_-',
];
}

public function bindValue(Cell $cell, $value)
{
 if (is_numeric($value)) {
  $cell->setValueExplicit($value, DataType::TYPE_NUMERIC);

  return true;
}
}

public function registerEvents(): array
{
  return [
    AfterSheet::class    => function(AfterSheet $event) {

      $event->sheet->getDelegate()->getStyle('A1:O1')
      ->getAlignment()
      ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            // $event->sheet->setCellValue('A10', 'Testing set');
    },
  ];
}

}