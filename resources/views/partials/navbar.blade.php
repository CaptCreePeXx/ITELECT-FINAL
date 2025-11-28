<nav class="w-full flex justify-between items-center px-6 py-4 bg-black bg-opacity-50 shadow-md">
    <a href="{{ route('dashboard') }}"
       class="px-6 py-2 rounded-lg font-semibold text-black bg-yellow-400 hover:bg-yellow-500 transition duration-300 shadow-md ml-48">
        ← Return to Dashboard
    </a>

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
