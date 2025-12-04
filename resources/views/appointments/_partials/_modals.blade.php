{{-- --- Delete Modal --- --}}
<div id="deleteModal" class="hidden fixed inset-0 bg-black/70 items-center justify-center z-50">
    <div class="bg-gray-800 rounded-xl p-6 w-96 max-w-full relative shadow-2xl">
        {{-- Close button redesigned for cleaner look --}}
        <button onclick="closeDeleteModal()" class="absolute -top-3 -right-3 text-white font-bold text-xl 
                             bg-red-600 rounded-full w-8 h-8 flex items-center justify-center 
                             hover:bg-red-700 transition">&times;</button>
        <h3 class="text-white font-semibold text-xl mb-4 flex items-center">
            <svg class="w-6 h-6 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.398 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            Confirm Deletion
        </h3>
        <p class="text-gray-300 mb-6">Are you sure you want to <span class="font-bold text-red-400">permanently delete</span> this appointment request? This action cannot be undone.</p>
        <div class="flex justify-end space-x-3">
            <button onclick="closeDeleteModal()" class="px-4 py-2 rounded bg-gray-600 text-white hover:bg-gray-500 transition">Cancel</button>
            <form id="deleteForm" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 rounded bg-red-600 text-white font-semibold hover:bg-red-700 transition">Delete Permanently</button>
            </form>
        </div>
    </div>
</div>

{{-- --- Note/Reason Modal --- --}}
<div id="noteModal" class="hidden fixed inset-0 bg-black/70 items-center justify-center z-50">
    <div class="bg-gray-800 rounded-xl p-6 w-96 max-w-full relative shadow-2xl">
        <button onclick="closeNoteModal()" class="absolute -top-3 -right-3 text-white font-bold text-xl 
                             bg-gray-600 rounded-full w-8 h-8 flex items-center justify-center 
                             hover:bg-gray-500 transition">&times;</button>
        <h3 class="text-white font-semibold text-xl mb-4 flex items-center">
             <svg class="w-6 h-6 mr-2 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            Appointment Note
        </h3>
        <textarea id="noteText" rows="6" class="w-full px-3 py-2 rounded bg-gray-700 text-white border border-gray-600 resize-none focus:ring-amber-400 focus:border-amber-400" readonly></textarea>
        
        {{-- Save button is hidden via JS for patients --}}
        <div class="flex justify-end mt-4">
            <button id="saveNoteButton" class="hidden px-4 py-2 rounded bg-amber-400 text-gray-950 hover:bg-amber-300 font-semibold transition">Save Note</button>
        </div>
    </div>
</div>

{{-- --- Cancel Request Modal --- --}}
<div id="cancelModal" class="hidden fixed inset-0 bg-black/70 items-center justify-center z-50">
    <div class="bg-gray-800 rounded-xl p-6 w-96 max-w-full relative shadow-2xl">
        <button onclick="closeCancelModal()" class="absolute -top-3 -right-3 text-white font-bold text-xl 
                             bg-gray-600 rounded-full w-8 h-8 flex items-center justify-center 
                             hover:bg-gray-500 transition">&times;</button>

        <h3 class="text-white font-semibold text-xl mb-4 flex items-center">
            <svg class="w-6 h-6 mr-2 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            Request Cancellation
        </h3>
        <p class="text-gray-300 mb-4 text-sm">Please provide a reason. This request requires confirmation from the clinic staff.</p>

        <form id="cancelForm" method="POST" onsubmit="submitCancelRequest(event)">
            @csrf
            {{-- Reason for cancellation --}}
            <textarea name="cancel_reason" rows="4" placeholder="Enter reason for cancellation..." class="w-full px-3 py-2 rounded bg-gray-700 text-white mb-4 border border-gray-600 focus:ring-amber-400 focus:border-amber-400" required></textarea>
            
            <div class="flex justify-end">
                <button type="submit" class="bg-amber-400 px-4 py-2 rounded text-gray-950 hover:bg-amber-300 font-semibold transition">Submit Request</button>
            </div>
        </form>
    </div>
</div>