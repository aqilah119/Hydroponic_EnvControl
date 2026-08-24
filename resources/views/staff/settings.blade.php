<x-app-layout>

<div class="flex justify-center py-8 px-6">

    <div style="width:100%; max-width:950px;">

        <!-- HEADER CARD -->
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
        line-height:1.1;
    ">
        Profile Settings
    </h1>

    <p style="
        font-size:18px;
        color:#6b7280;
        margin:0;
    ">
        Manage your account information and profile.
    </p>

</div>

        <!-- CARD -->
        <div style="
            background:white;
            border-radius:28px;
           padding:32px;
            box-shadow:0 8px 25px rgba(0,0,0,0.08);
        ">

            <form action="{{ route('staff.settings.update') }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf

                <!-- PROFILE SECTION -->
               <div class="profile-section" style="
    display:flex;
    align-items:center;
    gap:30px;
    padding-bottom:30px;
    margin-bottom:35px;
    border-bottom:1px solid #e5e7eb;
">

                  <!-- PROFILE IMAGE -->
<div>

<img id="profilePreview"
     src="{{ $staff && $staff->profile_picture
        ? asset('storage/' . $staff->profile_picture)
        : 'https://ui-avatars.com/api/?name=' . urlencode($staff->user->name ?? 'User') . '&background=546B41&color=fff&size=128' }}"
     style="
        width:100px;
        height:100px;
        border-radius:999px;
        object-fit:cover;
        border:4px solid #546B41;
        box-shadow:0 4px 10px rgba(0,0,0,0.15);
     ">

</div>
                    <!-- INFO -->
                    <div>

                        <h2 style="
                            font-size:24px;
                            font-weight:700;
                            color:#111827;
                            margin-bottom:6px;
                        ">
                            {{ $staff->user->name ?? 'User' }}
                        </h2>

                        <p style="
                            font-size:18px;
                            color:#6b7280;
                            margin-bottom:20px;
                        ">
                            {{ $user->email }}
                        </p>

                        <!-- CHANGE BUTTON -->
                        <label style="
                            display:inline-flex;
                            align-items:center;
                            gap:10px;
                            background:#546B41;
                            color:white;
                            padding:10px 18px;
                            border-radius:999px;
                            cursor:pointer;
                            font-weight:500;
                            box-shadow:0 4px 10px rgba(0,0,0,0.15);
                        ">

                            <i class="fa-solid fa-camera"></i>

                            Change Profile Picture

                            <input type="file"
       name="profile_picture"
       id="profileInput"
       accept="image/*"
       hidden>

                        </label>

                    </div>

                </div>

                <!-- EMAIL -->
                <div style="margin-bottom:28px;">

                    <label style="
                        display:block;
                        font-size:17px;
                        font-weight:600;
                        margin-bottom:12px;
                        color:#374151;
                    ">
                        Email Address
                    </label>

                    <input type="email"
                           name="email"
                           value="{{ $user->email }}"
                           style="
                                width:100%;
                                padding:16px 20px;
                                border:1px solid #d1d5db;
                                border-radius:18px;
                                font-size:16px;
                                outline:none;
                           ">

                </div>
 
                <!-- CONTACT NUMBER -->
<div style="margin-bottom:28px;">

    <label style="
        display:block;
        font-size:17px;
        font-weight:600;
        margin-bottom:12px;
        color:#374151;
    ">
        Contact Number
    </label>

    <input type="text"
       id="phone_number"
       name="phone_number"
           value="{{ $staff->phone_number ?? '' }}"
           placeholder="e.g. 0123456789"
           style="
                width:100%;
                padding:16px 20px;
                border:1px solid #d1d5db;
                border-radius:18px;
                font-size:16px;
                outline:none;
           ">
    
    @error('phone_number')

    

    <p style="
        color:red;
        margin-top:8px;
        font-size:14px;
    ">
        {{ $message }}
    </p>

@enderror

<p id="phoneError"
   style="
        color:red;
        margin-top:8px;
        font-size:14px;
        display:none;
   ">
    Not a valid number.
</p>

</div>

                <!-- ADDRESS -->
                <div style="margin-bottom:35px;">

                    <label style="
                        display:block;
                        font-size:17px;
                        font-weight:600;
                        margin-bottom:12px;
                        color:#374151;
                    ">
                        Address
                    </label>

                    <textarea name="address"
                              rows="4"
                              style="
                                width:100%;
                                padding:16px 20px;
                                border:1px solid #d1d5db;
                                border-radius:18px;
                                font-size:16px;
                                resize:none;
                                outline:none;
                              ">{{ $staff->address ?? '' }}</textarea>

                </div>


                <!-- SAVE BUTTON -->
                <div style="
                    display:flex;
                    justify-content:flex-end;
                ">

                    <button type="submit"
                              id="saveBtn"
                            style="
                                display:flex;
                                align-items:center;
                                gap:10px;
                                background:#546B41;
                                color:white;
                                border:none;
                                padding:11px 20px;
                                border-radius:999px;
                                font-size:14px;
                                font-weight:500;
                                cursor:pointer;
                                box-shadow:0 4px 10px rgba(0,0,0,0.15);
                            ">

                        <i class="fa-solid fa-floppy-disk"></i>

                        Save Changes

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>



<script>

document.getElementById('profileInput').addEventListener('change', function(event) {

    const image = document.getElementById('profilePreview');

    const file = event.target.files[0];

    if(file) {

        image.src = URL.createObjectURL(file);

    }

});

</script>


<script>

const phoneInput = document.getElementById('phone_number');
const saveBtn = document.getElementById('saveBtn');
const phoneError = document.getElementById('phoneError');

function validatePhone() {

    // buang selain nombor
    phoneInput.value = phoneInput.value.replace(/[^0-9]/g, '');

    const phone = phoneInput.value;

    // hanya 10 atau 11 digit
    const valid = /^[0-9]{10,11}$/.test(phone);

    if (!valid) {

        phoneError.style.display = 'block';

        saveBtn.disabled = true;
        saveBtn.style.opacity = '0.5';
        saveBtn.style.cursor = 'not-allowed';

    } else {

        phoneError.style.display = 'none';

        saveBtn.disabled = false;
        saveBtn.style.opacity = '1';
        saveBtn.style.cursor = 'pointer';
    }
}

// masa user type
phoneInput.addEventListener('input', validatePhone);

// masa page load
validatePhone();

</script>


<style>
@media (max-width: 767px) {

    .profile-section {
        flex-direction: column !important;
        align-items: center !important;
        text-align: center;
        gap: 20px !important;
    }

    .profile-section img {
        width: 100px !important;
        height: 100px !important;
        min-width: 100px !important;
        max-width: 100px !important;
        display: block !important;
    }

    .profile-section > div:last-child {
        width: 100%;
    }

    .profile-section label {
        justify-content: center;
        max-width: 100%;
    }
}
</style>

</x-app-layout>