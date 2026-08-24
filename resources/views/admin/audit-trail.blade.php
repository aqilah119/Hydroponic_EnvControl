<x-app-layout>

<div class="flex justify-center py-8 px-6">

    <div style="width:100%; max-width:1200px;">

        <!-- HEADER -->
        <div style="
            background:white;
            border-radius:28px;
            padding:24px 30px;
            margin-bottom:30px;
            box-shadow:0 8px 25px rgba(0,0,0,0.08);
        ">

            <h1 style="
                font-size:42px;
                font-weight:700;
                color:#1f2937;
                margin-bottom:8px;
            ">
                Audit Trail
            </h1>

            <p style="
                color:#6b7280;
                font-size:15px;
                margin:0;
            ">
                View and track important activities performed by users in the system.
            </p>

        </div>

        <!-- FILTER CARD -->

<div style="
background:white;
border-radius:28px;
padding:24px;
margin-bottom:30px;
box-shadow:0 8px 25px rgba(0,0,0,0.08);
">

<form method="GET">

<div style="
display:flex;
gap:20px;
align-items:end;
flex-wrap:wrap;
">


<div>

<label style="
display:block;
margin-bottom:8px;
font-weight:600;
">
Date From
</label>

<input
type="date"
name="date_from"
value="{{ request('date_from') }}"
style="
width:180px;
padding:12px;
border:1px solid #d1d5db;
border-radius:12px;
">

</div>

<div>

<label style="
display:block;
margin-bottom:8px;
font-weight:600;
">
Date To
</label>

<input
type="date"
name="date_to"
value="{{ request('date_to') }}"
style="
width:180px;
padding:12px;
border:1px solid #d1d5db;
border-radius:12px;
">

</div>

<div>

<label style="
display:block;
margin-bottom:8px;
font-weight:600;
">
Role
</label>

<select
id="roleFilter"
name="role"
style="
width:220px;
padding:12px;
border:1px solid #d1d5db;
border-radius:12px;
">

<option value="">
All Roles
</option>

@foreach($roles as $role)

<option
value="{{ $role->role }}"
{{ request('role') == $role->role ? 'selected' : '' }}
>

{{ ucfirst($role->role) }}

</option>

@endforeach

</select>

</div>

<div>

<label style="
display:block;
margin-bottom:8px;
font-weight:600;
">
Action
</label>

<select
id="actionFilter"
name="action"
style="
width:220px;
padding:12px;
border:1px solid #d1d5db;
border-radius:12px;
">

<option value="">
All Actions
</option>

@foreach($actions as $action)

<option
value="{{ $action }}"
{{ request('action') == $action ? 'selected' : '' }}
>

{{ $action }}

</option>

@endforeach

</select>

</div>

<div id="cropFilter">

<label style="
display:block;
margin-bottom:8px;
font-weight:600;
">
Assigned Crop
</label>

<select
name="assigned_crop"
style="
width:220px;
padding:12px;
border:1px solid #d1d5db;
border-radius:12px;
">

<option value="">
All Crops
</option>

@foreach($crops as $crop)

<option
value="{{ $crop->name }}"
{{ request('assigned_crop') == $crop->name ? 'selected' : '' }}
>

{{ $crop->name }}

</option>

@endforeach

</select>

</div>

<div>

<button
type="submit"
style="
background:#546B41;
color:white;
padding:12px 20px;
border:none;
border-radius:12px;
font-weight:600;
cursor:pointer;
">

Filter

</button>

<a
href="{{ route('admin.audit.export', request()->query()) }}"
style="
background:#1f2937;
color:white;
padding:12px 20px;
border-radius:12px;
font-weight:600;
text-decoration:none;
display:inline-block;
"
>

Export CSV

</a>

</div>

</div>

</form>

</div>

        <!-- TABLE CARD -->
        <div style="
            background:white;
            border-radius:28px;
            padding:24px;
            box-shadow:0 8px 25px rgba(0,0,0,0.08);
            overflow-x:auto;
        ">

            <table style="
                width:100%;
                border-collapse:collapse;
            ">

                <thead>

                    <tr style="
                        background:#f3f4f6;
                        color:#374151;
                    ">

                        <th style="padding:14px; text-align:left;">ID</th>

                        <th style="padding:14px; text-align:left;">
                            Date & Time
                        </th>

                        <th style="padding:14px; text-align:left;">
                            User
                        </th>

                        <th style="padding:14px; text-align:left;">
                            Role
                        </th>

                        <th style="padding:14px; text-align:left;">
                         Assigned Crop
                        </th>

                        <th style="padding:14px; text-align:left;">
                        Action
                        </th>

                        <th style="padding:14px; text-align:left;">
                        Details
                        </th>

                        <th style="padding:14px; text-align:left;">
                            IP Address
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($logs as $log)

                    <tr style="
                        border-bottom:1px solid #e5e7eb;
                    ">

                        <td style="padding:14px;">
                            {{ $log->id }}
                        </td>

                        <td style="padding:14px;">
    {{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y H:i:s') }}
</td>

                        <td style="padding:14px;">
                            {{ $log->user_name }}
                        </td>

                        <td style="padding:14px;">

                            <span style="
                                background:#ecfdf5;
                                color:#166534;
                                padding:4px 10px;
                                border-radius:999px;
                                font-size:13px;
                                font-weight:600;
                            ">
                                {{ ucfirst($log->role) }}
                            </span>

                        </td>

                        <td style="padding:14px;">

                         {{ $log->assigned_crop ?? '-' }}

                         </td>
                        <td style="
                            padding:14px;
                            font-weight:600;
                            color:#2563eb;
                        ">
                            {{ $log->action }}
                        </td>

                        <td style="padding:14px;">
                            {{ $log->details }}
                        </td>

                        <td style="padding:14px;">
                            {{ $log->ip_address }}
                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td
                            colspan="7"
                            style="
                                text-align:center;
                                padding:40px;
                                color:#6b7280;
                            "
                        >

                            No audit records available.

                        </td>

                    </tr>

                    @endforelse

                </tbody>

</table>

<div style="
margin-top:20px;
display:flex;
justify-content:center;
">
    {{ $logs->appends(request()->query())->links() }}
</div>

</div>

    </div>

</div>

<script>

const selectedAction =
    "{{ request('action') }}";

</script>

<script>

document.addEventListener('DOMContentLoaded', function () {

    const roleFilter =
        document.getElementById('roleFilter');

    const cropFilter =
        document.getElementById('cropFilter');

    const cropSelect =
    cropFilter.querySelector('select');

    const actionFilter =
        document.getElementById('actionFilter');

    const allActions = [

        'User Login',
        'User Logout',

        'Added Staff',
        'Updated Staff',
        'Deleted Staff',

        'Added Crop',
        'Updated Crop',
        'Deleted Crop',

        'Activated Crop',
        'Deactivated Crop'

    ];

    const staffActions = [

        'User Login',
        'User Logout'

    ];

    function updateFilters()
    {
        if(roleFilter.value === 'staff')
        {
            cropFilter.style.display = 'block';

            actionFilter.innerHTML = '';

            let defaultOption =
                document.createElement('option');

            defaultOption.value = '';
            defaultOption.text = 'All Actions';

            actionFilter.appendChild(defaultOption);

            staffActions.forEach(action => {

    let option =
        document.createElement('option');

    option.value = action;
    option.text = action;

    if(action === selectedAction)
    {
        option.selected = true;
    }

    actionFilter.appendChild(option);

});
        }
        else
{
    cropFilter.style.display = 'none';

    // RESET crop bila bukan staff

    const cropSelect =
        cropFilter.querySelector('select');

    cropSelect.value = '';

    actionFilter.innerHTML = '';

            let defaultOption =
                document.createElement('option');

            defaultOption.value = '';
            defaultOption.text = 'All Actions';

            actionFilter.appendChild(defaultOption);

            allActions.forEach(action => {

    let option =
        document.createElement('option');

    option.value = action;
    option.text = action;

    if(action === selectedAction)
    {
        option.selected = true;
    }

    actionFilter.appendChild(option);

});
        }
    }

    updateFilters();

    roleFilter.addEventListener(
        'change',
        updateFilters
    );

});

</script>

</x-app-layout>