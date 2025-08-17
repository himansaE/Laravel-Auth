<x-app-layout title="Emails">
    <div>
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-xl font-semibold">Emails</h1>
            <p class="text-sm text-gray-600">{{ count($emails) }} emails found</p>
        </div>

        <!-- Emails List -->
        <div class="space-y-3">
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
                            $cleanDate = preg_replace('/\s*\([^)]+\)$/', '', $date);
                            $formattedDate = \Carbon\Carbon::parse($cleanDate)->format('M j, Y g:i A');
                        } catch (\Exception $e) {
                            $formattedDate = $date;
                        }
                        }

                        // Get snippet if available
                        $snippet = $email->getSnippet();
                    @endphp

                    <div class="border border-gray-200 rounded p-4 {{ $isUnread ? 'border-l-4 border-l-blue-500' : '' }}">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h3 class="font-medium {{ $isUnread ? 'font-semibold' : '' }}">
                                    {{ $subject ?: 'No Subject' }}
                                    @if($isUnread)
                                        <span class="text-blue-600 text-sm ml-2">•</span>
                                    @endif
                                </h3>
                                
                                <p class="text-sm text-gray-600 mt-1">{{ $displayFrom }}</p>

                                @if($date)
                                    <p class="text-xs text-gray-500 mt-1">{{ $formattedDate }}</p>
                                @endif

                                @if($snippet)
                                    <p class="text-sm text-gray-700 mt-2">{{ Str::limit($snippet, 100) }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            @empty
                <div class="text-center py-12">
                    <p class="text-gray-500">No emails found</p>
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
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($nextPageToken ?? false || $prevPageToken ?? false)
            <div class="flex justify-between items-center mt-8">
                @if($prevPageToken ?? false)
                    <a href="{{ route('emails', ['pageToken' => $prevPageToken]) }}" 
                       class="px-4 py-2 border border-gray-300 text-sm rounded hover:bg-gray-50">
                        Previous
                    </a>
                @else
                    <div></div>
                @endif

                @if($nextPageToken ?? false)
                    <a href="{{ route('emails', ['pageToken' => $nextPageToken]) }}" 
                       class="px-4 py-2 border border-gray-300 text-sm rounded hover:bg-gray-50">
                        Next
                    </a>
                @endif
            </div>
        @endif
    </div>
</x-app-layout>