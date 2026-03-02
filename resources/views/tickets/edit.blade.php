<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <div class="mb-6 border-b border-gray-200 pb-4 flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">
                            Edit Ticket #{{ $ticket->id }}
                        </h2>
                        <p class="text-sm text-gray-500 mt-1">
                            Update the status or details of this complaint.
                        </p>
                    </div>
                    <span class="px-3 py-1 rounded-full text-sm font-bold
                        {{ $ticket->status === 'Open' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        Currently: {{ $ticket->status }}
                    </span>
                </div>

                <form method="POST" action="{{ route('tickets.update', $ticket) }}">
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                        <div>
                            <label class="block font-medium text-sm text-gray-700 mb-1">Complainant Name</label>
                            <input type="text" name="reporter_name" value="{{ old('reporter_name', $ticket->reporter_name) }}" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full" required>
                        </div>

                        <div>
                            <label class="block font-medium text-sm text-gray-700 mb-1">Category</label>
                            <select name="category" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full">
                                <option value="Hardware" {{ $ticket->category == 'Hardware' ? 'selected' : '' }}>Hardware</option>
                                <option value="Software" {{ $ticket->category == 'Software' ? 'selected' : '' }}>Software</option>
                                <option value="Network" {{ $ticket->category == 'Network' ? 'selected' : '' }}>Network</option>
                            </select>
                            <div class="mb-4">
                                <label class="block font-medium text-sm text-gray-700 mb-1">Due Date (Deadline)</label>
                                <input type="date" name="due_date" value="{{ old('due_date', $ticket->due_date) }}" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full">
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700 mb-1">Issue Subject</label>
                        <input type="text" name="title" value="{{ old('title', $ticket->title) }}" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full" required>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700 mb-1">Detailed Description</label>
                        <textarea name="description" rows="5" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full" required>{{ old('description', $ticket->description) }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block font-medium text-sm text-gray-700 mb-1">Priority Level</label>
                            <select name="priority" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full">
                                <option value="Low" {{ $ticket->priority == 'Low' ? 'selected' : '' }}>Low</option>
                                <option value="Medium" {{ $ticket->priority == 'Medium' ? 'selected' : '' }}>Medium</option>
                                <option value="High" {{ $ticket->priority == 'High' ? 'selected' : '' }}>High</option>
                            </select>
                        </div>

                        <div>
                            <label class="block font-medium text-sm text-gray-700 mb-1">Status</label>
                            <select name="status" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full bg-gray-50">
                                <option value="Open" {{ $ticket->status == 'Open' ? 'selected' : '' }}>Open</option>
                                <option value="Assigned" {{ $ticket->status == 'Assigned' ? 'selected' : '' }}>Assigned</option>
                                <option value="On Hold" {{ $ticket->status == 'On Hold' ? 'selected' : '' }}>On Hold</option>
                                <option value="Resolved" {{ $ticket->status == 'Resolved' ? 'selected' : '' }}>Resolved</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 border-t border-gray-100 pt-4">
                        <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 font-bold transition">
                            Update Ticket
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
