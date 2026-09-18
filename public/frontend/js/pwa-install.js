(function () {
    'use strict';

    var STORAGE_KEY = 'pwa_install_dismissed_until';
    var DISMISS_DAYS = 7;
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

        installBtn.disabled = false;
        installBtn.textContent = 'Install';

        if (labelEl) {
            labelEl.textContent = "Install Nut'er Guru BD";
        }

        if (howTo) {
            howTo.hidden = true;
        }
    }

    function showHowTo() {
        if (!howTo) {
            return;
        }

        if (isIos()) {
            howTo.innerHTML =
                '<strong>iPhone:</strong> Tap Share → <em>Add to Home Screen</em>.';
        } else {
            howTo.innerHTML =
                '<strong>Android:</strong> Menu ⋮ → <em>Install app</em>.';
        }
        howTo.hidden = false;
    }

    function waitForPrompt(timeoutMs) {
        return new Promise(function (resolve) {
            var existing = getPrompt();
            if (existing) {
                resolve(existing);
                return;
            }

            var done = false;
            var timer = setTimeout(function () {
                if (done) {
                    return;
                }
                done = true;
                window.removeEventListener('pwa-installable', onReady);
                resolve(getPrompt());
            }, timeoutMs);

            function onReady() {
                if (done) {
                    return;
                }
                done = true;
                clearTimeout(timer);
                window.removeEventListener('pwa-installable', onReady);
                resolve(getPrompt());
            }

            window.addEventListener('pwa-installable', onReady);
        });
    }

    function triggerNativeInstall() {
        if (installing) {
            return Promise.resolve(false);
        }

        installing = true;
        if (installBtn) {
            installBtn.disabled = true;
            installBtn.textContent = 'Installing...';
        }

        return waitForPrompt(2500).then(function (promptEvent) {
            if (!promptEvent || typeof promptEvent.prompt !== 'function') {
                installing = false;
                if (installBtn) {
                    installBtn.disabled = false;
                    installBtn.textContent = 'Install';
                }
                showHowTo();
                return false;
            }

            return promptEvent.prompt().then(function () {
                return promptEvent.userChoice;
            }).then(function () {
                setPrompt(null);
                installing = false;
                dismissPopup();
                return true;
            }).catch(function () {
                installing = false;
                setPrompt(null);
                if (installBtn) {
                    installBtn.disabled = false;
                    installBtn.textContent = 'Install';
                }
                showHowTo();
                return false;
            });
        });
    }

    window.addEventListener('pwa-installable', function () {
        showPopup();
    });

    window.addEventListener('appinstalled', function () {
        setPrompt(null);
        dismissPopup();
    });

    function onInstallClick(e) {
        e.preventDefault();
        e.stopPropagation();
        triggerNativeInstall();
    }

    if (installBtn) {
        installBtn.addEventListener('click', onInstallClick);
    }

    // Whole card tap installs (except close)
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

        // Show as soon as installable; otherwise remind on mobile
        setTimeout(function () {
            if (getPrompt()) {
                showPopup();
            } else if (isMobile()) {
                showPopup();
            }
        }, 1200);
    });
})();
