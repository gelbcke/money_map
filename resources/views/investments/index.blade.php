@extends('layouts.app', [
    'class' => 'sidebar-mini ',
    'namePage' => __('investments.title'),
    'activePage' => 'investments',
    'activeNav' => '',
])

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{__('investments.title')}}</h1>
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
                        <button data-toggle="modal" data-target="#create_new" class="btn btn-sm btn-info">
                            {{__("investments.create_new")}}
                        </button>
                    </div>
                    <div class="card-body p-0">
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
                                <th style="width: 10%">
                                </th>
                                </thead>
                                <tbody>
                                @foreach($investments as $value)
                                    <tr>
                                        <td>
                                            {{$value->date->format('d/m/Y')}}
                                        </td>
                                        <td>
                                            {{__('general.M_s')}} {{number_format($value->value, 2)}}
                                        </td>
                                        <td>
                                            <b>{{$value->bank->name}}</b> ({{$value->bank->wallet->name}})
                                        </td>
                                        <td>
                                            <a href="#insert_yield_{{$value->id}}" data-toggle="modal"
                                               data-target="#insert_yield_{{$value->id}}">
                                                <i class="fa fa-money-bill-trend-up" title="{{__("investments.insert_yield")}}"></i>
                                            </a>
                                            <div class="modal fade" id="insert_yield_{{$value->id}}"
                                                 style="display: none;" aria-hidden="true">
                                                <div class="modal-dialog modal-xl">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">{{__("investments.insert_yield")}}</h4>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                <span aria-hidden="true">×</span>
                                                            </button>
                                                        </div>

                                                        <form method="post"
                                                              action="{{ route('investments.insert_yield', $value->id) }}"
                                                              autocomplete="off">
                                                            <div class="modal-body">
                                                                @csrf
                                                                @method('post')
                                                                @include('alerts.success')

                                                                <div class="modal-header">
                                                                    <h4 class="modal-title">
                                                                        <b>{{$value->bank->name}}</b>
                                                                        ({{$value->bank->wallet->name}})</h4>
                                                                    <h6>{{$value->details}}</h6>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-md-4 pr-1">
                                                                        <div class="form-group">
                                                                            <label
                                                                                for="date">{{__("general.date")}}</label>
                                                                            <input type="date" name="date"
                                                                                   class="form-control"
                                                                                   value="{{ old('date') ?? date('Y-m-d')}}">
                                                                            @include('alerts.feedback', ['field' => 'date'])
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4 pr-1">
                                                                        <div class="form-group">
                                                                            <label
                                                                                for="value">{{__("general.value")}}</label>
                                                                            <input type="number" min="1" step="any"
                                                                                   name="value" id="value"
                                                                                   class="form-control"
                                                                                   value="{{ old('value') }}"
                                                                                   oninput="calc();">
                                                                            @include('alerts.feedback', ['field' => 'value'])
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <hr class="half-rule"/>
                                                                <div class="row">
                                                                    <div class="col-md-12 pr-1">
                                                                        <div class="form-group">
                                                                            <label
                                                                                for="details">{{__("general.details")}}</label>
                                                                            <textarea name="details"
                                                                                      class="form-control"
                                                                                      value="{{ old('details') }}"></textarea>
                                                                            @include('alerts.feedback', ['field' => 'details'])
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer justify-content-between">
                                                                <button type="button" class="btn btn-default"
                                                                        data-dismiss="modal">{{__('general.menu.cancel')}}</button>
                                                                <button type="submit"
                                                                        class="btn btn-primary">{{__('general.menu.save')}}</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <!-- /.modal-content -->
                                                </div>
                                                <!-- /.modal-dialog -->
                                            </div>
                                            | <a
                                                href="{{route('investments.show', $value->id)}}"><i
                                                    class="fa fa-eye"></i></a>
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
    <div class="modal fade" id="create_new" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{__("investments.create_new")}}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>

                <form method="post" action="{{ route('investments.store') }}" autocomplete="off">
                    <div class="modal-body">
                        @csrf
                        @method('post')
                        @include('alerts.success')
                        <div class="row">
                            <div class="col-md-12 pr-1">
                                <div class="form-group">
                                    <label for="budget_id">{{__("general.menu.budget")}}</label>
                                    <select id="budget_id" name="budget_id" class="form-control">
                                        <option value=""> --- {{__("general.menu.select")}} ---</option>
                                        @foreach($budgets as $value)
                                            <option value="{{$value->id}}">{{__('budget.'.$value->name)}}</option>
                                        @endforeach
                                    </select>
                                    @include('alerts.feedback', ['field' => 'budget_id'])
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 pr-1">
                                <div class="form-group">
                                    <label for="date">{{__("general.date")}}</label>
                                    <input type="date" name="date" class="form-control"
                                           value="{{ old('date') ?? date('Y-m-d')}}">
                                    @include('alerts.feedback', ['field' => 'date'])
                                </div>
                            </div>
                            <div class="col-md-4 pr-1">
                                <div class="form-group">
                                    <label for="value">{{__("general.value")}}</label>
                                    <input type="number" min="1" step="any" name="value" id="value" class="form-control"
                                           value="{{ old('value') }}" oninput="calc();">
                                    @include('alerts.feedback', ['field' => 'value'])
                                </div>
                            </div>
                            <div class="col-md-4 pr-1">
                                <div class="form-group">
                                    <label for="bank_id">{{__("general.bank")}} / {{__("general.account")}}</label>
                                    <select id="bank_id" name="bank_id" class="form-control">
                                        <option value=""> --- {{__("general.menu.select")}} ---</option>
                                        @foreach($banks as $value)
                                            <option value="{{$value->id}}"><b>{{$value->name}}</b>
                                                - {{$value->payment_method}} ({{$value->wallet->name}})
                                            </option>
                                        @endforeach
                                    </select>
                                    @include('alerts.feedback', ['field' => 'bank_id'])
                                </div>
                            </div>
                        </div>
                        <hr class="half-rule"/>
                        <div class="row">
                            <div class="col-md-12 pr-1">
                                <div class="form-group">
                                    <label for="details">{{__("general.details")}}</label>
                                    <textarea name="details" class="form-control"
                                              value="{{ old('details') }}"></textarea>
                                    @include('alerts.feedback', ['field' => 'details'])
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default"
                                data-dismiss="modal">{{__('general.menu.cancel')}}</button>
                        <button type="submit" class="btn btn-primary">{{__('general.menu.save')}}</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>



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
