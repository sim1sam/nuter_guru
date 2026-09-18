(function () {
    'use strict';

    var STORAGE_KEY = 'pwa_install_dismissed_until_v2';
    var DISMISS_DAYS = 3;
    var popup = document.getElementById('pwaInstallPopup');
    if (!popup) {
        return;
    }

    var installBtn = document.getElementById('pwaInstallBtn');
    var dismissBtn = document.getElementById('pwaInstallDismiss');
    var howTo = document.getElementById('pwaInstallHowTo');
    var labelEl = popup.querySelector('.pwa-install__label');
    var card = popup.querySelector('.pwa-install__card');
    var installing = false;

    function getPrompt() {
        return window.__pwaDeferredPrompt || null;
    }

    function setPrompt(event) {
        window.__pwaDeferredPrompt = event || null;
    }

    function isStandalone() {
        return (
            window.matchMedia('(display-mode: standalone)').matches ||
            window.navigator.standalone === true
        );
    }

    function isDismissed() {
        try {
            var until = parseInt(localStorage.getItem(STORAGE_KEY) || '0', 10);
            return Date.now() < until;
        } catch (e) {
            return false;
        }
    }

    function dismissPopup(persist) {
        popup.classList.remove('is-visible');
        popup.setAttribute('aria-hidden', 'true');
        if (persist === false) {
            return;
        }
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
        // Only show when native Android/Chrome install is actually available
        if (isStandalone() || isDismissed() || !getPrompt()) {
            return;
        }

        if (howTo) {
            howTo.hidden = true;
            howTo.innerHTML = '';
        }
        if (labelEl) {
            labelEl.textContent = "Install Nut'er Guru BD";
        }
        if (installBtn) {
            installBtn.disabled = false;
            installBtn.textContent = 'Install';
        }

        popup.classList.add('is-visible');
        popup.setAttribute('aria-hidden', 'false');
    }

    /**
     * Must call prompt() directly inside the click handler (same user gesture).
     * Do not await / setTimeout before prompt() or Chrome will block install.
     */
    function triggerNativeInstall() {
        var promptEvent = getPrompt();
        if (!promptEvent || typeof promptEvent.prompt !== 'function') {
            dismissPopup(false);
            return;
        }
        if (installing) {
            return;
        }

        installing = true;
        if (installBtn) {
            installBtn.disabled = true;
            installBtn.textContent = 'Installing...';
        }

        try {
            promptEvent.prompt();
        } catch (err) {
            installing = false;
            if (installBtn) {
                installBtn.disabled = false;
                installBtn.textContent = 'Install';
            }
            return;
        }

        var choice = promptEvent.userChoice;
        if (choice && typeof choice.then === 'function') {
            choice.then(function (result) {
                setPrompt(null);
                installing = false;
                if (result && result.outcome === 'accepted') {
                    dismissPopup(true);
                } else {
                    // User cancelled native dialog — hide popup, allow later
                    dismissPopup(false);
                    if (installBtn) {
                        installBtn.disabled = false;
                        installBtn.textContent = 'Install';
                    }
                }
            }).catch(function () {
                setPrompt(null);
                installing = false;
                dismissPopup(false);
            });
        } else {
            setPrompt(null);
            installing = false;
            dismissPopup(true);
        }
    }

    function onInstallClick(e) {
        e.preventDefault();
        e.stopPropagation();
        triggerNativeInstall();
    }

    // When Chrome says the app is installable, show our Install button
    window.addEventListener('pwa-installable', showPopup);

    // If prompt was captured before this script loaded
    if (getPrompt()) {
        showPopup();
    }

    window.addEventListener('beforeinstallprompt', function (e) {
        e.preventDefault();
        setPrompt(e);
        showPopup();
    });

    window.addEventListener('appinstalled', function () {
        setPrompt(null);
        dismissPopup(true);
    });

    if (installBtn) {
        installBtn.addEventListener('click', onInstallClick);
    }

    if (card) {
        card.addEventListener('click', function (e) {
            if (e.target.closest('#pwaInstallDismiss')) {
                return;
            }
            onInstallClick(e);
        });
    }

    if (dismissBtn) {
        dismissBtn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            dismissPopup(true);
        });
    }

    popup.addEventListener('click', function (e) {
        if (e.target === popup) {
            dismissPopup(true);
        }
    });
})();
