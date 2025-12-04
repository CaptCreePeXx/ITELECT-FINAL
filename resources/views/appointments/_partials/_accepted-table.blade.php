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