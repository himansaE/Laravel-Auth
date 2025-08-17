<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name', 'Laravel') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="{{ $bodyClass ?? 'bg-white min-h-screen' }}">
    <!-- Header -->
    <header class="border-b border-gray-200 py-4">
        <div class="max-w-4xl mx-auto px-4 flex justify-between items-center">
            <h1 class="text-lg font-medium">{{ $title ?? config('app.name', 'Laravel') }}</h1>
            
            @auth
                <div class="flex items-center space-x-4">
                    <span class="text-sm">{{ Auth::user()->name }}</span>
                    @if(request()->routeIs('dashboard'))
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-sm text-blue-600 hover:underline">
                                Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('dashboard') }}" class="text-sm text-blue-600 hover:underline">
                            Dashboard
                        </a>
                    @endif
                </div>
            @else
                <div class="text-sm text-gray-600">
                    Connect your Google services
                </div>
            @endauth
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-4 py-8">
        {{ $slot }}
    </main>

</body>
</html>