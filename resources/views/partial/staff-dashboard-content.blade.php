
<div class="max-w-full space-y-6">

@php

$statusColor = '#16a34a';
$waterColor = '#16a34a';
$phColor = '#16a34a';
$tempColor = '#16a34a';
$tdsColor = '#16a34a';

$selectedDate = request('selected_date', '2023-12-26');
$session = request('session', 'AM');
$selectedCrop = request('crop', 'Pak Choy');

$totalCrops = DB::table('plants')->count();

/* SENSOR TABLE MAPPING */

$sensorTable = 'sensor_logs';

if ($selectedCrop == 'Lettuce') {

    $sensorTable = 'sensor_logs_lettuce';

}
elseif ($selectedCrop == 'Chili') {

    $sensorTable = 'sensor_logs_chili';

}
else {

    $sensorTable = 'sensor_logs'; // Pak Choy

}

$totalAdmins = DB::table('users')
    ->where('role', 'admin')
    ->count();

$lettuceStaff = DB::table('staff')
    ->where('plant_id', 1)
    ->count();

$pakchoyStaff = DB::table('staff')
    ->where('plant_id', 2)
    ->count();

$chiliStaff = DB::table('staff')
    ->where('plant_id', 3)
    ->count();

$assignedCrop =
    auth()->user()->staff?->plant?->name
    ?? 'Not Assigned';
    
if(auth()->user()->role === 'staff'){

    $selectedCrop = $assignedCrop;

}

/* STAFF + ADMIN TABLE MAPPING */

if ($selectedCrop == 'Lettuce') {

    $sensorTable = 'sensor_logs_lettuce';

}
elseif ($selectedCrop == 'Chili') {

    $sensorTable = 'sensor_logs_chili';

}
else {

    $sensorTable = 'sensor_logs';

}

if ($session == 'AM') {

    $startTime = $selectedDate . ' 00:00:00';
    $endTime   = $selectedDate . ' 11:59:59';

} else {

    $startTime = $selectedDate . ' 12:00:00';
    $endTime   = $selectedDate . ' 23:59:59';

}

/* MAIN QUERY */
$latest = DB::table($sensorTable)
    ->whereBetween('timestamp', [$startTime, $endTime])
    ->selectRaw('
        AVG(dht_temp) as dht_temp,
        AVG(ph) as ph,
        AVG(tds) as tds,
        AVG(water_level) as water_level
    ')
    ->first();

$hasData =
    $latest &&
    $latest->dht_temp !== null &&
    $latest->ph !== null &&
    $latest->tds !== null &&
    $latest->water_level !== null;

if(!$hasData){

    $alertTitle = 'No Monitoring Data';

    $alertMessage =
        'No sensor readings available for anomaly analysis.';

    $alertAction =
        'Select another monitoring date or session.';

    $alertStatus =
        'Data Unavailable';

}

/* GRAPH */
$trendData = DB::table($sensorTable)
    ->whereBetween('timestamp', [$startTime, $endTime])
    ->orderBy('timestamp')
    ->limit(100)
    ->get();

/* ARRAY */
$labels = $trendData->pluck('timestamp')->map(fn($t)=>\Carbon\Carbon::parse($t)->format('H:i'))->toArray();
$tempData = $trendData->pluck('dht_temp')->map(fn($v)=>(float)$v)->toArray();
$phData = $trendData->pluck('ph')->map(fn($v)=>(float)$v)->toArray();
$tdsData = $trendData->pluck('tds')->map(fn($v)=>(float)$v)->toArray();
$waterData = $trendData->pluck('water_level')->map(fn($v)=>(float)$v)->toArray();

$addWaterData = $trendData->pluck('add_water')->map(fn($v)=> $v ? 1 : null)->toArray();
$phReducerData = $trendData->pluck('ph_reducer')->map(fn($v)=> $v ? 2 : null)->toArray();
$fanData = $trendData->pluck('ex_fan')->map(fn($v)=> $v ? 3 : null)->toArray();
$nutrientData = $trendData->pluck('nutrient_adder')->map(fn($v)=> $v ? 4 : null)->toArray();

/* CROP THRESHOLD */

if ($selectedCrop == 'Lettuce') {

    $phNormalMin = 5.8;
    $phNormalMax = 6.2;

    $tempNormalMin = 20;
    $tempNormalMax = 24;

    $tdsNormalMin = 560;
    $tdsNormalMax = 840;

}

elseif ($selectedCrop == 'Chili') {

    $phNormalMin = 6.0;
    $phNormalMax = 6.5;

    $tempNormalMin = 22;
    $tempNormalMax = 28;

    $tdsNormalMin = 1260;
    $tdsNormalMax = 1540;

}

else { // Pak Choy

    $phNormalMin = 5.5;
    $phNormalMax = 6.5;

    $tempNormalMin = 20;
    $tempNormalMax = 26;

    $tdsNormalMin = 1050;
    $tdsNormalMax = 1400;

}

$phWarningLow = $phNormalMin - 0.5;
$phWarningHigh = $phNormalMax + 0.5;

$tempWarningLow = $tempNormalMin - 2;
$tempWarningHigh = $tempNormalMax + 2;

$tdsWarningLow = $tdsNormalMin - 250;
$tdsWarningHigh = $tdsNormalMax + 250;

/* STATUS FUNCTION */
function checkStatus($value, $min, $max, $warnMin = null, $warnMax = null) {
    if ($value < $min || $value > $max) return 'Critical';
    if (($warnMin !== null && $value < $warnMin) || ($warnMax !== null && $value > $warnMax)) return 'Warning';
    return 'Normal';
}

/* SUDDEN CHANGE ANOMALY DETECTION */
$tempAnomalies = [];
$phAnomalies = [];
$tdsAnomalies = [];
$waterAnomalies = [];

foreach ($trendData as $index => $row) {

    if ($index == 0) continue;

    $prev = $trendData[$index - 1];

    /* TEMPERATURE SPIKE/DROP */
    if (abs($row->dht_temp - $prev->dht_temp) >= 2) {

        $tempAnomalies[] = $index;

    }

    /* PH SPIKE/DROP */
    if (abs($row->ph - $prev->ph) >= 0.5) {

        $phAnomalies[] = $index;

    }

    /* TDS SPIKE/DROP */
    if (abs($row->tds - $prev->tds) >= 100) {

        $tdsAnomalies[] = $index;

    }

    /* WATER SPIKE/DROP */
    if (abs($row->water_level - $prev->water_level) >= 15) {

        $waterAnomalies[] = $index;

    }

}

/* LATEST SENSOR ROW */
$latestRow = $trendData->last();

/* CURRENT ACTUATOR STATUS */
$currentFan = $latestRow?->ex_fan;
$currentPhReducer = $latestRow?->ph_reducer;
$currentAddWater = $latestRow?->add_water;
$currentNutrient = $latestRow?->nutrient_adder;


/* LABEL */
$phLabel = checkStatus(
    $latest->ph ?? 0,
    $phNormalMin - 0.5,
    $phNormalMax + 0.5,
    $phNormalMin,
    $phNormalMax
);

$tempLabel = checkStatus(
    $latest->dht_temp ?? 0,
    $tempNormalMin - 2,
    $tempNormalMax + 2,
    $tempNormalMin,
    $tempNormalMax
);

$tdsLabel = checkStatus(
    $latest->tds ?? 0,
    $tdsNormalMin - 250,
    $tdsNormalMax + 250,
    $tdsNormalMin,
    $tdsNormalMax
);

$waterLabel = checkStatus($latest->water_level ?? 0, 30, 100, 50, 100);

/* SYSTEM */
$status = 'Normal';

if(!$hasData){

    $status = 'No Data';

}
elseif ($latest) {

    $critical = 0;
    $warning = 0;

    foreach ([$waterLabel,$phLabel,$tempLabel,$tdsLabel] as $l) {

        if ($l=='Critical') $critical++;
        elseif ($l=='Warning') $warning++;

    }

    if ($critical>=2) $status='Danger';
    elseif ($critical>=1 || $warning>=2) $status='Warning';

}


if($hasData){
/* DYNAMIC ALERT */
$alertTitle = 'No Current Anomaly';
$alertMessage = 'No sudden fluctuation detected in current readings.';
$alertAction = 'Continue monitoring.';

if ($selectedDate == '2023-12-26') {

    $alertStatus = 'Monitoring Active';

} else {

    $alertStatus = 'Monitoring Completed';

}

if (in_array(count($trendData)-1, $tempAnomalies)) {

    $alertTitle = 'Temperature Spike Detected';

    if ($currentFan) {

        $alertMessage = 'Fan Status: ON';
        $alertAction = 'Cooling response active.';
        $alertStatus = 'Fan Activated';

    } else {

        $alertMessage = 'Fan Status: OFF';
        $alertAction = 'Recommended: Turn ON Exhaust Fan';
        $alertStatus = 'Cooling Required';

    }

}

elseif (in_array(count($trendData)-1, $phAnomalies)) {

    $alertTitle = 'pH Spike Detected';

    if ($currentPhReducer) {

        $alertMessage = 'pH Reducer Status: ON';
        $alertAction = 'pH correction active.';
        $alertStatus = 'pH Reducer Activated';

    } else {

        $alertMessage = 'pH Reducer Status: OFF';
        $alertAction = 'Recommended: Turn ON pH Reducer';
        $alertStatus = 'pH Adjustment Required';

    }

}

elseif (in_array(count($trendData)-1, $tdsAnomalies)) {

    $alertTitle = 'TDS Spike Detected';

    if ($currentNutrient) {

        $alertMessage = 'Nutrient Adder Status: ON';
        $alertAction = 'Nutrient adjustment active.';
        $alertStatus = 'Nutrient Adjustment Active';

    } else {

        $alertMessage = 'Nutrient Adder Status: OFF';
        $alertAction = 'Recommended: Check Nutrient Flow';
        $alertStatus = 'Nutrient Check Required';

    }

}

elseif (in_array(count($trendData)-1, $waterAnomalies)) {

    $alertTitle = 'Water Drop Detected';

    if ($currentAddWater) {

        $alertMessage = 'Water Pump Status: ON';
        $alertAction = 'Water refill active.';
        $alertStatus = 'Water Refill Active';

    } else {

        $alertMessage = 'Water Pump Status: OFF';
        $alertAction = 'Recommended: Add Water';
        $alertStatus = 'Water Refill Required';

    }
}

}

/* COLOR FUNCTION */
function color($label) {

    if ($label == 'Critical') return '#dc2626';
    if ($label == 'Warning') return '#f59e0b';

    return '#16a34a';

}

if($status == 'No Data'){

    $statusColor = '#6b7280';

}else{

    $statusColor = color(
        $status == 'Danger'
            ? 'Critical'
            : $status
    );

}
$waterColor = color($waterLabel);
$phColor = color($phLabel);
$tempColor = color($tempLabel);
$tdsColor = color($tdsLabel);

@endphp


{{-- HEADER --}}
<div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-[#546B41]">

    <h1 class="text-2xl font-semibold text-gray-800">
        Hydroponic Environment Control System
    </h1>

    <p class="text-sm text-gray-500 mt-1">
        Welcome back, {{ auth()->user()->name }}
    </p>

</div>


@if(auth()->user()->role === 'admin')

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 sm:gap-5 mt-5">

    <div class="bg-white p-5 rounded-xl shadow-sm border"
style="
    border-top:4px solid #546B41;
    box-shadow:0 4px 12px rgba(0,0,0,0.05);
">
        <div class="flex items-center justify-between">

            <div>

                <p class="text-sm text-gray-500">
                    Total Crops
                </p>

                <h2 class="text-3xl font-bold text-gray-800 mt-1">
                    {{ $totalCrops }}
                </h2>

            </div>

            <div style="
                width:55px;
                height:55px;
                border-radius:14px;
                background:#ecfdf5;
                display:flex;
                align-items:center;
                justify-content:center;
                font-size:24px;
            ">
                <i class="fas fa-seedling"
   style="
        color:#546B41;
        font-size:22px;
   ">
</i>
            </div>

        </div>

    </div>

   <div class="bg-white p-5 rounded-xl shadow-sm border"
style="border-top:4px solid #546B41;box-shadow:0 4px 12px rgba(0,0,0,0.05);">

    <p class="text-sm text-gray-500">
        Administrators
    </p>

    <h2 class="text-3xl font-bold text-gray-800 mt-1">
        {{ $totalAdmins }}
    </h2>

</div>


<div class="bg-white p-5 rounded-xl shadow-sm border"
style="border-top:4px solid #546B41;box-shadow:0 4px 12px rgba(0,0,0,0.05);">

    <p class="text-sm text-gray-500">
        Staff - Lettuce
    </p>

    <h2 class="text-3xl font-bold text-gray-800 mt-1">
        {{ $lettuceStaff }}
    </h2>

</div>


<div class="bg-white p-5 rounded-xl shadow-sm border"
style="border-top:4px solid #546B41;box-shadow:0 4px 12px rgba(0,0,0,0.05);">

    <p class="text-sm text-gray-500">
        Staff - Chili
    </p>

    <h2 class="text-3xl font-bold text-gray-800 mt-1">
        {{ $chiliStaff }}
    </h2>

</div>


<div class="bg-white p-5 rounded-xl shadow-sm border"
style="border-top:4px solid #546B41;box-shadow:0 4px 12px rgba(0,0,0,0.05);">

    <p class="text-sm text-gray-500">
        Staff -  Pak Choy
    </p>

    <h2 class="text-3xl font-bold text-gray-800 mt-1">
        {{ $pakchoyStaff }}
    </h2>

</div>

</div>

@endif

@if(auth()->user()->role === 'staff')

<div style="
    display:flex;
    gap:20px;
    margin-top:20px;
">

    <div class="bg-white p-5 rounded-xl shadow-sm border"
style="
    flex:1;
    border-top:4px solid #546B41;
    box-shadow:0 4px 12px rgba(0,0,0,0.05);
">

    <div class="flex items-center justify-between">

        <div>

            <p class="text-sm text-gray-500">
                Assigned Crop
            </p>

            <h2 class="text-2xl font-bold text-[#546B41] mt-2">
                {{ $assignedCrop }}
            </h2>

        </div>

        <div style="
            width:56px;
            height:56px;
            border-radius:14px;
            background:#ecfdf5;
            display:flex;
            align-items:center;
            justify-content:center;
        ">

            <i class="fas fa-leaf"
   style="
        color:#546B41;
        font-size:22px;
   ">
</i>

        </div>

    </div>

</div>

</div>

@endif


{{-- DATE FILTER --}}
<div class="bg-white p-4 rounded-xl shadow-sm flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4">

    <div>

        <p class="text-sm font-medium text-gray-700 flex items-center gap-2">

            <svg xmlns="http://www.w3.org/2000/svg"
                 class="w-4 h-4 text-gray-500"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor">

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>

            </svg>

            Select Date & Monitoring Session

        </p>

    </div>

   <form method="GET" class="flex flex-col sm:flex-row sm:flex-wrap items-stretch sm:items-center gap-3 w-full lg:w-auto">

    <input type="date"
    name="selected_date"
    value="{{ $selectedDate }}"
    min="2023-11-26"
    max="2023-12-26"
   class="border px-3 py-2 rounded text-sm w-full sm:w-auto"


<select name="session"
    class="border px-3 py-2 rounded text-sm w-full sm:w-auto">

    <option value="AM" {{ $session=='AM' ? 'selected' : '' }}>
        AM Session
    </option>

    <option value="PM" {{ $session=='PM' ? 'selected' : '' }}>
        PM Session
    </option>

</select>

@if(auth()->user()->role === 'admin')

@php

$crops = DB::table('plants')
    ->where('status','active')
    ->orderBy('name')
    ->get();

@endphp

<select name="crop"
    class="border px-3 py-2 rounded text-sm w-full sm:w-auto">

@foreach($crops as $crop)

<option value="{{ $crop->name }}"
{{ $selectedCrop == $crop->name ? 'selected' : '' }}>

{{ $crop->name }}

</option>

@endforeach

</select>

@endif

        <button type="submit"
            style="background:#546B41;color:white;padding:10px 20px;border-radius:8px;font-weight:bold;">

            Apply

        </button>

    </form>

</div>


{{-- SENSOR CARDS --}}
@if($latest)

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">

    <div class="bg-white border rounded-xl p-4 h-full">

        <p class="text-xs text-gray-500">System Status</p>

        <h2 class="text-lg font-semibold"
    @style(['color: ' . $statusColor])>
            {{ $status }}
        </h2>

    </div>

    <div class="bg-white border rounded-xl p-4 h-full">

        <p class="text-xs text-gray-500 mb-3">
Average Water Level
</p>

<h2 class="text-lg font-semibold mb-2">
{{ $hasData ? number_format($latest->water_level,0).'%' : 'N/A' }}
</h2>


<p class="mt-2"
@style(['color: ' . $waterColor])>
{{ $hasData ? $waterLabel : 'No Data Available' }}
</p>

    </div>

    <div class="bg-white border rounded-xl p-4 h-full">

        <p class="text-xs text-gray-500 mb-3">
Average pH Level
</p>

        <h2 class="text-lg font-semibold mb-2">
            {{ $hasData ? number_format($latest->ph,2) : 'N/A' }}
        </h2>
 


       <p class="mt-2"
@style(['color: ' . $phColor])>
           {{ $hasData ? $phLabel : 'No Data Available' }}
        </p>

    </div>

    <div class="bg-white border rounded-xl p-4 h-full">

        <p class="text-xs text-gray-500 mb-3">
Average Temperature
</p>

        <h2 class="text-lg font-semibold mb-2">
           {{ $hasData ? number_format($latest->dht_temp,1).'°C' : 'N/A' }}
        </h2>


        <p class="mt-2"
@style(['color: ' . $tempColor])>
{{ $hasData ? $tempLabel : 'No Data Available' }}
        </p>

    </div>

    <div class="bg-white border rounded-xl p-4 h-full">

        <p class="text-xs text-gray-500 mb-3">
Average TDS
</p>

        <h2 class="text-lg font-semibold mb-2">
           {{ $hasData ? number_format($latest->tds,0) : 'N/A' }}
        </h2>


        <p class="mt-2"
@style(['color: ' . $tdsColor])>
           {{ $hasData ? $tdsLabel : 'No Data Available' }}
        </p>

    </div>

</div>

@endif


{{-- GRAPH + ALERT PANEL --}}
@if($latest)

<div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

    {{-- GRAPH --}}
<div class="bg-white p-4 sm:p-6 rounded-xl border shadow-sm lg:col-span-3 
            h-[550px] sm:h-[600px] lg:h-[750px] 
            flex flex-col">

@if($hasData)

    <div class="flex justify-between items-center mb-4 shrink-0">

        <h2 class="font-semibold">
            Environmental Trends ({{ $session }} Session)
        </h2>

    </div>

    <div class="relative flex-1 min-h-0 w-full">
    
        <canvas id="envChart"
        data-labels='@json($labels)'
        data-temp='@json($tempData)'
        data-ph='@json($phData)'
        data-tds='@json($tdsData)'
        data-water='@json($waterData)'
        data-addwater='@json($addWaterData)'
        data-phreducer='@json($phReducerData)'
        data-fan='@json($fanData)'
        data-nutrient='@json($nutrientData)'
        data-temp-anomalies='@json($tempAnomalies)'
        data-ph-anomalies='@json($phAnomalies)'
        data-tds-anomalies='@json($tdsAnomalies)'
        data-water-anomalies='@json($waterAnomalies)'>

    </canvas>
</div>

    @else

<div class="flex flex-col items-center justify-center h-full text-center">
    
    <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center mb-6">

        <i class="fas fa-database text-3xl text-gray-400"></i>

    </div>

    <h3 class="text-3xl font-semibold text-gray-700">
        No Monitoring Data Available
    </h3>

    <p class="text-base text-gray-500 mt-4 max-w-lg leading-relaxed">
        No sensor readings were found for the selected monitoring date and session.
    </p>

</div>

@endif

    </div>


{{-- ALERT PANEL --}}
<div class="bg-white p-5 rounded-xl border shadow-sm lg:col-span-1">

    <div class="flex items-center justify-between mb-4">

        <h2 class="font-semibold text-gray-800">
            Alerts & Actions
        </h2>

    </div>

    <div class="space-y-4">

        <div class="border border-gray-100 rounded-xl p-4 bg-gradient-to-br from-white to-gray-50 shadow-sm">

    <div>

   <p class="text-base font-semibold text-gray-900">
    {{ $alertTitle }}
</p>

    <p class="text-sm text-gray-500 mt-1 leading-relaxed">
        {{ $alertMessage }}
    </p>

</div>

    <div class="mt-5 pt-4 border-t border-gray-100">

        <p class="text-base font-semibold text-gray-900">
    Recommended Action
</p>

        <p class="text-sm text-gray-500 mt-1 leading-relaxed">
            {{ $alertAction }}
        </p>

    </div>

    <div class="mt-4">

       <div
class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full shadow-sm w-fit"

@if($alertStatus == 'Data Unavailable')

style="
background: rgba(107,114,128,0.12);
border: 1px solid rgba(107,114,128,0.25);
backdrop-filter: blur(8px);
"

@else

style="
background: rgba(34,197,94,0.12);
border: 1px solid rgba(34,197,94,0.25);
backdrop-filter: blur(8px);
"

@endif
>

   <span class="w-2 h-2 rounded-full bg-green-500 flex-shrink-0"></span>

    <span
class="text-[11px] font-medium"

@if($alertStatus == 'Data Unavailable')

style="color:#6b7280;"

@else

style="color:#15803d;"

@endif
>
        {{ $alertStatus }}
    </span>

</div>

    </div>

</div>
</div>

</div>

</div>


@endif

{{-- REFERENCE RANGE --}}
<div class="bg-white p-5 rounded-xl border shadow-sm w-full">

    <div class="mb-4">

        <h2 class="font-semibold text-gray-800">
            Environmental Reference Threshold
        </h2>

        <p class="text-gray-400">

Hydroponic Monitoring Standard for

<strong>{{ $selectedCrop }}</strong>

</p>

    </div>

   <div class="overflow-x-auto mt-6 w-full">

    <table class="min-w-full text-sm text-left border-collapse">

        <thead>

               <tr class="border-b border-gray-200 bg-gray-50">

                    <th class="p-3 font-semibold text-gray-700">Parameter</th>
                    <th class="p-3 font-semibold" style="color:#16a34a;">
                                                                 Normal
                    </th>

                    <th class="p-3 font-semibold" style="color:#f59e0b;">
                                                                Warning
                    </th>

                    <th class="p-3 font-semibold" style="color:#dc2626;">
                                                                 Critical
                    </th>

            </thead>

            <tbody class="text-gray-700">

                <tr class="border-b border-gray-100 hover:bg-gray-50 transition">

<td class="p-3 font-medium text-pink-500">
pH
</td>

<td class="p-3">
{{ $phNormalMin }} – {{ $phNormalMax }}
</td>

<td class="p-3">
{{ $phWarningLow }} – {{ $phNormalMin }}
/
{{ $phNormalMax }} – {{ $phWarningHigh }}
</td>

<td class="p-3">
&lt; {{ $phWarningLow }}
/
&gt; {{ $phWarningHigh }}
</td>

</tr>

<tr class="border-b border-gray-100 hover:bg-gray-50 transition">
    <td class="p-3 font-medium text-indigo-600">Temperature</td>
    <td class="p-3">
{{ $tempNormalMin }}°C – {{ $tempNormalMax }}°C
</td>

<td class="p-3">
{{ $tempWarningLow }}°C – {{ $tempNormalMin }}°C
/
{{ $tempNormalMax }}°C – {{ $tempWarningHigh }}°C
</td>

<td class="p-3">
&lt; {{ $tempWarningLow }}°C
/
&gt; {{ $tempWarningHigh }}°C
</td>
</tr>

<tr class="border-b border-gray-100 hover:bg-gray-50 transition">
    <td class="p-3 font-medium text-cyan-500">TDS</td>
    <td class="p-3">
{{ $tdsNormalMin }} – {{ $tdsNormalMax }}
</td>

<td class="p-3">
{{ $tdsWarningLow }} – {{ $tdsNormalMin }}
/
{{ $tdsNormalMax }} – {{ $tdsWarningHigh }}
</td>

<td class="p-3">
&lt; {{ $tdsWarningLow }}
/
&gt; {{ $tdsWarningHigh }}
</td>
</tr>

<tr>
    <td class="p-3 font-medium text-amber-700">Water Level</td>
    <td class="p-3">50% – 100%</td>
    <td class="p-3">30% – 50%</td>
    <td class="p-3">&lt; 30%</td>
</tr>

            </tbody>

        </table>

</div>
<div class="mt-10 pt-6 border-t border-gray-100">

    <div class="flex items-center justify-between mb-4">

        <h2 class="font-semibold text-gray-800">
            Sudden Anomaly Threshold
        </h2>

    </div>

<div class="overflow-x-auto">

    <table class="w-full text-sm text-left border-collapse">

        <thead>

            <tr class="border-b border-gray-200 bg-gray-50">

                <th class="p-3 font-semibold text-gray-700">
                    Parameter
                </th>

                <th class="p-3 font-semibold text-gray-700">
                    Sudden Spike / Drop
                </th>

            </tr>

        </thead>

        <tbody class="text-gray-700">

            <tr class="border-b hover:bg-gray-50 transition">

                <td class="p-3 font-medium text-indigo-600">
                    Temperature
                </td>

                <td class="p-3">
                    ±2°C
                </td>

            </tr>

            <tr class="border-b hover:bg-gray-50 transition">

                <td class="p-3 font-medium text-pink-500">
                    pH
                </td>

                <td class="p-3">
                    ±0.5
                </td>

            </tr>

            <tr class="border-b hover:bg-gray-50 transition">

                <td class="p-3 font-medium text-cyan-500">
                    TDS
                </td>

                <td class="p-3">
                    ±100
                </td>
            </tr>

            <tr class="hover:bg-gray-50 transition">

                <td class="p-3 font-medium text-amber-700">
                    Water Level
                </td>

                <td class="p-3">
                    ±15%
                </td>

            </tr>

        </tbody>

    </table>

</div>

</div>


</div>



{{-- SCRIPT --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

document.addEventListener("DOMContentLoaded", function () {

    const canvas = document.getElementById('envChart');

    if (canvas) {

        const labels = JSON.parse(canvas.dataset.labels || '[]');
        const tempData = JSON.parse(canvas.dataset.temp || '[]');
        const phData = JSON.parse(canvas.dataset.ph || '[]');
        const tdsData = JSON.parse(canvas.dataset.tds || '[]');
        const waterData = JSON.parse(canvas.dataset.water || '[]');

        const addWater = JSON.parse(canvas.dataset.addwater || '[]');
        const phReducer = JSON.parse(canvas.dataset.phreducer || '[]');
        const fan = JSON.parse(canvas.dataset.fan || '[]');
        const nutrient = JSON.parse(canvas.dataset.nutrient || '[]');
        const tempAnomalies = JSON.parse(canvas.dataset.tempAnomalies || '[]');
        const phAnomalies = JSON.parse(canvas.dataset.phAnomalies || '[]');
        const tdsAnomalies = JSON.parse(canvas.dataset.tdsAnomalies || '[]');
        const waterAnomalies = JSON.parse(canvas.dataset.waterAnomalies || '[]');
        function getAnomalyMessages(i, datasetLabel) {

    let messages = [];

    /* TEMPERATURE */
    if (datasetLabel === 'Temperature' && tempAnomalies.includes(i)) {

        if (tempData[i] > tempData[i - 1]) {

            messages.push('⚠ Sudden temperature spike detected');

        } else {

            messages.push('⚠ Sudden temperature drop detected');

        }

    }

    /* PH */
    if (datasetLabel === 'pH' && phAnomalies.includes(i)) {

        if (phData[i] > phData[i - 1]) {

            messages.push('⚠ Sudden pH spike detected');

        } else {

            messages.push('⚠ Sudden pH drop detected');

        }

    }

    /* TDS */
    if (datasetLabel === 'TDS' && tdsAnomalies.includes(i)) {

        if (tdsData[i] > tdsData[i - 1]) {

            messages.push('⚠ Sudden TDS spike detected');

        } else {

            messages.push('⚠ Sudden TDS drop detected');

        }

    }

    /* WATER */
    if (datasetLabel === 'Water' && waterAnomalies.includes(i)) {

        if (waterData[i] > waterData[i - 1]) {

            messages.push('⚠ Sudden water increase detected');

        } else {

            messages.push('⚠ Sudden water drop detected');

        }

    }

    return messages;

}
        new Chart(canvas, {

            type: 'line',

            data: {

                labels: labels,

                datasets: [

                    {
    label: 'Temperature',
    data: tempData,
    borderColor: '#6366f1',
    borderWidth: 2,
    yAxisID: 'y1',
    fill: false,
    tension: 0.3,

    pointRadius: tempData.map((v,i) =>
        tempAnomalies.includes(i) ? 6 : 1
    ),

    pointBackgroundColor: tempData.map((v,i) =>
    tempAnomalies.includes(i)
        ? '#6366f1'
        : '#6366f1'
),

pointBorderColor: tempData.map((v,i) =>
    tempAnomalies.includes(i)
        ? '#ffffff'
        : '#6366f1'
),

pointBorderWidth: tempData.map((v,i) =>
    tempAnomalies.includes(i) ? 2 : 0
),

},
                    {
    label: 'pH',
    data: phData,
    borderColor: '#ec4899',
    borderWidth: 2,
    yAxisID: 'y1',
    fill: false,
    tension: 0.3,

    pointRadius: phData.map((v,i) =>
    phAnomalies.includes(i) ? 5 : 1
),

    pointBackgroundColor: phData.map((v,i) =>
        phAnomalies.includes(i)
            ? '#db2777'
            : '#ec4899'
    )
},
                    {
    label: 'TDS',
    data: tdsData,
    borderColor: '#06b6d4',
    borderWidth: 2,
    yAxisID: 'y2',
    fill: false,
    tension: 0.3,

    pointRadius: tdsData.map((v,i) =>
    tdsAnomalies.includes(i) ? 5 : 1
),

    pointBackgroundColor: tdsData.map((v,i) =>
        tdsAnomalies.includes(i)
            ? '#0891b2'
            : '#06b6d4'
    )
},
                    {
    label: 'Water',
    data: waterData,
    borderColor: '#8b5e3c',
    borderWidth: 2,
    yAxisID: 'y2',
    fill: false,
    tension: 0.3,

    pointRadius: waterData.map((v,i) =>
    waterAnomalies.includes(i) ? 5 : 1
),

    pointBackgroundColor: waterData.map((v,i) =>
        waterAnomalies.includes(i)
            ? '#6f4e37'
: '#8b5e3c'
    )
}

                ]

            },
            
           options: {

    responsive: true,

    maintainAspectRatio: false,

    interaction: {

    mode: 'index',
    intersect: false
},

                plugins: {

                    tooltip: {

                        callbacks: {

                            afterBody: function (context) {

                                const i = context[0].dataIndex;
                                const datasetLabel = context[0].dataset.label;

                               const anomalyMessages = getAnomalyMessages(i, datasetLabel);

return [

    ...(anomalyMessages.length
        ? ['', '--- Anomaly Detection ---', ...anomalyMessages]
        : []),

    '',
    '--- Actuator Control ---',
    'Add Water: ' + (addWater[i] ? 'ON' : 'OFF'),
    'pH Reducer: ' + (phReducer[i] ? 'ON' : 'OFF'),
    'Fan: ' + (fan[i] ? 'ON' : 'OFF'),
    'Nutrient: ' + (nutrient[i] ? 'ON' : 'OFF')

];

                            }

                        }

                    }

                },

                scales: {

                    y1: {
                        position: 'left',
                        min: 0,
                        max: 30
                    },

                    y2: {
    position: 'right',
    min: 0,
    suggestedMax: Math.max(...tdsData) + 100,
                        grid: {
                            drawOnChartArea: false
                        }
                    }

                }

            }

                });

    }

});

</script>
