@extends('layouts.app', [
    'class' => 'sidebar-mini ',
    'namePage' => __('general.details'),
    'activePage' => 'banks',
    'activeNav' => '',
])

@section('content')
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>{{ $bank->name }}</h1>
					<h6>
						<b>{{ __('bank.balance') }}:</b>
						{{ __('general.M_s') . ' ' . number_format($bank_details->getBalance($bank->id)['balance'], 2) }}
					</h6>
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
								<a href="{{ route('banks.index') }}" class="btn btn-sm btn-info">{{ __('general.menu.go_back') }}</a>
							</div>
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-md-6">
									<h3 class="card-title">{{ __('bank.credit_card_info') }}</h3>
									<br>
									<hr>
									@foreach ($bank->credit_cards as $value)
										<b>{{ __('bank.credit_limit') }}
											:</b> {{ __('general.M_s') . ' ' . number_format($value->credit_limit, 2) }}
										<br>

										<b>{{ __('bank.close_invoice') }}
											:</b> {{ __('general.every_day') . ' ' . $value->close_invoice }}
										<br>

										<b>{{ __('bank.due_date') }}:</b> {{ __('general.every_day') . ' ' . $value->due_date }}
										<br>
									@endforeach
								</div>
								<div class="col-md-6">
									<h3 class="card-title">{{ __('bank.investments_info') }}</h3>
									<br>
									<hr>
									<b>{{ __('investments.initial_value') }}:</b>
									{{ __('general.M_s') . ' ' . number_format($bank->investments->whereNull('org_id')->sum('value'), 2) }}
									<br>
									<b>{{ __('investments.profit') }}:</b>
									{{ __('general.M_s') . ' ' . number_format($bank->investments->whereNotNull('org_id')->sum('value'), 2) }}

								</div>
							</div>
							<hr>
							<div class="text-center">
								<b>{{ __('bank.invoice_this_month') }}</b>
								<br>
								{{ __('general.M_s') . ' ' . number_format($invoice_this_month, 2) }}
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="card">
						<div class="card-header">
							<h3 class="card-title">{{ __('bank.debit_expenses') }}</h3>
						</div>
						<div class="card-body p-0">
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
											{{ __('category.title') }}
										</th>
										<th>
											{{ __('general.menu.budget') }}
										</th>
										<th>
											{{ __('general.details') }}
										</th>
									</thead>
									<tbody>
										@foreach ($debit_expenses as $value)
											<tr>
												<td>
													{{ $value->date->format('d/m/Y') }}
												</td>
												<td>
													{{ __('general.M_s') . ' ' . number_format($value->value, 2) }}
												</td>
												<td>
													@if ($value->category)
														{{ $value->category->name }}
													@endif
												</td>
												<td>
													{{ $value->budget->name }}
												</td>
												<td>
													{{ $value->details }}
												</td>
											</tr>
										@endforeach
										@foreach ($rec_expenses->where('payment_method', 2) as $rec_expense)
											<tr>
												<td>
													{{ $rec_expense->date->format('d/m/Y') }}
												</td>
												<td>
													{{ __('general.M_s') . ' ' . number_format($rec_expense->value, 2) }}
													<small>
														<i class="fa fa-repeat" title="Despesas Recorrentes"></i>
													</small>
												</td>
												<td>
													@if ($rec_expense->category)
														{{ $rec_expense->category->name }}
													@endif
												</td>
												<td>
													{{ $rec_expense->budget->name }}
												</td>
												<td>
													{{ $rec_expense->details }}
												</td>
											</tr>
										@endforeach
									</tbody>
								</table>
								<hr>
								<div class="float-right" style="margin-bottom: 15px; margin-right: 15px">
									<b>{{ __('general.total') . ': ' . __('general.M_s') . ' ' . number_format($debit_expenses->sum('value') + $rec_expenses->where('payment_method', 2)->sum('value'), 2) }}</b>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="card">
						<div class="card-header">
							<h3 class="card-title">{{ __('bank.credit_card_parcels') }}</h3>
						</div>
						<div class="card-body p-0">
							<div class="table-responsive">
								<table class="table">
									<thead class="text-primary">
										<th>
											{{ __('bank.due_date') }}
										</th>
										<th>
											{{ __('bank.credit_parcels.parcel_value') }}
										</th>
										<th>
											{{ __('category.title') }}
										</th>
										<th>
											{{ __('general.details') }}
										</th>
										<th>
											{{ __('general.menu.budget') }}
										</th>
									</thead>
									<tbody>
										@foreach ($cc_parcels as $value)
											<tr>
												<td>
													{{ $value->date->format('d/m/Y') }}
												</td>
												<td>
													{{ __('general.M_s') . ' ' . number_format($value->parcel_vl, 2) }}
												</td>
												<td>
													@if ($value->category)
														{{ $value->category->name }}
													@endif
												</td>
												<td>
													{{ $value->expense->details }}
												</td>
												<td>
													{{ $value->expense->budget->name }}
												</td>
											</tr>
										@endforeach
									</tbody>
								</table>
								<hr>
								<div class="float-right" style="margin-bottom: 15px; margin-right: 15px">
									<b>{{ __('general.total') . ': ' . __('general.M_s') . ' ' . number_format($cc_parcels->sum('parcel_vl'), 2) }}</b>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="card">
						<div class="card-header">
							<h3 class="card-title">{{ __('bank.credit_card_expenses') }}</h3>
						</div>
						<div class="card-body p-0">
							<div class="table-responsive">
								<table class="table">
									<thead class="text-primary">
										<th>
											{{ __('general.date') }}
										</th>
										<th>
											{{ __('bank.credit_parcels.parcel_value') }}
										</th>
										<th>
											{{ __('category.title') }}
										</th>
										<th>
											{{ __('general.details') }}
										</th>
										<th>
											{{ __('general.menu.budget') }}
										</th>
									</thead>
									<tbody>
										@foreach ($cc_expenses as $value)
											<tr>
												<td>
													{{ $value->date->format('d/m/Y') }}
												</td>
												<td>
													{{ __('general.M_s') . ' ' . number_format($value->value, 2) }}
												</td>
												<td>
													@if ($value->category)
														{{ $value->category->name }}
													@endif
												</td>
												<td>
													{{ $value->details }}
												</td>
												<td>
													{{ $value->budget->name }}
												</td>
											</tr>
										@endforeach
										@foreach ($rec_expenses->where('payment_method', 1) as $rec_expense)
											<tr>
												<td>
													{{ $rec_expense->date->format('d/m/Y') }}
												</td>
												<td>
													{{ __('general.M_s') . ' ' . number_format($rec_expense->value, 2) }}
													<small>
														<i class="fa fa-repeat" title="Despesas Recorrentes"></i>
													</small>
												</td>
												<td>
													@if ($rec_expense->category)
														{{ $rec_expense->category->name }}
													@endif
												</td>
												<td>
													{{ $rec_expense->details }}
												</td>
												<td>
													{{ $rec_expense->budget->name }}
												</td>
											</tr>
										@endforeach
									</tbody>
								</table>
								<hr>
								<div class="float-right" style="margin-bottom: 15px; margin-right: 15px">
									<b>{{ __('general.total') . ': ' . __('general.M_s') . ' ' . number_format($cc_expenses->sum('value') + $rec_expenses->where('payment_method', 1)->sum('value'), 2) }}</b>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
@endsection
