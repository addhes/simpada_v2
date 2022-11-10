@props(["route"=>"", "icon"=>"	fas fa-bullhorn", "title", "small"=>"", "class"=>"", "id"=>""])

@if($route)
<a href='{{$route}}'
    class='btn btn-warning {{($small=='true')? 'btn-sm' : ''}} {{$class}}'
    id='{{$id}}'
    data-toggle="tooltip"
    title="{{ $title }}">
    <i class="{{$icon}}"></i>
    {{ $slot }}
</a>
@else
<button type="submit"
    class='btn btn-warning {{($small=='true')? 'btn-sm' : ''}} {{$class}}'
    data-toggle="tooltip"
    title="{{ $title }}">
    <i class="{{$icon}}"></i>
    {{ $slot }}
</button>
@endif
