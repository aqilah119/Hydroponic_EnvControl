<?php

namespace App\Http\Controllers;

use App\Models\AuditTrail;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Staff;

class AuditController extends Controller
{
    public function index(Request $request)
{

if (Auth::user()->role !== 'admin')
{
    abort(403);
}
    $query = AuditTrail::query();

    // ROLE FILTER

    if ($request->filled('role'))
    {
        $query->where(
            'role',
            $request->role
        );
    }

// STAFF hanya login/logout

if ($request->role === 'staff')
{
    $query->whereIn(
        'action',
        [
            'User Login',
            'User Logout'
        ]
    );
}

    // ACTION FILTER

if ($request->filled('action'))
{
    // kalau role staff,
    // hanya benarkan login/logout

    if (
        $request->role === 'staff'
        &&
        !in_array(
            $request->action,
            [
                'User Login',
                'User Logout'
            ]
        )
    )
    {
        abort(403);
    }

    $query->where(
        'action',
        $request->action
    );
}

    // ASSIGNED CROP FILTER

if ($request->filled('assigned_crop'))
{
    $query->where(
        'assigned_crop',
        $request->assigned_crop
    );
}

    // DATE FROM

    if ($request->filled('date_from'))
    {
        $query->whereDate(
            'created_at',
            '>=',
            $request->date_from
        );
    }

    // DATE TO

    if ($request->filled('date_to'))
    {
        $query->whereDate(
            'created_at',
            '<=',
            $request->date_to
        );
    }

   $logs = $query
    ->orderBy('id', 'asc')
    ->paginate(10);

    $roles = DB::table('users')
        ->select('role')
        ->distinct()
        ->get();
        $crops = DB::table('plants')
    ->select('name')
    ->orderBy('name')
    ->get();
    $actions = [
    

    'User Login',
    'User Logout',

    'Added Staff',
    'Updated Staff',
    'Deleted Staff',

    'Added Crop',
    'Updated Crop',
    'Deleted Crop',

    'Activated Crop',
    'Deactivated Crop'

];

    return view(
        'admin.audit-trail',
        compact(
    'logs',
    'roles',
    'actions',
    'crops'
)
    );
}

public function exportCsv(Request $request)
{
    $query = AuditTrail::query();

    if ($request->filled('role'))
    {
        $query->where(
            'role',
            $request->role
        );
    }

    if ($request->filled('action'))
    {
        $query->where(
            'action',
            $request->action
        );
    }

    if ($request->filled('assigned_crop'))
    {
        $query->where(
            'assigned_crop',
            $request->assigned_crop
        );
    }

    if ($request->filled('date_from'))
    {
        $query->whereDate(
            'created_at',
            '>=',
            $request->date_from
        );
    }

    if ($request->filled('date_to'))
    {
        $query->whereDate(
            'created_at',
            '<=',
            $request->date_to
        );
    }

    $logs = $query
        ->orderBy('id', 'asc')
        ->get();

    $fileName =
        'audit_trail_' .
        now()->format('Ymd_His') .
        '.csv';

    $headers = [

        'Content-Type' => 'text/csv',

        'Content-Disposition' =>
            "attachment; filename={$fileName}",

    ];

    $callback = function () use ($logs)
    {
        $file = fopen(
            'php://output',
            'w'
        );

        fputcsv($file, [

            'ID',
            'Date Time',
            'User',
            'Role',
            'Assigned Crop',
            'Action',
            'Details',
            'IP Address'

        ]);

        foreach ($logs as $log)
        {
            fputcsv($file, [

                $log->id,
                $log->created_at,
                $log->user_name,
                $log->role,
                $log->assigned_crop,
                $log->action,
                $log->details,
                $log->ip_address

            ]);
        }

        fclose($file);
    };

    return response()->stream(
        $callback,
        200,
        $headers
    );
}

}