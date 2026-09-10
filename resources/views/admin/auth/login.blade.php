@include('admin.header')

@php
    $loginBg = !empty($setting->admin_login_page)
        ? asset($setting->admin_login_page)
        : asset('uploads/custom-images/prod-mixed-dry.jpg');
    $logo = !empty($setting->logo) ? asset($setting->logo) : null;
    $year = date('Y');
@endphp

<body class="ng-login-body">
<style>
@import url('https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,700&family=Manrope:wght@400;500;600;700&display=swap');

:root {
    --ng-ink: #1c2a22;
    --ng-forest: #2f5d46;
    --ng-leaf: #3f7a58;
    --ng-cream: #faf7f1;
    --ng-line: #ddd4c6;
    --ng-muted: #6d7a71;
    --ng-danger: #b42318;
}

html, body {
    height: 100%;
    margin: 0;
}

body.ng-login-body {
    font-family: 'Manrope', sans-serif;
    background: var(--ng-cream);
    color: var(--ng-ink);
}

.navbar-bg,
.main-navbar,
.main-sidebar,
.main-footer,
#sidebar-wrapper {
    display: none !important;
}

.ng-login {
    min-height: 100vh;
    display: grid;
    grid-template-columns: minmax(0, 1.15fr) minmax(320px, 0.85fr);
    background: var(--ng-cream);
}

.ng-login__visual {
    position: relative;
    overflow: hidden;
    min-height: 100vh;
    color: #fff;
    isolation: isolate;
}

.ng-login__visual-bg {
    position: absolute;
    inset: 0;
    background:
        linear-gradient(145deg, rgba(18, 36, 28, 0.55) 0%, rgba(47, 93, 70, 0.28) 45%, rgba(18, 36, 28, 0.62) 100%),
        url('{{ $loginBg }}?v=2') center / cover no-repeat;
    transform: scale(1.04);
    animation: ng-pan 18s ease-in-out infinite alternate;
}

.ng-login__visual::after {
    content: '';
    position: absolute;
    inset: 0;
    background:
        radial-gradient(circle at 18% 20%, rgba(255, 255, 255, 0.12), transparent 32%),
        radial-gradient(circle at 80% 75%, rgba(63, 122, 88, 0.28), transparent 40%);
    pointer-events: none;
}

.ng-login__visual-content {
    position: relative;
    z-index: 1;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    padding: clamp(28px, 5vw, 56px);
}

.ng-login__brand-mark {
    display: inline-flex;
    align-items: center;
    gap: 12px;
    text-decoration: none;
    color: #fff;
}

.ng-login__brand-mark img {
    height: 42px;
    width: auto;
    background: rgba(255,255,255,0.92);
    border-radius: 10px;
    padding: 6px 10px;
}

.ng-login__brand-text {
    font-family: 'Fraunces', serif;
    font-size: 1.35rem;
    font-weight: 700;
    letter-spacing: 0.01em;
}

.ng-login__hero {
    max-width: 520px;
    margin: 40px 0;
}

.ng-login__eyebrow {
    display: inline-block;
    margin-bottom: 16px;
    padding: 6px 12px;
    border: 1px solid rgba(255,255,255,0.28);
    border-radius: 999px;
    font-size: 0.78rem;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    background: rgba(255,255,255,0.08);
    backdrop-filter: blur(6px);
}

.ng-login__hero h1 {
    font-family: 'Fraunces', serif;
    font-size: clamp(2.4rem, 4.4vw, 3.6rem);
    line-height: 1.08;
    font-weight: 700;
    margin: 0 0 16px;
    color: #fff;
}

.ng-login__hero p {
    margin: 0;
    font-size: 1.05rem;
    line-height: 1.65;
    color: rgba(255,255,255,0.86);
    max-width: 38ch;
}

.ng-login__chips {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 28px;
}

.ng-login__chip {
    padding: 8px 14px;
    border-radius: 999px;
    background: rgba(255,255,255,0.12);
    border: 1px solid rgba(255,255,255,0.18);
    font-size: 0.85rem;
    backdrop-filter: blur(4px);
}

.ng-login__panel {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: clamp(24px, 4vw, 48px);
    background: linear-gradient(180deg, #fffefb 0%, var(--ng-cream) 100%);
    position: relative;
}

.ng-login__panel::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image: radial-gradient(rgba(47, 93, 70, 0.06) 1px, transparent 1px);
    background-size: 18px 18px;
    opacity: 0.55;
    pointer-events: none;
}

.ng-login__card {
    position: relative;
    width: 100%;
    max-width: 420px;
    animation: ng-rise 0.55s ease both;
}

.ng-login__mobile-brand {
    display: none;
    margin-bottom: 24px;
}

.ng-login__mobile-brand img {
    height: 40px;
    width: auto;
}

.ng-login__card-head h2 {
    font-family: 'Fraunces', serif;
    font-size: 2rem;
    margin: 0 0 8px;
    color: var(--ng-ink);
}

.ng-login__card-head p {
    margin: 0 0 28px;
    color: var(--ng-muted);
    font-size: 0.98rem;
    line-height: 1.55;
}

.ng-login__field {
    margin-bottom: 18px;
}

.ng-login__field label {
    display: block;
    margin-bottom: 8px;
    font-size: 0.86rem;
    font-weight: 600;
    color: var(--ng-ink);
}

.ng-login__field label span {
    color: var(--ng-danger);
}

.ng-login__input-wrap {
    position: relative;
}

.ng-login__input-wrap i.ng-input-icon {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--ng-muted);
    font-size: 0.95rem;
    pointer-events: none;
}

.ng-login__field input[type="email"],
.ng-login__field input[type="password"],
.ng-login__field input[type="text"] {
    width: 100%;
    height: 50px;
    border: 1px solid var(--ng-line);
    border-radius: 12px;
    background: #fff;
    padding: 0 44px 0 42px;
    font-size: 0.96rem;
    color: var(--ng-ink);
    transition: border-color .2s ease, box-shadow .2s ease;
}

.ng-login__field input:focus {
    outline: none;
    border-color: var(--ng-leaf);
    box-shadow: 0 0 0 4px rgba(63, 122, 88, 0.15);
}

.ng-login__toggle-pass {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    border: 0;
    background: transparent;
    color: var(--ng-muted);
    width: 36px;
    height: 36px;
    border-radius: 8px;
    cursor: pointer;
}

.ng-login__toggle-pass:hover {
    color: var(--ng-forest);
    background: rgba(47, 93, 70, 0.08);
}

.ng-login__row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin: 8px 0 24px;
}

.ng-login__remember {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin: 0;
    font-size: 0.9rem;
    color: var(--ng-muted);
    cursor: pointer;
    user-select: none;
}

.ng-login__remember input {
    width: 16px;
    height: 16px;
    accent-color: var(--ng-forest);
}

.ng-login__submit {
    width: 100%;
    height: 52px;
    border: 0;
    border-radius: 12px;
    background: linear-gradient(135deg, var(--ng-forest) 0%, var(--ng-leaf) 100%);
    color: #fff;
    font-weight: 700;
    font-size: 1rem;
    letter-spacing: 0.01em;
    box-shadow: 0 12px 28px rgba(47, 93, 70, 0.28);
    transition: transform .2s ease, box-shadow .2s ease, filter .2s ease;
}

.ng-login__submit:hover {
    filter: brightness(1.05);
    transform: translateY(-1px);
    box-shadow: 0 16px 32px rgba(47, 93, 70, 0.34);
    color: #fff;
}

.ng-login__submit:active {
    transform: translateY(0);
}

.ng-login__meta {
    margin-top: 22px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
    font-size: 0.86rem;
    color: var(--ng-muted);
}

.ng-login__meta a {
    color: var(--ng-forest);
    font-weight: 600;
    text-decoration: none;
}

.ng-login__meta a:hover {
    text-decoration: underline;
}

.ng-login__errors {
    margin-bottom: 18px;
    padding: 12px 14px;
    border-radius: 12px;
    background: #fef3f2;
    border: 1px solid #fecdca;
    color: var(--ng-danger);
    font-size: 0.88rem;
}

.ng-login__errors ul {
    margin: 0;
    padding-left: 18px;
}

@keyframes ng-rise {
    from { opacity: 0; transform: translateY(14px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes ng-pan {
    from { transform: scale(1.04) translate3d(0, 0, 0); }
    to { transform: scale(1.08) translate3d(-1.5%, -1%, 0); }
}

@media (max-width: 991.98px) {
    .ng-login {
        grid-template-columns: 1fr;
    }

    .ng-login__visual {
        min-height: 240px;
        max-height: 34vh;
    }

    .ng-login__hero {
        margin: 12px 0 0;
    }

    .ng-login__hero h1 {
        font-size: 1.8rem;
    }

    .ng-login__hero p,
    .ng-login__chips,
    .ng-login__visual .ng-login__brand-mark {
        display: none;
    }

    .ng-login__mobile-brand {
        display: block;
    }

    .ng-login__panel {
        align-items: flex-start;
        padding-top: 28px;
    }
}
</style>

<div class="ng-login">
    <aside class="ng-login__visual">
        <div class="ng-login__visual-bg"></div>
        <div class="ng-login__visual-content">
            <a href="{{ url('/') }}" class="ng-login__brand-mark">
                @if($logo)
                    <img src="{{ $logo }}" alt="Nuter Guru">
                @endif
                <span class="ng-login__brand-text">Nuter Guru</span>
            </a>

            <div class="ng-login__hero">
                <span class="ng-login__eyebrow">{{ __('admin.Admin Panel') }}</span>
                <h1>Nuter Guru</h1>
                <p>{{ __('admin.Organic dry fruits & nuts commerce') }}</p>
                <div class="ng-login__chips">
                    <span class="ng-login__chip">Inventory</span>
                    <span class="ng-login__chip">Orders</span>
                    <span class="ng-login__chip">POS</span>
                </div>
            </div>

            <div style="opacity:.75;font-size:.85rem;">
                © {{ $year }} Nuter Guru
            </div>
        </div>
    </aside>

    <main class="ng-login__panel">
        <div class="ng-login__card">
            <div class="ng-login__mobile-brand">
                @if($logo)
                    <img src="{{ $logo }}" alt="Nuter Guru">
                @endif
            </div>

            <div class="ng-login__card-head">
                <h2>{{ __('admin.Welcome back') }}</h2>
                <p>{{ __('admin.Login Your  fashion shopping .') }}</p>
            </div>

            @if ($errors->any())
                <div class="ng-login__errors">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.login') }}" method="POST" autocomplete="on">
                @csrf

                <div class="ng-login__field">
                    <label for="email">{{ __('admin.Email') }} <span>*</span></label>
                    <div class="ng-login__input-wrap">
                        <i class="fas fa-envelope ng-input-icon"></i>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="admin@example.com">
                    </div>
                </div>

                <div class="ng-login__field">
                    <label for="password">{{ __('admin.Password') }} <span>*</span></label>
                    <div class="ng-login__input-wrap">
                        <i class="fas fa-lock ng-input-icon"></i>
                        <input id="password" type="password" name="password" required placeholder="••••••••">
                        <button type="button" class="ng-login__toggle-pass" id="ngTogglePass" aria-label="{{ __('admin.Show password') }}">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="ng-login__row">
                    <label class="ng-login__remember" for="remember">
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        {{ __('admin.Remember Me') }}
                    </label>
                </div>

                <button type="submit" class="ng-login__submit">
                    {{ __('admin.Login') }}
                </button>
            </form>

            <div class="ng-login__meta">
                <span>© {{ $year }} Nuter Guru</span>
                <a href="{{ url('/') }}">{{ __('admin.Back to store') }}</a>
            </div>
        </div>
    </main>
</div>

<script>
(function () {
    var btn = document.getElementById('ngTogglePass');
    var input = document.getElementById('password');
    if (!btn || !input) return;
    btn.addEventListener('click', function () {
        var show = input.type === 'password';
        input.type = show ? 'text' : 'password';
        btn.innerHTML = show ? '<i class="fas fa-eye-slash"></i>' : '<i class="fas fa-eye"></i>';
        btn.setAttribute('aria-label', show ? @json(__('admin.Hide password')) : @json(__('admin.Show password')));
    });
})();
</script>

@include('admin.footer')
