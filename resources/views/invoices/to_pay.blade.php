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
					@if ($invoices->count() > 0)
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
											{{ __('invoices.value_open') }}
										</th>
										<th>
											{{ __('general.info.registred_at') }}
										</th>
										<th>
										</th>
									</thead>
									<tbody>
										@foreach ($invoices as $value)
											<tr>
												<td>
													{{ $value->bank->name }} <small>({{ $value->user->name }})</small>
												</td>
												<td>
													{{ date('d/m/Y', strtotime($value->due_date)) }}
												</td>
												<td>
													{{ __('general.M_s') . ' ' . number_format($value->value, 2) }}
												</td>
												<td>
													{{ date('d/m/Y H:i:s', strtotime($value->updated_at)) }}
												</td>
												<td>
													<a href="{{ route('invoices.submitpayment', $value->id) }}" type="button"
														class="btn btn-block btn-warning btn-xs">
														{{ __('invoices.submit_payment') }}
													</a>
												</td>
											</tr>
										@endforeach
									</tbody>
								</table>
							</div>
						</div>
					@else
						{{ __('invoices.no_invoices_to_pay') }}
					@endif
				</div>
			</div>
		</div>
	</section>
@endsection
