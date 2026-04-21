<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // ── Index ────────────────────────────────────────────────────
    public function index(Request $request)
{
    $perPage = (int) $request->input('per_page', 10);

    if (!in_array($perPage, [10, 25, 50, 100])) {
        $perPage = 10;
    }

    $query = User::query();

    // Apply search if provided
    if ($search = $request->input('search')) {
        $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('role', 'like', "%{$search}%");
        });
    }

    $users = $query->paginate($perPage)->appends($request->query());
    $roleCounts = User::selectRaw('role, count(*) as count')
                      ->groupBy('role')
                      ->pluck('count', 'role');

    return view('admin.user-roles', compact('users', 'roleCounts'));
}


    // ── Store ────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|min:2|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role'     => 'required|in:admin,judge,tabulator,sas,guest',
            'status'   => 'required|in:active,inactive,pending',
        ], [
            'name.required'      => 'Name is required.',
            'name.min'           => 'Name must be at least 2 characters.',
            'email.required'     => 'Email is required.',
            'email.email'        => 'Please enter a valid email address.',
            'email.unique'       => 'This email is already registered.',
            'password.required'  => 'Password is required.',
            'password.min'       => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Passwords do not match.',
            'role.required'      => 'Please select a role.',
            'status.required'    => 'Please select a status.',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
            'status'   => $request->status,
        ]);

        return redirect()->route('admin.user-roles')
            ->with('success', 'User "' . $request->name . '" has been added successfully!');
    }

    // ── Update ───────────────────────────────────────────────────
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'   => 'required|string|min:2|max:255',
            'email'  => 'required|email|unique:users,email,' . $user->id,
            'role'   => 'required|in:admin,judge,tabulator,sas,guest',
            'status' => 'required|in:active,inactive,pending',
        ], [
            'name.required'   => 'Name is required.',
            'name.min'        => 'Name must be at least 2 characters.',
            'email.required'  => 'Email is required.',
            'email.email'     => 'Please enter a valid email address.',
            'email.unique'    => 'This email is already used by another user.',
            'role.required'   => 'Please select a role.',
            'status.required' => 'Please select a status.',
        ]);

        $user->update([
            'name'   => $request->name,
            'email'  => $request->email,
            'role'   => $request->role,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.user-roles', [
            'page'     => $request->input('page', 1),
            'per_page' => $request->input('per_page', 10),
        ])->with('success', 'User "' . $user->name . '" has been updated successfully!');
    }

    // ── Destroy (soft delete) ──────────────────────────────────────────────────
    public function destroy(User $user)
    {
        $name = $user->name;
        $user->delete();

        return redirect()->route('admin.user-roles')
            ->with('success', 'User "' . $name . '" has been deleted successfully!');
    }

    // ── Archive (view deleted users) ─────────────────────────────────
public function archive()
{
    $users = User::onlyTrashed()
                 ->orderBy('deleted_at', 'desc')
                 ->paginate(10);

    return view('admin.user-archive', compact('users'));
}

// ── Restore ──────────────────────────────────────────────────────
public function restore($id)
{
    $user = User::onlyTrashed()->findOrFail($id);
    $user->restore();

    return redirect()->route('admin.user-archive')
        ->with('success', 'User "' . $user->name . '" has been restored.');
}

// ── Force Delete (permanent) ─────────────────────────────────────
public function forceDelete($id)
{
    $user = User::onlyTrashed()->findOrFail($id);
    $name = $user->name;
    $user->forceDelete();

    return redirect()->route('admin.user-archive')
        ->with('success', 'User "' . $name . '" has been permanently deleted.');
}
}
