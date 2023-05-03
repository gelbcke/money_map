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
				<div class="card">
					<div class="card-header">
						<h5 class="card-title">{{ __('budget.title') }} </h5>
						<div class="card-tools">
							<a href="{{ route('budgets.index') }}" class="btn btn-sm btn-info">{{ __('general.menu.go_back') }}</a>
						</div>
					</div>
					<div class="card-body">
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
						<h6>{{ __('budget.active_groups') }}</h6>
						@foreach ($budgets as $value)
							<small title="{{ __('budget.' . $value->description) }}">{{ __('budget.' . $value->name) }} -
								{{ $value->budget }}%</small>
							@if ($budget->name == $value->name)
								<i class="fas fa-left-long" style="color: red"></i>
							@endif
							<br>
						@endforeach
						<small><b>{{ __('general.total') }} = {{ $budgets->sum('budget') }}%</b></small>
						<hr>
						<form method="POST" action="{{ route('budgets.update', $budget->id) }}" autocomplete="off">
							@csrf
							@method('PUT')
							@include('alerts.success')
							<div class="row">
							</div>
							<div class="row">
								<div class="col-md-4 pr-1">
									<div class="form-group">
										<label for="budget">{{ __('general.menu.budget') }} %</label>
										<input type="text" name="budget" class="form-control" value="{{ $budget->budget }}" required>
										@include('alerts.feedback', ['field' => 'budget'])
									</div>
								</div>
								<div class="col-md-4 pr-1">
									<div class="form-group">
										<label for="name">{{ __('general.name') }}</label>
										<input type="text" name="name" class="form-control" value="{{ $budget->name }}">
										@include('alerts.feedback', ['field' => 'name'])
									</div>
								</div>
								<div class="col-md-4 pr-1">
									<div class="form-group">
										<label for="operation">{{ __('expenses.operation.title') }}</label>
										<select name="operation" class="form-control">
											<option> --- {{ __('general.not_informed') }} --- </option>
											<option value="OUT" @if ($budget->operation == 'OUT') selected @endif> {{ __('general.menu.expenses') }}
											</option>
											<option value="SAVE" @if ($budget->operation == 'SAVE') selected @endif>
												{{ __('general.menu.investments') }}
											</option>
										</select>
										@include('alerts.feedback', ['field' => 'operation'])
									</div>
								</div>
								<div class="col-md-12 pr-1">
									<div class="form-group">
										<label for="description">{{ __('general.details') }}</label>
										<input type="text" name="description" class="form-control" value="{{ $budget->description }}">
										@include('alerts.feedback', ['field' => 'description'])
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
	</section>
@endsection
