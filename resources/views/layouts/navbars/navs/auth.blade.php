<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-dark">
	<!-- Left navbar links -->
	<ul class="navbar-nav">
		<li class="nav-item">
			<a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
		</li>
		<li class="nav-item d-none d-sm-inline-block">
			<a href="{{ route('home') }}" class="nav-link">{{ __('general.menu.dashboard') }}</a>
		</li>
	</ul>
	<!-- Right navbar links -->
	<ul class="navbar-nav ml-auto">
		<li class="nav-item">
			<a class="nav-link" href="{{ route('transfers.create') }}" role="button" title="{{ __('transfers.create_new') }}">
				<i class="nav-icon nav-icon fas fa-money-bill-transfer"></i>
			</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="{{ route('expenses.create') }}" role="button" title="{{ __('expenses.create_new') }}">
				<i class="nav-icon fas fa-arrow-down"></i>
			</a>
		</li>
		<li class="nav-item dropdown">
			<a class="nav-link" data-toggle="dropdown" href="#">
				<i class="far fa-bell"></i>
				@if ($G_notifications->count() > 0)
					<span class="badge badge-warning navbar-badge">{{ $G_notifications->count() }}</span>
				@endif
			</a>
			<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
				<span class="dropdown-item dropdown-header">
					{{ $G_notifications->count() . ' ' . __('notifications.title') }}
				</span>
				<div class="dropdown-divider"></div>
				@foreach ($G_notifications as $notification)
					<div class="dropdown-divider"></div>
					<a href="{{ route('notifications.index') }}" class="dropdown-item" title="{{ $notification->description }}">
						<i class="fas fa-envelope mr-2"></i>
						{!! substr($notification->description, 0, 25) !!}
						<span class="text-muted float-right text-sm">
							@if ($notification->created_at_difference())
								{{ $notification->created_at_difference() . ' ' . __('general.info.day_ago') }}
							@else
								{{ __('general.info.today') }}
							@endif
						</span>
					</a>
				@endforeach
				<div class="dropdown-divider"></div>
				<a href="{{ route('notifications.index') }}" class="dropdown-item dropdown-footer">See All Notifications</a>
			</div>
		</li>
		<li class="nav-item dropdown">
			<a class="nav-link" data-toggle="dropdown" href="#">
				<i class="fas fa-user"></i>
			</a>
			<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
				<a class="dropdown-item" href="{{ route('profile.edit') }}">{{ __('general.menu.my_profile') }}</a>
				<a class="dropdown-item" href="{{ route('user_groups.index') }}">{{ __('profile.user_groups.title') }}</a>
				<a class="dropdown-item" href="{{ route('logout') }}"
					onclick="event.preventDefault();
                          document.getElementById('logout-form').submit();">
					{{ __('general.menu.logout') }}
				</a>
			</div>
		</li>
	</ul>
</nav>
<!-- End Navbar -->
