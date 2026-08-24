<x-app-layout>

<div class="flex justify-center py-8 px-6">

<div style="width:100%; max-width:950px;">

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

Change Password

</h1>

<p style="
font-size:18px;
color:#6b7280;
">

Update your password securely.

</p>

</div>

<!-- CARD -->

<div style="
background:white;
border-radius:28px;
padding:32px;
box-shadow:0 8px 25px rgba(0,0,0,0.08);
">

@if(session('success'))

<div style="
background:#dcfce7;
color:#166534;
padding:14px;
border-radius:12px;
margin-bottom:20px;
">

{{ session('success') }}

</div>

@endif

@if(session('error'))

<div style="
background:#fee2e2;
color:#991b1b;
padding:14px;
border-radius:12px;
margin-bottom:20px;
">

{{ session('error') }}

</div>

@endif


@if ($errors->any())

<div style="
background:#fee2e2;
color:#991b1b;
padding:14px;
border-radius:12px;
margin-bottom:20px;
">

<ul>

@foreach ($errors->all() as $error)

<li>{{ $error }}</li>

@endforeach

</ul>

</div>

@endif

<form
method="POST"
action="{{ route('change.password.update') }}">

@csrf

<!-- CURRENT PASSWORD -->

<div style="margin-bottom:25px;">

<label style="
display:block;
font-size:17px;
font-weight:600;
margin-bottom:10px;
">

Current Password

</label>

<input
type="password"
name="current_password"

style="
width:100%;
padding:16px;
border:1px solid #d1d5db;
border-radius:18px;
">

<div style="margin-top:8px; text-align:right;">

<a
href="#"

onclick="showForgotPasswordMessage(); return false;"

style="
color:#546B41;
font-size:14px;
font-weight:600;
text-decoration:none;
">

Forgot Password?

</a>

</div>

</div>

<!-- NEW PASSWORD -->

<div style="margin-bottom:25px;">

<label style="
display:block;
font-size:17px;
font-weight:600;
margin-bottom:10px;
">

New Password

</label>

<input
type="password"
name="password"

style="
width:100%;
padding:16px;
border:1px solid #d1d5db;
border-radius:18px;
">

</div>

<!-- CONFIRM -->

<div style="margin-bottom:35px;">

<label style="
display:block;
font-size:17px;
font-weight:600;
margin-bottom:10px;
">

Confirm Password

</label>

<input
type="password"
name="password_confirmation"

style="
width:100%;
padding:16px;
border:1px solid #d1d5db;
border-radius:18px;
">

</div>

<button
type="submit"

style="
background:#546B41;
color:white;
border:none;
padding:12px 22px;
border-radius:999px;
font-weight:600;
cursor:pointer;
">

Update Password

</button>

</form>

</div>

</div>

</div>


<!-- FORGOT PASSWORD POPUP -->

<div id="forgotPopup"

style="
display:none;
position:fixed;
top:0;
left:0;
width:100%;
height:100%;
background:rgba(0,0,0,0.4);
z-index:9999;
">

<div

style="
background:white;
width:450px;
padding:30px;
border-radius:20px;

position:absolute;

top:50%;
left:50%;

transform:translate(-50%,-50%);

text-align:center;
">

<h3

style="
font-size:24px;
font-weight:700;
margin-bottom:15px;
color:#546B41;
">

Password Recovery

</h3>

<p

style="
color:#6b7280;
margin-bottom:25px;
">

Please log out and use the Forgot Password
link from the login page.

</p>

<button

onclick="closeForgotPasswordMessage()"

style="
background:#546B41;
color:white;
padding:10px 20px;
border:none;
border-radius:999px;
cursor:pointer;
">

OK

</button>

</div>

</div>

<script>

function showForgotPasswordMessage()
{
document.getElementById('forgotPopup').style.display='block';
}

function closeForgotPasswordMessage()
{
document.getElementById('forgotPopup').style.display='none';
}

</script>

</x-app-layout>