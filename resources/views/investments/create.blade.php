@extends('layouts.app', [
    'class' => 'sidebar-mini ',
    'namePage' => __('investments.title'),
    'activePage' => 'investments',
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
					<h1>{{ __('investments.title') }}</h1>
				</div>
			</div>
		</div><!-- /.container-fluid -->
	</section>
	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-body">
						<form method="post" action="{{ route('investments.store') }}" autocomplete="off">
							@csrf
							@method('post')
							@include('alerts.success')
							<div class="row">
								<div class="col-md-7 pr-1">
									<div class="form-group">
										<label for="budget_id">{{ __('general.menu.budget') }}</label>
										<select id="budget_id" name="budget_id" class="form-control">
											<option value=""> --- {{ __('general.menu.select') }} ---</option>
											@foreach ($budgets as $value)
												<option value="{{ $value->id }}">{{ $value->name }}</option>
											@endforeach
										</select>
										@include('alerts.feedback', ['field' => 'budget_id'])
									</div>
								</div>
								<div class="col-md-2 pr-1">
									<div class="form-group">
										<label for="date">{{ __('general.date') }}</label>
										<input type="date" name="date" class="form-control" value="{{ old('date') ?? date('Y-m-d') }}">
										@include('alerts.feedback', ['field' => 'date'])
									</div>
								</div>

								<div class="col-md-3 pr-1">
									<div class="form-group">
										<label for="bank_id">{{ __('general.bank') }} / {{ __('general.account') }}</label>
										<select id="bank_id" name="bank_id" class="form-control">
											<option value=""> --- {{ __('general.menu.select') }} ---</option>
											@foreach ($banks as $value)
												<option value="{{ $value->id }}"><b>{{ $value->name }}</b>
													- {{ $value->payment_method }} ({{ $value->wallet->name }})
												</option>
											@endforeach
										</select>
										@include('alerts.feedback', ['field' => 'bank_id'])
									</div>
								</div>
							</div>

							<div class="col-md-12 pr-1">
								<div class="btn-group btn-group-toggle btn-block" data-toggle="buttons">
									<label class="btn btn-secondary">
										<input type="radio" name="invest_group" id="RV" value="RV" autocomplete="off" checked>
										Renda Variável
									</label>
									<label class="btn btn-secondary">
										<input type="radio" name="invest_group" id="RF" value="RF" autocomplete="off">
										Renda Fixa
									</label>
								</div>
							</div>
							<hr>
							<div class="row">
								<div id="rv_box" class="col-md-4 pr-1">
									<div class="form-group">
										<label for="ticker">{{ __('investments.ticket') . ' - ' . __('investments.variable_returns') }}</label>
										<select class="symbolsearch form-control" name="ticker" id="ticker_rv"></select>
										@include('alerts.feedback', ['field' => 'ticker'])
									</div>
								</div>

								<div id="rf_box" class="col-md-4 pr-1" display:none>
									<div class="form-group">
										<label for="ticker">{{ __('investments.paper') . ' - ' . __('investments.fixed_returns') }}</label>
										<input class="form-control" name="ticker" id="ticker_rf" disabled>
										@include('alerts.feedback', ['field' => 'ticker'])
									</div>
								</div>
								<div class="col-md-3 pr-1">
									<div class="form-group">
										<label for="buy_price">{{ __('investments.buy_price') }}</label>
										<input type="number" type="number" min="1" step="any" name="buy_price" id="buy_price"
											class="form-control" value="{{ old('buy_price') }}" oninput="calc();">
										@include('alerts.feedback', ['field' => 'buy_price'])
									</div>
								</div>
								<div class="col-md-2 pr-1">
									<div class="form-group">
										<label for="quantity">{{ __('investments.quantity') }}</label>
										<input type="number" name="quantity" id="quantity" class="form-control" value="{{ old('quantity') }}"
											oninput="calc();">
										@include('alerts.feedback', ['field' => 'quantity'])
									</div>
								</div>
								<div class="col-md-3 pr-1">
									<div class="form-group">
										<label for="value">{{ __('general.value') }}</label>
										<input type="number" min="1" step="any" name="value" id="value" class="form-control"
											value="{{ old('value') }}">
										@include('alerts.feedback', ['field' => 'value'])
									</div>
								</div>
							</div>
							<hr class="half-rule" />
							<div class="row">
								<div class="col-md-12 pr-1">
									<div class="form-group">
										<label for="details">{{ __('general.details') }}</label>
										<textarea name="details" class="form-control" value="{{ old('details') }}"></textarea>
										@include('alerts.feedback', ['field' => 'details'])
									</div>
								</div>
							</div>
							<div class="modal-footer justify-content-between">
								<button type="submit" class="btn btn-primary">{{ __('general.menu.save') }}</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>
@endsection
@section('scripts')
	<script type="text/javascript">
		$(document).ready(function() {
			$('#rf_box').hide();
			$('input[name="invest_group"]').click(function() {
				if ($(this).attr('value') == 'RV') {
					$('#rv_box').show();
					$('#ticker_rv').prop('disabled', false);
				} else {
					$('#rv_box').hide();
					$('#ticker_rv').prop('disabled', true);
				}
				if ($(this).attr('value') == 'RF') {
					$('#rf_box').show();
					$('#ticker_rf').prop('disabled', false);
				} else {
					$('#rf_box').hide();
					$('#ticker_rf').prop('disabled', true);
				}
			});
		});
	</script>



	<!-- Select2 -->
	<script src="{{ asset('assets') }}/plugins/select2/js/select2.full.min.js"></script>

	<script type="text/javascript">
		$('.symbolsearch').select2({
			theme: 'bootstrap4',
			placeholder: 'Ticket',
			ajax: {
				url: 'symbol-search',
				dataType: 'json',
				type: 'GET',
				delay: 250,
				processResults: function(data) {
					return {
						results: $.map(data, function(item) {
							return {
								text: item.symbol,
								id: item.symbol
							}
						})
					};
				},
				cache: true
			}
		});

		$(document).on('select2:open', () => {
			document.querySelector('.select2-search__field').focus();
		});

		/**
		 * Calculate Quantity of Buy tickets
		 */
		function calc() {
			var buy_price = document.getElementById("buy_price").value;
			var buy_price = parseFloat(buy_price, 10);
			var quantity = document.getElementById("quantity").value;
			var quantity = parseFloat(quantity, 10);
			var value = buy_price.toFixed(2) * quantity;
			document.getElementById("value").value = value.toFixed(2);
		}
	</script>
@endsection
