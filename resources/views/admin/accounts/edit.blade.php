<section class="space-y-6">
    <header>
        <h2 class="text-lg font-black text-red-600 dark:text-red-500 uppercase tracking-widest transition-colors">
            {{ __('Delete Account') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400 transition-colors">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please download any data you wish to retain.') }}
        </p>
    </header>

    <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
            class="bg-red-600 hover:bg-red-700 text-white px-10 py-3 rounded-lg text-xs font-black uppercase tracking-[0.2em] transition shadow-lg active:scale-95 border border-transparent">
        {{ __('Delete Account') }}
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 transition-colors">
            @csrf
            @method('delete')

            <h2 class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-widest transition-colors">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400 transition-colors">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />
                <x-text-input id="password" name="password" type="password" class="mt-1 block w-3/4 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600 transition-colors" placeholder="{{ __('Password') }}" />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end gap-3 transition-colors">
                <x-secondary-button x-on:click="$dispatch('close')" class="dark:bg-gray-700 dark:text-white transition-colors">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button class="ms-3 transition-colors">
                    {{ __('Delete Account') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
