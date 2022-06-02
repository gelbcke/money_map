@extends('layouts.app', [
    'class' => 'sidebar-mini ',
    'namePage' => __('transfers.title'),
    'activePage' => 'transfers',
    'activeNav' => '',
])

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{__('transfers.title')}}</h1>
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
                        <a href="{{route('transfers.create')}}"
                           class="btn btn-sm btn-info">{{__("transfers.create_new")}}</a>
                    </div>
                    @if(session()->has('message'))
                        <div class="alert alert-success">
                            {{ session()->get('message') }}
                        </div>
                    @endif
                    <div class="card-body">
                        @if(count($transfers) > 0)
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class=" text-primary">
                                    <th>
                                        {{__("general.date")}}
                                    </th>
                                    <th>
                                        {{__("general.value")}}
                                    </th>
                                    <th style="width: 40%">
                                        {{__("transfers.bank_org") . " -> " . __("transfers.bank_dest")}}
                                    </th>
                                    </thead>
                                    <tbody>
                                    @foreach($transfers as $value)
                                        <tr>
                                            <td>
                                                {{$value->created_at->format('d/m/Y')}}
                                            </td>
                                            <td>
                                                {{__('general.M_s')}} {{number_format($value->value, 2)}}
                                            </td>
                                            <td>
                                                <b>{{$value->bank_org->name}}</b>
                                                <small>
                                                    ({{$value->bank_org->wallet->name}})
                                                </small>
                                                <i class="fas fa-arrow-right"></i>
                                                <b>{{$value->bank_dest->name}}</b>
                                                <small>
                                                    ({{$value->bank_dest->wallet->name}})
                                                </small>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info">
                                <h5><i class="icon fas fa-info"></i> {{__('messages.attention')}}</h5>
                                {{__('messages.no_records_found')}}
                            </div>
                        @endif
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
