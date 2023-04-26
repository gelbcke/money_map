@extends('layouts.app', [
    'class' => 'sidebar-mini ',
    'namePage' => __('bank.details'),
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
									@foreach ($cc_info->get() as $value)
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
													{{ __('budget.' . $value->budget->name) }}
												</td>
												<td>
													{{ $value->details }}
												</td>
											</tr>
										@endforeach
									</tbody>
								</table>
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
													{{ $value->expense->details }}
												</td>
												<td>
													{{ __('budget.' . $value->expense->budget->name) }}
												</td>
											</tr>
										@endforeach
									</tbody>
								</table>
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
													{{ $value->details }}
												</td>
												<td>
													{{ __('budget.' . $value->budget->name) }}
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
		</div>
	</section>
@endsection
