<x-app-layout>
    @section('title', 'Add New User')

    <div class="py-6 sm:py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                <div>
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white tracking-tight transition-colors">Add New User</h2>
                    <p class="text-gray-600 dark:text-gray-400 text-xs sm:text-sm transition-colors mt-1">Create a new staff or admin account for the system.</p>
                </div>
                <a href="{{ route('admin.accounts') }}" class="w-full sm:w-auto justify-center bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 px-4 py-2.5 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 font-bold transition text-xs sm:text-sm shadow-sm flex items-center gap-2 active:scale-95 border border-gray-300 dark:border-gray-600">
                    &larr; Back to Accounts
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 p-5 sm:p-8 rounded-2xl sm:rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 transition-colors">

                @if($errors->any())
                    <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-400 rounded-lg text-xs sm:text-sm border border-red-200 dark:border-red-800/50">
                        <div class="flex items-center gap-2 mb-2 font-bold uppercase tracking-wide">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                            Registration Errors
                        </div>
                        <ul class="list-disc pl-5 space-y-1 font-medium">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.store_staff') }}" method="POST" class="space-y-5 sm:space-y-6">
                    @csrf

                    <div>
                        <label class="block text-[10px] sm:text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-widest mb-1.5 transition-colors">Full Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               class="block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white text-sm sm:text-base transition-colors" placeholder="Enter full name">
                    </div>

                    <div>
                        <label class="block text-[10px] sm:text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-widest mb-1.5 transition-colors">Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               class="block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white text-sm sm:text-base transition-colors" placeholder="user@example.com">
                    </div>

                    <div>
                        <label class="block text-[10px] sm:text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-widest mb-1.5 transition-colors">Account Role</label>
                        <select name="role" required
                                class="block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white text-sm sm:text-base transition-colors">
                            <option value="staff" {{ old('role') === 'staff' ? 'selected' : '' }}>Staff / Technician</option>
                            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>System Administrator</option>
                        </select>
                    </div>

                    <div class="pt-4 border-t border-gray-100 dark:border-gray-700 mt-2 space-y-5 transition-colors">
                        <div x-data="{ show: false }">
                            <label class="block text-[10px] sm:text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-widest mb-1.5">Set Password</label>
                            <div class="relative">
                                <input :type="show ? 'text' : 'password'" name="password" required
                                       class="block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white text-sm sm:text-base pr-10 transition-colors">
                                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 dark:text-gray-500 hover:text-indigo-600 dark:hover:text-indigo-400 transition">
                                    <svg x-show="!show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    <svg x-show="show" style="display: none;" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                                </button>
                            </div>
                        </div>

                        <div x-data="{ show: false }">
                            <label class="block text-[10px] sm:text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-widest mb-1.5">Confirm Password</label>
                            <div class="relative">
                                <input :type="show ? 'text' : 'password'" name="password_confirmation" required
                                       class="block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white text-sm sm:text-base pr-10 transition-colors">
                                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 dark:text-gray-500 hover:text-indigo-600 dark:hover:text-indigo-400 transition">
                                    <svg x-show="!show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    <svg x-show="show" style="display: none;" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="pt-6 flex flex-col sm:flex-row items-stretch sm:items-center justify-between border-t border-gray-100 dark:border-gray-700 gap-4 transition-colors">
                        <a href="{{ route('admin.accounts') }}"
                           class="text-center text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-white font-bold text-xs sm:text-sm uppercase tracking-widest transition py-2 sm:py-0">
                            Cancel
                        </a>

                        <button type="submit"
                                class="bg-indigo-600 text-white px-8 py-3 rounded-md hover:bg-indigo-700 font-bold transition shadow-md active:scale-95 text-xs sm:text-sm uppercase tracking-widest border border-transparent">
                            Create Account
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
