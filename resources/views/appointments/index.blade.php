<x-app-layout>
    <x-slot name="header">
        {{-- Updated Header for better spacing and professional look --}}
        <div class="flex justify-between items-center px-4 sm:px-6 lg:px-8 w-full"> 
            
            {{-- Main Title --}}
            <h2 class="font-extrabold text-2xl text-amber-400 leading-tight">
                🦷 {{ __('My Appointments Dashboard') }}
            </h2>

            {{-- Notification Center Trigger (Far Right) --}}
            <button onclick="openNotificationCenter()" class="flex items-center space-x-1 text-gray-400 hover:text-amber-400 transition focus:outline-none relative">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L14 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 003-3H7a3 3 0 003 3z"></path>
                </svg>
                <span class="text-sm font-semibold hidden sm:inline">Alerts</span>
                
                {{-- Fixed red dot positioning: now amber-400 for unread --}}
                <span id="notificationBadge" class="absolute block h-3 w-3 rounded-full ring-2 ring-gray-900 bg-amber-400" style="top: -4px; right: -4px;"></span>
            </button>
            
        </div>
        {{-- CSRF Token for AJAX/Fetch requests (used by Cancel function) --}}
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </x-slot>

    {{-- 1. NOTIFICATION CENTER OVERLAY (Flyout Sidebar) --}}
    {{-- NOTE: Styles maintained, but text contrast improved for a professional feel --}}
    <div id="notificationCenter" class="hidden fixed top-0 right-0 h-full w-96 bg-gray-900/95 z-50 shadow-2xl transition-transform duration-300 transform translate-x-full overflow-y-auto p-6">
    
        <div class="flex justify-between items-center pb-4 border-b border-amber-400/50 mb-6">
            <h3 class="text-2xl font-bold text-amber-400">Notifications</h3>
            <button onclick="closeNotificationCenter()" class="text-gray-400 hover:text-white transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div id="notificationList" class="space-y-4">
            {{-- Placeholder Notification items --}}
            <div class="p-3 bg-gray-800 rounded-lg text-sm text-gray-300 border-l-4 border-amber-400/80">
                <p class="font-semibold">Appointment #123 Confirmed</p>
                <p class="text-xs">Your appointment for Dental Checkup on Dec 10th at 9:00 AM has been accepted.</p>
                <span class="text-xs text-amber-400/80 mt-1 block">3 minutes ago</span>
            </div>
            
            <div class="p-3 bg-gray-800 rounded-lg text-sm text-gray-300 border-l-4 border-red-500">
                <p class="font-semibold">Cancellation Request Denied</p>
                <p class="text-xs">Your request to cancel appointment #456 was denied by the administrator.</p>
                <span class="text-xs text-amber-400/80 mt-1 block">1 hour ago</span>
            </div>
        </div>
        
        <button class="w-full mt-8 py-2 bg-gray-700 text-gray-400 rounded hover:bg-gray-600 transition">
            Mark All as Read
        </button>
    </div>
    
    {{-- 2. MAIN SPLIT-PANEL LAYOUT --}}
    <div class="pt-4 pb-4 bg-gray-900">
        {{-- Reduced max-w for smaller card size and centered dashboard --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @php
                $pendingAppointments = $appointments->where('status', 'Pending')->where('is_cancelled', false);
                $acceptedAppointments = $appointments->where('status', 'Accepted')->where('is_cancelled', false);
                $completedAppointments = $appointments->where('status', 'Completed')->where('is_cancelled', false);
                $declinedAppointments = $appointments->where('status', 'Declined')->where('is_cancelled', false);
                $cancelledAppointments = $appointments->where('is_cancelled', true);
                
                $allHistoryAppointments = $cancelledAppointments
                    ->merge($declinedAppointments)
                    ->merge($completedAppointments)
                    ->sortByDesc('date');

                $activeAppointments = $pendingAppointments->merge($acceptedAppointments)->sortBy('date');
            @endphp
            
            {{-- Calcuated height for no scrollbar, slightly adjusted to 130px for more buffer --}}
            <div class="flex rounded-xl overflow-hidden shadow-2xl shadow-gray-950/50" style="height: calc(100vh - 300px);">

                {{-- MASTER PANEL (40% width - List View) --}}
                {{-- Border replaced with subtle shadow for division --}}
                <div id="masterPanel" 
                    class="w-2/5 bg-gray-900 p-6 flex flex-col shadow-2xl shadow-gray-900/70 z-10"> 
                    
                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-2xl font-extrabold text-white">My Schedule</h1>
                        <a href="{{ route('appointments.create') }}"
                            class="bg-amber-400 text-gray-950 px-4 py-2 rounded-full font-bold hover:bg-amber-300 transition text-sm shadow-lg">
                            + Book Appointment
                        </a>
                    </div>
                    
                    {{-- Navigation Tabs for Filtering the List (Assume partial is updated) --}}
                    @include('appointments._partials._master-tabs') 
                    
                    {{-- Appointment List Container --}}
                    <div id="appointmentListContainer" class="mt-4 overflow-y-auto flex-grow pr-3 space-y-3"> 
                        {{-- Content will be injected by JavaScript --}}
                    </div>
                </div>

                {{-- DETAIL PANEL (60% width - Focused Content Area) --}}
                {{-- Lighter background for focus, uses standard overflow-y-auto --}}
                <div id="detailPanel" class="w-3/5 bg-gray-800 p-8 flex justify-center items-center overflow-y-auto">
                    <div id="appointmentDetailsContent" class="w-full h-full">
                        <div class="text-center text-gray-400 text-lg">
                            <svg class="w-20 h-20 mx-auto mb-6 text-amber-400/70" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <h3 class="text-xl font-semibold text-white mb-2">Detailed View</h3>
                            <p class="font-medium">Select an appointment from the **My Schedule** panel to view comprehensive details and management options.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. Modals --}}
    @include('appointments._partials._modals')

    {{-- 4. JavaScript Logic (Updated for Redesign) --}}
    {{-- Remove the line <script src="{{ asset('js/app.js') }}"></script> --}}

<script>
    // --- DATA INITIALIZATION (Directly from PHP) ---
    // The data is now immediately available in the Blade file
    const allAppointmentsData = @json($appointments); 

    // --- HELPER FUNCTIONS ---
    const isNotCancelled = (app) => {
        const value = app.is_cancelled;
        return value === false || value === 0 || value === '0' || value === 'false' || value === null;
    }

    const isCancelled = (app) => {
        const value = app.is_cancelled;
        return value === true || value === 1 || value === '1' || value === 'true';
    }

    // Status Color Map
    const StatusMap = {
        'Pending': { border: 'border-amber-400', text: 'text-amber-400', icon: '⏳' },
        'Accepted': { border: 'border-green-500', text: 'text-green-500', icon: '✅' },
        'Completed': { border: 'border-blue-400', text: 'text-blue-400', icon: '📁' },
        'Declined': { border: 'border-red-500', text: 'text-red-500', icon: '❌' },
        'Cancelled': { border: 'border-red-500', text: 'text-red-500', icon: '❌' },
    };

    /**
     * Filters the appointments data based on the selected tab and updates the card list.
     * @param {string} view - 'active', 'pending', 'confirmed', or 'history'
     */
    function filterAppointments(view) {
        let filteredData;
        
        // Helper function for case-insensitive status check
        const lowerStatus = (app) => app.status ? app.status.toLowerCase() : '';

        // 1. Tab Styling Logic
        document.querySelectorAll('.tab-button').forEach(btn => {
            btn.classList.remove('bg-yellow-400', 'text-black', 'text-white', 
                                 'bg-gray-700/50', 'border-b-2', 'border-amber-400', 'font-semibold');
            
            btn.classList.add('text-gray-400', 'hover:text-amber-400');
        });

        const activeTab = document.getElementById(`tab-${view}`);

        if (activeTab) {
            activeTab.classList.remove('text-gray-400', 'hover:text-amber-400');
            activeTab.classList.add('bg-gray-700/50', 'border-b-2', 'border-amber-400', 'font-semibold', 'text-white');
        }

        // 2. Data Filtering Logic
        if (view === 'active') {
            filteredData = allAppointmentsData.filter(app => 
                (lowerStatus(app) === 'pending' || lowerStatus(app) === 'accepted') && isNotCancelled(app)
            );
        } else if (view === 'pending') {
            filteredData = allAppointmentsData.filter(app => 
                lowerStatus(app) === 'pending' && isNotCancelled(app)
            );
        } else if (view === 'confirmed') {
            filteredData = allAppointmentsData.filter(app => 
                lowerStatus(app) === 'accepted' && isNotCancelled(app)
            );
        } else if (view === 'history') {
            filteredData = allAppointmentsData.filter(app => 
                lowerStatus(app) === 'completed' || lowerStatus(app) === 'declined' || isCancelled(app)
            );
        } else {
            filteredData = allAppointmentsData;
        }

        // Sort the filtered data (e.g., by date descending for history, ascending for active)
        filteredData.sort((a, b) => {
            const dateA = new Date(a.date + ' ' + a.time);
            const dateB = new Date(b.date + ' ' + b.time);
            return view === 'history' ? dateB - dateA : dateA - dateB;
        });

        // 3. Update the List Container
        const listContainer = document.getElementById('appointmentListContainer');
        listContainer.innerHTML = generateAppointmentCards(filteredData, view);
        
        // 4. Load details of the first item 
        if (filteredData.length > 0) {
            if (typeof loadAppointmentDetails === 'function') {
                loadAppointmentDetails(filteredData[0].id);
            }
        } else {
            document.getElementById('appointmentDetailsContent').innerHTML = 
                '<div class="text-center text-gray-400 text-lg mt-20"><p>No appointments found in this category.</p><p class="text-sm mt-2">Select an appointment tab or book a new one to get started.</p></div>';
        }
    }

    /**
     * Generates HTML for appointment cards, implementing the new design.
     */
    function generateAppointmentCards(appointments, currentView) {
        if (appointments.length === 0) {
             return `
                <div class="text-center text-gray-500 mt-12 p-4 border border-dashed border-gray-700 rounded-lg">
                    <svg class="w-10 h-10 mx-auto mb-2 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <p class="font-semibold text-white">No appointments found.</p>
                    <p class="text-sm">This category is currently empty.</p>
                </div>
            `;
        }

        const today = new Date();
        today.setHours(0, 0, 0, 0);
        const tomorrow = new Date(today);
        tomorrow.setDate(today.getDate() + 1);
        const dayAfterTomorrow = new Date(today);
        dayAfterTomorrow.setDate(today.getDate() + 2);

        let html = '';
        let lastHeader = null;

        appointments.forEach((app, index) => {
            const appDate = new Date(app.date);
            appDate.setHours(0, 0, 0, 0);

            let header;
            // Header Grouping Logic
            if (appDate.getTime() === today.getTime() && currentView !== 'history') {
                header = 'Today';
            } else if (appDate.getTime() === tomorrow.getTime() && currentView !== 'history') {
                header = 'Tomorrow';
            } else if (currentView !== 'history' && appDate >= dayAfterTomorrow) {
                header = 'Upcoming';
            } else if (currentView === 'history') {
                 header = 'History';
            } else {
                 header = 'Other';
            }
            
            // Insert header if it's new
            if (header !== lastHeader) {
                html += `<h3 class="text-lg font-semibold text-gray-300 mt-6 mb-3 border-b border-gray-700 pb-1">${header}</h3>`;
                lastHeader = header;
            }

            // --- Card Content Generation (REDESIGN APPLIED) ---
            const statusKey = app.is_cancelled ? 'Cancelled' : app.status;
            const status = StatusMap[statusKey] || StatusMap['Completed'];
            let statusText = app.is_cancelled ? 'Cancelled' : (app.status === 'Accepted' ? 'Confirmed' : app.status);
            
            // Time Formatting
            let displayDate = appDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
            let timeParts = app.time.split(':');
            let hours = parseInt(timeParts[0]);
            let minutes = timeParts[1];
            let ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12;
            hours = hours ? hours : 12;
            let displayTime = hours + ':' + minutes + ' ' + ampm;

            let isFirstCard = index === 0 && (currentView === 'active');
            
            // New Card Design
            html += `
                <div class="appointment-card bg-gray-800/50 p-4 rounded-lg cursor-pointer transition duration-150 ease-in-out 
                     hover:bg-gray-800 border-l-4 ${status.border} hover:shadow-xl hover:shadow-gray-900/50 
                     ${isFirstCard ? 'ring-2 ring-amber-400/50' : ''}" 
                    onclick="loadAppointmentDetails(${app.id})" 
                    data-id="${app.id}">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center space-x-3">
                            <div class="text-3xl font-light text-amber-300">${appDate.getDate()}</div>
                            <div>
                                <p class="text-sm font-light text-gray-300">${displayDate.slice(0, 3)} | ${displayTime}</p>
                                <p class="text-base text-white font-semibold mt-0.5">${app.service}</p>
                            </div>
                        </div>
                        <div class="text-right">
                             <span class="text-xs font-semibold ${status.text} flex items-center space-x-1">
                                <span>${status.icon}</span> 
                                <span>${statusText}</span>
                            </span>
                            <p class="text-xs text-gray-400 mt-1">Dr. ${app.dentist ? app.dentist.name : 'N/A'}</p>
                        </div>
                    </div>
                </div>
            `;
        });

        return html;
    }

    /**
     * Loads the full detail view for a single appointment using AJAX.
     */
    function loadAppointmentDetails(id) {
        const detailContent = document.getElementById('appointmentDetailsContent');
        
        // Update active card styling
        document.querySelectorAll('.appointment-card').forEach(card => {
            card.classList.remove('ring-2', 'ring-amber-400/50');
        });
        const activeCard = document.querySelector(`.appointment-card[data-id="${id}"]`);
        if(activeCard) { 
            activeCard.classList.add('ring-2', 'ring-amber-400/50');
        }

        // Show loading state
        detailContent.innerHTML = `
            <div class="flex flex-col items-center justify-center h-full text-amber-400">
                <svg class="animate-spin h-8 w-8 text-amber-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="mt-4">Loading appointment details...</p>
            </div>
        `;

        // Fetch details from the server
        fetch(`/appointments/${id}/details`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to load details HTML. Status: ' + response.status);
                }
                return response.text();
            })
            .then(html => {
                detailContent.innerHTML = html;
            })
            .catch(error => {
                console.error('Error loading details:', error);
                detailContent.innerHTML = '<p class="text-center text-red-400 mt-20">Failed to load details. Check server route.</p>';
            });
    }

    // --- Notification, Modal, and Cancel Logic (Unchanged) ---
    function openNotificationCenter() {
        const center = document.getElementById('notificationCenter');
        center.classList.remove('hidden');
        setTimeout(() => {
            center.classList.remove('translate-x-full');
            const badge = document.getElementById('notificationBadge');
            if (badge) badge.style.display = 'none';
        }, 10);
    }

    function closeNotificationCenter() {
        const center = document.getElementById('notificationCenter');
        center.classList.add('translate-x-full');
        setTimeout(() => {
            center.classList.add('hidden');
        }, 300);
    }

    function openDeleteModal(appointmentId) {
        const form = document.getElementById('deleteForm');
        form.action = `/appointments/${appointmentId}`; 
        document.getElementById('deleteModal').style.display = 'flex';
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').style.display = 'none';
    }

    function openPatientNoteModal(note) {
        const noteTextarea = document.getElementById('noteText');
        const noteModal = document.getElementById('noteModal');

        if (noteTextarea) {
            noteTextarea.value = note;
            noteTextarea.setAttribute('readonly', 'readonly'); 
            const saveButton = document.getElementById('saveNoteButton');
            if (saveButton) {
                saveButton.classList.add('hidden'); 
            }
        }

        if (noteModal) {
            noteModal.style.display = 'flex'; 
        }
    }

    function closeNoteModal() {
        const noteTextarea = document.getElementById('noteText');
        const noteModal = document.getElementById('noteModal');
        
        if (noteTextarea) {
            noteTextarea.value = ''; 
        }

        if (noteModal) {
            noteModal.style.display = 'none';
        }
    }

    function openCancelModal(id) {
        const form = document.getElementById('cancelForm');
        form.action = `/appointments/${id}/request-cancel`;
        document.getElementById('cancelModal').style.display = 'flex';
    }
    function closeCancelModal() {
        document.getElementById('cancelModal').style.display = 'none';
    }

    function submitCancelRequest(event) {
        event.preventDefault();

        const form = event.target;
        const urlParts = form.action.split('/');
        const appointmentId = urlParts[urlParts.length - 2]; 
        const cancelReason = form.cancel_reason.value;

        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ cancel_reason: cancelReason })
        })
        .then(res => {
            if (!res.ok) {
                throw new Error('Network response was not ok');
            }
            return res.json();
        })
        .then(data => {
            if(data.success) {
                closeCancelModal();
                // Re-filter and refresh the view
                filterAppointments(document.querySelector('.tab-button.border-b-2').id.replace('tab-', '')); 
            } else {
                alert(data.message || 'Failed to submit cancellation request.');
            }
        })
        .catch(err => {
            console.error(err);
            alert('An error occurred while submitting the cancellation request.');
        });
    }

    // --- Initial Load ---
    document.addEventListener('DOMContentLoaded', () => {
        
        // 1. Select the 'active' tab and populate the list
        filterAppointments('active');
        
        // 2. Load details of the first card
        setTimeout(() => {
            const firstCard = document.querySelector('.appointment-card');
            if (firstCard && typeof loadAppointmentDetails === 'function') {
                loadAppointmentDetails(firstCard.dataset.id);
            }
        }, 50); 
    });

</script>

    @include('partials.footer')
</x-app-layout>