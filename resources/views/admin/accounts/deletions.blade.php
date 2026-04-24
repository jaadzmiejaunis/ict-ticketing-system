<x-app-layout>
    @section('title', 'Permanent Deletion Audit')

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <div>
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white tracking-tight transition-colors">Permanent Deletion Audit</h2>
                    <p class="text-gray-600 dark:text-gray-400 text-xs sm:text-sm transition-colors mt-1">A permanent record of all accounts purged from the system.</p>
                </div>
                <a href="{{ route('admin.accounts') }}" class="w-full md:w-auto justify-center bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 px-5 py-3 sm:py-2 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 font-bold transition text-xs sm:text-sm flex items-center gap-2 shadow-sm active:scale-95 border border-gray-300 dark:border-gray-600">
                    &larr; Back to User Management
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-2xl sm:rounded-lg shadow-sm overflow-hidden border border-gray-200 dark:border-gray-700 transition-colors">

                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full min-w-[800px] text-left border-collapse">
                        <thead class="bg-gray-50 dark:bg-gray-900/50 border-b border-gray-200 dark:border-gray-700 transition-colors">
                            <tr class="text-[10px] sm:text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest transition-colors">
                                <th class="px-4 sm:px-6 py-4">Purged User</th>
                                <th class="px-4 sm:px-6 py-4">Performed By</th>
                                <th class="px-4 sm:px-6 py-4">Reason</th>
                                <th class="px-4 sm:px-6 py-4 text-right">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700 transition-colors">
                            @forelse($logs as $log)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition duration-150">
                                    <td class="px-4 sm:px-6 py-4">
                                        <div class="font-bold text-red-600 dark:text-red-400 text-xs sm:text-sm truncate max-w-[200px]">{{ $log->user_name }}</div>
                                        <div class="text-[10px] sm:text-xs text-gray-400 dark:text-gray-500 italic truncate max-w-[200px]">{{ $log->user_email }}</div>
                                    </td>
                                    <td class="px-4 sm:px-6 py-4">
                                        <div class="text-xs sm:text-sm font-bold text-gray-900 dark:text-gray-200 transition-colors">{{ $log->admin->name }}</div>
                                        <div class="text-[9px] sm:text-[10px] text-indigo-600 dark:text-indigo-400 font-black uppercase tracking-tighter">Administrator</div>
                                    </td>
                                    <td class="px-4 sm:px-6 py-4">
                                        <div class="text-xs sm:text-sm text-gray-600 dark:text-gray-300 leading-relaxed max-w-xs sm:max-w-md truncate md:whitespace-normal" title="{{ $log->reason }}">
                                            {{ $log->reason }}
                                        </div>
                                    </td>
                                    <td class="px-4 sm:px-6 py-4 text-right">
                                        <div class="text-xs sm:text-sm font-bold text-gray-900 dark:text-gray-300 transition-colors">{{ $log->created_at->format('d M Y') }}</div>
                                        <div class="text-[10px] sm:text-xs text-gray-500 dark:text-gray-500 mt-0.5">{{ $log->created_at->format('h:i A') }}</div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-16 sm:py-24 text-center text-gray-500 dark:text-gray-400 font-bold italic text-xs sm:text-sm transition-colors">
                                        No deletion logs found. Your database is squeaky clean!
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($logs->hasPages())
                    <div class="p-4 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-200 dark:border-gray-700 transition-colors">
                        {{ $logs->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
