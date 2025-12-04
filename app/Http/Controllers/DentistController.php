<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Appointment;

class DentistController extends Controller
{
    /**
     * Show the dentist dashboard (main view).
     * Route name: dentist.dashboard
     */
    public function dashboard(): View
    {
        $dentistId = Auth::id();

        $today = now()->toDateString();
        $weekAhead = now()->addDays(7)->toDateString();

        // Today's appointments
        $todaysAppointments = Appointment::where('dentist_id', $dentistId)
            ->whereDate('date', $today)
            ->orderBy('time', 'asc')
            ->get();

        // Upcoming appointments (next 7 days)
        $upcomingAppointments = Appointment::where('dentist_id', $dentistId)
            ->whereDate('date', '>', $today)
            ->whereDate('date', '<=', $weekAhead)
            ->orderBy('date', 'asc')
            ->orderBy('time', 'asc')
            ->get();

        // Dashboard statistics
        $stats = [
            'today'     => $todaysAppointments->count(),
            'upcoming'  => $upcomingAppointments->count(),
            'completed' => Appointment::where('dentist_id', $dentistId)
                                ->whereDate('date', $today)
                                ->where('status', 'completed')
                                ->count(),
            'pending'   => Appointment::where('dentist_id', $dentistId)
                                ->whereDate('date', $today)
                                ->where('status', 'pending')
                                ->count(),
        ];

        return view('dentist.dashboard', compact(
            'dentistId',
            'todaysAppointments',
            'upcomingAppointments',
            'stats'
        ));
    }


    /**
     * Dentist schedule page (full list of appointments).
     * For a separate tab if needed.
     */
    public function schedule(): View
    {
        $dentistId = Auth::id();

        $appointments = Appointment::where('dentist_id', $dentistId)
            ->orderBy('date', 'asc')
            ->orderBy('time', 'asc')
            ->get();

        return view('dentist.schedule', compact('appointments', 'dentistId'));
    }


    /**
     * View a specific appointment details.
     */
    public function viewAppointment($id): View
    {
        $appointment = Appointment::findOrFail($id);

        if ($appointment->dentist_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        return view('dentist.appointment-view', compact('appointment'));
    }


    /**
     * Save appointment notes.
     */
    public function saveNotes(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'notes' => 'nullable|string|max:5000',
        ]);

        $appointment = Appointment::findOrFail($id);

        if ($appointment->dentist_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        $appointment->notes = $request->notes;
        $appointment->save();

        return back()->with('success', 'Notes saved successfully.');
    }


    /**
     * Mark appointment as completed.
     */
    public function completeAppointment($id): RedirectResponse
    {
        $appointment = Appointment::findOrFail($id);

        if ($appointment->dentist_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        $appointment->status = 'completed';
        $appointment->save();

        return back()->with('success', 'Appointment marked as completed.');
    }
}
