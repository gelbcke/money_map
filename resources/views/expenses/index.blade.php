@extends('layouts.app', [
    'class' => 'sidebar-mini ',
    'namePage' => __('expenses.title'),
    'activePage' => 'expenses',
    'activeNav' => '',
])

@section('content')
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>{{ __('expenses.title') }}</h1>
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
						<h5 class="card-title">{{ __('expenses.expenses') }}</h5>
						<div class="card-tools">
							<a href="{{ route('expenses.index', 'rec_exp') }}" class="btn btn-sm btn-default">
								{{ __('expenses.recurring_expenses') }}
							</a>
							<a href="{{ route('expenses.create') }}" class="btn btn-sm btn-info">{{ __('expenses.create_new') }}</a>
						</div>
					</div>
					<div class="card-body">
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
										{{ __('general.menu.budget') }}
									</th>
									<th>
										{{ __('general.menu.category') }}
									</th>
									<th>
										{{ __('general.details') }}
									</th>
									<th>
										{{ __('general.wallet') }} / {{ __('general.account') }}
									</th>
									<th>
										{{ __('expenses.operation.title') }}
									</th>
									<th style="width: 30px">
									</th>
								</thead>
								<tbody>
									@foreach ($expenses as $value)
										<tr>
											<td>
												{{ $value->date->format('d/m/Y') }}
											</td>
											<td title="{{ __('budget.' . $value->budget->name) }}">
												{{ __('general.M_s') . ' ' . number_format($value->value, 2) }}
												@if ($value->rec_expense)
													<small>
														<i class="fa fa-repeat" title="{{ __('expenses.recurring_expenses') }}"></i>
													</small>
												@endif
												@if ($value->parcels != null)
													<small class="text-info">
														<b>- {{ $value->parcels }}
															x {{ number_format($value->parcel_vl, 2) }}</b>
													</small>
												@endif
											</td>
											<td>
												{{ __('budget.' . $value->budget->name) }}
											</td>
											<td>
												@if ($value->category_id)
													<a href="{{ route('categories.show', $value->category_id) }}">
														{{ $value->category->name }}
													</a>
												@else
													{{ __('category.empty') }}
												@endif
											</td>
											<td>
												<a href="{{ route('expenses.show', $value->id) }}">
													{{ $value->details }}
												</a>
											</td>
											<td>
												<b>{{ $value->bank->name }}</b> - {{ $value->bank->payment_method }}
												({{ $value->bank->wallet->name }})
												@if ($value->rec_expense)
													<a href="{{ route('expenses.cancel_rec', $value->id) }}">
														<i class="fa fa-ban" style="color: red" title="{{ __('incomes.cancel_rec') }}"></i>
													</a>
												@endif
											</td>
											<td>
												@if ($value->payment_method == 1)
													<b>{{ __('expenses.operation.credit') }}</b>
												@elseif($value->payment_method == 2)
													<b>{{ __('expenses.operation.debit') }}</b>
												@elseif($value->payment_method == 3)
													<b>{{ __('expenses.operation.money') }}</b>
												@endif
											</td>
											<td>
												<a href="{{ route('expenses.edit', $value->id) }}">
													<i class="fa fa-edit"></i>
												</a>
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
