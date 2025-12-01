<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Dental Clinic Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="flex flex-col min-h-screen bg-gradient-to-br from-gray-900 via-black to-gray-800 text-yellow-400">

    <!-- NAVBAR -->
    <nav class="w-full flex justify-between items-center px-6 py-4 bg-black bg-opacity-50 shadow-md fixed top-0 left-0 z-50">
        <div class="text-2xl font-bold text-yellow-400">
            Dental Clinic Appointment System
        </div>
        <div class="flex space-x-4">
            <a href="{{ route('register') }}" 
                class="px-4 py-2 rounded-lg font-semibold text-black bg-yellow-500 hover:bg-yellow-600 transition duration-300 shadow-md">
                Register
            </a>
        </div>
    </nav>

    <!-- MAIN CONTENT (Centered like homepage) -->
    <div class="flex-grow flex flex-col items-center justify-center pt-24 px-4">

        <div class="w-full max-w-md bg-gray-900 bg-opacity-70 rounded-2xl p-8 shadow-xl">
            <h2 class="text-3xl font-bold mb-6 text-center">Login</h2>

            @if ($errors->has('error'))
                <div class="text-center mb-4 p-3 rounded-lg bg-red-500/20 text-red-400 
                            border border-red-500/30 text-sm font-medium 
                            animate-fadeIn">
                    {{ $errors->first('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
    @csrf

    <!-- ROLE DROPDOWN -->
    <div>
        <label class="block text-sm font-medium mb-2">Login as</label>
        <select name="role"
            class="w-full p-3 rounded-lg bg-gray-800 text-white border border-gray-700 
            focus:border-yellow-400 focus:ring focus:ring-yellow-300 outline-none transition"
            required>
            <option value="patient">Patient</option>
            <option value="receptionist">Receptionist</option>
            <option value="admin">Admin</option>
            <option value="dentist">Dentist</option>
        </select>
    </div>

    <!-- Email -->
    <div>
        <label class="mt-4 block text-sm font-medium mb-2">Email</label>
        <input type="email" name="email" placeholder="Enter your email"
            class="w-full p-3 rounded-lg bg-gray-800 text-white border border-gray-700 
            focus:border-yellow-400 focus:ring focus:ring-yellow-300 outline-none transition"
            required autofocus>
    </div>

    <!-- Password -->
    <div>
        <label class="mt-4 block text-sm font-medium mb-2">Password</label>
        <input type="password" name="password" placeholder="Enter your password"
            class="w-full p-3 rounded-lg bg-gray-800 text-white border border-gray-700 
            focus:border-yellow-400 focus:ring focus:ring-yellow-300 outline-none transition"
            required>
    </div>

    <!-- Login Button -->
    <div>
        <button type="submit"
            class="mt-8 w-full py-3 rounded-lg font-semibold text-black bg-yellow-400 
            hover:bg-yellow-500 transition duration-300 shadow-md">
            Login
        </button>
    </div>

    <p class="text-center text-gray-400 mt-4 text-sm">
        Don’t have an account?
        <a href="{{ route('register') }}" class="text-yellow-400 hover:underline">
            Register
        </a>
    </p>
</form>

        </div>

        <a href="{{ route('home') }}"
            class="mt-4 inline-flex items-center px-4 py-2 bg-transparent text-white font-semibold rounded-lg shadow hover:text-yellow-300 transition">
            ← Return to Dashboard
        </a>
        
    </div>

    <!-- FOOTER (Same as homepage layout) -->
    @include('partials.footer')

</body>
</html>
