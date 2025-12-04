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