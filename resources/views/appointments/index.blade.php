<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-yellow-400 leading-tight text-center">
            {{ __('🦷 My Appointments') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 bg-opacity-90 shadow-lg rounded-2xl p-6 border border-yellow-400/30">

                <!-- Header & Book Appointment Button -->
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-yellow-400">Manage Appointments</h1>
                    <a href="{{ route('appointments.create') }}"
                       class="bg-yellow-400 text-black px-4 py-2 rounded-lg font-semibold hover:bg-yellow-200 transition">
                        Book Appointment
                    </a>
                </div>

                @if($appointments->count() > 0)
                    <div class="overflow-x-auto rounded-xl shadow-lg">
                        <table class="min-w-full text-yellow-300">
                            <thead class="bg-gray-900 text-yellow-400 uppercase text-sm tracking-wider">
                                <tr>
                                    <th class="py-3 px-6 text-left">Date</th>
                                    <th class="py-3 px-6 text-left">Time</th>
                                    <th class="py-3 px-6 text-left">Service</th>
                                    <th class="py-3 px-6 text-left">Status</th>
                                    <th class="py-3 px-6 text-left">Note</th>
                                    <th class="py-3 px-6 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-600">
                                @foreach($appointments as $appointment)
                                    <tr class="hover:bg-gray-700/60 transition">
                                        <td class="py-3 px-6">{{ $appointment->date }}</td>
                                        <td class="py-3 px-6">{{ $appointment->time }}</td>
                                        <td class="py-3 px-6">{{ $appointment->service }}</td>
                                        <td class="py-3 px-6">
                                            <span @class([
                                                'px-2 py-1 rounded-md font-semibold text-sm',
                                                'bg-yellow-400 text-black' => $appointment->status === 'Pending',
                                                'bg-green-400 text-black' => $appointment->status === 'Accepted',
                                                'bg-red-400 text-black'   => $appointment->status === 'Declined',
                                                'bg-gray-400 text-black'  => !in_array($appointment->status, ['Pending','Accepted','Declined']),
                                            ])>
                                                {{ ucfirst($appointment->status) }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-6">
                                            {{ $appointment->note ?? '. . .' }}
                                        </td>
                                        <td class="py-3 px-6 flex justify-center gap-3">
    @if($appointment->status === 'Pending')
        <a href="{{ route('appointments.edit', $appointment->id) }}"
           class="text-yellow-400 hover:text-yellow-500 font-medium transition">
            Edit
        </a>
        <form action="{{ route('appointments.destroy', $appointment->id) }}"
              method="POST"
              onsubmit="return confirm('Are you sure you want to delete this appointment?')">
            @csrf
            @method('DELETE')
            <button type="submit"
                    class="text-red-400 hover:text-red-500 font-medium transition">
                Cancel
            </button>
        </form>
    @else
        <span class="text-gray-400 font-medium">-</span>
    @endif
</td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-yellow-400 mt-6 text-center text-lg">
                        No appointments booked yet.
                    </p>
                @endif

            </div>
        </div>
    </div>

    @include('partials.footer')
</x-app-layout>
