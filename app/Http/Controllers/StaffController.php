<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class StaffController extends Controller
{
    public function index()
    {
        $staff = DB::table('staff')->get();
        return view('staff.index', compact('staff'));
    }

    public function store(Request $request)
    {
       $request->validate([
    'name' => 'required',
    'email' => 'required|email|unique:users',
    'password' => 'required|min:6',

    'gender' => 'required',
    'phone_number' => 'required',
    'address' => 'required',
]);


        // 🔥 Generate STF ID
        $last = DB::table('staff')->orderBy('id', 'desc')->first();

        $num = $last ? (int) str_replace('STF','',$last->staff_id) + 1 : 1;
        $staffId = 'STF' . str_pad($num, 3, '0', STR_PAD_LEFT);

        // 🔥 Create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'staff',
        ]);

        // 🔥 Create staff + LINK
        DB::table('staff')->insert([
    'staff_id' => $staffId,
    'name' => $request->name,

    'gender' => $request->gender,
    'phone_number' => $request->phone_number,
    'address' => $request->address,

    'user_id' => $user->id,

    'created_at' => now(),
    'updated_at' => now(),
]);

        return back()->with('success', 'Staff added!');
    }
}