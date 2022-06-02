@extends('layouts.app', [
    'class' => 'sidebar-mini ',
    'namePage' => 'Wallets',
    'activePage' => 'wallets',
    'activeNav' => '',
])

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{$wallets[0]->name}}</h1>
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
                            <div class="card-tools">
                                <a href="{{route('wallets.index')}}"
                                   class="btn btn-sm btn-info">{{__("general.menu.go_back")}}</a>
                            </div>
                        </div>
                        <div class="card-body">
                            @foreach($wallets as $vl)
                                @if($vl->bank->count() > 0)
                                    <table class="table">
                                        <thead class=" text-primary">
                                        <th>
                                            {{__('general.menu.banks')}} / {{__('general.menu.accounts')}}
                                        </th>
                                        <th>
                                            {{__("bank.functions")}}
                                        </th>
                                        </thead>
                                        <tbody>
                                        @foreach($vl->bank as $value)
                                            <tr>
                                                <td>
                                                    <a href="{{route('banks.show', $value->id)}}">
                                                    {{$value->name}}
                                                    </a>
                                                        <small>
                                                            ({{__('general.owner')}}: {{$value->user->name}})
                                                        </small>
                                                </td>
                                                <td>
                                                    {{$value->payment_method}}
                                                    @if($value->f_deb != NULL)
                                                        <b title="{{__("general.debit")}}">D</b>
                                                    @endif
                                                    @if($value->f_cred != NULL)
                                                        <a href="{{route('banks.show', $value->id)}}">
                                                            <b title="{{__("general.credit")}}">C</b>
                                                        </a>
                                                    @endif
                                                    @if($value->f_invest != NULL)
                                                        <b title="{{__("general.investment")}}">I</b>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    {{__('bank.no_banks')}}
                                @endif
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
