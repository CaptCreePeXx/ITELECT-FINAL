<div class="flex space-x-2 border-b border-gray-700/50 pb-2">
    <button id="tab-active" 
        onclick="filterAppointments('active')" 
        class="tab-button px-3 py-1 rounded-sm text-sm transition text-gray-400 hover:text-amber-400 hover:bg-gray-700/50">
        Active
    </button>
    <button id="tab-pending" 
        onclick="filterAppointments('pending')" 
        class="tab-button px-3 py-1 rounded-sm text-sm transition text-gray-400 hover:text-amber-400 hover:bg-gray-700/50">
        Pending
    </button>
    <button id="tab-confirmed" 
        onclick="filterAppointments('confirmed')" 
        class="tab-button px-3 py-1 rounded-sm text-sm transition text-gray-400 hover:text-amber-400 hover:bg-gray-700/50">
        Confirmed
    </button>
    <button id="tab-history" 
        onclick="filterAppointments('history')" 
        class="tab-button px-3 py-1 rounded-sm text-sm transition text-gray-400 hover:text-amber-400 hover:bg-gray-700/50">
        History
    </button>
</div>