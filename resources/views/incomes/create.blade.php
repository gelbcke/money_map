@extends('layouts.app', [
    'class' => 'sidebar-mini ',
    'namePage' => __('incomes.title'). " / " .__('general.menu.create'),
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
                        <a href="{{route('incomes.index')}}"
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
                        <form method="post" action="{{ route('incomes.store') }}" autocomplete="off">
                            @csrf
                            @method('post')
                            @include('alerts.success')
                            <div class="row">
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
                                        <input type="number" min="1" step="any" name="value" id="value"
                                               class="form-control" value="{{ old('value') }}" oninput="calc();">
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

                            <input type="checkbox" id="rec_income" name="rec_income" value="1">
                            <label for="rec_income">{{__("incomes.recurring")}}</label>

                            <hr>
                            <button type="submit" class="btn btn-success">{{__('general.menu.save')}}</button>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

