@props(['title', 'count' => 0])

<div class="border border-gray-200 rounded">
    <div class="p-4 border-b border-gray-200">
        <h2 class="font-medium">{{ $title }}</h2>
    </div>
    <div class="p-4">
        @if($count > 0)
            {{ $slot }}
        @else
            <p class="text-gray-500 text-center py-4">No {{ strtolower($title) }} found</p>
        @endif
    </div>
</div>