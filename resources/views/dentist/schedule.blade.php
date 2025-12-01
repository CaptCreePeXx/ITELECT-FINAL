@extends('layouts.app')

@section('content')
<h1 class="text-3xl font-bold text-yellow-400 mb-6">My Schedule</h1>

<table class="w-full text-left text-gray-300">
    <thead>
        <tr class="border-b border-gray-600">
            <th class="p-2">Patient</th>
            <th class="p-2">Date</th>
            <th class="p-2">Time</th>
            <th class="p-2">Status</th>
        </tr>
    </thead>
    <tbody>
        @forelse($appointments as $appointment)
            <tr class="border-b border-gray-700">
                <td class="p-2">{{ $appointment->patient->name }}</td>
                <td class="p-2">{{ $appointment->date }}</td>
                <td class="p-2">{{ $appointment->time }}</td>
                <td class="p-2">{{ $appointment->status }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="p-2 text-center text-gray-400">No appointments yet.</td>
            </tr>
        @endforelse
    </tbody>
</table>
@endsection
