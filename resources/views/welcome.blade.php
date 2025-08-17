<x-app-layout title="Connect Your Google Services">
    <div>
        @guest
            <!-- Login Section -->
            <div class="text-center">
                <h1 class="text-2xl font-semibold mb-8">
                    Connect Your Google Services
                </h1>

                <!-- Login Button -->
                <a href="{{ route('auth.google.redirect') }}" 
                   class="inline-block px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded">
                    Continue with Google
                </a>
            </div>
        @else
            <!-- Authenticated User Content -->
            <div class="text-center">
                <h2 class="text-2xl font-semibold mb-4">Welcome back, {{ Auth::user()->name }}!</h2>
                <a href="{{ route('dashboard') }}" 
                   class="inline-block px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded">
                    Go to Dashboard
                </a>
            </div>
        @endguest

        <!-- Error Messages -->
        @if(session('error'))
            <div class="mt-8 p-4 border border-red-200 rounded text-red-700 bg-red-50">
                {{ session('error') }}
            </div>
        @endif
    </div>
</x-app-layout>