@php
    // kapasitas maksimum per user (1 GB = 1024 MB)
    $maxSize = 1024 * 1024 * 1024; // 1 GB dalam byte

    // total file yang sudah diupload user yang login
    $usedSize = \App\Models\File::where('user_id', auth()->id())->sum('file_size');

    // hitung persentase progress
    $percentage = $maxSize > 0 ? ($usedSize / $maxSize) * 100 : 0;

    // konversi ke MB
    $usedSizeMB = $usedSize / 1048576;
@endphp
<aside class="vironeer-sidebar">
    <div class="overlay"></div>

    <div class="vironeer-sidebar-header">
        <a href="#" class="vironeer-sidebar-logo">
            <img src="{{ asset('images/light-logo.png') }}" alt="informaticloud" />
        </a>
    </div>
    @if (auth()->user()->is_admin == 0)
        <div class="vironeer-sidebar-progress"
            style="padding: 15px; background: rgba(0,0,0,0.2); border-radius: 10px; margin: 15px;">
            <p style="margin: 0 0 8px; font-weight: bold; color: #fff; font-size: 14px;">
                Total Ukuran
            </p>
            <div class="progress"
                style="height: 12px; background: rgba(255,255,255,0.2); border-radius: 8px; overflow: hidden;">
                <div class="progress-bar"
                    style="width: {{ $percentage }}%;
                   background: rgba(255,255,255,0.9);
                   border-radius: 8px;">
                </div>
            </div>
            <small style="display: block; margin-top: 6px; font-size: 12px; color: #f1f1f1;">
                {{ number_format($usedSizeMB, 2) }} MB / 1024 MB
            </small>
        </div>
    @endif

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
                    class="vironeer-sidebar-link  {{ request()->is('files*') && !request()->is('files/shared') ? 'current' : '' }} ">
                    <p class="vironeer-sidebar-link-title">
                        <span><i class="fa fa-upload"></i>{{ __('My Files') }}</span>
                    </p>
                </a>
                <a href="{{ route('files.shared') }}"
                    class="vironeer-sidebar-link  {{ request()->is('files/shared') ? 'current' : '' }} ">
                    <p class="vironeer-sidebar-link-title">
                        <span><i class="fa fa-share-alt"></i>{{ __('Shared Files') }}</span>
                    </p>
                </a>
            </div>
        </div>
    </div>
</aside>
