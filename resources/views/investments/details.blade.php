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
					<h1>{{ __('investments.title') }}</h1>
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
						<h5 class="card-title"><b>{{ $investment->ticker . ' - ' . $investment->bank->name }}</b>
							<br>
							<small>
								{{ $investment->details }} - ID {{ $investment->id }}
							</small>
						</h5>
						<div class="card-tools">
							<a href="{{ route('investments.index') }}" class="btn btn-sm btn-info">{{ __('general.menu.go_back') }}</a>
						</div>
					</div>
					<div class="card-body">
						Total Investido:
						{{ __('general.M_s') .' ' .number_format($total_spent = $investment->whereNull('org_id')->where('ticker', $investment->ticker)->where('bank_id', $investment->bank_id)->sum('value'),2) }}
						@if ($investment->invest_group == 'RV')
							<br>
							Carteira:
							{{ __('general.M_s') .' ' .number_format($total_now =collect($client->getQuote($investment->ticker))->get('ask') *$investment->where('bank_id', $investment->bank_id)->whereNull('org_id')->where('ticker', $investment->ticker)->sum('quantity'),2) }}
							<br>
							Preco medio:
							{{ __('general.M_s') .' ' .($pm = number_format($investment->whereNull('org_id')->where('bank_id', $investment->bank_id)->where('ticker', $investment->ticker)->sum('value') /$investment->where('bank_id', $investment->bank_id)->whereNull('org_id')->where('ticker', $investment->ticker)->sum('quantity'),2)) }}
							<br>
							P/L: {{ __('general.M_s') . ' ' . ($pl = number_format($total_now - $total_spent, 2)) }}
							<br>

							Var: {{ number_format((($total_now - $total_spent) / $total_spent) * 100, 2) }} %
						@endif
						<br>
						Rendimentos:
						{{ __('general.M_s') .' ' .number_format($dividends = $investment->where('bank_id', $investment->bank_id)->whereNotNull('org_id')->where('ticker', $investment->ticker)->sum('value'),2) }}

						<hr>
						Lucro Real:
						@if ($investment->invest_group == 'RV')
							{{ __('general.M_s') .' ' .number_format($investment->where('bank_id', $investment->bank_id)->whereNotNull('org_id')->where('ticker', $investment->ticker)->sum('value') + $pl,2) }}
						@else
							{{ __('general.M_s') . ' ' . number_format($total_spent + $dividends, 2) }}
						@endif
					</div>
				</div>
			</div>
		</div>
		<div class="card-body">
			<!-- The timeline -->
			<div class="timeline timeline-inverse">
				<!-- timeline time label -->
				<div class="time-label">
					<span class="bg-danger">
						{{ date('d M Y') }}
					</span>
				</div>
				<!-- /.timeline-label -->
				@foreach ($investment_rec as $investment)
					<!-- timeline item -->
					<div>
						@if ($investment->operation == 'IN' && $investment->org_id == null)
							<i class="fas fa-cart-shopping bg-primary"></i>
						@elseif($investment->operation == 'IN' && $investment->org_id != null)
							<i class="fas fa-arrow-up bg-success"></i>
						@else
							<i class="fas fa-arrow-down bg-danger"></i>
						@endif
						<div class="timeline-item">
							<span class="time"><i class="far fa-calendar"></i> {{ $investment->date->format('d/m/Y') }}</span>

							<h3 class="timeline-header"><a
									href="{{ route('banks.show', $investment->bank->id) }}">{{ $investment->bank->name }}</a>
								<br>{{ __('general.M_s') . ' ' . number_format($investment->value, 2) }}
							</h3>

							<div class="timeline-body">
								{{ $investment->quantity . ' UN - ' . __('investments.price') . ' ' . __('general.M_s') . ' ' . number_format($investment->buy_price, 2) }}
								<br>
								{{ $investment->details }}
							</div>
						</div>
					</div>
					<!-- END timeline item -->
				@endforeach
				<div>
					<i class="far fa-clock bg-gray"></i>
				</div>
			</div>
		</div>
	</section>
@endsection
