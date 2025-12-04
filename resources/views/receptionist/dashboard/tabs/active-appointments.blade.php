<div x-show="activeTab === 'active'" x-cloak class="bg-gray-800 bg-opacity-90 shadow-lg rounded-b-2xl rounded-tr-2xl p-6 border border-yellow-400/30">
    <h3 class="text-yellow-400 font-bold mb-4">Active Appointments</h3>
    @if($appointments->count() > 0)
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
                    @foreach($appointments as $appointment)
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
                                    <button type="button" 
                                        onclick="openNoteModal({{ $appointment->id }}, 'receptionist_message', 'noteHiddenInput_{{ $appointment->id }}')" 
                                        class="px-2 py-1 text-sm rounded bg-gray-700 text-yellow-400 hover:bg-gray-600">
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