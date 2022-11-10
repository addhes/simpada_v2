<?php

namespace App\Exports;
use Illuminate\Support\Facades\DB;
use Modules\Submission\Entities\Submission;
use Modules\Submission\Entities\SubmissionDetail;
use Modules\Submission\Entities\Accountability;
use Modules\Submission\Entities\AccountabilityDetail;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class PerSubmissionExport implements FromView, WithStyles, ShouldAutoSize, WithColumnFormatting
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public $resdetail;
    function __construct($id) {
        $this->id = $id;
    }
    
    public function view(): View
    {
        DB::statement(DB::raw('set @rownum=0'));
        $data = DB::table('submissions as s')
        ->join('users as u', 's.user_id', '=', 'u.id')
        ->join('channels as c', 's.channel_id', '=', 'c.id')
        ->join('banks as b', 's.bank_id', '=', 'b.id')
        ->join('categories as k', 's.category_id', '=', 'k.id')
        ->select(DB::raw("@rownum  := @rownum  + 1 AS rownum"), 's.submission_code', 'u.name as username', 's.title', DB::raw('CAST(s.created_at AS date) as created_at') , 's.description', 'c.name as channel_name', 's.estimated_price', 'k.name as category_name', 's.destination_account', 's.account_number', 'b.name as bank_name', DB::raw('(CASE WHEN s.finance_app = 1 THEN "Approved" ELSE "Rejected" END) as isAppFinance'), DB::raw('(CASE WHEN s.director_app = 1 THEN "Approved" ELSE "Rejected" END) as isAppdirector'), 's.last_balance')
        ->where('s.id', $this->id);
        $results = $data->first();

        $accountability = Accountability::where('submission_code', $results->submission_code)->first();
        $this->resdetail = AccountabilityDetail::where('accountability_code', $accountability->accountability_code)->get();

        return view('export.report.persubmission', [
            'submission' => $results, 'accountability' => $accountability, 'accountabilitydetail' => $this->resdetail
        ]);
    }

    public function columnFormats(): array
    {
       return [
        // 'E' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        'H' => '"Rp "#,##0_-',
        'L' => '"Rp "#,##0_-',
    ];
}

public function styles(Worksheet $sheet)
{
    $sheet->getStyle('A1')->getFont()->setBold(true);

    $sheet->getStyle('A1:M3')->applyFromArray([
        'borders' => [
            'allBorders' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                'color' => ['argb' => '000000'],
            ],
        ],
    ]);

    $sheet->getStyle('A10:D'.(count($this->resdetail) + 11))->applyFromArray([
        'borders' => [
            'allBorders' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                'color' => ['argb' => '000000'],
            ],
        ],
    ]);

    $sheet->getStyle('D11:D'.(count($this->resdetail) + 11))->getNumberFormat()
    ->setFormatCode('Rp '.'#,##0');

    $sheet->getStyle('C'.(count($this->resdetail) + 13).':C'.(count($this->resdetail) + 13 + 5))->getNumberFormat()
    ->setFormatCode('Rp '.'#,##0');
}
}