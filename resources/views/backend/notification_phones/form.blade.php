                <!-- Row 1 -->
                <div class="row mt-4">
                    <div class="col md-4">
                        <div class="form-group">
                            {{ html()->label('Company')->class('form-control-label')->for('company_code') }}
                            <select class="form-control company_code" name="company_code" id="company_code">
                                @foreach($companies as $item)
                                <option value="{{ $item->param_key }}" {{ $item->param_key == ($$module_name_singular->company_code ?? '') ? 'selected' : '' }}>{{ $item->param_text }}</option>
                                @endforeach
                            </select>

                        </div>
                        <br>
                    </div>

                    <div class="col md-4">
                        <div class="form-group">
                            {{ html()->label('Category')->class('form-control-label')->for('category_code') }}
                            <select class="form-control category_code" name="category_code" id="category_code">
                                @foreach($categories as $item)
                                <option value="{{ $item->param_key }}" {{ $item->param_key == ($$module_name_singular->category_code ?? '') ? 'selected' : '' }}>{{ $item->param_text }}</option>
                                @endforeach
                            </select>
                        </div>
                        <br>
                    </div>

                    <div class="col md-4">
                        <div class="form-group">
                            {{ html()->label('User')->class('form-control-label')->for('user_id') }}
                            <select class="form-control user_id" name="user_id" id="user_id">
                                @foreach($users as $item)
                                <option value="{{ $item->id }}" {{ $item->id == ($$module_name_singular->user_id ?? 1) ? 'selected' : '' }}>{{ $item->name }} <span class="font-weight-bold">({{ $item->company_code }})</span></option>
                                @endforeach
                            </select>
                        </div>
                        <br>
                    </div>
                </div>


                <script>
$(document).ready(function() {
    $('.user_id').select2();
});
                </script>