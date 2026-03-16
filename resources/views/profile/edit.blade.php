<x-app-layout>
    <div class="py-12 bg-[#0f172a] min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <div class="mb-10">
                <h2 class="text-3xl font-bold text-white tracking-tight">
                    {{ __('Account Settings') }}
                </h2>
            </div>

            @if (session('status') === 'profile-updated')
                <div x-data="{ show: true }"
                    x-show="show"
                    x-init="setTimeout(() => show = false, 5000)"
                    class="p-5 bg-emerald-500/10 border border-emerald-500/30 rounded-3xl flex items-center justify-between transition-all shadow-2xl shadow-emerald-900/20">
                    <div class="flex items-center gap-4">
                        <div class="bg-emerald-500 p-1.5 rounded-full text-[#0f172a]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <p class="text-[11px] font-black text-emerald-500 uppercase tracking-[0.2em]">
                            SUCCESS: ALL ACCOUNT SETTINGS AND SECURITY HAVE BEEN UPDATED
                        </p>
                    </div>
                    <button @click="show = false" class="text-emerald-500/50 hover:text-emerald-500 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                    </button>
                </div>
            @endif

            <div class="p-10 bg-[#111827]/50 border border-gray-800 shadow-2xl sm:rounded-[2rem]">
                @include('profile.partials.update-profile-information-form')
            </div>

            <div class="p-10 bg-[#111827]/50 border border-red-900/10 shadow-2xl sm:rounded-[2rem]">
                @include('profile.partials.delete-user-form')
            </div>

        </div>
    </div>
</x-app-layout>
