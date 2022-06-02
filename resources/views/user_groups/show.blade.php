@extends('layouts.app', [
    'class' => 'sidebar-mini ',
    'namePage' => 'User Groups',
    'activePage' => 'user_group',
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
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('assets')}}/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="{{asset('assets')}}/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <!-- Bootstrap4 Duallistbox -->
    <link rel="stylesheet" href="{{asset('assets')}}/plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css">
    <!-- Content Header (Page header) -->
    <section class="content-header">
    </section>

    <!-- Main content -->
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{__("profile.user_groups.title")}}</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">{{$userGroup->name}}</h5>
                            <div class="card-tools">
                                <a href="{{route('user_groups.create')}}" class="btn btn-sm btn-info">Criar</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class=" text-primary">
                                    <th>
                                        {{__("general.users")}}
                                    </th>

                                    <th style="width: 2%">
                                    </th>

                                    </thead>
                                    <tbody>
                                    @foreach($users_gp as $value)
                                        <tr>
                                            <td>
                                                {{$value->name}}
                                            </td>
                                            <td>
                                                <form id="remove_{{$value->id}}" action="{{route('user_groups.remove_user',['id' => $value->id,'group_id' => $value->group_id])}}" method="POST">@csrf
                                                <a href="javascript:void(0)" onclick="$('#remove_{{$value->id}}').submit()">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                                <!-- left column -->
                                <div class="col-md-12">
                                    <!-- general form elements -->
                                    <div class="card card-primary">
                                        <div class="card-header">
                                            <h3 class="card-title">{{__('profile.user_groups.add_user_to_group')}}</h3>
                                        </div>
                                        <!-- /.card-header -->
                                        <!-- form start -->
                                <form action="{{ route('user_groups.update',$userGroup->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="users_id" class="col-sm-2 col-form-label">
                                                {{__("general.users")}}
                                            </label>
                                            <select id="users_id" name="users_id" class="select2" required>
                                                <option value="NULL">{{__('general.menu.select')}}</option>
                                                @foreach($add_users as $value)
                                                    <option value="{{ $value->id }}">{{ $value->name }} ({{ $value->email }})</option>
                                                @endforeach
                                            </select>
                                            @include('alerts.feedback', ['field' => 'users_id'])
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-primary">
                                            {{__('profile.user_groups.add_user')}}
                                        </button>
                                    </div>
                                </form>
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
