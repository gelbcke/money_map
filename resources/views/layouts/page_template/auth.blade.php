<!-- Site wrapper -->
<div class="wrapper">
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
    @include('layouts.navbars.sidebar')
    <div class="main-panel">
        @include('layouts.navbars.navs.auth')
        @if(!empty($first_run))
            <div id="toastsContainerTopRight" class="toasts-top-right fixed">
                <div class="toast bg-warning fade show" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-header">
                        <strong class="mr-auto">{{__('messages.first_run.title')}}</strong>
                    </div>
                    <div class="toast-body">
                        <a href="{{$first_run[1]}}" style="color: #000000;"> {{ $first_run[0] }}</a>
                    </div>
                </div>
            </div>
        @endif
    <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            @include('layouts.alerts.flash-message')
            @yield('content')
        </div>
        @include('layouts.footer')
    </div>
</div>
