<x-app-layout>

<div class="flex justify-center py-8 px-6">

    <div style="width:100%; max-width:1100px;">

   @if(session('success'))

<div id="successToast"

style="
    position:fixed;
    top:25px;
    right:25px;
    background:white;
    border-left:5px solid #22c55e;
    color:#111827;
    padding:16px 22px;
    border-radius:16px;
    box-shadow:0 10px 25px rgba(0,0,0,0.12);
    z-index:99999;
    min-width:320px;

    display:flex;
    align-items:center;
    gap:12px;

    animation:slideIn 0.4s ease;
">

    <div style="
        width:32px;
        height:32px;
        border-radius:999px;
        background:#dcfce7;
        color:#16a34a;

        display:flex;
        align-items:center;
        justify-content:center;

        font-weight:bold;
    ">
        ✓
    </div>

    <div>

        <div style="
            font-weight:700;
            color:#111827;
        ">
            Success
        </div>

        <div style="
            font-size:14px;
            color:#6b7280;
        ">
            {{ session('success') }}
        </div>

    </div>

</div>

@endif

@if(session('error'))

<div id="errorToast"

style="
    position:fixed;
    top:25px;
    right:25px;
    background:white;
    border-left:5px solid #ef4444;
    color:#111827;
    padding:16px 22px;
    border-radius:16px;
    box-shadow:0 10px 25px rgba(0,0,0,0.12);
    z-index:99999;
    min-width:380px;

    display:flex;
    align-items:center;
    gap:12px;

    animation:slideIn 0.4s ease;
">

    <div style="
        width:32px;
        height:32px;
        border-radius:999px;
        background:#fee2e2;
        color:#dc2626;

        display:flex;
        align-items:center;
        justify-content:center;

        font-weight:bold;
    ">
        !
    </div>

    <div>

        <div style="
            font-weight:700;
            color:#111827;
        ">
            Action Blocked
        </div>

        <div style="
            font-size:14px;
            color:#6b7280;
        ">
            {{ session('error') }}
        </div>

    </div>

</div>

@endif

        <!-- HEADER CARD -->
        <div class="staff-header-card" style="
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

                <h1 class="staff-page-title" style="
                    font-size:28px;
                    font-weight:700;
                    color:#1f2937;
                    margin-bottom:8px;
                ">
                    Staff Management
                </h1>

<div style="
    margin-top:10px;
    display:flex;
    flex-direction:column;
    gap:12px;
">

    <div style="display:flex;align-items:center;gap:10px;color:#546B41;font-weight:600;">
        <i class="fas fa-user-shield"></i>
        <span>Admin : {{ $adminCount }}</span>
    </div>

    <div style="display:flex;align-items:center;gap:10px;color:#546B41;font-weight:600;">
        <i class="fas fa-users"></i>
        <span>Staff - Lettuce : {{ $lettuceCount }}</span>
    </div>

    <div style="display:flex;align-items:center;gap:10px;color:#546B41;font-weight:600;">
        <i class="fas fa-users"></i>
        <span>Staff - Chili : {{ $chiliCount }}</span>
    </div>

    <div style="display:flex;align-items:center;gap:10px;color:#546B41;font-weight:600;">
        <i class="fas fa-users"></i>
        <span>Staff - Pak Choy : {{ $pakchoyCount }}</span>
    </div>

</div>
            </div>

            <!-- ADD STAFF BUTTON -->
<a href="{{ route('admin.add.staff') }}"
   class="add-staff-btn"
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
    transition:0.2s;
">

    + Add Staff

</a>

</div>

        <!-- USER LIST CARD -->
        <div style="
            background:white;
            border-radius:28px;
            padding:30px;
            box-shadow:0 8px 25px rgba(0,0,0,0.08);
        ">

            <!-- TITLE -->
            <div style="margin-bottom:40px;">

                <h2 class="user-list-title" style="
                    font-size:30px;
                    font-weight:700;
                    color:#111827;
                    margin-bottom:4px;
                ">
                    User List
                </h2>

               <div style="
    font-size:17px;
    color:#6b7280;
    margin:0;
">
    <p style="
    margin-bottom:22px;
">
    View and manage all registered users.
</p>

 <!-- SEARCH BOX -->

<div style="
    position:relative;
    flex:1;
">

    <input type="text"
           id="searchInput"
           placeholder="Search by name, Staff ID or phone number..."
           autocomplete="off"

           style="
                width:100%;
                padding:14px 50px 14px 18px;
                border:1px solid #d1d5db;
                border-radius:16px;
                font-size:15px;
                outline:none;
                background:#f9fafb;
           ">

    <i class="fas fa-magnifying-glass"

       style="
            position:absolute;
            right:18px;
            top:50%;
            transform:translateY(-50%);
            color:#9ca3af;
            font-size:16px;
            pointer-events:none;
       ">
    </i>

</div>

    </div>

</div>

            </div>


     <!-- USERS -->
    @foreach($staffs as $staff)

    <div class="staff-card"

    data-name="{{ strtolower($staff->user?->name ?? $staff->name ?? '') }}"
    data-staffid="{{ strtolower($staff->staff_id) }}"
    data-phone="{{ strtolower($staff->phone_number ?? '') }}"

    style="
        display:flex;
        align-items:center;
        justify-content:space-between;
        padding:16px 20px;
        border:1px solid #e5e7eb;
        border-radius:22px;

        margin-top:20px;
        margin-bottom:12px;

        background:#f9faf8;
    ">

                <!-- LEFT -->
<div class="staff-card-left" style="
    display:flex;
    align-items:center;
    gap:18px;
">

                   <!-- PROFILE IMAGE -->

<img
src="{{ $staff->profile_picture
    ? asset('storage/' . $staff->profile_picture)
    : 'https://ui-avatars.com/api/?name=' . urlencode($staff->user?->name ?? $staff->name ?? 'User') . '&background=546B41&color=fff&size=128' }}"
style="
    width:58px;
    height:58px;
    border-radius:999px;
    object-fit:cover;
    border:3px solid #546B41;
    flex-shrink:0;
    box-shadow:0 4px 10px rgba(0,0,0,0.12);
">

                    <!-- USER INFO -->
                    <div>

                        <h3 style="
    margin:0 0 6px 0;
    font-size:18px;
    font-weight:700;
    color:#111827;
">
    {{ $staff->user?->name ?? $staff->name ?? 'User' }}
</h3>

                        <p style="
                            margin:0 0 10px 0;
                            font-size:14px;
                            color:#6b7280;
                        ">
                           @if($staff->user_id)

                    {{ $staff->user->email }}

                    @else

             <span style="color:#9ca3af;">
             Not registered yet
            </span>

            @endif
                        </p>

                        <!-- ROLE -->
                        @if($staff->user && $staff->user->role === 'admin')

                            <span style="
                                background:#fee2e2;
                                color:#dc2626;
                                padding:4px 10px;
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
                               padding:4px 10px;
                                border-radius:999px;
                                font-size:13px;
                                font-weight:600;
                            ">
                                Staff
                            </span>

                        @endif

                    </div>

                </div>


                <!-- ACTION BUTTONS -->
<div class="staff-actions" style="
    display:flex;
    align-items:center;
    gap:12px;
    flex-wrap:wrap;
    justify-content:flex-end;
">

                  @if($staff->user)

    @if($staff->user->role !== 'admin')

    <form method="POST"
          action="{{ route('make.admin', $staff->user->id) }}">

        @csrf

        <button style="
            background:#546B41;
            color:white;
            border:none;
            padding:8px 14px;
            border-radius:999px;
            font-size:14px;
            font-weight:600;
            cursor:pointer;
            box-shadow:0 4px 10px rgba(0,0,0,0.15);
        ">

            Make Admin

        </button>

    </form>

    @endif

@else

    <span style="
        background:#fef3c7;
        color:#d97706;
        padding:8px 14px;
        border-radius:999px;
        font-size:13px;
        font-weight:600;
    ">
        Pending Registration
    </span>

@endif


                    <!-- EDIT -->
<a href="{{ route('admin.edit.staff', $staff->id) }}"

   style="
        background:white;
        border:1px solid #d1d5db;
        padding:10px 18px;
        border-radius:999px;
        font-size:14px;
        font-weight:500;
        cursor:pointer;
        text-decoration:none;
        color:black;
        display:inline-block;
   ">

    Edit

</a>

<!-- DELETE -->

<button
    type="button"

    data-url="{{ route('admin.delete.staff', $staff->id) }}"
   data-name="{{ $staff->user?->name ?? $staff->name }}"

    onclick="openDeleteModal(this.dataset.url, this.dataset.name)"

    style="
        background:#ef4444;
        color:white;
        border:none;
        padding:10px 18px;
        border-radius:999px;
        font-size:14px;
        font-weight:600;
        cursor:pointer;
">

    Delete

</button>
                </div>

            </div>

            @endforeach

        </div>

    </div>

</div>

<script>

const input = document.getElementById('searchInput');

input.addEventListener('keyup', function () {

    let value = this.value.toLowerCase();

    const cards = document.querySelectorAll('.staff-card');

    cards.forEach(card => {

        const name = card.dataset.name;
        const staffid = card.dataset.staffid;
        const phone = card.dataset.phone;

        if (
            name.startsWith(value) ||
            staffid.startsWith(value) ||
            phone.startsWith(value) ||
            value === ''
        ) {

            card.style.display = 'flex';

        } else {

            card.style.display = 'none';

        }

    });

});

</script>

<script>

function openDeleteModal(url, name)
{
    document.getElementById('deleteModal').style.display = 'flex';

    document.getElementById('deleteText').innerHTML =
        'Are you sure you want to delete <b>' +
        name +
        '</b>?<br><br>This action cannot be undone.';

    document.getElementById('deleteForm').action = url;
}

function closeDeleteModal()
{
    document.getElementById('deleteModal').style.display = 'none';
}

</script>

<script>

window.onclick = function(event)
{
    const modal = document.getElementById('deleteModal');

    if(event.target === modal)
    {
        closeDeleteModal();
    }
}

</script>

<!-- DELETE MODAL -->

<div id="deleteModal"

style="
    display:none;
    position:fixed;
    inset:0;
    background:rgba(0,0,0,0.45);
    z-index:9999;
    justify-content:center;
    align-items:center;
">

    <div style="
        background:white;
        width:420px;
        max-width:90%;
        border-radius:24px;
        padding:30px;
        box-shadow:0 10px 30px rgba(0,0,0,0.2);
    ">

<div style="
    width:70px;
    height:70px;
    border-radius:999px;
    background:#ecfdf5;
    color:#546B41;
    font-size:32px;
    font-weight:bold;
    display:flex;
    align-items:center;
    justify-content:center;
    margin:0 auto 20px auto;
">
    ⚠
</div>

        <h2 style="
    text-align:center;
    font-size:32px;
    font-weight:700;
    color:#111827;
    margin-bottom:20px;
">
            Delete Staff
        </h2>

        <p id="deleteText"

style="
    text-align:center;
    color:#6b7280;
    margin-bottom:32px;
    line-height:1.8;
">
        </p>

        <form id="deleteForm"
      action=""
      method="POST">

            @csrf
            @method('DELETE')

           <div style="
    display:flex;
    justify-content:center;
    gap:16px;
">

                <button type="button"

                    onclick="closeDeleteModal()"

                    style="
                        padding:10px 18px;
                        border-radius:999px;
                        border:1px solid #d1d5db;
                        background:white;
                        cursor:pointer;
                    ">

                    Cancel

                </button>

                <button type="submit"

    style="
        padding:12px 24px;
        border:none;
        border-radius:999px;
        background:#ef4444;
        color:white;
        font-weight:600;
        cursor:pointer;
        box-shadow:0 4px 10px rgba(239,68,68,0.25);
    ">

    Delete

</button>

            </div>

        </form>

    </div>

</div>

<script>

setTimeout(() => {

    const successToast =
        document.getElementById('successToast');

    const errorToast =
        document.getElementById('errorToast');

    if(successToast)
    {
        successToast.style.transition = '0.4s';
        successToast.style.opacity = '0';

        setTimeout(() => {

            successToast.remove();

        }, 400);
    }

    if(errorToast)
    {
        errorToast.style.transition = '0.4s';
        errorToast.style.opacity = '0';

        setTimeout(() => {

            errorToast.remove();

        }, 400);
    }

}, 4000);

</script>

<style>

@keyframes slideIn {

    from {

        opacity:0;
        transform:translateX(50px);

    }

    to {

        opacity:1;
        transform:translateX(0);

    }

}

@keyframes slideOut {

    from {

        opacity:1;
        transform:translateX(0);

    }

    to {

        opacity:0;
        transform:translateX(50px);

    }

}
/* =========================
   MOBILE - MANAGE STAFF
========================= */

@media (max-width: 767px) {

    /* Overall page */
    .staff-header-card {
        padding: 24px !important;
        border-radius: 22px !important;

        flex-direction: column !important;
        align-items: stretch !important;
        gap: 22px !important;
    }

    /* Staff Management */
    .staff-page-title {
        font-size: 24px !important;
        line-height: 1.25 !important;
        margin-bottom: 14px !important;
    }

    /* Add Staff */
    .add-staff-btn {
        align-self: flex-start !important;
        padding: 9px 16px !important;
        font-size: 13px !important;
    }

    /* User List */
    .user-list-title {
        font-size: 24px !important;
        line-height: 1.25 !important;
    }

    /* Individual staff card */
    .staff-card {
        padding: 20px !important;
        flex-direction: column !important;
        align-items: stretch !important;
        gap: 18px !important;
    }

    /* Image + name/email */
    .staff-card-left {
        width: 100% !important;
        gap: 15px !important;
        align-items: center !important;
    }

    .staff-card-left img {
        width: 55px !important;
        height: 55px !important;
        min-width: 55px !important;
    }

    /* Name */
    .staff-card-left h3 {
        font-size: 17px !important;
        line-height: 1.3 !important;
    }

    /* Email */
    .staff-card-left p {
        font-size: 13px !important;
        line-height: 1.4 !important;
        word-break: break-word !important;
    }

    /* Action buttons */
    .staff-actions {
        width: 100% !important;
        justify-content: flex-end !important;
        gap: 8px !important;
    }

    .staff-actions button,
    .staff-actions a,
    .staff-actions span {
        font-size: 12px !important;
    }

    /* Search */
    #searchInput {
        font-size: 14px !important;
        padding: 13px 42px 13px 15px !important;
    }
}
</style>

</x-app-layout>