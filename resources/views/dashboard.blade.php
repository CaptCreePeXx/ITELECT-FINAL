<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Dental Clinic Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
    <script src="//unpkg.com/alpinejs" defer></script>
</head>
<body class="min-h-screen flex flex-col bg-gradient-to-br from-gray-900 via-black to-gray-800 text-yellow-400">

    <!-- Navbar -->
    <nav class="w-full flex justify-between items-center px-6 py-4 bg-black bg-opacity-50 shadow-md fixed top-0 left-0 z-50">
        <div class="text-2xl font-bold text-yellow-400">
            Dental Clinic Appointment System
        </div>

        <div class="flex space-x-4 items-center">
            <a href="{{ route('profile.edit') }}" 
               class="text-gray-300 hover:text-yellow-400 transition duration-300 font-medium">
                {{ Auth::user()->name }}
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf   
                <button type="submit" 
                        class="px-4 py-2 rounded-lg font-semibold text-black bg-yellow-400 hover:bg-yellow-500 transition duration-300 shadow-md">
                    Logout
                </button>
            </form>
        </div>
    </nav>

        @if (session('success'))
    <div 
        x-data="{ show: false }"
        x-init="setTimeout(() => show = true, 100); setTimeout(() => show = false, 2800)" 
        x-show="show"
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 -translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-4"
        class="fixed top-6 left-1/2 -translate-x-1/2 bg-green-500/20 text-green-600 border border-green-500/30 backdrop-blur-sm px-4 py-3 rounded-lg shadow-md text-sm font-medium z-50 mt-20"
    >
        {{ session('success') }}
    </div>
@endif

    <!-- Main Content -->
    <main class="flex-grow flex flex-col items-center justify-center pt-32 pb-20 text-center">
        <h1 class="text-5xl font-extrabold mb-4 bg-clip-text text-transparent bg-gradient-to-r from-yellow-400 to-yellow-600">
            Welcome, {{ Auth::user()->name }}!
        </h1>

        <p class="text-lg text-gray-300 mb-8">You are logged in to your dashboard.</p>

        <div class="grid grid-cols-1 place-items-center gap-6 w-full max-w-md">

            @if(Auth::user()->role === 'patient')
                <a href="{{ route('appointments.index') }}" class="group w-full">
                    <div class="bg-gray-700 bg-opacity-70 p-6 rounded-xl shadow-lg transition transform hover:scale-105 hover:shadow-yellow-300/50">
                        <h2 class="text-xl font-bold mb-2 text-yellow-400 group-hover:text-yellow-300 transition">
                            Appointments
                        </h2>
                        <p class="text-gray-300 text-sm group-hover:text-yellow-200 transition">
                            View and manage your appointments.
                        </p>
                    </div>
                </a>

            @elseif(Auth::user()->role === 'receptionist')
                <a href="{{ route('appointments.manage') }}" class="group w-full">
                    <div class="bg-gray-700 bg-opacity-70 p-6 rounded-xl shadow-lg transition transform hover:scale-105 hover:shadow-yellow-300/50">
                        <h2 class="text-xl font-bold mb-2 text-yellow-400 group-hover:text-yellow-300 transition">
                            Manage Appointments
                        </h2>
                        <p class="text-gray-300 text-sm group-hover:text-yellow-200 transition">
                            View and handle pending appointment requests.
                        </p>
                    </div>
                </a>

            @if(Auth::user()->role === 'dentist')
                <a href="{{ route('dentist.schedule') }}" class="group w-full">
                    <div class="bg-gray-700 bg-opacity-70 p-6 rounded-xl shadow-lg transition transform hover:scale-105 hover:shadow-yellow-300/50">
                        <h2 class="text-xl font-bold mb-2 text-yellow-400 group-hover:text-yellow-300 transition">
                            My Schedule
                        </h2>
                        <p class="text-gray-300 text-sm group-hover:text-yellow-200 transition">
                            View your daily appointments and schedule.
                        </p>
                    </div>
                </a>
            @endif

            @elseif(Auth::user()->role === 'admin')
                <a href="{{ route('admin.appointments') }}" class="group w-full">
                    <div class="bg-gray-700 bg-opacity-70 p-6 rounded-xl shadow-lg transition transform hover:scale-105 hover:shadow-yellow-300/50">
                        <h2 class="text-xl font-bold mb-2 text-yellow-400 group-hover:text-yellow-300 transition">
                            All Appointments
                        </h2>
                        <p class="text-gray-300 text-sm group-hover:text-yellow-200 transition">
                            View all patient appointments.
                        </p>
                    </div>
                </a>

                <!--<a href="{{ route('appointments.manage') }}" class="group w-full">
                    <div class="bg-gray-700 bg-opacity-70 p-6 rounded-xl shadow-lg transition transform hover:scale-105 hover:shadow-yellow-300/50">
                        <h2 class="text-xl font-bold mb-2 text-yellow-400 group-hover:text-yellow-300 transition">
                            Receptionist Panel
                        </h2>
                        <p class="text-gray-300 text-sm group-hover:text-yellow-200 transition">
                            View and manage receptionist-controlled appointments.
                        </p>
                    </div>
                </a>-->

                <a href="{{ route('admin.users') }}" class="group w-full">
                    <div class="bg-gray-700 bg-opacity-70 p-6 rounded-xl shadow-lg transition transform hover:scale-105 hover:shadow-yellow-300/50">
                        <h2 class="text-xl font-bold mb-2 text-yellow-400 group-hover:text-yellow-300 transition">
                            User Management
                        </h2>
                        <p class="text-gray-300 text-sm group-hover:text-yellow-200 transition">
                            Add, edit, or remove users including receptionists.
                        </p>
                    </div>
                </a>
            @endif

        </div>
    </main>

    <!-- Footer (fixed at bottom like homepage) -->
    @include('partials.footer')

</body>

</html>
