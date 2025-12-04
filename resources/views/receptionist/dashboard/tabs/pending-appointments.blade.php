<div x-show="activeTab === 'pending'" x-cloak class="bg-gray-800 bg-opacity-90 shadow-lg rounded-b-2xl rounded-tr-2xl p-6 border border-yellow-400/30">
    <h3 class="text-yellow-400 font-bold mb-4">Pending Appointments</h3>
    @if($appointments->count() > 0)
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
                        <th class="py-3 px-6 text-left">Patient Note</th>
                        <th class="py-3 px-6 text-left">Status</th>
                        <th class="py-3 px-6 text-left">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-600">
                    @foreach($appointments as $appointment)
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