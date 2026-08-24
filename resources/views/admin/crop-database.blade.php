<x-app-layout>

<div class="flex justify-center py-8 px-6">

    <div style="width:100%; max-width:1100px;">

        <!-- HEADER CARD -->
       <div class="crop-header-card" style="
    background:white; 
    border-radius:28px; 
    padding:24px 30px; 
    margin-bottom:30px; 
    box-shadow:0 8px 25px rgba(0,0,0,0.08); 
    display:flex; 
    justify-content:space-between; 
    align-items:center; 
">

            <div>

                <h1 class="crop-page-title" style="
    font-size:28px; 
    font-weight:700; 
    color:#1f2937; 
    margin-bottom:8px; 
">
                    Manage crop
                </h1>

                <p style="
                    color:#6b7280;
                    font-size:15px;
                    margin:0;
                ">
                    Manage crop environmental requirements used for suitability analysis.
                </p>

            </div>

            <a href="{{ route('admin.add.crop') }}"
   class="add-crop-btn"
   style="
    background:#546B41;
    color:white;
    text-decoration:none;
    padding:10px 18px;
    border-radius:999px;
    font-size:14px;
    font-weight:600;
    display:inline-block;
    box-shadow:0 4px 12px rgba(0,0,0,0.15);
   ">
                <i class="fas fa-seedling"></i>
&nbsp;
Add Crop

            </a>

        </div>

        <!-- CROP LIST -->
       <div class="crop-list-card" style="
    background:white; 
    border-radius:28px; 
    padding:30px; 
    box-shadow:0 8px 25px rgba(0,0,0,0.08); 
">

<h2 class="crop-list-title" style="
    font-size:30px; 
    font-weight:700; 
    color:#111827; 
    margin-bottom:25px; 
">

    <i class="fas fa-seedling"
       style="color:#546B41;margin-right:10px;">
    </i>

    Crop List

</h2>

            <p style="
    color:#6b7280;
    margin-bottom:20px;
">
    Total Crops: {{ $crops->count() }}
</p>

          @foreach($crops as $crop)

<div class="crop-card" style="
    display:flex; 
    justify-content:space-between; 
    align-items:center; 
    padding:18px 22px; 
    border:1px solid #e5e7eb; 
    border-radius:22px; 
    margin-bottom:16px; 
    background:#f9faf8; 
">

               <div class="crop-info">
 
    <h3 style="
                        margin:0;
                        font-size:18px;
                        font-weight:700;
                        color:#111827;
                    ">
                        {{ $crop->name }}
                    </h3>

                    <p class="crop-page-description" style="
    color:#6b7280; 
    font-size:15px; 
    margin:0; 
">
                        {{ $crop->category }}
                    </p>

<div style="
    margin-top:12px;
    color:#4b5563;
    font-size:13px;
    line-height:1.8;
">

    <div>
        <strong>pH:</strong>
        {{ $crop->ph_min }} - {{ $crop->ph_max }}
    </div>

    <div>
        <strong>Temperature:</strong>
        {{ $crop->temp_min }}°C - {{ $crop->temp_max }}°C
    </div>

    <div>
        <strong>TDS:</strong>
        {{ $crop->tds_min }} - {{ $crop->tds_max }}
    </div>

    <div>
        <strong>Water Level:</strong>
        ≥ {{ $crop->water_min }}%
    </div>

</div>

                </div>

<div class="crop-actions" style="
    display:flex; 
    flex-direction:column; 
    align-items:flex-end; 
    gap:10px; 
">

                 @if($crop->status == 'active')

<span style="
    background:#dcfce7;
    color:#166534;
    padding:6px 12px;
    border-radius:999px;
    font-size:13px;
    font-weight:600;
">
    Active
</span>

@else

<span style="
    background:#fee2e2;
    color:#dc2626;
    padding:6px 12px;
    border-radius:999px;
    font-size:13px;
    font-weight:600;
">
    Inactive
</span>

@endif
                    

                    <a 
href="{{ route('admin.edit.crop', $crop->id) }}"
class="crop-edit-btn"
style="
    background:white;
    border:1px solid #d1d5db;
    padding:10px 18px;
    border-radius:999px;
    cursor:pointer;
    font-weight:600;
    text-decoration:none;
    color:black;
">

    <i class="fas fa-pen-to-square"></i>
&nbsp;
Edit Crop

</a>

<button 
    type="button"
    class="crop-delete-btn"
    onclick="openDeleteModal(
        '{{ $crop->id }}',
        '{{ $crop->name }}'
    )"

    style="
        background:#dc2626;
        color:white;
        border:none;
        padding:10px 18px;
        border-radius:999px;
        cursor:pointer;
        font-weight:600;
    ">

    <i class="fas fa-trash-can"></i>
&nbsp;
Delete

</button>
                </div>

            </div>

            @endforeach

        </div>

    </div>

</div>


<!-- DELETE MODAL -->

<div
    id="deleteModal"

    style="
        display:none;
        position:fixed;
        top:0;
        left:0;
        width:100%;
        height:100%;
        background:rgba(0,0,0,0.45);
        z-index:9999;
        justify-content:center;
        align-items:center;
    ">

    <div style="
        background:white;
        width:420px;
        border-radius:24px;
        padding:30px;
        box-shadow:0 20px 50px rgba(0,0,0,0.25);
    ">

        <h2 style="
    font-size:24px;
    font-weight:700;
    margin-bottom:12px;
    color:#111827;
">

    <i class="fas fa-triangle-exclamation"
       style="color:#dc2626;margin-right:8px;">
    </i>

    Delete Crop

</h2>

        <p
            id="deleteMessage"

            style="
                color:#6b7280;
                margin-bottom:25px;
                line-height:1.6;
            ">
        </p>

        <form
            id="deleteForm"
            method="POST">

            @csrf
            @method('DELETE')

            <div style="
                display:flex;
                justify-content:flex-end;
                gap:12px;
            ">

                <button
                    type="button"

                    onclick="closeDeleteModal()"

                    style="
                        background:#f3f4f6;
                        border:none;
                        padding:10px 18px;
                        border-radius:12px;
                        cursor:pointer;
                    ">
                    Cancel
                </button>

                <button
                    type="submit"

                    style="
                        background:#dc2626;
                        color:white;
                        border:none;
                        padding:10px 18px;
                        border-radius:12px;
                        cursor:pointer;
                        font-weight:600;
                    ">
                    Delete
                </button>

            </div>

        </form>

    </div>

</div>

<script>

function openDeleteModal(id, name)
{
    document.getElementById('deleteModal').style.display = 'flex';

    document.getElementById('deleteMessage').innerHTML =
        'Are you sure you want to delete <strong>' +
        name +
        '</strong>?';

    document.getElementById('deleteForm').action =
        '/delete-crop/' + id;
}

function closeDeleteModal()
{
    document.getElementById('deleteModal').style.display = 'none';
}

</script>

<style>

@media (max-width: 767px) {

    /* =========================
       MANAGE CROP MOBILE
    ========================= */

    /* HEADER */
    .crop-header-card {
        padding: 24px !important;
        border-radius: 22px !important;

        flex-direction: column !important;
        align-items: stretch !important;
        gap: 20px !important;
    }


    /* PAGE TITLE */
    .crop-page-title {
        font-size: 24px !important;
        line-height: 1.25 !important;
        margin-bottom: 10px !important;
    }


    /* DESCRIPTION */
    .crop-page-description {
        font-size: 14px !important;
        line-height: 1.6 !important;
    }


    /* ADD CROP */
    .add-crop-btn {
        align-self: flex-start !important;

        padding: 10px 18px !important;

        font-size: 14px !important;

        white-space: nowrap !important;

        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;

        width: fit-content !important;
    }


    /* CROP LIST */
    .crop-list-card {
        padding: 24px !important;
        border-radius: 22px !important;
    }


    /* CROP LIST TITLE */
    .crop-list-title {
        font-size: 24px !important;
        line-height: 1.3 !important;
        margin-bottom: 20px !important;
    }


    /* INDIVIDUAL CROP CARD */
    .crop-card {
        padding: 20px !important;

        flex-direction: column !important;

        align-items: stretch !important;

        gap: 20px !important;
    }


    /* CROP INFORMATION */
    .crop-info {
        width: 100% !important;
    }


    .crop-info h3 {
        font-size: 18px !important;
        line-height: 1.3 !important;
    }


    .crop-info p {
        font-size: 14px !important;
    }


    /* PARAMETER INFORMATION */
    .crop-info > div {
        font-size: 13px !important;
        line-height: 1.8 !important;
    }


    /* ACTION AREA */
.crop-actions {
    width: 100% !important;

    flex-direction: row !important;

    align-items: center !important;

    justify-content: flex-start !important;

    flex-wrap: wrap !important;

    gap: 8px !important;
}


    /* ACTIVE / INACTIVE */
    .crop-actions > span {
        font-size: 12px !important;

        padding: 6px 10px !important;
    }


    /* EDIT BUTTON */
.crop-edit-btn {
    padding: 9px 12px !important;
    font-size: 13px !important;
    white-space: nowrap !important;
}

    /* DELETE BUTTON */
.crop-delete-btn {
    padding: 9px 14px !important;
    font-size: 13px !important;
    white-space: nowrap !important;
}

}

</style>

</x-app-layout>


@if(session('success'))

<div id="success-alert" style="
    position:fixed;
    top:20px;
    right:20px;
    background:#546B41;
    color:white;
    padding:14px 20px;
    border-radius:12px;
    z-index:9999;
    box-shadow:0 4px 12px rgba(0,0,0,0.15);
">
    {{ session('success') }}
</div>

<script>
setTimeout(() => {
    const alert = document.getElementById('success-alert');
    if(alert){
        alert.remove();
    }
}, 3000);
</script>

@endif

@if(session('error'))

<div id="error-alert" style="
    position:fixed;
    top:20px;
    right:20px;
    background:#dc2626;
    color:white;
    padding:14px 20px;
    border-radius:12px;
    z-index:9999;
">

    {{ session('error') }}

</div>

<script>

setTimeout(() => {

    document.getElementById('error-alert')?.remove();

}, 4000);

</script>

@endif