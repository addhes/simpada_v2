@props(["route"=>"", "icon"=>"fas fa-desktop", "title", "small"=>"", "class"=>"", "dataid"=>"", "datatype"=>"", "id"=>"", "color"=>"btn-success"])

@if($route)
<a href='{{$route}}'
    class='btn {{$color}} {{($small=='true')? 'btn-sm' : ''}} {{$class}}'
    data-toggle="tooltip"
    data-id = "{{ $dataid }}"
    data-type = "{{ $datatype }}"
    title="{{ $title }}"
    id="{{ $id }}">
    <i class="{{$icon}}"></i>
    {{ $slot }}
</a>
@else
<button type="submit"
    class='btn btn-success {{($small=='true')? 'btn-sm' : ''}} {{$class}}'
    data-toggle="tooltip"
    title="{{ $title }}">
    <i class="{{$icon}}"></i>
    {{ $slot }}
</button>
@endif
