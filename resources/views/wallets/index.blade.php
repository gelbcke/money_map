@extends('layouts.app', [
    'class' => 'sidebar-mini ',
    'namePage' => __('wallet.title'),
    'activePage' => 'wallets',
    'activeNav' => '',
])

@section('content')
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>{{ __('wallet.title') }}</h1>
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
							<h5 class="title">{{ __('wallet.title') }}</h5>
						</div>
						<div class="card-body p-0">
							@if ($wallets->count() > 0)
								<div class="table-responsive">
									<table class="table-striped table">
										<thead class="text-primary">
											<th>
												{{ __('wallet.wallet_name') }}
											</th>
											<th>
												{{ __('general.info.belong_to') }}
											</th>
											<th>
												{{ __('general.group') }}
											</th>
											<th>
												{{ __('general.users') }}
											</th>
										</thead>
										<tbody>
											@foreach ($wallets as $value)
												<tr>
													<td>
														<a href="{{ route('wallets.show', $value->id) }}"> {{ $value->name }} </a>
														<a data-toggle="modal" data-target="#modal-default_{{ $value->id }}">
															<i class="fa fa-edit fa-xs"></i>
														</a>
													</td>
													<td>
														{{ $value->user->name }}
													</td>
													<td>
														@if ($value->group_id == 0)
															<i>{{ __('wallet.not_shared') }}</i>
														@else
															{{ $value->group->name }}
														@endif
													</td>
													<td>
														@if ($value->group_id)
															<a href="{{ route('user_groups.show', $value->group_id) }}">{{ __('general.see_users') }}</a>
														@else
															<i>{{ __('wallet.no_users') }}</i>
														@endif
													</td>
												</tr>
												<div class="modal fade" id="modal-default_{{ $value->id }}" style="display: none;" aria-hidden="true">
													<div class="modal-dialog">
														<div class="modal-content">
															<form method="POST" action="{{ route('wallets.update', $value->id) }}" autocomplete="off">
																@csrf
																@method('PUT')
																@include('alerts.success')
																<div class="modal-body">
																	<div class="row">
																		<div class="col-md-12 pr-1">
																			<div class="form-group">
																				<label for="name">{{ __('general.menu.edit') . ' ' . __('wallet.wallet_name') }}</label>
																				<input type="text" name="name" class="form-control" value={{ $value->name }}>
																				@include('alerts.feedback', ['field' => 'name'])
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
													</div>
												</div>
											@endforeach
										</tbody>
									</table>
								</div>
							@else
								{{ __('wallet.no_wallets') }}
							@endif
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="card">
								<div class="card-header">
									<h5 class="title">{{ __('wallet.create_new') }}</h5>
								</div>
								<div class="card-body">
									<form method="post" action="{{ route('wallets.store') }}" autocomplete="off">
										@csrf
										@method('post')
										@include('alerts.success')
										<div class="row">
											<div class="col-md-6 pr-1">
												<div class="form-group">
													<label for="name">{{ __('general.name') }}</label>
													<input type="text" name="name" class="form-control" value="{{ old('name') }}" autocomplete="none">
													@include('alerts.feedback', ['field' => 'name'])
												</div>
											</div>
										</div>
										<hr class="half-rule" />
										<button type="submit" class="btn btn-success">{{ __('general.menu.save') }}</button>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
@endsection
