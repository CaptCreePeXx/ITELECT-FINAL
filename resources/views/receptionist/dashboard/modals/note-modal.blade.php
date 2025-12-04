{{-- Note Modal Structure --}}
<div id="noteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50">
    <div class="bg-gray-900 rounded-xl p-6 w-96 max-w-full relative">
        <button onclick="closeNoteModal()" class="absolute top-2 right-2 text-yellow-400 font-bold text-3xl">&times;</button>
        <h3 id="noteModalTitle" class="text-yellow-300 font-semibold text-lg mb-4">Note</h3>
        <div class="mt-3">
            <textarea id="noteText" rows="6" placeholder="Enter note..." class="w-full px-3 py-2 rounded bg-gray-700 text-white"></textarea>
        </div>
        <div class="mt-4 flex justify-end gap-3">
            <button type="button" onclick="closeNoteModal()" class="px-4 py-2 rounded bg-gray-700 text-yellow-400 hover:bg-gray-600">Close</button>
            <button type="button" id="saveNoteButton" class="px-4 py-2 rounded bg-yellow-400 text-black hover:bg-yellow-500 hidden">Save Note</button> 
        </div>
    </div>
</div>