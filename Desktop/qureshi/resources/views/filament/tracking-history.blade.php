<div class="p-4">
    <div class="mb-4">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
            Shipment: {{ $booking->consignment_no }}
        </h3>
        <p class="text-sm text-gray-500 dark:text-gray-400">
            Courier: {{ $booking->courier_integration?->courier_name ?? 'N/A' }} | 
            Status: <span class="font-medium">{{ ucwords(str_replace('_', ' ', $booking->status)) }}</span>
        </p>
        @if($booking->delivered_at)
            <p class="text-sm text-green-600 dark:text-green-400">
                Delivered: {{ $booking->delivered_at->format('M d, Y H:i') }}
            </p>
        @endif
    </div>

    <div class="relative">
        @if($history->count() > 0)
            <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gray-200 dark:bg-gray-700"></div>
            
            @foreach($history as $index => $entry)
                <div class="relative flex items-start mb-6">
                    <div class="absolute left-2.5 w-3 h-3 rounded-full 
                        @if($loop->first)
                            bg-blue-500 ring-4 ring-blue-100 dark:ring-blue-900
                        @else
                            bg-gray-400 dark:bg-gray-500
                        @endif
                        mt-1.5">
                    </div>
                    <div class="ml-10 flex-1">
                        <div class="bg-white dark:bg-gray-800 rounded-lg p-3 shadow-sm border border-gray-200 dark:border-gray-700">
                            <div class="flex items-center justify-between">
                                <span class="font-medium text-sm text-gray-900 dark:text-white">
                                    {{ ucwords(str_replace('_', ' ', $entry->status)) }}
                                </span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $entry->created_at->format('M d, Y H:i') }}
                                </span>
                            </div>
                            @if($entry->location)
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    <span class="font-medium">Location:</span> {{ $entry->location }}
                                </p>
                            @endif
                            @if($entry->description)
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                                    {{ $entry->description }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="text-center py-8 text-gray-400 dark:text-gray-500">
                <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
                <p>No tracking history available yet.</p>
            </div>
        @endif
    </div>
</div>