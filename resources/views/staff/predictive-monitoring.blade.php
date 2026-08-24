<x-app-layout>

<div class="flex justify-center py-4 sm:py-8 px-4 sm:px-6 overflow-x-hidden">

<div
    class="space-y-6 w-full max-w-[1200px] min-w-0">

    {{-- HEADER --}}
<div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-200">

    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4">

        <div>

            <h1 class="text-4xl font-bold text-gray-900">
            Predictive Monitoring
            </h1>

            <p class="text-sm text-gray-500 mt-2">
                Predict environmental values for the same monitoring session on the next day.
            </p>

            @if(auth()->user()->role === 'staff')

<p class="text-sm text-green-700 mt-2">

Assigned Crop:
<strong>{{ $crop }}</strong>

</p>

@endif

        </div>

        <div class="border border-gray-200 rounded-2xl px-5 py-3 bg-white w-full sm:w-auto">

            <p class="text-xs text-gray-500">
                Last Updated
            </p>

            <p class="font-semibold text-gray-800">
                26 Dec 2023, 10:00 AM
            </p>

        </div>

    </div>

</div>

    {{-- FILTER --}}
    <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-200">

        <form method="GET"
              action="{{ route('predictive.monitoring') }}">



@if($startTime && $endTime)

<div class="mb-6 bg-green-50 border border-green-100 rounded-xl px-5 py-4 flex items-center gap-3">

    <div class="w-10 h-10 rounded-xl bg-green-100 flex items-center justify-center">

    <i class="fas fa-clock text-green-700"></i>

</div>

    <span class="text-sm text-gray-600">
        Session Time Range:
    </span>

    <span class="font-semibold text-gray-800">
        {{ $startTime }} - {{ $endTime }}
    </span>

</div>

@endif
            <div class="flex flex-col sm:flex-row sm:flex-wrap gap-5 items-stretch sm:items-end">

                <div>
                    <label class="block mb-2 text-sm font-semibold text-gray-700">
                        Select Date
                    </label>

                    <input
    type="date"
    name="date"
    value="{{ request('date','2023-12-26') }}"
    min="2023-11-26"
    max="2023-12-26"
    class="w-full sm:w-64 h-12 border border-gray-300 rounded-xl px-4">
                </div>
    
    @if(auth()->user()->role === 'admin')

<div>

<label class="block mb-2 text-sm font-semibold text-gray-700">
    Crop
</label>

<select
    name="crop"
   class="w-full sm:w-56 h-12 border border-gray-300 rounded-xl px-4">

    <option value="Pak Choy"
        {{ ($crop ?? '') == 'Pak Choy' ? 'selected' : '' }}>
        Pak Choy
    </option>

    <option value="Lettuce"
        {{ ($crop ?? '') == 'Lettuce' ? 'selected' : '' }}>
        Lettuce
    </option>

    <option value="Chili"
        {{ ($crop ?? '') == 'Chili' ? 'selected' : '' }}>
        Chili
    </option>

</select>

</div>

@endif

                <div>
                    <label class="block mb-2 text-sm font-semibold text-gray-700">
                        Monitoring Session
                    </label>
                   
                    <select
                        name="session"
                       class="w-56 h-12 border border-gray-300 rounded-xl px-4">

                        <option value="am"
                            {{ request('session') == 'am' ? 'selected' : '' }}>
                            AM Session
                        </option>

                        <option value="pm"
                            {{ request('session') == 'pm' ? 'selected' : '' }}>
                            PM Session
                        </option>

                    </select>
                </div>

                <button
                    type="submit"
                    class="bg-[#546B41] text-white px-6 h-12 rounded-xl font-medium">

                    Apply Filter

                </button>

            </div>

        </form>

    </div>

{{-- CURRENT + FORECAST ENVIRONMENT --}}

<div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-200">

    {{-- CURRENT --}}
<h2 class="text-2xl font-bold mb-6">
    Selected Session Average
</h2>

<p class="text-sm text-gray-500 mb-6">
    Average environmental values from the selected monitoring session.
</p>


@if(empty($current))

<div style="
    text-align:center;
    padding:50px;
    border:2px dashed #d1d5db;
    border-radius:20px;
    background:#f9fafb;
    margin-bottom:40px;
">

    <i class="fas fa-database"
       style="
            font-size:50px;
            color:#9ca3af;
       ">
    </i>

    <h3 style="
        margin-top:15px;
        font-size:22px;
        font-weight:600;
        color:#374151;
    ">
        No Current Data Available
    </h3>

    <p style="
        color:#6b7280;
        margin-top:8px;
    ">
        No monitoring records found for the selected date and session.
    </p>

</div>

@else

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5 mb-10">

        <div class="bg-red-50 border border-red-100 p-5 rounded-2xl">
            <div style="
    font-size:28px;
    color:#ef4444;
">
    <i class="fas fa-temperature-high"></i>
</div>
            <p class="text-gray-500 text-sm mt-2">Temperature</p>
            <h3 style="font-size:36px;font-weight:700;color:#ef4444;">
                {{ $current['temperature'] ?? '--' }}°C
            </h3>
        </div>

        <div class="bg-orange-50 border border-orange-100 p-5 rounded-2xl">
            <div style="
    font-size:28px;
    color:#f59e0b;
">
    <i class="fas fa-vial"></i>
</div>
            <p class="text-gray-500 text-sm mt-2">pH Level</p>
            <h3 style="font-size:36px;font-weight:700;color:#f59e0b;">
                {{ $current['ph'] ?? '--' }}
            </h3>
        </div>

        <div class="bg-green-50 border border-green-100 p-5 rounded-2xl">
            <div style="
    font-size:28px;
    color:#22c55e;
">
    <i class="fas fa-tint"></i>
</div>
            <p class="text-gray-500 text-sm mt-2">TDS</p>
            <h3 style="font-size:36px;font-weight:700;color:#22c55e;">
                {{ isset($current['tds']) ? number_format($current['tds'],0) : '--' }}
            </h3>
        </div>

        <div class="bg-blue-50 border border-blue-100 p-5 rounded-2xl">
            <div style="
    font-size:28px;
    color:#3b82f6;
">
    <i class="fas fa-water"></i>
</div>
            <p class="text-gray-500 text-sm mt-2">Water Level</p>
            <h3 style="font-size:36px;font-weight:700;color:#3b82f6;">
                {{ $current['water'] ?? '--' }}%
            </h3>
        </div>

    </div>


@endif

{{-- FORECAST --}}
<h2 class="text-2xl font-bold mb-6">
    {{ $forecastSession }} Prediction
</h2>

<p class="text-sm text-gray-500 mb-6">

    Predict average environmental values for

    <span class="font-semibold">
        {{ $forecastRange }}
    </span>

</p>

@if(empty($forecast))

<div style="
    text-align:center;
    padding:50px;
    border:2px dashed #d1d5db;
    border-radius:20px;
    background:#f9fafb;
">

    <i class="fas fa-chart-line"
       style="
            font-size:50px;
            color:#9ca3af;
       ">
    </i>

    <h3 style="
        margin-top:15px;
        font-size:22px;
        font-weight:600;
        color:#374151;
    ">
        No Forecast Available
    </h3>

    <p style="
        color:#6b7280;
        margin-top:8px;
    ">
        Prediction cannot be generated because insufficient monitoring data was found.
    </p>

</div>

@else

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">

    {{-- Temperature --}}
    <div class="bg-red-50 border border-red-100 p-5 rounded-2xl">

        <div style="
    font-size:28px;
    color:#ef4444;
">
    <i class="fas fa-temperature-high"></i>
</div>

        <p class="text-gray-500 text-sm mt-2">
            Temperature
        </p>

        <h3 style="
            font-size:36px;
            font-weight:700;
            color:#ef4444;
            margin-top:8px;
        ">
            {{ $forecast['temperature'] ?? '--' }}°C
        </h3>

   <p style="
    color:#546B41;
    font-weight:600;
    margin-top:8px;
">
    Predicted
</p>

    </div>

    {{-- pH --}}
    <div class="bg-orange-50 border border-orange-100 p-5 rounded-2xl">

        <div style="
    font-size:28px;
    color:#f59e0b;
">
    <i class="fas fa-vial"></i>
</div>

        <p class="text-gray-500 text-sm mt-2">
            pH Level
        </p>

        <h3 style="
            font-size:36px;
            font-weight:700;
            color:#f59e0b;
            margin-top:8px;
        ">
            {{ $forecast['ph'] ?? '--' }}
        </h3>

   <p style="
    color:#546B41;
    font-weight:600;
    margin-top:8px;
">
    Predicted
</p>

    </div>

    {{-- TDS --}}
    <div class="bg-green-50 border border-green-100 p-5 rounded-2xl">

        <div style="
    font-size:28px;
    color:#22c55e;
">
    <i class="fas fa-tint"></i>
</div>

        <p class="text-gray-500 text-sm mt-2">
            TDS
        </p>

        <h3 style="
            font-size:36px;
            font-weight:700;
            color:#22c55e;
            margin-top:8px;
        ">
            {{ isset($forecast['tds']) ? number_format($forecast['tds'],0) : '--' }}
        </h3>

    <p style="
    color:#546B41;
    font-weight:600;
    margin-top:8px;
">
    Predicted
</p>

    </div>

    {{-- Water Level --}}
    <div class="bg-blue-50 border border-blue-100 p-5 rounded-2xl">

        <div style="
    font-size:28px;
    color:#3b82f6;
">
    <i class="fas fa-water"></i>
</div>

        <p class="text-gray-500 text-sm mt-2">
            Water Level
        </p>

        <h3 style="
            font-size:36px;
            font-weight:700;
            color:#3b82f6;
            margin-top:8px;
        ">
            {{ ($forecast['water'] ?? '--') }}%
        </h3>

<p style="
    color:#546B41;
    font-weight:600;
    margin-top:8px;
">
    Predicted
</p>


    </div>

</div>

@endif

</div>

<div class="grid grid-cols-1 gap-6">

    {{-- RISK ASSESSMENT --}}
    <div class="lg:col-span-4 bg-white p-6 rounded-3xl shadow-sm border border-gray-200">

<h2 class="text-xl font-bold mb-8">
            Prediction Insight
        </h2>

        @if(empty($risk))

<div style="
    text-align:center;
    padding:50px;
    border:2px dashed #d1d5db;
    border-radius:20px;
    background:#f9fafb;
">

    <i class="fas fa-shield-alt"
       style="
            font-size:50px;
            color:#9ca3af;
       ">
    </i>

    <h3 style="
        margin-top:15px;
        font-size:22px;
        font-weight:600;
        color:#374151;
    ">
        No Risk Assessment Available
    </h3>

    <p style="
        color:#6b7280;
        margin-top:8px;
    ">
        Risk analysis cannot be generated because there is insufficient monitoring data.
    </p>

</div>

@else

<div style="
    display:flex;
    flex-direction:column;
    gap:20px;
    margin-top:24px;
">

    @foreach($risk as $item)


                <div class="bg-gray-50 border border-gray-200 rounded-2xl p-5">

                    <div class="flex justify-between items-center">

                        <h3 class="font-semibold">
                            {{ $item['parameter'] }}
                        </h3>

                        @if($item['status'] == 'Critical')

                            <span class="text-red-600 font-bold">
                                Critical
                            </span>

                        @elseif($item['status'] == 'Warning')

                            <span class="text-yellow-600 font-bold">
                                Warning
                            </span>

                        @else

                            <span class="text-green-600 font-bold">
                                Normal
                            </span>

                        @endif

                    </div>

                    <p class="text-gray-500 mt-2 text-sm">
                        {{ $item['message'] }}
                    </p>

                </div>

            @endforeach

</div>

@endif

    </div>

{{-- OVERALL RISK --}}

<div class="hidden">

<h2 class="text-lg font-semibold text-gray-700 mb-6">
    Overall Risk Level
</h2>

<div class="flex flex-col items-center justify-center"
     style="height:380px;">
@if(empty($risk))

<div class="w-24 h-24 rounded-full mb-6 shadow-lg flex items-center justify-center"
     style="background:#9ca3af;">

    <i class="fas fa-question text-white text-4xl"></i>

</div>

<h3 class="text-3xl font-bold text-gray-500">
    N/A
</h3>

@elseif($overallRisk == 'HIGH')

<div class="w-24 h-24 rounded-full mb-6 shadow-lg flex items-center justify-center"
     style="background:#ef4444;">

    <i class="fas fa-exclamation-triangle text-white text-4xl"></i>

</div>

<h3 class="text-3xl font-bold text-red-600">
    HIGH
</h3>

@elseif($overallRisk == 'MEDIUM')

<div class="w-24 h-24 rounded-full mb-6 shadow-lg flex items-center justify-center"
     style="background:#facc15;">

    <i class="fas fa-exclamation-triangle text-white text-4xl"></i>

</div>

<h3 class="text-3xl font-bold text-yellow-500">
    MEDIUM
</h3>

@else

<div class="w-24 h-24 rounded-full mb-6 shadow-lg flex items-center justify-center"
     style="background:#22c55e;">

    <i class="fas fa-check-circle text-white text-4xl"></i>

</div>

<h3 class="text-3xl font-bold text-green-600">
    LOW
</h3>

@endif

@if(empty($risk))

<p class="text-gray-500 mt-4 text-center">
    Insufficient data for risk analysis.
</p>

@else

<p class="text-gray-500 mt-4 text-center">
    Based on forecast analysis and risk evaluation.
</p>

@endif

</div>

</div>

</div>

<div>

    <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-200">


    Crop Suitability Analysis
@if(auth()->user()->role === 'admin')

<span class="text-sm text-gray-500">
({{ $crop }})
</span>

@endif

    <p class="text-gray-500 mb-6">
        Evaluate whether the predicted environment is suitable for the selected crop.
    </p>

    <form
    method="POST"
    action="{{ route('qc.evaluate') }}">

    @csrf

    <input
        type="hidden"
        name="selected_date"
        value="{{ request('date','2023-12-26') }}">

    <input type="hidden"
       name="forecast_temperature"
       value="{{ $forecast['temperature'] ?? '' }}">

<input type="hidden"
       name="forecast_ph"
       value="{{ $forecast['ph'] ?? '' }}">

<input type="hidden"
       name="forecast_tds"
       value="{{ $forecast['tds'] ?? '' }}">

<input type="hidden"
       name="forecast_water"
       value="{{ $forecast['water'] ?? '' }}">

@if(auth()->user()->role === 'admin')

<div class="flex flex-col sm:flex-row sm:items-end gap-4 sm:gap-6">

    <div>

        <label class="block mb-2 text-sm font-semibold text-gray-700">
            Selected Crop
        </label>

        <input
            type="text"
            value="{{ $crop }}"
            readonly
            class="w-full sm:w-64 h-12 border border-gray-300 rounded-xl px-4 bg-gray-100 text-gray-700">

        <input
            type="hidden"
            name="plant_name"
            value="{{ $crop }}">

    </div>

    <button
        type="submit"
        class="bg-[#546B41] text-white px-6 h-12 rounded-xl font-medium">

        Check Condition

    </button>

</div>

@else

<input
    type="hidden"
    name="plant_name"
    value="{{ $crop }}">

<button
    type="submit"
    class="bg-[#546B41] text-white px-6 h-12 rounded-xl font-medium">

    Check Condition

</button>

@endif

    </form>

@if(session('qc_status'))

@php

    $issueCount = count(session('qc_issues', []));

    if ($issueCount >= 3)
    {
        $riskLevel = 'High';
    }
    elseif ($issueCount >= 1)
    {
        $riskLevel = 'Medium';
    }
    else
    {
        $riskLevel = 'Low';
    }

@endphp

<div class="mt-6">

<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

    {{-- SUITABILITY RESULT --}}
    <div
    class="bg-blue-50 border border-blue-200 rounded-2xl p-6"
    style="
        display:flex;
        flex-direction:column;
        justify-content:center;
    ">


        <p class="text-sm text-gray-500 mb-2">
   Environmental Condition
</p>

       <div
    style="font-size:42px;font-weight:700;margin-bottom:10px;"
    class="
        @if(session('suitability_score') >= 75)
            text-green-600
        @elseif(session('suitability_score') >= 50)
            text-yellow-500
        @else
            text-red-600
        @endif
    ">

    {{ session('suitability_score') }}%

</div>
    
       <h3 class="text-2xl font-bold">

    @if(session('qc_status') == 'Optimal Condition')

        <span class="text-green-600">
            Optimal Condition
        </span>

    @elseif(session('qc_status') == 'Needs Improvement')

        <span class="text-yellow-600">
            Needs Improvement
        </span>

    @else

        <span class="text-red-600">
            Critical Condition
        </span>

    @endif

</h3>

        <p class="text-gray-500 mt-2">
            {{ session('qc_plant') }}
        </p>

    </div>


<div
    class="bg-gray-50 border border-gray-200 rounded-2xl p-6"
    style="
        display:flex;
        flex-direction:column;
        justify-content:center;
    ">


    <p class="text-sm text-gray-500 mb-2">
        Risk Analysis
    </p>

    <p class="text-lg font-semibold text-yellow-600 mb-4">
    {{ $riskLevel }} Risk Detected
     </p>

    <div class="space-y-3">

        @foreach(session('qc_issues', []) as $issue)

            <div class="text-gray-700 text-sm">

                • {{ $issue }}

            </div>

        @endforeach

    </div>

    </div>

</div>

{{-- AI RECOMMENDATION --}}

<div class="bg-blue-50 border border-blue-200 rounded-2xl p-5 mt-4">

    <div class="flex items-center gap-3 mb-4">

        <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">

            <i class="fas fa-robot text-blue-600"></i>

        </div>

        <div>

            <h3 class="font-semibold text-gray-800">
                AI Recommendation
            </h3>

            <p class="text-sm text-gray-500">
                AI-generated improvement suggestions
            </p>

        </div>

    </div>

    <div class="text-gray-700 leading-7">

        {!! nl2br(e(session('ai_result'))) !!}

    </div>

</div>

@endif

        {{-- DATE --}}
        <div style="
    margin-top:16px;
    font-size:12px;
    color:#9ca3af;
">

    Crop evaluated:
    {{ session('qc_plant') ?? $crop }}

</div>

    </div>

    </div>

    <div>

</div>

</div>

</div>

</x-app-layout>