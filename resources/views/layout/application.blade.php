<!DOCTYPE html>
<html>

<head>
    @include('includes.head')
    @include('includes.styles')
</head>

<body>
    @include('includes.sidebar')
    <div class="vironeer-page-content">
        @include('includes.header')
        <div class="container">
            <div class="vironeer-page-body">
                <div class="py-4 g-4">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            @hasSection('back')
                                <a href="@yield('back')" class="btn btn-secondary"><i
                                        class="fas fa-arrow-left me-2"></i>{{ __('Back') }}</a>
                            @endif
                            @hasSection('access')
                                <div class="dropdown">
                                    <button class="btn btn-secondary dropdown-toggle" type="button"
                                        id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                        @yield('access')
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a class="dropdown-item"
                                                href="{{ route('pages.index') }}">{{ __('Pages') }}</a></li>
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                @yield('content')
            </div>
        </div>
        @include('includes.footer')
    </div>
    @include('includes.scripts')
</body>

</html>
