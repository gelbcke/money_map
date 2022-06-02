@extends('layouts.app', [
    'class' => 'sidebar-mini ',
    'namePage' => __('investments.title'),
    'activePage' => 'investments',
    'activeNav' => '',
])

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{__('investments.title')}}</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title"><b>{{$investment->bank->name}}</b>
                            <br>
                            <small>
                                {{$investment->details}} - ID {{$investment->id}}
                            </small>
                        </h5>
                        <div class="card-tools">
                            <a href="{{route('investments.index')}}"
                               class="btn btn-sm btn-info">{{__("general.menu.go_back")}}</a>
                        </div>
                    </div>
                    <div class="card-body">

                        {{__("general.date")}}: {{$investment->date->format('d/m/Y')}}
                        <br>
                        {{__("investments.initial_value")}}
                        : {{__('general.M_s')}} {{number_format($investment->value, 2)}}
                        <br>
                        {{__("investments.profit")}}
                        : {{__('general.M_s')}} {{number_format($investment->where('org_id', $investment->id)->sum('value') - $investment->value, 2)}}
                        <br>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
