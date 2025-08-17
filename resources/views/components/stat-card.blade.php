@props(['title', 'count', 'route' => null])

<div class="border border-gray-200 rounded p-4">
    <div class="flex justify-between items-center">
        <div>
            <p class="text-sm text-gray-600">{{ $title }}</p>
            <p class="text-xl font-semibold">{{ $count }}</p>
        </div>
        
        @if($route && $count > 0)
            <a href="{{ $route }}" class="text-blue-600 hover:underline text-sm">
                View
            </a>
        @endif
    </div>
</div>