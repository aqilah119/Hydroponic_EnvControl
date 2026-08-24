<x-app-layout>

<div class="max-w-7xl mx-auto space-y-6">

@php

$selectedDate = request('selected_date')
    ?? DB::table('sensor_logs')
        ->latest('timestamp')
        ->value(DB::raw('DATE(timestamp)'));

$availableDates = DB::table('sensor_logs')
    ->selectRaw('DATE(timestamp) as date')
    ->distinct()
    ->orderBy('date', 'desc')
    ->pluck('date');

$avgData = DB::table('sensor_logs')
    ->whereDate('timestamp', $selectedDate)
    ->selectRaw('
        AVG(dht_temp) as avg_temp,
        AVG(ph) as avg_ph,
        AVG(tds) as avg_tds,
        AVG(water_level) as avg_water,

        AVG(CASE WHEN ex_fan = true THEN 1 ELSE 0 END) as avg_ex_fan,

        AVG(CASE WHEN ph_reducer = true THEN 1 ELSE 0 END) as avg_ph_reducer,

        AVG(CASE WHEN nutrient_adder = true THEN 1 ELSE 0 END) as avg_nutrient_adder,

        AVG(CASE WHEN add_water = true THEN 1 ELSE 0 END) as avg_add_water
    ')
    ->first();

$plants = DB::table('plants')
    ->where('status', 'active')
    ->pluck('name');

@endphp

    {{-- HEADER --}}
    
    <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-green-600">

        <h1 class="text-2xl font-semibold text-gray-800">
             Crop Analysis & AI Recommendation
        </h1>

        <p class="text-sm text-gray-500 mt-1">
              Analyzing crop condtion & AI recommendation 
        </p>

    </div>

<div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-200">

    <form method="GET">

        <div style="
    display:flex;
    align-items:flex-end;
    gap:20px;
">

            {{-- DATE --}}
            <div style="width:320px;">

                <label style="
                    display:block;
                    font-size:15px;
                    font-weight:600;
                    color:#374151;
                    margin-bottom:12px;
                ">
                    Select Monitoring Date
                </label>

                <select
                    name="selected_date"

                    style="
                        width:100%;
                        height:56px;
                        border:1px solid #d1d5db;
                        border-radius:18px;
                        padding:0 18px;
                        color:#374151;
                        background:white;
                        outline:none;
                    ">

                    @foreach($availableDates as $date)

                        <option value="{{ $date }}"
                            {{ $selectedDate == $date ? 'selected' : '' }}>

                            {{ $date }}

                        </option>

                    @endforeach

                </select>

            </div>

            {{-- BUTTON --}}
            <button
    type="submit"

    style="
        background:#546B41;
        color:white;
        height:56px;
        padding:0 28px;
        border-radius:18px;
        font-weight:600;
        border:none;
        cursor:pointer;
        white-space:nowrap;
        box-shadow:0 2px 8px rgba(0,0,0,0.08);
        margin-bottom:1px;
    ">
                Apply Filter

            </button>

        </div>

    </form>

</div>

</div>

{{-- ENVIRONMENT SUMMARY --}}
<div style="
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:18px;
    margin-top:10px;
">

    {{-- TEMPERATURE --}}
    <div class="bg-white rounded-3xl p-5 shadow-sm border border-gray-200">

        <p style="
            font-size:14px;
            color:#6b7280;
            margin-bottom:14px;
            font-weight:500;
        ">
            Average Temperature
        </p>

        <h2 style="
            font-size:34px;
            font-weight:700;
            color:#ef4444;
            line-height:1;
        ">
            {{ round($avgData->avg_temp, 1) }}°C
        </h2>

    </div>

    {{-- PH --}}
    <div class="bg-white rounded-3xl p-5 shadow-sm border border-gray-200">

        <p style="
            font-size:14px;
            color:#6b7280;
            margin-bottom:14px;
            font-weight:500;
        ">
            Average pH
        </p>

        <h2 style="
            font-size:34px;
            font-weight:700;
            color:#f59e0b;
            line-height:1;
        ">
            {{ round($avgData->avg_ph, 1) }}
        </h2>

    </div>

    {{-- TDS --}}
    <div class="bg-white rounded-3xl p-5 shadow-sm border border-gray-200">

        <p style="
            font-size:14px;
            color:#6b7280;
            margin-bottom:14px;
            font-weight:500;
        ">
            Average TDS
        </p>

        <h2 style="
            font-size:34px;
            font-weight:700;
            color:#22c55e;
            line-height:1;
        ">
            {{ round($avgData->avg_tds, 0) }}
        </h2>

    </div>

    {{-- WATER --}}
    <div class="bg-white rounded-3xl p-5 shadow-sm border border-gray-200">

        <p style="
            font-size:14px;
            color:#6b7280;
            margin-bottom:14px;
            font-weight:500;
        ">
            Average Water Level
        </p>

        <h2 style="
            font-size:34px;
            font-weight:700;
            color:#3b82f6;
            line-height:1;
        ">
            {{ round($avgData->avg_water, 0) }}%
        </h2>

    </div>

</div>

{{-- EXISTING CROP SUITABILITY CHECK --}}

<div class="bg-white rounded-3xl p-5 shadow-sm border border-gray-200"
    style="margin-top:22px;">

    {{-- HEADER --}}
    <div class="mb-4">

        <h2 style="
            font-size:22px;
            font-weight:700;
            color:#1f2937;
            margin-bottom:4px;
        ">
        
        Crop Analysis

        </h2>

        <p style="
            color:#6b7280;
            font-size:13px;
        ">
        Environmental condition analysis and AI-based improvement suggestions

        </p>

    </div>

    {{-- FORM --}}
    <form method="POST"
        action="{{ route('qc.evaluate') }}">

        @csrf

        <input type="hidden"
            name="selected_date"
            value="{{ $selectedDate }}">

        <div style="
            display:flex;
            align-items:flex-end;
            gap:16px;
        ">

            {{-- CROP --}}
            <div style="width:300px;">

                <label style="
                    display:block;
                    font-size:14px;
                    font-weight:600;
                    color:#374151;
                    margin-bottom:10px;
                ">
                    Select Crop
                </label>

                @if(auth()->user()->role === 'admin')

<select
    name="plant_name"

    style="
        width:100%;
        height:52px;
        border:1px solid #d1d5db;
        border-radius:16px;
        padding:0 16px;
        color:#374151;
        background:white;
        outline:none;
    ">

    @foreach($plants as $plant)

        <option value="{{ $plant }}"
            {{ session('qc_plant') == $plant ? 'selected' : '' }}>

            {{ $plant }}

        </option>

    @endforeach

</select>

@else

<input
    type="text"
   value="{{ auth()->user()->staff?->plant?->name }}"
    readonly

    style="
        width:100%;
        height:52px;
        border:1px solid #d1d5db;
        border-radius:16px;
        padding:0 16px;
        color:#374151;
        background:#f9fafb;
        outline:none;
    ">

<input
    type="hidden"
    name="plant_name"
    value="{{ auth()->user()->staff?->plant?->name }}"

@endif

            </div>

            {{-- BUTTON --}}
            <button
                type="submit"

                style="
                    background:#546B41;
                    color:white;
                    height:52px;
                    padding:0 24px;
                    border-radius:16px;
                    font-weight:600;
                    border:none;
                    cursor:pointer;
                    white-space:nowrap;
                    box-shadow:0 2px 8px rgba(0,0,0,0.08);
                ">

                Check condition

            </button>

        </div>

    </form>

    {{-- QC RESULT --}}
@if(session('qc_status'))

    <div style="
        margin-top:24px;
        border:1px solid #e5e7eb;
        border-radius:20px;
        padding:20px;
        background:#f9fafb;
    ">

        {{-- TITLE --}}
        <div style="margin-bottom:14px;">

            <h3 style="
                font-size:20px;
                font-weight:700;
                color:#1f2937;
                margin-bottom:4px;
            ">
                {{ session('qc_plant') }}
            </h3>

            @if(session('qc_status') == 'Optimal Condition')

                <p style="
                    color:#16a34a;
                    font-weight:600;
                    font-size:16px;
                ">
                   Environment Stable
                </p>

            @else

                <p style="
                    color:#dc2626;
                    font-weight:600;
                    font-size:16px;
                ">
                    Environment Unstable
                </p>

            @endif

        </div>

        {{-- STABLE --}}
        @if(session('qc_status') == 'Optimal Condition')

    <div style="
        color:#374151;
        font-size:14px;
        line-height:1.8;
    ">

        ✓ Environmental temperature remains stable <br>
        ✓ pH condition is within recommended range <br>
        ✓ Nutrient concentration appears stable <br>
        ✓ Water level is sufficient for current crop condition

    </div>

        @else

            {{-- ISSUES --}}
            <div style="
                color:#374151;
                font-size:14px;
                line-height:1.8;
            ">

                @foreach(session('qc_issues', []) as $issue)

                    • {{ $issue }} <br>

                @endforeach

            </div>

        @endif

        {{-- DATE --}}
        <div style="
            margin-top:16px;
            font-size:12px;
            color:#9ca3af;
        ">

            Based on average sensor data from:
            {{ session('actual_time') }}

        </div>

    </div>

@endif

{{-- AI SOLUTION --}}
@if(session('ai_result'))

    <div style="
        margin-top:24px;
        border:1px solid #dbeafe;
        border-radius:20px;
        padding:22px;
        background:linear-gradient(to bottom right,#eff6ff,#ffffff);
    ">

        {{-- HEADER --}}
        <div style="
            display:flex;
            align-items:center;
            justify-content:space-between;
            margin-bottom:18px;
        ">

            <div>

                <h3 style="
                    font-size:20px;
                    font-weight:700;
                    color:#1f2937;
                    margin-bottom:4px;
                ">
                    AI Solution
                </h3>

                <p style="
                    font-size:13px;
                    color:#6b7280;
                ">
                    AI-generated environmental analysis and actuator recommendations
                </p>

            </div>

            <div style="
                background:#dbeafe;
                color:#2563eb;
                font-size:12px;
                font-weight:600;
                padding:6px 12px;
                border-radius:999px;
            ">

                AI Powered

            </div>

        </div>

        {{-- RESULT --}}
        <div style="
            font-size:14px;
            line-height:1.9;
            color:#374151;
        ">

            {!! nl2br(
                str_replace(
                    ['**', '##', '- '],
                    '',
                    e(session('ai_result'))
                )
            ) !!}

        </div>

    </div>

@endif

</div>

</x-app-layout>