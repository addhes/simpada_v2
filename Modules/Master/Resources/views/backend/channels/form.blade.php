<div class="form-group row">
    <label class="col-lg-2 col-form-label" for="name">Channel</label>
    <div class="col-lg-10">
        <input type="text" class="form-control" id="name" name="name" placeholder="Channel Name" value="{{ $$module_name_singular->name ?? '' }}">
        @if ($errors->has('name'))
        <span class="text-danger">{{ $errors->first('name') }}</span>
        @endif
    </div>
</div>
<div class="form-group row">
    <label class="col-lg-2 col-form-label" for="channel_id">ID</label>
    <div class="col-lg-10">
        <input type="text" class="form-control" id="channel_id" name="channel_id" placeholder="Channel ID" value="{{ $$module_name_singular->channel_id ?? '' }}">
        @if ($errors->has('channel_id'))
        <span class="text-danger">{{ $errors->first('channel_id') }}</span>
        @endif
    </div>
</div>
<div class="form-group row">
    <label class="col-lg-2 col-form-label" for="pic">PIC</label>
    <div class="col-lg-10">
        <input type="text" class="form-control" id="pic" name="pic" placeholder="PIC Name" value="{{ $$module_name_singular->pic ?? '' }}">
    </div>
</div>
<div class="form-group row">
    <label class="col-lg-2 col-form-label" for="Email">Email</label>
    <div class="col-lg-10">
        <input type="email" id="email" name="email" class="form-control" placeholder="Email" value="{{ $$module_name_singular->email ?? '' }}">
    </div>
</div>