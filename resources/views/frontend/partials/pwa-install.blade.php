@php
    $pwaIcon = asset('frontend/pwa/icon-192.png');
@endphp

<div id="pwaInstallPopup" class="pwa-install" aria-hidden="true" role="dialog" aria-label="Install Nut'er Guru BD">
    <div class="pwa-install__card">
        <button type="button" id="pwaInstallDismiss" class="pwa-install__close" aria-label="Close">
            <svg width="12" height="12" viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                <path d="M18 6L6 18M6 6l12 12"/>
            </svg>
        </button>

        <div class="pwa-install__body">
            <img src="{{ $pwaIcon }}" alt="Nut'er Guru BD" class="pwa-install__icon" width="36" height="36" loading="eager">
            <div class="pwa-install__text">
                <span class="pwa-install__label">Install Nut'er Guru BD</span>
                <div id="pwaInstallHowTo" class="pwa-install__howto" hidden></div>
            </div>
            <button type="button" id="pwaInstallBtn" class="pwa-install__btn">Install</button>
        </div>
    </div>
</div>
