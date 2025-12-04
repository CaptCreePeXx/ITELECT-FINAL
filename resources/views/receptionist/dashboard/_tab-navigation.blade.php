<div class="bg-gray-800 bg-opacity-90 shadow-lg rounded-t-2xl p-4 border-b border-yellow-400/30">
    <nav class="flex space-x-4 overflow-x-auto text-sm font-medium">
        <button @click="activeTab = 'pending'" :class="{ 'bg-yellow-400 text-gray-900': activeTab === 'pending', 'text-yellow-400 hover:text-yellow-300': activeTab !== 'pending' }" class="py-2 px-4 rounded-lg transition-colors duration-200 whitespace-nowrap">
            Pending Appointments ({{ $pendingAppointments->count() }})
        </button>
        <button @click="activeTab = 'active'" :class="{ 'bg-yellow-400 text-gray-900': activeTab === 'active', 'text-yellow-400 hover:text-yellow-300': activeTab !== 'active' }" class="py-2 px-4 rounded-lg transition-colors duration-200 whitespace-nowrap">
            Active Appointments ({{ $activeAppointments->count() }})
        </button>
        <button @click="activeTab = 'ongoing'" :class="{ 'bg-yellow-400 text-gray-900': activeTab === 'ongoing', 'text-yellow-400 hover:text-yellow-300': activeTab !== 'ongoing' }" class="py-2 px-4 rounded-lg transition-colors duration-200 whitespace-nowrap">
            Ongoing (Today) ({{ $ongoingAppointments->count() }})
        </button>
        <button @click="activeTab = 'cancellation'" :class="{ 'bg-red-600 text-white': activeTab === 'cancellation', 'text-yellow-400 hover:text-yellow-300': activeTab !== 'cancellation' }" class="py-2 px-4 rounded-lg transition-colors duration-200 whitespace-nowrap font-bold">
            Cancellation Requests ({{ $cancellationRequests->count() }})
        </button>
        <button @click="activeTab = 'history'" :class="{ 'bg-yellow-400 text-gray-900': activeTab === 'history', 'text-yellow-400 hover:text-yellow-300': activeTab !== 'history' }" class="py-2 px-4 rounded-lg transition-colors duration-200 whitespace-nowrap">
            Appointment History
        </button>
    </nav>
</div>