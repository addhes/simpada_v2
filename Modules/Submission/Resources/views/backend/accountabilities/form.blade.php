<div class="row">
    <div class="col">
        <div class="form-group row">
            <label class="col-lg-2 col-form-label" for="date">Tanggal</label>
            <div class="col-lg-10">
                <input class="form-control" id="date" type="date" name="date"
                    value="{{$today ?? $$module_name_singular->date}}" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-lg-2 col-form-label" for="submission_code">Kode Pengajuan</label>
            <div class="col-lg-10">
                <select class="custom-select mb-2 select-submission" name="submission_code" id="submission_code"
                    required="true" {{ ($accountability->submission_code ?? '') == '' ? '' : 'disabled' }}>
                    <option value="" selected>Select item</option>
                    @foreach ($submissions as $item)
                    <option value="{{ $item->submission_code }}"
                        {{ $item->submission_code == ($accountability->submission_code ?? '') ? 'selected' : '' }}>
                        {{ $item->submission_code }} - {{ $item->title }}
                    </option>
                    @endforeach
                </select>
                @if ($errors->has('submission_code'))
                <span class="text-danger">{{ $errors->first('submission_code') }}</span>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <label class="col-lg-2 col-form-label" for="description">Deskripsi</label>
            <div class="col-lg-10">
                <textarea class="form-control" name="description" id="description" name="description" cols="30" rows="5"
                    placeholder="Berikut adalah rincian dari pengeluaran dana . . ."> {{ $accountability->description ?? '' }} </textarea>
                @if ($errors->has('description'))
                <span class="text-danger">{{ $errors->first('description') }}</span>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <label class="col-lg-2 col-form-label" for="accountability_attachment">Attachment</label>
            <div class="col-lg-10">
                <input type="file" class="form-control" id="accountability_attachment" name="accountability_attachment"
                {{ ($accountability->accountability_attachment ?? '') == '' ? 'required' : '' }}>
                @if ($errors->has('accountability_attachment'))
                <span class="text-danger">{{ $errors->first('accountability_attachment') }}</span>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <label class="col-lg-2 col-form-label" for="accountability_attachment"></label>
            <div class="col-lg-10">
                <div class="btn-group">
                    @if($module_action == 'Edit')
                    <a href="{{ asset('storage/accountability-attachment/'.$accountability->accountability_attachment ) }}"
                        target="_blank"
                        class="{{ ($accountability->accountability_attachment ?? '') == '' ? 'a-disabled' : '' }}"
                        aria-haspopup="true" aria-expanded="false"> <i class='uil uil-file-alt mr-1'></i>Lihat
                        Attachment</a>
                    @endif
                </div>
            </div>
        </div>

        <div>
            <div class="well clearfix">
                <a href="#" class="btn btn-primary pull-right add-record" data-added="0"><i class='uil uil-plus'></i>
                    Add Detail</a>
            </div>
            <br>
            <div class="table-responsive">
                <table class="table table-bordered tbl" id="tbl_posts">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tanggal</th>
                            <th>Keterangan</th>
                            <th>Nominal</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="tbl_posts_body">
                        @if(count($accountabilitydetail ?? []) > 0)
                        @foreach($accountabilitydetail ?? [] as $index => $item)
                        @php
                        $index += 1;
                        @endphp
                        <tr id="rec-{{ $index }}">
                            <td><span class="sn">{{ $index }}</span>.</td>
                            <td>
                                <input class="form-control" type="date" required="true" id="datedetail[]"
                                    name="datedetail[]" value="{{ $item->date }}">
                            </td>
                            <td>
                                <input type="text" required="true" class="form-control input-detail"
                                    id="descriptiondetail[]" name="descriptiondetail[]" value="{{ $item->description }}"
                                    placeholder="Biaya Admin">
                            </td>
                            <td>
                                <input type="text" required="true" class="form-control input-element nominal-detail"
                                    onkeyup="myfunction()" name="nominal[]" value="@currency($item->nominal)"
                                    placeholder="16.000">
                            </td>
                            <td class="text-center"><a href="#" class="btn btn-xs delete-record btn-danger"
                                    data-id="{{ $index }}"><i class="uil uil-minus"></i></a></td>
                        </tr>
                        @endforeach
                        @else
                        <tr id="rec-">
                            <td><span class="sn">1</span>.</td>
                            <td>
                                <input class="form-control" type="date" required="true" id="datedetail[]"
                                    name="datedetail[]">
                            </td>
                            <td>
                                <input type="text" required="true" class="form-control input-detail"
                                    id="descriptiondetail[]" name="descriptiondetail[]" placeholder="Biaya Admin">
                            </td>
                            <td>
                                <input type="text" required="true" class="form-control input-element nominal-detail"
                                    onkeyup="myfunction()" name="nominal[]" placeholder="16.000">
                            </td>
                            <td class="text-center"><a href="#" class="btn btn-xs delete-record btn-danger"
                                    data-id="0"><i class="uil uil-minus"></i></a></td>
                        </tr>
                        @endif
                    </tbody>
                    <tfoot>
                        <tr id="rec-1">
                            <td></td>
                            <td></td>
                            <td class="font-weight-bold">Total</td>
                            <td> <input type="text" class="form-control input-element input-total" id="total"
                                    name="total" placeholder="" value="" readonly></td>
                            <td></td>
                        </tr>
                        <tr id="">
                            <td><span class="sn"></span></td>
                            <td></td>
                            <td>
                                <label class="font-weight-bold">Estimasi</label>
                            </td>
                            <td>
                                <input type="text" class="form-control input-element" id="estimasi" name="estimasi"
                                    value="@currency($submission->estimated_price ?? 0)" disabled>
                            </td>
                        </tr>
                        <tr id="">
                            <td><span class="sn"></span></td>
                            <td></td>
                            <td>
                                <label class="font-weight-bold">Selisih</label>
                            </td>
                            <td class="selisih-ket">
                                <input type="text" class="form-control input-element selisih" id="selisih"
                                    name="selisih" value="" disabled>
                                <label class="font-weight-bold text-warning" id="desc-selisih"></label>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<table id="sample_table" style="display:none;">
    <tr id="">
        <td><span class="sn"></span>.</td>
        <td><input class="form-control" type="date" id="datedetail[]" name="datedetail[]"></td>
        <td> <input type="text" class="form-control" id="descriptiondetail[]" name="descriptiondetail[]"></td>
        <td><input type="text" class="form-control input-element nominal-detail" onkeyup="myfunction()"
                name="nominal[]"></td>
        <td class="text-center"><a href="#" class="btn btn-xs delete-record btn-danger" data-id="0"><i
                    class="uil uil-minus"></i></a></td>
    </tr>
</table>