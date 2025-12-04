<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

class PatientsController extends Controller
{
    public function index()
    {
        // Only show appointments that belong to the logged-in patient
          $appointments = Appointment::where('patient_id', auth()->id())
            ->where('status', '!=', 'Cancelled') // hide cancelled
            ->get();

        return view('appointments.index', compact('appointments'));
    }

    public function create()
    {
        return view('appointments.create');
    }

    // App/Http/Controllers/PatientsController.php (CORRECTED store method)

public function store(Request $request)
{
    $validatedData = $request->validate([
        'date' => 'required|date',
        'time' => 'required',
        'service' => 'required|string|max:255',
        // 1. ADD VALIDATION for the 'note' field from the form
        'note' => 'nullable|string|max:1000', 
    ]);

    Appointment::create([
        'patient_id' => auth()->id(),
        'service' => $validatedData['service'], // Use validated data
        'date' => $validatedData['date'],
        'time' => $validatedData['time'],

        // 2. ADD the new 'patient_reason' field using the submitted 'note' data
        'patient_reason' => $validatedData['note'] ?? null, 

        // 3. Ensure the 'note' field (reserved for the receptionist) is null
        'note' => null, 
        
        'status' => 'Pending',
    ]);

    return redirect()->route('appointments.index')->with('success', 'Appointment booked successfully!');
}

    public function edit(Appointment $appointment)
    {
        // Ensure the patient owns this appointment
        if ($appointment->patient_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('appointments.edit', compact('appointment'));
    }

    public function update(Request $request, Appointment $appointment)
{
    if ($appointment->patient_id !== auth()->id()) {
        abort(403, 'Unauthorized action.');
    }

    // Validation (no 'status' here for patients)
    $request->validate([
        'date' => 'required|date',
        'time' => 'required',
        'service' => 'required|string|max:255',
    ]);

    // Update only patient-editable fields
    $appointment->update([
        'service' => $request->service,
        'date' => $request->date,
        'time' => $request->time,
    ]);

    return redirect()->route('appointments.index')->with('success', 'Appointment updated successfully!');
}


    public function destroy(Appointment $appointment)
    {
        if ($appointment->patient_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $appointment->delete();

        return redirect()->route('appointments.index')->with('success', 'Appointment deleted successfully!');
    }

    public function requestCancel(Request $request, Appointment $appointment)
{
    $appointment->cancel_requested = true;
    $appointment->cancel_reason = $request->cancel_reason;
    $appointment->save();

    return response()->json(['success' => true]);
}

public function showDetailsHtml(Appointment $appointment)
{
    // 1. SECURITY CHECK: Ensure the logged-in user (patient) owns this appointment.
    // auth()->id() retrieves the currently logged-in user's ID.
    // $appointment->patient_id is the ID of the user who booked the appointment.
    if (auth()->id() !== $appointment->patient_id) {
        // If the IDs don't match, block the request and return an error.
        abort(403, 'Unauthorized action. This appointment does not belong to your account.');
    }
    
    // 2. Return the Detail View Partial (only if the user is authorized).
    return view('appointments._partials._detail-view', compact('appointment'));
}


}
