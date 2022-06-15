@extends('layouts.app', [
'class' => 'sidebar-mini ',
'namePage' => __('incomes.title'),
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
                    <a href="{{route('incomes.create')}}" class="btn btn-sm btn-info">{{__("incomes.create_new")}}</a>
                </div>
                @if(session()->has('message'))
                <div class="alert alert-success">
                    {{ session()->get('message') }}
                </div>
                @endif
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead class=" text-primary">
                                <th>
                                    {{__("general.date")}}
                                </th>
                                <th>
                                    {{__("general.value")}}
                                </th>
                                <th>
                                    {{__("general.wallet")}} / {{__("general.account")}}
                                </th>
                                <th style="width: 20%">

                                </th>
                            </thead>
                            <tbody>
                                @foreach($incomes as $value)
                                <tr>
                                    <td>
                                        {{$value->date->format('d/m/Y')}}
                                    </td>
                                    <td>
                                        {{__('general.M_s')}} {{number_format($value->value, 2)}}
                                        @if($value->rec_income)
                                        <small>
                                            <i class="fas fa-sync-alt" title="{{__("incomes.recurring")}}"></i>
                                        </small>
                                        @endif
                                    </td>
                                    <td>
                                        <b>{{$value->bank->name}}</b> ({{$value->bank->wallet->name}})
                                    </td>
                                    <td>
                                        @if($value->confirmed == 0)
                                        <a style="color: green;" href="{{route('incomes.confirm_recepit', $value->id)}}">
                                            {{__('incomes.confirm_receipt')}}
                                        </a>
                                        <br>
                                        @endif
                                        <form action="{{ route('incomes.cancel_rec',$value->org_id) }}" method="POST">
                                            <a href="{{route('incomes.show', $value->id)}}">{{__('general.details')}}</a>
                                            <br>
                                            @csrf
                                            @method('POST')
                                            <a type="submit" style="color: red">{{__('incomes.cancel_rec')}}</a>
                                        </form>
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


@if ($errors->any())
<div class="toasts-top-right fixed">
    <div class="toast bg-danger fade show" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="mr-auto">{{__('messages.attention')}}</strong>
            <button data-dismiss="toast" type="button" class="ml-2 mb-1 close" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="toast-body">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </div>
    </div>
</div>
@endif
@endsection