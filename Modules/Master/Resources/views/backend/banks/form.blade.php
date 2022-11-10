<div class="row">
    <div class="col">
        <div class="form-group row">
            <label class="col-lg-2 col-form-label" for="name">Bank</label>
            <div class="col-lg-10">
                <input type="text" class="form-control" id="name" name="name" placeholder="Bank Name" value="{{ $$module_name_singular->name ?? '' }}">
                @if ($errors->has('name'))
                <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
            </div>
        </div>
    </div>
</div>