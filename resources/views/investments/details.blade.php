@extends('layouts.app', [
    'class' => 'sidebar-mini ',
    'namePage' => __('investments.title'),
    'activePage' => 'investments',
    'activeNav' => '',
])

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
					<div class="card-header">
						<h5 class="card-title"><b>{{ $investment->bank->name }}</b>
							<br>
							<small>
								{{ $investment->details }} - ID {{ $investment->id }}
							</small>
						</h5>
						<div class="card-tools">
							<a href="{{ route('investments.index') }}" class="btn btn-sm btn-info">{{ __('general.menu.go_back') }}</a>
						</div>
					</div>
					<div class="card-body">

						{{ __('general.date') }}: {{ $investment->date->format('d/m/Y') }}
						<br>
						{{ __('investments.initial_value') }}
						: {{ __('general.M_s') }} {{ number_format($investment->value, 2) }}
						<br>
						{{ __('investments.profit') }}
						: {{ __('general.M_s') }}
						{{ number_format($investment->where('org_id', $investment->id)->sum('value'), 2) }}
						<hr>
						{{ __('investments.balance') }}
						: {{ __('general.M_s') }}
						{{ number_format($investment->where('org_id', $investment->id)->sum('value') + $investment->value, 2) }}
						<br>
					</div>
				</div>
			</div>
		</div>

		<!-- The timeline -->
		<div class="timeline timeline-inverse">
			<!-- timeline time label -->
			<div class="time-label">
				<span class="bg-danger">
					{{ date('d M Y') }}
				</span>
			</div>
			<!-- /.timeline-label -->

			@foreach ($investment_rec as $value)
				<!-- timeline item -->
				<div>

					@if ($value->operation == 'IN')
						<i class="fas fa-arrow-up bg-success"></i>
					@else
						<i class="fas fa-arrow-down bg-danger"></i>
					@endif
					<div class="timeline-item">
						<span class="time"><i class="far fa-calendar"></i> {{ $value->date->format('d/m/Y') }}</span>

						<h3 class="timeline-header"><a href="{{ route('banks.show', $value->bank->id) }}">{{ $value->bank->name }}</a>
							{{ $value->operation }} </h3>

						<div class="timeline-body">
							{{ $value->details }}
						</div>
					</div>
				</div>
				<!-- END timeline item -->
			@endforeach
		</div>
	</section>
@endsection
