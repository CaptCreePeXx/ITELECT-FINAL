<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-yellow-400 leading-tight text-center">
            {{ __('Add New User') }}
        </h2>
    </x-slot>

    <div class="py-16 px-6 bg-gray-900 min-h-screen">
        <div class="max-w-3xl mx-auto bg-gray-800 bg-opacity-90 shadow-lg rounded-2xl p-8 border border-yellow-400/30">
            
            @if ($errors->any())
                <div class="mb-6 text-red-400">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-yellow-400 font-semibold mb-2" for="name">Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}"
                        class="w-full px-4 py-2 rounded bg-gray-900 border border-yellow-400 text-yellow-300 focus:outline-none focus:ring-2 focus:ring-yellow-400">
                </div>

                <div>
                    <label class="block text-yellow-400 font-semibold mb-2" for="email">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                        class="w-full px-4 py-2 rounded bg-gray-900 border border-yellow-400 text-yellow-300 focus:outline-none focus:ring-2 focus:ring-yellow-400">
                </div>

                <div>
                    <label class="block text-yellow-400 font-semibold mb-2" for="role">Role</label>
                    <select name="role" id="role"
                        class="w-full px-4 py-2 rounded bg-gray-900 border border-yellow-400 text-yellow-300 focus:outline-none focus:ring-2 focus:ring-yellow-400">
                        <option value="">Select Role</option>
                        <option value="patient" {{ old('role') == 'patient' ? 'selected' : '' }}>Patient</option>
                        <option value="receptionist" {{ old('role') == 'receptionist' ? 'selected' : '' }}>Receptionist</option>
                        <option value="dentist" {{ old('role') == 'dentist' ? 'selected' : '' }}>Dentist</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>

                <p class="text-gray-300 italic text-sm ml-4">
                    Default password: <br>
                    <span class="not-italic">password</span>
                </p>

                <div class="flex justify-between items-center">
                    <a href="{{ route('admin.users') }}"
                        class="px-4 py-2 bg-gray-700 text-yellow-400 rounded hover:bg-gray-600 transition">Cancel</a>
                    <button type="submit"
                        class="px-6 py-2 bg-yellow-400 text-black rounded hover:bg-yellow-500 transition">Add User</button>
                </div>
            </form>
        </div>
    </div>

    @include('partials.footer')
</x-app-layout>
