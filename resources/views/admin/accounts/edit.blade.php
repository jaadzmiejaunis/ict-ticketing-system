<x-app-layout>
    @section('title', 'Edit Technician Account')

    <div class="py-6 sm:py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-2xl sm:rounded-lg border border-gray-200 dark:border-gray-700 transition-colors">

                <div class="p-5 sm:p-8 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 transition-colors">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white transition-colors uppercase tracking-tight">Edit Profile: Technician Account</h1>
                            <p class="text-gray-600 dark:text-gray-400 text-xs sm:text-sm mt-1 transition-colors">
                                Updating system access for <span class="font-bold text-indigo-600 dark:text-indigo-400">{{ $user->name }}</span>
                            </p>
                        </div>
                        <a href="{{ route('admin.accounts') }}" class="w-full sm:w-auto inline-flex items-center justify-center bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 px-5 py-3 sm:py-2.5 rounded-md font-bold transition shadow-sm text-xs sm:text-sm uppercase tracking-wider border border-gray-300 dark:border-gray-600 active:scale-95">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            Back to Users
                        </a>
                    </div>
                </div>

                <form action="{{ route('admin.accounts.update', $user) }}" method="POST" enctype="multipart/form-data" class="p-5 sm:p-8 space-y-6 sm:space-y-8">
                    @csrf
                    @method('PUT')

                    <div class="flex flex-col items-center justify-center text-center gap-5 sm:gap-6 pb-6 sm:pb-8 border-b border-gray-100 dark:border-gray-700 transition-colors">
                        <div>
                            <label class="block text-[10px] sm:text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-widest mb-3 transition-colors">Profile Image</label>
                            <div class="relative group inline-block cursor-pointer" onclick="document.getElementById('avatarInput').click()">
                                <img id="preview" src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=4f46e5&color=fff' }}"
                                    class="w-28 h-28 sm:w-32 sm:h-32 rounded-full object-cover border-4 border-white dark:border-gray-700 shadow-md transition-colors" alt="Avatar">

                                <div class="absolute inset-0 rounded-full bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity">
                                    <div class="text-white flex flex-col items-center">
                                        <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2-2H5a2 2 0 01-2-2V9z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <span class="text-[8px] font-bold uppercase tracking-tighter">Change Photo</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="w-full max-w-xs">
                            <label class="block text-[10px] sm:text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-widest mb-2 transition-colors">Upload New Avatar</label>
                            <input type="file" name="avatar" id="avatarInput" onchange="previewImage(event)"
                                class="block w-full text-xs sm:text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-md file:border-0 file:text-[10px] sm:file:text-xs file:font-bold file:uppercase file:bg-indigo-50 dark:file:bg-indigo-900/30 file:text-indigo-700 dark:file:text-indigo-400 hover:file:bg-indigo-100 transition-colors">
                            <p class="mt-2 text-[9px] sm:text-[10px] text-gray-500 dark:text-gray-500 uppercase font-bold transition-colors">Supported: JPG, PNG, WEBP (Max 2MB)</p>
                            <x-input-error :messages="$errors->get('avatar')" class="mt-2" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 sm:gap-6">
                        <div>
                            <label class="block text-[10px] sm:text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-widest mb-1.5 transition-colors">Full Name</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                   class="block w-full bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-md shadow-sm text-sm sm:text-base focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <label class="block text-[10px] sm:text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-widest mb-1.5 transition-colors">Email Address</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                   class="block w-full bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-md shadow-sm text-sm sm:text-base focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 sm:gap-6">
                        <div>
                            <label class="block text-[10px] sm:text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-widest mb-1.5 transition-colors">Account Role</label>
                            <select name="role" class="block w-full bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-md shadow-sm text-sm sm:text-base focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                                <option value="staff" {{ old('role', $user->role) === 'staff' ? 'selected' : '' }}>Technician</option>
                                <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Administrator</option>
                            </select>
                            <x-input-error :messages="$errors->get('role')" class="mt-2" />
                        </div>

                        <div>
                            <label class="block text-[10px] sm:text-xs font-bold text-gray-700 dark:text-gray-400 uppercase tracking-widest mb-1.5 transition-colors">Login Status</label>
                            <div class="mt-1">
                                <span class="px-3 py-1.5 rounded-full text-[10px] sm:text-xs font-black uppercase border tracking-tight inline-block {{ $user->is_active ? 'bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-400 border-green-200 dark:border-green-700/50' : 'bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-400 border-red-200 dark:border-red-700/50' }} transition-colors">
                                    {{ $user->is_active ? 'Account Active' : 'Account Deactivated' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="pt-6 sm:pt-8 border-t border-gray-100 dark:border-gray-700 transition-colors">
                        <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-tight mb-1 transition-colors">Reset User Password</h3>
                        <p class="text-[10px] sm:text-xs text-gray-500 dark:text-gray-400 mb-5 sm:mb-6 transition-colors">Leave blank if you do not want to change the password.</p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 sm:gap-6">
                            <div>
                                <label class="block text-[10px] sm:text-xs font-bold text-gray-700 dark:text-gray-400 uppercase mb-1.5 transition-colors">New Password</label>
                                <div class="relative">
                                    <input type="password" name="password" id="password"
                                        class="block w-full bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-md shadow-sm text-sm sm:text-base focus:ring-indigo-500 focus:border-indigo-500 transition-colors pr-10">
                                    <button type="button" onclick="togglePassword('password', 'eye-icon-password')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-indigo-500 transition-colors">
                                        <svg id="eye-icon-password" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                </div>
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>

                            <div>
                                <label class="block text-[10px] sm:text-xs font-bold text-gray-700 dark:text-gray-400 uppercase mb-1.5 transition-colors">Confirm Password</label>
                                <div class="relative">
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                        class="block w-full bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-md shadow-sm text-sm sm:text-base focus:ring-indigo-500 focus:border-indigo-500 transition-colors pr-10">
                                    <button type="button" onclick="togglePassword('password_confirmation', 'eye-icon-confirm')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-indigo-500 transition-colors">
                                        <svg id="eye-icon-confirm" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end pt-4 border-t border-gray-100 dark:border-gray-700 transition-colors">
                        <button type="submit" class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3.5 sm:py-3 rounded-md font-bold transition shadow-md text-xs sm:text-sm uppercase tracking-wider active:scale-95 border border-transparent">
                            Save Account Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('preview');
                output.src = reader.result;
            }
            if(event.target.files[0]) {
                reader.readAsDataURL(event.target.files[0]);
            }
        }
        function togglePassword(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const eyeIcon = document.getElementById(iconId);

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                // Eye-off icon (slashed)
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                `;
            } else {
                passwordInput.type = 'password';
                // Regular Eye icon
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                `;
            }
        }
    </script>
</x-app-layout>
