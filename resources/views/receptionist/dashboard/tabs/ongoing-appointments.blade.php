<div x-show="activeTab === 'ongoing'" x-cloak class="bg-gray-800 bg-opacity-90 shadow-lg rounded-b-2xl rounded-tr-2xl p-6 border border-yellow-400/30">
    <h3 class="text-yellow-400 font-bold mb-4">Ongoing Appointments (Today)</h3>
    @if($appointments->count() > 0)
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
                    @foreach($appointments as $appointment)
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