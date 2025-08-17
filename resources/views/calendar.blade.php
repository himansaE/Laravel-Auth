<x-app-layout title="Calendar Events">
    <div>
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-xl font-semibold">Calendar Events</h1>
            <p class="text-sm text-gray-600">{{ count($calendarEvents) }} events found</p>
        </div>

        <!-- Events List -->
        <div class="space-y-3">
            @forelse($calendarEvents as $event)
                @if(is_object($event) && method_exists($event, 'getSummary'))
                    @php
                        $summary = $event->getSummary() ?: 'No Title';
                        $start = $event->getStart();
                        $startTime = '';
                        
                        if ($start) {
                            if ($start->dateTime) {
                                try {
                                    $startTime = \Carbon\Carbon::parse($start->dateTime)->format('M j, Y g:i A');
                                } catch (\Exception $e) {
                                    $startTime = $start->dateTime;
                                }
                            } elseif ($start->date) {
                                try {
                                    $startTime = \Carbon\Carbon::parse($start->date)->format('M j, Y') . ' (All day)';
                                } catch (\Exception $e) {
                                    $startTime = $start->date . ' (All day)';
                                }
                            }
                        }
                        
                        $description = $event->getDescription();
                        $location = $event->getLocation();
                    @endphp

                    <div class="border border-gray-200 rounded p-4">
                        <h3 class="font-medium">{{ $summary }}</h3>
                        
                        @if($startTime)
                            <p class="text-sm text-gray-600 mt-1">{{ $startTime }}</p>
                        @endif

                        @if($location)
                            <p class="text-sm text-gray-600 mt-1">{{ $location }}</p>
                        @endif

                        @if($description)
                            <p class="text-sm text-gray-700 mt-2">{{ Str::limit($description, 150) }}</p>
                        @endif
                    </div>
                @endif
            @empty
                <div class="text-center py-12">
                    <p class="text-gray-500">No events found</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($nextPageToken ?? false || $prevPageToken ?? false)
            <div class="flex justify-between items-center mt-8">
                @if($prevPageToken ?? false)
                    <a href="{{ route('calendar', ['pageToken' => $prevPageToken]) }}" 
                       class="px-4 py-2 border border-gray-300 text-sm rounded hover:bg-gray-50">
                        Previous
                    </a>
                @else
                    <div></div>
                @endif

                @if($nextPageToken ?? false)
                    <a href="{{ route('calendar', ['pageToken' => $nextPageToken]) }}" 
                       class="px-4 py-2 border border-gray-300 text-sm rounded hover:bg-gray-50">
                        Next
                    </a>
                @endif
            </div>
        @endif
    </div>
                       class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Next
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                @endif
            </div>
        @endif
    </div>
</x-app-layout>    </div>
</x-app-layout>