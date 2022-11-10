@props(["route"=>"", "icon"=>"fa fa-check", "title", "small"=>"", "class"=>"","dataid"=>"", "datatype"=>"", "id"=>""])

@if($route)
<a href='{{$route}}'
    class='btn btn-primary {{($small=='true')? 'btn-sm' : ''}} {{$class}}'
    data-toggle="tooltip"
    data-id = "{{ $dataid }}"
    data-type = "{{ $datatype }}"
    title="{{ $title }}"
    id= "{{ $id }}">
    <i class="{{$icon}}"></i>
    {{ $slot }}
</a>
@else
<button type="submit"
    class='btn btn-primary {{($small=='true')? 'btn-sm' : ''}} {{$class}}'
    data-toggle="tooltip"
    title="{{ $title }}">
    <i class="{{$icon}}"></i>
    {{ $slot }}
</button>
@endif
