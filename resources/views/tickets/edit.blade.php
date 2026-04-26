<x-app-layout>
    @section('title', 'Edit Ticket')
    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="relative bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-2xl sm:rounded-lg p-5 sm:p-8 border border-gray-200 dark:border-gray-700 transition-colors">

                <a href="{{ route('tickets.index') }}"
                   class="absolute top-5 right-5 sm:top-8 sm:right-8 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition p-2 active:scale-95 flex items-center justify-center z-10"
                   title="Cancel and Go Back">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </a>

                <div class="mb-5 sm:mb-6 border-b border-gray-200 dark:border-gray-700 pb-3 sm:pb-4 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 sm:gap-0 transition-colors pr-10 sm:pr-12">
                    <div>
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white tracking-tight transition-colors">
                            Edit Ticket #{{ $ticket->id }}
                        </h2>
                        <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mt-1 transition-colors">
                            Update the status or details of this complaint.
                        </p>
                    </div>
                    <span class="self-start sm:self-auto px-3 py-1 rounded-full text-[10px] sm:text-sm font-bold border transition-colors uppercase tracking-wide
                        {{ $ticket->status === 'Open' ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 border-green-200 dark:border-green-800/50' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 border-gray-300 dark:border-gray-600' }}">
                        Currently: {{ $ticket->status }}
                    </span>
                </div>

                <form method="POST" action="{{ route('tickets.update', $ticket) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 mb-5 sm:mb-6">
                        <div>
                            <label class="block text-[10px] sm:text-xs font-bold text-gray-700 dark:text-gray-400 uppercase mb-1 transition-colors">Complainant Name (Student/Staff)</label>
                            <input type="text" name="reporter_name" value="{{ old('reporter_name', $ticket->reporter_name) }}" class="w-full text-sm sm:text-base bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white placeholder-gray-400 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition-colors" required>
                        </div>

                        <div>
                            <label class="block text-[10px] sm:text-xs font-bold text-gray-700 dark:text-gray-400 uppercase mb-1 transition-colors">Category</label>
                            <select name="category" class="w-full text-sm sm:text-base bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                                <option value="Hardware" {{ $ticket->category == 'Hardware' ? 'selected' : '' }}>Hardware</option>
                                <option value="Software" {{ $ticket->category == 'Software' ? 'selected' : '' }}>Software</option>
                                <option value="Network" {{ $ticket->category == 'Network' ? 'selected' : '' }}>Network</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-5 sm:mb-6">
                        <label class="block text-[10px] sm:text-xs font-bold text-gray-700 dark:text-gray-400 uppercase mb-1 transition-colors">Issue Subject</label>
                        <input type="text" name="title" value="{{ old('title', $ticket->title) }}" class="w-full text-sm sm:text-base bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white placeholder-gray-400 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition-colors" required>
                    </div>

                    <div class="mb-5 sm:mb-6">
                        <label class="block text-[10px] sm:text-xs font-bold text-gray-700 dark:text-gray-400 uppercase mb-1 transition-colors">Detailed Description</label>
                        <textarea name="description" rows="5" class="w-full text-sm sm:text-base bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white placeholder-gray-400 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition-colors" required>{{ old('description', $ticket->description) }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 mb-6 sm:mb-8">
                        <div>
                            <label class="block text-[10px] sm:text-xs font-bold text-gray-700 dark:text-gray-400 uppercase mb-1 transition-colors">Due Date (Deadline)</label>
                            <input type="date" name="due_date" value="{{ old('due_date', $ticket->due_date) }}" class="w-full text-sm sm:text-base bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white placeholder-gray-400 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        </div>

                        <div>
                            <label class="block text-[10px] sm:text-xs font-bold text-gray-700 dark:text-gray-400 uppercase mb-1 transition-colors">Priority Level</label>
                            <select name="priority" class="w-full text-sm sm:text-base bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                                <option value="Low" {{ $ticket->priority == 'Low' ? 'selected' : '' }}>Low</option>
                                <option value="Medium" {{ $ticket->priority == 'Medium' ? 'selected' : '' }}>Medium</option>
                                <option value="High" {{ $ticket->priority == 'High' ? 'selected' : '' }}>High</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-6 sm:mb-8">
                        <label class="block text-[10px] sm:text-xs font-bold text-gray-700 dark:text-gray-400 uppercase mb-2 sm:mb-1 transition-colors">Update Attachment (Photo/Video)</label>

                        @if($ticket->media_path)
                            <div class="mb-3 px-3 py-2 bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-100 dark:border-indigo-800 rounded-md inline-block max-w-full">
                                <p class="text-[10px] sm:text-xs text-indigo-700 dark:text-indigo-400 font-bold flex items-center gap-2 truncate">
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                    <span class="truncate">Currently attached: <a href="{{ asset('storage/' . $ticket->media_path) }}" target="_blank" class="underline hover:text-indigo-900 dark:hover:text-indigo-300 transition-colors">View File</a></span>
                                </p>
                            </div>
                        @endif

                        <input type="file" id="media-upload" name="media" accept=".jpg,.jpeg,.png,.mp4" class="block w-full text-xs sm:text-sm text-gray-500 file:mr-3 sm:file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-[10px] sm:file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 dark:file:bg-gray-700 dark:file:text-gray-300 transition-colors">

                        <p id="media-error" class="text-red-500 text-[10px] sm:text-xs font-bold mt-2 uppercase tracking-wide hidden"></p>

                        @error('media')
                            <p class="text-red-500 text-[10px] sm:text-xs font-bold mt-2 uppercase tracking-wide">
                                {{ $message }}
                            </p>
                        @enderror
                        <p class="text-gray-500 text-[10px] sm:text-xs mt-1.5 sm:mt-1 italic">Allowed formats: JPEG, PNG, MP4. Max size: 20MB. Uploading a new file will replace the current one.</p>
                    </div>

                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 sm:gap-5 border-t border-gray-200 dark:border-gray-700 pt-5 sm:pt-6 transition-colors">
                        <button type="submit" id="submit-btn" class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 sm:py-2.5 rounded-md font-bold transition shadow-sm text-xs sm:text-sm uppercase tracking-wider active:scale-95 border border-transparent">
                            Update Ticket
                        </button>
                        <a href="{{ route('tickets.index') }}" class="w-full sm:w-auto text-center text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-white transition font-bold py-2 sm:py-0 text-xs sm:text-sm uppercase tracking-wider">
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
