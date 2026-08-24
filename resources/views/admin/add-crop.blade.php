<x-app-layout>

<div class="flex justify-center py-8 px-6">

    <div style="width:100%; max-width:900px;">

        <!-- HEADER -->
        <div style="
            background:white;
            border-radius:28px;
            padding:30px;
            margin-bottom:30px;
            box-shadow:0 8px 25px rgba(0,0,0,0.08);
        ">

            <h1 style="
    font-size:28px;
    font-weight:700;
    color:#1f2937;
    margin-bottom:8px;
">
    Add Crop
</h1>
            <p style="
    color:#6b7280;
    font-size:15px;
    margin:0;
">
                Register a new crop environmental profile.
            </p>

        </div>

        <!-- FORM -->
        <div style="
            background:white;
            border-radius:28px;
            padding:30px;
            box-shadow:0 8px 25px rgba(0,0,0,0.08);
        ">

            @if ($errors->any())

<div style="
    background:#fee2e2;
    color:#b91c1c;
    padding:15px;
    border-radius:12px;
    margin-bottom:20px;
">

    <strong>Please fix the following errors:</strong>

    <ul style="margin-top:10px;">

        @foreach ($errors->all() as $error)

            <li>{{ $error }}</li>

        @endforeach

    </ul>

</div>

@endif
            <form method="POST" action="{{ route('admin.store.crop') }}">

                @csrf

                <!-- Crop Name -->
                <div style="margin-bottom:22px;">

                    <label style="
                        display:block;
                        margin-bottom:8px;
                        font-weight:600;
                    ">
                        Crop Name
                    </label>

                    <input
                        type="text"
                        name="name"
                        placeholder="Enter crop name"
                        style="
                            width:100%;
                            padding:14px;
                            border:1px solid #d1d5db;
                            border-radius:16px;
                        ">
                </div>

                <!-- Category -->
                <div style="margin-bottom:22px;">

                    <label style="
                        display:block;
                        margin-bottom:8px;
                        font-weight:600;
                    ">
                        Category
                    </label>

                    <select
                        name="category"
                        style="
                            width:100%;
                            padding:14px;
                            border:1px solid #d1d5db;
                            border-radius:16px;
                        ">

                        <option>Leafy Vegetable</option>

                        <option>Fruiting Vegetable</option>

                        <option>Herb</option>

                    </select>

                </div>

                <!-- pH -->
<div style="
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:20px;
    margin-bottom:22px;
">

    <div>

        <label style="
            display:block;
            margin-bottom:8px;
            font-weight:600;
        ">
            pH Min
        </label>

        <input
            type="number"
            step="0.1"
            name="ph_min"
            style="
                width:100%;
                padding:14px;
                border:1px solid #d1d5db;
                border-radius:16px;
            ">
    </div>

    <div>

        <label style="
            display:block;
            margin-bottom:8px;
            font-weight:600;
        ">
            pH Max
        </label>

        <input
            type="number"
            step="0.1"
            name="ph_max"
            style="
                width:100%;
                padding:14px;
                border:1px solid #d1d5db;
                border-radius:16px;
            ">
    </div>

</div>

<!-- Temperature -->
<div style="
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:20px;
    margin-bottom:22px;
">

    <div>

        <label style="
            display:block;
            margin-bottom:8px;
            font-weight:600;
        ">
            Temperature Min
        </label>

        <input
            type="number"
            name="temp_min"
            style="
                width:100%;
                padding:14px;
                border:1px solid #d1d5db;
                border-radius:16px;
            ">
    </div>

    <div>

        <label style="
            display:block;
            margin-bottom:8px;
            font-weight:600;
        ">
            Temperature Max
        </label>

        <input
            type="number"
            name="temp_max"
            style="
                width:100%;
                padding:14px;
                border:1px solid #d1d5db;
                border-radius:16px;
            ">
    </div>

</div>

<!-- TDS -->
<div style="
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:20px;
    margin-bottom:22px;
">

    <div>

        <label style="
            display:block;
            margin-bottom:8px;
            font-weight:600;
        ">
            TDS Min
        </label>

        <input
            type="number"
            name="tds_min"
            style="
                width:100%;
                padding:14px;
                border:1px solid #d1d5db;
                border-radius:16px;
            ">
    </div>

    <div>

        <label style="
            display:block;
            margin-bottom:8px;
            font-weight:600;
        ">
            TDS Max
        </label>

        <input
            type="number"
            name="tds_max"
            style="
                width:100%;
                padding:14px;
                border:1px solid #d1d5db;
                border-radius:16px;
            ">
    </div>

</div>

<!-- Water Level -->
<div style="margin-bottom:22px;">

    <label style="
        display:block;
        margin-bottom:8px;
        font-weight:600;
    ">
        Minimum Water Level (%)
    </label>

    <input
        type="number"
        name="water_min"
        style="
            width:100%;
            padding:14px;
            border:1px solid #d1d5db;
            border-radius:16px;
        ">

</div>

<!-- Status -->
<div style="margin-bottom:30px;">

    <label style="
        display:block;
        margin-bottom:8px;
        font-weight:600;
    ">
        Status
    </label>

    <select
        name="status"
        style="
            width:100%;
            padding:14px;
            border:1px solid #d1d5db;
            border-radius:16px;
        ">

        <option value="active">Active</option>

        <option value="inactive">Inactive</option>

    </select>

</div>

<!-- BUTTON -->
<button
    type="submit"
    style="
        background:#546B41;
        color:white;
        border:none;
        padding:14px 28px;
        border-radius:16px;
        font-weight:600;
        cursor:pointer;
        box-shadow:0 4px 12px rgba(0,0,0,0.15);
    ">

    Save Crop

</button>

</form>

</div>

</div>

</div>

</x-app-layout>

@if ($errors->any())

<div id="error-alert" style="
    position:fixed;
    top:20px;
    right:20px;
    background:#dc2626;
    color:white;
    padding:14px 20px;
    border-radius:12px;
    z-index:9999;
    box-shadow:0 4px 12px rgba(0,0,0,0.15);
">

    {{ $errors->first() }}

</div>

<script>

setTimeout(() => {

    document.getElementById('error-alert')?.remove();

}, 4000);

</script>

@endif
