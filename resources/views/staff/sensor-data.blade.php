<x-app-layout>

<div class="max-w-7xl mx-auto">

@if(session('success'))

<div style="
background:#ecfdf5;
border:1px solid #86efac;
padding:14px;
border-radius:14px;
margin-bottom:18px;
color:#166534;
font-weight:600;
">

{{ session('success') }}

</div>

@endif

@if(session('error'))

<div style="
background:#fef2f2;
border:1px solid #fecaca;
padding:14px;
border-radius:14px;
margin-bottom:18px;
color:#dc2626;
font-weight:600;
">

{{ session('error') }}

</div>

@endif

{{-- HEADER --}}
<div style="
    background:white;
    border:1px solid #e5e7eb;
    border-radius:22px;
    padding:22px 28px;
    box-shadow:0 2px 6px rgba(0,0,0,0.04);
    margin-bottom:18px;
">

    <div>

        {{-- LEFT --}}
        <div>

            <h1 style="
                font-size:24px;
                font-weight:700;
                line-height:1.2;
                color:#1f2937;
                margin:0;
            ">
                Sensor & Actuator Records
            </h1>

            <p style="
                font-size:14px;
                color:#6b7280;
                margin-top:6px;
                margin-bottom:20px;
            ">
                Historical records of sensor & actuator  
            </p>
@if(auth()->user()->role === 'admin')

<p style="
font-size:14px;
color:#556F3D;
font-weight:600;
margin-top:8px;
">
Current Crop: {{ $selectedCrop }}
</p>

<p style="
font-size:13px;
color:#6b7280;
margin-top:6px;
">
To replace the existing dataset for a crop, select the crop and upload a new CSV dataset.
</p>

@endif

        </div>

      <div style="
display:flex;
align-items:flex-end;
gap:12px;
flex-wrap:wrap;
margin-top:20px;
">

@if(auth()->user()->role === 'admin')
<form
    id="importForm"
    action="{{ route('sensor.import.dataset') }}"
    method="POST"
    enctype="multipart/form-data"

    style="
    display:flex;
    flex-direction:column;
    gap:10px;
    ">

    @csrf

    <input
        type="file"
        name="dataset"
        id="datasetInput"
        accept=".csv"
        hidden
        onchange="showFileName(this)">

<div class="dataset-upload-row" style="
display:flex;
align-items:flex-end;
gap:10px;
">
<div>

<label style="
display:block;
font-size:14px;
font-weight:600;
color:#374151;
margin-bottom:10px;
">
Dataset Crop
</label>

<select
name="crop"
id="datasetCrop"
onchange="syncCrop()"

style="
width:220px;
height:52px;
padding:0 18px;
padding-right:40px;
border:1px solid #d1d5db;
border-radius:16px;
background:white;
color:#1f2937;
font-size:15px;
outline:none;
cursor:pointer;
appearance:auto;
-webkit-appearance:menulist;
-moz-appearance:menulist;
">

<option value="Pak Choy"
{{ $selectedCrop == 'Pak Choy' ? 'selected' : '' }}>
Pak Choy
</option>

<option value="Lettuce"
{{ $selectedCrop == 'Lettuce' ? 'selected' : '' }}>
Lettuce
</option>

<option value="Chili"
{{ $selectedCrop == 'Chili' ? 'selected' : '' }}>
Chili
</option>

</select>

</div>

<button
type="button"
            onclick="document.getElementById('datasetInput').click();"

            style="
            background:#556F3D;
            color:white;
            height:50px;
            padding:0 22px;
            border-radius:16px;
            font-weight:600;
            border:none;
            cursor:pointer;
            ">

            Choose CSV

        </button>

        <span
            id="selectedFileName"

            style="
            background:#f9fafb;
            border:1px solid #e5e7eb;
            padding:10px 14px;
            border-radius:12px;
            color:#6b7280;
           width:220px;
            font-size:14px;
            ">

            No file selected

        </span>

    </div>

</form>

<button
    type="button"
    onclick="confirmImport()"

    style="
    background:#1f2937;
    color:white;
    height:50px;
    min-width:180px;
    border-radius:16px;
    font-weight:600;
    border:none;
    cursor:pointer;
    ">

    Import CSV

</button>

@endif

        </div>

    </div>

</div>

  {{-- FILTER SECTION --}}
<div style="
    background:white;
    border:1px solid #e5e7eb;
    border-radius:24px;
    padding:24px 28px;
    box-shadow:0 2px 6px rgba(0,0,0,0.04);
    margin-bottom:18px;
">

<form method="GET" action="{{ route('sensor.data') }}">

    <div style="
display:flex;
align-items:flex-end;
gap:18px;
flex-wrap:wrap;
">

        {{-- DATE --}}
        <div>

            <label style="
                display:block;
                font-size:14px;
                font-weight:600;
                color:#374151;
                margin-bottom:10px;
            ">
                Select Date
            </label>

            <select
    name="date"

    style="
        width:180px;
        height:52px;
        padding:0 18px;
        border:1px solid #d1d5db;
        border-radius:16px;
        background:white;
        color:#1f2937;
        font-size:15px;
        outline:none;
        cursor:pointer;
    ">

                @foreach($dates as $date)

                    <option value="{{ $date }}"
                        {{ $selectedDate == $date ? 'selected' : '' }}>

                        {{ \Carbon\Carbon::parse($date)->format('d-m-Y') }}

                    </option>

                @endforeach

            </select>

        </div>

       @if(auth()->user()->role === 'admin')

{{-- CROP --}}
<div>

<label style="
display:block;
font-size:14px;
font-weight:600;
color:#374151;
margin-bottom:10px;
">
View Crop
</label>

<select
name="crop"
id="viewCrop"

style="
width:180px;
height:52px;
padding:0 18px;
padding-right:40px;
border:1px solid #d1d5db;
border-radius:16px;
background:white;
color:#1f2937;
font-size:15px;
outline:none;
cursor:pointer;
appearance:auto;
-webkit-appearance:menulist;
-moz-appearance:menulist;
">

<option value="Pak Choy"
{{ request('crop') == 'Pak Choy' ? 'selected' : '' }}>
Pak Choy
</option>

<option value="Lettuce"
{{ request('crop') == 'Lettuce' ? 'selected' : '' }}>
Lettuce
</option>

<option value="Chili"
{{ request('crop') == 'Chili' ? 'selected' : '' }}>
Chili
</option>

</select>

</div>

@endif

        {{-- STATUS --}}
        <div>
            
            <label style="
                display:block;
                font-size:14px;
                font-weight:600;
                color:#374151;
                margin-bottom:10px;
            ">
                System Status
            </label>

            <select
    name="status"

    style="
        width:180px;
        height:52px;
        padding:0 18px;
        border:1px solid #d1d5db;
        border-radius:16px;
        background:white;
        color:#1f2937;
        font-size:15px;
        outline:none;
        cursor:pointer;
    ">

                <option value="">
                    All Status
                </option>

                <option value="Normal"
                    {{ request('status') == 'Normal' ? 'selected' : '' }}>
                    Normal
                </option>

                <option value="Warning"
                    {{ request('status') == 'Warning' ? 'selected' : '' }}>
                    Warning
                </option>

                <option value="Critical"
                    {{ request('status') == 'Critical' ? 'selected' : '' }}>
                    Critical
                </option>

            </select>

        </div>

        {{-- BUTTON --}}
       <div class="sensor-filter-button">
    <button
        type="submit"

                style="
                    background:#556F3D;
                    color:white;
                    height:52px;
                    padding:0 20px;
                    border-radius:16px;
                    font-weight:600;
                    font-size:15px;
                    display:flex;
                    align-items:center;
                    justify-content:center;
                    gap:10px;
                    border:none;
                    cursor:pointer;
                    box-shadow:0 4px 10px rgba(85,111,61,0.14);
                    transition:0.2s;
                    margin-top:24px;
                "

                onmouseover="this.style.background='#465C32'"
                onmouseout="this.style.background='#556F3D'">

                <svg xmlns="http://www.w3.org/2000/svg"
                    style="width:18px;height:18px"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">

                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M3 4a1 1 0 011-1h16a1 1 0 01.8 1.6L15 12v6l-6 2v-8L3.2 4.6A1 1 0 013 4z"/>

                </svg>

                Apply Filter

            </button>

        </div>

<div class="sensor-filter-button">

<a href="{{ route('sensor.export.csv', [
'date' => request('date'),
'status' => request('status'),
'crop' => request('crop')
]) }}"

style="
background:#1f2937;
color:white;
height:52px;
padding:0 20px;
border-radius:16px;
font-weight:600;
font-size:15px;
display:flex;
align-items:center;
justify-content:center;
text-decoration:none;
gap:10px;
margin-top:24px;
">

Export CSV

</a>

</div>

    </div>

</form>

</div>

 {{-- SUMMARY CARDS --}}
<div class="sensor-summary-grid" style="
    display:grid;
    grid-template-columns:repeat(5,minmax(0,1fr));
    gap:16px;
    margin-bottom:22px;
">

    {{-- CARD --}}
    <div style="
        background:white;
        border:1px solid #e5e7eb;
        border-radius:20px;
        padding:18px 20px;
        min-height:120px;
        box-shadow:0 2px 6px rgba(0,0,0,0.04);
    ">

        <p style="
            font-size:13px;
            color:#6b7280;
            margin-bottom:14px;
            font-weight:500;
        ">
            Total Records
        </p>

        <h2 style="
            font-size:26px;
            line-height:1;
            font-weight:700;
            color:#111827;
            margin:0;
        ">
            {{ number_format($totalRecords) }}
        </h2>

    </div>

    {{-- CARD --}}
    <div style="
        background:white;
        border:1px solid #e5e7eb;
        border-radius:20px;
        padding:18px 20px;
        min-height:120px;
        box-shadow:0 2px 6px rgba(0,0,0,0.04);
    ">

        <p style="
            font-size:13px;
            color:#6b7280;
            margin-bottom:14px;
            font-weight:500;
        ">
            Average Temperature
        </p>

        <h2 style="
            font-size:26px;
            line-height:1;
            font-weight:700;
            color:#ef4444;
            margin:0;
        ">
            {{ $avgTemp }}°C
        </h2>

    </div>

    {{-- CARD --}}
    <div style="
        background:white;
        border:1px solid #e5e7eb;
        border-radius:20px;
        padding:18px 20px;
        min-height:120px;
        box-shadow:0 2px 6px rgba(0,0,0,0.04);
    ">

        <p style="
            font-size:13px;
            color:#6b7280;
            margin-bottom:14px;
            font-weight:500;
        ">
            Average pH
        </p>

        <h2 style="
            font-size:26px;
            line-height:1;
            font-weight:700;
            color:#f59e0b;
            margin:0;
        ">
            {{ $avgPh }}
        </h2>

    </div>

    {{-- CARD --}}
    <div style="
        background:white;
        border:1px solid #e5e7eb;
        border-radius:20px;
        padding:18px 20px;
        min-height:120px;
        box-shadow:0 2px 6px rgba(0,0,0,0.04);
    ">

        <p style="
            font-size:13px;
            color:#6b7280;
            margin-bottom:14px;
            font-weight:500;
        ">
            Average TDS
        </p>

        <h2 style="
            font-size:26px;
            line-height:1;
            font-weight:700;
            color:#22c55e;
            margin:0;
        ">
            {{ $avgTds }}
        </h2>

    </div>

    {{-- CARD --}}
    <div style="
        background:white;
        border:1px solid #e5e7eb;
        border-radius:20px;
        padding:18px 20px;
        min-height:120px;
        box-shadow:0 2px 6px rgba(0,0,0,0.04);
    ">

        <p style="
            font-size:13px;
            color:#6b7280;
            margin-bottom:14px;
            font-weight:500;
        ">
            Average Water Level
        </p>

        <h2 style="
            font-size:26px;
            line-height:1;
            font-weight:700;
            color:#3b82f6;
            margin:0;
        ">
            {{ $avgWaterLevel }}%
        </h2>

    </div>

</div>

    {{-- SENSOR DATA TABLE --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">

        {{-- TABLE HEADER --}}
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">

            <div>

                <h2 class="text-xl font-semibold text-gray-800">
                    Historical Sensor Records
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Monitor historical hydroponic environmental readings
                </p>

            </div>


            {{-- TOTAL --}}
            <div class="bg-[#EEF3EA] text-[#556F3D] px-4 py-2 rounded-xl text-sm font-medium">

               {{ number_format($totalRecords) }} Records

            </div>

        </div>

{{-- TABLE --}}
<div class="overflow-x-auto">

    <table class="w-full border-collapse table-auto">

        {{-- HEAD --}}
        <thead class="bg-[#F5F7F4] border-b border-gray-100">

            <tr>

                <th class="w-[170px] px-6 py-4 text-left text-sm font-semibold text-gray-600 whitespace-nowrap">
                    Time
                </th>

                <th class="px-6 py-4 text-left text-sm font-semibold whitespace-nowrap"
                style="color:#ef4444;">
                Temperature
                </th>

                <th class="px-6 py-4 text-left text-sm font-semibold whitespace-nowrap"
                style="color:#f59e0b;">
                pH
                </th>

                <th class="px-6 py-4 text-left text-sm font-semibold whitespace-nowrap"
                style="color:#16a34a;">
                TDS
                </th>

                <th class="px-6 py-4 text-left text-sm font-semibold whitespace-nowrap"
                style="color:#3b82f6;">
                Water Level
                </th>

                <th class="px-6 py-4 text-center text-sm font-semibold text-gray-600 whitespace-nowrap">
                    Add Water
                </th>

                <th class="px-6 py-4 text-center text-sm font-semibold text-gray-600 whitespace-nowrap">
                    pH Reducer
                </th>

                <th class="px-6 py-4 text-center text-sm font-semibold text-gray-600 whitespace-nowrap">
                    Ex Fan
                </th>

                <th class="px-6 py-4 text-center text-sm font-semibold text-gray-600 whitespace-nowrap">
                    Nutrient
                </th>

            </tr>

        </thead>


       <tbody class="divide-y divide-gray-100">

@if($sensorData->isEmpty())

<tr>

    <td colspan="9" class="py-16">
    <div class="flex items-center justify-between text-gray-400 text-lg font-medium">
        No sensor data available
    </div>
</td>
</tr>

@else

@foreach($sensorData as $data)

<tr class="bg-white hover:bg-[#F8FAF7] transition duration-200">

    <td class="px-6 py-4 whitespace-nowrap">

    <div class="min-w-[140px]">

        <span class="text-base font-semibold text-gray-800">
            {{ \Carbon\Carbon::parse($data->timestamp)->format('h:i A') }}
        </span>

    </div>

</td>

   {{-- TEMP --}}
<td class="px-6 py-4 whitespace-nowrap">
    <span class="text-base font-semibold"
        style="color:#ef4444;">
       {{ number_format($data->dht_temp, 1) }}°C
    </span>
</td>

{{-- PH --}}
<td class="px-6 py-4 whitespace-nowrap">
    <span class="text-base font-semibold"
        style="color:#f59e0b;">
        {{ number_format($data->ph, 2) }}
    </span>
</td>

{{-- TDS --}}
<td class="px-6 py-4 whitespace-nowrap">
    <span class="text-base font-semibold"
        style="color:#16a34a;">
      {{ number_format($data->tds, 0) }}
    </span>
</td>

{{-- WATER --}}
<td class="px-6 py-4 whitespace-nowrap">
    <span class="text-base font-semibold"
        style="color:#3b82f6;">
        {{ number_format($data->water_level, 0) }}%
    </span>
</td>

   {{-- ADD WATER --}}
<td class="px-6 py-4 text-center">

@if(filter_var($data->add_water, FILTER_VALIDATE_BOOLEAN))

<span style="
display:inline-flex;
align-items:center;
justify-content:center;
min-width:84px;
padding:6px 16px;
border-radius:999px;
font-size:12px;
font-weight:700;
border:1px solid #bbf7d0;
background:#f0fdf4;
color:#16a34a;
box-shadow:0 2px 6px rgba(0,0,0,0.05);
">
ON
</span>

@else

<span style="
display:inline-flex;
align-items:center;
justify-content:center;
min-width:84px;
padding:6px 16px;
border-radius:999px;
font-size:12px;
font-weight:700;
border:1px solid #fecaca;
background:#fef2f2;
color:#dc2626;
box-shadow:0 2px 6px rgba(0,0,0,0.05);
">
OFF
</span>

@endif

</td>


{{-- PH REDUCER --}}
<td class="px-6 py-4 text-center">

@if(filter_var($data->ph_reducer, FILTER_VALIDATE_BOOLEAN))

<span style="
display:inline-flex;
align-items:center;
justify-content:center;
min-width:84px;
padding:6px 16px;
border-radius:999px;
font-size:12px;
font-weight:700;
border:1px solid #bbf7d0;
background:#f0fdf4;
color:#16a34a;
box-shadow:0 2px 6px rgba(0,0,0,0.05);
">
ON
</span>

@else

<span style="
display:inline-flex;
align-items:center;
justify-content:center;
min-width:84px;
padding:6px 16px;
border-radius:999px;
font-size:12px;
font-weight:700;
border:1px solid #fecaca;
background:#fef2f2;
color:#dc2626;
box-shadow:0 2px 6px rgba(0,0,0,0.05);
">
OFF
</span>

@endif

</td>


{{-- EX FAN --}}
<td class="px-6 py-4 text-center">

@if(filter_var($data->ex_fan, FILTER_VALIDATE_BOOLEAN))

<span style="
display:inline-flex;
align-items:center;
justify-content:center;
min-width:84px;
padding:6px 16px;
border-radius:999px;
font-size:12px;
font-weight:700;
border:1px solid #bbf7d0;
background:#f0fdf4;
color:#16a34a;
box-shadow:0 2px 6px rgba(0,0,0,0.05);
">
ON
</span>

@else

<span style="
display:inline-flex;
align-items:center;
justify-content:center;
min-width:84px;
padding:6px 16px;
border-radius:999px;
font-size:12px;
font-weight:700;
border:1px solid #fecaca;
background:#fef2f2;
color:#dc2626;
box-shadow:0 2px 6px rgba(0,0,0,0.05);
">
OFF
</span>

@endif

</td>


{{-- NUTRIENT --}}
<td class="px-6 py-4 text-center">

@if(filter_var($data->nutrient_adder, FILTER_VALIDATE_BOOLEAN))

<span style="
display:inline-flex;
align-items:center;
justify-content:center;
min-width:84px;
padding:6px 16px;
border-radius:999px;
font-size:12px;
font-weight:700;
border:1px solid #bbf7d0;
background:#f0fdf4;
color:#16a34a;
box-shadow:0 2px 6px rgba(0,0,0,0.05);
">
ON
</span>

@else

<span style="
display:inline-flex;
align-items:center;
justify-content:center;
min-width:84px;
padding:6px 16px;
border-radius:999px;
font-size:12px;
font-weight:700;
border:1px solid #fecaca;
background:#fef2f2;
color:#dc2626;
box-shadow:0 2px 6px rgba(0,0,0,0.05);
">
OFF
</span>

@endif

</td>
</tr>

@endforeach

@endif

</tbody>

    </table>

    <div class="p-6">

    {{ $sensorData->appends(request()->query())->links() }}


</div>

</div>

    </div>

</div>

<script>

function syncCrop()
{
    document.getElementById('viewCrop').value =
    document.getElementById('datasetCrop').value;
}

function showFileName(input)
{
    if (input.files.length > 0)
    {
        document.getElementById('selectedFileName').innerText =
            input.files[0].name;
    }
}

function confirmImport()
{
    let file =
        document.getElementById('datasetInput').files.length;

    if (!file)
    {
        alert('Please select a CSV file first.');
        return;
    }

    if (
        confirm(
            'The existing monitoring dataset will be replaced with the selected CSV file. Do you want to continue?'
        )
    )
    {
        document.getElementById('importForm').submit();
    }
}

</script>


<style>

@media (max-width: 767px) {

    /* =========================
       SUMMARY CARDS
    ========================= */

    .sensor-summary-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        gap: 12px !important;
    }


    /* =========================
       CSV UPLOAD
    ========================= */

    .dataset-upload-row {
        flex-wrap: wrap !important;
    }

    .dataset-upload-row > div {
        width: 100% !important;
    }

    .dataset-upload-row select {
        width: 100% !important;
    }

    .dataset-upload-row button {
        width: 100% !important;
    }

    .dataset-upload-row span {
        width: 100% !important;
    }


    /* =========================
       FILTER BUTTONS
    ========================= */

    .sensor-filter-button {
        width: 100% !important;
    }

    .sensor-filter-button button,
    .sensor-filter-button a {
        width: 100% !important;
    }

}

</style>

</x-app-layout>