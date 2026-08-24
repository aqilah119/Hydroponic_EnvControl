<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use OpenAI;

class QCController extends Controller
{
 public function evaluate(Request $request)
{
    
    // 🔍 GET PLANT

    $plant = DB::table('plants')
    ->where('name', $request->plant_name)
    ->first();


if (!$plant)
    return back()->with('error','Plant not found.');

$forecastData = (object)[

    'dht_temp' => $request->forecast_temperature,

    'ph' => $request->forecast_ph,

    'tds' => $request->forecast_tds,

    'water_level' => $request->forecast_water,

];

// 📊 AVERAGE SENSOR DATA BASED ON SELECTED DATE
$data = DB::table('sensor_logs')
    ->whereDate('timestamp', $request->selected_date)
    ->selectRaw('
    AVG(ph) as ph,
    AVG(dht_temp) as dht_temp,
    AVG(tds) as tds,
    AVG(water_level) as water_level,

    AVG(CASE WHEN ex_fan = true THEN 1 ELSE 0 END) as avg_ex_fan,

    AVG(CASE WHEN ph_reducer = true THEN 1 ELSE 0 END) as avg_ph_reducer,

    AVG(CASE WHEN nutrient_adder = true THEN 1 ELSE 0 END) as avg_nutrient_adder,

    AVG(CASE WHEN add_water = true THEN 1 ELSE 0 END) as avg_add_water
')
    ->first();


if (!$data || $forecastData->ph === null) {

    return back()->with('error', 'No sensor data found.');

}
   // 🧠 EVALUATION
$status = 'Optimal Condition';
$issues = [];

/* pH */
if ($forecastData->ph < $plant->ph_min) {


    $issues[] =
        'pH too low (Predicted: '
        . $forecastData->ph .
        ' | Required: '
        . $plant->ph_min . ' - ' . $plant->ph_max . ')';

}
elseif ($forecastData->ph > $plant->ph_max) {


    $issues[] =
        'pH too high (Predicted: '
        . $forecastData->ph .
        ' | Required: '
        . $plant->ph_min . ' - ' . $plant->ph_max . ')';
}

/* TEMPERATURE */
if ($forecastData->dht_temp < $plant->temp_min) {


    $issues[] =
        'Temperature too low (Predicted: '
        . $forecastData->dht_temp . '°C | Required: '
        . $plant->temp_min . '°C - '
        . $plant->temp_max . '°C)';

}
elseif ($forecastData->dht_temp > $plant->temp_max) {


    $issues[] =
        'Temperature too high (Predicted: '
        . $forecastData->dht_temp. '°C | Required: '
        . $plant->temp_min . '°C - '
        . $plant->temp_max . '°C)';
}

/* TDS */
if ($forecastData->tds < $plant->tds_min) {
 

    $issues[] =
        'TDS too low (Predicted: '
        . $forecastData->tds .
        ' | Required: '
        . $plant->tds_min . ' - '
        . $plant->tds_max . ')';

}
elseif ($forecastData->tds > $plant->tds_max) {


    $issues[] =
        'TDS too high (Predicted: '
        . $forecastData->tds .
        ' | Required: '
        . $plant->tds_min . ' - '
        . $plant->tds_max . ')';
}

/* WATER */
if ($forecastData->water_level < $plant->water_min) {


    $issues[] =
        'Water level too low (Predicted: '
        . $forecastData->water_level . '% | Minimum: '
        . $plant->water_min . '%)';
}

$issueCount = count($issues);

$suitabilityScore = max(
    0,
    100 - ($issueCount * 25)
);

if ($suitabilityScore >= 75)
{
    $status = 'Optimal Condition';
}
elseif ($suitabilityScore >= 50)
{
    $status = 'Needs Improvement';
}
else
{
    $status = 'Critical Condition';
}

// 🤖 OPENAI CLIENT

$client = OpenAI::client(
    (string) config('services.openai.key')
);

$avgExFan = round($data->avg_ex_fan * 100);
$avgPhReducer = round($data->avg_ph_reducer * 100);
$avgNutrientAdder = round($data->avg_nutrient_adder * 100);
$avgAddWater = round($data->avg_add_water * 100);

// 🤖 AI PROMPT
$prompt = "
You are a hydroponic farming AI assistant.

Analyze the predicted hydroponic environment for {$plant->name}.

Predicted Environmental Conditions:

- Predicted Temperature: {$forecastData->dht_temp}°C
- Predicted pH: {$forecastData->ph}
- Predicted TDS: {$forecastData->tds}
- Predicted Water Level: {$forecastData->water_level}%

Actuator Activity Analysis:

- Exhaust Fan Active: {$data->avg_ex_fan}%
- pH Reducer Active: {$data->avg_ph_reducer}%
- Nutrient Adder Active: {$data->avg_nutrient_adder}%
- Water Refill Active: {$data->avg_add_water}%

Tasks:

1. Evaluate whether the predicted environment is suitable for {$plant->name}.
2. Identify future environmental risks based on the prediction.
3. Explain which predicted parameters are outside the crop requirements.
4. Recommend corrective actions BEFORE the next monitoring session.
5. Keep recommendations practical and easy to understand.

Format response EXACTLY like this:

Prediction Summary:
- ...

Risk Assessment:
- ...
- ...

Recommended Actions:
- ...
- ...

Use natural human-friendly explanations.
Avoid robotic wording.
";

// 🤖 AI RESPONSE
$response = $client->chat()->create([
    'model' => 'gpt-4o-mini',
    'messages' => [
        [
            'role' => 'user',
            'content' => $prompt,
        ]
    ],
    'max_tokens' => 500,
]);

$aiResult = $response->choices[0]->message->content;


return back()->with([
    'qc_status' => $status,
    'qc_issues' => $issues,
    'qc_plant' => $plant->name,
    'suitability_score' => $suitabilityScore,
    'selected_date' => $request->selected_date,
    'ai_result' => $aiResult,
'actual_time' => $request->selected_date

]);

}
}