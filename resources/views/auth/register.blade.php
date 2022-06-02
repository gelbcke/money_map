@extends('layouts.app', [
    'namePage' => __('auth.title_register'),
    'body_class' => 'hold-transition login-page dark-mode',
    'activePage' => 'register',
])

@section('content')
    <div class="register-box">
        <!-- /.login-logo -->
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="{{route('home')}}" class="h1"><b>FIN</b>NET</a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">{{ __('auth.title_register') }}</p>


                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="input-group mb-3 {{ $errors->has('name') ? ' has-danger' : '' }}">
                        <input class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}"
                               placeholder="{{ __('general.name') }}" type="text" name="name" value="{{ old('name') }}"
                               required autofocus>
                        @if ($errors->has('name'))
                            <span class="invalid-feedback" style="display: block;" role="alert">
                      <strong>{{ $errors->first('name') }}</strong>
                    </span>
                        @endif
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3 {{ $errors->has('email') ? ' has-danger' : '' }}">
                        <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                               placeholder="{{ __('profile.email') }}" type="email" name="email"
                               value="{{ old('email') }}" required>
                        @if ($errors->has('email'))
                            <span class="invalid-feedback" style="display: block;" role="alert">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                        @endif
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3 {{ $errors->has('password') ? ' has-danger' : '' }}">
                        <input class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}"
                               placeholder="{{ __('profile.password') }}" type="password" name="password" required>
                        @if ($errors->has('password'))
                            <span class="invalid-feedback" style="display: block;" role="alert">
                      <strong>{{ $errors->first('password') }}</strong>
                    </span>
                        @endif
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input class="form-control" placeholder="{{ __('profile.confirm_password') }}" type="password"
                               name="password_confirmation" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="icheck-primary">
                            <input type="checkbox" id="agreeTerms" name="terms" value="agree">
                            <label for="agreeTerms">
                                {{ __('general.i_agree') }} <a href="#">{{ __('general.terms_and_conditions') }}</a>
                            </label>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block">{{__('general.get_started')}}</button>
                    </div>
                    <!-- /.col -->

                </form>
            </div>
        </div>
    </div>
@endsection
