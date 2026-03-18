<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login | {{ config('app.name', 'ICT Support') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <style>
        body { font-family: 'Instrument Sans', sans-serif; }
    </style>
</head>
<body class="bg-[#0f172a] antialiased text-gray-200">
    <div class="min-h-screen flex flex-col items-center justify-center p-6">

        <div class="mb-10">
            <svg class="w-16 h-16 text-gray-500/30" viewBox="0 0 62 65" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M61.8548 14.6253L31.3537 0.170654L0.852539 14.6253L31.3537 29.0799L61.8548 14.6253Z" fill="currentColor"/>
                <path d="M61.8548 32.25L31.3537 46.7046L0.852539 32.25V46.7046L31.3537 61.1593L61.8548 46.7046V32.25Z" fill="currentColor"/>
                <path d="M31.3537 29.0799V46.7046L61.8548 32.25V14.6253L31.3537 29.0799Z" fill="currentColor" fill-opacity="0.5"/>
            </svg>
        </div>

        <div class="w-full max-w-[480px] bg-[#1e293b]/40 border border-gray-800/60 shadow-2xl rounded-2xl p-10 backdrop-blur-sm">

            <x-auth-session-status class="mb-6" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-8">
                @csrf

                <div>
                    <label for="email" class="block text-[11px] font-bold text-gray-400 uppercase tracking-[0.15em] mb-2.5">
                        {{ __('Email') }}
                    </label>
                    <input id="email"
                           class="block w-full bg-[#0f172a]/80 border-gray-800 text-gray-200 rounded-lg py-3 px-4 focus:ring-1 focus:ring-gray-600 focus:border-gray-600 transition shadow-inner text-sm"
                           type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div>
                    <label for="password" class="block text-[11px] font-bold text-gray-400 uppercase tracking-[0.15em] mb-2.5">
                        {{ __('Password') }}
                    </label>
                    <input id="password"
                           class="block w-full bg-[#0f172a]/80 border-gray-800 text-gray-200 rounded-lg py-3 px-4 focus:ring-1 focus:ring-gray-600 focus:border-gray-600 transition shadow-inner text-sm"
                           type="password"
                           name="password"
                           required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="flex items-center">
                    <input id="remember_me" type="checkbox"
                           class="w-6 h-6 rounded-lg border-gray-700 bg-[#0f172a] text-indigo-600 shadow-sm focus:ring-0 transition cursor-pointer"
                           name="remember">
                    <span class="ms-3 text-[13px] font-medium text-gray-400 cursor-pointer select-none">{{ __('Remember me') }}</span>
                </div>

                <div class="pt-6 border-t border-gray-800/50">
                    <div class="flex flex-col items-center gap-8">
                        <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}" data-theme="dark"></div>
                        <x-input-error :messages="$errors->get('g-recaptcha-response')" class="mt-1" />

                        <div class="w-full flex items-center justify-between gap-6">
                            @if (Route::has('password.request'))
                                <a class="text-[12px] font-medium text-gray-400 hover:text-white underline underline-offset-4 transition" href="{{ route('password.request') }}">
                                    {{ __('Forgot password?') }}
                                </a>
                            @endif

                            <button type="submit" class="bg-white hover:bg-gray-100 text-[#0f172a] font-extrabold text-[12px] uppercase tracking-widest px-10 py-3.5 rounded-xl transition-all active:scale-95 shadow-xl">
                                {{ __('Log In') }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <p class="mt-12 text-[10px] font-bold text-gray-600 uppercase tracking-[0.4em]">
            &copy; {{ date('Y') }} ICT SUPPORT TICKETING SYSTEM
        </p>
    </div>
</body>
</html>
