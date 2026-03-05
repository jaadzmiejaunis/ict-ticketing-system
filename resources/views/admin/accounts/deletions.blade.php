<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-white">Permanent Deletion Audit</h2>
                    <p class="text-gray-400 text-sm">A permanent record of all accounts purged from the system.</p>
                </div>
                <a href="{{ route('admin.accounts') }}" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 font-semibold transition text-sm flex items-center gap-2 shadow-sm">
                    &larr; Back to User Management
                </a>
            </div>

            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">Purged User</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">Performed By</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">Reason</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase text-right">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($logs as $log)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-red-600">{{ $log->user_name }}</div>
                                    <div class="text-xs text-gray-400 italic">{{ $log->user_email }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-semibold text-gray-900">{{ $log->admin->name }}</div>
                                    <div class="text-[10px] text-indigo-600 font-bold uppercase">Administrator</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $log->reason }}
                                </td>
                                <td class="px-6 py-4 text-right text-sm text-gray-500">
                                    {{ $log->created_at->format('d M Y, h:i A') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-500 italic">
                                    No deletion logs found. Your database is squeaky clean!
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
