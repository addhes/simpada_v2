<table class="table table-responsive  tbl-show">
    <tr>
        <td>User</td>
        <td>:</td>
        <td>{{ $$module_name_singular->name }}</td>
    </tr>
    <tr>
        <td>Pengajuan</td>
        <td>:</td>
        <td>{{ $$module_name_singular->title }}</td>
    </tr>
    <tr>
        <td>Deskripsi</td>
        <td>:</td>
        <td>{{ $$module_name_singular->description }}</td>
    </tr>
    <tr>
        <td>Category</td>
        <td>:</td>
        <td>{{ $$module_name_singular->category }}</td>
    </tr>
    <tr>
        <td>Channel</td>
        <td>:</td>
        <td>{{ $$module_name_singular->channel }}</td>
    </tr>
    <tr>
        <td>Bank Tujuan</td>
        <td>:</td>
        <td>{{ $$module_name_singular->bank }}</td>
    </tr>
    <tr>
        <td>Rekening Tujuan</td>
        <td>:</td>
        <td>{{ $$module_name_singular->destination_account }}</td>
    </tr>
    <tr>
        <td>Nomor Rekening Tujuan</td>
        <td>:</td>
        <td>{{ $$module_name_singular->account_number }}</td>
    </tr>
    <tr>
        <td>Attachment</td>
        <td>:</td>
        <td>
            <div class="btn-group">
                @if($submission->user_attachment == '')
                <p class="font-italic text-danger">Attachment tidak ada.</p>
                @else
                <a href="{{ asset ('storage/user-attachment/'.$submission->user_attachment) }}" target="_blank"
                    class="{{ $submission->user_attachment == '' ? 'a-disabled' : '' }}" aria-haspopup="true"
                    aria-expanded="false">
                    <i class='uil uil-file-alt mr-1'></i>Unduh</a>
                @endif
            </div>
        </td>
    </tr>
</table>

<hr>
<div class="container-fluid" style="padding: auto;">
    <strong>ESTIMASI BIAYA </strong>
</div>

<table class="table table-responsive" id="tbl_posts">
    <thead>
        <tr>
            <th>#</th>
            <th>Keterangan</th>
            <th>Nominal</th>
        </tr>
    </thead>
    <tbody id="tbl_posts_body">
        @foreach($submissiondetail ?? [] as $index => $item)
        @php
        $index += 1;
        @endphp
        <tr id="rec-{{ $index }}">
            <td><span class="sn">{{ $index }}</span>.</td>
            <td>
                {{ $item->description}}
            </td>
            <td>
                Rp. @currency($item->nominal)
            </td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr id="rec-1">
            <td></td>
            <td class="font-weight-bold">Total Estimasi</td>
            <td> Rp. @currency($total) </td>
            <td></td>
        </tr>
    </tfoot>
</table>

@if($status['status'] == 'Finish')
<div class="form-group row">
    <div class="col-lg-12">
        <div class="btn-group">
            <a href="{{ asset ('storage/finance-attachment/'.$submission->finance_attachment) }}" target="_blank"
                class="{{ $submission->finance_attachment == '' ? 'a-disabled' : '' }}" aria-haspopup="true"
                aria-expanded="false">
                <i class='uil uil-file-alt mr-1'></i>Download Attachment Keuangan</a>
        </div>
        @if($submission->finance_attachment == '')
        <p class="font-italic text-danger">Attachment belum ada.</p>
        @endif
    </div>
</div>
@endif