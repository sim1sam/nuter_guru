@php
    use App\Helpers\AccountNavHelper;

    $user = auth()->user();
    $initials = strtoupper(substr($user->name, 0, 2));
    $navItems = AccountNavHelper::routes();
    $isDashboard = AccountNavHelper::isDashboard();
@endphp

<aside class="account-nav">
    {{-- Mobile: user bar; tab nav only on sub-pages (not dashboard home) --}}
    <div class="account-mobile-bar d-lg-none">
        <div class="account-mobile-bar__user">
            <div class="account-mobile-bar__avatar">{{ $initials }}</div>
            <div class="account-mobile-bar__meta">
                <p class="account-mobile-bar__name">{{ $user->name }}</p>
                <p class="account-mobile-bar__email">{{ $user->email }}</p>
            </div>
            @unless($isDashboard)
                <a href="{{ route('dashboard') }}" class="account-mobile-bar__home" aria-label="{{ __('Account home') }}">
                    <i class="fas fa-th-large"></i>
                </a>
            @endunless
        </div>

        @unless($isDashboard)
            <nav class="account-mobile-nav" aria-label="{{ __('Account navigation') }}">
                @foreach ($navItems as $item)
                    <a href="{{ route($item['url']) }}"
                       class="account-mobile-nav__link {{ AccountNavHelper::isActive($item['routes']) ? 'is-active' : '' }}">
                        <i class="fas {{ $item['icon'] }}"></i>
                        {{ $item['label'] }}
                    </a>
                @endforeach
                <a href="#"
                   class="account-mobile-nav__link account-mobile-nav__link--logout"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i>
                    {{ __('Logout') }}
                </a>
            </nav>
        @endunless
    </div>

    {{-- Desktop sidebar --}}
    <div class="account-sidebar d-none d-lg-block">
        <div class="account-sidebar__profile">
            <div class="account-sidebar__avatar">{{ $initials }}</div>
            <h2 class="account-sidebar__name">{{ $user->name }}</h2>
            <p class="account-sidebar__email">{{ $user->email }}</p>
        </div>
        <nav class="account-sidebar__nav" aria-label="{{ __('Account navigation') }}">
            @foreach ($navItems as $item)
                <a href="{{ route($item['url']) }}"
                   class="account-sidebar__link {{ AccountNavHelper::isActive($item['routes']) ? 'is-active' : '' }}">
                    <i class="fas {{ $item['icon'] }}"></i>
                    {{ $item['label'] }}
                </a>
            @endforeach
            <a href="#"
               class="account-sidebar__link account-sidebar__link--danger"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt"></i>
                {{ __('Logout') }}
            </a>
        </nav>
    </div>
</aside>
