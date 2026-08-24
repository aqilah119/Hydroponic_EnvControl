<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SimulatorController extends Controller
{
   public function index()
{
    $plants = DB::table('plants')
        ->where('status', 'active')
        ->orderBy('name')
        ->get();

    return view(
    'simulator',
    [
        'plants' => $plants,
        'selectedPlant' => $plants->first(),
        'result' => null
    ]
);

}

    public function run(Request $request)
    {
        $temperature = $request->temperature;
        $ph = $request->ph;
        $tds = $request->tds;
        $water = $request->water;

        $plant = DB::table('plants')
        ->where('id', $request->plant_id)
        ->first();

$parameterStatus = [];

$parameterStatus['temperature'] = (
    $temperature >= $plant->temp_min
    &&
    $temperature <= $plant->temp_max
)
? 'Optimal'
: 'Warning';

$parameterStatus['ph'] = (
    $ph >= $plant->ph_min
    &&
    $ph <= $plant->ph_max
)
? 'Optimal'
: 'Warning';

$parameterStatus['tds'] = (
    $tds >= $plant->tds_min
    &&
    $tds <= $plant->tds_max
)
? 'Optimal'
: 'Warning';

$parameterStatus['water'] = (
    $water >= $plant->water_min
)
? 'Optimal'
: 'Warning';


$score = 100;

        // TEMPERATURE CHECK

if (
    $temperature < $plant->temp_min
    ||
    $temperature > $plant->temp_max
)
{
    $score -= 25;
}

// PH CHECK

if (
    $ph < $plant->ph_min
    ||
    $ph > $plant->ph_max
)
{
    $score -= 25;
}

// TDS

if (
    $tds < $plant->tds_min
    ||
    $tds > $plant->tds_max
)
{
    $score -= 25;
}

// WATER

if (
    $water < $plant->water_min
)
{
    $score -= 25;
}

// GROWTH STATUS

if ($score >= 90)
{
    $growth = 'Excellent';
}
elseif ($score >= 70)
{
    $growth = 'Good';
}
elseif ($score >= 50)
{
    $growth = 'Moderate';
}
else
{
    $growth = 'Poor';
}

// RISK LEVEL

if ($score >= 90)
{
    $risk = 'Low';
}
elseif ($score >= 70)
{
    $risk = 'Medium';
}
else
{
    $risk = 'High';
}

// CROP CONDITION

if ($score >= 90)
{
    $condition = 'Normal';
}
elseif ($score >= 70)
{
    $condition = 'Warning';
}
else
{
    $condition = 'Critical';
}

// SIMULATION INSIGHT

$insight = [];

if ($parameterStatus['temperature'] == 'Warning')
{
    $insight[] =
        'Temperature is outside the recommended range and may affect crop growth.';
}

if ($parameterStatus['ph'] == 'Warning')
{
    $insight[] =
        'pH level is outside the optimal range and may reduce nutrient absorption.';
}

if ($parameterStatus['tds'] == 'Warning')
{
    $insight[] =
        'TDS value is outside the recommended range and may impact nutrient availability.';
}

if ($parameterStatus['water'] == 'Warning')
{
    $insight[] =
        'Water level is below the recommended threshold and may stress the crop.';
}

if (count($insight) == 0)
{
    $insight[] =
        'All environmental parameters are within the recommended range. Healthy crop growth is expected.';
}


return view(
    'simulator',
    [

        'plants' => DB::table('plants')
            ->where('status', 'active')
            ->orderBy('name')
            ->get(),

        'selectedPlant' => $plant,

        'result' => [

            'plant' => $plant->name,

            'temperature' => $temperature,
            'ph' => $ph,
            'tds' => $tds,
            'water' => $water,

            'score' => $score,
            'growth' => $growth,
            'risk' => $risk,
            'condition' => $condition,
            'insight' => $insight,
            'parameterStatus' => $parameterStatus

        ]

    ]
);
    }
}