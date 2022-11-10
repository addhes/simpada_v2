@php
$required = (Str::contains($field['rules'], 'required')) ? "required" : "";
$required_mark = ($required != "") ? '<span class="text-danger"> <strong>*</strong> </span>' : '';
@endphp

<div class="form-group {{ $errors->has($field['name']) ? ' has-error' : '' }}">
    <div class="custom-control custom-checkbox">
        <input type='hidden' value='0' name='{{ $field['name'] }}'>
        {{ html()->checkbox($field['name'],old($field['name'], setting($field['name']))?true:false , \Illuminate\Support\Arr::get($field, 'value', '1'))->class('custom-control-input')->id($field['name']) }}
        {{ html()->label($field['name'])->class('custom-control-label')->for($field['name']) }}
        @if ($errors->has($field['name'])) <small class="help-block">{{ $errors->first($field['name']) }}</small> @endif
    </div>{!! $required_mark !!}
</div>
