<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserApprovalController extends Controller
{
    public function index()
    {
        $pendingUsers = User::where('is_active', false)->orderBy('created_at', 'desc')->get();
        return view('admin.approvals.index', compact('pendingUsers'));
    }

    public function count()
    {
        $count = User::where('is_active', false)->count();
        return response()->json(['count' => $count]);
    }

    public function approve($id)
    {
        $user = User::findOrFail($id);
        $user->is_active = true;
        $user->save();

        return redirect()->back()->with('success', 'User approved successfully.');
    }

    public function reject($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->back()->with('success', 'User rejected and removed.');
    }
}
