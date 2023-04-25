@extends('layouts.app', [
    'class' => 'sidebar-mini ',
    'namePage' => __('general.menu.category'),
    'activePage' => 'categories',
    'activeNav' => '',
])

@section('content')
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>{{ __('general.menu.category') . ' - ' . __('general.menu.expenses') }}</h1>
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
								<a href="{{ route('categories.index') }}" class="btn btn-sm btn-info">{{ __('general.menu.go_back') }}</a>
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
							<form method="post" action="{{ route('categories.update', $category->id) }}" autocomplete="off">
								@csrf
								@method('put')
								@include('alerts.success')
								<div class="row">
									<div class="col-md-6 pr-1">
										<div class="form-group">
											<label for="name">{{ __('general.name') }}</label>
											<input type="text" name="name" class="form-control" value="{{ $category->name }}" autocomplete="none">
											@include('alerts.feedback', ['field' => 'name'])
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
	<script type="application/javascript">
        $(document).ready(function () {
            $('#f_cred').change(function () {
                $('#show_cred').toggle();
            });
        });
    </script>
@endsection
