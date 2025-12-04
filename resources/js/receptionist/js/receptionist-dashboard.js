let currentAppointmentId = null;
let currentNoteType = null;
let currentHiddenInputId = null;

/**
 * Opens the generic note modal.
 * @param {number} id - The appointment ID.
 * @param {('receptionist_message'|'patient_reason')} mode - Defines the modal's function (view patient note or edit receptionist note).
 * @param {string | null} hiddenInputId - ID of the hidden input field to store the receptionist note.
 */
window.openNoteModal = function(id, mode = 'receptionist_message', hiddenInputId = null) {
    currentAppointmentId = id;
    currentNoteType = mode;
    currentHiddenInputId = hiddenInputId;

    const noteTextarea = document.getElementById('noteText');
    const saveButton = document.getElementById('saveNoteButton');
    const modalTitle = document.getElementById('noteModalTitle');
    const modal = document.getElementById('noteModal');
    const receptionistDashboardElement = document.getElementById('receptionist-dashboard-wrapper');

    // Reset styles and visibility
    noteTextarea.value = '';
    noteTextarea.removeAttribute('readonly');
    noteTextarea.classList.remove('bg-gray-800', 'text-gray-300');
    saveButton.classList.remove('hidden');
    saveButton.onclick = saveNoteToForm; // Restore default click action

    if (receptionistDashboardElement) {
    // --- All your receptionist-specific global variables go here ---
    let currentAppointmentId = null; 
    // ... etc.

    // --- All your window.functions go here ---
    window.openNoteModal = function(id, mode = 'receptionist_message', hiddenInputId = null) {
        // ... all your current receptionist modal logic ...
    }

    // window.openDeclineModal = function(...) { ... }
    // ... etc.
    
    // Attach the default generic saveNoteToForm action to the save button on DOM load.
    document.addEventListener('DOMContentLoaded', () => {
        // ... (etc.)
    });
}

    if (mode === 'patient_reason') {
        const patientNoteSpan = document.getElementById('patientNote_' + id);
        noteTextarea.value = patientNoteSpan ? patientNoteSpan.textContent.trim() : 'No note provided.';
        noteTextarea.setAttribute('readonly', 'readonly');
        noteTextarea.classList.add('bg-gray-800', 'text-gray-300');
        saveButton.classList.add('hidden');
        modalTitle.textContent = 'Patient Booking Reason';
    } else if (mode === 'receptionist_message' && hiddenInputId) {
        const hiddenInput = document.getElementById(hiddenInputId);
        noteTextarea.value = hiddenInput ? hiddenInput.value : '';
        modalTitle.textContent = 'Receptionist Note for Patient';
    }

    // Show modal
    modal.style.display = 'flex';
}

/**
 * Closes the note modal and resets state.
 */
window.closeNoteModal = function() {
    currentAppointmentId = null;
    currentNoteType = null;
    currentHiddenInputId = null;

    const modal = document.getElementById('noteModal');
    if (modal) {
        modal.style.display = 'none';
    }

    const noteTextarea = document.getElementById('noteText');
    if (noteTextarea) noteTextarea.value = '';
}

/**
 * Saves the note from the textarea back to the hidden form input and potentially submits the form.
 */
window.saveNoteToForm = function() {
    const noteTextarea = document.getElementById('noteText');
    if (currentHiddenInputId && noteTextarea) {
        const hiddenInput = document.getElementById(currentHiddenInputId);
        if (hiddenInput) {
             hiddenInput.value = noteTextarea.value;
             
             // Logic to handle form submission for 'Update Note' in Active Appointments tab
             if(currentHiddenInputId.startsWith('noteHiddenInput_') && !currentHiddenInputId.includes('Accept') && !currentHiddenInputId.includes('Decline')) {
                 const formId = 'noteForm_' + currentAppointmentId;
                 document.getElementById(formId).submit();
             }
        }
    }

    // Close the modal always
    closeNoteModal();
}

/**
 * Special modal function for 'Decline' action, handles note saving and form submission.
 * Assumes the hidden input ID is 'noteHiddenInputDecline_ID'.
 * @param {number} id - The appointment ID.
 */
window.openDeclineModal = function(id) {
    // Hidden input ID for the decline note field on the decline form
    const hiddenInputId = 'noteHiddenInputDecline_' + id;
    
    // Open the modal
    openNoteModal(id, 'receptionist_message', hiddenInputId);

    // Customize the modal for Decline action
    document.getElementById('noteModalTitle').textContent = 'Reason for Decline';
    
    // Override the save button action to save the note AND submit the decline form
    document.getElementById('saveNoteButton').onclick = function() {
        // 1. Save the note to the hidden input
        saveNoteToForm(); 
        
        // 2. Submit the decline form
        const formId = 'declineForm_' + id;
        document.getElementById(formId).submit();
    };
}


// Attach the default generic saveNoteToForm action to the save button on DOM load.
document.addEventListener('DOMContentLoaded', () => {
    const saveButton = document.getElementById('saveNoteButton');
    if (saveButton) {
        saveButton.onclick = saveNoteToForm;
    }
});