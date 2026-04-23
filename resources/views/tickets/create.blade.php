<x-app-layout>
    @section('title', 'New Complaint')

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="relative bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-2xl sm:rounded-lg p-5 sm:p-8 border border-gray-200 dark:border-gray-700 transition-colors">

                <a href="{{ route('tickets.index') }}"
                   class="absolute top-5 right-5 sm:top-8 sm:right-8 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition p-2 active:scale-95 flex items-center justify-center"
                   title="Cancel and Go Back">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </a>

                <div class="mb-5 sm:mb-6 border-b border-gray-200 dark:border-gray-700 pb-3 sm:pb-4 transition-colors pr-10">
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white tracking-tight transition-colors">
                        New Complaint
                    </h2>
                    <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mt-1 transition-colors">
                        Please fill in the details of the issue below.
                    </p>
                </div>

                <form method="POST" action="{{ route('tickets.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 mb-5 sm:mb-6">
                        <div>
                            <label class="block text-[10px] sm:text-xs font-bold text-gray-600 dark:text-gray-400 uppercase mb-1 transition-colors">Complainant Name (Student/Staff)</label>
                            <input type="text" name="reporter_name" value="{{ old('reporter_name') }}" class="w-full text-sm sm:text-base bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white placeholder-gray-400 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition-colors" placeholder="Enter full name..." required>
                            @error('reporter_name') <p class="text-red-500 text-[10px] sm:text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-[10px] sm:text-xs font-bold text-gray-600 dark:text-gray-400 uppercase mb-1 transition-colors">Category</label>
                            <select name="category" class="w-full text-sm sm:text-base bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                                <option value="Hardware" {{ old('category') == 'Hardware' ? 'selected' : '' }}>Hardware</option>
                                <option value="Software" {{ old('category') == 'Software' ? 'selected' : '' }}>Software</option>
                                <option value="Network" {{ old('category') == 'Network' ? 'selected' : '' }}>Network</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-5 sm:mb-6">
                        <label class="block text-[10px] sm:text-xs font-bold text-gray-600 dark:text-gray-400 uppercase mb-1 transition-colors">Issue Subject</label>
                        <input type="text" name="title" value="{{ old('title') }}" class="w-full text-sm sm:text-base bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white placeholder-gray-400 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition-colors" placeholder="e.g. PC won't turn on, Forgotten portal password..." required>
                        @error('title') <p class="text-red-500 text-[10px] sm:text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-5 sm:mb-6">
                        <label class="block text-[10px] sm:text-xs font-bold text-gray-600 dark:text-gray-400 uppercase mb-1 transition-colors">Detailed Description</label>
                        <textarea name="description" rows="5" class="w-full text-sm sm:text-base bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white placeholder-gray-400 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition-colors" placeholder="Describe the problem in detail..." required>{{ old('description') }}</textarea>
                        @error('description') <p class="text-red-500 text-[10px] sm:text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 mb-6 sm:mb-8">
                        <div>
                            <label class="block text-[10px] sm:text-xs font-bold text-gray-600 dark:text-gray-400 uppercase mb-1 transition-colors">Due Date (Deadline)</label>
                            <input type="date" name="due_date" value="{{ old('due_date') }}" class="w-full text-sm sm:text-base bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white placeholder-gray-400 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 [color-scheme:light] dark:[color-scheme:dark] transition-colors">
                        </div>

                        <div>
                            <label class="block text-[10px] sm:text-xs font-bold text-gray-600 dark:text-gray-400 uppercase mb-1 transition-colors">Priority Level</label>
                            <select name="priority" class="w-full text-sm sm:text-base bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                                <option value="Low" {{ old('priority') == 'Low' ? 'selected' : '' }}>Low</option>
                                <option value="Medium" {{ old('priority') == 'Medium' || !old('priority') ? 'selected' : '' }}>Medium</option>
                                <option value="High" {{ old('priority') == 'High' ? 'selected' : '' }}>High</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-6 sm:mb-8">
                        <label class="block text-[10px] sm:text-xs font-bold text-gray-600 dark:text-gray-400 uppercase mb-2 sm:mb-1">Attachment (Photo/Video)</label>

                        <input type="file" id="media-upload" name="media" accept=".jpg,.jpeg,.png,.mp4" class="block w-full text-xs sm:text-sm text-gray-500 file:mr-3 sm:file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-[10px] sm:file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 dark:file:bg-gray-700 dark:file:text-gray-300 transition-colors">

                        <p id="media-error" class="text-red-500 text-[10px] sm:text-xs font-bold mt-2 uppercase tracking-wide hidden"></p>

                        @error('media')
                            <p class="text-red-500 text-[10px] sm:text-xs font-bold mt-2 uppercase tracking-wide">
                                {{ $message }}
                            </p>
                        @enderror
                        <p class="text-gray-500 text-[10px] sm:text-xs mt-1.5 sm:mt-1 italic">Allowed formats: JPEG, PNG, MP4. Max size: 20MB</p>
                    </div>

                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 sm:gap-5 border-t border-gray-200 dark:border-gray-700 pt-5 sm:pt-6 transition-colors">
                        <button type="submit" id="submit-btn" class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 sm:py-2.5 rounded-md font-bold transition shadow-sm text-xs sm:text-sm uppercase tracking-wider active:scale-95 border border-transparent">
                            Submit Ticket
                        </button>
                        <a href="{{ route('tickets.index') }}" class="w-full sm:w-auto text-center text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition font-bold py-2 sm:py-0 text-xs sm:text-sm uppercase tracking-wider">
                            Cancel
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mediaUpload = document.getElementById('media-upload');
            const mediaError = document.getElementById('media-error');
            const submitBtn = document.getElementById('submit-btn');

            if (mediaUpload) {
                mediaUpload.addEventListener('change', function(e) {
                    const file = e.target.files[0];

                    if (file) {
                        const validTypes = ['image/jpeg', 'image/png', 'video/mp4'];
                        if (!validTypes.includes(file.type)) {
                            mediaError.textContent = '❌ ERROR: Invalid format! Please upload only JPEG, PNG, or MP4 files.';
                            mediaError.classList.remove('hidden');
                            mediaUpload.value = '';
                            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                            submitBtn.disabled = true;
                            return;
                        }

                        if (file.size > 20971520) {
                            mediaError.textContent = '❌ ERROR: File is too large! Maximum allowed size is 20MB.';
                            mediaError.classList.remove('hidden');
                            mediaUpload.value = '';
                            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                            submitBtn.disabled = true;
                            return;
                        }

                        mediaError.classList.add('hidden');
                        submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                        submitBtn.disabled = false;
                    } else {
                        mediaError.classList.add('hidden');
                        submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                        submitBtn.disabled = false;
                    }
                });
            }
        });
    </script>
</x-app-layout>
