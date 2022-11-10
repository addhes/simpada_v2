@props(["route"=>"","title"=>""])
<div class="row page-title">
    <div class="col-md-12">
        <nav aria-label="breadcrumb" class="float-right mt-1">
            <ol class="breadcrumb">
                @if($route)
                <li class="breadcrumb-item"><a href="{{route($route)}}"> {{__('Dashboard')}}</a></li>
                @else
                <li class="breadcrumb-item"><a href="{{route('backend.dashboard')}}"> {{__('Dashboard')}}</a></li>
                @endif
                {!! $slot !!}
            </ol>
        </nav>
        <h4 class="mb-1 mt-0">{{ Str::title($title) }} Management</h4>
    </div>
</div>