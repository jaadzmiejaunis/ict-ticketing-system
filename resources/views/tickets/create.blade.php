<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <div class="mb-6 border-b border-gray-200 pb-4">
                    <h2 class="text-2xl font-bold text-gray-800">
                        New Complaint
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">
                        Please fill in the details of the issue below.
                    </p>
                </div>

                <form method="POST" action="{{ route('tickets.store') }}">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                        <div>
                            <label class="block font-medium text-sm text-gray-700 mb-1">Complainant Name (Student/Staff)</label>
                            <input type="text" name="reporter_name" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full" required>
                        </div>

                        <div>
                            <label class="block font-medium text-sm text-gray-700 mb-1">Category</label>
                            <select name="category" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full">
                                <option value="Hardware">Hardware</option>
                                <option value="Software">Software</option>
                                <option value="Network">Network</option>
                            </select>
                            <div class="mb-4">
                                <label class="block font-medium text-sm text-gray-700 mb-1">Due Date (Deadline)</label>
                                <input type="date" name="due_date" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full">
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700 mb-1">Issue Subject</label>
                        <input type="text" name="title" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full" placeholder="e.g. PC won't turn on" required>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700 mb-1">Detailed Description</label>
                        <textarea name="description" rows="5" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full" placeholder="Describe the problem..." required></textarea>
                    </div>

                    <div class="mb-6 w-full md:w-1/3">
                        <label class="block font-medium text-sm text-gray-700 mb-1">Priority Level</label>
                        <select name="priority" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full">
                            <option value="Low">Low</option>
                            <option value="Medium">Medium</option>
                            <option value="High">High</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-4 border-t border-gray-100 pt-4">
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 font-bold transition">
                            Submit Ticket
                        </button>
                        <a href="{{ route('tickets.index') }}" class="text-gray-600 hover:text-gray-900 font-medium">
                            Cancel
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
