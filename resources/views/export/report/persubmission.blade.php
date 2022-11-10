<link href="{{ URL::asset('assets/test.css') }}" rel="stylesheet" type="text/css" />

<body>
    <table class="table pengajuan" style="border:1px;">
        <thead>
            <tr style="background-color: #000000;">
                <td colspan=13 style="text-align: center;" class="cell">Pengajuan</td>
            </tr>
            <tr class="cell">
                <th><b>Kode Pengajuan</b></th>
                <th><b>User</b></th>
                <th><b>Pengajuan</b></th>
                <th><b>Kategori</b></th>
                <th><b>Tgl</b></th>
                <th><b>Deskripsi</b></th>
                <th><b>Channel</b></th>
                <th><b>Estimasi Harga</b></th>
                <th><b>Bank</b></th>
                <th><b>Rekening</b></th>
                <th><b>Nomor Rekening</b></th>
                <th><b>Saldo Terakhir</b></th>
                <th><b>Status</b></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $submission->submission_code }}</td>
                <td>{{ $submission->username }}</td>
                <td>{{ $submission->title }}</td>
                <td>{{ $submission->category_name }}</td>
                <td>{{ date('d-m-Y',strtotime($submission->created_at)) }}</td>
                <td>{{ $submission->description }}</td>
                <td>{{ $submission->channel_name }}</td>
                <td>{{ $submission->estimated_price }}</td>
                <td>{{ $submission->bank_name }}</td>
                <td>{{ $submission->destination_account }}</td>
                <td>{{ $submission->account_number }}</td>
                <td>{{ $submission->last_balance }}</td>
                <td>{{ $submission->isAppdirector }}</td>
            </tr>
        </tbody>
    </table>

    <table class="table pj-header">
        <thead>
            <tr>
                <td colspan=4 style="text-align: center; font-weight: bold;">Pertanggung Jawaban</td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><b>Tanggal</b></td>
                <td>:</td>
                <td>{{ date('d-m-Y',strtotime($accountability->date)) }}</td>
            </tr>
            <tr>
                <td><b>Kode Pengajuan</b></td>
                <td>:</td>
                <td>{{ $accountability->submission_code }}</td>
            </tr>
            <tr>
                <td><b>Deskripsi</b></td>
                <td>:</td>
                <td>{{ $accountability->description }}</td>
            </tr>
        </tbody>
    </table>

    <table class="table pj-detail">
        <thead>
            <tr>
                <th style="text-align: center; font-weight: bold;">No</th>
                <th style="text-align: center; font-weight: bold;">Tanggal</th>
                <th style="text-align: center; font-weight: bold;">Jenis Biaya</th>
                <th style="text-align: center; font-weight: bold;">Nominal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($accountabilitydetail as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->date }}</td>
                <td>{{ $item->description }}</td>
                <td>{{ $item->nominal }}</td>
            </tr>
            @endforeach
            <tr>
                <td colspan=2></td>
                <td>Total</td>
                <td>{{ $accountabilitydetail->sum('nominal') }}</td>
            </tr>
        </tbody>
    </table>

    <table class="table pj-result">
        <tbody>
            <tr>
                <td><b>Estimasi</b></td>
                <td>:</td>
                <td>{{ $submission->estimated_price }}</td>
            </tr>
            <tr>
                <td><b>Total Pengeluaran</b></td>
                <td>:</td>
                <td>{{ $accountabilitydetail->sum('nominal') }}</td>
            </tr>
            <tr>
                <td><b>Selisih</b></td>
                <td>:</td>
                <td>{{ $submission->estimated_price - $accountabilitydetail->sum('nominal') }}</td>
            </tr>
            <tr></tr>
            <tr>
                <td>
                    @if($submission->estimated_price > $accountabilitydetail->sum('nominal'))
                    <b>Anggaran Lebih</b>
                    @elseif($submission->estimated_price < $accountabilitydetail->sum('nominal'))
                        <b>Anggaran Kurang</b>
                        @else
                        <b>Anggaran Sesuai</b>
                        @endif
                </td>
                <td>:</td>
                <td>{{ abs($submission->estimated_price - $accountabilitydetail->sum('nominal')) }}</td>
            </tr>
        </tbody>
    </table>