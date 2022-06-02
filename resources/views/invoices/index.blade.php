@extends('layouts.app', [
    'class' => 'sidebar-mini ',
    'namePage' => __("bank.invoice.title"),
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
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">{{__("bank.credit_card")}}</h5>
                    </div>
                    <div class="card-body">
                        @if($banks->count() > 0)
                            <table class="table">
                                <thead class=" text-primary">
                                <th>
                                    {{__('bank.credit_card')}} / {{__('general.menu.accounts')}}
                                </th>
                                <th>
                                    {{__("bank.due_date")}}
                                </th>
                                <th>
                                    {{__("bank.credit_limit")}}
                                </th>
                                <th style="width: 2%">
                                </th>
                                </thead>
                                <tbody>
                                @foreach($banks as $value)
                                    <tr>
                                        <td>
                                            {{$value->name}} <small>({{$value->user->name}})</small>
                                        </td>
                                        <td>
                                            @if(\Carbon\Carbon::now()->format('d') > $value->credit_card[0]->due_date)
                                                {{\Carbon\Carbon::now()->addMonth()->setDay($value->credit_card[0]->due_date)->format('d/m/Y')}}
                                                <br>
                                            @else
                                                {{\Carbon\Carbon::now()->setDay($value->credit_card[0]->due_date)->format('d/m/Y')}}
                                                <br>
                                            @endif
                                        </td>
                                        <td>
                                            {{__('general.M_s') . " " . number_format($value->credit_card[0]->credit_limit, 2)}}
                                        </td>
                                        <td>
                                            <a href="{{route('invoices.show', $value->id)}}">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @else
                            {{__('bank.no_credit_card')}}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
