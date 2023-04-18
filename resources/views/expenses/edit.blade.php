@extends('layouts.app', [
    'class' => 'sidebar-mini ',
    'namePage' => __('expenses.title') . ' / ' . __('general.menu.edit'),
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
						<h5 class="card-title">{{ __('expenses.edit') }}</h5>
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
						<form method="POST" action="{{ route('expenses.update', $expense->id) }}" autocomplete="off">
							@csrf
							@method('PUT')
							@include('alerts.success')
							<div class="row">
							</div>
							<div class="row">
								<div class="col-md-3 pr-1">
									<div class="form-group">
										<label for="date">{{ __('general.date') }}</label>
										<input type="date" name="date" class="form-control" value="{{ $expense->date->format('Y-m-d') }}">
										@include('alerts.feedback', ['field' => 'date'])
									</div>
								</div>
								<div class="col-md-2 pr-1">
									<div class="form-group">
										<label for="value">{{ __('general.value') }}</label>
										<input type="number" min="1" step="any" name="value" id="value" class="form-control"
											value="{{ $expense->value }}" oninput="calc();">
										@include('alerts.feedback', ['field' => 'value'])
									</div>
								</div>
								<div class="col-md-4 pr-1">
									<div class="form-group">
										<label for="bank_id">{{ __('general.bank') }} / {{ __('general.account') }}</label>
										<div class="float-right">
											<input type="radio" id="cred" name="payment_method" title={{ __('general.credit') }} value="1"
												@if ($expense->payment_method == 1) checked @endif>
											<label for="cred">{{ __('general.credit') }}</label> |
											<input type="radio" id="deb" name="payment_method" title={{ __('general.debit') }} value="2"
												@if ($expense->payment_method == 2) checked @endif>
											<label for="deb">{{ __('general.debit') }}</label> |
											<input type="radio" id="cash" name="payment_method" title={{ __('general.cash') }} value="3"
												@if ($expense->payment_method == 3) checked @endif>
											<label for="cash">{{ __('general.cash') }}</label>
										</div>
										<select id="bank_id" name="bank_id" class="form-control">
											<option value=""> --- {{ __('general.menu.select') }} ---</option>
											@foreach ($banks as $value)
												<option value="{{ $value->id }}" @if ($expense->bank_id == $value->id) selected @endif>
													<b>{{ $value->name }}</b>
													- {{ $value->payment_method }} ({{ $value->wallet->name }})
												</option>
											@endforeach
										</select>
										@include('alerts.feedback', ['field' => 'bank_id'])
									</div>
								</div>
								<div class="col-md-3 pr-1">
									<div class="form-group">
										<label for="budget_id">{{ __('general.menu.budget') }}</label>
										<select id="budget_id" name="budget_id" class="form-control">
											<option value=""> --- {{ __('general.menu.select') }} ---</option>
											@foreach ($budgets as $value)
												<option value="{{ $value->id }}" @if ($expense->budget_id == $value->id) selected @endif>
													{{ __('budget.' . $value->name) }}</option>
											@endforeach
										</select>
										@include('alerts.feedback', ['field' => 'budget_id'])
									</div>
								</div>
							</div>

							<div class="row" id="show_cred" style="display:none">
								<hr>
								<div class="form-check">
									<input type="checkbox" class="form-check-input" id="showparcels" name="showparcels"
										@isset($expense->parcels)) checked @endisset>
									<label class="form-check-label">{{ __('expenses.parceled_expenses') }}</label>
								</div>
								<hr>
							</div>


							<div class="row" id="show_parcels" style="display:none">
								<div class="col-md-6 pr-1">
									<div class="form-group">
										<label for="parcels">{{ __('expenses.parcels') }}</label>
										<input type="number" name="parcels" id="parcels" class="form-control" value="{{ $expense->parcels }}"
											oninput="calc();">
										@include('alerts.feedback', ['field' => 'parcels'])
									</div>
								</div>
								<div class="col-md-6 pr-1">
									<div class="form-group">
										<label for="parcel_vl">{{ __('expenses.parcels_vl') }}</label>
										<input type="number" min="1" step="any" name="parcel_vl" id="parcel_vl" class="form-control"
											value="{{ $expense->parcel_vl }}">
										@include('alerts.feedback', ['field' => 'parcel_vl'])
									</div>
								</div>
							</div>
							<hr class="half-rule" />
							<div class="row">
								<div class="col-md-12 pr-1">
									<div class="form-group">
										<label for="details">{{ __('general.details') }}</label>
										<textarea name="details" class="form-control" required>{{ $expense->details }}</textarea>
										@include('alerts.feedback', ['field' => 'details'])
									</div>
								</div>
							</div>
							<input type="checkbox" id="rec_expense" name="rec_expense" value="1"
								@if ($expense->rec_expense == 1) checked @endif>
							<label for="rec_expense">{{ __('expenses.recurring_expenses') }}</label>
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
	<script type="application/javascript">
        function calc() {
            var value = document.getElementById("value").value;
            var value = parseFloat(value, 10);
            var parcels = document.getElementById("parcels").value;
            var parcels = parseFloat(parcels, 10);
            var parcel_vl = value.toFixed(2) / parcels;
            document.getElementById("parcel_vl").value = parcel_vl.toFixed(2);
        }

        function check_cred_inputs() {
            $(document).ready(function() {
				if ($("#cred").is(':checked')) {
                    document.getElementById('show_cred').style.display = '';
                }
                else{
                    document.getElementById('show_cred').style.display = 'none';
                    document.getElementById('show_parcels').style.display = 'none';
                    $("id:showparcels").removeAttr("checked");
                }
            });

            $(document).ready(function() {
				if ($("#showparcels").is(':checked')) {
                    document.getElementById('show_parcels').style.display = '';
                } else {
                    document.getElementById('show_parcels').style.display = 'none';
                }
            });

        }

        $(document).click(function() {
			if ($("#cred").is(':checked')) {
                document.getElementById('show_cred').style.display = '';
            }
            else{
                document.getElementById('show_cred').style.display = 'none';
                document.getElementById('show_parcels').style.display = 'none';
                $("id:showparcels").removeAttr("checked");
            }
        });

         $(document).click(function() {
				if ($("#showparcels").is(':checked')) {
                    document.getElementById('show_parcels').style.display = '';
                } else {
                    document.getElementById('show_parcels').style.display = 'none';
                }
            });

    window.onload = check_cred_inputs;

    </script>
@endsection
