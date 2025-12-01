<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Appointment;

class DentistController extends Controller
{
    public function schedule()
    {
        $dentistId = Auth::id();

        // Get appointments for this dentist
        $appointments = Appointment::where('dentist_id', $dentistId)
            ->orderBy('date', 'asc')
            ->get();

        return view('dentist.schedule', compact('appointments'));
    }
}
