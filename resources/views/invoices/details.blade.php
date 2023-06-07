@extends('layouts.app', [
    'class' => 'sidebar-mini ',
    'namePage' => __('invoices.title'),
    'activePage' => 'invoices',
    'activeNav' => '',
])

@section('styles')
	<!-- DataTables -->
	<link rel="stylesheet" href="{{ asset('assets') }}/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
	<link rel="stylesheet" href="{{ asset('assets') }}/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
	<link rel="stylesheet" href="{{ asset('assets') }}/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
@endsection

@section('content')
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-10">
					<h1>{{ __('bank.invoice.title') }}</h1>
				</div>
				<div class="col-sm-2">
					<form method="get" id="period" action="{{ route('invoices.show', $cc_info->first()->bank->id) }}"
						autocomplete="off">
						<div class="form-group">
							<div class='input-group date'>
								<input type='text' class="form-control form-control-sm" id='datetimepicker2' name="date"
									value={{ $request->date }} />
								<button type="submit" class="btn btn-success btn-flat btn-sm">
									{{ __('general.menu.search') }}
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div><!-- /.container-fluid -->
	</section>
	<!-- Main content -->
	<div class="invoice mb-3 p-3">
		<!-- title row -->
		<div class="row">
			<div class="col-12">
				<h4>
					<i class="fas fa-money-bill"></i> {{ config('app.name') }}
				</h4>
			</div>
			<!-- /.col -->
		</div>
		<!-- info row -->
		<div class="row invoice-info">
			<div class="col-sm-4 invoice-col">
				{{ __('general.info.registred_by') }}
				<address>
					<strong>{{ config('app.name') }}.</strong>
				</address>
			</div>
			<!-- /.col -->
			<div class="col-sm-4 invoice-col">
				{{ __('general.info.belong_to') }}
				<address>
					<strong>{{ $user->name }}</strong><br>
					Email: {{ $user->email }}
				</address>
			</div>
			<!-- /.col -->
			<div class="col-sm-4 invoice-col">
				<b>{{ __('bank.due_date') }}</b>
				@if (\Carbon\Carbon::now()->format('d') > $cc_info->first()->due_date)
					{{ $period->addMonth()->setDay($cc_info->first()->due_date)->format('d/m/Y') }}<br>
				@else
					{{ $period->setDay($cc_info->first()->due_date)->format('d/m/Y') }}<br>
				@endif
				<b>{{ __('general.account') }}
					:</b> {{ $cc_info->first()->bank->name . ' (' . $cc_info->first()->bank->wallet->name . ')' }}
				<br>
				<b>{{ __('general.total_to_pay') }}:</b> {{ __('general.M_s') . ' ' . number_format($invoices, 2) }}
			</div>
			<!-- /.col -->
		</div>
		<!-- /.row -->

		<!-- Table row -->
		<div class="row">
			<div class="col-12 table-responsive">
				<table id="invoice_table" class="table-bordered table-striped table">
					<thead>
						<tr>
							<th style="width: 10%">
								{{ __('general.date') }}
							</th>
							<th style="width: 20%; text-align: center">
								{{ __('general.info.registred_by') }}
							</th>
							<th tyle="width: 20%; text-align: center">
								{{ __('general.budget') }}
							</th>
							<th tyle="width: 20%; text-align: center">
								{{ __('general.category') }}
							</th>
							<th>
								{{ __('general.details') }}
							</th>

							<th style="text-align:right; width: 20%">
								{{ __('general.value') }}
							</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($cc_expenses as $value)
							<tr>
								<td>
									{{ $value->date->format('d/m/Y') }}
								</td>
								<td style="width: 20%; text-align: center">
									{{ $value->user->name }}
								</td>
								<td tyle="width: 20%; text-align: center">
									{{ $value->budget->name }}
								</td>
								<td tyle="width: 20%; text-align: center">
									@if ($value->category_id)
										{{ $value->category->name }}
									@else
										{{ __('category.empty') }}
									@endif
								</td>
								<td>
									{{ $value->details }}
								</td>
								<td style="text-align:right">
									{{ __('general.M_s') . ' ' . number_format($value->value, 2) }}
								</td>
							</tr>
						@endForeach
						@foreach ($cc_parcels as $value)
							<tr>
								<td>
									{{ $value->date->format('d/m/Y') }}
								</td>
								<td style="width: 20%; text-align: center">
									{{ $value->expense->user->name }}
								</td>
								<td tyle="width: 20%; text-align: center">
									{{ $value->expense->budget->name }}
								</td>
								<td tyle="width: 20%; text-align: center">
									@if ($value->expense->category_id)
										{{ $value->expense->category->name }}
									@else
										{{ __('category.empty') }}
									@endif
								</td>
								<td>
									{{ $value->expense->details }}
									- {{ $value->parcel_nb . '/' . $value->where('expense_id', $value->expense_id)->count('expense_id') }}
								</td>
								<td style="text-align:right">
									{{ __('general.M_s') . ' ' . number_format($value->parcel_vl, 2) }}
								</td>
							</tr>
						@endForeach
						@foreach ($rec_expenses as $value)
							<tr>
								<td>
									{{ $value->date->format('d/m/Y') }}
								</td>
								<td style="width: 20%; text-align: center">
									{{ $value->user->name }}
								</td>
								<td tyle="width: 20%; text-align: center">
									{{ $value->budget->name }}
								</td>
								<td tyle="width: 20%; text-align: center">
									@if ($value->category_id)
										{{ $value->category->name }}
									@else
										{{ __('category.empty') }}
									@endif
								</td>
								<td>
									{{ $value->details }}
								</td>
								<td style="text-align:right">
									<small>
										<i class="fa fa-repeat" title="Despesas Recorrentes"> </i>
									</small>
									{{ __('general.M_s') . ' ' . number_format($value->value, 2) }}
								</td>
							</tr>
						@endForeach

					</tbody>
				</table>
			</div>
			<!-- /.col -->
		</div>
		<!-- /.row -->

		<div class="row">
			<div class="col-6">
			</div>
			<!-- /.col -->
			<div class="col-6">
				<div class="table-responsive">
					<table class="table">
						<tr>
							<th>Total:</th>
							<td style="text-align:right"> {{ __('general.M_s') . ' ' . number_format($invoices, 2) }}</td>
						</tr>
					</table>
				</div>
			</div>
			<!-- /.col -->
		</div>
		<!-- /.row -->
	</div>
	<!-- /.invoice -->
@endsection
@section('scripts')
	<link rel="stylesheet"
		href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css" />

	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/moment.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

	<script
		src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js">
	</script>

	<script type="text/javascript">
		$(function() {
			$('#datetimepicker2').datetimepicker({
				viewMode: 'months',
				format: 'MM-YYYY'
			})
		});
	</script>

	<!-- DataTables  & Plugins -->
	<script src="{{ asset('assets') }}/plugins/datatables/jquery.dataTables.min.js"></script>
	<script src="{{ asset('assets') }}/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
	<script src="{{ asset('assets') }}/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
	<script src="{{ asset('assets') }}/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
	<script src="{{ asset('assets') }}/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
	<script src="{{ asset('assets') }}/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
	<script src="{{ asset('assets') }}/plugins/jszip/jszip.min.js"></script>
	<script src="{{ asset('assets') }}/plugins/pdfmake/pdfmake.min.js"></script>
	<script src="{{ asset('assets') }}/plugins/pdfmake/vfs_fonts.js"></script>
	<script src="{{ asset('assets') }}/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
	<script src="{{ asset('assets') }}/plugins/datatables-buttons/js/buttons.print.min.js"></script>
	<script src="{{ asset('assets') }}/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>

	<script src="https://cdn.datatables.net/plug-ins/1.10.12/sorting/date-eu.js"></script>

	<script>
		$(function() {
			$("#invoice_table").DataTable({
				"responsive": true,
				"lengthChange": false,
				"autoWidth": false,
				"paging": false,
				"columnDefs": [{
					"targets": 0,
					"type": "date-eu"
				}],
				"order": [0, 'desc'],
				"buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
			}).buttons().container().appendTo('#invoice_table_wrapper .col-md-6:eq(0)');
		});
	</script>
@endsection
