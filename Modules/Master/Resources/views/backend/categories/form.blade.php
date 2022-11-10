<div class="form-group row">
    <label class="col-lg-2 col-form-label" for="name">Kategori</label>
    <div class="col-lg-10">
        <input type="text" class="form-control" id="name" name="name" placeholder="Category Name"
            value="{{ $$module_name_singular->name ?? '' }}">
        @if ($errors->has('name'))
        <span class="text-danger">{{ $errors->first('name') }}</span>
        @endif
    </div>
</div>
<div class="form-group row">
    <label class="col-lg-2 col-form-label" for="description">Keterangan</label>
    <div class="col-lg-10">
        <textarea class="form-control" name="description" id="description" placeholder="Description"
            value="{{ $$module_name_singular->description ?? '' }}">
        </textarea>
        @if ($errors->has('description'))
        <span class="text-danger">{{ $errors->first('description') }}</span>
        @endif
    </div>
</div>