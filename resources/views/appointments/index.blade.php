<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-yellow-400 leading-tight text-center">
            🦷 My Appointments
        </h2>
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </x-slot>

    <div class="py-12 bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Manage Appointments --}}
            <div class="bg-gray-800 bg-opacity-90 shadow-lg rounded-2xl p-6 border border-yellow-400/30">

                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-yellow-400">Manage Appointments</h1>
                    <a href="{{ route('appointments.create') }}"
                       class="bg-yellow-400 text-black px-4 py-2 rounded-lg font-semibold hover:bg-yellow-200 transition">
                        Book Appointment
                    </a>
                </div>

                @php
                    // Filter out cancelled appointments
                    $activeAppointments = $appointments->where('is_cancelled', false);
                @endphp

                @if($activeAppointments->count() > 0)
                    <div class="overflow-x-auto rounded-xl shadow-lg">
                        <table class="min-w-full text-yellow-300">
                            <thead class="bg-gray-900 text-yellow-400 uppercase text-sm tracking-wider">
                                <tr>
                                    <th class="py-3 px-6 text-left">Date</th>
                                    <th class="py-3 px-6 text-left">Time</th>
                                    <th class="py-3 px-6 text-left">Service</th>
                                    <th class="py-3 px-6 text-left">Status</th>
                                    <th class="py-3 px-6 text-left">Dentist</th>
                                    <th class="py-3 px-6 text-center">Note</th>
                                    <th class="py-3 px-6 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-600">
                                @foreach($activeAppointments as $appointment)
                                    <tr class="hover:bg-gray-700/60 transition">
                                        <td class="py-3 px-6">{{ $appointment->date }}</td>
                                        <td class="py-3 px-6">{{ \Carbon\Carbon::parse($appointment->time)->format('h:i A') }}</td>
                                        <td class="py-3 px-6">{{ $appointment->service }}</td>
                                        <td class="py-3 px-6">
                                            <span @class([
                                                'px-2 py-1 rounded-md font-semibold text-sm',
                                                'bg-yellow-400 text-black' => $appointment->status === 'Pending',
                                                'bg-green-400 text-black' => $appointment->status === 'Accepted',
                                                'bg-red-400 text-black'   => $appointment->status === 'Declined',
                                                'bg-gray-400 text-black'  => !in_array($appointment->status, ['Pending','Accepted','Declined']),
                                            ])>
                                                {{ ucfirst($appointment->status) }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-6">{{ $appointment->dentist->name ?? 'N/A' }}</td>
                                        <td class="py-3 px-6 text-center">
                                            @if($appointment->note)
                                                <button type="button"
                                                        onclick="openNoteModal('{{ $appointment->note }}')"
                                                        class="px-2 py-1 rounded bg-transparent border border-yellow-300 text-white hover:bg-yellow-300/80 hover:text-black text-sm active:bg-yellow-400/20 active:text-white">
                                                    View
                                                </button>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="py-2 px-4 text-center">
    @if($appointment->status === 'Pending')
        <a href="{{ route('appointments.edit', $appointment->id) }}"
           class="bg-blue-500 px-2 py-1 rounded text-black hover:bg-blue-400 mr-2">
            Edit
        </a>
        <button type="button"
                onclick="openDeleteModal({{ $appointment->id }})"
                class="bg-red-500 px-2 py-1 rounded text-black hover:bg-red-400">
            Delete
        </button>
    @elseif($appointment->status === 'Accepted')
        @if($appointment->cancel_requested)
            <span class="text-gray-400 font-medium text-sm">Request submitted</span>
        @else
            <button type="button"
                    onclick="openCancelModal({{ $appointment->id }})"
                    class="bg-red-400 px-2 py-1 rounded text-black hover:bg-red-500 active:text-gray-200">
                Cancel
            </button>
        @endif
    @else
        <span class="text-gray-400 font-medium text-sm">-</span>
    @endif
</td>



                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-yellow-400 mt-6 text-center text-lg">
                        No appointments booked yet.
                    </p>
                @endif
            </div>

            {{-- Optional: Add Cancelled Appointments Table --}}
            @php
                $cancelledAppointments = $appointments->where('is_cancelled', true);
            @endphp
            @if($cancelledAppointments->count() > 0)
                <div class="bg-gray-800 bg-opacity-90 shadow-lg rounded-2xl p-6 border border-yellow-400/30 mt-10">
                    <h2 class="text-xl font-bold text-yellow-400 mb-4">Cancelled Appointments</h2>
                    <div class="overflow-x-auto rounded-xl shadow-lg">
                        <table class="min-w-full text-yellow-300">
                            <thead class="bg-gray-900 text-yellow-400 uppercase text-sm tracking-wider">
                                <tr>
                                    <th class="py-3 px-6 text-left">Date</th>
                                    <th class="py-3 px-6 text-left">Time</th>
                                    <th class="py-3 px-6 text-left">Service</th>
                                    <th class="py-3 px-6 text-left">Status</th>
                                    <th class="py-3 px-6 text-left">Dentist</th>
                                    <th class="py-3 px-6 text-left">Reason</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-600">
                                @foreach($cancelledAppointments as $appointment)
                                    <tr class="hover:bg-gray-700/60 transition">
                                        <td class="py-3 px-6">{{ $appointment->date }}</td>
                                        <td class="py-3 px-6">{{ \Carbon\Carbon::parse($appointment->time)->format('h:i A') }}</td>
                                        <td class="py-3 px-6">{{ $appointment->service }}</td>
                                        <td class="py-3 px-6 text-red-400 font-semibold">Cancelled</td>
                                        <td class="py-3 px-6">{{ $appointment->dentist->name ?? 'N/A' }}</td>
                                        <td class="py-3 px-6">{{ $appointment->cancel_reason ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

        </div>
    </div>

    <!-- Delete Confirmation Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50">
    <div class="bg-gray-900 rounded-xl p-6 w-96 max-w-full relative">
        <button onclick="closeDeleteModal()" class="absolute -top-3 -right-3 text-yellow-400 font-bold text-3xl 
                       bg-gray-800 rounded-full w-10 h-10 flex items-center justify-center 
                       hover:bg-yellow-400 hover:text-black transition">&times;</button>
        <h3 class="text-yellow-300 font-semibold text-lg mb-4">Confirm Deletion</h3>
        <p class="text-yellow-400 mb-4">Are you sure you want to delete this appointment? This action cannot be undone.</p>
        <div class="flex justify-end space-x-3">
            <button onclick="closeDeleteModal()" class="px-4 py-2 rounded bg-gray-700 text-yellow-400 hover:bg-gray-600">Cancel</button>
            <form id="deleteForm" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 rounded bg-red-500 text-black hover:bg-red-400">Delete</button>
            </form>
        </div>
    </div>
</div>


    {{-- Note Modal --}}
    <div id="noteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50">
        <div class="bg-gray-900 rounded-xl p-6 w-96 max-w-full relative">
            <button onclick="closeNoteModal()" class="absolute -top-3 -right-3 text-yellow-400 font-bold text-3xl 
                           bg-gray-800 rounded-full w-10 h-10 flex items-center justify-center 
                           hover:bg-yellow-400 hover:text-black transition">&times;</button>
            <h3 class="text-yellow-300 font-semibold text-lg mb-4">Appointment Note</h3>
            <textarea id="noteContent" rows="6" class="w-full px-3 py-2 rounded bg-gray-700 text-white resize-none" readonly></textarea>
        </div>
    </div>

    {{-- Cancel Request Modal --}}
<div id="cancelModal" class="hidden fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50">
    <div class="bg-gray-900 rounded-xl p-6 w-96 max-w-full relative">
        <!-- Updated exit button copied from Delete modal -->
        <button onclick="closeCancelModal()" class="absolute -top-3 -right-3 text-yellow-400 font-bold text-3xl 
                       bg-gray-800 rounded-full w-10 h-10 flex items-center justify-center 
                       hover:bg-yellow-400 hover:text-black transition">&times;</button>

        <h3 class="text-yellow-300 font-semibold text-lg mb-4">Reason for Cancellation</h3>

        <form id="cancelForm" method="POST" onsubmit="submitCancelRequest(event)">
            @csrf
            <textarea name="cancel_reason" rows="4" placeholder="Enter reason..." class="w-full px-3 py-2 rounded bg-gray-700 text-white mb-3" required></textarea>
            <button type="submit" class="bg-red-500 px-2 py-1 rounded text-black hover:bg-red-400 w-full">Submit</button>
        </form>
    </div>
</div>


    <script>

        function openDeleteModal(appointmentId) {
        const form = document.getElementById('deleteForm');
        form.action = `/appointments/${appointmentId}`; // update with the correct route
        document.getElementById('deleteModal').classList.remove('hidden');
        document.getElementById('deleteModal').classList.add('flex');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
        document.getElementById('deleteModal').classList.remove('flex');
    }
        function openNoteModal(note) {
            document.getElementById('noteContent').value = note;
            document.getElementById('noteModal').classList.remove('hidden');
            document.getElementById('noteModal').classList.add('flex');
        }
        function closeNoteModal() {
            document.getElementById('noteModal').classList.add('hidden');
            document.getElementById('noteModal').classList.remove('flex');
        }

        function openCancelModal(id) {
            const form = document.getElementById('cancelForm');
            form.action = `/appointments/${id}/request-cancel`;
            document.getElementById('cancelModal').classList.remove('hidden');
            document.getElementById('cancelModal').classList.add('flex');
        }
        function closeCancelModal() {
            document.getElementById('cancelModal').classList.add('hidden');
            document.getElementById('cancelModal').classList.remove('flex');
        }

        function submitCancelRequest(event) {
            event.preventDefault();

            const form = event.target;
            const appointmentId = form.action.split('/').slice(-2, -1)[0];
            const cancelReason = form.cancel_reason.value;

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ cancel_reason: cancelReason })
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    closeCancelModal();
                    const rowButton = document.querySelector(`button[onclick="openCancelModal(${appointmentId})"]`);
                    if(rowButton) rowButton.outerHTML = `<span class="text-gray-400 font-medium text-sm">Request submitted</span>`;
                } else {
                    alert(data.message || 'Failed to submit cancellation request.');
                }
            })
            .catch(err => {
                console.error(err);
                alert('Failed to submit cancellation request.');
            });
        }
    </script>

    @include('partials.footer')
</x-app-layout>
