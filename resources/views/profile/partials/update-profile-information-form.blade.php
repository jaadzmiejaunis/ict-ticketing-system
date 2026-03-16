<section>
    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-12">
        @csrf
        @method('patch')

        <div class="border-b border-gray-800/50 pb-12">
            <header class="mb-10">
                <h2 class="text-xl font-bold text-white uppercase tracking-tight">Identity Information</h2>
                <p class="mt-1 text-[10px] font-black text-indigo-500 uppercase tracking-[0.2em] opacity-60">Update your name, email, and profile photo.</p>
            </header>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 items-start">

                <div class="flex flex-col items-center justify-center pt-4">
                    <div class="relative group">
                        <div class="w-40 h-40 rounded-full border-4 border-indigo-500/20 overflow-hidden ring-8 ring-[#0f172a] shadow-2xl">
                            @php
                                $avatarPath = Auth::user()->avatar;
                                $originalUrl = $avatarPath ? asset('storage/' . $avatarPath) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=6366f1&color=ffffff';
                            @endphp
                            <img src="{{ $originalUrl }}" class="w-full h-full object-cover" id="avatar-preview" data-original="{{ $originalUrl }}">
                        </div>

                        <label for="avatar-upload" class="absolute bottom-1 right-1 bg-indigo-600 p-3 rounded-full cursor-pointer border-4 border-[#111827] hover:bg-indigo-500 transition shadow-xl">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path></svg>
                            <input id="avatar-upload" name="avatar" type="file" class="hidden" accept="image/*" onchange="livePreview(this)" />
                        </label>

                        <button type="button" id="cancel-upload" style="display: none;" onclick="revertImage()" class="absolute -top-2 -right-2 bg-red-600 hover:bg-red-500 text-white p-2 rounded-full shadow-lg border-2 border-[#111827] transition active:scale-90">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                        </button>
                    </div>

                    <div class="mt-6 text-center">
                        <p class="text-[9px] font-black text-gray-500 uppercase tracking-[0.2em]">Only upload images below 2MB</p>
                        <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
                    </div>
                </div>

                <div class="md:col-span-2 space-y-8">
                    <div>
                        <x-input-label for="name" :value="__('Full Name')" class="text-[10px] font-black uppercase text-gray-400 tracking-widest mb-3" />
                        <x-text-input id="name" name="name" type="text" class="w-full bg-[#0f172a] border-gray-800 text-gray-200 rounded-xl py-4 px-6 shadow-inner focus:ring-indigo-500" :value="old('name', $user->name)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </div>

                    <div>
                        <x-input-label for="email" :value="__('Email Address')" class="text-[10px] font-black uppercase text-gray-400 tracking-widest mb-3" />
                        <x-text-input id="email" name="email" type="email" class="w-full bg-[#0f172a] border-gray-800 text-gray-200 rounded-xl py-4 px-6 shadow-inner focus:ring-indigo-500" :value="old('email', $user->email)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('email')" />
                    </div>
                </div>
            </div>
        </div>

        <div>
            <header class="mb-10">
                <h2 class="text-xl font-bold text-white uppercase tracking-tight">Security & Password</h2>
                <p class="mt-1 text-[10px] font-black text-indigo-500 uppercase tracking-[0.2em] opacity-60">Only fill this if you want to change your password.</p>
            </header>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 items-start">

                <div class="hidden md:flex justify-center pt-6">
                    <div class="w-28 h-28 rounded-3xl bg-indigo-500/5 border border-indigo-500/10 flex items-center justify-center text-indigo-500/20">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                </div>

                <div class="md:col-span-2 space-y-8">
                    <div>
                        <x-input-label for="current_password" :value="__('Current Password')" class="text-[10px] font-black uppercase text-gray-400 tracking-widest mb-3" />
                        <x-text-input id="current_password" name="current_password" type="password" class="w-full bg-[#0f172a] border-gray-800 text-gray-200 rounded-xl py-4 px-6 shadow-inner" />
                        <x-input-error :messages="$errors->get('current_password')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="password" :value="__('New Password')" class="text-[10px] font-black uppercase text-gray-400 tracking-widest mb-3" />
                        <x-text-input id="password" name="password" type="password" class="w-full bg-[#0f172a] border-gray-800 text-gray-200 rounded-xl py-4 px-6 shadow-inner" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="password_confirmation" :value="__('Confirm New Password')" class="text-[10px] font-black uppercase text-gray-400 tracking-widest mb-3" />
                        <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="w-full bg-[#0f172a] border-gray-800 text-gray-200 rounded-xl py-4 px-6 shadow-inner" />
                    </div>
                </div>
            </div>
        </div>

        <div class="pt-10">
            <div class="flex flex-col md:flex-row items-center justify-end gap-12 bg-[#0f172a] p-8 rounded-[2rem] border border-gray-800 shadow-inner">

                <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}" data-theme="dark"></div>

                <button type="submit" class="w-full md:w-auto bg-indigo-600 hover:bg-indigo-500 text-white px-20 py-5 rounded-2xl text-xs font-black uppercase tracking-[0.25em] transition shadow-2xl shadow-indigo-500/20 active:scale-95">
                    SAVE ALL CHANGES
                </button>

            </div>
            <x-input-error class="mt-4 text-right" :messages="$errors->get('g-recaptcha-response')" />
        </div>
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
