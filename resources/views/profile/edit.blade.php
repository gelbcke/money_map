@extends('layouts.app', [
    'class' => 'sidebar-mini ',
    'namePage' => __('profile.title'),
    'activePage' => 'profile',
    'activeNav' => '',
])
@section('styles')
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="{{asset('assets')}}/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('assets')}}/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="{{asset('assets')}}/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <!-- Bootstrap4 Duallistbox -->
    <link rel="stylesheet" href="{{asset('assets')}}/plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css">
    @endsection
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{__('profile.title')}}</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Whoops!</strong> There were some problems with your input.<br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                @endif
                <!-- Profile Image -->
                    <div class="card card-primary card-outline">
                        <div class="card-body box-profile">
                            <div class="text-center">
                                <img class="profile-user-img img-fluid img-circle"
                                     src="{{asset('assets')}}/dist/img/default-150x150.png"
                                     alt="User profile picture">
                            </div>
                            <h3 class="profile-username text-center">{{Auth::user()->name}}</h3>

                            <p class="text-muted text-center">{{Auth::user()->email}}</p>
                            <ul class="list-group list-group-unbordered mb-3">
                                <b>{{__('general.group')}}</b>

                                <li class="list-group-item">

NOT Working

                                </li>

                            </ul>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header p-2">
                            <ul class="nav nav-pills">
                                <li class="nav-item"><a class="nav-link active" href="#user"
                                                        data-toggle="tab">{{__('profile.user_info')}}</a></li>
                                <li class="nav-item"><a class="nav-link" href="#system"
                                                        data-toggle="tab">{{__('profile.system')}}</a></li>

                            </ul>
                        </div><!-- /.card-header -->
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="active tab-pane" id="user">
                                    <form class="form-horizontal" method="post" action="{{ route('profile.update') }}"
                                          autocomplete="off"
                                          enctype="multipart/form-data">
                                        @csrf
                                        @method('put')
                                        @include('alerts.success')
                                        <div class="form-group row">
                                            <label for="inputName"
                                                   class="col-sm-2 col-form-label">{{__('profile.name')}}</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="name" class="form-control"
                                                       value="{{ old('name', auth()->user()->name) }}">
                                                @include('alerts.feedback', ['field' => 'name'])
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputEmail"
                                                   class="col-sm-2 col-form-label">{{__('profile.email')}}</label>
                                            <div class="col-sm-10">
                                                <input type="email" name="email" class="form-control"
                                                       placeholder="Email"
                                                       value="{{ old('email', auth()->user()->email) }}">
                                                @include('alerts.feedback', ['field' => 'email'])
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="offset-sm-2 col-sm-10">
                                                <button type="submit"
                                                        class="btn btn-danger">{{__('general.menu.save')}}</button>
                                            </div>
                                        </div>
                                    </form>
                                    <form class="form-horizontal" method="post" action="{{ route('profile.password') }}"
                                          autocomplete="off">
                                        @csrf
                                        @method('put')
                                        @include('alerts.success', ['key' => 'password_status'])
                                        <div class="form-group row {{ $errors->has('password') ? ' has-danger' : '' }}">
                                            <label for="inputName"
                                                   class="col-sm-2 col-form-label">{{__("profile.current_password")}}</label>
                                            <div class="col-sm-10">
                                                <input
                                                    class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}"
                                                    name="old_password"
                                                    placeholder="{{ __('profile.current_password') }}" type="password"
                                                    required>
                                                @include('alerts.feedback', ['field' => 'old_password'])
                                            </div>
                                        </div>
                                        <div class="form-group row {{ $errors->has('password') ? ' has-danger' : '' }}">
                                            <label for="inputEmail"
                                                   class="col-sm-2 col-form-label">{{__("profile.new_password")}}</label>
                                            <div class="col-sm-10">
                                                <input
                                                    class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}"
                                                    placeholder="{{ __('profile.new_password') }}" type="password"
                                                    name="password" required>
                                                @include('alerts.feedback', ['field' => 'password'])
                                            </div>
                                        </div>
                                        <div class="form-group row {{ $errors->has('password') ? ' has-danger' : '' }}">
                                            <label for="inputEmail"
                                                   class="col-sm-2 col-form-label">{{__("profile.confirm_password")}}</label>
                                            <div class="col-sm-10">
                                                <input class="form-control"
                                                       placeholder="{{ __('profile.confirm_password') }}"
                                                       type="password" name="password_confirmation" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="offset-sm-2 col-sm-10">
                                                <button type="submit"
                                                        class="btn btn-danger">{{__('profile.change_password')}}</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <!-- /.tab-pane -->
                                <div class="tab-pane" id="system">
                                    <form class="form-horizontal" method="post" action="{{ route('profile.settings') }}"
                                          autocomplete="off">
                                        @csrf
                                        @method('post')
                                        @include('alerts.success', ['key' => 'settings'])
                                        <div
                                            class="form-group row {{ $errors->has('currency_id') ? ' has-danger' : '' }}">
                                            <label for="currency_id"
                                                   class="col-sm-2 col-form-label">{{__("profile.currency")}}</label>
                                            <div class="col-sm-10">
                                                <select id="currency_id" name="currency_id" class="form-control select2"
                                                        required>
                                                    <option value=""> --- {{__("general.menu.select")}} ---</option>
                                                    @foreach($currency as $key => $value)
                                                        <option
                                                            value="{{$value->id}}" @if(Auth::user()->currency_id == $value->id) selected @endif>{{$value->name ." - ". $value->symbol}}</option>
                                                    @endforeach
                                                </select>
                                                @include('alerts.feedback', ['field' => 'currency_id'])
                                            </div>
                                        </div>
                                        <div class="form-group row {{ $errors->has('language') ? ' has-danger' : '' }}">
                                            <label for="language"
                                                   class="col-sm-2 col-form-label">{{__("profile.language")}}</label>
                                            <div class="col-sm-10">
                                                <select id="language" name="language" class="form-control" required>
                                                    <option value=""> --- {{__("general.menu.select")}} ---</option>
                                                    @foreach($language as $value)
                                                        <option value="{{$value}}" @if(Auth::user()->language == $value) selected @endif>{{$value}}</option>
                                                    @endforeach
                                                </select>
                                                @include('alerts.feedback', ['field' => 'language'])
                                            </div>
                                        </div>
                                        <div class="form-group row {{ $errors->has('theme') ? ' has-danger' : '' }}">
                                            <label for="theme"
                                                   class="col-sm-2 col-form-label">{{__("profile.theme")}}</label>
                                            <div class="col-sm-10">
                                                <select id="theme" name="theme" class="form-control" required>
                                                    <option value=""> --- {{__("general.menu.select")}} ---</option>
                                                    @foreach($theme as $value)
                                                        <option value="{{$value}}" @if(Auth::user()->theme == $value) selected @endif>{{$value}}</option>
                                                    @endforeach
                                                </select>
                                                @include('alerts.feedback', ['field' => 'theme'])
                                            </div>
                                        </div>
                                        <div class="form-group row {{ $errors->has('timezone_id') ? ' has-danger' : '' }}">
                                            <label for="timezone_id"
                                                   class="col-sm-2 col-form-label">{{__("profile.timezone")}}</label>
                                            <div class="col-sm-10">
                                                <select id="timezone_id" name="timezone_id" class="select2" required>
                                                    @foreach($timezones as $timezone)
                                                        <option value="{{ $timezone->id }}" @if(Auth::user()->timezone_id == $timezone->id) selected @endif>{{ $timezone->name }} ({{ $timezone->offset }})</option>
                                                    @endforeach
                                                </select>
                                                @include('alerts.feedback', ['field' => 'timezone_id'])
                                            </div>
                                        </div>
<hr>
                                        <div class="form-group row">
                                            <div class="offset-sm-2 col-sm-10">
                                                <button type="submit"
                                                        class="btn btn-danger">{{__('profile.save_settings')}}</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <!-- /.tab-pane -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('scripts')
    <!-- Bootstrap 4 -->
    <script src="{{asset('assets')}}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Select2 -->
    <script src="{{asset('assets')}}/plugins/select2/js/select2.full.min.js"></script>
    <!-- Bootstrap4 Duallistbox -->
    <script src="{{asset('assets')}}/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
    <script>
        $(function () {
            //Initialize Select2 Elements
            $('.select2').select2({
                theme: 'bootstrap4'
            })
        });
    </script>
@endsection

