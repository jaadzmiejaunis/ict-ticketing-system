<x-app-layout>
    <div class="py-6 sm:py-12 bg-gray-50 dark:bg-[#0f172a] min-h-screen transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 sm:space-y-8">

            <div class="mb-6 sm:mb-10">
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white tracking-tight transition-colors">
                    {{ __('Account Settings') }}
                </h2>
            </div>

            @if (session('status') === 'profile-updated')
                <div x-data="{ show: true }"
                    x-show="show"
                    x-init="setTimeout(() => show = false, 5000)"
                    class="p-4 sm:p-5 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/30 rounded-2xl sm:rounded-3xl flex items-start sm:items-center justify-between gap-4 transition-all shadow-xl dark:shadow-2xl shadow-emerald-900/5 dark:shadow-emerald-900/20">
                    <div class="flex items-center sm:items-center gap-3 sm:gap-4">
                        <div class="bg-emerald-500 p-1.5 rounded-full text-white dark:text-[#0f172a] shrink-0 mt-0.5 sm:mt-0">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <p class="text-[9px] sm:text-[11px] font-black text-emerald-700 dark:text-emerald-500 uppercase tracking-widest sm:tracking-[0.2em] transition-colors leading-snug">
                            SUCCESS: ALL ACCOUNT SETTINGS AND SECURITY HAVE BEEN UPDATED
                        </p>
                    </div>
                    <button @click="show = false" class="text-emerald-500/50 hover:text-emerald-500 transition shrink-0">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                    </button>
                </div>
            @endif

            <div class="p-5 sm:p-10 bg-white dark:bg-[#111827]/50 border border-gray-200 dark:border-gray-800 shadow-xl dark:shadow-2xl rounded-2xl sm:rounded-[2rem] transition-colors">
                @include('profile.partials.update-profile-information-form')
            </div>

            <div class="p-5 sm:p-10 bg-white dark:bg-[#111827]/50 border border-red-200 dark:border-red-900/10 shadow-xl dark:shadow-2xl rounded-2xl sm:rounded-[2rem] transition-colors">
                @include('profile.partials.delete-user-form')
            </div>

        </div>
    </div>
</x-app-layout>
