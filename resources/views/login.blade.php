<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Manila Central University: Pageant System – Login</title>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;900&family=DM+Sans:wght@300;400;500;600&display=swap">
        <link rel="stylesheet" href="{{ asset('css/login.css') }}">
        @vite(['resources/css/app.css'])
    </head>

    <body>

        <div class="loginPageBackground">

            {{-- MCU watermark logo top right --}}
            <div class="watermark">
                <img src="{{ asset('images/mcuLogo1.png') }}" class="watermark-img">
                <div class="watermark-text">Manila Central<br>University</div>
            </div>

            <div class="loginCard">

                {{-- Header --}}
                <div class="card-header">
                    <img class="mcuLogo1" src="{{ asset('images/mcuLogo1.png') }}">
                </div>

                <div class="divider"></div>

                <div class="pick-title">Pick an account</div>

                {{-- Account options --}}
                <div class="account-list">

                    <a href="{{ route('auth.microsoft') }}" class="account-item">
                        <div class="account-avatar account-avatar--person">
                            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" width="20" height="20">
                                <circle cx="12" cy="8" r="4" fill="currentColor"/>
                                <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round"/>
                            </svg>
                        </div>
                        <div class="account-info">
                            <div class="account-name">Sign in with Microsoft</div>
                            <div class="account-email">Use your MCU Microsoft 365 account</div>
                        </div>
                        <div class="account-arrow">
                            <svg viewBox="0 0 20 20" width="16" height="16" fill="none">
                                <circle cx="10" cy="10" r="9" stroke="currentColor" stroke-width="1.2"/>
                                <path d="M8 7l3 3-3 3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </a>

                    <a href="{{ route('auth.google') }}" class="account-item">
                        <div class="account-avatar account-avatar--person">
                            <svg viewBox="0 0 24 24" width="20" height="20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/>
                                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                            </svg>
                        </div>
                        <div class="account-info">
                            <div class="account-name">Sign in with Google</div>
                            <div class="account-email">Use your Gmail account</div>
                        </div>
                        <div class="account-arrow">
                            <svg viewBox="0 0 20 20" width="16" height="16" fill="none">
                                <circle cx="10" cy="10" r="9" stroke="currentColor" stroke-width="1.2"/>
                                <path d="M8 7l3 3-3 3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </a>

                </div>{{-- ← account-list ends HERE --}}

                {{-- Email/Password login OUTSIDE account-list --}}
                <div class="divider"></div>

                <div class="manual-login">
                    <div class="pick-title" style="font-size: 14px; margin-bottom: 12px;">Sign in with email</div>

                    @if (session('error'))
                        <div class="alert-error">{{ session('error') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="alert-error">{{ $errors->first() }}</div>
                    @endif

                    <form method="POST" action="{{ route('auth.login') }}">
                        @csrf

                        <div class="form-group">
                            <input
                                type="email"
                                name="email"
                                placeholder="Email address"
                                value="{{ old('email') }}"
                                class="form-input @error('email') input-error @enderror"
                                required
                            >
                            @error('email')
                                <span class="input-error-msg">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <input
                                type="password"
                                name="password"
                                placeholder="Password"
                                class="form-input @error('password') input-error @enderror"
                                required
                            >
                            @error('password')
                                <span class="input-error-msg">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit" class="btn-login">Sign In</button>

                    </form>
                </div>

                <div class="login-note">
                    MCU Faculty &amp; Staff only &middot; Unauthorized access is prohibited
                </div>

            </div>

        </div>

    </body>

</html>
