<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->where('role_id', '!=', 1)->get();
        return view('admin.users.index', compact('users'));
    }

    public function updateStatus(User $user)
    {
        $user->update([
            'account_status' => $user->account_status === 'active' ? 'locked' : 'active'
        ]);

        return back()->with('success', 'Trạng thái người dùng đã được cập nhật.');
    }
}
