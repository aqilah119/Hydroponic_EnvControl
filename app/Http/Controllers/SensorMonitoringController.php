<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use App\Models\AuditTrail;
use Illuminate\Support\Facades\Auth;
use App\Models\Staff;

class SensorMonitoringController extends Controller
{
    public function index(Request $request)
    {
      if (Auth::user()->role === 'staff')
{
   $staff = Staff::with('plant')
    ->where('user_id', Auth::id())
    ->first();

   $selectedCrop = $staff->plant?->name ?? 'Pak Choy';
}
else
{
    $selectedCrop = $request->crop ?? 'Pak Choy';
}

$table = 'sensor_logs';

if($selectedCrop == 'Lettuce')
{
    $table = 'sensor_logs_lettuce';
}
elseif($selectedCrop == 'Chili')
{
    $table = 'sensor_logs_chili';
}
        // GET UNIQUE DATES
        $dates = DB::table($table)
            ->selectRaw('DATE(timestamp) as date')
            ->distinct()
            ->orderBy('date', 'desc')
            ->pluck('date');

        // SELECTED DATE
        $selectedDate = $request->date
            ?? DB::table($table)
                ->latest('timestamp')
                ->value(DB::raw('DATE(timestamp)'));

        // STATUS FILTER
        $status = $request->status;

        // SENSOR DATA
$sensorData = DB::table($table)
    ->whereDate('timestamp', $selectedDate)
    ->orderBy('timestamp', 'asc')
    ->get()
    ->filter(function ($data) use ($status) {

        // TEMP
        $tempStatus = 'Normal';

        if ($data->dht_temp < 18 || $data->dht_temp > 28) {
            $tempStatus = 'Critical';
        } elseif ($data->dht_temp < 20 || $data->dht_temp > 26) {
            $tempStatus = 'Warning';
        }

        // PH
        $phStatus = 'Normal';

        if ($data->ph < 5.5 || $data->ph > 6.5) {
            $phStatus = 'Critical';
        } elseif ($data->ph < 5.8 || $data->ph > 6.2) {
            $phStatus = 'Warning';
        }

        // TDS
        $tdsStatus = 'Normal';

        if ($data->tds < 600 || $data->tds > 1500) {
            $tdsStatus = 'Critical';
        } elseif ($data->tds < 800 || $data->tds > 1200) {
            $tdsStatus = 'Warning';
        }

        // WATER
        $waterStatus = 'Normal';

        if ($data->water_level < 30) {
            $waterStatus = 'Critical';
        } elseif ($data->water_level < 50) {
            $waterStatus = 'Warning';
        }

        // FINAL STATUS
        $statuses = [
            $tempStatus,
            $phStatus,
            $tdsStatus,
            $waterStatus
        ];

        $critical = collect($statuses)
            ->filter(fn($s) => $s == 'Critical')
            ->count();

        $warning = collect($statuses)
            ->filter(fn($s) => $s == 'Warning')
            ->count();

        $finalStatus = 'Normal';

        if ($critical >= 2) {
            $finalStatus = 'Critical';
        } elseif ($critical >= 1 || $warning >= 2) {
            $finalStatus = 'Warning';
        }

        if (!$status) {
            return true;
        }

        return $finalStatus == $status;
    });

// TOTAL RECORDS BEFORE PAGINATION
$totalRecords = $sensorData->count();

    // PAGINATION
$currentPage = LengthAwarePaginator::resolveCurrentPage();

$perPage = 50;

$currentItems = $sensorData
    ->slice(($currentPage - 1) * $perPage, $perPage)
    ->values();

$sensorData = new LengthAwarePaginator(
    $currentItems,
    $sensorData->count(),
    $perPage,
    $currentPage,
    [
        'path' => request()->url(),
        'query' => request()->query(),
    ]
);

// AVERAGES
$avgTemp = round($sensorData->avg('dht_temp'), 1);

$avgPh = round($sensorData->avg('ph'), 1);

$avgTds = round($sensorData->avg('tds'), 0);

$avgWaterLevel = round($sensorData->avg('water_level'), 0);

return view('staff.sensor-data', compact(
    'sensorData',
    'dates',
    'selectedDate',
    'selectedCrop',
    'totalRecords',
    'avgTemp',
    'avgPh',
    'avgTds',
    'avgWaterLevel'
));
    }
public function exportCsv(Request $request)
{
    $selectedDate = $request->date;
    $status = $request->status;

    $sensorData = DB::table('sensor_logs')
        ->whereDate('timestamp', $selectedDate)
        ->orderBy('timestamp', 'asc')
        ->get()
        ->filter(function ($data) use ($status) {

            $tempStatus = 'Normal';

            if ($data->dht_temp < 18 || $data->dht_temp > 28) {
                $tempStatus = 'Critical';
            } elseif ($data->dht_temp < 20 || $data->dht_temp > 26) {
                $tempStatus = 'Warning';
            }

            $phStatus = 'Normal';

            if ($data->ph < 5.5 || $data->ph > 6.5) {
                $phStatus = 'Critical';
            } elseif ($data->ph < 5.8 || $data->ph > 6.2) {
                $phStatus = 'Warning';
            }

            $tdsStatus = 'Normal';

            if ($data->tds < 600 || $data->tds > 1500) {
                $tdsStatus = 'Critical';
            } elseif ($data->tds < 800 || $data->tds > 1200) {
                $tdsStatus = 'Warning';
            }

            $waterStatus = 'Normal';

            if ($data->water_level < 30) {
                $waterStatus = 'Critical';
            } elseif ($data->water_level < 50) {
                $waterStatus = 'Warning';
            }

            $statuses = [
                $tempStatus,
                $phStatus,
                $tdsStatus,
                $waterStatus
            ];

            $critical = collect($statuses)
                ->filter(fn($s) => $s == 'Critical')
                ->count();

            $warning = collect($statuses)
                ->filter(fn($s) => $s == 'Warning')
                ->count();

            $finalStatus = 'Normal';

            if ($critical >= 2) {
                $finalStatus = 'Critical';
            } elseif ($critical >= 1 || $warning >= 2) {
                $finalStatus = 'Warning';
            }

            if (!$status) {
                return true;
            }

            return $finalStatus == $status;
        });

    $filename = 'sensor-data-' . $selectedDate . '.csv';

    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => "attachment; filename=\"$filename\"",
    ];

    $callback = function () use ($sensorData) {

        $file = fopen('php://output', 'w');

        fputcsv($file, [
            'Timestamp',
            'Temperature',
            'pH',
            'TDS',
            'Water Level'
        ]);

        foreach ($sensorData as $row) {

            fputcsv($file, [
                $row->timestamp,
                $row->dht_temp,
                $row->ph,
                $row->tds,
                $row->water_level
            ]);
        }

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}

public function importDataset(Request $request)
{
    set_time_limit(300);

    if (Auth::user()->role !== 'admin')
{
    abort(403);
}
    $request->validate([
        'dataset' => 'required|mimes:csv,txt'
    ]);

    $crop = $request->crop;

$table = 'sensor_logs';

if($crop == 'Lettuce')
{
    $table = 'sensor_logs_lettuce';
}
elseif($crop == 'Chili')
{
    $table = 'sensor_logs_chili';
}
    DB::beginTransaction();

    try {

        $file = fopen(
            $request->file('dataset')->getRealPath(),
            'r'
        );

        $header = fgetcsv($file);

       DB::table($table)->delete();

$batch = [];

while (($row = fgetcsv($file)) !== false)
{
    $data = array_combine($header, $row);

    $batch[] = [

        'timestamp' => \Carbon\Carbon::createFromFormat(
    'd/m/Y H:i',
    trim($data['timestamp'])
)->format('Y-m-d H:i:s'),

        'dht_temp' => $data['DHT_temp'],

        'tds' => $data['TDS'],

        'water_level' => $data['water_level'],

        'ph' => $data['pH'],

        'add_water' =>
    strtolower(trim($data['add_water'])) === 't',

'ph_reducer' =>
    strtolower(trim($data['ph_reducer'])) === 't',

'ex_fan' =>
    strtolower(trim($data['ex_fan'])) === 't',

'nutrient_adder' =>
    strtolower(trim($data['nutrients_adder'])) === 't',

    'plant_id' =>
        $table == 'sensor_logs' ? 2 :
        ($table == 'sensor_logs_lettuce' ? 1 : 3),
    
        'created_at' => now(),

        'updated_at' => now()

    ];

    if (count($batch) >= 1000)
    {
        DB::table($table)->insert($batch);

        $batch = [];
    }
}

if (!empty($batch))
{
    DB::table($table)->insert($batch);
}
        fclose($file);

        DB::commit();

AuditTrail::create([

 'user_id' => Auth::id(),

    'user_name' => Auth::user()->name,

    'role' => Auth::user()->role,

    'assigned_crop' => null,

    'action' => 'Imported Dataset',

    'details' =>
    'Imported monitoring dataset: ' .
    $request->file('dataset')->getClientOriginalName(),
    'ip_address' => request()->ip(),

]);

       return redirect()->route('sensor.data', [
    'crop' => $crop
])->with(
    'success',
    $crop . ' dataset imported successfully.'
);

    } catch (\Exception $e) {

        DB::rollBack();

        return back()->with(
            'error',
            $e->getMessage()
        );
    }
}
}

