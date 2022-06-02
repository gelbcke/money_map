@extends('layouts.app', [
    'class' => 'sidebar-mini ',
    'namePage' => __('budget.title'),
    'activePage' => 'budgets',
    'activeNav' => '',
])

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">{{__("budget.details")}}</h5>                       
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
                                    {{__("general.info.registred_by")}}
                                </th>
                                <th>
                                    {{__("general.date")}}
                                </th>
                                <th>
                                    {{__("general.bank")}}
                                </th>
                                <th>
                                    {{__("general.value")}}
                                </th>
                                <th>
                                    {{__("general.details")}}
                                </th>
                                </thead>
                                <tbody>
                                @foreach($budget_details as $value)
                                    <tr>
                                        <td>
                                            {{$value->user->name}}
                                        </td>
                                        <td>
                                            {{$value->date->format('d/m/Y')}}
                                        </td>
                                        <td>
                                            {{$value->bank->name}} 
                                        </td>
                                        <td>
                                            {{__('general.M_s')." ".number_format($value->value, 2)}}
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
    </section>
@endsection
