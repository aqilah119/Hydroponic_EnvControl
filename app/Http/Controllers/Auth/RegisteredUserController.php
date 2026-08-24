<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Staff;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeEmail;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display Step 1 (Enter Staff ID)
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * 🔥 STEP 1: Check Staff ID
     */
    public function checkStaff(Request $request)
    {
        $request->validate([
            'staff_id' => ['required', 'string'],
        ]);

        // 🔍 Find staff by staff_id (string)
        $staff = Staff::where('staff_id', $request->staff_id)->first();

        if (!$staff) {
            return back()->withErrors([
                'staff_id' => 'Invalid Staff ID',
            ])->withInput();
        }

       // ❌ Prevent duplicate registration
if ($staff->user_id != null) {

    return back()->withErrors([
        'staff_id' => 'Account already registered, contact admin',
    ])->withInput();

}

    session([
    'register_staff_id' => $staff->id
]);

return redirect()->route('register.step2');
    }

    /**
     * 🔥 STEP 2: Complete Registration
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'staff_id' => ['required', 'string'],
        ]);

        // 🔍 Double check staff (SECURITY)
        $staff = Staff::where('staff_id', $request->staff_id)->first();

        if (!$staff) {
            return back()->withErrors([
                'staff_id' => 'Invalid Staff ID',
            ])->withInput();
        }

        // ❌ Prevent duplicate
if ($staff->user_id != null) {

    return back()->withErrors([
        'staff_id' => 'This staff already registered',
    ])->withInput();

}

        // ✅ Create user
        $user = User::create([
            'name' => $staff->name,       // 🔥 auto from staff table
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'staff',
        ]);
        // 🔗 Link staff with user
$staff->user_id = $user->id;
$staff->save();

/* SEND WELCOME EMAIL */
Mail::to($user->email)
    ->send(new WelcomeEmail($user));

event(new Registered($user));

Auth::login($user);

return redirect()->route('dashboard');
    }
}