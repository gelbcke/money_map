@extends('layouts.app', [
    'class' => 'sidebar-mini ',
    'namePage' => __("invoices.title"),
    'activePage' => 'invoices',
    'activeNav' => '',
])

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{__('bank.invoice.title')}}</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <div class="invoice p-3 mb-3">
        <!-- title row -->
        <div class="row">
            <div class="col-12">
                <h4>
                    <i class="fas fa-money-bill"></i> {{config('app.name')}}
                </h4>
            </div>
            <!-- /.col -->
        </div>
        <!-- info row -->
        <div class="row invoice-info">
            <div class="col-sm-4 invoice-col">
                {{__('general.info.registred_by')}}
                <address>
                    <strong>{{config('app.name')}}.</strong>
                </address>
            </div>
            <!-- /.col -->
            <div class="col-sm-4 invoice-col">
                {{__('general.info.belong_to')}}
                <address>
                    <strong>{{$user->name}}</strong><br>
                    Email: {{$user->email}}
                </address>
            </div>
            <!-- /.col -->
            <div class="col-sm-4 invoice-col">
                <b>{{__('bank.due_date')}}</b>
                @if(\Carbon\Carbon::now()->format('d') > $cc_info->first()->due_date)
                    {{\Carbon\Carbon::now()->addMonth()->setDay($cc_info->first()->due_date)->format('d/m/Y')}}<br>
                @else
                    {{\Carbon\Carbon::now()->setDay($cc_info->first()->due_date)->format('d/m/Y')}}<br>
                @endif
                <b>{{__('general.account')}}
                    :</b> {{$cc_info->first()->bank->name ." (". $cc_info->first()->bank->wallet->name.")"}}
                <br>
                <b>{{__('general.total_to_pay')}}:</b> {{__('general.M_s') ." ".number_format($invoices, 2)}}
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

        <!-- Table row -->
        <div class="row">
            <div class="col-12 table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th style="width: 10%">
                            {{__('general.date')}}
                        </th>
                        <th style="width: 20%; text-align: center">
                            {{__('general.info.registred_by')}}
                        </th>
                        <th>
                            {{__('general.details')}}
                        </th>

                        <th style="text-align:right; width: 20%">
                            {{__("general.value")}}
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($cc_expenses as $value)
                        <tr>
                            <td>
                                {{$value->date->format('d/m/Y')}}
                            </td>
                            <td style="width: 20%; text-align: center">
                                {{$value->user->name}}
                            </td>
                            <td>
                                {{$value->details}}
                            </td>
                            <td style="text-align:right">
                                {{__('general.M_s') ." ".number_format($value->value, 2)}}
                            </td>
                        </tr>
                    @endForeach
                    @foreach($cc_parcels as $value)
                        <tr>
                            <td>
                                {{$value->date->format('d/m/Y')}}
                            </td>
                            <td style="width: 20%; text-align: center">
                                {{$value->expense->user->name}}
                            </td>
                            <td>
                                {{$value->expense->details}}
                                - {{$value->parcel_nb ."/".$value->where('expense_id',$value->expense_id)->count('expense_id')}}
                            </td>
                            <td style="text-align:right">
                                {{__('general.M_s') ." ".number_format($value->parcel_vl, 2)}}
                            </td>
                        </tr>
                    @endForeach
                    </tbody>
                </table>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

        <div class="row">
            <div class="col-6">
            </div>
            <!-- /.col -->
            <div class="col-6">
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <th>Total:</th>
                            <td> {{__('general.M_s') ." ".number_format($invoices, 2)}}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

        <!-- this row will not appear when printing -->
        <div class="row no-print">
            <div class="col-12">
                <button type="button" class="btn btn-success float-right"><i class="far fa-credit-card"></i> Submit
                    Payment
                </button>
                <button type="button" class="btn btn-primary float-right" style="margin-right: 5px;">
                    <i class="fas fa-download"></i> Generate PDF
                </button>
            </div>
        </div>
    </div>
    <!-- /.invoice -->

@endsection
