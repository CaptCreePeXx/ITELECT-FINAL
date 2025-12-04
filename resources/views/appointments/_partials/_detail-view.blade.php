@php
    // Determine status and set display variables
    $isPending = $appointment->status === 'Pending' && !$appointment->is_cancelled;
    $isAccepted = $appointment->status === 'Accepted' && !$appointment->is_cancelled;
    $isHistory = $appointment->status !== 'Pending' && $appointment->status !== 'Accepted';
    $isCancelled = $appointment->is_cancelled;

    $displayStatus = $isCancelled ? 'Cancelled' : ucfirst($appointment->status);

    $statusColor = match(strtolower($displayStatus)) {
        'pending' => 'bg-yellow-400 text-black',
        'accepted' => 'bg-green-500 text-black',
        'completed' => 'bg-blue-400 text-black',
        'declined' => 'bg-red-500 text-white',
        'cancelled' => 'bg-red-400 text-white',
        default => 'bg-gray-500 text-white',
    };

    // Get the note or reason for the View button
    $noteContent = $appointment->note ?? $appointment->cancel_reason;
@endphp

<div class="h-full flex flex-col overflow-y-auto">
    
    {{-- Header and Status --}}
    <div class="border-b border-gray-700 pb-4 mb-4 flex justify-between items-start">
        <div>
            <h3 class="text-3xl font-bold text-yellow-400 mb-1">{{ $appointment->service }}</h3>
            <p class="text-lg text-gray-300">
                <span class="font-semibold">{{ \Carbon\Carbon::parse($appointment->date)->format('F j, Y') }}</span> 
                at 
                <span class="font-semibold">{{ \Carbon\Carbon::parse($appointment->time)->format('h:i A') }}</span>
            </p>
        </div>
        <span class="px-3 py-1 rounded-full text-base font-extrabold {{ $statusColor }}">
            {{ $displayStatus }}
        </span>
    </div>

    {{-- Core Details --}}
    <div class="grid grid-cols-2 gap-y-4 gap-x-8 text-white mb-6">
        
        <div class="space-y-1">
            <p class="text-sm font-semibold text-gray-400">Dentist</p>
            <p class="text-base font-medium">{{ $appointment->dentist->name ?? 'Not Assigned' }}</p>
        </div>

        <div class="space-y-1">
            <p class="text-sm font-semibold text-gray-400">Duration</p>
            <p class="text-base font-medium">{{ $appointment->service_duration }} minutes</p>
        </div>
        
        @if ($isCancelled)
            <div class="space-y-1 col-span-2">
                <p class="text-sm font-semibold text-gray-400">Cancellation Date</p>
                <p class="text-base font-medium">{{ $appointment->updated_at->format('M j, Y h:i A') }}</p>
            </div>
        @endif
        
    </div>

    {{-- Note/Reason Section --}}
    <div class="bg-gray-700 p-4 rounded-lg shadow-inner flex justify-between items-center mb-6">
        <p class="text-sm font-semibold text-yellow-400 uppercase tracking-wider">
            Appointment Note / Reason
        </p>
        @if ($noteContent)
            <button type="button" 
                onclick="openPatientNoteModal('{{ addslashes($noteContent) }}')"
                class="px-3 py-1 rounded bg-yellow-400 text-black hover:bg-yellow-300 text-sm font-semibold transition">
                View Note
            </button>
        @else
            <span class="text-gray-400 text-sm italic">No specific note or reason recorded.</span>
        @endif
    </div>

    {{-- Action Buttons --}}
    <div class="mt-auto border-t border-gray-700 pt-4 flex space-x-4 justify-end">
        
        @if ($isPending)
            {{-- Pending Actions --}}
            <a href="{{ route('appointments.edit', $appointment->id) }}"
               class="px-4 py-2 rounded font-semibold text-black bg-yellow-400 hover:bg-yellow-300 transition">
                ✏️ Edit Appointment
            </a>
            <button type="button" 
                    onclick="openDeleteModal({{ $appointment->id }})"
                    class="px-4 py-2 rounded font-semibold text-white border border-red-500 hover:bg-red-500/20 transition">
                🗑️ Delete
            </button>
            
        @elseif ($isAccepted)
            {{-- Accepted Actions --}}
            <button type="button" 
                    onclick="openCancelModal({{ $appointment->id }})"
                    class="px-4 py-2 rounded font-semibold text-white border border-red-500 hover:bg-red-500/20 transition">
                ❌ Request Cancellation
            </button>
            
        @elseif ($isHistory)
            {{-- History View (No actions, purely informational) --}}
            <span class="text-gray-400 italic">This appointment is closed.</span>
        @endif

    </div>
</div>