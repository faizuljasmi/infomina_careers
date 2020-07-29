<div class="sidebar" data-color="black" data-active-color="danger">
    <div class="logo">
        <a href="http://www.creative-tim.com" class="simple-text logo-mini">
            <div class="logo-image-small">
                <img src="{{ asset('paper') }}/img/favicon.png">
            </div>
        </a>
        <a href="" class="simple-text logo-normal">
            {{ $user->name }}
        </a>
    </div>
    <div class="sidebar-wrapper">
        <ul class="nav">
            <li class="{{ $elementActive == 'dashboard' ? 'active' : '' }}">
                <a href="{{ route('home') }}">
                    <i class="nc-icon nc-bank"></i>
                    <p>{{ __('Dashboard') }}</p>
                </a>
            </li>
            <li class="{{ $elementActive == 'vacancies' ? 'active' : '' }}">
            <a href="{{route('admin-view-vacancies')}}">
                    <i class="nc-icon nc-briefcase-24"></i>
                    <p>{{ __('Vacancies') }}</p>
                </a>
            </li>
            <li class="{{ $elementActive == 'applications' ? 'active' : '' }}">
            <a href="{{route('admin-view-applications')}}">
                    <i class="nc-icon nc-single-copy-04"></i>
                    <p>{{ __('Applications') }}</p>
                </a>
            </li>
            <li class="{{ $elementActive == 'e-form' ? 'active' : '' }}">
                <a href="{{route('admin-view-applications')}}">
                        <i class="nc-icon nc-laptop"></i>
                        <p>{{ __('e-Form') }}</p>
                    </a>
                </li>
        </ul>
    </div>
</div>
