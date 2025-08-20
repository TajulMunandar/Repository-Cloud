<aside class="vironeer-sidebar">
    <div class="overlay"></div>
    <div class="vironeer-sidebar-header">
        <a href="#" class="vironeer-sidebar-logo">
            <img src="{{ asset('images/light-logo.png') }}" alt="informaticloud" />
        </a>
    </div>
    <div class="vironeer-sidebar-menu" data-simplebar>
        <div class="vironeer-sidebar-links">
            <div class="vironeer-sidebar-links-cont">
                <a href="/dashboard" class="vironeer-sidebar-link {{ request()->is('dashboard*') ? 'current' : '' }}">
                    <p class="vironeer-sidebar-link-title">
                        <span><i class="fas fa-th-large"></i>{{ __('Dashboard') }}</span>
                    </p>
                </a>
                @if (auth()->user()->is_admin == 1)
                    <a href="{{ route('users.index') }}"
                        class="vironeer-sidebar-link {{ request()->is('users*') ? 'current' : '' }}">
                        <p class="vironeer-sidebar-link-title">
                            <span><i class="fa fa-users"></i> {{ __('Manage Users') }}</span>
                        </p>
                    </a>
                @endif
                <a href="{{ route('files.index') }}"
                    class="vironeer-sidebar-link  {{ request()->is('files*') ? 'current' : '' }} ">
                    <p class="vironeer-sidebar-link-title">
                        <span><i class="fa fa-upload"></i>{{ __('Manage Uploads') }}</span>
                        {{-- @if ($unviewedUsersCount)
                            <span class="counter">{{ $unviewedUsersCount }}</span>
                        @endif --}}
                    </p>
                </a>
            </div>
        </div>
    </div>
</aside>
