@extends('layouts.app', [
    'class' => 'sidebar-mini ',
    'namePage' => __('expenses.title') . ' / ' . __('general.menu.create'),
    'activePage' => 'expenses',
    'activeNav' => '',
])

@section('styles')
	<!-- Select2 -->
	<link rel="stylesheet" href="{{ asset('assets') }}/plugins/select2/css/select2.min.css">
	<link rel="stylesheet" href="{{ asset('assets') }}/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
@endsection

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
						<h5 class="card-title">{{ __('expenses.create_new') }}</h5>
						<div class="card-tools">
							<a href="{{ route('expenses.index') }}" class="btn btn-sm btn-info">{{ __('general.menu.go_back') }}</a>
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
						<form method="post" action="{{ route('expenses.store') }}" autocomplete="off">
							@csrf
							@method('post')
							@include('alerts.success')
							<div class="row">
							</div>
							<div class="row">
								<div class="col-md-2 pr-1">
									<div class="form-group">
										<label for="date">{{ __('general.date') }}</label>
										<input type="date" name="date" class="form-control" value="{{ old('date') ?? date('Y-m-d') }}">
										@include('alerts.feedback', ['field' => 'date'])
									</div>
								</div>
								<div class="col-md-3 pr-1">
									<div class="form-group">
										<label for="value">{{ __('general.value') }}</label>
										<div class="input-group">
											<div class="input-group-prepend">
												<span class="input-group-text">{{ __('general.M_s') }}</span>
											</div>
											<input type="number" min="0" step="0.01" lang="en" name="value" id="value"
												class="form-control" value="{{ old('value') }}" oninput="calc();">
											@include('alerts.feedback', ['field' => 'value'])
										</div>
									</div>
								</div>
								<div class="col-md-3 pr-1">
									<div class="form-group">
										<label for="budget_id">{{ __('general.menu.budget') }}</label>
										<select id="budget_id" name="budget_id" class="form-control">
											<option value=""> --- {{ __('general.menu.select') }} ---</option>
											@foreach ($budgets as $value)
												<option value="{{ $value->id }}">{{ __('budget.' . $value->name) }}</option>
											@endforeach
										</select>
										@include('alerts.feedback', ['field' => 'budget_id'])
									</div>
								</div>
								<div class="col-md-4 pr-1">
									<div class="form-group">
										<label for="bank_id">{{ __('general.bank') }} / {{ __('general.account') }}</label>
										<select id="bank_id" name="bank_id" class="form-control">
											<option value=""> --- {{ __('general.menu.select') }} ---</option>
											@foreach ($banks as $value)
												<option value="{{ $value->id }}">
													<strong>{{ $value->name }}</strong>
													- {{ __('general.wallet') . ' ' . $value->wallet->name }}
												</option>
											@endforeach
										</select>
										@include('alerts.feedback', ['field' => 'bank_id'])
									</div>
								</div>
								<div class="col-md-12 pr-1">
									<div class="btn-group btn-group-toggle btn-block" data-toggle="buttons">
										<label class="btn btn-secondary">
											<input type="radio" name="payment_method" id="cred" value="1" autocomplete="off">
											{{ __('general.credit') }}
										</label>
										<label class="btn btn-secondary">
											<input type="radio" name="payment_method" id="deb" value="2" autocomplete="off">
											{{ __('general.debit') }}
										</label>
										<label class="btn btn-secondary">
											<input type="radio" name="payment_method" id="cash" value="3" autocomplete="off">
											{{ __('general.cash') }}
										</label>
									</div>
								</div>
							</div>
							<div class="row" id="show_cred" style="display:none">
								<hr>
								<div class="form-check">
									<input type="checkbox" class="form-check-input" id="showparcels" name="showparcels">
									<label class="form-check-label">{{ __('expenses.parceled_expenses') }}</label>
								</div>
								<hr>
							</div>

							<div class="row" id="show_parcels" style="display:none">
								<div class="col-md-6 pr-1">
									<div class="form-group">
										<label for="parcels">{{ __('expenses.parcels') }}</label>
										<input type="number" name="parcels" id="parcels" class="form-control" value="{{ old('parcels') }}"
											oninput="calc();">
										@include('alerts.feedback', ['field' => 'parcels'])
									</div>
								</div>
								<div class="col-md-6 pr-1">
									<div class="form-group">
										<label for="parcel_vl">{{ __('expenses.parcels_vl') }}</label>
										<input type="number" min="1" step="any" name="parcel_vl" id="parcel_vl" class="form-control"
											value="{{ old('parcel_vl') }}">
										@include('alerts.feedback', ['field' => 'parcel_vl'])
									</div>
								</div>
							</div>

							<hr class="half-rule" />

							<div class="row">
								<div class="col-md-12 pr-1">
									<div class="form-group">
										<label for="category_id">{{ __('general.menu.category') }}</label>
										<div class="input-group">
											<select id="category_id" name="category_id" class="form-control select2categories">
												<option value=""> --- {{ __('general.menu.select') }} ---</option>
												@foreach ($categories as $value)
													<option value="{{ $value->id }}">{{ $value->name }}</option>
												@endforeach
											</select>
											<div class="input-group-append" onclick="window.open('{{ route('categories.create') }}','new_window');">
												<div class="input-group-text">
													<i class="fa fa-plus"></i>
												</div>
											</div>
											@include('alerts.feedback', ['field' => 'category_id'])
										</div>
									</div>
								</div>
								<div class="col-md-12 pr-1">
									<div class="form-group">
										<label for="details">{{ __('general.details') }}</label>
										<textarea name="details" class="form-control" value="{{ old('details') }}" required></textarea>
										@include('alerts.feedback', ['field' => 'details'])
									</div>
								</div>
							</div>
							<input type="checkbox" id="rec_expense" name="rec_expense" value="1">
							<label for="rec_expense">{{ __('expenses.recurring_expenses') . ' (' . __('expenses.by_month') . ')' }}</label>
							<hr>
							<button type="submit" class="btn btn-success">{{ __('general.menu.save') }}</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>
@endsection

@section('scripts')
	<!-- Select2 -->
	<script src="{{ asset('assets') }}/plugins/select2/js/select2.full.min.js"></script>
	<!-- Page specific script -->
	<script type="application/javascript" src="{{ asset('assets') }}/dist/js/pages/expenses.js"></script>
@endsection
