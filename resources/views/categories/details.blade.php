@extends('layouts.app', [
    'class' => 'sidebar-mini ',
    'namePage' => __('category.title'),
    'activePage' => 'categories',
    'activeNav' => '',
])

@section('content')
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-10">
					<h1>{{ __('expenses.title') . ': ' . $category->name }}</h1>
				</div>
				<div class="col-sm-2">
					<form method="get" id="period" action="{{ route('categories.show', $category->id) }}" autocomplete="off">
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
	<div class="card">
		<div class="card-header">
			<div class="card-tools">
				{!! __('category.expenses_in_this_category') .
				    ': <b>' .
				    __('general.M_s') .
				    ' ' .
				    number_format($exp_details->sum('value'), 2) .
				    '</b>' !!}
			</div>
		</div>

		<!-- Table row -->
		<div class="card-body p-0">
			<div class="table-responsive">
				<table class="table-striped table">
					<thead>
						<tr>
							<th style="width: 10%">
								{{ __('general.date') }}
							</th>
							<th style="width: 20%; text-align: center">
								{{ __('general.info.registred_by') }}
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
						@foreach ($exp_details as $value)
							<tr>
								<td>
									{{ $value->date->format('d/m/Y') }}
								</td>
								<td style="width: 20%; text-align: center">
									{{ $value->user->name }}
								</td>
								<td>
									{{ $value->details }}
								</td>
								<td style="text-align:right">
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
@endsection
