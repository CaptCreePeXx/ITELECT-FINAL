<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Dental Clinic Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-gray-900 via-black to-gray-800 flex flex-col items-center justify-center text-yellow-400">

    <!-- Navbar -->
    <nav class="w-full flex justify-between items-center px-6 py-4 bg-black bg-opacity-50 shadow-md fixed top-0 left-0 z-50">
        <div class="text-2xl font-bold text-yellow-400">
            Dental Clinic Appointment System
        </div>
        <div class="flex space-x-4">
            <a href="{{ route('register') }}" class="px-4 py-2 rounded-lg font-semibold text-black bg-yellow-500 hover:bg-yellow-600 transition duration-300 shadow-md">Register</a>
        </div>
    </nav>

    <div class="w-full max-w-md mt-24 bg-gray-900 bg-opacity-70 rounded-2xl p-8 shadow-xl">
        <h2 class="text-3xl font-bold mb-6 text-center">Login</h2>

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf
            <div>
                <label class="block text-sm font-medium mb-2">Email</label>
                <input type="email" placeholder="Enter your email" name="email" class="w-full p-3 rounded-lg bg-gray-800 text-white border border-gray-700 focus:border-yellow-400 focus:ring focus:ring-yellow-300 outline-none transition" required autofocus>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Password</label>
                <input type="password" placeholder="Enter your password" name="password" class="w-full p-3 rounded-lg bg-gray-800 text-white border border-gray-700 focus:border-yellow-400 focus:ring focus:ring-yellow-300 outline-none transition" required>
            </div>

            <button type="submit" class="w-full py-3 rounded-lg font-semibold text-black bg-yellow-400 hover:bg-yellow-500 transition duration-300 shadow-md">Login</button>

            <p class="text-center text-gray-400 mt-4 text-sm">
                Don’t have an account?
                <a href="{{ route('register') }}" class="text-yellow-400 hover:underline">Register</a>
            </p>
        </form>
    </div>

     <!-- Footer -->
     <footer class="text-center mt-12 text-sm text-gray-400 mb-6">
        &copy; {{ date('Y') }} Dental Clinic. All rights reserved.
    </footer>

</body>
</html>
