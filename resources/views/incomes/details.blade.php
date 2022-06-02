@extends('layouts.app', [
    'class' => 'sidebar-mini ',
    'namePage' => __('incomes.title'). " / " .__('general.details'),
    'activePage' => 'incomes',
    'activeNav' => '',
])

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{__('incomes.title')}}</h1>
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
                        <div class="card-title">
                            <h5 class="title">{{__('general.details')}} <small> ID: {{$income->id}}</small></h5>
                        </div>
                        <div class="card-tools">
                            <a href="{{route('incomes.index')}}"
                               class="btn btn-sm btn-info">{{__("general.menu.go_back")}}</a>
                        </div>
                    </div>
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
                    <div class="card-body">

                        @foreach($income_details as $value)
                            {{__("general.date")}}: {{$value->date->format('d/m/Y')}}
                            <br>
                            {{__("general.value")}}: {{__('general.M_s') . " " . number_format($value->value, 2)}}
                            @if($value->rec_income)
                                <small>
                                    <i class="fas fa-sync-alt" title="{{__("incomes.recurring")}}"></i>
                                </small>
                            @endif
                            <br>
                            {{__("general.bank")}}/{{__("general.account")}}: <b>{{$value->bank->name}}</b>
                            - {{$value->bank->payment_method}} ({{$value->bank->wallet->name}})
                            <br>
                            <p class="pull-right">
                                {{__("general.info.registred_by")}}: {{$value->user->name}}
                                <br>
                                {{__("general.info.registred_at")}}: {{$value->created_at->format('d/m/Y H:m')}}
                            </p>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
