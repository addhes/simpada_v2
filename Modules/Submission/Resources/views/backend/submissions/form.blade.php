<div class="row">
    <div class="col">
        <div class="form-group row">
            <label class="col-lg-2 col-form-label" for="title">Pengajuan <span class="wajib">*</span></label>
            <div class="col-lg-10">
                <input type="text" class="form-control" id="title" name="title"
                    placeholder="Cth: Harddisk Eksternal 1 TB" value="{{ $$module_name_singular->title ?? '' }}"
                    required>
                @if ($errors->has('title'))
                <span class="text-danger">{{ $errors->first('title') }}</span>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <label class="col-lg-2 col-form-label" for="description">Deskripsi <span class="wajib">*</span></label>
            <div class="col-lg-10">
                <textarea class="form-control" id="description" name="description"
                    placeholder="Kami membutuhkan HDD tersebut untuk keperluan . . .">{{ $$module_name_singular->description ?? '' }}</textarea>
                @if ($errors->has('description'))
                <span class="text-danger">{{ $errors->first('description') }}</span>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <label class="col-lg-2 col-form-label" for="category_id">Kategori</label>
            <div class="col-lg-10">
                <select class="custom-select mb-2" name="category_id" id="category_id">
                    <option value="6" selected>Select item</option>
                    @foreach ($category as $item)
                    <option value="{{ $item->id }}"
                        {{ $item->id == ($$module_name_singular->category_id ?? '') ? 'selected' : '' }}>
                        {{ $item->name }}
                    </option>
                    @endforeach
                </select>
                @if ($errors->has('category_id'))
                <span class="text-danger">{{ $errors->first('category_id') }}</span>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <label class="col-lg-2 col-form-label" for="channel_id">Kebutuhan Channel</label>
            <div class="col-lg-10">
                <select class="custom-select mb-2" name="channel_id" id="channel_id">
                    <option value="0" selected>Select item</option>
                    @foreach ($channel as $item)
                    <option value="{{ $item->id }}"
                        {{ $item->id == ($$module_name_singular->channel_id ?? '') ? 'selected' : '' }}>
                        {{ $item->name }}
                    </option>
                    @endforeach
                </select>
                @if ($errors->has('channel_id'))
                <span class="text-danger">{{ $errors->first('channel_id') }}</span>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <label class="col-lg-2 col-form-label" for="estimated_price">Estimasi Dana <span
                    class="wajib">*</span></label>
            <div class="col-lg-10">
                @if ($errors->has('estimated_price'))
                <span class="text-danger">{{ $errors->first('estimated_price') }}</span>
                @endif
                <div>
                    <div class="well clearfix">
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
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="tbl_posts_body">
                                @if(count($submissiondetail ?? []) > 0)
                                @foreach($submissiondetail ?? [] as $index => $item)
                                @php
                                $index += 1;
                                @endphp
                                <tr id="rec-{{ $index }}">
                                    <td><span class="sn">{{ $index }}</span>.</td>
                                    <td>
                                        <input type="text" required="true" class="form-control input-detail"
                                            id="descriptiondetail[]" name="descriptiondetail[]"
                                            placeholder="Biaya Admin" value="{{ $item->description}}">
                                    </td>
                                    <td>
                                        <input type="text" required="true"
                                            class="form-control input-element nominal-detail" onkeyup="myfunction()"
                                            name="nominal[]" placeholder="16.000" value="{{ $item->nominal }}">
                                    </td>

                                    <td class="text-center action"><a href="#"
                                            class="btn btn-xs delete-record btn-danger" data-id="{{ $index }}"><i
                                                class="uil uil-minus"></i></a></td>
                                </tr>
                                @endforeach
                                @else
                                <tr id="rec-1">
                                    <td><span class="sn">1</span>.</td>
                                    <td>
                                        <input type="text" required="true" class="form-control input-detail"
                                            id="descriptiondetail[]" name="descriptiondetail[]"
                                            placeholder="Biaya Admin">
                                    </td>
                                    <td>
                                        <input type="text" required="true"
                                            class="form-control input-element nominal-detail" onkeyup="myfunction()"
                                            name="nominal[]" placeholder="16.000">
                                    </td>
                                    <td><a class="btn btn-xs delete-record" data-id="1"><i
                                                class="glyphicon glyphicon-trash"></i></a></td>
                                </tr>
                                @endif

                            </tbody>
                            <tfoot>
                                <tr id="rec-1">
                                    <td></td>
                                    <td class="font-weight-bold">Total Estimasi</td>
                                    <td> <input type="text" class="form-control input-element input-total" id="total"
                                            name="estimated_price" placeholder="" readonly></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-lg-2 col-form-label" for="bank_id">Bank Tujuan <span class="wajib">*</span></label>
            <div class="col-lg-10">
                <select class="custom-select mb-2" name="bank_id" id="bank_id" required>
                    <option value="" selected>Select item</option>
                    @foreach ($bank as $item)
                    <option value="{{ $item->id }}"
                        {{ $item->id == ($$module_name_singular->bank_id ?? '') ? 'selected' : '' }}>
                        {{ $item->name }}
                    </option>
                    @endforeach
                </select>
                @if ($errors->has('bank_id'))
                <span class="text-danger">{{ $errors->first('bank_id') }}</span>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <label class="col-lg-2 col-form-label" for="destination_account">Rekening Tujuan <span
                    class="wajib">*</span></label>
            <div class="col-lg-10">
                <input type="text" id="destination_account" name="destination_account" class="form-control"
                    placeholder="Rudi Hartono" value="{{ $$module_name_singular->destination_account ?? '' }}" required>
                @if ($errors->has('destination_account'))
                <span class="text-danger">{{ $errors->first('destination_account') }}</span>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <label class="col-lg-2 col-form-label" for="account_number">Nomor Rekening Tujuan <span
                    class="wajib">*</span></label>
            <div class="col-lg-10">
                <input type="text" class="form-control" id="account_number" name="account_number"
                    placeholder="500001015" value="{{ $$module_name_singular->account_number ?? '' }}" required>
                @if ($errors->has('account_number'))
                <span class="text-danger">{{ $errors->first('account_number') }}</span>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <label class="col-lg-2 col-form-label" for="user_attachment">Attachment <span
                    class="wajib">*</span></label>
            <div class="col-lg-10">
                <input type="file" class="form-control" id="user_attachment" name="user_attachment" required>
                @if ($errors->has('user_attachment'))
                <span class="text-danger">{{ $errors->first('user_attachment') }}</span>
                @endif
            </div>
        </div>
        @if($module_action !== 'Add')
        <div class="form-group row">
            <label class="col-lg-2 col-form-label" for="user_attachment"></label>
            <div class="col-lg-10">
                <div class="btn-group">
                    @if (Storage::exists('public/user-attachment/'.$$module_name_singular->user_attachment))
                                    <a href="{{ asset ('storage/user-attachment/'.$submission->user_attachment) }}"
                                        target="_blank" class=" btn btn-primary {{ $submission->user_attachment == '' ? 'a-disabled' : '' }}"
                                        aria-haspopup="true" aria-expanded="false">
                                        <i class='uil uil-file-alt mr-1'></i>Lihat Attachment</a>
                                    @else
                                    <a href="{{ $submission->user_attachment }}"
                                        target="_blank" class=" btn btn-primary {{ $submission->user_attachment == '' ? 'a-disabled' : '' }}"
                                        aria-haspopup="true" aria-expanded="false">
                                        <i class='uil uil-file-alt mr-1'></i>Lihat Attachment</a>
                                    @endif
                    {{-- <a href="{{ asset('storage/user-attachment/'.($submission->user_attachment ?? '')) }}" target="_blank"
                        class="btn btn-primary {{ ($submission->user_attachment ?? '') == '' ? 'disabled' : '' }}"
                        aria-haspopup="true" aria-expanded="false">
                        <i class='uil uil-file-alt mr-1'></i>Lihat Attachment</a> --}}
                </div>
                @if(($submission->user_attachment ?? '') == '')
                <p class="font-italic text-danger">Attachment belum ada.</p>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

<table id="sample_table" style="display:none;">
    <tr id="">
        <td><span class="sn"></span>.</td>
        <td> <input type="text" class="form-control" id="descriptiondetail[]" name="descriptiondetail[]"></td>
        <td><input type="text" class="form-control input-element nominal-detail" onkeyup="myfunction()"
                name="nominal[]"></td>
        <td class="text-center"><a href="#" class="btn btn-xs delete-record btn-danger" data-id="0"><i
                    class="uil uil-minus"></i></a></td>
    </tr>
</table>

