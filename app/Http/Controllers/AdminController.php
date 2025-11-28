<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Appointment; // if you have an Appointment model

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
        'role' => 'required|in:patient,receptionist,admin',
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

    public function appointments()
    {
        $appointments = Appointment::all();
        return view('admin.appointments', compact('appointments'));
    }

    public function users()
{
    $patients = User::where('role', 'patient')->get();
    $receptionists = User::where('role', 'receptionist')->get();
    $admins = User::where('role', 'admin')->get(); // <-- Add this line

    return view('admin.users', compact('patients', 'receptionists', 'admins'));
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
        'role' => 'required|in:patient,receptionist,admin',
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
}
