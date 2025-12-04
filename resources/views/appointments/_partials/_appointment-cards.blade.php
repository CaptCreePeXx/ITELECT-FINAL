@if($appointments->count() > 0)
    {{-- This section is temporary, replaced by JavaScript's generateAppointmentCards on DOMContentLoaded --}}
    @foreach($appointments as $appointment)
        @php
            $statusText = $appointment->is_cancelled ? 'Cancelled' : $appointment->status;
            $statusColor = match($statusText) {
                'Pending' => 'bg-yellow-400',
                'Accepted' => 'bg-green-500',
                'Cancelled', 'Declined' => 'bg-red-500',
                default => 'bg-gray-500',
            };
            $displayDate = \Carbon\Carbon::parse($appointment->date)->format('M j');
            $displayTime = \Carbon\Carbon::parse($appointment->time)->format('h:i A');
        @endphp
        
        <div class="appointment-card bg-gray-700/50 p-3 rounded-lg mb-3 cursor-pointer hover:bg-gray-700 transition 
            {{ $loop->first && $currentView === 'active' ? 'border-2 border-yellow-400' : '' }}" 
            onclick="loadAppointmentDetails({{ $appointment->id }})" 
            data-id="{{ $appointment->id }}">
            <div class="flex justify-between items-start">
                <div class="flex items-center space-x-3">
                    <div class="text-xl font-bold text-yellow-300">{{ $displayDate }}</div>
                    <div>
                        <p class="text-base text-white font-semibold">{{ $appointment->service }}</p>
                        <p class="text-xs text-gray-400">{{ $displayTime }} with Dr. {{ $appointment->dentist->name ?? 'N/A' }}</p>
                    </div>
                </div>
                <span class="px-2 py-1 rounded-full text-xs font-semibold text-black {{ $statusColor }}">
                    {{ $statusText }}
                </span>
            </div>
        </div>
    @endforeach
@else
    <p class="text-gray-400 text-center mt-6">No active appointments found.</p>
@endif