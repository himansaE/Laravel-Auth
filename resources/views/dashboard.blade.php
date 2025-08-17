<x-app-layout title="Dashboard">
    <div>
        <!-- Welcome Section -->
        <div class="mb-8">
            <h1 class="text-2xl font-semibold mb-2">Welcome, {{ explode(' ', Auth::user()->name)[0] }}!</h1>
            <p class="text-gray-600">{{ Auth::user()->email }}</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <x-stat-card 
                title="Calendar Events" 
                :count="$calendarEventsCount ?? 0" 
                :route="route('calendar')" />

            <x-stat-card 
                title="Tasks" 
                :count="$tasksCount ?? 0" 
                :route="route('tasks')" />

            <x-stat-card 
                title="Emails" 
                :count="$emailsCount ?? 0"
                :route="route('emails')" />
        </div>

        <!-- Refresh Button -->
        <div class="text-center">
            <button onclick="window.location.reload()" 
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded">
                Refresh Data
            </button>
        </div>
    </div>
</x-app-layout>