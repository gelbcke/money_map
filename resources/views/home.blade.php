@extends('layouts.app', [
    'namePage' => __('home.title'),
    'class' => 'login-page sidebar-mini ',
    'activePage' => 'home',
])

@section('content')
	<!-- Content Header (Page header) -->
	<section class="content-header">
	</section>
	<!-- Main content -->
	<section class="content">
		<div class="container-fluid">

			<!-- DASH HEAD -->
			<div class="row">
				<div class="col-12 col-sm-6 col-md-3">
					<div class="info-box">
						<a href="{{ route('incomes.index') }}" class="info-box-icon bg-success elevation-1">
							<span>
								<i class="fas fa-arrow-up" title="Soma de todos os valores recebidos no mês"></i>
							</span>
						</a>
						<div class="info-box-content">
							<span class="info-box-text"> {{ __('incomes.total_month') }}</span>
							<span class="info-box-number">
								{{ __('general.M_s') . ' ' . number_format($income_thisMonth, 2) }}
								<br>
								<font size="1">
									Pendentes: {{ __('general.M_s') . ' ' . number_format($incomepending_thisMonth, 2) }}
									<i class="fa-solid fa-circle-info"></i>
								</font>
							</span>
						</div>
					</div>
				</div>

				<div class="col-12 col-sm-6 col-md-3">
					<div class="info-box mb-3">
						<a href="{{ route('expenses.index') }}" class="info-box-icon bg-danger elevation-1">
							<span>
								<i class="fas fa-arrow-down" title="Soma de todas as despesas e parcelas (crédito) que vencem este mês"></i>
							</span>
						</a>
						<div class="info-box-content">
							<span class="info-box-text">{{ __('expenses.total_month') }}</span>
							<span class="info-box-number">

								<font
									@if ($total_expenses_thisMonth >= $income_thisMonth) class="text-danger"
                                @elseif($total_expenses_thisMonth >= ($income_thisMonth * 60) / 100)
                                class="text-warning" @endif>
									{{ __('general.M_s') . ' ' . number_format($total_expenses_thisMonth, 2) }}
								</font>

								<font size="1">
									<p style='margin-top:0px;  margin-bottom: 5px; line-height:0px;'>
										Recorrentes: {{ __('general.M_s') . ' ' . number_format($rec_expenses->sum('value'), 2) }}
										<i class="fa-solid fa-circle-info"></i>
									</p>
									<p style='margin-top:0px; margin-bottom: 0; line-height:0px;'>
										Parcelas: {{ __('general.M_s') . ' ' . number_format($parcels_thisMonth->sum('parcel_vl'), 2) }}
										<i class="fa-solid fa-circle-info"></i>
									</p>
								</font>

							</span>
						</div>
					</div>
				</div>

				<div class="clearfix hidden-md-up"></div>
				<div class="col-12 col-sm-6 col-md-3">
					<div class="info-box mb-3">
						<a href="{{ route('banks.index') }}" class="info-box-icon bg-info elevation-1">
							<span>
								<i class="fas fa-sack-dollar" title="Soma do saldo disponível em todas as contas"></i>
							</span>
						</a>
						<div class="info-box-content">
							<span class="info-box-text">Saldo</span>
							<span class="info-box-number">{{ __('general.M_s') . ' ' . number_format($balance, 2) }}
								<br>

								<font size="1" @if ($total_toPay >= $balance) class="text-danger" @endif>
									Faturas: {{ __('general.M_s') . ' ' . number_format($total_toPay, 2) }}
									<i class="fa-solid fa-circle-info"></i>
								</font>
							</span>
						</div>
					</div>
				</div>

				<div class="col-12 col-sm-6 col-md-3">
					<div class="info-box mb-3">
						<a href="{{ route('investments.index') }}" class="info-box-icon bg-warning elevation-1">
							<span>
								<i class="fa fa-piggy-bank"></i>
							</span>
						</a>
						<div class="info-box-content">
							<span class="info-box-text">Investido</span>
							<span class="info-box-number">{{ __('general.M_s') . ' ' . number_format($investments->sum('value'), 2) }}
								<br>
								<font size="1">
									Rendimentos: {{ __('general.M_s') . ' ' . number_format($investments_div->sum('value'), 2) }}
									<i class="fa-solid fa-circle-info"></i>
								</font>
							</span>
						</div>
					</div>
				</div>
			</div>
			<!-- END DASH HEAD -->

			<!-- BUDGETS -->
			<div class="row">
				<!-- Prev Month -->
				<div class="col-lg-6 col-md-6">
					<div class="card card-chart">
						<div class="card-header">
							<h5 class="card-category">{{ __('home.budget') }}</h5>
							<h6 class="card-title">{{ __('home.prev_month') }}</h6>
						</div>
						<div class="card-body p-0">
							<table class="table-sm table">
								<thead>
									<tr>
										<th style="width: 10px">%</th>
										<th>{{ __('general.menu.budget') }}</th>
										<th>{{ __('general.limit') }}</th>
										<th style="width: 110px">{{ __('general.max_value') }}</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($budgets->where('operation', 'OUT') as $budget)
										<tr>
											<td>{{ $budget->budget }}</td>
											<td>{{ $budget->name }}</td>
											<td>
												<div style="display: none">
													{{ $exp_calc = $cred_expenses_prevMonth->where('budget_id', $budget->id)->sum('value') + $deb_expenses_prevMonth->where('budget_id', $budget->id)->sum('value') + $parcels_prevMonth->where('budget_id', $budget->id)->sum('parcel_vl') + $rec_expenses->where('budget_id', $budget->id)->sum('value') }}
												</div>
												@if ($income_thisMonth && $exp_calc)
													<div style="display: none">
														{{ $percent = ($exp_calc / (($income_prevMonth * $budget->budget) / 100)) * 100 }}
													</div>
													<span class="badge">
														{{ __('general.M_s') . ' ' . number_format($exp_calc, 2) }}
														({{ number_format($percent, 0) }}%)
													</span>
													<div class="progress progress-xs">
														@if ($percent < 50)
															<div class="progress-bar bg-success" style="width:{{ number_format($percent, 0) }}%"></div>
														@elseif($percent >= 50 && $percent < 85)
															<div class="progress-bar bg-warning" style="width:{{ number_format($percent, 0) }}%"></div>
														@elseif($percent >= 85)
															<div class="progress-bar bg-danger" style="width:{{ number_format($percent, 0) }}%"></div>
														@endif
													</div>
												@endif
											</td>
											<td>
												<span class="badge">
													{{ __('general.M_s') . ' ' . number_format(($income_prevMonth * $budget->budget) / 100, 2) }}
												</span>
											</td>
										</tr>
									@endforeach
									@foreach ($budgets->where('operation', 'SAVE') as $budget)
										<!-- Investements-->
										<tr>
											<td>{{ $budget->budget }}</td>
											<td>{{ $budget->name }}</td>
											<td>
												<div style="display: none">
													{{ $save_calc = $investments_prevMonth->where('budget_id', $budget->id)->sum('value') }}
												</div>
												@if ($income_prevMonth && $save_calc)
													<div style="display: none">
														{{ $percent = ($save_calc / (($income_prevMonth * $budget->budget) / 100)) * 100 }}
													</div>
													<span class="badge">
														{{ __('general.M_s') . ' ' . number_format($save_calc, 2) }}
														({{ number_format($percent, 0) }}%)
													</span>
													<div class="progress progress-xs">
														@if ($percent < 50)
															<div class="progress-bar bg-danger" style="width:{{ number_format($percent, 0) }}%"></div>
														@elseif($percent >= 50 && $percent < 85)
															<div class="progress-bar bg-warning" style="width:{{ number_format($percent, 0) }}%"></div>
														@elseif($percent >= 85)
															<div class="progress-bar bg-success" style="width:{{ number_format($percent, 0) }}%"></div>
														@endif
													</div>
												@endif
											</td>
											<td>
												<span class="badge">
													{{ __('general.M_s') . ' ' . number_format(($income_prevMonth * $budget->budget) / 100, 2) }}
												</span>
											</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<!-- END Prev Month -->

				<!-- This Month -->
				<div class="col-lg-6 col-md-6">
					<div class="card card-chart">
						<div class="card-header">
							<h5 class="card-category">{{ __('home.budget') }}</h5>
							<h6 class="card-title">{{ __('home.this_month') }}</h6>
						</div>
						<div class="card-body p-0">
							<table class="table-sm table">
								<thead>
									<tr>
										<th style="width: 10px">%</th>
										<th>{{ __('general.menu.budget') }}</th>
										<th>{{ __('general.limit') }}</th>
										<th style="width: 110px">{{ __('general.max_value') }}</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($budgets->where('operation', 'OUT') as $budget)
										<tr>
											<td>{{ $budget->budget }}</td>
											<td>{{ $budget->name }}</td>
											<td>
												<div style="display: none">
													{{ $exp_calc = $cred_expenses_thisMonth->where('budget_id', $budget->id)->sum('value') + $deb_expenses_thisMonth->where('budget_id', $budget->id)->sum('value') + $parcels_thisMonth->where('budget_id', $budget->id)->sum('parcel_vl') + $rec_expenses->where('budget_id', $budget->id)->sum('value') }}
												</div>
												@if ($income_thisMonth && $exp_calc)
													<div style="display: none">
														{{ $percent = ($exp_calc / (($income_thisMonth * $budget->budget) / 100)) * 100 }}
													</div>
													<span class="badge">
														{{ __('general.M_s') . ' ' . number_format($exp_calc, 2) }}
														({{ number_format($percent, 0) }}%)
													</span>
													<div class="progress progress-xs">
														@if ($percent < 50)
															<div class="progress-bar bg-success" style="width:{{ number_format($percent, 0) }}%"></div>
														@elseif($percent >= 50 && $percent < 85)
															<div class="progress-bar bg-warning" style="width:{{ number_format($percent, 0) }}%"></div>
														@elseif($percent >= 85)
															<div class="progress-bar bg-danger" style="width:{{ number_format($percent, 0) }}%"></div>
														@endif
													</div>
												@endif
											</td>
											<td>
												<span class="badge">
													{{ __('general.M_s') . ' ' . number_format(($income_thisMonth * $budget->budget) / 100, 2) }}
												</span>
											</td>
										</tr>
									@endforeach
									@foreach ($budgets->where('operation', 'SAVE') as $budget)
										<!-- Investements-->
										<tr>
											<td>{{ $budget->budget }}</td>
											<td>{{ $budget->name }}</td>
											<td>
												<div style="display: none">
													{{ $save_calc = $investments_thisMonth->where('budget_id', $budget->id)->sum('value') }}
												</div>
												@if ($income_thisMonth && $save_calc)
													<div style="display: none">
														{{ $percent = ($save_calc / (($income_thisMonth * $budget->budget) / 100)) * 100 }}
													</div>
													<span class="badge">
														{{ __('general.M_s') . ' ' . number_format($save_calc, 2) }}
														({{ number_format($percent, 0) }}%)
													</span>
													<div class="progress progress-xs">
														@if ($percent < 50)
															<div class="progress-bar bg-danger" style="width:{{ number_format($percent, 0) }}%"></div>
														@elseif($percent >= 50 && $percent < 85)
															<div class="progress-bar bg-warning" style="width:{{ number_format($percent, 0) }}%"></div>
														@elseif($percent >= 85)
															<div class="progress-bar bg-success" style="width:{{ number_format($percent, 0) }}%"></div>
														@endif
													</div>
												@endif
											</td>
											<td>
												<span class="badge">
													{{ __('general.M_s') . ' ' . number_format(($income_thisMonth * $budget->budget) / 100, 2) }}
												</span>
											</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<!-- END This Month -->
			</div>
			<!-- END BUDGETS -->

			<!-- FUTURE PARCELS -->
			<div class="row">
				<div class="col-lg-9">
					<div class="card">
						<div class="card-header border-0">
							<div class="d-flex justify-content-between">
								<h3 class="card-title">{{ __('home.parceled_expenses') }}
									<small>({{ __('home.future_releases') }})</small>
								</h3>
								<a href="{{ route('invoices.index') }}">{{ __('general.menu.invoices') }}</a>
							</div>
						</div>
						<div class="card-body">
							@if ($parcels_byMonth)
								<div class="d-flex">
									<p class="d-flex flex-column">
										<span
											class="text-bold text-lg">{{ __('general.M_s') . ' ' . number_format($parcels_byMonth->sum('total'), 2) }}</span>
										<span>Total de Compras Parceladas</span>
									</p>
								</div>
								<!-- /.d-flex -->
								<div class="position-relative mb-4">
									<canvas id="parcel_exp_chart" height="200"></canvas>
								</div>
							@else
								<h6 class="card-footer">{{ __('messages.no_records_found') }}</h6>
							@endif
						</div>
					</div>
				</div>
				<!-- END FUTURE PARCELS -->

				<!-- CAT CHART -->
				<div class="col-md-3">
					<div class="card">
						<div class="card-header">
							<h5 class="card-category">{{ __('home.expenses') }}</h5>
							<h4 class="card-title">
								{{ __('home.by_category') }}
								<small> ( {{ __('home.this_month') }} ) </small>
							</h4>
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-md-12">
									<div class="chart-responsive">
										<canvas id="expense_by_category" height="205"></canvas>
									</div>
								</div>
							</div>
							<hr>
							<small>
								{{ __('category.empty') . ' ' . __('general.M_s') }}
								{{ number_format($exp_without_cat_thisMonth->value('total'), 2) }}
							</small>
						</div>
					</div>
				</div>
			</div>
			<!-- END CAT CHART -->

			<!-- LAST EXPENSES -->
			<div class="row">
				<div class="col-md-12">
					<div class="card">
						<div class="card-header">
							<h5 class="card-category">{{ __('home.last_expenses') }}</h5>
						</div>
						<div class="card-body p-0">
							@if ($last_expenses->count() > 0)
								<div class="table-responsive">
									<table class="table">
										<thead class="text-primary">
											<th>
												{{ __('general.date') }}
											</th>
											<th>
												{{ __('general.value') }}
											</th>
											<th>
												{{ __('general.bank') }} / {{ __('general.account') }}
											</th>
											<th>
												{{ __('general.budget') }}
											</th>
											<th class="text-right">
												{{ __('general.details') }}
											</th>
										</thead>
										<tbody>
											@foreach ($last_expenses as $value)
												<tr>
													<td>
														{{ $value->date->format('d/m/Y') }}
													</td>

													<td>
														{{ __('general.M_s') }} {{ number_format($value->value, 2) }}
													</td>
													<td>
														{{ $value->bank->name }} -
														@if ($value->payment_method == 1)
															{{ __('general.credit') }}
														@elseif($value->payment_method == 2)
															{{ __('general.debit') }}
														@elseif($value->payment_method == 3)
															{{ __('general.cash') }}
														@else
															{{ __('general.not_informed') }}
														@endif
													</td>
													<td>
														{{ $value->budget->name }}
													</td>
													<td class="text-right">
														{{ $value->details }}
													</td>
												</tr>
											@endforeach
										</tbody>
									</table>
								</div>
							@else
								<h6 class="card-footer">{{ __('messages.no_records_found') }}</h6>
							@endif
						</div>
					</div>
				</div>
			</div>
			<!-- END LAST EXPENSES -->

			<!-- INVESTMENTS -->
			<div class="row">
				<div class="col-md-3">
					<div class="card card-tasks">
						<div class="card-header">
							<h5 class="card-category">{{ __('home.investments') }}</h5>
							<h4 class="card-title">{{ __('home.by_month') }}</h4>
						</div>
						<div class="card-body p-0">
							@if ($investments_byMonth)
								<div class="table-full-width table-responsive">
									<table class="table">
										<tbody>
											@foreach ($investments_byMonth as $vl)
												<tr>
													<td>
														{{ __($vl->month) }}
													</td>
													<td>
														{{ __('general.M_s') . ' ' . number_format($vl->value, 2) }}
													</td>
												</tr>
											@endforeach
										</tbody>
									</table>
								</div>
							@else
								<h6 class="card-footer">{{ __('messages.no_records_found') }}</h6>
							@endif
						</div>
					</div>
				</div>
				<div class="col-md-9">
					<div class="card card-tasks">
						<div class="card-header">
							<h5 class="card-category">{{ __('home.investments') }}</h5>
							<h4 class="card-title">{{ __('investments.yield') }}</h4>
						</div>
						<div class="card-body p-0">
							@if ($investments_div)
								<div class="table-full-width table-responsive">
									<table class="table">
										<tbody>
											@foreach ($investments_div as $vl)
												<tr>
													<td>
														{{ $vl->ticker }}
													</td>
													<td>
														{{ $vl->details . ' (' . $vl->operation . ')' }}
													</td>
													<td>
														{{ __('general.M_s') . ' ' . number_format($vl->value, 2) }}
													</td>
													<td>
														{{ $vl->budget->name . ' (' . $vl->invest_group . ')' }}
													</td>
												</tr>
											@endforeach
										</tbody>
									</table>
								</div>
							@else
								<h6 class="card-footer">{{ __('messages.no_records_found') }}</h6>
							@endif
						</div>
					</div>
				</div>
			</div>
			<!-- END INVESTMENTS -->
		</div>
	</section>
@endsection

@push('js')
	<script src="{{ asset('assets') }}/plugins/chart.js/Chart.min.js"></script>
@endpush

@section('scripts')
	<script>
		$(function() {
			//-------------
			//- FUTURE PARCELS EXPENSES - BAR CHART -
			//-------------

			var ticksStyle = {
				fontColor: '#495057',
				fontStyle: 'bold'
			}

			var mode = 'index'
			var intersect = true

			var $salesChart = $('#parcel_exp_chart')
			// eslint-disable-next-line no-unused-vars
			var salesChart = new Chart($salesChart, {
				type: 'bar',
				data: {
					labels: {!! $parcels_byMonth->pluck('monthname') !!},
					datasets: [{
						backgroundColor: '#007bff',
						borderColor: '#007bff',
						data: {!! $parcels_byMonth->pluck('total') !!}
					}]
				},
				options: {
					maintainAspectRatio: false,
					tooltips: {
						mode: mode,
						intersect: intersect
					},
					hover: {
						mode: mode,
						intersect: intersect
					},
					legend: {
						display: false
					},
					scales: {
						yAxes: [{
							// display: false,
							gridLines: {
								display: true,
								lineWidth: '4px',
								color: 'rgba(0, 0, 0, .2)',
								zeroLineColor: 'transparent'
							},
							ticks: $.extend({
								beginAtZero: true,
								// Include a dollar sign in the ticks
								callback: function(value) {
									if (value >= 1000) {
										value /= 1000
										value += 'k'
									}

									return '$' + value
								}
							}, ticksStyle)
						}],
						xAxes: [{
							display: true,
							gridLines: {
								display: false
							},
							ticks: ticksStyle
						}]
					}
				}
			})

			//-------------
			//- CATEGORY EXPENSES - DONUT CHART -
			//-------------
			var donutChartCanvas = $('#expense_by_category').get(0).getContext('2d')
			var donutData = {
				labels: {!! $exp_by_cat_thisMonth->pluck('category.name') !!},
				datasets: [{
					data: {!! $exp_by_cat_thisMonth->pluck('total') !!},
					backgroundColor: ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'],
				}]
			}
			var donutOptions = {
				maintainAspectRatio: false,
				responsive: true,
				legend: {
					display: false,
				},
			}

			new Chart(donutChartCanvas, {
				type: 'doughnut',
				data: donutData,
				options: donutOptions
			})
		})
	</script>
@endsection
