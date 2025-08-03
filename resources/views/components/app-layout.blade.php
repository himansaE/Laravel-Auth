<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name', 'Laravel') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="{{ $bodyClass ?? 'bg-gray-50 min-h-screen' }}">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="{{ url('/') }}" class="text-xl font-bold text-gray-900">Google Dashboard</a>
                </div>
                
                @auth
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center space-x-2">
                            @if(Auth::user()->google_avatar)
                                <img src="{{ Auth::user()->google_avatar }}" alt="{{ Auth::user()->name }}" class="w-8 h-8 rounded-full object-cover">
                            @else
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <span class="text-sm font-medium text-blue-600">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                </div>
                            @endif
                            <span class="text-sm text-gray-700">{{ Auth::user()->name }}</span>
                        </div>
                        @if(request()->routeIs('dashboard'))
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-sm text-gray-500 hover:text-gray-700 transition-colors">
                                    Logout
                                </button>
                            </form>
                        @else
                            <a href="{{ route('dashboard') }}" 
                               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
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
        </div>
    </header>

    <!-- Main Content -->
    <main>
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="text-center text-gray-600">
                <p>{{ Auth::check() ? 'Connected as ' . Auth::user()->email : 'Login to continue' }}</p>
            </div>
        </div>
    </footer>
</body>
</html>