<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Dental Clinic Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-gray-900 via-black to-gray-800 text-yellow-400">

    <!-- Navbar -->
    <nav class="w-full flex justify-between items-center px-6 py-4 bg-black bg-opacity-50 shadow-md">
        
    <div class="text-2xl font-bold text-yellow-400">
        Dental Clinic Appointment System
        
    </div>
    
    <div class="flex space-x-4 items-center">
        <span class="text-gray-300">{{ Auth::user()->name }}</span>
        <form method="POST" action="{{ route('logout') }}">
            @csrf   
            <button type="submit" class="px-4 py-2 rounded-lg font-semibold text-black bg-yellow-400 hover:bg-yellow-500 transition duration-300 shadow-md">
                Logout
            </button>
        </form>
    </div>
</nav>


    <!-- Main Content -->
    <div class="flex flex-col items-center justify-center min-h-screen text-center pt-24">
    <h1 class="text-5xl font-extrabold mb-4 bg-clip-text text-transparent bg-gradient-to-r from-yellow-400 to-yellow-600">
        Welcome, {{ Auth::user()->name }}!
    </h1>
    <p class="text-lg text-gray-300 mb-8">You are logged in to your dashboard.</p>

    <div class="grid grid-cols-1 place-items-center gap-6 w-full">


    <!-- Appointments Card (Clickable) -->
@if(Auth::user()->role === 'patient')
    <a href="{{ route('appointments.index') }}" class="group">
        <div class="bg-gray-700 bg-opacity-70 p-6 rounded-xl shadow-lg transition transform hover:scale-105 hover:shadow-yellow-300/50">
            <h2 class="text-xl font-bold mb-2 text-yellow-400 group-hover:text-yellow-300 transition">Appointments</h2>
            <p class="text-gray-300 text-sm group-hover:text-yellow-200 transition">View and manage your appointments.</p>
        </div>
    </a>
@elseif(Auth::user()->role === 'receptionist')
    <a href="{{ route('appointments.manage') }}" class="group">
        <div class="bg-gray-700 bg-opacity-70 p-6 rounded-xl shadow-lg transition transform hover:scale-105 hover:shadow-yellow-300/50">
            <h2 class="text-xl font-bold mb-2 text-yellow-400 group-hover:text-yellow-300 transition">Manage Appointments</h2>
            <p class="text-gray-300 text-sm group-hover:text-yellow-200 transition">View and handle pending appointment requests.</p>
        </div>
    </a>
@endif





</div>

</div>

@include('partials.footer')


</body>
</html>
