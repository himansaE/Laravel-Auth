<x-app-layout title="Tasks">
    <div>
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-xl font-semibold">Tasks</h1>
            <p class="text-sm text-gray-600">{{ count($tasks) }} tasks found</p>
        </div>

        <!-- Tasks List -->
        <div class="space-y-3">
            @forelse($tasks as $task)
                @if(is_object($task) && method_exists($task, 'getTitle'))
                    @php
                        $title = $task->getTitle() ?: 'Untitled Task';
                        $notes = $task->getNotes();
                        $status = $task->getStatus();
                        $due = $task->getDue();
                        
                        $isCompleted = $status === 'completed';
                        
                        $dueDate = '';
                        if ($due) {
                            try {
                                $dueDate = \Carbon\Carbon::parse($due)->format('M j, Y');
                            } catch (\Exception $e) {
                                $dueDate = $due;
                            }
                        }
                    @endphp

                    <div class="border border-gray-200 rounded p-4">
                        <div class="flex items-start space-x-3">
                            <div class="mt-1">
                                @if($isCompleted)
                                    <div class="w-4 h-4 bg-green-600 rounded-full flex items-center justify-center">
                                        <span class="text-white text-xs">✓</span>
                                    </div>
                                @else
                                    <div class="w-4 h-4 border-2 border-gray-300 rounded-full"></div>
                                @endif
                            </div>
                            
                            <div class="flex-1">
                                <h3 class="font-medium {{ $isCompleted ? 'line-through text-gray-500' : '' }}">
                                    {{ $title }}
                                </h3>
                                
                                @if($notes)
                                    <p class="text-sm text-gray-600 mt-1">{{ Str::limit($notes, 100) }}</p>
                                @endif
                                
                                @if($dueDate)
                                    <p class="text-xs text-gray-500 mt-2">Due: {{ $dueDate }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            @empty
                <div class="text-center py-12">
                    <p class="text-gray-500">No tasks found</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($nextPageToken ?? false || $prevPageToken ?? false)
            <div class="flex justify-between items-center mt-8">
                @if($prevPageToken ?? false)
                    <a href="{{ route('tasks', ['pageToken' => $prevPageToken]) }}" 
                       class="px-4 py-2 border border-gray-300 text-sm rounded hover:bg-gray-50">
                        Previous
                    </a>
                @else
                    <div></div>
                @endif

                @if($nextPageToken ?? false)
                    <a href="{{ route('tasks', ['pageToken' => $nextPageToken]) }}" 
                       class="px-4 py-2 border border-gray-300 text-sm rounded hover:bg-gray-50">
                        Next
                    </a>
                @endif
            </div>
        @endif
    </div>
</x-app-layout>