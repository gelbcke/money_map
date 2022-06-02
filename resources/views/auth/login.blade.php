@extends('layouts.app', [
    'namePage' => __('auth.title_login'),
    'body_class' => 'hold-transition login-page',
    'activePage' => 'login',
])
@section('content')
    <div class="login-box">
    @include('alerts.migrations_check')
    <!-- /.login-logo -->
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <div class="text-center">
                    <img class="profile-user-img img-fluid" style="border:0;"
                         src="{{ asset('assets') }}/dist/img/moneymap_logo.png"
                         alt="MoneyMap Logo">
                </div>
            </div>
            <div class="card-body">
                <p class="login-box-msg">{{__('auth.title_login')}}</p>
                <form role="form" method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="input-group mb-3 {{ $errors->has('email') ? ' has-danger' : '' }}">
                        <input type="email" name="email"
                               class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="Email"
                               value="{{ old('email') }}" required autofocus>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    @if ($errors->has('email'))
                        <span class="invalid-feedback" style="display: block;" role="alert">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                    <div class="input-group mb-3 {{ $errors->has('password') ? ' has-danger' : '' }}">
                        <input type="password" name="password"
                               class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}"
                               placeholder="{{ __('profile.password') }}" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    @if ($errors->has('password'))
                        <span class="invalid-feedback" style="display: block;" role="alert">
                    <strong>{{ $errors->first('password') }}</strong>
                    </span>
                    @endif
                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" id="remember">
                                <label for="remember">
                                    {{__('auth.remember_me')}}
                                </label>
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-4">
                            <button type="submit"
                                    class="btn btn-primary btn-block">{{ __('general.menu.login') }}</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>

                <p class="mb-1">
                    <a href="{{ route('password.request') }}"
                       class="link footer-link">{{ __('auth.forgot_password') }}</a>
                </p>
                <p class="mb-0">
                    <a href="{{ route('register') }}" class="text-center">{{ __('auth.create_account') }}</a>
                </p>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.login-box -->
@endsection
