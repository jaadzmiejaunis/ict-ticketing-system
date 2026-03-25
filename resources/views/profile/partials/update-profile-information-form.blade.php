<section>
    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('patch')

        <div class="bg-gray-800 p-6 sm:p-8 rounded-lg shadow-sm border border-gray-700">
            <header class="mb-6 border-b border-gray-700 pb-4">
                <h2 class="text-xl font-bold text-white tracking-tight">Identity Information</h2>
                <p class="mt-1 text-sm text-gray-400">Update your account's profile information and email address.</p>
            </header>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-start">

                <div class="flex flex-col items-center justify-center pt-2">
                    <div class="relative group">
                        <div class="w-32 h-32 rounded-full border-4 border-gray-700 bg-gray-900 overflow-hidden ring-4 ring-gray-800 shadow-lg">
                            @php
                                $avatarPath = Auth::user()->avatar;
                                $originalUrl = $avatarPath ? asset('storage/' . $avatarPath) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=4f46e5&color=ffffff';
                            @endphp
                            <img src="{{ $originalUrl }}" class="w-full h-full object-cover" id="avatar-preview" data-original="{{ $originalUrl }}">
                        </div>

                        <label for="avatar-upload" class="absolute bottom-0 right-0 bg-indigo-600 p-2.5 rounded-full cursor-pointer border-4 border-gray-800 hover:bg-indigo-500 transition shadow-md">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path></svg>
                            <input id="avatar-upload" name="avatar" type="file" class="hidden" accept="image/*" onchange="livePreview(this)" />
                        </label>

                        <button type="button" id="cancel-upload" style="display: none;" onclick="revertImage()" class="absolute -top-1 -right-1 bg-red-600 hover:bg-red-500 text-white p-1.5 rounded-full shadow-md border-2 border-gray-800 transition active:scale-90">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                        </button>
                    </div>

                    <div class="mt-4 text-center">
                        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Max Size: 2MB</p>
                        <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
                    </div>
                </div>

                <div class="md:col-span-2 space-y-6">
                    <div>
                        <x-input-label for="name" :value="__('Full Name')" class="block text-xs font-bold text-gray-400 uppercase mb-1" />
                        <x-text-input id="name" name="name" type="text" class="w-full bg-gray-700 border-gray-600 text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" :value="old('name', $user->name)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </div>

                    <div>
                        <x-input-label for="email" :value="__('Email Address')" class="block text-xs font-bold text-gray-400 uppercase mb-1" />
                        <x-text-input id="email" name="email" type="email" class="w-full bg-gray-700 border-gray-600 text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" :value="old('email', $user->email)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('email')" />
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-gray-800 p-6 sm:p-8 rounded-lg shadow-sm border border-gray-700">
            <header class="mb-6 border-b border-gray-700 pb-4">
                <h2 class="text-xl font-bold text-white tracking-tight">Security & Password</h2>
                <p class="mt-1 text-sm text-gray-400">Ensure your account is using a long, random password to stay secure.</p>
            </header>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-start">

                <div class="hidden md:flex justify-center pt-2">
                    <div class="w-24 h-24 rounded-2xl bg-gray-700 border border-gray-600 flex items-center justify-center text-gray-500 shadow-inner">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                </div>

                <div class="md:col-span-2 space-y-6">
                    <div>
                        <x-input-label for="current_password" :value="__('Current Password')" class="block text-xs font-bold text-gray-400 uppercase mb-1" />
                        <x-text-input id="current_password" name="current_password" type="password" class="w-full bg-gray-700 border-gray-600 text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" />
                        <x-input-error :messages="$errors->get('current_password')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="password" :value="__('New Password')" class="block text-xs font-bold text-gray-400 uppercase mb-1" />
                        <x-text-input id="password" name="password" type="password" class="w-full bg-gray-700 border-gray-600 text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="password_confirmation" :value="__('Confirm New Password')" class="block text-xs font-bold text-gray-400 uppercase mb-1" />
                        <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="w-full bg-gray-700 border-gray-600 text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" />
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-gray-800 p-6 rounded-lg shadow-sm border border-gray-700 flex flex-col md:flex-row items-center justify-end gap-6">
            <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}" data-theme="dark"></div>

            <button type="submit" class="w-full md:w-auto bg-indigo-600 hover:bg-indigo-500 text-white px-8 py-3 rounded-md font-bold transition shadow-sm text-sm uppercase tracking-wider active:scale-95 border border-indigo-500">
                Save Changes
            </button>
        </div>
        <x-input-error class="mt-2 text-right" :messages="$errors->get('g-recaptcha-response')" />

    </form>
</section>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script>
    function livePreview(input) {
        const file = input.files[0];
        const preview = document.getElementById('avatar-preview');
        const cancelBtn = document.getElementById('cancel-upload');

        if (file) {
            if (!file.type.startsWith('image/')) {
                alert("Please select a valid image file.");
                input.value = "";
                return;
            }
            if (file.size > 2097152) {
                alert("File size exceeds 2MB limit.");
                input.value = "";
                return;
            }
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                cancelBtn.style.display = 'block';
            }
            reader.readAsDataURL(file);
        }
    }

    function revertImage() {
        const input = document.getElementById('avatar-upload');
        const preview = document.getElementById('avatar-preview');
        const cancelBtn = document.getElementById('cancel-upload');
        input.value = "";
        preview.src = preview.getAttribute('data-original');
        cancelBtn.style.display = 'none';
    }
</script>
