<!-- ========== Left Sidebar Start ========== -->
<div class="left-side-menu">
    <div class="media user-profile mt-2 mb-2">
        <img src="{{ asset(auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}"
            class="avatar-sm rounded-circle mr-2" alt="Shreyu" />
        <img src="{{ asset(auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}"
            class="avatar-xs rounded-circle mr-2" alt="Shreyu" />

        <div class="media-body">
            <h6 class="pro-user-name mt-0 mb-0">{{ Auth::user()->name }} </h6>
            <span class="pro-user-desc">{{ Auth::user()->getRoleNames()[0]??"" }}</span>
        </div>
        <div class="dropdown align-self-center profile-dropdown-menu">
            <a class="dropdown-toggle mr-0" data-toggle="dropdown" href="#" role="button" aria-haspopup="false"
                aria-expanded="false">
                <span data-feather="chevron-down"></span>
            </a>
            <div class="dropdown-menu profile-dropdown">

                @php
                if(Auth::user()->hasRole('finance')){
                $role = 'finance';
                }elseif(Auth::user()->hasRole('employee')){
                $role = 'employee';
                }elseif(Auth::user()->hasRole('director')){
                $role = 'director';
                }else{
                $role = 'backend';
                }
                @endphp
                <a href="{{route($role.'.users.profile', Auth::user()->id)}}" class="dropdown-item notify-item">
                    <i data-feather="user" class="icon-dual icon-xs mr-2"></i>
                    <span>My Account</span>
                </a>

                <a href="javascript:void(0);" class="dropdown-item notify-item">
                    <i class="fas fa-envelope"></i>
                    <span>{{ Auth::user()->email }}</span>
                </a>
                <a href="{{ route('logout') }}" class="dropdown-item notify-item"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i data-feather="log-out" class="icon-dual icon-xs mr-2"></i>
                    <span>@lang('Logout')</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;"> @csrf
                </form>
            </div>
        </div>
    </div>

    <div class="sidebar-content">
        <!--- Sidemenu -->
        <div id="sidebar-menu" class="slimscroll-menu">
            @include('layouts.shared.app-menu')
        </div>
        <!-- End Sidebar -->

        <div class="clearfix"></div>
    </div>
    <!-- Sidebar -left -->

</div>
<!-- Left Sidebar End -->