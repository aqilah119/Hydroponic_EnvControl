<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Auth;
use App\Models\Staff;

class PredictiveMonitoringController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        /* DEFAULT */

        $crop = request('crop', 'Pak Choy');

        /* STAFF AUTO ASSIGNED */

        if ($user->role == 'staff')
        {
            $staff = Staff::with('plant')
                ->where('user_id', $user->id)
                ->first();

            if ($staff)
            {
                $crop = $staff?->plant?->name;
            }
        }

        /* TABLE MAPPING */

        $table = 'sensor_logs';

        if ($crop == 'Lettuce')
        {
            $table = 'sensor_logs_lettuce';
        }
        elseif ($crop == 'Chili')
        {
            $table = 'sensor_logs_chili';
        }

        $date = request('date');
        $session = request('session');

        if (!$session)
        {
            $session = 'am';
        }

        if ($session == 'am')
        {
            $forecastSession = 'Next Day AM Session';
            $forecastRange = '12:00 AM - 11:59 AM';
        }
        else
        {
            $forecastSession = 'Next Day PM Session';
            $forecastRange = '12:00 PM - 11:59 PM';
        }

        // DEFAULT DATE

        if (!$date)
        {
            $date = '2023-12-26';
        }

        // SENSOR QUERY

        $query = DB::table($table)
            ->whereDate('timestamp', $date);

        if ($session == 'am')
        {
            $query->whereTime('timestamp', '>=', '00:00:00')
                ->whereTime('timestamp', '<', '12:00:00');
        }

        elseif ($session == 'pm')
        {
            $query->whereTime('timestamp', '>=', '12:00:00')
                ->whereTime('timestamp', '<', '24:00:00');
        }

        // GET DATA

        $data = $query
            ->orderBy('timestamp', 'asc')
            ->get();

        // SESSION TIME RANGE

        $startTime = null;
        $endTime = null;

        if ($data->count() > 0)
        {
            $startTime = \Carbon\Carbon::parse(
                $data->first()->timestamp
            )->format('h:i A');

            $endTime = \Carbon\Carbon::parse(
                $data->last()->timestamp
            )->format('h:i A');
        }

        if ($data->count() < 2)
        {
            return view(
                'staff.predictive-monitoring',
                [

                    'current' => [],
                    'forecast' => [],
                    'risk' => [],
                    'overallRisk' => null,

                    'forecastSession' => $forecastSession,
                    'forecastRange' => $forecastRange,

                    'startTime' => null,
                    'endTime' => null

                ]
            );
        }

        $historicalData = DB::table($table)
            ->whereDate('timestamp', '>=', '2023-11-26')
            ->whereDate('timestamp', '<=', '2023-12-26')
            ->orderBy('timestamp', 'asc')
            ->get();

        // RUN PYTHON SCRIPT

        $jsonData = json_encode(
            $historicalData->toArray()
        );

        file_put_contents(
            storage_path('app/sensor_data.json'),
            $jsonData
        );

        $process = new Process([

            'python3',

            base_path('python/predict_environment.py'),

            storage_path('app/sensor_data.json'),

            $session,

            $date

        ]);

        $process->run();

        if (!$process->isSuccessful())
        {
            dd($process->getErrorOutput());
        }

        $predictions = json_decode(
            $process->getOutput(),
            true
        );

        $current = $predictions['current'] ?? [];

        $forecast = $predictions['forecast'] ?? [];

        $risk = $predictions['risk'] ?? [];

        $overallRisk = $predictions['overall_risk'] ?? null;

        // RETURN VIEW

        return view(
            'staff.predictive-monitoring',
            compact(

                'crop',
                'current',
                'forecast',
                'risk',
                'overallRisk',

                'forecastSession',
                'forecastRange',

                'startTime',
                'endTime'
            )
        );
    }
}
