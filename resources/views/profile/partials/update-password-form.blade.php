<section>
    <header class="mb-10 border-b border-gray-800 pb-6">
        <h2 class="text-xl font-bold text-white uppercase tracking-tight">
            {{ __('Update Password') }}
        </h2>
        <p class="mt-1 text-xs font-black text-indigo-400/60 uppercase tracking-widest">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    @if (session('status') === 'password-updated')
        <div x-data="{ show: true }"
            x-show="show"
            x-init="setTimeout(() => show = false, 4000)"
            class="mb-8 p-4 bg-emerald-500/10 border border-emerald-500/30 rounded-2xl flex items-center justify-between transition-all">
            <div class="flex items-center gap-3">
                <div class="bg-emerald-500 p-1 rounded-full text-[#0f172a]">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <span class="text-xs font-black text-emerald-500 uppercase tracking-[0.1em]">Password Updated Successfully</span>
            </div>
            <button @click="show = false" class="text-emerald-500/50 hover:text-emerald-500 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    @endif

    <form method="post" action="{{ route('password.update') }}" class="space-y-8">
        @csrf
        @method('put')

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-start">

            <div class="hidden md:flex flex-col items-center justify-center pt-4">
                <div class="w-24 h-24 rounded-2xl bg-indigo-500/5 border-2 border-indigo-500/10 flex items-center justify-center text-indigo-500/30">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <p class="mt-4 text-[8px] font-black text-gray-600 uppercase tracking-[0.2em] text-center">Encrypted Storage</p>
            </div>

            <div class="md:col-span-2 space-y-6">

                <div>
                    <x-input-label for="update_password_current_password" :value="__('Current Password')" class="text-[10px] font-black uppercase text-indigo-400 tracking-widest mb-2" />
                    <x-text-input id="update_password_current_password" name="current_password" type="password" class="w-full bg-[#0f172a] border-gray-800 text-gray-300 rounded-xl py-3 shadow-inner focus:ring-indigo-500" autocomplete="current-password" />
                    <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="update_password_password" :value="__('New Password')" class="text-[10px] font-black uppercase text-indigo-400 tracking-widest mb-2" />
                    <x-text-input id="update_password_password" name="password" type="password" class="w-full bg-[#0f172a] border-gray-800 text-gray-300 rounded-xl py-3 shadow-inner focus:ring-indigo-500" autocomplete="new-password" />
                    <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" class="text-[10px] font-black uppercase text-indigo-400 tracking-widest mb-2" />
                    <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="w-full bg-[#0f172a] border-gray-800 text-gray-300 rounded-xl py-3 shadow-inner focus:ring-indigo-500" autocomplete="new-password" />
                    <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                </div>

                <div class="p-6 bg-[#0f172a]/50 border border-gray-800 rounded-2xl shadow-inner">
                    <x-input-label :value="__('Security Verification')" class="text-[10px] font-black uppercase text-gray-500 tracking-widest mb-4" />
                    <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}" data-theme="dark"></div>
                    <x-input-error class="mt-2" :messages="$errors->updatePassword->get('g-recaptcha-response')" />
                </div>

                <div class="pt-2">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white px-12 py-4 rounded-xl text-[11px] font-black uppercase tracking-[0.2em] transition shadow-xl shadow-indigo-900/40 active:scale-95">
                        {{ __('UPDATE PASSWORD') }}
                    </button>
                </div>

            </div>
        </div>
    </form>
</section>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>
