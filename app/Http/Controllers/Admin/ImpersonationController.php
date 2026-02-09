<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ImpersonationController extends Controller
{
    /**
     * Stop impersonating and return to the original admin account
     */
    public function stop()
    {
        if (!Session::has('admin_impersonator_id')) {
            return redirect()->route('dashboard');
        }

        $adminId = Session::pull('admin_impersonator_id');
        $admin = User::find($adminId);

        if ($admin && ($admin->is_super_admin || $admin->isSuperAdmin())) {
            Auth::login($admin);
            return redirect()->route('admin.users')->with('message', 'Returned to admin account.');
        }

        Auth::logout();
        return redirect()->route('login');
    }
}
