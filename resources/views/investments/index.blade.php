@extends('layouts.app', [
    'class' => 'sidebar-mini ',
    'namePage' => __('investments.title'),
    'activePage' => 'investments',
    'activeNav' => '',
])

@section('content')
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<!-- TradingView Widget BEGIN -->
		<div class="tradingview-widget-container">
			<div class="tradingview-widget-container__widget"></div>
			<script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-tickers.js" async>
				{
					"symbols": [{
							"description": "USD/BRL",
							"proName": "FX_IDC:USDBRL"
						},
						{
							"description": "Bovespa",
							"proName": "INDEX:IBOV"
						},
						{
							"proName": "BITSTAMP:BTCUSD",
							"title": "Bitcoin"
						},
						{
							"proName": "BITSTAMP:ETHUSD",
							"title": "Ethereum"
						}
					],
					"colorTheme": "dark",
					"isTransparent": false,
					"showSymbolLogo": true,
					"locale": "en"
				}
			</script>
		</div>
		<!-- TradingView Widget END -->
	</section>

	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">

						<div class="card-tools">
							<a href="{{ route('investments.calculator') }}" class="btn btn-sm btn-default">
								{{ __('investments.calculator') }}
							</a>
							<a href="{{ route('investments.create') }}" class="btn btn-sm btn-info">
								{{ __('investments.create_new') }}
							</a>
						</div>
					</div>
					<div class="card-body p-0">
						<div class="table-responsive">
							<table class="table">
								<thead class="text-primary">
									<th>
										{{ __('general.wallet') }} / {{ __('general.account') }}
									</th>
									<th>
										{{ __('investments.ticket') }}
									</th>
									<th>
										{{ __('investments.average_price') }}
									</th>
									<th>
										{{ __('investments.current_quote') }}
									</th>
									<th>
										P/L
									</th>
									<th style="width: 10%">
									</th>
								</thead>
								<tbody>
									@foreach ($investments as $value)
										<span style="display: none;">
											{{ $get_invest = $value->whereNull('org_id')->where('ticker', $value->ticker)->where('bank_id', $value->bank_id)->get() }}
										</span>
										<tr>
											<td>
												<b>{{ $value->bank->name }}</b> ({{ $value->bank->wallet->name }})
											</td>
											<td title="{{ collect($client->getQuote($value->ticker))->get('longName') }}">
												<a href="{{ route('investments.show', $value->id) }}">
													{{ $value->ticker }}
												</a>
											</td>
											<td>
												@if ($value->invest_group == 'RV')
													{{ __('general.M_s') .' ' .number_format($pm =$get_invest->sum('value') /$value->where('bank_id', $value->bank_id)->whereNull('org_id')->where('ticker', $value->ticker)->sum('quantity'),2) }}
												@else
													{{ __('general.M_s') . ' ' . number_format($get_invest->sum('value'), 2) }}
												@endif
											</td>
											<td>
												@if ($value->invest_group == 'RV')
													{{ __('general.M_s') . ' ' . number_format(collect($client->getQuote($value->ticker))->get('ask'), 2) }}
												@else
													{{ __('general.M_s') . ' ' . number_format($get_invest->sum('value'), 2) }}
												@endif
											</td>
											<td>
												Total Investido:
												{{ __('general.M_s') . ' ' . number_format($total_spent = $get_invest->sum('value'), 2) }}
												@if ($value->invest_group == 'RV')
													<br>
													Carteira:
													{{ __('general.M_s') . ' ' . number_format($total_now = collect($client->getQuote($value->ticker))->get('ask') * $get_invest->sum('quantity'), 2) }}
													<br>
													P/L:
													@if (($pl = $total_now - $total_spent) < 0)
														<a class="text-danger">
														@else
															<a class="text-success">
													@endif

													{{ __('general.M_s') . ' ' . number_format($pl, 2) }}
													</a>

													<br>
													Var: {{ number_format((($total_now - $total_spent) / $total_spent) * 100, 2) }} %
												@endif
												<hr>
												Rendimentos:
												{{ __('general.M_s') .' ' .number_format($value->where('bank_id', $value->bank_id)->whereNotNull('org_id')->where('ticker', $value->ticker)->sum('value'),2) }}
												@if ($value->invest_group == 'RV')
													<br>
													P/L Real:
													@if (
														($real_pl =
															$value->where('bank_id', $value->bank_id)->whereNotNull('org_id')->where('ticker', $value->ticker)->sum('value') + $pl) < 0)
														<a class="text-danger">
														@else
															<a class="text-success">
													@endif
													{{ __('general.M_s') . ' ' . number_format($real_pl, 2) }}
													</a>
												@endif
											</td>
											<td>
												<a href="#insert_yield_{{ $value->id }}" data-toggle="modal"
													data-target="#insert_yield_{{ $value->id }}">
													<i class="fa fa-money-bill-trend-up" title="{{ __('investments.insert_yield') }}"></i>
												</a>
												<div class="modal fade" id="insert_yield_{{ $value->id }}" style="display: none;" aria-hidden="true">
													<div class="modal-dialog modal-xl">
														<div class="modal-content">
															<div class="modal-header">
																<h4 class="modal-title">{{ __('investments.insert_yield') }}</h4>
																<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																	<span aria-hidden="true">×</span>
																</button>
															</div>

															<form method="post" action="{{ route('investments.insert_yield', $value->id) }}" autocomplete="off">
																<div class="modal-body">
																	@csrf
																	@method('post')
																	@include('alerts.success')

																	<div class="modal-header">
																		<h4 class="modal-title">
																			<b>{{ $value->invest_group . ': ' . $value->ticker }}</b>
																			({{ $value->bank->name }})
																		</h4>
																		<h6>
																			<b>
																				{{ collect($client->getQuote($value->ticker))->get('longName') }}
																			</b>
																			<br>
																			{{ $value->details }}
																		</h6>
																	</div>

																	<div class="row">
																		<div class="col-md-4 pr-1">
																			<div class="form-group">
																				<label for="date">{{ __('general.date') }}</label>
																				<input type="date" name="date" class="form-control"
																					value="{{ old('date') ?? date('Y-m-d') }}">
																				@include('alerts.feedback', ['field' => 'date'])
																			</div>
																		</div>
																		<div class="col-md-4 pr-1">
																			<div class="form-group">
																				<label for="value">{{ __('general.value') }}</label>
																				<input type="number" step="any" name="value" id="value" class="form-control"
																					value="{{ old('value') }}" oninput="calc();">
																				@include('alerts.feedback', ['field' => 'value'])
																			</div>
																		</div>
																	</div>
																	<hr class="half-rule" />
																	<div class="row">
																		<div class="col-md-12 pr-1">
																			<div class="form-group">
																				<label for="details">{{ __('general.details') }}</label>
																				<textarea name="details" class="form-control" value="{{ old('details') }}"></textarea>
																				@include('alerts.feedback', ['field' => 'details'])
																			</div>
																		</div>
																	</div>
																</div>
																<div class="modal-footer justify-content-between">
																	<button type="button" class="btn btn-default"
																		data-dismiss="modal">{{ __('general.menu.cancel') }}</button>
																	<button type="submit" class="btn btn-primary">{{ __('general.menu.save') }}</button>
																</div>
															</form>
														</div>
														<!-- /.modal-content -->
													</div>
													<!-- /.modal-dialog -->
												</div>
												| <a href="{{ route('investments.show', $value->id) }}"><i class="fa fa-eye"></i></a>
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
					<strong class="mr-auto">{{ __('messages.attention') }}</strong>
					<button data-dismiss="toast" type="button" class="close ml-2 mb-1" aria-label="Close">
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
