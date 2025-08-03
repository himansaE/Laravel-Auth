@props(['title', 'count', 'icon', 'iconColor' => 'blue', 'subtitle' => null, 'route' => null])

<div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200 hover:shadow-md transition-all duration-200">
    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-{{ $iconColor }}-100 rounded-lg flex items-center justify-center">
                {!! $icon !!}
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">{{ $title }}</p>
                <p class="text-2xl font-bold text-gray-900">{{ $count }}</p>
                @if($subtitle)
                    <p class="text-xs text-gray-500 mt-1">{{ $subtitle }}</p>
                @endif
            </div>
        </div>
        
        @if($route && $count > 0)
            <div class="ml-4">
                <a href="{{ $route }}" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    View
                </a>
            </div>
        @endif
    </div>
</div>