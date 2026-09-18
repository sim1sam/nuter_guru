(function () {
    'use strict';

    var STORAGE_KEY = 'pwa_install_dismissed_until';
    var DISMISS_DAYS = 7;
    var deferredPrompt = null;
    var popup = document.getElementById('pwaInstallPopup');
    if (!popup) {
        return;
    }

    var installBtn = document.getElementById('pwaInstallBtn');
    var dismissBtn = document.getElementById('pwaInstallDismiss');
    var howTo = document.getElementById('pwaInstallHowTo');
    var labelEl = popup.querySelector('.pwa-install__label');

    function isMobile() {
        return window.matchMedia('(max-width: 991.98px)').matches;
    }

    function isStandalone() {
        return (
            window.matchMedia('(display-mode: standalone)').matches ||
            window.navigator.standalone === true
        );
    }

    function isIos() {
        var ua = window.navigator.userAgent || '';
        return /iPad|iPhone|iPod/.test(ua) || (navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1);
    }

    function isDismissed() {
        try {
            var until = parseInt(localStorage.getItem(STORAGE_KEY) || '0', 10);
            return Date.now() < until;
        } catch (e) {
            return false;
        }
    }

    function dismissPopup() {
        popup.classList.remove('is-visible');
        popup.setAttribute('aria-hidden', 'true');
        try {
            localStorage.setItem(
                STORAGE_KEY,
                String(Date.now() + DISMISS_DAYS * 24 * 60 * 60 * 1000)
            );
        } catch (e) {
            /* ignore */
        }
    }

    function showPopup() {
        if (isStandalone() || isDismissed()) {
            return;
        }

        popup.classList.add('is-visible');
        popup.setAttribute('aria-hidden', 'false');
        updateInstallUi();
    }

    function updateInstallUi() {
        if (!installBtn) {
            return;
        }

        if (deferredPrompt) {
            installBtn.textContent = 'Install';
            installBtn.disabled = false;
            if (labelEl) {
                labelEl.textContent = "Install Nut'er Guru BD";
            }
            if (howTo) {
                howTo.hidden = true;
            }
            return;
        }

        if (isIos()) {
            installBtn.textContent = 'How to';
            if (labelEl) {
                labelEl.textContent = 'Add Nut\'er Guru BD to Home Screen';
            }
        } else {
            installBtn.textContent = 'How to';
            if (labelEl) {
                labelEl.textContent = "Install Nut'er Guru BD";
            }
        }
    }

    function showHowTo() {
        if (!howTo) {
            return;
        }

        if (isIos()) {
            howTo.innerHTML =
                '<strong>iPhone / iPad:</strong> Tap Share <span aria-hidden="true">□↑</span> then <em>Add to Home Screen</em>.';
        } else {
            howTo.innerHTML =
                '<strong>Android:</strong> Browser menu ⋮ → <em>Install app</em> / <em>Add to Home screen</em>.';
        }
        howTo.hidden = false;
    }

    function registerServiceWorker() {
        if (!('serviceWorker' in navigator)) {
            return Promise.resolve(null);
        }

        return navigator.serviceWorker
            .register('/sw.js', { scope: '/' })
            .then(function (reg) {
                if (reg && reg.update) {
                    reg.update().catch(function () {});
                }
                return reg;
            })
            .catch(function () {
                return null;
            });
    }

    // Listen ASAP so we don't miss beforeinstallprompt
    window.addEventListener('beforeinstallprompt', function (e) {
        e.preventDefault();
        deferredPrompt = e;
        showPopup();
    });

    window.addEventListener('appinstalled', function () {
        deferredPrompt = null;
        dismissPopup();
    });

    registerServiceWorker();

    if (installBtn) {
        installBtn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            if (deferredPrompt) {
                installBtn.disabled = true;
                deferredPrompt.prompt();
                deferredPrompt.userChoice
                    .then(function () {
                        deferredPrompt = null;
                        dismissPopup();
                    })
                    .catch(function () {
                        installBtn.disabled = false;
                        showHowTo();
                    });
                return;
            }

            // No native prompt available (iOS / criteria not met yet)
            showHowTo();
        });
    }

    if (dismissBtn) {
        dismissBtn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            dismissPopup();
        });
    }

    popup.addEventListener('click', function (e) {
        if (e.target === popup) {
            dismissPopup();
        }
    });

    window.addEventListener('load', function () {
        if (isStandalone() || isDismissed()) {
            return;
        }

        // Wait for engagement / SW; show when installable OR show how-to on mobile
        setTimeout(function () {
            if (deferredPrompt) {
                showPopup();
            } else if (isMobile()) {
                showPopup();
            }
        }, 1800);
    });
})();
