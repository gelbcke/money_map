@extends('layouts.app', [
    'class' => 'sidebar-mini ',
    'namePage' => __('profile.user_groups.title'),
    'activePage' => 'user_groups',
    'activeNav' => '',
])

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{__('profile.user_groups.title')}}</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="title">{{__("profile.user_groups.title")}}</h5>
                        </div>
                        <div class="card-body">
                            @if($user_groups->count() > 0)
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead class=" text-primary">
                                        <th>
                                            {{__("general.name")}}
                                        </th>
                                        <th>
                                            {{__("general.owner")}}
                                        </th>
                                        <th>
                                            {{__("general.users")}}
                                        </th>
                                        <th>
                                            Action
                                        </th>
                                        </thead>
                                        <tbody>
                                        @foreach($user_groups as $value)
                                            <tr>
                                                <td>
                                                    <a href="{{route('user_groups.show', $value->id)}}"> {{$value->name}} </a>
                                                </td>
                                                <td>
                                                    {{$value->user->name}}
                                                </td>
                                                <td>
                                                    @foreach($value->users as $vl)
                                                        {{$vl->name}}<br>
                                                    @endforeach
                                                </td>
                                                <td>
                                                    <form method="post" action="{{ route('user_groups.destroy', $value->id) }}">
                                                        {{ csrf_field() }}
                                                        {{ method_field('DELETE') }}
                                                        <button type="submit">Delete</button>
                                                    </form>


                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                {{__('profile.user_groups.no_group')}}
                            @endif
                        </div>
                    </div>
                    @if($user_groups->count() == 0)
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="title">{{__("profile.user_groups.create_new")}}</h5>
                                </div>
                                <div class="card-body">
                                    <form method="post" action="{{ route('user_groups.store') }}" autocomplete="off">
                                        @csrf
                                        @method('post')
                                        @include('alerts.success')
                                        <div class="row">
                                            <div class="col-md-6 pr-1">
                                                <div class="form-group">
                                                    <label for="name">{{__("general.name")}}</label>
                                                    <input type="text" name="name" class="form-control"
                                                           value="{{ old('name') }}" autocomplete="none">
                                                    @include('alerts.feedback', ['field' => 'name'])
                                                </div>
                                            </div>
                                        </div>
                                        <hr class="half-rule"/>
                                        <button type="submit"
                                                class="btn btn-success">{{__('general.menu.save')}}</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                        @endif
                </div>
            </div>
        </div>
    </section>
@endsection
