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
<body class="bg-gray-900 antialiased text-gray-200">
    <div class="min-h-screen flex flex-col items-center justify-center p-6">

        <div class="mb-10">
            <div class="p-4 bg-gray-800 rounded-2xl border border-gray-700 shadow-xl">
                <svg class="w-12 h-12 text-indigo-500" viewBox="0 0 62 65" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M61.8548 14.6253L31.3537 0.170654L0.852539 14.6253L31.3537 29.0799L61.8548 14.6253Z" fill="currentColor"/>
                    <path d="M61.8548 32.25L31.3537 46.7046L0.852539 32.25V46.7046L31.3537 61.1593L61.8548 46.7046V32.25Z" fill="currentColor"/>
                    <path d="M31.3537 29.0799V46.7046L61.8548 32.25V14.6253L31.3537 29.0799Z" fill="currentColor" fill-opacity="0.5"/>
                </svg>
            </div>
        </div>

        <div class="w-full max-w-[450px] bg-gray-800 border border-gray-700 shadow-2xl rounded-xl p-8 sm:p-10">

            <x-auth-session-status class="mb-6" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-6" id="loginForm" onsubmit="return validateCaptcha()">
                @csrf

                <div>
                    <x-input-label for="email" :value="__('Email Address')" class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1" />
                    <x-text-input id="email"
                                 class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:ring-indigo-500 focus:border-indigo-500"
                                 type="email"
                                 name="email"
                                 :value="old('email')"
                                 required
                                 autofocus
                                 autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="password" :value="__('Password')" class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1" />
                    <x-text-input id="password"
                                 class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:ring-indigo-500 focus:border-indigo-500"
                                 type="password"
                                 name="password"
                                 required
                                 autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="flex items-center">
                    <input id="remember_me" type="checkbox"
                           class="w-5 h-5 rounded border-gray-600 bg-gray-700 text-indigo-600 shadow-sm focus:ring-0 transition cursor-pointer"
                           name="remember">
                    <span class="ms-3 text-sm font-medium text-gray-400 cursor-pointer select-none">{{ __('Remember me') }}</span>
                </div>

                <div class="pt-6 border-t border-gray-700">
                    <div class="flex flex-col items-center gap-6">
                        <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}" data-theme="dark"></div>
                        <x-input-error :messages="$errors->get('g-recaptcha-response')" class="mt-1" />

                        <div class="w-full flex items-center justify-between gap-4">
                            @if (Route::has('password.request'))
                                <a class="text-xs font-bold text-gray-500 hover:text-indigo-400 transition uppercase tracking-tighter" href="{{ route('password.request') }}">
                                    {{ __('Forgot password?') }}
                                </a>
                            @endif

                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs uppercase tracking-widest px-8 py-3 rounded-md transition-all active:scale-95 shadow-lg border border-indigo-500">
                                {{ __('Log In') }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <p class="mt-12 text-[9px] font-bold text-gray-600 uppercase tracking-[0.5em]">
            ICT SUPPORT TICKETING SYSTEM &copy; {{ date('Y') }}
        </p>
    </div>

    <script>
        function validateCaptcha() {
            const response = grecaptcha.getResponse();

            if (response.length === 0) {
                alert("Security Check: Please verify that you are not a robot.");
                return false;
            }

            return true;
        }
    </script>
</body>
</html>
