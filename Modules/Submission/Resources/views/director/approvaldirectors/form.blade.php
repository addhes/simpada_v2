<table class="table table-responsive table-borderless tbl-show">
    <tr>
        <td class="font-weight-bold">User</td>
        <td>:</td>
        <td>{{ $$module_name_singular->name }}</td>
    </tr>
    <tr>
        <td class="font-weight-bold">Kode Pengajuan</td>
        <td>:</td>
        <td>{{ $$module_name_singular->submission_code }}</td>
    </tr>
    <tr>
        <td class="font-weight-bold">Pengajuan</td>
        <td>:</td>
        <td>{{ $$module_name_singular->title }}</td>
    </tr>
    <tr>
        <td class="font-weight-bold">Deskripsi</td>
        <td>:</td>
        <td>{{ $$module_name_singular->description }}</td>
    </tr>
    <tr>
        <td class="font-weight-bold">Category</td>
        <td>:</td>
        <td>{{ $$module_name_singular->category }}</td>
    </tr>
    <tr>
        <td class="font-weight-bold">Channel</td>
        <td>:</td>
        <td>{{ $$module_name_singular->channel }}</td>
    </tr>
    <tr>
        <td class="font-weight-bold">Bank Tujuan</td>
        <td>:</td>
        <td>{{ $$module_name_singular->bank }}</td>
    </tr>
    <tr>
        <td class="font-weight-bold">Rekening Tujuan</td>
        <td>:</td>
        <td>{{ $$module_name_singular->destination_account }}</td>
    </tr>
    <tr>
        <td class="font-weight-bold">Nomor Rekening Tujuan</td>
        <td>:</td>
        <td>{{ $$module_name_singular->account_number }}</td>
    </tr>
    <tr>
        <td class="font-weight-bold">Attachment</td>
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
<hr>

<div class="form-group row">
    <div class="col-lg-12">
        <label class="form-label font-weight-bold" for="finance_desc">Deskripsi Keuangan <span
                class="wajib">*</span></label>
        <textarea class="form-control" id="finance_desc" name="finance_desc" placeholder="Acc Keuangan" disabled>{{ $$module_name_singular->finance_desc ?? '' }}</textarea>
        @if ($errors->has('finance_desc'))
        <span class="text-danger">{{ $errors->first('finance_desc') }}</span>
        @endif
    </div>
</div>


<div class="form-group row {{ $$module_name_singular->director_desc == '' && $module_action == 'Show' ? 'd-none' :  '' }}">
    <div class="col-lg-12">
        <label class="form-label" for="director_desc">Deskripsi Direktur <span class="wajib">*</span></label>
        <textarea class="form-control" id="director_desc" name="director_desc"
            placeholder="Acc" {{ $module_action == 'Show' ? 'disabled' :  '' }}>{{ $$module_name_singular->director_desc ?? '' }}</textarea>
        @if ($errors->has('director_desc'))
        <span class="text-danger">{{ $errors->first('director_desc') }}</span>
        @endif
    </div>
</div>

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

<div class="form-group row">
    <div class="col-lg-12">
        <!-- <label class="form-label" for="description">Saldo Terakhir</label> -->
        <input type="hidden" class="form-control input-element" name="last_balance" placeholder="Rp 150.000"
            value="@currency($last_balance)" readonly>
        @if ($errors->has('last_balance'))
        <span class="text-danger">{{ $errors->first('last_balance') }}</span>
        @endif
    </div>
</div>