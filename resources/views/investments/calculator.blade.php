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
					<h1>Calculadora Juros compostos</h1>
				</div>
			</div>
		</div>
		<!-- /.container-fluid -->
	</section>
	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-body">
						<div class="form-row">
							<div class="form-group col-xl-6">
								<label>Aporte inicial</label>
								<input type="number" value="1000" class="form-control" id="valorInicial" oninput="realizaProjecao()">
							</div>
							<div class="form-group col-xl-6">
								<label>Aporte mensal</label>
								<input type="number" class="form-control" id="valorMensal" oninput="realizaProjecao()">
							</div>
						</div>

						<div class="form-row">
							<div class="form-group col-xl-6">
								<label>Taxa de juros ao mês (SELIC)</label>
								<input type="text" class="form-control" id="selic" name="selic" oninput="realizaProjecao()">
							</div>
							<div class="form-group col-xl-6">
								<label>Período em meses</label>
								<input type="number" value="36" class="form-control" id="periodo" oninput="realizaProjecao()">
							</div>
						</div>
						<hr>
						<h2>Resultado</h2>
						<br />
						<div class="row">
							<table class="table">
								<tbody>
									<tr>
										<td scope="row">Total liquido</td>
										<td id="totalLiquido"></td>
									</tr>

									<tr>
										<td scope="row">Total aplicado</td>
										<td id="totalAplicado"></td>
									</tr>

									<tr>
										<td scope="row">Total rendimento liquido</td>
										<td id="valorGanhoLiquido"></td>
									</tr>

									<tr>
										<td scope="row">Valor Futuro Bruto</td>
										<td id="valorFuturoFinal"></td>
									</tr>

									<tr>
										<td scope="row">Total rendimento bruto</td>
										<td id="valorGanhoBruto"></td>
									</tr>

									<tr>
										<td scope="row">Imposto</td>
										<td id="imposto"></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>

	</section>
@endsection
@section('scripts')
	<script src="https://code.jquery.com/jquery-3.6.2.min.js"
		integrity="sha256-2krYZKh//PcchRtd+H+VyyQoZ/e3EcrkxhM8ycwASPA=" crossorigin="anonymous"></script>
	<script type="text/javascript" src="{{ asset('assets') }}/dist/js/pages/investments_calc_jc.js"></script>
@endsection
