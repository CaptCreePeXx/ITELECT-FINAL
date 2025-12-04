<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-yellow-400 leading-tight text-center">
            🦷 My Appointments
        </h2>
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </x-slot>

    <div class="py-12 bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @php
                // --- 1. Filter Appointments into distinct groups ---
                
                // Active Requests awaiting approval (Edit/Delete available)
                $pendingAppointments = $appointments->where('status', 'Pending')->where('is_cancelled', false);

                // Confirmed appointments (Only Cancel Request available)
                $acceptedAppointments = $appointments->where('status', 'Accepted')->where('is_cancelled', false);

                // History components
                $completedAppointments = $appointments->where('status', 'Completed')->where('is_cancelled', false);
                $declinedAppointments = $appointments->where('status', 'Declined')->where('is_cancelled', false);
                $cancelledAppointments = $appointments->where('is_cancelled', true);
                
                // Combine all history items for the main table and count
                $allHistoryAppointments = $cancelledAppointments
                    ->merge($declinedAppointments)
                    ->merge($completedAppointments)
                    ->sortByDesc('date');
            @endphp
            
            
            <div class="bg-gray-800 bg-opacity-90 shadow-lg rounded-2xl p-6 border border-yellow-400/30">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-yellow-400">📝 Pending Appointment Requests</h1>
                    <a href="{{ route('appointments.create') }}"
                       class="bg-yellow-400 text-black px-4 py-2 rounded-lg font-semibold hover:bg-yellow-200 transition">
                        Book New Appointment
                    </a>
                </div>

                @if($pendingAppointments->count() > 0)
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
                                @foreach($pendingAppointments as $appointment)
                                    <tr class="hover:bg-gray-700/60 transition">
                                        <td class="py-3 px-6">{{ $appointment->date }}</td>
                                        <td class="py-3 px-6">{{ \Carbon\Carbon::parse($appointment->time)->format('h:i A') }}</td>
                                        <td class="py-3 px-6">{{ $appointment->service }}</td>
                                        <td class="py-3 px-6">
                                            <span class="px-2 py-1 rounded-md font-semibold text-sm bg-yellow-400 text-black">
                                                {{ ucfirst($appointment->status) }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-6">{{ $appointment->dentist->name ?? 'N/A' }}</td>
                                        <td class="py-3 px-6 text-center">
    @if($appointment->note)
        <button type="button" onclick="openPatientNoteModal('{{ $appointment->note }}')"
            class="px-2 py-1 rounded bg-transparent border border-yellow-300 text-white hover:bg-yellow-300/80 hover:text-black text-sm active:bg-yellow-400/20 active:text-white">
            View
        </button>
    @else
        <span class="text-gray-400">-</span>
    @endif
</td>
                                        <td class="py-2 px-4 text-center">
                                            {{-- Only Edit and Delete are available for Pending --}}
                                            <a href="{{ route('appointments.edit', $appointment->id) }}"
                                               class="bg-blue-500 px-2 py-1 rounded text-black hover:bg-blue-400 mr-2 text-sm">
                                                Edit
                                            </a>
                                            <button type="button" onclick="openDeleteModal({{ $appointment->id }})"
                                                    class="bg-red-500 px-2 py-1 rounded text-black hover:bg-red-400 text-sm">
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-yellow-400 mt-6 text-center text-lg">
                        No pending appointment requests.
                    </p>
                @endif
            </div>

            @if($acceptedAppointments->count() > 0)
                <div class="bg-gray-800 bg-opacity-90 shadow-lg rounded-2xl p-6 border border-yellow-400/30 mt-10">
                    <h2 class="text-2xl font-bold text-green-400 mb-4">✅ Confirmed Appointments</h2>
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
                                @foreach($acceptedAppointments as $appointment)
                                    <tr class="hover:bg-gray-700/60 transition">
                                        <td class="py-3 px-6">{{ $appointment->date }}</td>
                                        <td class="py-3 px-6">{{ \Carbon\Carbon::parse($appointment->time)->format('h:i A') }}</td>
                                        <td class="py-3 px-6">{{ $appointment->service }}</td>
                                        <td class="py-3 px-6">
                                            <span class="px-2 py-1 rounded-md font-semibold text-sm bg-green-400 text-black">
                                                {{ ucfirst($appointment->status) }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-6">{{ $appointment->dentist->name ?? 'N/A' }}</td>
                                        <td class="py-3 px-6 text-center">
                                            @if($appointment->note)
                                                <button type="button" onclick="openPatientNoteModal('{{ $appointment->note }}')"
                                                        class="px-2 py-1 rounded bg-transparent border border-yellow-300 text-white hover:bg-yellow-300/80 hover:text-black text-sm active:bg-yellow-400/20 active:text-white">
                                                    View
                                                </button>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="py-2 px-4 text-center">
                                            {{-- Only Cancel Request is available for Accepted --}}
                                            @if($appointment->cancel_requested)
                                                <span class="text-gray-400 font-medium text-sm">Request submitted</span>
                                            @else
                                                <button type="button" onclick="openCancelModal({{ $appointment->id }})"
                                                        class="bg-red-400 px-2 py-1 rounded text-black hover:bg-red-500 active:text-gray-200 text-sm">
                                                    Cancel
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif


            @if($allHistoryAppointments->count() > 0)
                <div class="bg-gray-800 bg-opacity-90 shadow-lg rounded-2xl p-6 border border-yellow-400/30 mt-10">
                    
                    {{-- History Header (Toggle Switch) --}}
                    <button onclick="toggleHistory()" class="w-full text-left flex justify-between items-center p-2 rounded-lg hover:bg-gray-700/50 transition mb-4">
                        <h2 class="text-2xl font-bold text-yellow-400">
                            📅 Appointment History 
                            <span class="text-lg font-medium text-gray-400 ml-3">
                                ({{ $allHistoryAppointments->count() }} records total)
                            </span>
                        </h2>
                        {{-- Toggle Icon (Rotates when open) --}}
                        <svg id="historyToggleIcon" class="w-6 h-6 text-yellow-400 transform transition-transform duration-300 rotate-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    {{-- Collapsible Content with In-Header Filter --}}
                    <div id="historyContent" class="hidden mt-4"> 
                        <div class="overflow-x-auto rounded-xl shadow-lg">
                            <table class="min-w-full text-yellow-300">
                                <thead class="bg-gray-900 text-yellow-400 uppercase text-sm tracking-wider">
                                    <tr>
                                        <th class="py-3 px-6 text-left">Date</th>
                                        <th class="py-3 px-6 text-left">Time</th>
                                        <th class="py-3 px-6 text-left">Service</th>
                                        
                                        {{-- Status Header with Dropdown Filter (FIXED) --}}
                                        <th id="statusFilterHeader" class="py-3 px-6 text-left relative z-20">
                                            <div class="flex items-center space-x-1 cursor-pointer" onclick="toggleFilterDropdown(event)">
                                                <span>Status</span>
                                                <svg class="w-4 h-4 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                            </div>
                                            
                                            {{-- Dropdown Menu --}}
                                            <div id="filterDropdown" class="absolute left-0 mt-2 w-48 bg-gray-700 rounded-md shadow-lg hidden">
                                                <a href="#" class="block px-4 py-2 text-sm text-yellow-300 hover:bg-gray-600" onclick="selectFilter(event, 'all')">All History</a>
                                                <a href="#" class="block px-4 py-2 text-sm text-yellow-300 hover:bg-gray-600" onclick="selectFilter(event, 'completed')">Completed</a>
                                                <a href="#" class="block px-4 py-2 text-sm text-yellow-300 hover:bg-gray-600" onclick="selectFilter(event, 'declined')">Declined</a>
                                                <a href="#" class="block px-4 py-2 text-sm text-yellow-300 hover:bg-gray-600" onclick="selectFilter(event, 'cancelled')">Cancelled</a>
                                            </div>
                                        </th>
                                        {{-- End Status Header --}}

                                        <th class="py-3 px-6 text-left">Dentist</th>
                                        <th class="py-3 px-6 text-left">Reason/Note</th>
                                    </tr>
                                </thead>
                                {{-- All History Data --}}
                                <tbody id="allHistoryTableBody" class="divide-y divide-gray-600">
                                    @foreach($allHistoryAppointments as $appointment)
                                        @php
                                            // Determine the specific status key for filtering
                                            $statusKey = $appointment->is_cancelled ? 'cancelled' : strtolower($appointment->status);
                                            // Determine the display status and color
                                            $displayStatus = $appointment->is_cancelled ? 'Cancelled' : ucfirst($appointment->status);
                                            $bgColor = match($statusKey) {
                                                'completed' => 'bg-blue-400',
                                                'declined' => 'bg-red-500', 
                                                'cancelled' => 'bg-red-400',
                                                default => 'bg-gray-400',
                                            };
                                        @endphp

                                        <tr class="hover:bg-gray-700/60 transition" 
                                            data-status="{{ $statusKey }}">
                                            <td class="py-3 px-6">{{ $appointment->date }}</td>
                                            <td class="py-3 px-6">{{ \Carbon\Carbon::parse($appointment->time)->format('h:i A') }}</td>
                                            <td class="py-3 px-6">{{ $appointment->service }}</td>
                                            <td class="py-3 px-6">
                                                <span class="px-2 py-1 rounded-md font-semibold text-sm text-black {{ $bgColor }}">
                                                    {{ $displayStatus }}
                                                </span>
                                            </td>
                                            <td class="py-3 px-6">{{ $appointment->dentist->name ?? 'N/A' }}</td>
                                            <td class="py-3 px-6">{{ $appointment->cancel_reason ?? $appointment->note ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Modals (Unchanged) --}}
            {{-- ... (Delete Modal, Note Modal, Cancel Modal HTML remains here) ... --}}
            <div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50">
                <div class="bg-gray-900 rounded-xl p-6 w-96 max-w-full relative">
                    <button onclick="closeDeleteModal()" class="absolute -top-3 -right-3 text-yellow-400 font-bold text-3xl 
                                     bg-gray-800 rounded-full w-10 h-10 flex items-center justify-center 
                                     hover:bg-yellow-400 hover:text-black transition">&times;</button>
                    <h3 class="text-yellow-300 font-semibold text-lg mb-4">Confirm Deletion</h3>
                    <p class="text-yellow-400 mb-4">Are you sure you want to delete this appointment request?</p>
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

            <div id="noteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50">
                <div class="bg-gray-900 rounded-xl p-6 w-96 max-w-full relative">
                    <button onclick="closeNoteModal()" class="absolute -top-3 -right-3 text-yellow-400 font-bold text-3xl 
                                     bg-gray-800 rounded-full w-10 h-10 flex items-center justify-center 
                                     hover:bg-yellow-400 hover:text-black transition">&times;</button>
                    <h3 class="text-yellow-300 font-semibold text-lg mb-4">Appointment Note</h3>
                    <textarea id="noteText" rows="6" class="w-full px-3 py-2 rounded bg-gray-700 text-white resize-none" readonly></textarea>
                </div>
            </div>

            <div id="cancelModal" class="hidden fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50">
                <div class="bg-gray-900 rounded-xl p-6 w-96 max-w-full relative">
                    <button onclick="closeCancelModal()" class="absolute -top-3 -right-3 text-yellow-400 font-bold text-3xl 
                                     bg-gray-800 rounded-full w-10 h-10 flex items-center justify-center 
                                     hover:bg-yellow-400 hover:text-black transition">&times;</button>

                    <h3 class="text-yellow-300 font-semibold text-lg mb-4">Request Cancellation</h3>
                    <p class="text-yellow-400 mb-3 text-sm">A cancellation request must be approved by the clinic.</p>

                    <form id="cancelForm" method="POST" onsubmit="submitCancelRequest(event)">
                        @csrf
                        <textarea name="cancel_reason" rows="4" placeholder="Enter reason..." class="w-full px-3 py-2 rounded bg-gray-700 text-white mb-3" required></textarea>
                        <button type="submit" class="bg-red-500 px-2 py-1 rounded text-black hover:bg-red-400 w-full font-semibold">Submit Request</button>
                    </form>
                </div>
            </div>


        </div>
    </div>

    <script>
        
        // --- Modal Functions (Unchanged) ---
        function openDeleteModal(appointmentId) {
            const form = document.getElementById('deleteForm');
            form.action = `/appointments/${appointmentId}`; 
            document.getElementById('deleteModal').classList.remove('hidden');
            document.getElementById('deleteModal').classList.add('flex');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            document.getElementById('deleteModal').classList.remove('flex');
        }
        
        // In the <script> block of your patient appointments page

// In the <script> block of appointments.blade.php

function openPatientNoteModal(note) {
    const noteTextarea = document.getElementById('noteText');
    const noteModal = document.getElementById('noteModal');
    
    if (noteTextarea) {
        // Set the content and explicitly ensure it is read-only
        noteTextarea.value = note;
        noteTextarea.setAttribute('readonly', 'readonly'); 
        
        // Hide the save button if the receptionist HTML structure required it to be present
        const saveButton = document.getElementById('saveNoteButton');
        if (saveButton) {
            saveButton.classList.add('hidden'); 
        }
    }

    if (noteModal) {
        noteModal.classList.remove('hidden');
        noteModal.classList.add('flex');
    }
}

function closeNoteModal() {
    document.getElementById('noteModal').classList.add('hidden');
    document.getElementById('noteModal').classList.remove('flex');
}

// Keep the simple closing function


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

        // --- History Toggle Function (Unchanged) ---
        function toggleHistory() {
            const content = document.getElementById('historyContent');
            const icon = document.getElementById('historyToggleIcon');
            
            // Toggle visibility
            if (content.classList.contains('hidden')) {
                content.classList.remove('hidden');
                icon.classList.add('rotate-180'); 
            } else {
                content.classList.add('hidden');
                icon.classList.remove('rotate-180'); 
            }
        }
        
        // --- Cancel Request Submission (Unchanged) ---
        function submitCancelRequest(event) {
            event.preventDefault();

            const form = event.target;
            const urlParts = form.action.split('/');
            const appointmentId = urlParts[urlParts.length - 2]; 
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
            .then(res => {
                if (!res.ok) {
                    throw new Error('Network response was not ok');
                }
                return res.json();
            })
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
                alert('An error occurred while submitting the cancellation request.');
            });
        }
        
        // --- UPDATED: In-Header Filter Logic ---
        function toggleFilterDropdown(event) {
            // Stop propagation to prevent document click listener from immediately closing it
            event.stopPropagation(); 
            const dropdown = document.getElementById('filterDropdown');
            dropdown.classList.toggle('hidden');
        }

        function selectFilter(event, filterValue) {
            event.preventDefault(); // Stop default link action
            
            // Close dropdown immediately after selection
            document.getElementById('filterDropdown').classList.add('hidden');
            
            const rows = document.getElementById('allHistoryTableBody').getElementsByTagName('tr');

            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const status = row.getAttribute('data-status');

                let showRow = false;
                
                if (filterValue === 'all') {
                    showRow = true;
                } else if (filterValue === status) {
                    showRow = true;
                }

                row.style.display = showRow ? '' : 'none';
            }
        }

        // Close dropdown if user clicks outside of it
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('filterDropdown');
            const statusHeader = document.getElementById('statusFilterHeader'); // Use the new ID
            
            // Check if the click occurred outside the status header (which contains the dropdown)
            if (dropdown && !dropdown.classList.contains('hidden') && statusHeader && !statusHeader.contains(event.target)) {
                dropdown.classList.add('hidden');
            }
        });
    </script>
    
    @include('partials.footer')
</x-app-layout>