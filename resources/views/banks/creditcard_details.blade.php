@extends('layouts.app', [
    'class' => 'sidebar-mini ',
    'namePage' => __('bank.credit_card_details'),
    'activePage' => 'banks',
    'activeNav' => '',
])

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{$cc_info->first()->bank->name}}</h1>
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
                            <h3 class="card-title">{{__('bank.credit_card_info')}}</h3>
                            <div class="card-tools">
                                <a href="{{route('banks.index')}}"
                                   class="btn btn-sm btn-info">{{__("general.menu.go_back")}}</a>
                            </div>
                        </div>
                        <div class="card-body">
                            @foreach($cc_info->get() as $value)
                                <b>{{__("bank.credit_limit")}}
                                    :</b> {{__('general.M_s') ." ".number_format($value->credit_limit, 2)}}
                                <br>

                                <b>{{__("bank.close_invoice")}}
                                    :</b> {{__('general.every_day') ." ".$value->close_invoice}}
                                <br>

                                <b>{{__("bank.due_date")}}:</b>  {{__('general.every_day') ." ".$value->due_date}}
                                <br>
                            @endforeach
                            <hr>
                            <div class="text-center">
                                <b>{{__('bank.invoice_this_month')}}</b>
                                <br>
                                {{__('general.M_s') ." ".number_format($invoice_this_month, 2)}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{__('bank.credit_card_parcels')}}</h3>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class="text-primary">
                                    <th>
                                        {{__("bank.due_date")}}
                                    </th>
                                    <th>
                                        {{__("bank.credit_parcels.parcel_value")}}
                                    </th>
                                    <th>
                                        {{__("general.details")}}
                                    </th>
                                    </thead>
                                    <tbody>
                                    @foreach($cc_parcels as $value)
                                        <tr>
                                            <td>
                                                {{$value->date->format('d/m/Y')}}
                                            </td>
                                            <td>
                                                {{__('general.M_s') ." ".number_format($value->parcel_vl, 2)}}
                                            </td>
                                            <td>
                                                {{$value->expense->details}}
                                            </td>

                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{__('bank.credit_card_expenses')}}</h3>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class=" text-primary">
                                    <th>
                                        {{__("general.date")}}
                                    </th>
                                    <th>
                                        {{__("bank.credit_parcels.parcel_value")}}
                                    </th>
                                    <th>
                                        {{__("general.details")}}
                                    </th>
                                    </thead>
                                    <tbody>
                                    @foreach($cc_expenses as $value)
                                        <tr>
                                            <td>
                                                {{$value->date->format('d/m/Y')}}
                                            </td>
                                            <td>
                                                {{__('general.M_s') ." ".number_format($value->value, 2)}}
                                            </td>
                                            <td>
                                                {{$value->details}}
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
