<x-app-layout title="Calendar Events">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header with count -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Calendar Events</h1>
            <p class="text-sm text-gray-500 mt-1">{{ count($calendarEvents) }} events loaded</p>
        </div>

        <!-- Events Grid -->
        <div class="grid gap-4">
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

                    <div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-md transition-shadow">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900 text-lg">{{ $summary }}</h3>
                                
                                @if($startTime)
                                    <div class="flex items-center mt-2 text-sm text-gray-600">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $startTime }}
                                    </div>
                                @endif

                                @if($location)
                                    <div class="flex items-center mt-1 text-sm text-gray-600">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        {{ $location }}
                                    </div>
                                @endif

                                @if($description)
                                    <p class="mt-3 text-sm text-gray-700 line-clamp-2">{{ Str::limit($description, 150) }}</p>
                                @endif
                            </div>
                            
                            <div class="ml-4 flex-shrink-0">
                                <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                            </div>
                        </div>
                    </div>
                @endif
            @empty
                <div class="text-center py-12">
                    <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No events found</h3>
                    <p class="text-gray-500">No calendar events to display at the moment.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($nextPageToken || $prevPageToken)
            <div class="flex justify-between items-center mt-8">
                @if($prevPageToken)
                    <a href="{{ route('calendar', ['pageToken' => $prevPageToken]) }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Previous
                    </a>
                @else
                    <div></div>
                @endif

                @if($nextPageToken)
                    <a href="{{ route('calendar', ['pageToken' => $nextPageToken]) }}" 
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
</x-app-layout>