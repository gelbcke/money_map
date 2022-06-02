@extends('layouts.app', [
    'namePage' => __('home.title'),
    'class' => 'login-page sidebar-mini ',
    'activePage' => 'home'
])

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-success elevation-1">
                            <a href="{{route('incomes.index')}}">
                                <i class="fas fa-arrow-up" title="Soma de todos os valores recebidos no mês"></i>
                            </a>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text"> {{__('incomes.total_month')}}</span>
                            <span class="info-box-number">
                                  {{__('general.M_s')." ".number_format($get_income_this_month, 2)}}
                                </span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-danger elevation-1">
                             <a href="{{route('expenses.index')}}">
                                <i class="fas fa-arrow-down"
                                   title="Soma de todas as despesas e parcelas (crédito) que vencem este mês"></i>
                             </a>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">{{__('expenses.total_month')}}</span>
                            <span
                                class="info-box-number">{{__('general.M_s')." ".number_format($exp_by_budget_this_month->sum('total') + $sum_parcels_this_month->sum('parcel_vl'), 2)}}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->

                <!-- fix for small devices only -->
                <div class="clearfix hidden-md-up"></div>
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-info elevation-1">
                            <i class="fas fa-sack-dollar" title="Soma do saldo disponível em todas as contas"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Saldo</span>
                            <span class="info-box-number">{{__('general.M_s')." ".number_format($balance, 2)}}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-warning elevation-1">
                             <a href="{{route('investments.index')}}">
                                <i class="fa fa-piggy-bank"></i>
                             </a>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Investido</span>
                            <span
                                class="info-box-number">{{__('general.M_s')." ".number_format($investments->sum('value'), 2)}}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <div class="card card-chart">
                        <div class="card-header">
                            <h5 class="card-category">{{ __('home.budget') }}</h5>
                            <h6 class="card-title">{{ __('home.prev_month') }}</h6>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-sm">
                                <thead>
                                <tr>
                                    <th style="width: 10px">%</th>
                                    <th>{{__('general.menu.budget')}}</th>
                                    <th>{{__('general.limit')}}</th>
                                    <th style="width: 110px">{{__('general.max_value')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($get_out_budgets as $value)
                                    <tr>
                                        <td>{{$value->budget}}</td>
                                        <td>{{__('budget.' . $value->name)}}</td>
                                        <td>
                                            <div class="progress progress-xs">
                                                @foreach($exp_by_budget_prev_month as $vl)
                                                    @if($get_income_prev_month > 0)
                                                        @if($vl->budget_id == $value->id)
                                                            <div style="display: none">
                                                                {{$percent = (double)$vl->total + $sum_parcels_prev_month->where('expense_id', $value->id)->sum('parcel_vl') / ($get_income_prev_month * $value->budget / 100) * 100}}
                                                            </div>
                                                            @if($percent < 50)
                                                                <div class="progress-bar bg-success"
                                                                     title="{{__('general.M_s')." ".number_format($vl->total + $sum_parcels_prev_month->where('expense_id', $value->id)->sum('parcel_vl'), 2)}}  ({{number_format($percent, 0)}}%)"
                                                                     style="width:{{number_format($percent, 0)}}%"></div>
                                                            @elseif($percent >= 50 && $percent < 85)
                                                                <div class="progress-bar bg-warning"
                                                                     title="{{__('general.M_s')." ".number_format($vl->total + $sum_parcels_prev_month->where('expense_id', $value->id)->sum('parcel_vl'), 2)}}  ({{number_format($percent, 0)}}%)"
                                                                     style="width:{{number_format($percent, 0)}}%"></div>
                                                            @elseif($percent >= 85)
                                                                <div class="progress-bar bg-danger"
                                                                     title="{{__('general.M_s')." ".number_format($vl->total + $sum_parcels_prev_month->where('expense_id', $value->id)->sum('parcel_vl'), 2)}}  ({{number_format($percent, 0)}}%)"
                                                                     style="width:{{number_format($percent, 0)}}%"></div>
                                                            @endif
                                                        @endif
                                                    @endif
                                                @endforeach
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge">
                                                {{__('general.M_s')." ".number_format($get_income_prev_month * $value->budget / 100, 2)}}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                                @foreach($get_save_budgets as $value)
                                    <tr>
                                        <td>{{$value->budget}}</td>
                                        <td>{{__('budget.' . $value->name)}}</td>
                                        <td>
                                            <div class="progress progress-xs">
                                                @foreach($save_by_budget_prev_month as $vl)
                                                    @if($get_income_prev_month > 0)
                                                        <div style="display: none">
                                                            {{$SAVE_prev_month = $vl->total / ($get_income_prev_month * $value->budget / 100) * 100}}
                                                        </div>
                                                        @if($SAVE_prev_month < 50)
                                                            <div class="progress-bar bg-danger"
                                                                 title="{{__('general.M_s')." ".number_format($vl->total, 2)}}  ({{number_format($SAVE_prev_month, 0)}}%)"
                                                                 style="width:{{number_format($SAVE_prev_month, 0)}}%"></div>
                                                        @elseif($SAVE_prev_month >= 50 && $SAVE_prev_month < 90)
                                                            <div class="progress-bar bg-warning"
                                                                 title="{{__('general.M_s')." ".number_format($vl->total, 2)}}  ({{number_format($SAVE_prev_month, 0)}}%)"
                                                                 style="width:{{number_format($SAVE_prev_month, 0)}}%"></div>
                                                        @elseif($SAVE_prev_month >= 90)
                                                            <div class="progress-bar bg-success"
                                                                 title="{{__('general.M_s')." ".number_format($vl->total, 2)}}  ({{number_format($SAVE_prev_month, 0)}}%)"
                                                                 style="width:{{number_format($SAVE_prev_month, 0)}}%"></div>
                                                        @endif
                                                    @endif
                                                @endforeach
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge">
                                                {{__('general.M_s')." ".number_format($get_income_prev_month * $value->budget / 100, 2)}}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="card card-chart">
                        <div class="card-header">
                            <h5 class="card-category">{{ __('home.budget') }}</h5>
                            <h6 class="card-title">{{ __('home.this_month') }}</h6>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-sm">
                                <thead>
                                <tr>
                                    <th style="width: 10px">%</th>
                                    <th>{{__('general.menu.budget')}}</th>
                                    <th>{{__('general.limit')}}</th>
                                    <th style="width: 110px">{{__('general.max_value')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($get_out_budgets as $value)
                                    <tr>
                                        <td>{{$value->budget}}</td>
                                        <td>{{__('budget.' . $value->name)}}</td>
                                        <td>
                                            <div class="progress progress-xs">
                                                @foreach($exp_by_budget_this_month as $vl)
                                                    @if($vl->budget_id == $value->id)
                                                        @if($get_income_this_month > 0)
                                                            <div style="display: none">
                                                                {{$percent = (($vl->total + $sum_parcels_this_month->where('expense_id', $value->id)->sum('parcel_vl'))  / ($get_income_this_month * $value->budget / 100)) * 100}}
                                                            </div>
                                                            @if($percent < 50)
                                                                <div class="progress-bar bg-success"
                                                                     title="{{__('general.M_s')." ".number_format($vl->total + $sum_parcels_this_month->where('expense_id', $value->id)->sum('parcel_vl'), 2)}}  ({{number_format($percent, 0)}}%)"
                                                                     style="width:{{number_format($percent, 0)}}%"></div>
                                                            @elseif($percent >= 50 && $percent < 85)
                                                                <div class="progress-bar bg-warning"
                                                                     title="{{__('general.M_s')." ".number_format($vl->total + $sum_parcels_this_month->where('expense_id', $value->id)->sum('parcel_vl'), 2)}}  ({{number_format($percent, 0)}}%)"
                                                                     style="width:{{number_format($percent, 0)}}%"></div>
                                                            @elseif($percent >= 85)
                                                                <div class="progress-bar bg-danger"
                                                                     title="{{__('general.M_s')." ".number_format($vl->total + $sum_parcels_this_month->where('expense_id', $value->id)->sum('parcel_vl'), 2)}}  ({{number_format($percent, 0)}}%)"
                                                                     style="width:{{number_format($percent, 0)}}%"></div>
                                                            @endif
                                                        @endif
                                                    @endif
                                                @endforeach
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge">
                                                {{__('general.M_s')." ".number_format($get_income_this_month * $value->budget / 100, 2)}}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                                @foreach($get_save_budgets as $value)
                                    <tr>
                                        <td>{{$value->budget}}</td>
                                        <td>{{__('budget.' . $value->name)}}</td>
                                        <td>
                                            <div class="progress progress-xs">
                                                @foreach($save_by_budget_this_month as $vl)
                                                    @if($get_income_this_month > 0)
                                                        <div style="display: none">
                                                            {{$SAVE_this_month = $vl->total / ($get_income_this_month * $value->budget / 100) * 100}}
                                                        </div>
                                                        @if($SAVE_this_month < 50)
                                                            <div class="progress-bar bg-danger"
                                                                 title="{{__('general.M_s')." ".number_format($vl->total, 2)}}  ({{number_format($SAVE_this_month, 0)}}%)"
                                                                 style="width:{{number_format($SAVE_this_month, 0)}}%"></div>
                                                        @elseif($SAVE_this_month >= 50 && $SAVE_this_month < 90)
                                                            <div class="progress-bar bg-warning"
                                                                 title="{{__('general.M_s')." ".number_format($vl->total, 2)}}  ({{number_format($SAVE_this_month, 0)}}%)"
                                                                 style="width:{{number_format($SAVE_this_month, 0)}}%"></div>
                                                        @elseif($SAVE_this_month >= 90)
                                                            <div class="progress-bar bg-success"
                                                                 title="{{__('general.M_s')." ".number_format($vl->total, 2)}}  ({{number_format($SAVE_this_month, 0)}}%)"
                                                                 style="width:{{number_format($SAVE_this_month, 0)}}%"></div>
                                                        @endif
                                                    @endif
                                                @endforeach
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge">
                                                {{__('general.M_s')." ".number_format($get_income_this_month * $value->budget / 100, 2)}}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header border-0">
                            <div class="d-flex justify-content-between">
                                <h3 class="card-title">{{ __('home.parceled_expenses') }}
                                    <small>({{ __('home.future_releases') }})</small></h3>
                                <a href="{{route('credit_parcels.index')}}">{{__('general.menu.report')}}</a>
                            </div>
                        </div>
                        <div class="card-body">
                            @if($parceled_expenses->count() > 0)
                                <div class="d-flex">
                                    <p class="d-flex flex-column">
                                        <span
                                            class="text-bold text-lg">{{__('general.M_s') ." ".number_format($parceled_expenses->sum('total'), 2)}}</span>
                                        <span>Total de Compras Parceladas</span>
                                    </p>
                                </div>
                                <!-- /.d-flex -->
                                <div class="position-relative mb-4">
                                    <canvas id="parcel_exp_chart" height="200"></canvas>
                                </div>
                            @else
                                <h6 class="card-footer">{{__('messages.no_records_found')}}</h6>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="card card-tasks">
                        <div class="card-header ">
                            <h5 class="card-category">{{ __('home.expenses') }}</h5>
                            <h4 class="card-title">{{ __('home.by_month') }}</h4>
                        </div>
                        <div class="card-body p-0">
                            @if($sum_expenses_month->count() > 0 )
                                <div class="table-full-width table-responsive">
                                    <table class="table">
                                        <tbody>
                                        @foreach($sum_expenses_month as $vl)
                                            <tr>
                                                <td>
                                                    {{__($vl->months)}}
                                                </td>
                                                <td>
                                                    {{__('general.M_s')." ".number_format($vl->value + $sum_parcels_this_month->sum('parcel_vl'), 2)}}
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <h6 class="card-footer">{{__('messages.no_records_found')}}</h6>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-tasks">
                        <div class="card-header ">
                            <h5 class="card-category">{{ __('home.investments') }}</h5>
                            <h4 class="card-title">{{ __('home.by_month') }}</h4>
                        </div>
                        <div class="card-body p-0">
                            @if($sum_investments_month->count() > 0 )
                                <div class="table-full-width table-responsive">
                                    <table class="table">
                                        <tbody>
                                        @foreach($sum_investments_month as $vl)
                                            <tr>
                                                <td>
                                                    {{__($vl->months)}}
                                                </td>
                                                <td>
                                                    {{__('general.M_s')." ".number_format($vl->value, 2)}}
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <h6 class="card-footer">{{__('messages.no_records_found')}}</h6>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-category">{{ __('home.last_expenses') }}</h5>
                        </div>
                        <div class="card-body p-0">
                            @if($last_expenses->count() > 0)
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead class=" text-primary">
                                        <th>
                                            {{ __('general.date') }}
                                        </th>
                                        <th>
                                            {{ __('general.value') }}
                                        </th>
                                        <th>
                                            {{ __('general.bank') }} / {{ __('general.account') }}
                                        </th>
                                        <th class="text-right">
                                            {{ __('general.category') }}
                                        </th>
                                        </thead>
                                        <tbody>
                                        @foreach($last_expenses as $value)
                                            <tr>
                                                <td>
                                                    {{$value->date->format('d/m/Y')}}
                                                </td>
                                                <td>
                                                    {{ __('general.M_s') }} {{number_format($value->value, 2)}}
                                                </td>
                                                <td>
                                                    {{$value->bank->name}} -
                                                    @if($value->payment_method == 1)
                                                        {{ __('general.credit') }}
                                                    @elseif($value->payment_method == 2)
                                                        {{ __('general.debit') }}
                                                    @elseif($value->payment_method == 3)
                                                        {{ __('general.cash') }}
                                                    @else
                                                        {{ __('general.not_informed') }}
                                                    @endif
                                                </td>
                                                <td class="text-right">
                                                    {{__('budget.' . $value->budget->name)}}
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <h6 class="card-footer">{{__('messages.no_records_found')}}</h6>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{ __('home.recurring_expenses') }}</h4>
                        </div>
                        <div class="card-body p-0">
                            @if($rec_expenses->count() > 0)
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead class=" text-primary">
                                        <th>
                                            {{ __('general.expire') }}
                                        </th>
                                        <th>
                                            {{ __('general.value') }}
                                        </th>
                                        <th>
                                            {{ __('general.category') }}
                                        </th>
                                        <th class="text-right">
                                            {{ __('general.details') }}
                                        </th>
                                        </thead>
                                        <tbody>
                                        @foreach($rec_expenses->get() as $value)
                                            <tr>
                                                <td>
                                                    {{ __('general.every_day') }} {{$value->date->format('d')}}
                                                </td>
                                                <td>
                                                    {{ __('general.M_s') }} {{number_format($value->value, 2)}}
                                                </td>
                                                <td>
                                                    {{__('budget.' . $value->budget->name)}}
                                                </td>
                                                <td class="text-right">
                                                    {{$value->details}}
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <h6 class="card-footer">{{__('messages.no_records_found')}}</h6>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('js')
    <script src="{{asset('assets')}}/plugins/chart.js/Chart.min.js"></script>
@endpush

@section('scripts')
    <script>
        $(function () {
            'use strict'

            var ticksStyle = {
                fontColor: '#495057',
                fontStyle: 'bold'
            }

            var mode = 'index'
            var intersect = true

            var $salesChart = $('#parcel_exp_chart')
            // eslint-disable-next-line no-unused-vars
            var salesChart = new Chart($salesChart, {
                type: 'bar',
                data: {
                    labels: {{ $parceled_expenses->pluck('month') }},
                    datasets: [
                        {
                            backgroundColor: '#007bff',
                            borderColor: '#007bff',
                            data: {{ $parceled_expenses->pluck('total') }}
                        }
                    ]
                },
                options: {
                    maintainAspectRatio: false,
                    tooltips: {
                        mode: mode,
                        intersect: intersect
                    },
                    hover: {
                        mode: mode,
                        intersect: intersect
                    },
                    legend: {
                        display: false
                    },
                    scales: {
                        yAxes: [{
                            // display: false,
                            gridLines: {
                                display: true,
                                lineWidth: '4px',
                                color: 'rgba(0, 0, 0, .2)',
                                zeroLineColor: 'transparent'
                            },
                            ticks: $.extend({
                                beginAtZero: true,

                                // Include a dollar sign in the ticks
                                callback: function (value) {
                                    if (value >= 1000) {
                                        value /= 1000
                                        value += 'k'
                                    }

                                    return '$' + value
                                }
                            }, ticksStyle)
                        }],
                        xAxes: [{
                            display: true,
                            gridLines: {
                                display: false
                            },
                            ticks: ticksStyle
                        }]
                    }
                }
            })
        })
    </script>
@endsection
