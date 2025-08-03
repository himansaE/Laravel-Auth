<x-app-layout title="Dashboard">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Welcome Section -->
        <div class="mb-8">
            <div class="flex items-center space-x-4">
                @if(Auth::user()->google_avatar)
                    <img src="{{ Auth::user()->google_avatar }}" alt="{{ Auth::user()->name }}" class="w-16 h-16 rounded-full shadow-lg object-cover">
                @else
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center shadow-lg">
                        <span class="text-2xl font-bold text-white">{{ substr(Auth::user()->name, 0, 1) }}</span>
                    </div>
                @endif
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-1">Welcome back, {{ explode(' ', Auth::user()->name)[0] }}!</h1>
                    <p class="text-gray-600">{{ Auth::user()->email }}</p>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <x-stat-card 
                title="Calendar Events" 
                :count="$calendarEventsCount ?? 0" 
                subtitle="Total events"
                :route="route('calendar')"
                icon-color="red"
                :icon="'<svg class=\'w-6 h-6 text-red-600\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z\'></path></svg>'" />

            <x-stat-card 
                title="Tasks" 
                :count="$tasksCount ?? 0" 
                subtitle="Total tasks"
                :route="route('tasks')"
                icon-color="green"
                :icon="'<svg class=\'w-6 h-6 text-green-600\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4\'></path></svg>'" />

            <x-stat-card 
                title="Emails" 
                :count="$emailsCount ?? 0"
                subtitle="Total emails"
                :route="route('emails')"
                icon-color="blue"
                :icon="'<svg class=\'w-6 h-6 text-blue-600\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z\'></path></svg>'" />
        </div>



        <!-- Refresh Button -->
        <div class="mt-8 text-center">
            <button onclick="window.location.reload()" 
                    class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Refresh Data
            </button>
        </div>
    </div>
</x-app-layout>