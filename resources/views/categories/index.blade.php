@extends('layouts.app', [
    'class' => 'sidebar-mini ',
    'namePage' => __('general.menu.category') . ' / ' . __('general.menu.expenses'),
    'activePage' => 'categories',
    'activeNav' => '',
])

@section('content')
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>{{ __('category.title') }}</h1>
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
							<h5 class="card-title">{{ __('general.menu.category') . ' / ' . __('general.menu.expenses') }}</h5>
							<div class="card-tools">
								<a href="{{ route('categories.create') }}" class="btn btn-sm btn-info">{{ __('category.create_new') }}</a>
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
											{{ __('category.title') }}
										</th>
										<th>
											{{ __('general.info.registred_by') }}
										</th>
										<th>
											{{ __('general.group') }}
										</th>
										<th>
										</th>
									</thead>
									<tbody>
										@foreach ($categories as $value)
											<tr>
												<td>
													{{ $value->name }}
												</td>
												<td>
													{{ $value->user->name }}
												</td>
												<td>
													@if ($value->group_id)
														{{ $value->group->name }}
													@endif
												</td>
												<td>
													<a href="{{ route('categories.edit', $value->id) }}">
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
		</div>
	</section>
@endsection
