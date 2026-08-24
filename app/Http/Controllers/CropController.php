<?php

namespace App\Http\Controllers;

use App\Models\Plant;
use App\Models\Staff;
use Illuminate\Http\Request;
use App\Models\AuditTrail;
use Illuminate\Support\Facades\Auth;

class CropController extends Controller
{
    public function index()
    {
        $crops = Plant::latest()->get();

        return view(
            'admin.crop-database',
            compact('crops')
        );
    }

    public function create()
{
    return view('admin.add-crop');
}

public function store(Request $request)
{
    $request->validate([

    'name' => 'required',

    'category' => 'required',

    'ph_min' => 'required|numeric',

    'ph_max' => 'required|numeric|gt:ph_min',

    'temp_min' => 'required|numeric',

    'temp_max' => 'required|numeric|gt:temp_min',

    'tds_min' => 'required|numeric',

    'tds_max' => 'required|numeric|gt:tds_min',

    'water_min' => 'required|numeric|min:0|max:100',

    'status' => 'required',

], [

    'ph_max.gt' => 'pH Maximum must be greater than pH Minimum.',

    'temp_max.gt' => 'Temperature Maximum must be greater than Temperature Minimum.',

    'tds_max.gt' => 'TDS Maximum must be greater than TDS Minimum.',

]);

   $crop = Plant::create([

        'name' => $request->name,

        'category' => $request->category,

        'ph_min' => $request->ph_min,

        'ph_max' => $request->ph_max,

        'temp_min' => $request->temp_min,

        'temp_max' => $request->temp_max,

        'tds_min' => $request->tds_min,

        'tds_max' => $request->tds_max,

        'water_min' => $request->water_min,

        'status' => $request->status,


    ]);

    AuditTrail::create([

     'user_id' => Auth::id(),

    'user_name' => Auth::user()->name,

    'role' => Auth::user()->role,

    'assigned_crop' => null,

    'action' => 'Added Crop',

    'details' => 'Added crop: ' . $crop->name,

    'ip_address' => request()->ip(),

]);

    return redirect()
        ->route('admin.crop.database')
        ->with('success', 'Crop added successfully.');
}

public function edit($id)
{
    $crop = Plant::findOrFail($id);

    return view(
        'admin.edit-crop',
        compact('crop')
    );
}

public function update(Request $request, $id)
{
    $crop = Plant::findOrFail($id);

$oldStatus = $crop->status;
    
$request->validate([

    'name' => 'required',

    'category' => 'required',

    'ph_min' => 'required|numeric',

    'ph_max' => 'required|numeric|gt:ph_min',

    'temp_min' => 'required|numeric',

    'temp_max' => 'required|numeric|gt:temp_min',

    'tds_min' => 'required|numeric',

    'tds_max' => 'required|numeric|gt:tds_min',

    'water_min' => 'required|numeric|min:0|max:100',

    'status' => 'required',

], [

    'ph_max.gt' => 'pH Maximum must be greater than pH Minimum.',

    'temp_max.gt' => 'Temperature Maximum must be greater than Temperature Minimum.',

    'tds_max.gt' => 'TDS Maximum must be greater than TDS Minimum.',

]);
    $crop->update([

        'name' => $request->name,

        'category' => $request->category,

        'ph_min' => $request->ph_min,

        'ph_max' => $request->ph_max,

        'temp_min' => $request->temp_min,

        'temp_max' => $request->temp_max,

        'tds_min' => $request->tds_min,

        'tds_max' => $request->tds_max,

        'water_min' => $request->water_min,

        'status' => $request->status,

    ]);

    if (
    $oldStatus !== $request->status
)
{
    AuditTrail::create([

         'user_id' => Auth::id(),
        'user_name' => Auth::user()->name,

        'role' => Auth::user()->role,

        'assigned_crop' => null,

        'action' =>
            $request->status == 'active'
            ? 'Activated Crop'
            : 'Deactivated Crop',

        'details' =>
            $request->status == 'active'
            ? 'Activated crop: ' . $crop->name
            : 'Deactivated crop: ' . $crop->name,

        'ip_address' => request()->ip(),

    ]);
}


    AuditTrail::create([

     'user_id' => Auth::id(),
    'user_name' => Auth::user()->name,

    'role' => Auth::user()->role,

    'assigned_crop' => null,

    'action' => 'Updated Crop',

    'details' => 'Updated crop: ' . $crop->name,

    'ip_address' => request()->ip(),

]);

    return redirect()
        ->route('admin.crop.database')
        ->with('success', 'Crop updated successfully.');
}

public function destroy($id)
{
    $crop = Plant::findOrFail($id);

$staffUsingCrop = Staff::where(
    'plant_id',
    $crop->id
)->exists();

    if ($staffUsingCrop) {

        return redirect()
            ->route('admin.crop.database')
            ->with(
                'error',
                'Cannot delete crop because it is assigned to staff. Please deactivate it instead.'
            );
    }


    AuditTrail::create([

     'user_id' => Auth::id(),
    'user_name' => Auth::user()->name,

    'role' => Auth::user()->role,

    'assigned_crop' => null,

    'action' => 'Deleted Crop',

    'details' => 'Deleted crop: ' . $crop->name,

    'ip_address' => request()->ip(),

]);

    $crop->delete();

    return redirect()
        ->route('admin.crop.database')
        ->with(
            'success',
            'Crop deleted successfully.'
        );
}
}