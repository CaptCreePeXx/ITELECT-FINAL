<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - My Laravel App</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-gray-900 via-black to-gray-800 text-yellow-400">

    <!-- Navigation Bar -->
    <nav class="w-full flex justify-between items-center px-6 py-4 bg-black bg-opacity-50 shadow-md fixed top-0 left-0 z-50">
        <div class="text-2xl font-bold text-yellow-400">
            Dental Clinic Management System
        </div>
        <div class="flex space-x-4">
            <a href="{{ route('login') }}" class="px-4 py-2 rounded-lg font-semibold text-black bg-yellow-400 hover:bg-yellow-500 transition duration-300 shadow-md">
                Login
            </a>
            <a href="{{ route('register') }}" class="px-4 py-2 rounded-lg font-semibold text-black bg-yellow-500 hover:bg-yellow-600 transition duration-300 shadow-md">
                Register
            </a>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="flex flex-col justify-center items-center text-center min-h-screen pt-24">
        <!-- Header -->
        <h1 class="text-5xl md:text-6xl font-extrabold mb-6 bg-clip-text text-transparent bg-gradient-to-r from-yellow-400 to-yellow-600">
                Welcome to the Dental Clinic
        </h1>

        <!-- Subtitle -->
        <p class="text-lg md:text-xl text-gray-300 mb-12 max-w-xl">
            Book your appointment today!
        </p>

        <!-- Optional call-to-action buttons (can remove if using nav bar) -->
        <!--
        <div class="flex flex-col sm:flex-row gap-4">
            <a href="{{ route('login') }}" class="px-6 py-3 rounded-lg font-semibold text-black bg-yellow-400 hover:bg-yellow-500 transition duration-300 shadow-lg hover:shadow-xl">
                Login
            </a>
            <a href="{{ route('register') }}" class="px-6 py-3 rounded-lg font-semibold text-black bg-yellow-500 hover:bg-yellow-600 transition duration-300 shadow-lg hover:shadow-xl">
                Register
            </a>
        </div>
        -->
    </div>

    @include('partials.footer')

</body>
</html>
    