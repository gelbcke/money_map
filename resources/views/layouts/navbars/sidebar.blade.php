<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{route('home')}}" class="brand-link">
        <img src="{{ asset('assets') }}/dist/img/moneymap_logo.png" alt="{{config('app.name')}}"
             class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">{{config('app.name')}}</span>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('assets') }}/dist/img/default-150x150.png" class="img-circle elevation-2"
                     alt="User Image">
            </div>
            <div class="info">
                <a href="{{route('profile.edit')}}" class="d-block">{{Auth::user()->name}}</a>
            </div>
        </div>
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="{{ route('home') }}" class="nav-link @if ($activePage == 'home') active @endif">
                        <i class="nav-icon fas fa-chart-bar"></i>
                        <p>{{ __('general.menu.dashboard') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('expenses.index') }}"
                       class="nav-link @if ($activePage == 'expenses') active @endif">
                        <i class="nav-icon fas fa-arrow-down"></i>
                        <p>{{ __('general.menu.expenses') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('incomes.index') }}"
                       class="nav-link @if ($activePage == 'incomes') active @endif">
                        <i class="nav-icon fas fa-arrow-up"></i>
                        <p>{{ __('general.menu.incomes') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('transfers.index') }}"
                       class="nav-link @if ($activePage == 'transfers') active @endif">
                        <i class="nav-icon fas fa-money-bill-transfer"></i>
                        <p>{{ __('general.menu.transfers') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('investments.index') }}"
                       class="nav-link @if ($activePage == 'investments') active @endif">
                        <i class="nav-icon fas fa-piggy-bank"></i>
                        <p>{{ __('general.menu.investments') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('invoices.index') }}"
                       class="nav-link @if ($activePage == 'invoices') active @endif">
                        <i class="nav-icon fa-solid fa-file-invoice-dollar"></i>
                        <p>{{ __('general.menu.invoices') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('wallets.index') }}"
                       class="nav-link @if ($activePage == 'wallets') active @endif">
                        <i class="nav-icon fa-solid fa-wallet"></i>
                        <p>{{ __("general.menu.wallets") }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('banks.index') }}"
                       class="nav-link @if ($activePage == 'banks') active @endif">
                        <i class="nav-icon fa-solid fa-building-columns"></i>
                        <p>{{ __("general.menu.banks") }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('budgets.index')}}"
                       class="nav-link @if ($activePage == 'budgets') active @endif">
                        <i class="nav-icon fa-solid fa-coins"></i>
                        <p>{{ __("general.menu.budget") }}</p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>



