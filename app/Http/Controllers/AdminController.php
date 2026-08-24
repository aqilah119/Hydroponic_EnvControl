<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Staff;
use App\Models\AuditTrail;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
public function manageStaff(Request $request)
{
    $search = $request->search;

    $staffs = Staff::with('user')

        ->when($search, function ($query) use ($search) {

            $query->where(function ($q) use ($search) {

                $q->where('name', 'ilike', "{$search}%")

                ->orWhere('staff_id', 'ilike', "{$search}%")

                ->orWhere('phone_number', 'ilike', "{$search}%")

                  ->orWhereHas('user', function ($user) use ($search) {

                      $user->where('email', 'ilike', "{$search}%");

                  });

            });

        })

        ->latest()
        ->get();

   $adminCount = User::where('role', 'admin')->count();

$lettuceCount = Staff::where('plant_id', 1)->count();

$pakchoyCount = Staff::where('plant_id', 2)->count();

$chiliCount = Staff::where('plant_id', 3)->count();

return view(
    'admin.manage-staff',
    compact(
        'staffs',
        'adminCount',
        'lettuceCount',
        'chiliCount',
        'pakchoyCount'
    )
);
}
    public function addStaff()
{
    $crops = \App\Models\Plant::where(
        'status',
        'active'
    )->orderBy('name')->get();

    return view(
        'admin.add-staff',
        compact('crops')
    );
}

    public function storeStaff(Request $request)
    {
        $request->validate([
    'name' => 'required',
    'plant_id' => 'required_if:role,staff',
    'gender' => 'required',
    'phone_number' => 'required',
    'address' => 'required',
]);

        // LAST STAFF
        $last = DB::table('staff')
            ->orderBy('id', 'desc')
            ->first();

        // AUTO GENERATE STF
        $num = $last
            ? (int) str_replace('STF', '', $last->staff_id) + 1
            : 1;

        $staffId = 'STF' . str_pad($num, 3, '0', STR_PAD_LEFT);

        // INSERT STAFF
 DB::table('staff')->insert([
    'staff_id' => $staffId,

    'name' => $request->name,

    'gender' => $request->gender,

    'phone_number' => $request->phone_number,

    'address' => $request->address,

   'plant_id' =>
    $request->role === 'admin'
        ? null
        : $request->plant_id,

    'created_at' => now(),

    'updated_at' => now(),
]);

$cropName =
    $request->role === 'admin'
        ? 'All Crops'
        : DB::table('plants')
            ->where('id', $request->plant_id)
            ->value('name');

AuditTrail::create([

    'user_name' => Auth::user()->name, 
    'assigned_crop' => null,

    'action' => 'Added Staff',

    'details' =>
    'Added staff: ' .
    $staffId .
    ' - ' .
    $request->name .
    ' | Crop: ' .
    $cropName,

    'ip_address' => request()->ip(),

]);

        return redirect()
            ->route('admin.manage.staff')
            ->with('success', 'Staff added successfully. Staff ID: ' . $staffId);
    }
    public function editStaff($id)
{
    $staff = Staff::with('user')->findOrFail($id);

    return view('admin.edit-staff', compact('staff'));
}


public function updateStaff(Request $request, $id)
{
    $staff = Staff::findOrFail($id);

    $request->validate([

        'name' => 'required',

    ]);

$assignedCrop =
    ($staff->user && $staff->user->role === 'admin')
        ? 'All Crops'
        : DB::table('plants')
    ->where('id', $request->plant_id)
    ->value('name');

    $staff->update([

    'name' => $request->name,

    'phone_number' => $request->phone_number,

    'gender' => $request->gender,

    'address' => $request->address,

   'plant_id' => $request->plant_id,

    'status' => $request->status,

]);

    // UPDATE EMAIL kalau ada user account
    if ($staff->user) {

    $staff->user->update([
        'name' => $request->name,
        'email' => $request->email,
    ]);

}

    AuditTrail::create([

    'user_name' => Auth::user()->name,

    'role' => Auth::user()->role,

    'assigned_crop' => null,

    'action' => 'Updated Staff',

    'details' =>
    'Updated staff: ' .
    $staff->staff_id .
    ' - ' .
    $staff->name .
    ' | Crop: ' .
    $assignedCrop,

    'ip_address' => request()->ip(),

]);

    return redirect()
        ->route('admin.manage.staff')
        ->with('success', 'Staff updated successfully.');

}

public function deleteStaff($id)
{
    $staff = Staff::findOrFail($id);

    if ($staff->user_id === Auth::id())
{
    return back()->with(
        'error',
        'You cannot delete your own account.'
    );
}

    $staffId = $staff->staff_id;
$staffName = $staff->name;
$assignedCrop = $staff->plant?->name;

// CHECK IF THIS IS THE LAST STAFF FOR THE CROP

$staffCountForCrop = Staff::where(
    'plant_id',
    $staff->plant_id
)->count();

if (
    $assignedCrop &&
    $assignedCrop !== 'All Crops' &&
    $staffCountForCrop <= 1
)
{
    return redirect()
        ->route('admin.manage.staff')
        ->with(
            'error',
            'Cannot delete this staff because no other staff is assigned to monitor '
            . $assignedCrop .
            '. Please assign another staff first.'
        );
}

    // delete user sekali
    if ($staff->user) {

        $staff->user->delete();

    }

    AuditTrail::create([

     'user_id' => Auth::id(),
    'user_name' => Auth::user()->name,

    'role' => Auth::user()->role,

    'assigned_crop' => null,

    'action' => 'Deleted Staff',

    'details' =>
    'Deleted staff: ' .
    $staffId .
    ' - ' .
    $staffName .
    ' | Crop: ' .
    $assignedCrop,

    'ip_address' => request()->ip(),

]);
    // delete staff
    $staff->delete();

    return redirect()
        ->route('admin.manage.staff')
        ->with('success', 'Staff deleted successfully.');
}

}

