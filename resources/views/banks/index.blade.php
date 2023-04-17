@extends('layouts.app', [
    'class' => 'sidebar-mini ',
    'namePage' => __('general.menu.banks') . ' / ' . __('general.menu.accounts'),
    'activePage' => 'banks',
    'activeNav' => '',
])

@section('content')
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>{{ __('bank.title') }}</h1>
				</div>
			</div>
		</div><!-- /.container-fluid -->
	</section>
	<!-- Main content -->
	<section class="content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-12">
					<div class="card">
						<div class="card-header">
							<h5 class="card-title">{{ __('general.menu.banks') }} / {{ __('general.menu.accounts') }}</h5>
							<div class="card-tools">
								<a href="{{ route('banks.create') }}" class="btn btn-sm btn-info">{{ __('bank.create_new') }}</a>
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
						<div class="card-body p-0">
							<div class="table-responsive">
								<table class="table">
									<thead class="text-primary">
										<th>
											{{ __('general.name') }}
										</th>
										<th>
											{{ __('bank.balance') }}
										</th>
										<th>
											{{ __('bank.functions') }}
										</th>
										<th>
											{{ __('general.wallet') }} / {{ __('general.info.belong_to') }}
										</th>
										<th>
											{{ __('general.group') }}
										</th>
									</thead>
									<tbody>
										@foreach ($banks as $value)
											<tr>
												<td>
													{{ $value->name }}
												</td>
												<td>
													{{ __('general.M_s') .
													    ' ' .
													    number_format(
													        \App\Models\Income::where('bank_id', $value->id)->where('confirmed', 1)->sum('value') -
													            \App\Models\Expense::whereNull('parcels')->where('bank_id', $value->id)->sum('value') -
													            (\App\Models\Transfer::where('org_bank_id', $value->id)->sum('value') -
													                \App\Models\Transfer::where('dest_bank_id', $value->id)->sum('value')),
													        2,
													    ) }}

												</td>
												<td>
													{{ $value->payment_method }}
													@if ($value->f_deb != null)
														<b title="{{ __('general.debit') }}">D</b>
													@endif
													@if ($value->f_cred != null)
														<a href="{{ route('banks.show', $value->id) }}">
															<b title="{{ __('general.credit') }}">C</b>
														</a>
													@endif
													@if ($value->f_invest != null)
														<b title="{{ __('general.investment') }}">I</b>
													@endif
												</td>
												<td>
													@if ($value->wallet_id == null)
														<i class="text-danger">
															<b>{{ __('account.inactive') }}</b>
														</i>
													@else
														<b>{{ $value->wallet->name }}</b> - {{ $value->user->name }}
													@endif

												</td>
												<td>
													@if ($value->group_id == null)
														<small>{{ __('general.no_group') }}</small>
													@else
														{{ $value->group->name }}
													@endif
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
