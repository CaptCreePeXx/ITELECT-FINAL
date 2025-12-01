<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-yellow-400 leading-tight text-center">
            🦷 Receptionist Dashboard
        </h2>
    </x-slot>

    {{-- Main Content Container with Tabbed Interface --}}
    <div class="py-12 bg-gray-900 min-h-screen">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8 space-y-8" x-data="{ activeTab: 'pending' }">

            {{-- Tab Navigation (No changes here) --}}
            <div class="bg-gray-800 bg-opacity-90 shadow-lg rounded-t-2xl p-4 border-b border-yellow-400/30">
                <nav class="flex space-x-4 overflow-x-auto text-sm font-medium">
                    <button @click="activeTab = 'pending'" :class="{ 'bg-yellow-400 text-gray-900': activeTab === 'pending', 'text-yellow-400 hover:text-yellow-300': activeTab !== 'pending' }" class="py-2 px-4 rounded-lg transition-colors duration-200 whitespace-nowrap">
                        Pending Appointments ({{ $pendingAppointments->count() }})
                    </button>
                    <button @click="activeTab = 'active'" :class="{ 'bg-yellow-400 text-gray-900': activeTab === 'active', 'text-yellow-400 hover:text-yellow-300': activeTab !== 'active' }" class="py-2 px-4 rounded-lg transition-colors duration-200 whitespace-nowrap">
                        Active Appointments ({{ $activeAppointments->count() }})
                    </button>
                    <button @click="activeTab = 'ongoing'" :class="{ 'bg-yellow-400 text-gray-900': activeTab === 'ongoing', 'text-yellow-400 hover:text-yellow-300': activeTab !== 'ongoing' }" class="py-2 px-4 rounded-lg transition-colors duration-200 whitespace-nowrap">
                        Ongoing (Today) ({{ $ongoingAppointments->count() }})
                    </button>
                    <button @click="activeTab = 'completed'" :class="{ 'bg-yellow-400 text-gray-900': activeTab === 'completed', 'text-yellow-400 hover:text-yellow-300': activeTab !== 'completed' }" class="py-2 px-4 rounded-lg transition-colors duration-200 whitespace-nowrap">
                        Completed ({{ $completedAppointments->count() }})
                    </button>
                    <button @click="activeTab = 'cancellation'" :class="{ 'bg-yellow-400 text-gray-900': activeTab === 'cancellation', 'text-yellow-400 hover:text-yellow-300': activeTab !== 'cancellation' }" class="py-2 px-4 rounded-lg transition-colors duration-200 whitespace-nowrap">
                        Cancellation Requests ({{ $cancellationRequests->count() }})
                    </button>
                    <button @click="activeTab = 'declined'" :class="{ 'bg-yellow-400 text-gray-900': activeTab === 'declined', 'text-yellow-400 hover:text-yellow-300': activeTab !== 'declined' }" class="py-2 px-4 rounded-lg transition-colors duration-200 whitespace-nowrap">
                        Declined ({{ $declinedAppointments->count() }})
                    </button>
                    <button @click="activeTab = 'cancelled'" :class="{ 'bg-yellow-400 text-gray-900': activeTab === 'cancelled', 'text-yellow-400 hover:text-yellow-300': activeTab !== 'cancelled' }" class="py-2 px-4 rounded-lg transition-colors duration-200 whitespace-nowrap">
                        Cancelled ({{ $cancelledAppointments->count() }})
                    </button>
                </nav>
            </div>
            
            {{-- Tab Content (Each original section is now controlled by the activeTab state) --}}
            <div class="p-0">
                
                {{-- Pending Appointments --}}
                <div x-show="activeTab === 'pending'" x-cloak class="bg-gray-800 bg-opacity-90 shadow-lg rounded-b-2xl rounded-tr-2xl p-6 border border-yellow-400/30">
                    <h3 class="text-yellow-400 font-bold mb-4">Pending Appointments</h3>
                    @if($pendingAppointments->count() > 0)
                        <div class="overflow-x-auto rounded-xl shadow-lg">
                            <table class="min-w-full text-yellow-300">
                                <thead class="bg-gray-900 text-yellow-400 uppercase text-sm tracking-wider">
                                    <tr>
                                        <th class="py-3 px-6 text-left">Patient ID</th>
                                        <th class="py-3 px-6 text-left">Name</th>
                                        <th class="py-3 px-6 text-left">Email</th>
                                        <th class="py-3 px-6 text-left">Date</th>
                                        <th class="py-3 px-6 text-left">Time</th>
                                        <th class="py-3 px-6 text-left">Service</th>
                                        <th class="py-3 px-6 text-left">Status</th>
                                        <th class="py-3 px-6 text-left">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-600">
                                    @foreach($pendingAppointments as $appointment)
                                        <tr class="hover:bg-gray-700/60 transition">
                                            <td class="py-3 px-6">{{ $appointment->patient->id ?? 'N/A' }}</td>
                                            <td class="py-3 px-6">{{ $appointment->patient->name ?? 'N/A' }}</td>
                                            <td class="py-3 px-6">{{ $appointment->patient->email ?? 'N/A' }}</td>
                                            <td class="py-3 px-6">{{ $appointment->date }}</td>
                                            <td class="py-3 px-6">{{ \Carbon\Carbon::parse($appointment->time)->format('h:i A') }}</td>
                                            <td class="py-3 px-6">{{ $appointment->service }}</td>
                                            <td class="py-3 px-6">{{ ucfirst($appointment->status) ?? 'Pending' }}</td>
                                            <td class="py-3 px-6">
                                                {{-- The key is: DO NOT remove the form wrapper as it contains the inputs --}}
                                                <form action="{{ route('appointments.accept', $appointment->id) }}" method="POST" id="form_{{ $appointment->id }}" class="flex flex-col gap-2">
                                                    @csrf
                                                    <select name="dentist_id" class="w-full px-2 py-1 rounded bg-gray-700 text-white text-center">
                                                        <option value="">-- Assign Dentist --</option>
                                                        @foreach($dentists as $dentist)
                                                            <option value="{{ $dentist->id }}" {{ $appointment->dentist_id == $dentist->id ? 'selected' : '' }}>
                                                                {{ $dentist->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                    {{-- The note input must stay here --}}
                                                    <input type="hidden" name="note" id="noteHiddenInput_{{ $appointment->id }}" value="{{ $appointment->note ?? '' }}">

                                                    <div class="flex justify-between items-center mt-2">
                                                        {{-- The Note button will only open the modal --}}
                                                        <button type="button" onclick="openNoteModal({{ $appointment->id }})" class="px-2 py-1 text-sm rounded bg-gray-700 text-yellow-400 hover:bg-gray-600">Note</button>
                                                        <div class="flex gap-2">
                                                            {{-- The Accept button submits this form --}}
                                                            <button type="submit" class="text-white px-2 py-1 text-sm rounded hover:bg-yellow-400/90 hover:text-black bg-transparent border border-yellow-400">Accept</button>
                                                            {{-- The Decline button submits this form to a different route --}}
                                                            <button type="submit" formaction="{{ route('appointments.decline', $appointment->id) }}" class="bg-red-700 text-white px-2 py-1 text-sm rounded hover:bg-red-500">Decline</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-yellow-400 mt-6 text-center text-lg">No pending appointments.</p>
                    @endif
                </div>

                {{-- Active Appointments (No changes here, keeping only the logic for note update) --}}
                <div x-show="activeTab === 'active'" x-cloak class="bg-gray-800 bg-opacity-90 shadow-lg rounded-b-2xl rounded-tr-2xl p-6 border border-yellow-400/30">
                    <h3 class="text-yellow-400 font-bold mb-4">Active Appointments</h3>
                    @if($activeAppointments->count() > 0)
                        <div class="overflow-x-auto max-h-96 rounded-xl shadow-lg">
                            <table class="min-w-full text-yellow-300">
                                <thead class="bg-gray-900 text-yellow-400 sticky top-0 z-10 uppercase text-sm tracking-wider">
                                    <tr>
                                        <th class="py-2 px-4 text-left">Patient</th>
                                        <th class="py-2 px-4 text-left">Date</th>
                                        <th class="py-2 px-4 text-left">Time</th>
                                        <th class="py-2 px-4 text-left">Service</th>
                                        <th class="py-2 px-4 text-left">Dentist</th>
                                        <th class="py-2 px-4 text-left">Status</th>
                                        <th class="py-2 px-4 text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-600">
                                    @foreach($activeAppointments as $appointment)
                                        <tr class="hover:bg-gray-700/60 transition">
                                            <td class="py-2 px-4">{{ $appointment->patient->name ?? 'N/A' }}</td>
                                            <td class="py-2 px-4">{{ $appointment->date }}</td>
                                            <td class="py-2 px-4">{{ \Carbon\Carbon::parse($appointment->time)->format('h:i A') }}</td>
                                            <td class="py-2 px-4">{{ $appointment->service }}</td>
                                            <td class="py-2 px-4">
                                                <form action="{{ route('appointments.assignDentist', $appointment->id) }}" method="POST" class="flex items-center gap-2">
                                                    @csrf
                                                    <select name="dentist_id" class="px-2 py-1 rounded bg-gray-700 text-white text-sm w-auto">
                                                        <option value="">-- Dentist --</option>
                                                        @foreach($dentists as $dentist)
                                                            <option value="{{ $dentist->id }}" {{ $appointment->dentist_id == $dentist->id ? 'selected' : '' }}>
                                                                {{ $dentist->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <button type="submit" class="px-2 py-1 rounded bg-yellow-400 text-gray-900 text-sm hover:bg-yellow-500">
                                                        {{ $appointment->dentist_id ? 'Reassign' : 'Assign' }}
                                                    </button>
                                                </form>
                                            </td>
                                            <td class="py-2 px-4">{{ ucfirst($appointment->status) }}</td>
                                            <td class="py-2 px-4 text-center">
                                                <form action="{{ route('appointments.updateNote', $appointment->id) }}" method="POST" id="noteForm_{{ $appointment->id }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="note" id="noteHiddenInput_{{ $appointment->id }}" value="{{ $appointment->note ?? '' }}">
                                                    {{-- The Note button will open the modal --}}
                                                    <button type="button" onclick="openNoteModal({{ $appointment->id }})" class="px-2 py-1 text-sm rounded bg-gray-700 text-yellow-400 hover:bg-gray-600">
                                                        Update Note
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-yellow-400 mt-6 text-center text-lg">No active appointments.</p>
                    @endif
                </div>

                {{-- Ongoing Appointments (No changes) --}}
                <div x-show="activeTab === 'ongoing'" x-cloak class="bg-gray-800 bg-opacity-90 shadow-lg rounded-b-2xl rounded-tr-2xl p-6 border border-yellow-400/30">
                    <h3 class="text-yellow-400 font-bold mb-4">Ongoing Appointments (Today)</h3>
                    @if($ongoingAppointments->count() > 0)
                        <div class="overflow-x-auto max-h-96 rounded-xl shadow-lg">
                            <table class="min-w-full text-yellow-300">
                                <thead class="bg-gray-900 text-yellow-400 sticky top-0 z-10 uppercase text-sm tracking-wider">
                                    <tr>
                                        <th class="py-2 px-4 text-left">Patient</th>
                                        <th class="py-2 px-4 text-left">Time</th>
                                        <th class="py-2 px-4 text-left">Service</th>
                                        <th class="py-2 px-4 text-left">Dentist</th>
                                        <th class="py-2 px-4 text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-600">
                                    @foreach($ongoingAppointments as $appointment)
                                        <tr class="hover:bg-gray-700/60 transition">
                                            <td class="py-2 px-4">{{ $appointment->patient->name ?? 'N/A' }}</td>
                                            <td class="py-2 px-4">{{ \Carbon\Carbon::parse($appointment->time)->format('h:i A') }}</td>
                                            <td class="py-2 px-4">{{ $appointment->service }}</td>
                                            <td class="py-2 px-4">{{ $appointment->dentist->name ?? '-' }}</td>
                                            <td class="py-2 px-4 text-center">
                                                <form action="{{ route('appointments.complete', $appointment->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="bg-yellow-400 text-gray-900 px-2 py-1 rounded hover:bg-yellow-500 text-sm">Mark Completed</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-yellow-400 mt-6 text-center text-lg">No ongoing appointments today.</p>
                    @endif
                </div>

                {{-- Completed Appointments (No changes) --}}
                <div x-show="activeTab === 'completed'" x-cloak class="bg-gray-800 bg-opacity-90 shadow-lg rounded-b-2xl rounded-tr-2xl p-6 border border-yellow-400/30">
                    <h3 class="text-yellow-400 font-bold mb-4">Completed Appointments</h3>
                    @if($completedAppointments->count() > 0)
                        <div class="overflow-x-auto max-h-96 rounded-xl shadow-lg">
                            <table class="min-w-full text-yellow-300">
                                <thead class="bg-gray-900 text-yellow-400 sticky top-0 z-10 uppercase text-sm tracking-wider">
                                    <tr>
                                        <th class="py-2 px-4 text-left">Patient</th>
                                        <th class="py-2 px-4 text-left">Date</th>
                                        <th class="py-2 px-4 text-left">Time</th>
                                        <th class="py-2 px-4 text-left">Service</th>
                                        <th class="py-2 px-4 text-left">Dentist</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-600">
                                    @foreach($completedAppointments as $appointment)
                                        <tr class="hover:bg-gray-700/60 transition">
                                            <td class="py-2 px-4">{{ $appointment->patient->name ?? 'N/A' }}</td>
                                            <td class="py-2 px-4">{{ $appointment->date }}</td>
                                            <td class="py-2 px-4">{{ \Carbon\Carbon::parse($appointment->time)->format('h:i A') }}</td>
                                            <td class="py-2 px-4">{{ $appointment->service }}</td>
                                            <td class="py-2 px-4">{{ $appointment->dentist->name ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-yellow-400 mt-6 text-center text-lg">No completed appointments.</p>
                    @endif
                </div>

                {{-- Cancellation Requests (No changes) --}}
                <div x-show="activeTab === 'cancellation'" x-cloak class="bg-gray-800 bg-opacity-90 shadow-lg rounded-b-2xl rounded-tr-2xl p-6 border border-yellow-400/30">
                    <h3 class="text-yellow-400 font-bold mb-4">Cancellation Requests</h3>
                    @if($cancellationRequests->count() > 0)
                        <div class="overflow-x-auto max-h-96 rounded-xl shadow-lg">
                            <table class="min-w-full text-yellow-300">
                                <thead class="bg-gray-900 text-yellow-400 sticky top-0 z-10 uppercase text-sm tracking-wider">
                                    <tr>
                                        <th class="py-2 px-4 text-left">Patient</th>
                                        <th class="py-2 px-4 text-left">Date</th>
                                        <th class="py-2 px-4 text-left">Time</th>
                                        <th class="py-2 px-4 text-left">Service</th>
                                        <th class="py-2 px-4 text-left">Reason</th>
                                        <th class="py-2 px-4 text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-600">
                                    @foreach($cancellationRequests as $appointment)
                                        <tr class="hover:bg-gray-700/60 transition">
                                            <td class="py-2 px-4">{{ $appointment->patient->name ?? 'N/A' }}</td>
                                            <td class="py-2 px-4">{{ $appointment->date }}</td>
                                            <td class="py-2 px-4">{{ \Carbon\Carbon::parse($appointment->time)->format('h:i A') }}</td>
                                            <td class="py-2 px-4">{{ $appointment->service }}</td>
                                            <td class="py-2 px-4">{{ $appointment->cancel_reason ?? '-' }}</td>
                                            <td class="py-2 px-4 text-center">
                                                <form action="{{ route('appointments.cancelRequestHandled', $appointment->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="bg-red-600 text-white px-2 py-1 rounded hover:bg-red-500 text-sm">
                                                        Cancel
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-yellow-400 mt-6 text-center text-lg">No cancellation requests.</p>
                    @endif
                </div>

                {{-- Declined Appointments (No changes) --}}
                <div x-show="activeTab === 'declined'" x-cloak class="bg-gray-800 bg-opacity-90 shadow-lg rounded-b-2xl rounded-tr-2xl p-6 border border-yellow-400/30">
                    <h3 class="text-yellow-400 font-bold mb-4">Declined Appointments</h3>
                    @if($declinedAppointments->count() > 0)
                        <div class="overflow-x-auto max-h-96 rounded-xl shadow-lg">
                            <table class="min-w-full text-yellow-300">
                                <thead class="bg-gray-900 text-yellow-400 sticky top-0 z-10 uppercase text-sm tracking-wider">
                                    <tr>
                                        <th class="py-2 px-4 text-left">Patient</th>
                                        <th class="py-2 px-4 text-left">Date</th>
                                        <th class="py-2 px-4 text-left">Time</th>
                                        <th class="py-2 px-4 text-left">Service</th>
                                        <th class="py-2 px-4 text-left">Dentist</th>
                                        <th class="py-2 px-4 text-left">Reason</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-600">
                                    @foreach($declinedAppointments as $appointment)
                                        <tr class="hover:bg-gray-700/60 transition">
                                            <td class="py-2 px-4">{{ $appointment->patient->name ?? 'N/A' }}</td>
                                            <td class="py-2 px-4">{{ $appointment->date }}</td>
                                            <td class="py-2 px-4">{{ \Carbon\Carbon::parse($appointment->time)->format('h:i A') }}</td>
                                            <td class="py-2 px-4">{{ $appointment->service }}</td>
                                            <td class="py-2 px-4">{{ $appointment->dentist->name ?? '-' }}</td>
                                            <td class="py-2 px-4">{{ $appointment->cancel_reason ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-yellow-400 mt-6 text-center text-lg">No declined appointments.</p>
                    @endif
                </div>
                
                {{-- Cancelled Appointments --}}
                <div x-show="activeTab === 'cancelled'" x-cloak class="bg-gray-800 bg-opacity-90 shadow-lg rounded-b-2xl rounded-tr-2xl p-6 border border-yellow-400/30">
                    <h3 class="text-yellow-400 font-bold mb-4">Cancelled Appointments</h3>
                    @if($cancelledAppointments->count() > 0)
                        <div class="overflow-x-auto max-h-96 rounded-xl shadow-lg">
                            <table class="min-w-full text-yellow-300">
                                <thead class="bg-gray-900 text-yellow-400 sticky top-0 z-10 uppercase text-sm tracking-wider">
                                    <tr>
                                        <th class="py-2 px-4 text-left">Patient</th>
                                        <th class="py-2 px-4 text-left">Date</th>
                                        <th class="py-2 px-4 text-left">Time</th>
                                        <th class="py-2 px-4 text-left">Service</th>
                                        <th class="py-2 px-4 text-left">Dentist</th>
                                        {{-- This header ensures the reason column exists --}}
                                        <th class="py-2 px-4 text-left">Reason</th> 
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-600">
                                    @foreach($cancelledAppointments as $appointment)
                                        <tr class="hover:bg-gray-700/60 transition">
                                            <td class="py-2 px-4">{{ $appointment->patient->name ?? 'N/A' }}</td>
                                            <td class="py-2 px-4">{{ $appointment->date }}</td>
                                            <td class="py-2 px-4">{{ \Carbon\Carbon::parse($appointment->time)->format('h:i A') }}</td>
                                            <td class="py-2 px-4">{{ $appointment->service }}</td>
                                            <td class="py-2 px-4">{{ $appointment->dentist->name ?? '-' }}</td>
                                            {{-- This line is the key change to display the reason/note --}}
                                            <td class="py-2 px-4">{{ $appointment->cancel_reason ?? '-' }}</td> 
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-yellow-400 mt-6 text-center text-lg">No cancelled appointments.</p>
                    @endif
                </div>

            </div>
            
        </div>
    </div>

    {{-- Note Modal (No changes) --}}
    <div id="noteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50">
        <div class="bg-gray-900 rounded-xl p-6 w-96 max-w-full relative">
            <button onclick="closeNoteModal()" class="absolute top-2 right-2 text-yellow-400 font-bold text-3xl">&times;</button>
            <h3 class="text-yellow-300 font-semibold text-lg mb-4">Edit Note</h3>
            <div class="mt-3">
                <textarea id="noteText" rows="6" placeholder="Enter note..." class="w-full px-3 py-2 rounded bg-gray-700 text-white"></textarea>
            </div>
            <div class="mt-4 flex justify-end gap-3">
                <button type="button" onclick="closeNoteModal()" class="px-4 py-2 rounded bg-gray-700 text-yellow-400 hover:bg-gray-600">Cancel</button>
                <button type="button" onclick="saveNoteToForm()" class="px-4 py-2 rounded bg-yellow-400 text-black hover:bg-yellow-500">Save</button>
            </div>
        </div>
    </div>

    {{-- JavaScript (UPDATED) --}}
    <script>
        let currentAppointmentId = null;

        function openNoteModal(id) {
            currentAppointmentId = id;
            // The noteHiddenInput element is now either on form_ID (pending) or noteForm_ID (active)
            const hiddenInput = document.getElementById('noteHiddenInput_' + id);
            document.getElementById('noteText').value = hiddenInput ? hiddenInput.value : '';
            document.getElementById('noteModal').classList.remove('hidden');
            document.getElementById('noteModal').classList.add('flex');
        }

        function closeNoteModal() {
            currentAppointmentId = null;
            document.getElementById('noteModal').classList.add('hidden');
            document.getElementById('noteModal').classList.remove('flex');
        }

        function saveNoteToForm() {
            if (!currentAppointmentId) return closeNoteModal();
            const note = document.getElementById('noteText').value;
            const hiddenInput = document.getElementById('noteHiddenInput_' + currentAppointmentId);
            
            if (hiddenInput) {
                hiddenInput.value = note;
            }

            // **CRITICAL CHANGE HERE:** // 1. For Active Appointments (which use noteForm_ID), we still want to submit to save the note right away.
            // 2. For Pending Appointments (which use form_ID), we DO NOT submit. The form is submitted only by Accept/Decline buttons.
            const isPendingForm = document.getElementById('form_' + currentAppointmentId) && !document.getElementById('noteForm_' + currentAppointmentId);
            
            if (!isPendingForm) {
                const form = document.getElementById('noteForm_' + currentAppointmentId);
                if (form) form.submit();
            }
            // If it's a pending form, we only save the note to the hidden input and close the modal.

            closeNoteModal();
        }
    </script>

    {{-- Success Message (No changes) --}}
    @if (session('success'))
        <div 
            x-data="{ show: false }"
            x-init="setTimeout(() => show = true, 100); setTimeout(() => show = false, 2800)" 
            x-show="show"
            x-cloak
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-4"
            class="fixed top-6 left-1/2 -translate-x-1/2 bg-green-500/80 text-green-50 border border-green-500/70 backdrop-blur-sm px-4 py-3 rounded-lg shadow-md text-sm font-medium z-50"
        >
            {{ session('success') }}
        </div>
    @endif

    {{-- Alpine.js Script Inclusion (No changes) --}}
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    {{-- Footer Inclusion (No changes) --}}
    @include('partials.footer')
</x-app-layout>