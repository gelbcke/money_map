@extends('layouts.app', [
    'class' => 'sidebar-mini ',
    'namePage' => __("credit_parcels.title"),
    'activePage' => 'credit_parcels',
    'activeNav' => '',
])

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{__("bank.credit_parcels.title")}}</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
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
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table">
                                <thead class=" text-primary">
                                <th>
                                    {{__("general.details")}}
                                </th>
                                <th>
                                    {{__("bank.credit_parcels.parcel_value")}}
                                </th>
                                <th>
                                    {{__("general.account")}} / {{__("general.wallet")}}
                                </th>
                                <th style="width: 10%">
                                    {{__('general.date')}}
                                </th>
                                </thead>
                                <tbody>
                                @foreach($expenses as $value)
                                    <tr>
                                        <td>
                                            {{$value->expense->details}}
                                        </td>
                                        <td>
                                            {{__('general.M_s')}} {{number_format($value->parcel_vl, 2)}}

                                        </td>
                                        <td>
                                            <b>{{$value->bank->name}}</b> - {{$value->bank->payment_method}}
                                            ({{$value->bank->wallet->name}})
                                        </td>
                                        <td>
                                            {{$value->date->format('d/m/Y')}}
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
    </section>
@endsection
