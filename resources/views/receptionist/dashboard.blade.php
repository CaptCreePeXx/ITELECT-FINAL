<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-yellow-400 leading-tight text-center">
            🦷 Receptionist Dashboard
        </h2>
    </x-slot>

    {{-- Main Content Container with Tabbed Interface --}}
    <div class="py-12 bg-gray-900 min-h-screen">
        {{-- Alpine.js State for Tab Management --}}
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8 space-y-8" x-data="{ activeTab: 'pending', activeHistoryTab: 'completed' }">

            {{-- Tab Navigation (Include) --}}
            @include('receptionist.dashboard._tab-navigation', [
                'pendingAppointments' => $pendingAppointments,
                'activeAppointments' => $activeAppointments,
                'ongoingAppointments' => $ongoingAppointments,
                'cancellationRequests' => $cancellationRequests,
            ])
            
            {{-- Tab Content Containers (Includes) --}}
            <div class="p-0">
                
                {{-- Pending Appointments Tab --}}
                @include('receptionist.dashboard.tabs.pending-appointments', ['appointments' => $pendingAppointments, 'dentists' => $dentists])

                {{-- Active Appointments Tab --}}
                @include('receptionist.dashboard.tabs.active-appointments', ['appointments' => $activeAppointments, 'dentists' => $dentists])

                {{-- Ongoing Appointments Tab --}}
                @include('receptionist.dashboard.tabs.ongoing-appointments', ['appointments' => $ongoingAppointments])

                {{-- Cancellation Requests Tab --}}
                @include('receptionist.dashboard.tabs.cancellation-requests', ['appointments' => $cancellationRequests])
                
                {{-- Combined Appointment History Tab --}}
                @include('receptionist.dashboard.tabs.history-appointments', [
                    'completedAppointments' => $completedAppointments,
                    'declinedAppointments' => $declinedAppointments,
                    'cancelledAppointments' => $cancelledAppointments,
                ])

            </div>
        </div>
    </div>

    {{-- Note Modal (Include) --}}
    @include('receptionist.dashboard.modals.note-modal')

    {{-- Success Message (Alpine.js) --}}
    @if (session('success'))
        <div 
            x-data="{ show: false }"
            x-init="setTimeout(() => show = true, 100); setTimeout(() => show = false, 2800)" 
            x-show="show"
            x-cloak
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-4"
            class="fixed top-4 right-4 z-50 p-4 rounded-lg bg-green-500 text-white shadow-xl max-w-sm"
        >
            {{ session('success') }}
        </div>
    @endif
    
    {{-- NOTE: Ensure 'resources/js/receptionist-dashboard.js' is loaded by your main layout (e.g., using Vite or Mix) --}}
</x-app-layout>