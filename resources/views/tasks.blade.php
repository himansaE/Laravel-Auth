<x-app-layout title="Tasks">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header with count -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Tasks</h1>
            <p class="text-sm text-gray-500 mt-1">{{ count($tasks) }} tasks loaded</p>
        </div>

        <!-- Tasks Grid -->
        <div class="grid gap-4">
            @forelse($tasks as $task)
                @if(is_object($task) && method_exists($task, 'getTitle'))
                    @php
                        $title = $task->getTitle() ?: 'Untitled Task';
                        $notes = $task->getNotes();
                        $status = $task->getStatus();
                        $due = $task->getDue();
                        $updated = $task->getUpdated();
                        
                        $isCompleted = $status === 'completed';
                        
                        $dueDate = '';
                        if ($due) {
                            try {
                                $dueDate = \Carbon\Carbon::parse($due)->format('M j, Y');
                            } catch (\Exception $e) {
                                $dueDate = $due;
                            }
                        }
                        
                        $updatedDate = '';
                        if ($updated) {
                            try {
                                $updatedDate = \Carbon\Carbon::parse($updated)->format('M j, Y g:i A');
                            } catch (\Exception $e) {
                                $updatedDate = $updated;
                            }
                        }
                    @endphp

                    <div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-md transition-shadow">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mt-1">
                                @if($isCompleted)
                                    <div class="w-5 h-5 bg-green-500 rounded-full flex items-center justify-center">
                                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                @else
                                    <div class="w-5 h-5 border-2 border-gray-300 rounded-full"></div>
                                @endif
                            </div>
                            
                            <div class="ml-4 flex-1">
                                <h3 class="font-semibold text-gray-900 {{ $isCompleted ? 'line-through text-gray-500' : '' }}">
                                    {{ $title }}
                                </h3>
                                
                                @if($notes)
                                    <p class="mt-2 text-sm text-gray-700">{{ Str::limit($notes, 200) }}</p>
                                @endif
                                
                                <div class="flex items-center mt-3 space-x-4 text-xs text-gray-500">
                                    @if($dueDate)
                                        <div class="flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            Due: {{ $dueDate }}
                                        </div>
                                    @endif
                                    
                                    @if($updatedDate)
                                        <div class="flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Updated: {{ $updatedDate }}
                                        </div>
                                    @endif
                                    
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $isCompleted ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $isCompleted ? 'Completed' : 'Pending' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @empty
                <div class="text-center py-12">
                    <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No tasks found</h3>
                    <p class="text-gray-500">No tasks to display at the moment.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($nextPageToken || $prevPageToken)
            <div class="flex justify-between items-center mt-8">
                @if($prevPageToken)
                    <a href="{{ route('tasks', ['pageToken' => $prevPageToken]) }}" 
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
                    <a href="{{ route('tasks', ['pageToken' => $nextPageToken]) }}" 
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