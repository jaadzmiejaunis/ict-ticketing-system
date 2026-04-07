<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 sm:p-8 border border-gray-700">

                <div class="mb-6 border-b border-gray-700 pb-4 flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-white tracking-tight">
                            Edit Ticket #{{ $ticket->id }}
                        </h2>
                        <p class="text-sm text-gray-400 mt-1">
                            Update the status or details of this complaint.
                        </p>
                    </div>
                    <span class="px-3 py-1 rounded-full text-sm font-bold border
                        {{ $ticket->status === 'Open' ? 'bg-green-900/30 text-green-400 border-green-800' : 'bg-gray-700 text-gray-300 border-gray-600' }}">
                        Currently: {{ $ticket->status }}
                    </span>
                </div>

                <form method="POST" action="{{ route('tickets.update', $ticket) }}">
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Complainant Name (Student/Staff)</label>
                            <input type="text" name="reporter_name" value="{{ old('reporter_name', $ticket->reporter_name) }}" class="w-full bg-gray-700 border-gray-600 text-white placeholder-gray-400 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Category</label>
                            <select name="category" class="w-full bg-gray-700 border-gray-600 text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="Hardware" {{ $ticket->category == 'Hardware' ? 'selected' : '' }}>Hardware</option>
                                <option value="Software" {{ $ticket->category == 'Software' ? 'selected' : '' }}>Software</option>
                                <option value="Network" {{ $ticket->category == 'Network' ? 'selected' : '' }}>Network</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Issue Subject</label>
                        <input type="text" name="title" value="{{ old('title', $ticket->title) }}" class="w-full bg-gray-700 border-gray-600 text-white placeholder-gray-400 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                    </div>

                    <div class="mb-6">
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Detailed Description</label>
                        <textarea name="description" rows="5" class="w-full bg-gray-700 border-gray-600 text-white placeholder-gray-400 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>{{ old('description', $ticket->description) }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Due Date (Deadline)</label>
                            <input type="date" name="due_date" value="{{ old('due_date', $ticket->due_date) }}" class="w-full bg-gray-700 border-gray-600 text-white placeholder-gray-400 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 [color-scheme:dark]">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Priority Level</label>
                            <select name="priority" class="w-full bg-gray-700 border-gray-600 text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="Low" {{ $ticket->priority == 'Low' ? 'selected' : '' }}>Low</option>
                                <option value="Medium" {{ $ticket->priority == 'Medium' ? 'selected' : '' }}>Medium</option>
                                <option value="High" {{ $ticket->priority == 'High' ? 'selected' : '' }}>High</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center gap-5 border-t border-gray-700 pt-6">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white px-8 py-2.5 rounded-md font-bold transition shadow-sm text-sm uppercase tracking-wider active:scale-95 border border-indigo-500">
                            Update Ticket
                        </button>
                        <a href="{{ route('tickets.index') }}" class="text-gray-400 hover:text-white transition font-bold text-sm uppercase tracking-wider">
                            Cancel
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
