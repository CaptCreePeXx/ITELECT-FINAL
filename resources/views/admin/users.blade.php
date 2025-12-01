<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-3xl text-yellow-400 leading-tight text-center">
            {{ __('Users Management') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-900 min-h-screen">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-12">

            <!-- Create User Button -->
            <div class="text-right mb-1">
                <a href="{{ route('admin.users.create') }}"
                   class=" border border-gray-200  px-5 py-2 bg-transparent text-white hover:text-black font-semibold rounded-lg shadow hover:bg-yellow-400/90 hover:border-transparent transition">
                   <!--class="text-white px-3 py-1 rounded hover:bg-yellow-400/90 hover:text-black bg-transparent border border-gray-100"> -->
                    + Create User
                </a>
            </div>

            <!-- Admins Table -->
            <div class="bg-gray-800 bg-opacity-90 shadow-lg rounded-2xl p-6 border border-yellow-400/30">
                <h3 class="text-2xl text-white font-semibold mb-6 text-center">Admins</h3>

                @if($admins->count() > 0)
                    <div class="overflow-x-auto rounded-xl shadow-lg">
                        <table class="min-w-full text-yellow-300">
                            <thead class="bg-gray-900 text-yellow-400 uppercase text-sm tracking-wider">
                                <tr>
                                    <th class="py-3 px-6 text-left">ID</th>
                                    <th class="py-3 px-6 text-left">Name</th>
                                    <th class="py-3 px-6 text-left">Email</th>
                                    <th class="py-3 px-6 text-left">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-600">
                                @foreach($admins as $user)
                                    <tr class="hover:bg-gray-700/60 transition">
                                        <td class="py-3 px-6">{{ $user->id }}</td>
                                        <td class="py-3 px-6">{{ $user->name }}</td>
                                        <td class="py-3 px-6">{{ $user->email }}</td>
                                        <td class="py-3 px-6 flex gap-2">
                                            <a href="{{ route('admin.users.edit', $user->id) }}"
                                               class="text-white px-3 py-1 rounded hover:bg-yellow-400/90 hover:text-black bg-transparent border border-gray-100">
                                               Edit
                                            </a>
                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button class="bg-red-700 text-white px-3 py-1 rounded hover:bg-red-500">
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-yellow-400 mt-4 text-center text-lg">No admins found.</p>
                @endif
            </div>

            <!-- Dentists Table -->
            <div class="bg-gray-800 bg-opacity-90 shadow-lg rounded-2xl p-6 border border-yellow-400/30">
                <h3 class="text-2xl text-white font-semibold mb-6 text-center">Dentists</h3>

                @if($dentists->count() > 0)
                    <div class="overflow-x-auto rounded-xl shadow-lg">
                        <table class="min-w-full text-yellow-300">
                            <thead class="bg-gray-900 text-yellow-400 uppercase text-sm tracking-wider">
                                <tr>
                                    <th class="py-3 px-6 text-left">ID</th>
                                    <th class="py-3 px-6 text-left">Name</th>
                                    <th class="py-3 px-6 text-left">Email</th>
                                    <th class="py-3 px-6 text-left">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-600">
                                @foreach($dentists as $user)
                                    <tr class="hover:bg-gray-700/60 transition">
                                        <td class="py-3 px-6">{{ $user->id }}</td>
                                        <td class="py-3 px-6">{{ $user->name }}</td>
                                        <td class="py-3 px-6">{{ $user->email }}</td>
                                        <td class="py-3 px-6 flex gap-2">
                                            <a href="{{ route('admin.users.edit', $user->id) }}"
                                            class="text-white px-3 py-1 rounded hover:bg-yellow-400/90 hover:text-black bg-transparent border border-gray-100">
                                            Edit
                                            </a>
                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button class="bg-red-700 text-white px-3 py-1 rounded hover:bg-red-500">
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-yellow-400 mt-4 text-center text-lg">No dentists found.</p>
                @endif
            </div>

            <!-- Receptionists Table -->
            <div class="bg-gray-800 bg-opacity-90 shadow-lg rounded-2xl p-6 border border-yellow-400/30">
                <h3 class="text-2xl text-white font-semibold mb-6 text-center">Receptionists</h3>

                @if($receptionists->count() > 0)
                    <div class="overflow-x-auto rounded-xl shadow-lg">
                        <table class="min-w-full text-yellow-300">
                            <thead class="bg-gray-900 text-yellow-400 uppercase text-sm tracking-wider">
                                <tr>
                                    <th class="py-3 px-6 text-left">ID</th>
                                    <th class="py-3 px-6 text-left">Name</th>
                                    <th class="py-3 px-6 text-left">Email</th>
                                    <th class="py-3 px-6 text-left">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-600">
                                @foreach($receptionists as $user)
                                    <tr class="hover:bg-gray-700/60 transition">
                                        <td class="py-3 px-6">{{ $user->id }}</td>
                                        <td class="py-3 px-6">{{ $user->name }}</td>
                                        <td class="py-3 px-6">{{ $user->email }}</td>
                                        <td class="py-3 px-6 flex gap-2">
                                            <a href="{{ route('admin.users.edit', $user->id) }}"
                                               class="text-white px-3 py-1 rounded hover:bg-yellow-400/90 hover:text-black bg-transparent border border-gray-100">
                                               Edit
                                            </a>
                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button class="bg-red-700 text-white px-3 py-1 rounded hover:bg-red-500">
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-yellow-400 mt-4 text-center text-lg">No receptionists found.</p>
                @endif
            </div>

            <!-- Patients Table -->
            <div class="bg-gray-800 bg-opacity-90 shadow-lg rounded-2xl p-6 border border-yellow-400/30">
                <h3 class="text-2xl text-white font-semibold mb-6 text-center">Patients</h3>

                @if($patients->count() > 0)
                    <div class="overflow-x-auto rounded-xl shadow-lg">
                        <table class="min-w-full text-yellow-300">
                            <thead class="bg-gray-900 text-yellow-400 uppercase text-sm tracking-wider">
                                <tr>
                                    <th class="py-3 px-6 text-left">ID</th>
                                    <th class="py-3 px-6 text-left">Name</th>
                                    <th class="py-3 px-6 text-left">Email</th>
                                    <th class="py-3 px-6 text-left">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-600">
                                @foreach($patients as $user)
                                    <tr class="hover:bg-gray-700/60 transition">
                                        <td class="py-3 px-6">{{ $user->id }}</td>
                                        <td class="py-3 px-6">{{ $user->name }}</td>
                                        <td class="py-3 px-6">{{ $user->email }}</td>
                                        <td class="py-3 px-6 flex gap-2">
                                            <a href="{{ route('admin.users.edit', $user->id) }}"
                                               class="text-white px-3 py-1 rounded hover:bg-yellow-400/90 hover:text-black bg-transparent border border-gray-100">
                                               Edit
                                            </a>
                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button class="bg-red-700 text-white px-3 py-1 rounded hover:bg-red-500">
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-yellow-400 mt-4 text-center text-lg">No patients found.</p>
                @endif
            </div>

        </div>
    </div>

    @include('partials.footer')
</x-app-layout>
