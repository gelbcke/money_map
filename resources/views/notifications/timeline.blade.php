@extends('layouts.app', [
    'class' => 'sidebar-mini ',
    'namePage' => __('notifications.title'),
    'activePage' => 'notifications',
    'activeNav' => '',
])

@section('content')

	<!-- Content Header (Page header) -->
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>{{ __('notifications.title') }}</h1>
				</div>
			</div>
		</div><!-- /.container-fluid -->
	</section>
	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					@if (session()->has('message'))
						<div class="alert alert-success">
							{{ session()->get('message') }}
						</div>
					@endif
					<!-- /.card-header -->
					<div class="card-body">
						<ul class="todo-list" data-widget="todo-list">
							@foreach ($notifications as $value)
								<li>
									<!-- checkbox -->
									<div class="icheck-primary d-inline ml-2">
										<input type="checkbox" data-id="{{ $value->id }}" class="toggle-class" value="1" name="readed"
											id="readed" {{ old('readed', isset($value->readed) ? 'checked' : '') }}>
										<label for="readed"></label>
									</div>
									<span class="text">{!! $value->description !!}</span>
									<div class="tools">
										@if ($value->need_approval)
											<i class="fas fa-check"></i>
										@endif
									</div>
								</li>
							@endforeach
						</ul>
					</div>
				</div>
			</div>
		</div>
	</section>


	@if ($errors->any())
		<div class="toasts-top-right fixed">
			<div class="toast bg-danger fade show" role="alert" aria-live="assertive" aria-atomic="true">
				<div class="toast-header">
					<strong class="mr-auto">{{ __('messages.attention') }}</strong>
					<button data-dismiss="toast" type="button" class="close ml-2 mb-1" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
				</div>
				<div class="toast-body">
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</div>
			</div>
		</div>
	@endif
@endsection
@section('scripts')
	<script type="application/javascript">
		$('.toggle-class').on('change',function(){

            let status = $(this).prop('checked') == true ? true : false;
            let readed = $(this).data('readed');
            let id = $(this).data('id');
                $.ajax({
                type:'put',
                dataType:'json',
                url: "{{ route('notifications.mark_readed', '') }}" + "/" + $(this).data('id'),
                data:{'id': id, '_token': "{{ csrf_token() }}"},
            });
        });
	</script>
@endsection
