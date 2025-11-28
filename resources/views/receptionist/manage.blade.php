<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-yellow-400 leading-tight text-center">
            {{ __('Manage Appointments') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-900 min-h-screen">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 bg-opacity-90 shadow-lg rounded-2xl p-6 border border-yellow-400/30">
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
                                    <th class="py-3 px-6 text-left">Status</th>
                                    <th class="py-3 px-6 text-left">Note</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-600">
                                @foreach($appointments as $appointment)
                                    <tr class="hover:bg-gray-700/60 transition">
                                        <td class="py-3 px-6">{{ $appointment->patient->id ?? 'N/A' }}</td>
                                        <td class="py-3 px-6">{{ $appointment->patient->name ?? 'N/A' }}</td>
                                        <td class="py-3 px-6">{{ $appointment->patient->email ?? 'N/A' }}</td>
                                        <td class="py-3 px-6">{{ $appointment->date }}</td>
                                        <td class="py-3 px-6">{{ \Carbon\Carbon::parse($appointment->time)->format('h:i A') }}</td>
                                        <td class="py-3 px-6">{{ $appointment->service }}</td>
                                        <td class="py-3 px-6">{{ ucfirst($appointment->status) }}</td>
                                        <td class="py-3 px-6">
                                            <form action="" method="POST" class="flex gap-2">
                                                @csrf
                                                <input type="text" name="note" placeholder="Leave a note..." 
                                                       class="w-full px-2 py-1 rounded text-white bg-transparent border border-gray-100" 
                                                       value="{{ $appointment->note ?? '' }}">
                                                <button formaction="{{ route('appointments.accept', $appointment->id) }}" 
                                                        class="text-white px-3 py-1 rounded hover:bg-yellow-400/90 hover:text-black bg-transparent border border-gray-100">
                                                    Accept
                                                </button>
                                                <button formaction="{{ route('appointments.decline', $appointment->id) }}" 
                                                        class="bg-red-700 text-white px-3 py-1 rounded hover:bg-red-500">
                                                    Decline
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-yellow-400 mt-6 text-center text-lg">
                        No pending appointments.
                    </p>
                @endif
            </div>
        </div>
    </div>

    @include('partials.footer')
</x-app-layout>
