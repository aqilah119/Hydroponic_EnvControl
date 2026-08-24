<x-app-layout>

<div class="flex justify-center py-8 px-4 sm:px-6 w-full min-w-0">

    <div style="width:100%; max-width:1400px; min-width:0;">

        <!-- HEADER -->
        <div style="
            background:white;
            border-radius:28px;
            padding:32px 40px;
            margin-bottom:30px;
            box-shadow:0 8px 25px rgba(0,0,0,0.08);
        ">

            <h1 style="
                font-size:32px;
                font-weight:700;
                color:#1f2937;
                margin-bottom:10px;
            ">
                Crop Growth Simulator
            </h1>

            <p style="
                font-size:17px;
                color:#6b7280;
            ">
                Simulate environmental conditions and observe their impact on crop.
            </p>

        </div>

        <!-- FORM CARD -->
        <div style="
            background:white;
            border-radius:28px;
            padding:32px;
            box-shadow:0 8px 25px rgba(0,0,0,0.08);
        ">

<div
@if($result)
class="simulator-layout"
@else
style="display:block;"
@endif
>
            <form action="{{ route('simulator.run') }}"
                  method="POST">

                @csrf

                <!-- CROP -->
                <div style="margin-bottom:25px;">

                    <label style="
                        display:block;
                        font-weight:600;
                        margin-bottom:10px;
                    ">
                        Select Crop
                    </label>

                    <select
                     name="plant_id"
                     id="plantSelect"
                     style="
                     width:100%;
                     padding:14px;
                     border:1px solid #d1d5db;
                     border-radius:16px;
                     ">
                        @foreach($plants as $plant)

                     <option
    value="{{ $plant->id }}"
    {{ isset($selectedPlant) && $selectedPlant->id == $plant->id ? 'selected' : '' }}
    data-temp-min="{{ $plant->temp_min }}"
    data-temp-max="{{ $plant->temp_max }}"
    data-ph-min="{{ $plant->ph_min }}"
    data-ph-max="{{ $plant->ph_max }}"
    data-tds-min="{{ $plant->tds_min }}"
    data-tds-max="{{ $plant->tds_max }}"
    data-water-min="{{ $plant->water_min }}"
>
    {{ $plant->name }}
</option>

                    @endforeach

                   </select>

</div>

<div style="
    background:#F8FAFC;
    border:1px solid #E5E7EB;
    border-radius:18px;
    padding:18px;
    margin-bottom:25px;
">

    <h3 style="
        font-size:16px;
        font-weight:700;
        margin-bottom:12px;
        color:#1F2937;
    ">
        Crop Information
    </h3>

   <div style="
    text-align:center;
    margin-bottom:10px;
">

    <img
    id="cropImage"
    src="/images/{{ strtolower(str_replace(' ','',$selectedPlant->name)) }}.png"
    style="
        display:block;
        margin:0 auto;
        width:100%;
        max-width:260px;
        height:160px;
        object-fit:contain;
    "
>

</div>

<h3
    id="cropName"
    style="
        text-align:center;
        font-size:20px;
        font-weight:700;
        margin-bottom:15px;
        color:#111827;
    "
>
    {{ $selectedPlant->name }}
</h3>

<p
    id="cropDescription"
    style="
        text-align:center;
        color:#6B7280;
        margin-bottom:18px;
        line-height:1.6;
    "
>
    Suitable for warm environmental conditions.
</p>

<hr style="
    margin:15px 0;
    border:none;
    border-top:1px solid #E5E7EB;
">

    <p style="margin-bottom:6px;">
        <strong>Temperature:</strong>
       <span id="tempMin">
    {{ $selectedPlant->temp_min }}
</span>
        -
        <span id="tempMax">
    {{ $selectedPlant->temp_max }}
</span> °C
    </p>

   <p style="margin-bottom:6px;">
    <strong>pH:</strong>

    <span id="phMin">
        {{ $selectedPlant->ph_min }}
    </span>

    -

    <span id="phMax">
        {{ $selectedPlant->ph_max }}
    </span>

</p>

    <p style="margin-bottom:6px;">
    <strong>TDS:</strong>

    <span id="tdsMin">
        {{ $selectedPlant->tds_min }}
    </span>

    -

    <span id="tdsMax">
        {{ $selectedPlant->tds_max }}
    </span>

    ppm

</p>

    <p>
    <strong>Water:</strong>
    ≥

    <span id="waterMin">
        {{ $selectedPlant->water_min }}
    </span>

    %
</p>

</div>

<!-- TEMPERATURE -->
<div style="margin-bottom:25px;">

    <label style="
        display:block;
        font-weight:600;
        margin-bottom:10px;
    ">
        Temperature (°C)
    </label>

    <div style="
        display:flex;
        align-items:center;
        gap:15px;
    ">

        <input
            type="range"
            min="18"
            max="28"
            step="0.1"
            value="{{ $result['temperature'] ?? 24 }}"
            id="tempSlider"
            style="
               flex:1;
               min-width:0;
            ">

        <input
            type="number"
            min="18"
            max="28"
            step="0.1"
            value="{{ $result['temperature'] ?? 24 }}"
            name="temperature"
            id="tempInput"
            style="
                width:90px;
                padding:10px;
                border:1px solid #d1d5db;
                border-radius:12px;
                text-align:center;
            ">

    </div>

</div>

                <!-- PH -->
<div style="margin-bottom:25px;">

    <label style="
        display:block;
        font-weight:600;
        margin-bottom:10px;
    ">
        pH Level
    </label>

    <div style="
        display:flex;
        align-items:center;
        gap:15px;
    ">

        <input
            type="range"
            min="5"
            max="7"
            step="0.01"
            value="{{ $result['ph'] ?? 6 }}"
            id="phSlider"
            style="
                flex:1;
                min-width:0;

            ">

        <input
            type="number"
            min="5"
            max="7"
            step="0.01"
            value="{{ $result['ph'] ?? 6 }}"
            name="ph"
            id="phInput"
            style="
                width:90px;
                padding:10px;
                border:1px solid #d1d5db;
                border-radius:12px;
                text-align:center;
            ">

    </div>

</div>
               <!-- TDS -->
<div style="margin-bottom:25px;">

    <label style="
        display:block;
        font-weight:600;
        margin-bottom:10px;
    ">
        TDS (ppm)
    </label>

    <div style="
        display:flex;
        align-items:center;
        gap:15px;
    ">

        <input
            type="range"
            min="400"
            max="1600"
            step="10"
            value="{{ $result['tds'] ?? 1000 }}"
            id="tdsSlider"
            style="
                flex:1;
                min-width:0;
            ">

        <input
            type="number"
            min="400"
            max="1600"
            step="10"
            value="{{ $result['tds'] ?? 1000 }}"
            name="tds"
            id="tdsInput"
            style="
                width:90px;
                padding:10px;
                border:1px solid #d1d5db;
                border-radius:12px;
                text-align:center;
            ">

    </div>

</div>
<!-- WATER -->
<div style="margin-bottom:30px;">

    <label style="
        display:block;
        font-weight:600;
        margin-bottom:10px;
    ">
        Water Level (%)
    </label>

    <div style="
        display:flex;
        align-items:center;
        gap:15px;
    ">

        <input
            type="range"
            min="0"
            max="100"
            step="1"
            value="{{ $result['water'] ?? 50 }}"
            id="waterSlider"
            style="
                flex:1;
                min-width:0;
            ">

        <input
            type="number"
            min="0"
            max="100"
            step="1"
           value="{{ $result['water'] ?? 50 }}"
           name="water"
            id="waterInput"
            style="
                width:90px;
                padding:10px;
                border:1px solid #d1d5db;
                border-radius:12px;
                text-align:center;
            ">

    </div>

</div>

                <button
                    type="submit"
                    style="
                        background:#546B41;
                        color:white;
                        border:none;
                        padding:12px 24px;
                        border-radius:999px;
                        cursor:pointer;
                    ">

                    Run Simulation

                </button>

            </form>

<div style="
    display:flex;
    flex-direction:column;
    gap:25px;
">

@if($result)

<div class="result-cards">

        <!-- HEALTH SCORE -->
    <div style="
        background:#F0FDF4;
        border:1px solid #BBF7D0;
        border-radius:20px;
        padding:20px;
        text-align:center;
    ">

        <div style="
    width:60px;
    height:60px;
    background:#DCFCE7;
    border-radius:16px;
    display:flex;
    align-items:center;
    justify-content:center;
    margin:0 auto 15px;
">

    <i class="fa-solid fa-heart-pulse"
       style="
            color:#16A34A;
            font-size:26px;
       ">
    </i>

</div>

        <p style="
            color:#6b7280;
            font-size:14px;
            margin-top:8px;
        ">
            Health Score
        </p>

        <h2 style="
            font-size:28px;
            font-weight:700;
            color:#166534;
        ">
            {{ $result['score'] }}%
        </h2>

    </div>

    <!-- GROWTH -->
    <div style="
        background:#EFF6FF;
        border:1px solid #BFDBFE;
        border-radius:20px;
        padding:20px;
        text-align:center;
    ">

        <div style="
    width:60px;
    height:60px;
    background:#DBEAFE;
    border-radius:16px;
    display:flex;
    align-items:center;
    justify-content:center;
    margin:0 auto 15px;
">

    <i class="fa-solid fa-chart-line"
       style="
            color:#2563EB;
            font-size:26px;
       ">
    </i>

</div>

        <p style="
            color:#6b7280;
            font-size:14px;
            margin-top:8px;
        ">
            Growth Status
        </p>

        <h2 style="
            font-size:24px;
            font-weight:700;
            color:#1D4ED8;
        ">
            {{ $result['growth'] }}
        </h2>

    </div>

    <!-- RISK -->
    <div style="
        background:#FEFCE8;
        border:1px solid #FDE68A;
        border-radius:20px;
        padding:20px;
        text-align:center;
    ">

       <div style="
    width:60px;
    height:60px;
    background:#FEF3C7;
    border-radius:16px;
    display:flex;
    align-items:center;
    justify-content:center;
    margin:0 auto 15px;
">

    <i class="fa-solid fa-triangle-exclamation"
       style="
            color:#D97706;
            font-size:26px;
       ">
    </i>

</div>

        <p style="
            color:#6b7280;
            font-size:14px;
            margin-top:8px;
        ">
            Risk Level
        </p>

        <h2 style="
            font-size:24px;
            font-weight:700;
            color:#CA8A04;
        ">
            {{ $result['risk'] }}
        </h2>

    </div>

    <!-- YIELD -->
    <div style="
        background:#FDF4FF;
        border:1px solid #E9D5FF;
        border-radius:20px;
        padding:20px;
        text-align:center;
    ">

        <div style="
    width:60px;
    height:60px;
    background:#F3E8FF;
    border-radius:16px;
    display:flex;
    align-items:center;
    justify-content:center;
    margin:0 auto 15px;
">

    <i class="fa-solid fa-seedling"
       style="
            color:#7E22CE;
            font-size:26px;
       ">
    </i>

</div>
        <p style="
            color:#6b7280;
            font-size:14px;
            margin-top:8px;
        ">
           Crop Condition
        </p>

        <h2 style="
            font-size:24px;
            font-weight:700;
            color:#7E22CE;
        ">
            {{ $result['condition'] }}
        </h2>

    </div>

    </div>

@if($result)

<div style="
    margin-top:25px;
    background:white;
    border-radius:20px;
    border:1px solid #e5e7eb;
    overflow:hidden;
">

    <div style="
        padding:20px;
        border-bottom:1px solid #e5e7eb;
        font-weight:700;
        font-size:18px;
        color:#1f2937;
    ">
        Parameter Analysis
    </div>

    <table class="parameter-table" style="
    width:100%;
    border-collapse:collapse;
">

        <thead>

            <tr style="background:#f9fafb;">

                <th class="parameter-header" style="padding:14px;text-align:left;">
    Parameter
</th>

                <th class="parameter-header" style="padding:14px;text-align:center;">
    Current Value
</th>

                <th style="padding:14px;text-align:center;">
    Status
</th>

            </tr>

        </thead>

        <tbody>

            <tr>
                <td style="padding:14px;">Temperature</td>
                <td style="padding:14px;text-align:center;">
                    {{ $result['temperature'] }} °C
                </td>
                <td style="padding:14px;text-align:center;">

@if($result['parameterStatus']['temperature'] == 'Optimal')

<span style="
background:#DCFCE7;
color:#166534;
padding:6px 12px;
border-radius:999px;
font-size:12px;
font-weight:600;
">
Optimal
</span>

@else

<span style="
background:#FEF3C7;
color:#92400E;
padding:6px 12px;
border-radius:999px;
font-size:12px;
font-weight:600;
">
Warning
</span>

@endif

</td>
            </tr>

            <tr>
                <td style="padding:14px;">pH</td>
                <td style="padding:14px;text-align:center;">
                    {{ $result['ph'] }}
                </td>
                <td style="padding:14px;text-align:center;">

@if($result['parameterStatus']['ph'] == 'Optimal')

<span style="
background:#DCFCE7;
color:#166534;
padding:6px 12px;
border-radius:999px;
font-size:12px;
font-weight:600;
">
Optimal
</span>

@else

<span style="
background:#FEF3C7;
color:#92400E;
padding:6px 12px;
border-radius:999px;
font-size:12px;
font-weight:600;
">
Warning
</span>

@endif

</td>
            </tr>

            <tr>
                <td style="padding:14px;">TDS</td>
                <td style="padding:14px;text-align:center;">
                    {{ $result['tds'] }}
                </td>
                <td style="padding:14px;text-align:center;">

@if($result['parameterStatus']['tds'] == 'Optimal')

<span style="
background:#DCFCE7;
color:#166534;
padding:6px 12px;
border-radius:999px;
font-size:12px;
font-weight:600;
">
Optimal
</span>

@else

<span style="
background:#FEF3C7;
color:#92400E;
padding:6px 12px;
border-radius:999px;
font-size:12px;
font-weight:600;
">
Warning
</span>

@endif

</td>
            </tr>

            <tr>
                <td style="padding:14px;">Water Level</td>
                <td style="padding:14px;text-align:center;">
                    {{ $result['water'] }}%
                </td>
                <td style="padding:14px;text-align:center;">

@if($result['parameterStatus']['water'] == 'Optimal')

<span style="
background:#DCFCE7;
color:#166534;
padding:6px 12px;
border-radius:999px;
font-size:12px;
font-weight:600;
">
Optimal
</span>

@else

<span style="
background:#FEF3C7;
color:#92400E;
padding:6px 12px;
border-radius:999px;
font-size:12px;
font-weight:600;
">
Warning
</span>

@endif

</td>
            </tr>

        </tbody>

    </table>

</div>

@endif

 @if($result)

<div style="
    margin-top:25px;
    background:white;
    border-radius:24px;
    padding:28px;
    border:1px solid #e5e7eb;
    box-shadow:0 4px 12px rgba(0,0,0,0.04);
">

    <div style="
        display:flex;
        align-items:center;
        gap:16px;
        margin-bottom:20px;
    ">

        <div id="insightIcon"
             style="
                width:60px;
                height:60px;
                border-radius:16px;
                background:#FEF3C7;
                display:flex;
                align-items:center;
                justify-content:center;
             ">

            <i class="fa-solid fa-lightbulb"
               style="
                    color:#D97706;
                    font-size:24px;
               ">
            </i>

        </div>

        <div>

            <h2 style="
                font-size:24px;
                font-weight:700;
                color:#111827;
            ">
                Simulation Insight
            </h2>

            <p style="
                color:#6b7280;
            ">
                System-generated environmental assessment.
            </p>

        </div>

        </div>

    @foreach($result['insight'] as $item)

        <div style="
            background:#F9FAFB;
            padding:14px 18px;
            border-radius:12px;
            margin-bottom:10px;
        ">

            {{ $item }}

        </div>

    @endforeach

</div>

@endif

@endif

</div>

</div>

</div>

</div>

<script>


tempSlider.oninput = function() {

    tempInput.value = this.value;

}

tempInput.oninput = function() {

    tempSlider.value = this.value;

}

phSlider.oninput = function() {

    phInput.value = this.value;

}

phInput.oninput = function() {

    phSlider.value = this.value;

}

tdsSlider.oninput = function() {

    tdsInput.value = this.value;

}

tdsInput.oninput = function() {

    tdsSlider.value = this.value;

}

waterSlider.oninput = function() {

    waterInput.value = this.value;

}

waterInput.oninput = function() {

    waterSlider.value = this.value;

}


const plantSelect =
    document.getElementById('plantSelect');


plantSelect.addEventListener('change', function() {

    const selected =
        this.options[this.selectedIndex];
tempSlider.min =
    selected.dataset.tempMin;

tempSlider.max =
    selected.dataset.tempMax;

tempSlider.value =
    selected.dataset.tempMin;

tempInput.value =
    selected.dataset.tempMin;

tempInput.min =
    selected.dataset.tempMin;

tempInput.max =
    selected.dataset.tempMax;

phSlider.min =
    selected.dataset.phMin;

phSlider.max =
    selected.dataset.phMax;

phInput.min =
    selected.dataset.phMin;

phInput.max =
    selected.dataset.phMax;

phSlider.value =
    selected.dataset.phMin;

phInput.value =
    selected.dataset.phMin;

tdsSlider.min =
    selected.dataset.tdsMin;

tdsSlider.max =
    selected.dataset.tdsMax;

tdsInput.min =
    selected.dataset.tdsMin;

tdsInput.max =
    selected.dataset.tdsMax;

    tdsSlider.value =
    selected.dataset.tdsMin;

tdsInput.value =
    selected.dataset.tdsMin;

    document.getElementById('tempMin').textContent =
    selected.dataset.tempMin;

    document.getElementById('tempMax').textContent =
        selected.dataset.tempMax;

    document.getElementById('phMin').textContent =
        selected.dataset.phMin;

    document.getElementById('phMax').textContent =
        selected.dataset.phMax;

    document.getElementById('tdsMin').textContent =
        selected.dataset.tdsMin;

    document.getElementById('tdsMax').textContent =
        selected.dataset.tdsMax;

    document.getElementById('waterMin').textContent =
        selected.dataset.waterMin;

    waterSlider.min =
    selected.dataset.waterMin;

waterInput.min =
    selected.dataset.waterMin;

waterSlider.value =
    selected.dataset.waterMin;

waterInput.value =
    selected.dataset.waterMin;

    document.getElementById('cropName').textContent =
    selected.text;

const cropImage =
    document.getElementById('cropImage');

if(selected.text.includes('Chili'))
{
    cropImage.src = '/images/chili.png';
}
else if(selected.text.includes('Lettuce'))
{
    cropImage.src = '/images/lettuce.png';
}
else
{
    cropImage.src = '/images/pakchoy.png';
}

const cropDescription =
    document.getElementById('cropDescription');

if(selected.text.includes('Chili'))
{
    cropDescription.textContent =
        'Best grown in warm conditions.';
}
else if(selected.text.includes('Lettuce'))
{
    cropDescription.textContent =
        'Best grown in cool conditions.';
}
else
{
    cropDescription.textContent =
        'Best grown in hydroponic systems.';
}


});

</script>

<style>

.simulator-layout {
    display: grid;
    grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
    gap: 25px;
    width: 100%;
    min-width: 0;
}

.simulator-layout form {
    width: 100%;
    min-width: 0;
}
    /* MOBILE PARAMETER TABLE */
    .parameter-table,
    .parameter-table tbody,
    .parameter-table tr,
    .parameter-table td {
        display: block;
        width: 100%;
    }

    .parameter-table thead {
        display: none;
    }

    .parameter-table tr {
        padding: 16px;
        border-bottom: 1px solid #e5e7eb;
    }

    .parameter-table tr:last-child {
        border-bottom: none;
    }

    .parameter-table td {
        padding: 6px 14px !important;
        text-align: left !important;
    }

    .parameter-table td:nth-child(1) {
        font-weight: 700;
        font-size: 16px;
        color: #111827;
        margin-bottom: 4px;
    }

    .parameter-table td:nth-child(2)::before {
        content: "Current Value: ";
        font-weight: 600;
        color: #6b7280;
    }

    .parameter-table td:nth-child(3)::before {
        content: "Status: ";
        font-weight: 600;
        color: #6b7280;
    }

    .parameter-table td:nth-child(3) {
        margin-top: 4px;
    }
    
.result-cards {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}

#insightIcon{
    animation: pulseGlow 2s ease-in-out infinite;
}

@keyframes pulseGlow{

    0%{
        transform:scale(1);
    }

    50%{
        transform:scale(1.08);
    }

    100%{
        transform:scale(1);
    }

}

@keyframes plantFloat{

    0%{
        transform:translateY(0px);
    }

    50%{
        transform:translateY(-8px);
    }

    100%{
        transform:translateY(0px);
    }

}

* {
    box-sizing: border-box;
}

.simulator-layout,
.simulator-layout > *,
.simulator-layout form,
.result-cards,
.result-cards > * {
    max-width: 100%;
    min-width: 0;
}

@media (max-width: 767px) {

    .simulator-layout {
        grid-template-columns: 1fr;
        gap: 20px;
    }

    .result-cards {
        grid-template-columns: 1fr;
    }

}

</style>

</x-app-layout>