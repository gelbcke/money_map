@extends('layouts.app', [
    'class' => 'sidebar-mini ',
    'namePage' => __('transfers.title'). " / " .__('general.menu.create'),
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
                        <a href="{{route('transfers.index')}}"
                           class="btn btn-sm btn-info">{{__("general.menu.go_back")}}</a>
                    </div>
                    <div class="card-body">
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
                        <form method="post" action="{{ route('transfers.store') }}" autocomplete="off">
                            @csrf
                            @method('post')
                            @include('alerts.success')
                            <div class="row">
                            </div>
                            <div class="row">
                                <div class="col-md-6 pr-1">
                                    <div class="form-group">
                                        <label for="date">{{__("general.date")}}</label>
                                        <input type="date" name="date" class="form-control"
                                               value="{{ old('date') ?? date('Y-m-d')}}">
                                        @include('alerts.feedback', ['field' => 'date'])
                                    </div>
                                </div>
                                <div class="col-md-6 pr-1">
                                    <div class="form-group">
                                        <label for="value">{{__("general.value")}}</label>
                                        <input type="number" min="1" step="any" name="value" id="value"
                                               class="form-control" value="{{ old('value') }}" oninput="calc();">
                                        @include('alerts.feedback', ['field' => 'value'])
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-5 pr-1">
                                    <div class="form-group">
                                        <label for="org_bank_id">{{__("transfers.bank_org")}}
                                            / {{__("general.account")}}</label>
                                        <select id="org_bank_id" name="org_bank_id" class="form-control">
                                            <option value=""> --- {{__("general.menu.select")}} ---</option>
                                            @foreach($banks as $value)
                                                <option value="{{$value->id}}"><b>{{$value->name}}</b>
                                                    - {{$value->payment_method}} ({{$value->wallet->name}}) -
                                                    {{
                                                        __('bank.balance') ." - " .__('general.M_s')." ".
                                                        number_format((
                                                        \App\Models\Income::where('bank_id', $value->id)->sum('value')
                                                        -
                                                        \App\Models\Expense::whereNull('parcels')->where('bank_id', $value->id)->sum('value')
                                                        )
                                                        -
                                                        (
                                                        \App\Models\Transfer::where('org_bank_id', $value->id)->sum('value')
                                                        -
                                                        \App\Models\Transfer::where('dest_bank_id', $value->id)->sum('value')
                                                        ),2)
                                                    }}
                                                </option>
                                            @endforeach
                                        </select>

                                        @include('alerts.feedback', ['field' => 'org_bank_id'])
                                    </div>
                                </div>

                                <div class="col-md-2 pr-1">
                                    <div style="left: 40%; top: 40%; position: absolute">
                                        <i class="fas fa-2x fa-arrow-alt-circle-right"></i>
                                    </div>
                                </div>
                                <div class="col-md-5 pr-1">
                                    <div class="form-group">
                                        <label for="dest_bank_id">{{__("transfers.bank_dest")}}
                                            / {{__("general.account")}}</label>
                                        <select id="dest_bank_id" name="dest_bank_id" class="form-control">
                                            <option value=""> --- {{__("general.menu.select")}} ---</option>
                                            @foreach($banks as $value)
                                                <option value="{{$value->id}}"><b>{{$value->name}}</b>
                                                    - {{$value->payment_method}} ({{$value->wallet->name}})
                                                </option>
                                            @endforeach
                                        </select>
                                        @include('alerts.feedback', ['field' => 'dest_bank_id'])
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success">{{__('general.menu.save')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection


