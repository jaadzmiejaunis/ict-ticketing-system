<x-app-layout>
    @section('title', 'New Complaint')

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 sm:p-8 border border-gray-200 dark:border-gray-700 transition-colors">

                <div class="mb-6 border-b border-gray-200 dark:border-gray-700 pb-4 transition-colors">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight transition-colors">
                        New Complaint
                    </h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1 transition-colors">
                        Please fill in the details of the issue below.
                    </p>
                </div>

                <form method="POST" action="{{ route('tickets.store') }}">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 uppercase mb-1 transition-colors">Complainant Name (Student/Staff)</label>
                            <input type="text" name="reporter_name" class="w-full bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white placeholder-gray-400 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition-colors" placeholder="Enter full name..." required>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 uppercase mb-1 transition-colors">Category</label>
                            <select name="category" class="w-full bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                                <option value="Hardware">Hardware</option>
                                <option value="Software">Software</option>
                                <option value="Network">Network</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 uppercase mb-1 transition-colors">Issue Subject</label>
                        <input type="text" name="title" class="w-full bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white placeholder-gray-400 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition-colors" placeholder="e.g. PC won't turn on, Forgotten portal password..." required>
                    </div>

                    <div class="mb-6">
                        <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 uppercase mb-1 transition-colors">Detailed Description</label>
                        <textarea name="description" rows="5" class="w-full bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white placeholder-gray-400 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition-colors" placeholder="Describe the problem in detail..." required></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 uppercase mb-1 transition-colors">Due Date (Deadline)</label>
                            <input type="date" name="due_date" class="w-full bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white placeholder-gray-400 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 [color-scheme:light] dark:[color-scheme:dark] transition-colors">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 uppercase mb-1 transition-colors">Priority Level</label>
                            <select name="priority" class="w-full bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                                <option value="Low">Low</option>
                                <option value="Medium" selected>Medium</option>
                                <option value="High">High</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center gap-5 border-t border-gray-200 dark:border-gray-700 pt-6 transition-colors">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-2.5 rounded-md font-bold transition shadow-sm text-sm uppercase tracking-wider active:scale-95 border border-transparent">
                            Submit Ticket
                        </button>
                        <a href="{{ route('tickets.index') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition font-bold text-sm uppercase tracking-wider">
                            Cancel
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
