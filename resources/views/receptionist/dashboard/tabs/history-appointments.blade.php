<div x-show="activeTab === 'history'" x-cloak class="bg-gray-800 bg-opacity-90 shadow-lg rounded-b-2xl rounded-tr-2xl p-6 border border-yellow-400/30">
    <h3 class="text-yellow-400 font-bold mb-4">Appointment History</h3>

    {{-- Internal Filter Buttons --}}
    <div class="mb-4 flex space-x-4">
        <button @click="activeHistoryTab = 'completed'" :class="{ 'bg-green-400 text-gray-900': activeHistoryTab === 'completed', 'text-yellow-400 hover:text-yellow-300 border border-yellow-400': activeHistoryTab !== 'completed' }" class="py-2 px-4 rounded-lg transition-colors duration-200 text-sm">
            Completed ({{ $completedAppointments->count() }})
        </button>
        <button @click="activeHistoryTab = 'declined'" :class="{ 'bg-red-600 text-white': activeHistoryTab === 'declined', 'text-yellow-400 hover:text-yellow-300 border border-yellow-400': activeHistoryTab !== 'declined' }" class="py-2 px-4 rounded-lg transition-colors duration-200 text-sm">
            Declined ({{ $declinedAppointments->count() }})
        </button>
        <button @click="activeHistoryTab = 'cancelled'" :class="{ 'bg-gray-500 text-white': activeHistoryTab === 'cancelled', 'text-yellow-400 hover:text-yellow-300 border border-yellow-400': activeHistoryTab !== 'cancelled' }" class="py-2 px-4 rounded-lg transition-colors duration-200 text-sm">
            Cancelled ({{ $cancelledAppointments->count() }})
        </button>
    </div>

    {{-- 1. Completed Appointments --}}
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
                                <td class="py-2 px-4">{{ $appointment->note ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-yellow-400 mt-6 text-center text-lg">No completed appointments found.</p>
        @endif
    </div>

    {{-- 2. Declined Appointments --}}
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
    
    {{-- 3. Cancelled Appointments --}}
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