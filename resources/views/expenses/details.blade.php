@extends('layouts.app', [
    'class' => 'sidebar-mini ',
    'namePage' => __('expenses.title'). " / " .__('general.details'),
    'activePage' => 'expenses',
    'activeNav' => '',
])

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{__('budget.title')}}</h1>
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
                        <h5 class="card-title">{{__("general.menu.budget")}} <small> {{$expense->budget->name}} -
                                ID {{$expense->id}}</small></h5>
                        <div class="card-tools">
                            <a href="{{route('expenses.index')}}"
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

                        @foreach($exp_details as $value)
                            {{__("general.date")}}: {{$value->date->format('d/m/Y')}}
                            <br>
                            {{__("general.value")}}: {{__("general.M_s")}} {{number_format($value->value, 2)}}
                            <br>
                            {{__("general.category")}}: {{$value->budget->name}}
                            <br>
                            {{__("general.bank")}} / {{__("general.account")}}: <b>{{$value->bank->name}}</b>
                            ({{$value->bank->wallet->name}})
                            <br>
                            {{__("expenses.payment_mode")}}:
                            @if($value->payment_method == 1)
                                {{__("general.credit")}}
                            @elseif($value->payment_method == 2)
                                {{__("general.debit")}}
                            @elseif($value->payment_method == 3)
                                {{__("general.cash")}}
                            @else
                                {{__("general.not_informed")}}
                            @endif
                            @if($value->rec_expense != NULL)
                                <br>
                                {{__("expenses.recurring_expenses")}}
                                : {{__("general.expire")}} {{$value->date->format('d')}}
                            @endif
                            @if($value->parcels != NULL)
                                <hr>
                                {{__("expenses.parcels")}}: {{$value->parcels}}x
                                <br>
                                {{__("expenses.parcels_vl")}}
                                : {{__("general.M_s")}} {{number_format($value->parcel_vl, 2)}}
                            @endif
                            @if($value->details != NULL)
                                <hr>
                                <b>{{__("general.details")}}:</b>
                                <br>
                                {{$value->details}}
                            @endif
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
