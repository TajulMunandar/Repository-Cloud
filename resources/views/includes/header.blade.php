<header class="vironeer-page-header">
    <div class="vironeer-sibebar-icon me-auto">
        <i class="fa fa-bars fa-lg"></i>
    </div>

    <div class="vironeer-user-menu">
        <div class="vironeer-user" id="dropdownMenuButton" data-bs-toggle="dropdown">
            <div class="vironeer-user-avatar">
                <img src="{{ asset('images/avatars/default.png') }}" alt="informaticloud" />
            </div>
            <div class="vironeer-user-info d-none d-md-block">
                <p class="vironeer-user-title mb-0">{{ Auth::user()->username }}</p>
                <p class="vironeer-user-text mb-0">{{ Auth::user()->email }}</p>
            </div>
        </div>
        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">

            <li>
                <form action="/logout" method="POST">
                    @csrf
                    <button class="dropdown-item text-danger"><i
                            class="fas fa-sign-out-alt me-2"></i>{{ __('Logout') }}</button>
                </form>
            </li>
        </ul>
    </div>
</header>
