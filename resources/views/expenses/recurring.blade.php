@extends('layouts.app', [
    'class' => 'sidebar-mini ',
    'namePage' => 'Expenses',
    'activePage' => 'expenses',
    'activeNav' => '',
])

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{__('user_groups.title')}}</h1>
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
                        <h5 class="title">{{__(" Expenses")}}</h5>
                        <a href="{{route('expenses.create')}}" class="btn btn-sm btn-info">{{__(" Create")}}</a>
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
                        <div class="table-responsive">
                            <table class="table">
                                <thead class=" text-primary">
                                <th>
                                    {{__("Date")}}
                                </th>
                                <th>
                                    {{__("Value")}}
                                </th>
                                <th>
                                    {{__("Wallet")}} / {{__("Account")}}
                                </th>
                                <th>

                                </th>
                                </thead>
                                <tbody>
                                @foreach($expenses as $value)
                                    <tr>
                                        <td>
                                            {{$value->date->format('d/m/Y')}}
                                        </td>
                                        <td>
                                            {{number_format($value->value, 2)}}
                                            @if($value->rec_expense != NULL)
                                                <small>
                                                    <i class="now-ui-icons arrows-1_refresh-69"
                                                       title="{{__("Recurring Expense")}}"></i>
                                                </small>
                                            @endif
                                            @if($value->parcels != NULL)
                                                <small class="text-info">
                                                    <b>- {{$value->parcels}}
                                                        x {{number_format($value->parcel_vl, 2)}}</b>
                                                </small>
                                            @endif
                                        </td>
                                        <td>
                                            <b>{{$value->bank->name}}</b> - {{$value->bank->payment_method}}
                                            ({{$value->bank->wallet->name}})
                                        </td>
                                        <td><a href="{{route('expenses.show', $value->id)}}">Details</a></td>
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
