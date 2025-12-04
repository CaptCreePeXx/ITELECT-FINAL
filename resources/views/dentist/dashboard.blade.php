<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-yellow-400 leading-tight text-center">
            🦷 Dentist Daily Overview
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 text-yellow-300">

            <!-- Welcome Section -->
            <div class="mb-8 p-6 bg-gray-800 rounded-xl shadow-2xl border-l-4 border-yellow-500">
                <h3 class="text-3xl font-bold mb-2 text-white">Good morning, Dr. {{ Auth::user()->name ?? 'Dentist' }}!</h3>
                <p class="text-gray-400">Here is your complete schedule for the day and the week ahead.</p>
            </div>
            
            <!-- Dashboard Statistics (Concise Overview) -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                
                @php
                    $statItems = [
                        ['title' => "Today's Patients", 'value' => $stats['today'], 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-3 text-yellow-400"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Z" /></svg>'],
                        ['title' => "Completed", 'value' => $stats['completed'], 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-3 text-green-400"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>'],
                        ['title' => "Pending", 'value' => $stats['pending'], 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-3 text-yellow-500"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.021 3.375 1.774 3.375h14.456c1.753 0 2.64-1.875 1.774-3.375l-7.277-12.628a1.5 1.5 0 0 0-2.676 0L12 9Z" /></svg>'],
                        ['title' => "Upcoming (Week)", 'value' => $stats['upcoming'], 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-3 text-blue-400"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" /></svg>'],
                    ];
                @endphp

                @foreach ($statItems as $item)
                    <div class="bg-gray-800 p-5 rounded-xl shadow border border-gray-700 hover:border-yellow-600 transition duration-300">
                        <div class="flex items-center mb-1">
                            {!! $item['icon'] !!}
                            <p class="text-sm font-semibold text-gray-400">{{ $item['title'] }}</p>
                        </div>
                        <h2 class="text-3xl font-extrabold text-white">{{ $item['value'] }}</h2>
                    </div>
                @endforeach
            </div>

            <!-- Helper function for Status Badges -->
            @php
            function getStatusBadge($status) {
                $status = strtolower($status);
                $class = match($status) {
                    'completed' => 'bg-green-600 text-white',
                    'pending' => 'bg-yellow-600 text-gray-900',
                    'accepted' => 'bg-blue-600 text-white',
                    default => 'bg-gray-500 text-white',
                };
                return "<span class=\"px-3 py-1 text-xs font-semibold rounded-full $class capitalize min-w-[70px]\">$status</span>";
            }
            @endphp

            <!-- Today's Appointments Table (Primary Focus) -->
            <h3 class="text-xl font-semibold mb-4 border-b border-gray-700 pb-2">📅 Today's Schedule ({{ date('F j, Y') }})</h3>

            @if($todaysAppointments->isEmpty())
                <div class="p-6 bg-gray-800 rounded-xl mb-10 border border-gray-700 text-gray-400">
                    <p class="text-lg font-medium">✨ Time for administrative work! No appointments scheduled for today.</p>
                </div>
            @else
                <div class="overflow-x-auto mb-10 shadow-2xl rounded-xl border border-gray-700">
                    <table class="min-w-full bg-gray-800 divide-y divide-gray-700">
                        <thead class="bg-gray-700 text-gray-300 uppercase tracking-wider">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-medium w-1/6">Time</th>
                                <th class="px-4 py-3 text-left text-sm font-medium w-2/6">Patient</th>
                                <th class="px-4 py-3 text-left text-sm font-medium w-2/6">Service</th>
                                <th class="px-4 py-3 text-center text-sm font-medium w-1/6">Status</th>
                                <th class="px-4 py-3 text-center text-sm font-medium w-1/6">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            @foreach($todaysAppointments as $appt)
                                <tr class="hover:bg-gray-700/70 transition duration-150 @if($loop->first) border-t-2 border-yellow-500 @endif">
                                    {{-- Time format change: 24-hour to 12-hour AM/PM --}}
                                    <td class="px-4 py-3 text-white font-extrabold">{{ date('g:i A', strtotime($appt->time)) }}</td>
                                    {{-- FIX: Accessing patient name via the relationship and name attribute --}}
                                    <td class="px-4 py-3">{{ $appt->patient->name ?? 'N/A' }}</td>
                                    <td class="px-4 py-3 text-gray-400">{{ $appt->service }}</td>
                                    <td class="px-4 py-3 text-center">
                                        {!! getStatusBadge($appt->status) !!}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        {{-- Updated 'Details' to open a modal instead of navigating --}}
                                        <button type="button" 
                                           onclick="showAppointmentDetails('{{ $appt->id }}', '{{ $appt->patient->name ?? 'N/A' }}', '{{ date('g:i A', strtotime($appt->time)) }}', '{{ $appt->service }}', '{{ $appt->status }}')"
                                           class="text-yellow-400 hover:text-yellow-300 transition font-medium text-sm border border-yellow-400/30 px-3 py-1 rounded-lg">
                                            Details
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            <!-- Upcoming Appointments (Next 7 Days) - Secondary Overview -->
            <h3 class="text-xl font-semibold mb-4 border-b border-gray-700 pb-2">🗓️ Upcoming Appointments (Next 7 Days)</h3>

            @if($upcomingAppointments->isEmpty())
                <p class="text-gray-400 mb-10">No upcoming appointments in the next 7 days.</p>
            @else
                <div class="overflow-x-auto mb-10 shadow-lg rounded-xl border border-gray-700">
                    <table class="min-w-full bg-gray-800 divide-y divide-gray-700">
                        <thead class="bg-gray-700 text-gray-300 uppercase tracking-wider">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-medium w-1/6">Date</th>
                                <th class="px-4 py-3 text-left text-sm font-medium w-1/6">Time</th>
                                <th class="px-4 py-3 text-left text-sm font-medium w-2/6 hidden sm:table-cell">Patient</th>
                                <th class="px-4 py-3 text-left text-sm font-medium w-2/6 hidden md:table-cell">Service</th>
                                <th class="px-4 py-3 text-center text-sm font-medium w-1/6">Status</th>
                                <th class="px-4 py-3 text-center text-sm font-medium w-1/6">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            @foreach($upcomingAppointments as $appt)
                                <tr class="hover:bg-gray-700/70 transition duration-150">
                                    <td class="px-4 py-3 font-medium">{{ $appt->date }}</td>
                                    {{-- Time format change: 24-hour to 12-hour AM/PM --}}
                                    <td class="px-4 py-3 text-white">{{ date('g:i A', strtotime($appt->time)) }}</td>
                                    {{-- FIX: Accessing patient name via the relationship and name attribute --}}
                                    <td class="px-4 py-3 hidden sm:table-cell">{{ $appt->patient->name ?? 'N/A' }}</td>
                                    <td class="px-4 py-3 hidden md:table-cell text-gray-400">{{ $appt->service }}</td>
                                    <td class="px-4 py-3 text-center">
                                        {!! getStatusBadge($appt->status) !!}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        {{-- 'Prepare' remains a navigation link for complex, pre-clinical planning --}}
                                        <a href="{{ route('dentist.appointment.view', $appt->id) }}"
                                           class="text-yellow-400 hover:text-yellow-300 transition font-medium text-sm">
                                            <!--Prepare-->
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

        </div>
    </div>

    <!-- Appointment Details Modal (Hidden by default) -->
    <div id="detailsModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-75 z-50 overflow-y-auto" onclick="if(event.target.id === 'detailsModal') hideAppointmentDetails()">
        <div class="flex items-center justify-center min-h-screen px-4 py-6">
            {{-- Added 'relative' here for the absolute close button to work --}}
            <div class="bg-gray-800 rounded-xl shadow-2xl w-full max-w-lg p-6 transform transition-all duration-300 border border-yellow-500 relative">
                
                {{-- Updated Close Button to match the provided style --}}
                <button onclick="hideAppointmentDetails()" class="absolute -top-3 -right-3 text-yellow-400 font-bold text-3xl 
                    bg-gray-800 rounded-full w-10 h-10 flex items-center justify-center 
                    hover:bg-yellow-400 hover:text-black transition">&times;</button>

                <!-- Modal Header -->
                <div class="flex justify-between items-center pb-3 border-b border-gray-700 mb-4">
                    <h3 id="modalTitle" class="text-2xl font-bold text-white">Appointment Details</h3>
                </div>

                <!-- Modal Content -->
                <div id="modalContent" class="text-gray-300 space-y-4">
                    <!-- Content will be populated by JavaScript -->
                    <p class="text-center">Loading details...</p>
                </div>

                <!-- Modal Footer (Action buttons) -->
                <div class="mt-6 pt-4 border-t border-gray-700">
                    <button onclick="hideAppointmentDetails()" class="w-full bg-gray-700 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
                        Close / Back to Schedule
                    </button>
                </div>

            </div>
        </div>
    </div>
    
    <script>
        // --- Core Local Storage Logic ---
        
        // Key generator for localStorage
        function getNoteKey(appointmentId) {
            return `dentist_note_${appointmentId}`;
        }

        /**
         * Saves the content of the structured notes (S, O, P) to localStorage instantly as a JSON string.
         */
        function saveNotes(appointmentId) {
            // Get all three input fields
            const subjectiveArea = document.getElementById('noteSubjective');
            const objectiveArea = document.getElementById('noteObjective');
            const planArea = document.getElementById('notePlan');
            const saveButton = document.getElementById('saveNotesButton');
            
            saveButton.textContent = 'Saving...';
            saveButton.disabled = true;

            try {
                // 1. Create the structured data object
                const notesData = {
                    subjective: subjectiveArea.value,
                    objective: objectiveArea.value,
                    plan: planArea.value,
                };
                
                // 2. Save the JSON string to localStorage
                localStorage.setItem(getNoteKey(appointmentId), JSON.stringify(notesData));
                
                // 3. Update the "original" content on all fields to track changes
                subjectiveArea.dataset.originalContent = notesData.subjective;
                objectiveArea.dataset.originalContent = notesData.objective;
                planArea.dataset.originalContent = notesData.plan;

                saveButton.textContent = 'Saved!';
                setTimeout(() => {
                    // Reset button state/text after a brief display
                    if (saveButton.textContent === 'Saved!') {
                        saveButton.disabled = true; // Keep disabled if content hasn't changed
                    }
                }, 1500);

            } catch (error) {
                console.error("Error saving notes to local storage:", error);
                saveButton.textContent = 'Save Failed';
                saveButton.disabled = false;
            }
        }

        /**
         * Loads existing structured notes from localStorage for the appointment.
         */
        function loadNotes(appointmentId) {
            const subjectiveArea = document.getElementById('noteSubjective');
            const objectiveArea = document.getElementById('noteObjective');
            const planArea = document.getElementById('notePlan');
            const saveButton = document.getElementById('saveNotesButton');
            
            // 1. Get the existing JSON string from localStorage
            const storedJson = localStorage.getItem(getNoteKey(appointmentId));
            let storedNotes = { subjective: "", objective: "", plan: "" };

            if (storedJson) {
                try {
                    // Try to parse the JSON
                    storedNotes = JSON.parse(storedJson);
                } catch (e) {
                    console.error("Error parsing stored notes JSON:", e);
                    // Fallback in case of old data format
                    // Since the previous version only had one field named 'realTimeNotes', we'll put all old data in 'subjective'
                    storedNotes = { subjective: storedJson, objective: "", plan: "" };
                }
            }

            // Helper function to check if any field is modified
            const checkModification = () => {
                const isModified = (
                    subjectiveArea.value !== subjectiveArea.dataset.originalContent ||
                    objectiveArea.value !== objectiveArea.dataset.originalContent ||
                    planArea.value !== planArea.dataset.originalContent
                );
                saveButton.disabled = !isModified;
                if (isModified && (saveButton.textContent === 'Saved!' || saveButton.textContent === 'Save Failed')) {
                    saveButton.textContent = 'Save Notes';
                }
            };

            // 2. Set the textarea values and original content
            subjectiveArea.value = storedNotes.subjective || "";
            objectiveArea.value = storedNotes.objective || "";
            planArea.value = storedNotes.plan || "";
            
            subjectiveArea.dataset.originalContent = subjectiveArea.value;
            objectiveArea.dataset.originalContent = objectiveArea.value;
            planArea.dataset.originalContent = planArea.value;

            // 3. Setup the input listeners on all fields
            subjectiveArea.oninput = checkModification;
            objectiveArea.oninput = checkModification;
            planArea.oninput = checkModification;
            
            // 4. Initial state: disabled
            saveButton.disabled = true; 
        }

        // --- UI Logic ---
        
        /**
         * Hides the details modal.
         */
        function hideAppointmentDetails() {
            const modal = document.getElementById('detailsModal');
            
            // Clean up the input listeners when closing
            const subjectiveArea = document.getElementById('noteSubjective');
            const objectiveArea = document.getElementById('noteObjective');
            const planArea = document.getElementById('notePlan');
            
            if (subjectiveArea) subjectiveArea.oninput = null;
            if (objectiveArea) objectiveArea.oninput = null;
            if (planArea) planArea.oninput = null;

            modal.classList.add('hidden');
        }

        /**
         * Simulates marking an appointment as complete.
         */
        function markAppointmentComplete(appointmentId) {
            console.log('Marking appointment ' + appointmentId + ' as completed. (Simulated)');
            
            const hideModal = window.hideAppointmentDetails || (() => {}); 

            const modalTitle = document.getElementById('modalTitle');
            modalTitle.textContent = 'Appointment Completed!';
            
            const content = document.getElementById('modalContent');
            content.innerHTML = `
                <div class="p-4 bg-green-900/50 rounded-lg text-white text-center">
                    <p class="text-xl font-bold mb-2">Success!</p>
                    <p>The system has logged Appointment ID ${appointmentId} as complete.</p>
                </div>
            `;
            
            // Hide action buttons after completion 
            const footer = document.querySelector('#detailsModal .mt-6.pt-4.border-t.border-gray-700');
            if (footer) {
                footer.innerHTML = '';
            }
            
            // Re-add simple close button
            const closeButton = document.createElement('button');
            closeButton.onclick = hideModal;
            closeButton.className = 'w-full bg-gray-700 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg transition duration-200';
            closeButton.textContent = 'Close';
            const newFooter = document.createElement('div');
            newFooter.className = "mt-6 pt-4 border-t border-gray-700";
            newFooter.appendChild(closeButton);
            document.querySelector('#detailsModal .max-w-lg').appendChild(newFooter);
        }

        /**
         * Shows the appointment details modal and initiates note loading.
         */
        function showAppointmentDetails(id, patientName, time, service, status) {
            const modal = document.getElementById('detailsModal');
            const title = document.getElementById('modalTitle');
            const content = document.getElementById('modalContent');
            
            title.textContent = `Appointment: ${patientName} (${time})`;
            
            // Build the content dynamically with structured SOAP fields
            content.innerHTML = `
                <div class="p-4 bg-gray-700/50 rounded-lg shadow-inner">
                    <p class="text-lg font-bold text-white mb-2">${service}</p>
                    <div class="grid grid-cols-2 gap-y-2 text-sm">
                        <p><strong class="text-gray-400">Time:</strong></p>
                        <p class="text-white font-medium">${time}</p>
                        
                        <p><strong class="text-gray-400">Status:</strong></p>
                        <p><span class="font-bold uppercase text-yellow-300">${status}</span></p>
                        
                        <p><strong class="text-gray-400">Patient:</strong></p>
                        <p>${patientName}</p>
                    </div>
                </div>
                
                <div class="mt-4 pt-4 border-t border-gray-700">
                    <h4 class="font-semibold text-white mb-2">Clinical Notes</h4>
                    
                    <label for="noteSubjective" class="block text-sm font-medium text-gray-400 mt-2">1. Subjective (Complaint, Symptoms)</label>
                    <textarea id="noteSubjective" rows="3" class="mt-1 w-full bg-gray-700 border-gray-600 rounded-lg text-white p-3 focus:ring-yellow-500 focus:border-yellow-500" placeholder="Patient reported sharp pain in molar region..."></textarea>
                    
                    <label for="noteObjective" class="block text-sm font-medium text-gray-400 mt-2">2. Objective (Clinical Findings, Tests)</label>
                    <textarea id="noteObjective" rows="4" class="mt-1 w-full bg-gray-700 border-gray-600 rounded-lg text-white p-3 focus:ring-yellow-500 focus:border-yellow-500" placeholder="Tooth #30 shows deep decay, percussion test negative, no swelling..."></textarea>
                    
                    <label for="notePlan" class="block text-sm font-medium text-gray-400 mt-2">3. Assessment & Plan (Diagnosis, Treatment)</label>
                    <textarea id="notePlan" rows="4" class="mt-1 w-full bg-gray-700 border-gray-600 rounded-lg text-white p-3 focus:ring-yellow-500 focus:border-yellow-500" placeholder="Diagnosis: Pulpalgia. Plan: Recommend RCT on #30. Scheduled follow-up in 2 weeks."></textarea>

                    <div class="flex space-x-3 mt-3">
                         <button id="saveNotesButton" 
                                 onclick="saveNotes('${id}')"
                                 disabled 
                                 class="flex-1 bg-yellow-600 hover:bg-yellow-700 text-gray-900 font-bold py-2 px-4 rounded-lg transition duration-200 shadow-md disabled:opacity-50 disabled:cursor-not-allowed">
                            Save Notes
                        </button>
                        <button onclick="markAppointmentComplete('${id}')"
                                class="flex-1 bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200 shadow-md">
                            Mark Complete
                        </button>
                    </div>
                </div>
            `;
            
            modal.classList.remove('hidden');
            
            // Start loading notes immediately after rendering the content
            loadNotes(id);
        }
        
        // Expose functions globally for HTML buttons
        window.showAppointmentDetails = showAppointmentDetails;
        window.markAppointmentComplete = markAppointmentComplete;
        window.saveNotes = saveNotes; 
        window.hideAppointmentDetails = hideAppointmentDetails;
        window.loadNotes = loadNotes;
    </script>
</x-app-layout>