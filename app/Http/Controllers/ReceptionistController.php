<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;

class ReceptionistController extends Controller
{
    public function manage()
    {
        $today = date('Y-m-d');

        // Pending appointments
        $pendingAppointments = Appointment::where('status', 'Pending')
            ->latest()
            ->with('patient', 'dentist')
            ->get();

        // Active / Accepted appointments
        $activeAppointments = Appointment::where('status', 'Accepted')
            ->where('is_cancelled', false)
            ->latest()
            ->with('patient', 'dentist')
            ->get();

        // Ongoing today
        $ongoingAppointments = Appointment::where('status', 'Accepted')
            ->where('date', $today)
            ->where('is_cancelled', false)
            ->latest()
            ->with('patient', 'dentist')
            ->get();

        // Completed appointments
        $completedAppointments = Appointment::where('status', 'Completed')
            ->latest()
            ->with('patient', 'dentist')
            ->get();

        // Declined appointments
        $declinedAppointments = Appointment::where('status', 'Declined')
            ->latest()
            ->with('patient', 'dentist')
            ->get();

        // Cancellation requests
        $cancellationRequests = Appointment::where('cancel_requested', true)
            ->latest()
            ->with('patient', 'dentist')
            ->get();

        // Cancelled / History appointments (Option 2)
        $cancelledAppointments = Appointment::where('is_cancelled', true)
            ->latest()
            ->with('patient', 'dentist')
            ->get();

        // Dentists
        $dentists = User::where('role', 'dentist')->get();

        return view('receptionist.dashboard', compact(
            'pendingAppointments',
            'activeAppointments',
            'ongoingAppointments',
            'completedAppointments',
            'declinedAppointments',
            'cancellationRequests',
            'cancelledAppointments',
            'dentists'
        ));
    }

    public function accept(Request $request, $id)
    {
        $request->validate([
            'note' => 'nullable|string|max:255',
            'dentist_id' => 'nullable|exists:users,id',
        ]);

        $appointment = Appointment::findOrFail($id);
        $appointment->status = 'Accepted';
        $appointment->note = $request->note;

        if ($request->filled('dentist_id')) {
            $appointment->dentist_id = $request->dentist_id;
        }

        $appointment->save();

        return redirect()->back()->with('success', 'Appointment accepted.');
    }

    public function decline(Request $request, $id)
    {
        $request->validate([
            'note' => 'required|string|max:255', // make required here for modal
        ]);

        $appointment = Appointment::findOrFail($id);
        $appointment->status = 'Declined';
        $appointment->note = $request->note; 
        $appointment->save();

        return redirect()->back()->with('success', 'Appointment declined.');
    }

    public function assignDentist(Request $request, $id)
    {
        $request->validate([
            'dentist_id' => 'required|exists:users,id',
        ]);

        $appointment = Appointment::findOrFail($id);
        $appointment->dentist_id = $request->dentist_id;
        $appointment->save();

        return redirect()->back()->with('success', 'Dentist assigned successfully.');
    }

    public function complete($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->status = 'Completed';
        $appointment->save();

        return redirect()->back()->with('success', 'Appointment marked as completed.');
    }

    public function updateNote(Request $request, Appointment $appointment)
    {
        $appointment->note = $request->note;
        $appointment->save();

        return redirect()->back()->with('success', 'Note updated successfully.');
    }

    public function cancelRequestHandled(Request $request, $appointmentId)
{
    $appointment = Appointment::findOrFail($appointmentId);

    // Mark the cancellation as handled
    $appointment->cancel_requested = false;
    $appointment->is_cancelled = true;

    // Keep the existing reason from the patient, only use receptionist reason if none exists
    if (!$appointment->cancel_reason) {
        $appointment->cancel_reason = 'Cancelled by receptionist';
    }

    $appointment->save();

    return redirect()->back()->with('success', 'Cancellation request handled successfully.');
}


}
