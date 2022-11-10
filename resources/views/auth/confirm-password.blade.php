@extends('layouts.non-auth',['isDark'=>true])

@section('title') @lang('Confirm password') @endsection

@section('content')

<div class="account-pages my-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div class="card">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-6 p-5">
                                <div class="mx-auto mb-5">
                                    <a href="/">
                                        <img src="assets/images/logo.png" alt="" height="24" />
                                        <h3 class="d-inline align-middle ml-1 text-logo">Shreyu</h3>
                                    </a>
                                </div>

                                <div class="text-center">
                                    <img src="assets/images/users/avatar-7.jpg" height="88" alt="user-image" class="avatar-lg rounded-circle shadow">
                                    <h4 class="h4 mb-0 mt-3">Hi ! Nik</h4>
                                    <p class="text-muted mt-1 mb-4">
                                        Enter your password to access the admin.
                                    </p>
                                </div>
                                @include('flash::message')

                                @if (Session::has('status'))
                                <!-- Session Status -->
                                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                    <p>
                                        <i class="fas fa-bolt"></i> {{ Session::get('status') }}
                                    </p>

                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                @endif

                                @if ($errors->any())
                                <!-- Validation Errors -->
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <p>
                                        <i class="fas fa-exclamation-triangle"></i> @lang('Please fix the following errors & try again!')
                                    </p>
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>

                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                @endif
                               

                               <form role="form" method="POST" action="{{ route('password.confirm') }}" class="authentication-form">
                                    @csrf

                                    <div class="form-group mt-4">
                                        <label class="form-control-label">Password</label>
                                        <div class="input-group input-group-merge">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="icon-dual" data-feather="lock"></i>
                                                </span>
                                            </div>
                                            <input type="password" class="form-control @if($errors->has('password')) is-invalid @endif" id="password"
                                                placeholder="{{ __('Password') }}" name="password" />

                                            @if($errors->has('password'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('password') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>

                                   

                                    <div class="form-group mb-0 text-center">
                                        <button class="btn btn-primary btn-block" type="submit">{{ __('Login') }}</button>
                                    </div>
                                </form>
                            </div>
                            <div class="col-lg-6 d-none d-md-inline-block">
                                <div class="auth-page-sidebar">
                                    <div class="overlay"></div>
                                    <div class="auth-user-testimonial">
                                        <p class="font-size-24 font-weight-bold text-white mb-1">I simply love it!</p>
                                        <p class="lead">"It's a elegent templete. I love it very much!"</i>
                                        </p>
                                        <p>- Admin User</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div> <!-- end card-body -->
                </div>
                <!-- end card -->

                <div class="row mt-3">
                    <div class="col-12 text-center">
                        <p class="text-muted">Back to <a href="{{ route('login') }}"
                                class="text-primary font-weight-bold ml-1">Login</a></p>
                    </div> <!-- end col -->
                </div>
                <!-- end row -->

            </div> <!-- end col -->
        </div>
        <!-- end row -->
    </div>
    <!-- end container -->
</div>

@endsection
