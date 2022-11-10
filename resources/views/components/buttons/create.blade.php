@props(["route"=>"", "icon"=>"fas fa-plus-circle", "title", "small"=>"", "class"=>"", "id"=>""])

@if($route)
<a href='{{$route}}'
    class='btn btn-primary {{($small=='true')? 'btn-sm' : ''}} {{$class}}'
    data-toggle="tooltip"
    title="{{ $title }}"
    id="{{ $id }}">
    <i class="{{$icon}}"></i>
    {{ $slot }}
</a>
@else
<button type="submit"
    class='btn btn-primary {{($small=='true')? 'btn-sm' : ''}} {{$class}}'
    data-toggle="tooltip"
    title="{{ $title }}"
    id="{{ $id }}">
    <i class="{{$icon}}"></i>
    {{ $slot }}
</button>
@endif
