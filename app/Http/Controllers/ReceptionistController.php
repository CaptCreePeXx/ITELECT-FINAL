<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

class ReceptionistController extends Controller
{
    public function manage()
    {
        // Get all pending appointments
        $appointments = Appointment::where('status', 'pending')->latest()->get();
        return view('receptionist.manage', compact('appointments'));
    }

    public function accept(Request $request, $id)
    {
        $request->validate([
            'note' => 'nullable|string|max:255',
        ]);

        $appointment = Appointment::findOrFail($id);
        $appointment->status = 'accepted';
        $appointment->note = $request->note; // Save note
        $appointment->save();

        return redirect()->back()->with('success', 'Appointment accepted.');
    }

    public function decline(Request $request, $id)
    {
        $request->validate([
            'note' => 'nullable|string|max:255',
        ]);

        $appointment = Appointment::findOrFail($id);
        $appointment->status = 'declined';
        $appointment->note = $request->note; // Save note
        $appointment->save();

        return redirect()->back()->with('success', 'Appointment declined.');
    }
}
