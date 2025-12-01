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

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'time' => 'required',
            'service' => 'required|string|max:255',
        ]);

        Appointment::create([
            'patient_id' => auth()->id(),
            'service' => $request->service,
            'date' => $request->date,
            'time' => $request->time,
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


}
