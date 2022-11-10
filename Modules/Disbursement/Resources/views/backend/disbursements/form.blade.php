<div class="form-group row">
    <label class="col-lg-2 col-form-label" for="date">Tanggal</label>
    <div class="col-lg-10">
        <input class="form-control" id="date" type="date" name="date" value="{{$today}}" readonly>
    </div>
</div>
<div class="form-group row">
    <label class="col-lg-2 col-form-label" for="title">Pengeluaran <span class="wajib">*</span></label>
    <div class="col-lg-10">
        <input type="text" class="form-control" id="title" name="title" placeholder="Cth: Tagihan Internet . . ."
            value="{{ $$module_name_singular->title ?? '' }}" required>
        @if ($errors->has('title'))
        <span class="text-danger">{{ $errors->first('title') }}</span>
        @endif
    </div>
</div>
<div class="form-group row">
    <label class="col-lg-2 col-form-label" for="description">Deskripsi <span class="wajib">*</span></label>
    <div class="col-lg-10">
        <input type="text" class="form-control" id="description" name="description"
            placeholder="Untuk pembayaran bulan . . ." value="{{ $$module_name_singular->description ?? '' }}" required>
        @if ($errors->has('description'))
        <span class="text-danger">{{ $errors->first('description') }}</span>
        @endif
    </div>
</div>
<div class="form-group row">
    <label class="col-lg-2 col-form-label" for="nominal">Nominal <span class="wajib">*</span></label>
    <div class="col-lg-10">
        @if ($errors->has('nominal'))
        <span class="text-danger">{{ $errors->first('nominal') }}</span>
        @endif
        <div>
            <div class="well clearfix add-detail">
                <a href="#" class="btn btn-primary pull-right add-record" data-added="0"><i
                        class='uil uil-plus'></i> Add Detail</a>
            </div>
            <br>

            <div class="table-responsive">
                <table class="table table-bordered" id="tbl_posts">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Keterangan</th>
                            <th>Nominal</th>
                            <th class="action">Action</th>
                        </tr>
                    </thead>
                    <tbody id="tbl_posts_body">
                        @if(count($disbursementdetail ?? []) > 0)
                        @foreach($disbursementdetail ?? [] as $index => $item)
                        @php
                            $index += 1;
                        @endphp
                        <tr id="rec-{{ $index }}">
                            <td><span class="sn">{{ $index }}</span>.</td>
                            <td>
                                <input type="text" class="form-control input-detail" id="descriptiondetail[]"
                                    name="descriptiondetail[]" placeholder="Biaya Admin"
                                    value="{{ $item->description }}" required>
                            </td>
                            <td>
                                <input type="text" class="form-control input-element nominal-detail"
                                    onkeyup="myfunction()" name="nominal[]" placeholder="16.000"
                                    value="{{ $item->nominal }}" required>
                            </td>
                            <td class="text-center action"><a href="#" class="btn btn-xs delete-record btn-danger"
                                    data-id="{{ $index }}"><i class="uil uil-minus"></i></a></td>
                        </tr>
                        @endforeach
                        @else
                        <tr id="rec-1">
                            <td><span class="sn">1</span>.</td>
                            <td>
                                <input type="text" class="form-control input-detail" id="descriptiondetail[]"
                                    name="descriptiondetail[]" placeholder="Biaya Admin" required>
                            </td>
                            <td>
                                <input type="text" class="form-control input-element nominal-detail"
                                    onkeyup="myfunction()" name="nominal[]" placeholder="16.000" required>
                            </td>
                            <td class="text-center action"><a class="btn btn-xs delete-record" data-id="1"><i
                                        class="glyphicon glyphicon-trash"></i></a></td>
                        </tr>
                        @endif
                    </tbody>
                    <tfoot>
                        <tr id="rec-1">
                            <td></td>
                            <td class="font-weight-bold">Total</td>
                            <td> <input type="text" class="form-control input-element input-total" id="total"
                                    name="total" placeholder="" readonly></td>
                            <td></td>
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
        <td> <input type="text" class="form-control" id="descriptiondetail[]" name="descriptiondetail[]"></td>
        <td><input type="text" class="form-control input-element nominal-detail" onkeyup="myfunction()"
                name="nominal[]"></td>
        <td class="text-center action"><a href="#" class="btn btn-xs delete-record btn-danger" data-id="0"><i
                    class="uil uil-minus"></i></a></td>
    </tr>
</table>