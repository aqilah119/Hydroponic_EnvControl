<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class StaffSettingsController extends Controller
{
    /**
     * Display settings page
     */
    public function index()
    {
        /** @var User $user */
           /** @var User $user */
$user = Auth::user();
        $staff = $user->staff;

        return view('staff.settings', compact('user', 'staff'));
    }

    /**
     * Update settings
     */
public function update(Request $request)
{
    /** @var User $user */
    $user = Auth::user();

    $staff = $user->staff;

    // VALIDATION
    $request->validate([

    'phone_number' => [
        'required',
        'regex:/^[0-9]{10,11}$/'
    ],

], [

    'phone_number.regex' => 'Not a valid number.',

]);

    // UPDATE EMAIL
    $user->email = $request->email;
    $user->save();

    // UPDATE ADDRESS
    $staff->address = $request->address;
    $staff->phone_number = $request->phone_number;

    // UPLOAD IMAGE
    if ($request->hasFile('profile_picture')) {

        $image = $request->file('profile_picture');

        $imageName = time() . '.' . $image->getClientOriginalExtension();

        $image->storeAs('profile_pictures', $imageName, 'public');

        $staff->profile_picture = 'profile_pictures/' . $imageName;
    }

    // SAVE STAFF
    $staff->save();

    return back()->with('success', 'Profile updated successfully!');
}
public function changePassword()
{
    return view('staff.change-password');
}

public function updatePassword(Request $request)
{
    $request->validate([

        'current_password' => 'required',

        'password' => 'required|min:8|confirmed'

    ]);


   /** @var User $user */
$user = Auth::user();

    if (!Hash::check(
        $request->current_password,
        $user->password
    ))
    {
        return back()->with(
            'error',
            'Current password is incorrect.'
        );
    }

    $user->password = Hash::make(
        $request->password
    );

    return back()->with(
        'success',
        'Password changed successfully.'
    );
}
}