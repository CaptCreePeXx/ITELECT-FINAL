<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Dental Clinic Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="flex flex-col min-h-screen bg-gradient-to-br from-gray-900 via-black to-gray-800 text-yellow-400">

    <!-- NAVBAR -->
    <nav class="w-full flex justify-between items-center px-6 py-4 bg-black bg-opacity-50 shadow-md fixed top-0 left-0 z-50">
        <div class="text-2xl font-bold text-yellow-400">
            Dental Clinic Appointment System
        </div>
        <div class="flex space-x-4">
            <a href="{{ route('login') }}" 
                class="px-4 py-2 rounded-lg font-semibold text-black bg-yellow-400 hover:bg-yellow-500 transition duration-300 shadow-md">
                Login
            </a>
        </div>
    </nav>

    <!-- MAIN CONTENT (Centered like homepage + login) -->
    <div class="flex-grow flex flex-col items-center justify-center pt-24 px-4">

        <div class="w-full max-w-md bg-gray-900 bg-opacity-70 rounded-2xl p-8 shadow-xl">
            <h2 class="text-3xl font-bold mb-6 text-center">Register</h2>

            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf

                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium mb-2">Name</label>
                    <input type="text" name="name" placeholder="Enter your name"
                        class="w-full p-3 rounded-lg bg-gray-800 text-white border border-gray-700 
                        focus:border-yellow-400 focus:ring focus:ring-yellow-300 outline-none transition"
                        required autofocus>
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium mb-2">Email</label>
                    <input type="email" name="email" placeholder="Enter your email"
                        class="w-full p-3 rounded-lg bg-gray-800 text-white border border-gray-700 
                        focus:border-yellow-400 focus:ring focus:ring-yellow-300 outline-none transition"
                        required>
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-sm font-medium mb-2">Password</label>
                    <input type="password" name="password" placeholder="Create password"
                        class="w-full p-3 rounded-lg bg-gray-800 text-white border border-gray-700 
                        focus:border-yellow-400 focus:ring focus:ring-yellow-300 outline-none transition"
                        required>
                </div>

                <!-- Confirm Password -->
                <div>
                    <label class="block text-sm font-medium mb-2">Confirm Password</label>
                    <input type="password" name="password_confirmation" placeholder="Confirm password"
                        class="w-full p-3 rounded-lg bg-gray-800 text-white border border-gray-700 
                        focus:border-yellow-400 focus:ring focus:ring-yellow-300 outline-none transition"
                        required>
                </div>

                <!-- Button -->
                <button type="submit"
                    class="w-full py-3 rounded-lg font-semibold text-black bg-yellow-400 
                    hover:bg-yellow-500 transition duration-300 shadow-md">
                    Register
                </button>

                <p class="text-center text-gray-400 mt-4 text-sm">
                    Already have an account?
                    <a href="{{ route('login') }}" class="text-yellow-400 hover:underline">
                        Login
                    </a>
                </p>

            </form>

        </div>

        <a href="{{ route('home') }}"
            class="mt-4 inline-flex items-center px-4 py-2 bg-transparent text-white font-semibold rounded-lg shadow hover:text-yellow-300 transition">
            ← Return to Dashboard
        </a>

    </div>

    <!-- FOOTER (matches homepage + login) -->
    @include('partials.footer')

</body>
</html>
