<x-app-layout title="Emails">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header with count -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Emails</h1>
            <p class="text-sm text-gray-500 mt-1">{{ count($emails) }} emails loaded</p>
        </div>

        <!-- Emails Grid -->
        <div class="grid gap-4">
            @forelse($emails as $email)
                @if(is_object($email) && method_exists($email, 'getPayload'))
                    @php
                        $payload = $email->getPayload();
                        $headers = $payload->getHeaders();

                        $subject = '';
                        $from = '';
                        $date = '';

                        foreach($headers as $header) {
                            if($header->getName() === 'Subject') {
                                $subject = $header->getValue();
                            } elseif($header->getName() === 'From') {
                                $from = $header->getValue();
                            } elseif($header->getName() === 'Date') {
                                $date = $header->getValue();
                            }
                        }

                        // Extract just the email/name from the From field
                        if(preg_match('/^(.*?)\s*<(.+?)>$/', $from, $matches)) {
                            $fromName = trim($matches[1], '"');
                            $fromEmail = $matches[2];
                            $displayFrom = $fromName ?: $fromEmail;
                        } else {
                            $displayFrom = $from;
                        }

                        // Check if email is unread (UNREAD label in labelIds)
                        $labelIds = $email->getLabelIds() ?? [];
                        $isUnread = in_array('UNREAD', $labelIds);

                        try {
                            // Clean up the date string to remove duplicate timezone info
                            $cleanDate = preg_replace('/\s*\([^)]+\)$/', '', $date);
                            $formattedDate = \Carbon\Carbon::parse($cleanDate)->format('M j, Y g:i A');
                        } catch (\Exception $e) {
                            // Fallback to raw date if parsing fails
                            $formattedDate = $date;
                        }

                        // Get snippet if available
                        $snippet = $email->getSnippet();
                    @endphp

                    <div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-md transition-shadow {{ $isUnread ? 'border-l-4 border-l-blue-500' : '' }}">
                        <div class="flex items-start justify-between">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between mb-2">
                                    <h3 class="font-semibold text-gray-900 {{ $isUnread ? 'font-bold' : '' }}">
                                        {{ $subject ?: 'No Subject' }}
                                    </h3>
                                    @if($isUnread)
                                        <div class="flex-shrink-0 ml-2">
                                            <div class="w-2 h-2 bg-blue-500 rounded-full" title="Unread"></div>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="flex items-center text-sm text-gray-600 mb-2">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <span class="font-medium">{{ $displayFrom }}</span>
                                </div>

                                @if($date)
                                    <div class="flex items-center text-xs text-gray-500 mb-3">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $formattedDate }}
                                    </div>
                                @endif

                                @if($snippet)
                                    <p class="text-sm text-gray-700 line-clamp-2">{{ $snippet }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            @empty
                <div class="text-center py-12">
                    <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No emails found</h3>
                    <p class="text-gray-500">No emails to display at the moment.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($nextPageToken || $prevPageToken)
            <div class="flex justify-between items-center mt-8">
                @if($prevPageToken)
                    <a href="{{ route('emails', ['pageToken' => $prevPageToken]) }}" 
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
                    <a href="{{ route('emails', ['pageToken' => $nextPageToken]) }}" 
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