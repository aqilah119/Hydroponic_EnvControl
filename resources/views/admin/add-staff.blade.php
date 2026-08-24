<x-app-layout>

<div style="
    max-width:560px;
    margin:20px auto;
">

    <!-- HEADER -->
    <div style="
        background:white;
        border-radius:26px;
        padding:28px 32px;
        margin-bottom:18px;
        box-shadow:0 4px 14px rgba(0,0,0,0.05);
    ">

        <h1 style="
            font-size:34px;
            font-weight:700;
            color:#1f2937;
            margin-bottom:8px;
        ">
            Add Staff
        </h1>

        <p style="
            color:#6b7280;
            font-size:15px;
            margin:0;
        ">
            Register new staff to generate Staff ID.
        </p>

    </div>

    <!-- FORM -->
    <div style="
        background:white;
        border-radius:26px;
        padding:30px 32px;
        box-shadow:0 4px 14px rgba(0,0,0,0.05);
    ">

        @if ($errors->any())

            <div style="
                background:#fee2e2;
                color:#dc2626;
                padding:12px;
                border-radius:12px;
                margin-bottom:18px;
                font-size:14px;
            ">

                {{ $errors->first() }}

            </div>

        @endif

        <form method="POST" action="{{ route('admin.store.staff') }}">

            @csrf

            <!-- STAFF NAME -->
            <div style="margin-bottom:20px;">

                <label style="
                    display:block;
                    font-weight:600;
                    margin-bottom:8px;
                    color:#374151;
                    font-size:15px;
                ">
                    Staff Name
                </label>

                <input
    type="text"
    name="name"
    autocomplete="off"
    required
    placeholder="Enter staff name"
    style="
        width:100%;
        padding:14px 16px;
        border:1px solid #d1d5db;
        border-radius:14px;
        outline:none;
        font-size:15px;
        box-sizing:border-box;
    "
>

            </div>

<!-- GENDER -->

<div style="margin-bottom:24px;">

    <label style="
        display:block;
        font-weight:600;
        margin-bottom:8px;
        color:#374151;
        font-size:15px;
    ">
        Gender
    </label>

    <div style="position:relative;">

        <select
            name="gender"
            required
            style="
                width:100%;
                padding:14px 45px 14px 16px;
                border:1px solid #d1d5db;
                border-radius:14px;
                outline:none;
                font-size:15px;
                background:white;
                appearance:none;
            "
        >

            <option value="">
                Select Gender
            </option>

            <option value="Male">
                Male
            </option>

            <option value="Female">
                Female
            </option>

        </select>

        <i class="fas fa-chevron-down"
           style="
                position:absolute;
                right:18px;
                top:50%;
                transform:translateY(-50%);
                color:#6b7280;
                pointer-events:none;
           ">
        </i>

    </div>

</div>


<!-- PHONE NUMBER -->

<div style="margin-bottom:24px;">

    <label style="
        display:block;
        font-weight:600;
        margin-bottom:8px;
        color:#374151;
        font-size:15px;
    ">
        Phone Number
    </label>

    <input
        type="text"
        name="phone_number"
        autocomplete="off"
        placeholder="Enter phone number"
        style="
            width:100%;
            padding:14px 16px;
            border:1px solid #d1d5db;
            border-radius:14px;
            outline:none;
            font-size:15px;
            box-sizing:border-box;
        "
    >

</div>
<!-- ADDRESS -->

<div style="margin-bottom:24px;">

    <label style="
        display:block;
        font-weight:600;
        margin-bottom:8px;
        color:#374151;
        font-size:15px;
    ">
        Address
    </label>

    <textarea
        name="address"
        rows="3"
        placeholder="Enter address"
        style="
            width:100%;
            padding:14px 16px;
            border:1px solid #d1d5db;
            border-radius:14px;
            outline:none;
            font-size:15px;
            resize:none;
            box-sizing:border-box;
        "
    ></textarea>

</div>


<!-- ROLE -->

<div style="margin-bottom:24px;">

    <label style="
        display:block;
        font-weight:600;
        margin-bottom:8px;
        color:#374151;
        font-size:15px;
    ">
        Role
    </label>

   <div style="position:relative;">

    <select
        id="roleSelect"
        name="role"
        style="
            width:100%;
            padding:14px 45px 14px 16px;
            border:1px solid #d1d5db;
            border-radius:14px;
            outline:none;
            font-size:15px;
            background:white;
            appearance:none;
            -webkit-appearance:none;
            -moz-appearance:none;
        "
    >

        <option value="staff">
            Staff Monitoring
        </option>

        <option value="admin">
            Administrator
        </option>

    </select>

    <i class="fas fa-chevron-down"
       style="
            position:absolute;
            right:18px;
            top:50%;
            transform:translateY(-50%);
            color:#6b7280;
            pointer-events:none;
       ">
    </i>

</div>

</div>


<!-- ASSIGNED CROP -->

<div id="cropSection" style="margin-bottom:24px;">

    <label style="
        display:block;
        font-weight:600;
        margin-bottom:8px;
        color:#374151;
        font-size:15px;
    ">
        Assigned Crop
    </label>

    <div style="position:relative;">

    <select
    name="plant_id"
    style="
        width:100%;
        padding:14px 45px 14px 16px;
        border:1px solid #d1d5db;
        border-radius:14px;
        outline:none;
        font-size:15px;
        box-sizing:border-box;
        background:white;
        appearance:none;
        -webkit-appearance:none;
        -moz-appearance:none;
    "
>

        <option value="">
    Select Crop
</option>

@foreach($crops as $crop)

<option value="{{ $crop->id }}">
    {{ $crop->name }}
</option>

@endforeach
    </select>

    <i class="fas fa-chevron-down"
       style="
            position:absolute;
            right:18px;
            top:50%;
            transform:translateY(-50%);
            color:#6b7280;
            pointer-events:none;
       ">
    </i>

</div>

</div>

            <!-- BUTTON -->
            <button type="submit"
                style="
                    background:#546B41;
                    color:white;
                    border:none;
                    padding:13px 24px;
                    border-radius:14px;
                    font-size:15px;
                    font-weight:600;
                    cursor:pointer;
                    box-shadow:0 4px 10px rgba(0,0,0,0.1);
                "
            >

                Add Staff

            </button>

        </form>

    </div>

</div>


<script>

const roleSelect = document.getElementById('roleSelect');
const cropSection = document.getElementById('cropSection');
const plantSelect = document.querySelector('select[name="plant_id"]');

function toggleCrop()
{
    if (roleSelect.value === 'admin')
    {
        cropSection.style.display = 'none';
        plantSelect.value = '';
        plantSelect.removeAttribute('required');
    }
    else
    {
        cropSection.style.display = 'block';
        plantSelect.setAttribute('required', true);
    }
}

roleSelect.addEventListener('change', toggleCrop);

toggleCrop();

</script>

</x-app-layout>