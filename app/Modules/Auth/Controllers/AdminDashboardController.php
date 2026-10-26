<?php

namespace Modules\Auth\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    /**
     * Display the admin dashboard with statistics and user list.
     */
    public function index(): View
    {
        $totalUsers = User::count();
        $totalAdmins = User::where('role', 'admin')->count();
        $totalRegularUsers = User::where('role', 'user')->count();
        $users = User::latest()->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalAdmins',
            'totalRegularUsers',
            'users'
        ));
    }

    /**
     * Update the specified user's role.
     */
    public function updateRole(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'role' => ['required', 'in:admin,user'],
        ]);

        // Prevent self role change to avoid locking oneself out
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat mengubah peran akun Anda sendiri.');
        }

        $user->update(['role' => $validated['role']]);

        return back()->with('success', "Role untuk user {$user->name} berhasil diubah menjadi {$validated['role']}.");
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroyUser(User $user): RedirectResponse
    {
        // Prevent deleting oneself
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $name = $user->name;
        $user->delete();

        return back()->with('success', "User {$name} berhasil dihapus.");
    }
}
