<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Appointment;
use Carbon\Carbon;

class AdminController extends Controller
{
    
    public function create()
{
    return view('admin.create');
}

public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'role' => 'required|in:patient,receptionist,dentist,admin',
    ]);

    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'role' => $request->role,
        'password' => bcrypt('password'), // default password, ask user to change later
    ]);

    return redirect()->route('admin.users')->with('success', 'User added successfully.');
}

    public function index()
    {
        return view('admin.dashboard');
    }

   public function appointments(Request $request)
{
    $query = Appointment::query();

    // Search by patient name or email
    if ($request->filled('search')) {
        $search = $request->search;
        $query->whereHas('patient', function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");
        });
    }

    // Filter by status
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    // Sorting
    $sortField = $request->get('sort_field', 'date'); // default sort by date
    $sortDirection = $request->get('sort_direction', 'asc'); // default asc
    $query->orderBy($sortField, $sortDirection);

    $appointments = $query->with('patient')->paginate(8)->withQueryString();

    // Summary counts
    $summary = [
        'total' => Appointment::count(),
        'pending' => Appointment::where('status', 'pending')->count(),
        'accepted' => Appointment::where('status', 'accepted')->count(),
        'declined' => Appointment::where('status', 'declined')->count(),
    ];

    return view('admin.appointments', compact('appointments', 'summary'));
}


    public function users()
{
    $patients = User::where('role', 'patient')->get();
    $receptionists = User::where('role', 'receptionist')->get();
    $admins = User::where('role', 'admin')->get(); // <-- Add this line
    $dentists = User::where('role', 'dentist')->get();

    return view('admin.users', compact('patients', 'receptionists', 'dentists', 'admins'));
}


    // Show edit form
    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.edit', compact('user'));
    }

    // Update user
    public function updateUser(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $id,
        'role' => 'required|in:patient,receptionist,dentist,admin',
    ]);

    $user = User::findOrFail($id);
    $user->update($request->only(['name', 'email', 'role']));

    return redirect()->route('admin.users')->with('success', 'User updated successfully.');
}
    // Delete user
    public function destroyUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users')->with('success', 'User deleted successfully.');
    }

    public function showAppointment($id)
{
    $appointment = Appointment::with('patient', 'dentist')->findOrFail($id);

    return response()->json([
        'patient' => $appointment->patient->name ?? 'N/A',
        'email' => $appointment->patient->email ?? 'N/A',
        'date' => $appointment->date,
        'time' => $appointment->time,
        'service' => $appointment->service,
        'status' => $appointment->status,
        'dentist' => $appointment->dentist->name ?? 'N/A',
        'note' => $appointment->note ?? null,
        'created_at' => $appointment->created_at->format('M d, Y h:i A'),
    ]);
}

public function destroyAppointment($id)
{
    $appointment = Appointment::findOrFail($id);
    $appointment->delete();

    return redirect()->route('admin.appointments')->with('status', 'Appointment deleted successfully.');
}

public function dashboard()
{
    $today = Carbon::today();

    $summary = [
        'total' => Appointment::whereDate('date', $today)->count(),
        'pending' => Appointment::whereDate('date', $today)
                                ->where('status', 'pending')
                                ->count(),
        'completed' => Appointment::whereDate('date', $today)
                                  ->where('status', 'completed')
                                  ->count(),
        'canceled' => Appointment::whereDate('date', $today)
                                 ->where('status', 'canceled')
                                 ->count(),
    ];

    return view('admin.dashboard', compact('summary'));
}

}
