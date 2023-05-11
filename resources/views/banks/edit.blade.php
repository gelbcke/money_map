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
					<h1>{{ __('general.menu.banks') . ' / ' . __('general.menu.accounts') }}</h1>
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
							<form method="post" action="{{ route('banks.update', $bank->id) }}" autocomplete="off">
								@csrf
								@method('put')
								@include('alerts.success')
								<div class="row">
									<div class="col-md-6 pr-1">
										<div class="form-group">
											<label for="name">{{ __('general.name') }}</label>
											<input type="text" name="name" class="form-control" value="{{ $bank->name }}" autocomplete="none">
											@include('alerts.feedback', ['field' => 'name'])
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6 pr-1">
										<div class="form-group">
											<label for="payment_method">{{ __('expenses.payment_mode') }}
												/ {{ __('bank.functions') }}</label>
											<div>
												<input type="checkbox" id="f_deb" name="f_deb" value="1"
													@if ($bank->f_deb) checked @endif>
												<label for="f_deb">{{ __('general.debit') }}</label>
												<br>
												<input type="checkbox" id="f_cred" name="f_cred" value="1"
													@if ($bank->f_cred) checked @endif>
												<label for="f_cred">{{ __('general.credit') }}</label>
												<br>
												<input type="checkbox" id="f_invest" name="f_invest" value="1"
													@if ($bank->f_invest) checked @endif>
												<label for="f_invest">{{ __('general.investment') }}</label>
											</div>
											@include('alerts.feedback', ['field' => 'payment_method'])
										</div>
									</div>
								</div>
								<div class="row" id="show_cred" style="display:none">
									<div class="col-md-12">
										<div class="card">
											<div class="card-header">
												<h5 class="card-title">{{ __('bank.credit_card_info') }}</h5>
											</div>
											<div class="card-body">
												<div class="col-md-4 pr-1">
													<div class="form-group">
														<label for="due_date">{{ __('bank.due_date') }}</label>
														<input type="number" name="due_date" class="form-control"
															value="{{ $bank->credit_cards->value('due_date') }}">
														@include('alerts.feedback', ['field' => 'due_date'])
													</div>
												</div>
												<div class="col-md-4 pr-1">
													<div class="form-group">
														<label for="close_invoice">{{ __('bank.close_invoice') }}</label>
														<input type="number" name="close_invoice" class="form-control"
															value="{{ $bank->credit_cards->value('close_invoice') }}">
														@include('alerts.feedback', ['field' => 'close_invoice'])
													</div>
												</div>

												<div class="col-md-4 pr-1">
													<div class="form-group">
														<label for="credit_limit">{{ __('bank.credit_limit') }}</label>
														<input type="number" min="1" step="any" name="credit_limit" class="form-control"
															value="{{ $bank->credit_cards->value('credit_limit') }}">
														@include('alerts.feedback', ['field' => 'credit_limit'])
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<hr class="half-rule" />

								<button type="submit" class="btn btn-success">
									{{ __('general.menu.save') }}
								</button>


							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
@endsection
@section('scripts')
	<script type="application/javascript" src="{{asset('assets')}}\dist\js\pages\banks.js"></script>
@endsection
