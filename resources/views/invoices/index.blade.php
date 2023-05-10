@extends('layouts.app', [
    'class' => 'sidebar-mini ',
    'namePage' => __('bank.invoice.title'),
    'activePage' => 'invoices',
    'activeNav' => '',
])

@section('content')
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>{{ __('bank.invoice.title') }}</h1>
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
						<h5 class="card-title">{{ __('bank.credit_card') }}</h5>
						<div class="card-tools">
							<a href="{{ route('invoices.to_pay') }}" class="btn btn-sm btn-default">
								<span title="{{ $invoices->count() }} Invoices to pay" class="badge badge-primary">
									{{ $invoices->count() }}
								</span> {{ __('invoices.invoice_to_pay') }}
							</a>
						</div>
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
					@if ($banks->count() > 0)
						<div class="card-body p-0">
							<div class="table-responsive">
								<table class="table-striped table">
									<thead class="text-primary">
										<th>
											{{ __('bank.credit_card') }} / {{ __('general.menu.accounts') }}
										</th>
										<th>
											{{ __('bank.due_date') }}
										</th>
										<th>
											{{ __('bank.credit_limit') }}
										</th>
										<th>
											{{ __('invoices.value_last_month') }}
										</th>
										<th>
											{{ __('invoices.value_open') }}
										</th>

										<th style="width: 2%">
										</th>
									</thead>
									<tbody>
										@foreach ($banks as $value)
											<tr>
												<td>
													{{ $value->name }} <small>({{ $value->user->name }})</small>
												</td>
												<td>
													@if (\Carbon\Carbon::now()->format('d') > $value->credit_cards[0]->due_date)
														{{ \Carbon\Carbon::now()->addMonth()->setDay($value->credit_cards[0]->due_date)->format('d/m/Y') }}
														<br>
													@else
														{{ \Carbon\Carbon::now()->setDay($value->credit_cards[0]->due_date)->format('d/m/Y') }}
														<br>
													@endif
												</td>
												<td>
													{{ __('general.M_s') . ' ' . number_format($value->credit_cards[0]->credit_limit, 2) }}
												</td>

												<td>
													{{ __('general.M_s') .
													    ' ' .
													    number_format(
													        $value->credit_parcels->whereBetween('date', [
													                Carbon\Carbon::now()->startOfMonth()->subMonth(2)->setDay($value->credit_cards->value('close_invoice'))->format('Y-m-d'),
													                Carbon\Carbon::now()->startOfMonth()->subMonth(1)->setDay($value->credit_cards->value('close_invoice'))->format('Y-m-d'),
													            ])->where('bank_id', $value->id)->sum('parcel_vl') +
													            $value->expenses->whereNull('parcels')->whereBetween('date', [
													                    Carbon\Carbon::now()->startOfMonth()->subMonth(2)->setDay($value->credit_cards->value('close_invoice'))->format('Y-m-d'),
													                    Carbon\Carbon::now()->startOfMonth()->subMonth(1)->setDay($value->credit_cards->value('close_invoice'))->format('Y-m-d'),
													                ])->where('bank_id', $value->id)->where('payment_method', 1)->sum('value'),
													        2,
													    ) }}
												</td>
												<td>
													{{ __('general.M_s') .
													    ' ' .
													    number_format(
													        $value->credit_parcels->whereBetween('date', [
													                Carbon\Carbon::now()->startOfMonth()->subMonth()->setDay($value->credit_cards->value('close_invoice'))->format('Y-m-d'),
													                Carbon\Carbon::now()->startOfMonth()->setDay($value->credit_cards->value('close_invoice'))->format('Y-m-d'),
													            ])->where('bank_id', $value->id)->sum('parcel_vl') +
													            $value->expenses->whereNull('parcels')->whereBetween('date', [
													                    Carbon\Carbon::now()->startOfMonth()->subMonth()->setDay($value->credit_cards->value('close_invoice'))->format('Y-m-d'),
													                    Carbon\Carbon::now()->startOfMonth()->setDay($value->credit_cards->value('close_invoice'))->format('Y-m-d'),
													                ])->where('bank_id', $value->id)->where('payment_method', 1)->sum('value'),
													        2,
													    ) }}
												</td>
												<td>
													<a href="{{ route('invoices.show', [$value->id, 'date' => $now]) }}" target="_blank">
														<i class="fa fa-eye"></i>
													</a>
												</td>
											</tr>
										@endforeach
									</tbody>
								</table>

							</div>
						@else
							{{ __('bank.no_credit_card') }}
					@endif
				</div>
			</div>
		</div>
		</div>
	</section>
@endsection
