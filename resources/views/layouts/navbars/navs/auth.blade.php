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
			<a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
				@if (\Illuminate\Support\Facades\Auth::user()->language == 'pt_BR')
					<i class="flag-icon flag-icon-br"></i>
				@elseif(\Illuminate\Support\Facades\Auth::user()->language == 'en')
					<i class="flag-icon flag-icon-us"></i>
				@endif
			</a>
			<div class="dropdown-menu dropdown-menu-right p-0">
				<a href="#" class="dropdown-item @if (\Illuminate\Support\Facades\Auth::user()->language == 'pt_BR') active @endif">
					<i class="flag-icon flag-icon-br mr-2"></i> Português
				</a>
				<a href="#" class="dropdown-item @if (\Illuminate\Support\Facades\Auth::user()->language == 'en') active @endif">
					<i class="flag-icon flag-icon-us mr-2"></i> English
				</a>
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
