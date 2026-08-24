<x-app-layout>

<div class="flex justify-center py-8 px-6">

    <div style="width:100%; max-width:1000px;">

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
                Edit Staff
            </h1>

            <p style="
                font-size:18px;
                color:#6b7280;
                margin:0;
            ">
                Update staff information and access settings.
            </p>

        </div>


        <!-- MAIN CARD -->
        <div style="
            background:white;
            border-radius:28px;
            padding:35px;
            box-shadow:0 8px 25px rgba(0,0,0,0.08);
        ">

            <form method="POST"
                  action="{{ route('admin.update.staff', $staff->id) }}">

                @csrf
                @method('PUT')


                <!-- TOP PROFILE -->
                <div style="
                    display:flex;
                    align-items:center;
                    gap:28px;
                    padding-bottom:30px;
                    margin-bottom:35px;
                    border-bottom:1px solid #e5e7eb;
                ">

                   <!-- PROFILE IMAGE -->

<img
src="{{ $staff->profile_picture
    ? asset('storage/' . $staff->profile_picture)
    : 'https://ui-avatars.com/api/?name=' . urlencode($staff->user->name ?? $staff->name) . '&background=546B41&color=fff&size=128' }}"

style="
    width:100px;
    height:100px;
    border-radius:999px;
    object-fit:cover;
    border:4px solid #546B41;
    box-shadow:0 4px 12px rgba(0,0,0,0.15);
">

                    <!-- INFO -->
                    <div>

                        <h2 style="
                            font-size:26px;
                            font-weight:700;
                            color:#111827;
                            margin-bottom:6px;
                        ">
                            {{ $staff->user->name ?? $staff->name }}
                        </h2>

                        <p style="
                            font-size:17px;
                            color:#6b7280;
                            margin-bottom:14px;
                        ">
                            {{ $staff->user->email ?? 'No email' }}
                        </p>

                        @if($staff->user && $staff->user->role === 'admin')

                            <span style="
                                background:#fee2e2;
                                color:#dc2626;
                                padding:5px 12px;
                                border-radius:999px;
                                font-size:13px;
                                font-weight:600;
                            ">
                                Admin
                            </span>

                        @else

                            <span style="
                                background:#dcfce7;
                                color:#166534;
                                padding:5px 12px;
                                border-radius:999px;
                                font-size:13px;
                                font-weight:600;
                            ">
                                Staff
                            </span>

                        @endif

                    </div>

                </div>


                <!-- PERSONAL INFO -->
                <h3 style="
                    font-size:20px;
                    font-weight:700;
                    margin-bottom:24px;
                    color:#111827;
                ">
                    Personal Information
                </h3>


                <div style="
                    display:grid;
                    grid-template-columns:1fr 1fr;
                    gap:24px;
                    margin-bottom:35px;
                ">

                    <!-- NAME -->
                    <div>

                        <label style="
                            display:block;
                            font-size:15px;
                            font-weight:600;
                            margin-bottom:10px;
                            color:#374151;
                        ">
                            Full Name
                        </label>

                        <input type="text"
                               name="name"
                               value="{{ $staff->user->name ?? $staff->name }}"

                               style="
                                    width:100%;
                                    padding:15px 18px;
                                    border:1px solid #d1d5db;
                                    border-radius:16px;
                                    font-size:15px;
                                    outline:none;
                               ">

                    </div>


                    <!-- STAFF ID -->
                    <div>

                        <label style="
                            display:block;
                            font-size:15px;
                            font-weight:600;
                            margin-bottom:10px;
                            color:#374151;
                        ">
                            Staff ID
                        </label>

                        <input type="text"
                               value="{{ $staff->staff_id }}"
                               readonly

                               style="
                                    width:100%;
                                    padding:15px 18px;
                                    border:1px solid #d1d5db;
                                    border-radius:16px;
                                    font-size:15px;
                                    background:#f3f4f6;
                                    color:#6b7280;
                               ">

                    </div>


                    <!-- EMAIL -->
                    <div>

                        <label style="
                            display:block;
                            font-size:15px;
                            font-weight:600;
                            margin-bottom:10px;
                            color:#374151;
                        ">
                            Email Address
                        </label>

                        <input type="email"
                               name="email"
                               value="{{ $staff->user->email ?? '' }}"

                               style="
                                    width:100%;
                                    padding:15px 18px;
                                    border:1px solid #d1d5db;
                                    border-radius:16px;
                                    font-size:15px;
                                    outline:none;
                               ">

                    </div>


                    <!-- PHONE -->
                    <div>

                        <label style="
                            display:block;
                            font-size:15px;
                            font-weight:600;
                            margin-bottom:10px;
                            color:#374151;
                        ">
                            Phone Number
                        </label>

                        <input type="text"
                               name="phone_number"
                               value="{{ $staff->phone_number }}"

                               style="
                                    width:100%;
                                    padding:15px 18px;
                                    border:1px solid #d1d5db;
                                    border-radius:16px;
                                    font-size:15px;
                                    outline:none;
                               ">

                    </div>


                    <!-- GENDER -->
                    <div>

                        <label style="
                            display:block;
                            font-size:15px;
                            font-weight:600;
                            margin-bottom:10px;
                            color:#374151;
                        ">
                            Gender
                        </label>

                        <select name="gender"

                                style="
                                    width:100%;
                                    padding:15px 18px;
                                    border:1px solid #d1d5db;
                                    border-radius:16px;
                                    font-size:15px;
                                    outline:none;
                                ">

                            <option value="Male"
                                {{ $staff->gender == 'Male' ? 'selected' : '' }}>
                                Male
                            </option>

                            <option value="Female"
                                {{ $staff->gender == 'Female' ? 'selected' : '' }}>
                                Female
                            </option>

                        </select>

                    </div>

                </div>

                <div>

<label style="
display:block;
font-size:15px;
font-weight:600;
margin-bottom:10px;
color:#374151;
">
Address
</label>

<textarea
name="address"
rows="3"
style="
width:100%;
padding:15px 18px;
border:1px solid #d1d5db;
border-radius:16px;
font-size:15px;
outline:none;
">{{ $staff->address }}</textarea>

</div>

                <!-- ROLE SECTION -->
                <h3 style="
                    font-size:20px;
                    font-weight:700;
                    margin-bottom:24px;
                    color:#111827;
                ">
                    Role & Access
                </h3>


                <div style="
                    display:grid;
                    grid-template-columns:1fr 1fr;
                    gap:24px;
                    margin-bottom:35px;
                ">

<!-- ASSIGNED CROP -->
<div>

    <label style="
        display:block;
        font-size:15px;
        font-weight:600;
        margin-bottom:10px;
        color:#374151;
    ">
        Assigned Crop
    </label>

    @if($staff->user && $staff->user->role === 'admin')

        <input type="text"
               value="All Crops"
               readonly

               style="
                    width:100%;
                    padding:15px 18px;
                    border:1px solid #d1d5db;
                    border-radius:16px;
                    font-size:15px;
                    background:#f3f4f6;
                    color:#6b7280;
               ">

    @else

       <select name="plant_id">

                style="
                    width:100%;
                    padding:15px 18px;
                    border:1px solid #d1d5db;
                    border-radius:16px;
                    font-size:15px;
                    outline:none;
                ">

            @php

$crops = \App\Models\Plant::where(
    'status',
    'active'
)->orderBy('name')->get();

@endphp

@foreach($crops as $crop)

<option
   value="{{ $crop->id }}"
   {{ $staff->plant_id == $crop->id ? 'selected' : '' }}
>

    {{ $crop->name }}

</option>

@endforeach

        </select>

    @endif

</div>


                    <!-- STATUS -->
                    <div>

                        <label style="
                            display:block;
                            font-size:15px;
                            font-weight:600;
                            margin-bottom:10px;
                            color:#374151;
                        ">
                            Account Status
                        </label>

                        <select name="status"

                                style="
                                    width:100%;
                                    padding:15px 18px;
                                    border:1px solid #d1d5db;
                                    border-radius:16px;
                                    font-size:15px;
                                    outline:none;
                                ">

                            <option value="Active"
                                {{ $staff->status == 'Active' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="Inactive"
                                {{ $staff->status == 'Inactive' ? 'selected' : '' }}>
                                Inactive
                            </option>

                        </select>

                    </div>

                </div>


                <!-- SAVE -->
                <div style="
                    display:flex;
                    justify-content:flex-end;
                ">

                    <button type="submit"

                            style="
                                background:#546B41;
                                color:white;
                                border:none;
                                padding:13px 22px;
                                border-radius:999px;
                                font-size:15px;
                                font-weight:600;
                                cursor:pointer;
                                box-shadow:0 4px 10px rgba(0,0,0,0.15);
                            ">

                        Save Changes

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

</x-app-layout>