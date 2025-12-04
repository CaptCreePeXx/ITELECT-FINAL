@if($allHistoryAppointments->count() > 0)
    <div class="bg-gray-800 bg-opacity-90 shadow-lg rounded-2xl p-6 border border-yellow-400/30 mt-10">
        
        {{-- History Header (Toggle Switch) --}}
        <button onclick="toggleHistory()" class="w-full text-left flex justify-between items-center p-2 rounded-lg hover:bg-gray-700/50 transition mb-4">
            <h2 class="text-2xl font-bold text-yellow-400">
                📅 Appointment History 
                <span class="text-lg font-medium text-gray-400 ml-3">
                    ({{ $allHistoryAppointments->count() }} records total)
                </span>
            </h2>
            {{-- Toggle Icon (Rotates when open) --}}
            <svg id="historyToggleIcon" class="w-6 h-6 text-yellow-400 transform transition-transform duration-300 rotate-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>

        {{-- Collapsible Content with In-Header Filter (Starts Hidden) --}}
        <div id="historyContent" class="hidden mt-4"> 
            <div class="overflow-x-auto rounded-xl shadow-lg">
                <table class="min-w-full text-yellow-300">
                    <thead class="bg-gray-900 text-yellow-400 uppercase text-sm tracking-wider">
                        <tr>
                            <th class="py-3 px-6 text-left">Date</th>
                            <th class="py-3 px-6 text-left">Time</th>
                            <th class="py-3 px-6 text-left">Service</th>
                            
                            {{-- Status Header with Dropdown Filter --}}
                            <th id="statusFilterHeader" class="py-3 px-6 text-left relative z-20">
                                <div class="flex items-center space-x-1 cursor-pointer" onclick="toggleFilterDropdown(event)">
                                    <span>Status</span>
                                    <svg class="w-4 h-4 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                                
                                {{-- Dropdown Menu --}}
                                <div id="filterDropdown" class="absolute left-0 mt-2 w-48 bg-gray-700 rounded-md shadow-lg hidden">
                                    <a href="#" class="block px-4 py-2 text-sm text-yellow-300 hover:bg-gray-600" onclick="selectFilter(event, 'all')">All History</a>
                                    <a href="#" class="block px-4 py-2 text-sm text-yellow-300 hover:bg-gray-600" onclick="selectFilter(event, 'completed')">Completed</a>
                                    <a href="#" class="block px-4 py-2 text-sm text-yellow-300 hover:bg-gray-600" onclick="selectFilter(event, 'declined')">Declined</a>
                                    <a href="#" class="block px-4 py-2 text-sm text-yellow-300 hover:bg-gray-600" onclick="selectFilter(event, 'cancelled')">Cancelled</a>
                                </div>
                            </th>
                            {{-- End Status Header --}}

                            <th class="py-3 px-6 text-left">Dentist</th>
                            {{-- Updated Header Text for consistency --}}
                            <th class="py-3 px-6 text-center">Note/Reason</th> 
                        </tr>
                    </thead>
                    {{-- All History Data --}}
                    <tbody id="allHistoryTableBody" class="divide-y divide-gray-600">
                        @foreach($allHistoryAppointments as $appointment)
                            @php
                                // Determine the specific status key for filtering
                                $statusKey = $appointment->is_cancelled ? 'cancelled' : strtolower($appointment->status);
                                // Determine the display status and color
                                $displayStatus = $appointment->is_cancelled ? 'Cancelled' : ucfirst($appointment->status);
                                
                                $bgColor = match($statusKey) {
                                    'completed' => 'bg-blue-400',
                                    'declined' => 'bg-red-500', 
                                    'cancelled' => 'bg-red-400',
                                    default => 'bg-gray-400',
                                };

                                // Get the relevant text for the modal, preferring cancel_reason if available
                                $noteContent = $appointment->cancel_reason ?? $appointment->note;
                            @endphp

                            <tr class="hover:bg-gray-700/60 transition" 
                                data-status="{{ $statusKey }}">
                                <td class="py-3 px-6">{{ $appointment->date }}</td>
                                <td class="py-3 px-6">{{ \Carbon\Carbon::parse($appointment->time)->format('h:i A') }}</td>
                                <td class="py-3 px-6">{{ $appointment->service }}</td>
                                <td class="py-3 px-6">
                                    <span class="px-2 py-1 rounded-md font-semibold text-sm text-black {{ $bgColor }}">
                                        {{ $displayStatus }}
                                    </span>
                                </td>
                                <td class="py-3 px-6">{{ $appointment->dentist->name ?? 'N/A' }}</td>
                                
                                {{-- 🔑 NEW: Note/Reason View Button --}}
                                <td class="py-3 px-6 text-center">
                                    @if($noteContent)
                                        {{-- We pass the combined note/reason content to the existing JS function --}}
                                        <button type="button" 
                                            onclick="openPatientNoteModal('{{ addslashes($noteContent) }}')"
                                            class="px-2 py-1 rounded bg-transparent border border-yellow-300 text-white hover:bg-yellow-300/80 hover:text-black text-sm active:bg-yellow-400/20 active:text-white">
                                            View
                                        </button>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif