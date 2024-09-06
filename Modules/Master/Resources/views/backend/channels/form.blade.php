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

<div class="form-group row">
    <label class="col-lg-2 col-form-label" for="text">Type</label>
    <div class="col-lg-10">
        <div class="form-check form-check-inline">
            @if (!empty($$module_name_singular->is_wp))
                <input class="form-check-input" type="checkbox" id="is_wp_ada1" name="is_wp" value="{{ !empty($$module_name_singular->is_wp) }}" {{ $$module_name_singular->is_wp == 1 ? 'checked' : '' }}>
            @else
                <input class="form-check-input" type="checkbox" id="is_wp1" name="is_wp" value="1">
                <input type="hidden" name="is_wp" id="is_wp0" value="0">
            @endif

            <label class="form-check-label" for="inlineCheckbox1">WP</label>
          </div>
          <div class="form-check form-check-inline">
            @if (!empty($$module_name_singular->is_bhk))
                <input class="form-check-input" type="checkbox" id="is_bhk_ada1" name="is_bhk" value="{{ !empty($$module_name_singular->is_bhk) }}" {{ $$module_name_singular->is_bhk == 1 ? 'checked' : '' }}>

            @else
            <input class="form-check-input" type="checkbox" id="is_bhk1" name="is_bhk" value="1">
            <input type="hidden" name="is_bhk" id="is_bhk0" value="0">
            @endif
            <label class="form-check-label" for="inlineCheckbox2">BHK</label>
          </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {


    $('#is_wp1').change(function() {
        if ($(this).is(':checked')) {
            $('#is_wp0').val('1');
        } else {
            $('#is_wp0').val('0');
        }

        console.log($('#is_wp0').val())
    });

    $('#is_bhk1').change(function() {
        if ($(this).is(':checked')) {
            $('#is_bhk0').val('1');
        } else {
            $('#is_bhk0').val('0');
        }

        console.log($('#is_bhk0').val())
        // console.log($('#is_bhk1').val())
    });

});
</script>
