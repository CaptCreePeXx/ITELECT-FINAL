<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-yellow-400 leading-tight text-center">
            🦷 Receptionist Dashboard
        </h2>
    </x-slot>

    {{-- Main Content Container with Tabbed Interface --}}
    <div class="py-12 bg-gray-900 min-h-screen">
        {{-- ADDED: history tab state for the new combined tab --}}
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8 space-y-8" x-data="{ activeTab: 'pending', activeHistoryTab: 'completed' }">

            {{-- Tab Navigation (CLEANED UP) --}}
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
                    <button @click="activeTab = 'cancellation'" :class="{ 'bg-red-600 text-white': activeTab === 'cancellation', 'text-yellow-400 hover:text-yellow-300': activeTab !== 'cancellation' }" class="py-2 px-4 rounded-lg transition-colors duration-200 whitespace-nowrap font-bold">
                        Cancellation Requests ({{ $cancellationRequests->count() }})
                    </button>
                    {{-- NEW COMBINED HISTORY TAB --}}
                    <button @click="activeTab = 'history'" :class="{ 'bg-yellow-400 text-gray-900': activeTab === 'history', 'text-yellow-400 hover:text-yellow-300': activeTab !== 'history' }" class="py-2 px-4 rounded-lg transition-colors duration-200 whitespace-nowrap">
                        Appointment History
                    </button>
                </nav>
            </div>
            
            {{-- Tab Content (The separate declined/completed/cancelled blocks are removed from this section) --}}
            <div class="p-0">
                
               {{-- Pending Appointments (Patient Note Added) --}}
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
                        <th class="py-3 px-6 text-left">Patient Note</th> {{-- NEW COLUMN: PATIENT'S NOTE --}}
                        <th class="py-3 px-6 text-left">Status</th>
                        <th class="py-3 px-6 text-left">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-600">
                    @foreach($pendingAppointments as $appointment)
                        <tr class="hover:bg-gray-700/60 transition">
                            <td class="py-3 px-6 whitespace-nowrap">{{ $appointment->patient->id ?? 'N/A' }}</td>
                            <td class="py-3 px-6 font-semibold whitespace-nowrap">{{ $appointment->patient->name ?? 'N/A' }}</td>
                            <td class="py-3 px-6">{{ $appointment->patient->email ?? 'N/A' }}</td>
                            <td class="py-3 px-6 whitespace-nowrap">{{ $appointment->date }}</td>
                            <td class="py-3 px-6 whitespace-nowrap">{{ \Carbon\Carbon::parse($appointment->time)->format('h:i A') }}</td>
                            <td class="py-3 px-6">{{ $appointment->service }}</td>
                            <td class="py-3 px-6 whitespace-nowrap">
    @if($appointment->patient_reason)
        
        {{-- Store the actual text in a HIDDEN span for the modal to read --}}
        <span id="patientNote_{{ $appointment->id }}" class="hidden">
            {{ $appointment->patient_reason }}
        </span>
        
        {{-- Updated Button Design (Matching Accept Button) --}}
        <button type="button" 
            onclick="openNoteModal({{ $appointment->id }}, 'patient_reason')" 
            class="text-white px-2 py-1 text-sm rounded hover:bg-yellow-400/90 hover:text-black bg-transparent border border-yellow-400">
            View
        </button>
        
    @else
        <span class="text-gray-400">-</span>
    @endif
</td>
                            <td class="py-3 px-6 whitespace-nowrap">{{ ucfirst($appointment->status) ?? 'Pending' }}</td>
                            <td class="py-3 px-6">
    <div class="flex flex-col gap-2">

        {{-- 1. FORM FOR ACCEPT AND ASSIGN DENTIST (Primary Action) --}}
        <form action="{{ route('appointments.accept', $appointment->id) }}" method="POST" id="acceptForm_{{ $appointment->id }}">
            @csrf
            
            <select name="dentist_id" class="w-full px-2 py-1 rounded bg-gray-700 text-white text-center">
                <option value="">-- Assign Dentist --</option>
                @foreach($dentists as $dentist)
                    <option value="{{ $dentist->id }}" {{ $appointment->dentist_id == $dentist->id ? 'selected' : '' }}>
                        {{ $dentist->name }}
                    </option>
                @endforeach
            </select>
            
            {{-- HIDDEN NOTE INPUT for ACCEPT/NOTE ACTION --}}
            <input type="hidden" name="note" id="noteHiddenInputAccept_{{ $appointment->id }}" value="{{ $appointment->note ?? '' }}">
            
            <div class="flex justify-between items-center mt-2">
                {{-- *** FIX 1: Pass the hidden input ID to openNoteModal *** --}}
                <button type="button" 
                    onclick="openNoteModal({{ $appointment->id }}, 'receptionist_message', 'noteHiddenInputAccept_{{ $appointment->id }}')" 
                    class="px-2 py-1 text-xs rounded bg-gray-700 text-yellow-400 hover:bg-gray-600">
                    Note
                </button>

                <button type="submit" class="text-white px-2 py-1 text-sm rounded hover:bg-yellow-400/90 hover:text-black bg-transparent border border-yellow-400">
                    Accept
                </button>
            </div>
        </form>

        <hr class="border-gray-700/50"> 

        {{-- 2. FORM FOR DECLINE (Separate Action) --}}
        <form action="{{ route('appointments.decline', $appointment->id) }}" method="POST" id="declineForm_{{ $appointment->id }}" class="flex justify-end">
            @csrf
            
            {{-- HIDDEN NOTE INPUT for DECLINE ACTION --}}
            <input type="hidden" name="note" id="noteHiddenInputDecline_{{ $appointment->id }}" value="{{ $appointment->note ?? '' }}">

            {{-- *** FIX 2: Call the new openDeclineModal function *** --}}
            <button type="button" 
                onclick="openDeclineModal({{ $appointment->id }})" 
                class="bg-red-700 text-white px-2 py-1 text-sm rounded hover:bg-red-500 w-full">
                Decline
            </button>
        </form>

    </div>
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
                {{-- Active Appointments (No change in content) --}}
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

                {{-- Ongoing Appointments (No change in content) --}}
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

                {{-- Cancellation Requests (No change in content) --}}
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
                
                {{-- NEW: COMBINED APPOINTMENT HISTORY (Completed, Declined, Cancelled) --}}
                <div x-show="activeTab === 'history'" x-cloak class="bg-gray-800 bg-opacity-90 shadow-lg rounded-b-2xl rounded-tr-2xl p-6 border border-yellow-400/30">
                    <h3 class="text-yellow-400 font-bold mb-4">Appointment History</h3>

                    {{-- Internal Filter Buttons --}}
                    <div class="mb-4 flex space-x-4">
                        <button @click="activeHistoryTab = 'completed'" :class="{ 'bg-green-400 text-gray-900': activeHistoryTab === 'completed', 'text-yellow-400 hover:text-yellow-300 border border-yellow-400': activeHistoryTab !== 'completed' }" class="py-2 px-4 rounded-lg transition-colors duration-200 text-sm">
                            Completed
                        </button>
                        <button @click="activeHistoryTab = 'declined'" :class="{ 'bg-red-600 text-white': activeHistoryTab === 'declined', 'text-yellow-400 hover:text-yellow-300 border border-yellow-400': activeHistoryTab !== 'declined' }" class="py-2 px-4 rounded-lg transition-colors duration-200 text-sm">
                            Declined
                        </button>
                        <button @click="activeHistoryTab = 'cancelled'" :class="{ 'bg-gray-500 text-white': activeHistoryTab === 'cancelled', 'text-yellow-400 hover:text-yellow-300 border border-yellow-400': activeHistoryTab !== 'cancelled' }" class="py-2 px-4 rounded-lg transition-colors duration-200 text-sm">
                            Cancelled
                        </button>
                    </div>

                    {{-- 1. Completed Appointments (Moved from old tab) --}}
                    <div x-show="activeHistoryTab === 'completed'" x-cloak>
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
                                            <th class="py-2 px-4 text-left">Note/Reason</th>
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
                                                <td class="py-2 px-4">{{ $appointment->note ?? '-' }}</td> {{-- Display Note/Reason --}}
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-yellow-400 mt-6 text-center text-lg">No completed appointments found.</p>
                        @endif
                    </div>

                    {{-- 2. Declined Appointments (Moved from old tab) --}}
                    <div x-show="activeHistoryTab === 'declined'" x-cloak>
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
                            <p class="text-yellow-400 mt-6 text-center text-lg">No declined appointments found.</p>
                        @endif
                    </div>
                    
                    {{-- 3. Cancelled Appointments (Moved from old tab) --}}
                    <div x-show="activeHistoryTab === 'cancelled'" x-cloak>
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
                                                <td class="py-2 px-4">{{ $appointment->cancel_reason ?? '-' }}</td> 
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-yellow-400 mt-6 text-center text-lg">No cancelled appointments found.</p>
                        @endif
                    </div>

                </div> 
                {{-- END NEW: COMBINED APPOINTMENT HISTORY --}}


            </div>
        </div>
    </div>

    {{-- Note Modal (Ensure it has these IDs) --}}
<div id="noteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50">
    <div class="bg-gray-900 rounded-xl p-6 w-96 max-w-full relative">
        <button onclick="closeNoteModal()" class="absolute top-2 right-2 text-yellow-400 font-bold text-3xl">&times;</button>
        <h3 id="noteModalTitle" class="text-yellow-300 font-semibold text-lg mb-4">Note</h3>
        <div class="mt-3">
            <textarea id="noteText" rows="6" placeholder="Enter note..." class="w-full px-3 py-2 rounded bg-gray-700 text-white"></textarea>
        </div>
        <div class="mt-4 flex justify-end gap-3">
            <button type="button" onclick="closeNoteModal()" class="px-4 py-2 rounded bg-gray-700 text-yellow-400 hover:bg-gray-600">Close</button>
            <button type="button" id="saveNoteButton" class="px-4 py-2 rounded bg-yellow-400 text-black hover:bg-yellow-500 hidden">Save Note</button> 
        </div>
    </div>
</div>

    {{-- JavaScript (UPDATED) --}}
    <script>
let currentAppointmentId = null;
let currentNoteType = null;
let currentHiddenInputId = null;

function openNoteModal(id, mode = 'receptionist_message', hiddenInputId = null) {
    currentAppointmentId = id;
    currentNoteType = mode;
    currentHiddenInputId = hiddenInputId;

    const noteTextarea = document.getElementById('noteText');
    const saveButton = document.getElementById('saveNoteButton');
    const modalTitle = document.getElementById('noteModalTitle');
    const modal = document.getElementById('noteModal');

    // Reset
    noteTextarea.value = '';
    noteTextarea.removeAttribute('readonly');
    noteTextarea.classList.remove('bg-gray-800', 'text-gray-300');
    saveButton.classList.remove('hidden');

    if (mode === 'patient_reason') {
        const patientNoteSpan = document.getElementById('patientNote_' + id);
        noteTextarea.value = patientNoteSpan ? patientNoteSpan.textContent.trim() : 'No note provided.';
        noteTextarea.setAttribute('readonly', 'readonly');
        noteTextarea.classList.add('bg-gray-800', 'text-gray-300');
        saveButton.classList.add('hidden');
        modalTitle.textContent = 'Patient Booking Reason';
    } else if (mode === 'receptionist_message' && hiddenInputId) {
        const hiddenInput = document.getElementById(hiddenInputId);
        noteTextarea.value = hiddenInput ? hiddenInput.value : '';
        modalTitle.textContent = 'Receptionist Note for Patient';
    }

    // Show modal
    modal.style.display = 'flex';
}

function closeNoteModal() {
    currentAppointmentId = null;
    currentNoteType = null;
    currentHiddenInputId = null;

    const modal = document.getElementById('noteModal');
    if (modal) {
        modal.style.display = 'none';
    }

    const noteTextarea = document.getElementById('noteText');
    if (noteTextarea) noteTextarea.value = '';
}

function saveNoteToForm() {
    const noteTextarea = document.getElementById('noteText');
    if (currentHiddenInputId && noteTextarea) {
        const hiddenInput = document.getElementById(currentHiddenInputId);
        if (hiddenInput) hiddenInput.value = noteTextarea.value;
    }

    // Close the modal always
    closeNoteModal();
}

// DOMContentLoaded: attach click
document.addEventListener('DOMContentLoaded', () => {
    const saveButton = document.getElementById('saveNoteButton');
    if (saveButton) {
        saveButton.onclick = saveNoteToForm;
    }
});
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
        </div>
    @endif
</x-app-layout>