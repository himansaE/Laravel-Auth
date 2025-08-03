@props(['title', 'icon', 'iconColor' => 'blue', 'count' => 0])

<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900">{{ $title }}</h2>
            <div class="w-8 h-8 bg-{{ $iconColor }}-100 rounded-lg flex items-center justify-center">
                {!! $icon !!}
            </div>
        </div>
    </div>
    <div class="p-6">
        @if($count > 0)
            {{ $slot }}
        @else
            <div class="text-center py-8">
                <div class="w-12 h-12 text-gray-400 mx-auto mb-4">
                    {!! $icon !!}
                </div>
                <p class="text-gray-500">{{ $emptyMessage ?? 'No ' . strtolower($title) . ' found' }}</p>
            </div>
        @endif
    </div>
</div>