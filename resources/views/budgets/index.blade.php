@extends('layouts.app', [
    'class' => 'sidebar-mini ',
    'namePage' => __('budget.title'),
    'activePage' => 'budgets',
    'activeNav' => '',
])

@section('content')
	<!-- Content Header (Page header) -->
	<section class="content-header">
	</section>
	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				@if ($budgets->where('status', 1)->sum('budget') == 100)
					<div class="callout callout-success">
						<h5>{{ __('budget.sum_message.success_title') }}</h5>

						<p>{{ __('budget.sum_message.success') }}</p>
					</div>
				@elseif ($budgets->where('status', 1)->sum('budget') < 100)
					<div class="callout callout-warning">
						<h5>{{ __('budget.sum_message.warning_title') }}</h5>

						<p>{{ __('budget.sum_message.warning') }}</p>
					</div>
				@elseif ($budgets->where('status', 1)->sum('budget') > 100)
					<div class="callout callout-danger">
						<h5>{{ __('budget.sum_message.danger_title') }}</h5>

						<p>{{ __('budget.sum_message.danger') }}</p>
					</div>
				@endif
			</div>
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h5 class="card-title">{{ __('budget.title') }}</h5>
						<div class="card-tools">
							<a href="{{ route('budgets.create') }}" class="btn btn-sm btn-info">{{ __('budget.create_new') }}</a>
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
						@if ($budgets->where('status', 1)->count() == 0)
							<div class="col-md-12">
								<form action="{{ route('budgets.default_budget') }}" method="POST">
									@csrf
									@method('POST')
									<button type="submit" class="btn btn-block btn-flat btn-success">
										{{ __('budget.use_default_budget') }}</button>
								</form>
							</div>
						@else
							<div class="table-responsive">
								<table class="table-striped table">
									<thead class="text-primary">
										<th>
											{{ __('general.name') }}
										</th>
										<th>
											{{ __('general.menu.budget') }}
										</th>
										<th>
											{{ __('budget.operation') }}
										</th>
										<th>
											{{ __('general.info.registred_by') }}
										</th>
										<th style="width: 20%">
										</th>
									</thead>
									<tbody>
										@foreach ($budgets as $value)
											<tr>
												<td title="{{ $value->description }}">
													{{ $value->name }}
												</td>
												<td>
													{{ $value->budget }}%
												</td>
												<td>
													{{ __('budget.' . $value->operation) }}
												</td>
												<td>
													{{ $value->user->name }}
												</td>
												<td>
													<form
														@if ($value->status == 1) action="{{ route('budgets.disable', $value->id) }}"
                                            @else
                                            action="{{ route('budgets.enable', $value->id) }}" @endif
														method="POST">
														<a href="{{ route('budgets.edit', $value->id) }}">{{ __('general.menu.edit') }}</a>
														|
														<a href="{{ route('budgets.show', $value->id) }}">{{ __('general.details') }}</a>
														|
														@csrf
														@method('POST')
														@if ($value->status == 1)
															<button type="submit" style="border: none; background: none; color: red">
																{{ __('budget.cancel') }}
															</button>
														@else
															<button type="submit" style="border: none; background: none; color: green">
																{{ __('budget.reactivate') }}
															</button>
														@endif
													</form>
												</td>
											</tr>
										@endforeach
									</tbody>
								</table>
							</div>
							<div class="card-body">
								<div class="progress mb-3">
									<div @if ($budgets->where('status', 1)->sum('budget') == '100') class="progress-bar bg-success" @else class="progress-bar bg-info" @endif
										role="progressbar" aria-valuenow="{{ $budgets->where('status', 1)->sum('budget') }}" aria-valuemin="0"
										aria-valuemax="100" style="width: {{ $budgets->where('status', 1)->sum('budget') }}%"
										title="{{ $budgets->where('status', 1)->sum('budget') }}%">
									</div>
								</div>
							</div>
						@endif
					</div>
				</div>
			</div>
		</div>
	</section>
@endsection
