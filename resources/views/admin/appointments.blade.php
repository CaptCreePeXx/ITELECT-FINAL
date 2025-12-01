<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-3xl text-yellow-400 leading-tight text-center">
            {{ __('All Appointments') }}
        </h2>
    </x-slot>

    <div class="flex flex-col min-h-screen bg-gray-900 text-yellow-400">
        <div class="flex-1 max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col space-y-6">

            <!-- Summary Cards -->
            <div class="grid grid-cols-4 gap-4 mb-6">
                <div class="border-2 border-yellow-300/20 bg-gray-800 p-4 rounded-xl text-yellow-400 text-center shadow">
                    <h3 class="font-semibold">Total Appointments</h3>
                    <p class="text-2xl text-white">{{ $summary['total'] }}</p>
                </div>
                <div class="border-2 border-yellow-300/20 bg-gray-800 p-4 rounded-xl text-yellow-400 text-center shadow">
                    <h3 class="font-semibold">Pending</h3>
                    <p class="text-2xl text-white">{{ $summary['pending'] }}</p>
                </div>
                <div class="border-2 border-yellow-300/20 bg-gray-800 p-4 rounded-xl text-green-400 text-center shadow">
                    <h3 class="font-semibold">Accepted</h3>
                    <p class="text-2xl text-white">{{ $summary['accepted'] }}</p>
                </div>
                <div class="border-2 border-yellow-300/20 bg-gray-800 p-4 rounded-xl text-red-400 text-center shadow">
                    <h3 class="font-semibold">Declined</h3>
                    <p class="text-2xl text-white">{{ $summary['declined'] }}</p>
                </div>
            </div>

            <!-- Search / Filter / Sort -->
            <form method="GET" class="flex flex-wrap gap-3 mb-6 items-center">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Search by name or email"
                    class="w-96 px-4 py-2 rounded-3xl bg-gray-800 text-yellow-300 border border-yellow-300/40 focus:outline-none focus:border-yellow-400">

                <div class="flex gap-3 ml-auto">
                    <select name="status" class="px-10 py-2 rounded-3xl bg-gray-800 text-yellow-300 border border-gray-600 focus:outline-none focus:border-yellow-400">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Accepted</option>
                        <option value="declined" {{ request('status') == 'declined' ? 'selected' : '' }}>Declined</option>
                    </select>

                    <select name="sort_field" class="px-10 py-2 rounded-3xl bg-gray-800 text-yellow-300 border border-gray-600 focus:outline-none focus:border-yellow-400">
                        <option value="date" {{ request('sort_field') == 'date' ? 'selected' : '' }}>Date</option>
                        <option value="time" {{ request('sort_field') == 'time' ? 'selected' : '' }}>Time</option>
                        <option value="status" {{ request('sort_field') == 'status' ? 'selected' : '' }}>Status</option>
                    </select>

                    <select name="sort_direction" class="px-10 py-2 rounded-3xl bg-gray-800 text-yellow-300 border border-gray-600 focus:outline-none focus:border-yellow-400">
                        <option value="asc" {{ request('sort_direction') == 'asc' ? 'selected' : '' }}>Ascending</option>
                        <option value="desc" {{ request('sort_direction') == 'desc' ? 'selected' : '' }}>Descending</option>
                    </select>

                    <button type="submit" class="hover:bg-yellow-400 hover:text-black border-2 border-yellow-300/60 text-white px-10 py-2 bg-transparent rounded-3xl transition">
                        Search
                    </button>
                </div>
            </form>

            <!-- Appointments Table -->
            <div class="bg-gray-800 bg-opacity-90 shadow-lg rounded-3xl p-6 border border-yellow-400/30">
                @if($appointments->count() > 0)
                    <div class="overflow-x-auto rounded-xl shadow-lg">
                        <table class="min-w-max mx-auto text-yellow-300">
                            <thead class="bg-gray-900 text-yellow-400 uppercase text-sm tracking-wider">
                                <tr>
                                    <th class="py-3 px-6 text-left">Patient ID</th>
                                    <th class="py-3 px-6 text-left">Name</th>
                                    <th class="py-3 px-6 text-left">Email</th>
                                    <th class="py-3 px-6 text-left">Date</th>
                                    <th class="py-3 px-6 text-left">Time</th>
                                    <th class="py-3 px-6 text-left">Status</th>
                                    <th class="py-3 px-6 text-left">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-600">
                                @foreach($appointments as $appointment)
                                    <tr class="hover:bg-gray-700/60 transition">
                                        <td class="py-3 px-6">{{ $appointment->patient->id ?? 'N/A' }}</td>
                                        <td class="py-3 px-6">{{ $appointment->patient->name ?? 'N/A' }}</td>
                                        <td class="py-3 px-6">{{ $appointment->patient->email ?? 'N/A' }}</td>
                                        <td class="py-3 px-6">{{ \Carbon\Carbon::parse($appointment->date)->format('M d, Y') }}</td>
                                        <td class="py-3 px-6">{{ \Carbon\Carbon::parse($appointment->time)->format('h:i A') }}</td>
                                        <td class="py-3 px-6">
                                            <span @class([
                                                'px-2 py-1 rounded-md font-semibold text-sm',
                                                'bg-yellow-400 text-black' => strtolower($appointment->status) === 'pending',
                                                'bg-green-400 text-black'  => strtolower($appointment->status) === 'accepted',
                                                'bg-red-400 text-black'    => strtolower($appointment->status) === 'declined',
                                                'bg-gray-400 text-black'   => !in_array(strtolower($appointment->status), ['pending','accepted','declined']),
                                            ])>
                                                {{ ucfirst($appointment->status) }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-6 flex gap-2">
                                            <button 
                                                onclick="openModal({{ $appointment->id }})"
                                                class="px-3 py-1 bg-transparent border-2 border-yellow-300/60 text-white rounded-xl hover:text-black hover:bg-yellow-400 transition">
                                                View Details
                                            </button>
                                            <button 
                                                onclick="openDeleteModal({{ $appointment->id }})"
                                                class="px-3 py-1 bg-red-700 text-white rounded hover:bg-red-500 transition">
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 text-center">
                        {{ $appointments->links() }}
                    </div>
                @else
                    <p class="text-yellow-400 mt-6 text-center text-lg">No appointments found.</p>
                @endif
            </div>
        </div>

        <footer class="w-full py-4 text-center text-sm text-gray-400 border-t border-yellow-400/30">
            &copy; {{ date('Y') }} Dental Clinic. All rights reserved.
        </footer>
    </div>

        <!-- View Details Modal -->
    <div id="detailsModal" class="hidden fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50">
        <div class="bg-gray-900 rounded-xl p-6 w-96 max-w-full relative">
            <!-- Close Button -->
            <button onclick="closeModal()" 
                    class="absolute -top-3 -right-3 text-yellow-400 font-bold text-3xl 
                        bg-gray-800 rounded-full w-10 h-10 flex items-center justify-center 
                        hover:bg-yellow-400 hover:text-black transition">
                &times;
            </button>

            <!-- Modal Content -->
            <div id="modalContent" class="space-y-4 text-white pt-4">
                <!-- JS injects details here -->
            </div>
        </div>
    </div>
    
        <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50">
        <div class="bg-gray-900 rounded-xl p-6 w-96 max-w-full relative">
            <!-- Close Button -->
            <button onclick="closeDeleteModal()" 
                    class="absolute -top-3 -right-3 text-yellow-400 font-bold text-3xl 
                        bg-gray-800 rounded-full w-10 h-10 flex items-center justify-center 
                        hover:bg-yellow-400 hover:text-black transition">
                &times;
            </button>

            <!-- Modal Content -->
            <p class="text-yellow-300 text-lg mb-4 pt-4">
                Are you sure you want to delete this appointment?
            </p>

            <form id="deleteFormModal" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex justify-end gap-3">
                    <button type="button" 
                            onclick="closeDeleteModal()" 
                            class="px-4 py-2 rounded bg-gray-700 text-yellow-400 hover:bg-gray-600">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 rounded bg-red-700 text-white hover:bg-red-500">
                        Delete
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let currentAppointmentId = null;

        function openModal(id) {
            fetch(`/admin/appointments/${id}`)
                .then(res => res.json())
                .then(data => {
                    document.getElementById('modalContent').innerHTML = `
                        <div class="space-y-3">
                            <div class="p-3 bg-gray-700 rounded-md">
                                <p class="font-semibold text-yellow-300">Patient Info</p>
                                <p><strong>Name:</strong> ${data.patient}</p>
                                <p><strong>Email:</strong> ${data.email}</p>
                            </div>
                            <div class="p-3 bg-gray-700 rounded-md">
                                <p class="font-semibold text-yellow-300">Appointment Details</p>
                                <p><strong>Date:</strong> ${data.date}</p>
                                <p><strong>Time:</strong> ${data.time}</p>
                                <p><strong>Service:</strong> ${data.service}</p>
                                <p><strong>Status:</strong> ${data.status}</p>
                                <p><strong>Dentist:</strong> ${data.dentist ?? 'N/A'}</p>
                            </div>
                            <div class="p-3 bg-gray-700 rounded-md">
                                <p class="font-semibold text-yellow-300">Additional Notes</p>
                                <p>${data.note ?? 'No notes available'}</p>
                            </div>
                            <p class="text-xs text-gray-400 mt-2 text-right">Created at: ${data.created_at}</p>
                        </div>
                    `;
                    const modal = document.getElementById('detailsModal');
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                });
        }

        function closeModal() {
            const modal = document.getElementById('detailsModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function openDeleteModal(id) {
            currentAppointmentId = id;
            const form = document.getElementById('deleteFormModal');
            form.action = `/admin/appointments/${id}`;
            const modal = document.getElementById('deleteModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>
</x-app-layout>
