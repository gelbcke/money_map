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
                        <div class="card-title">
                            <h5 class="title">{{__("profile.user_groups.my_groups")}}</h5>
                        </div>
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
                                        {{__("general.menu.actions")}}
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
                                                <button type="submit" class="btn btn-sm btn-danger">{{__("general.menu.delete")}}</button>
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
                        @if($user_belongTo[0]->group_id )
                        <hr>
                        <div class="table-responsive">
                            <table class="table">
                                <h5 class="title">{{__("profile.user_groups.groups_i_belong")}}</h5>
                                <thead class=" text-primary">
                                    <th>
                                        {{__("general.name")}}
                                    </th>
                                    <th>
                                        {{__("general.owner")}}
                                    </th>
                                    <th>
                                        {{ __("general.status") }}
                                    </th>
                                    <th>
                                        {{__("general.menu.actions")}}
                                    </th>
                                </thead>
                                <tbody>
                                    @foreach($user_belongTo as $value)
                                    <tr>
                                        <td>
                                            <a href="{{route('user_groups.show', $value->group->id)}}"> {{$value->group->name}} </a>
                                        </td>
                                        <td>
                                            {{$value->group->user->name}}
                                        </td>
                                        <td>
                                            @if(in_array(Auth::id(), $value->group->user_id))

                                            {{ __('profile.user_groups.in_of_group') }}

                                            @else

                                            {{ __('profile.user_groups.out_of_group') }}

                                            @endif
                                        </td>
                                        <td>
                                            @if(in_array(Auth::id(), $value->group->user_id))
                                            <a class="btn btn-sm btn-danger" href="{{route('user_groups.get_out', [$value->group->id, Auth::user()->id])}}">
                                                {{ __('profile.user_groups.get_out_group') }}
                                            </a>
                                            @else
                                            <a class="btn btn-sm btn-success" href="{{route('user_groups.get_in', [$value->group->id, Auth::user()->id])}}">
                                                {{ __('profile.user_groups.get_in_group') }}
                                            </a>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
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
                                                <input type="text" name="name" class="form-control" value="{{ old('name') }}" autocomplete="none">
                                                @include('alerts.feedback', ['field' => 'name'])
                                            </div>
                                        </div>
                                    </div>
                                    <hr class="half-rule" />
                                    <button type="submit" class="btn btn-success">{{__('general.menu.save')}}</button>
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