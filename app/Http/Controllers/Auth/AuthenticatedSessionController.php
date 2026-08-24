<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\AuditTrail;
use App\Models\Staff;


class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = $request->user();

        if ($user->role === 'staff')
{
    $staff = Staff::where(
        'user_id',
        $user->id
    )->first();

    if ($staff && $staff->status === 'Inactive')
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return back()->withErrors([
            'email' => 'Your account has been deactivated. Please contact administrator.',
        ]);
    }
}

        $assignedCrop = null;

if ($user->role === 'staff')
{
    $staff = Staff::where(
        'user_id',
        $user->id
    )->first();

   $assignedCrop = $staff?->plant?->name;
}

AuditTrail::create([

    'user_id' => $user->id,

    'user_name' => $user->name,

    'role' => $user->role,

    'assigned_crop' => $assignedCrop,

    'action' => 'User Login',

    'details' => 'User logged into the system',

    'ip_address' => $request->ip(),

]);

        // 🔥 SAFE CHECK (avoid error if role null)
        if ($user && $user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        // default → staff
        return redirect()->route('staff.dashboard');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
  
    $user = Auth::user();

$assignedCrop = null;

if ($user && $user->role === 'staff')
{
    $staff = Staff::where(
        'user_id',
        $user->id
    )->first();

    $assignedCrop = $staff?->plant?->name;
}

AuditTrail::create([

    'user_id' => $user->id,

    'user_name' => $user->name,

    'role' => $user->role,

    'assigned_crop' => $assignedCrop,

    'action' => 'User Logout',

    'details' => 'User logged out from the system',

    'ip_address' => $request->ip(),

]);
        
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}