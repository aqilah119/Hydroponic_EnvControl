<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>Hydroponic Environment Control</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: #f5f7f5;
        }

        /* NAVBAR */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 60px;
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: bold;
            font-size: 20px;
            color: #546B41;
        }

        .logo img {
            width: 40px;
        }

        .nav-btn a {
            margin-left: 15px;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 500;
        }

        .login {
            color: #546B41;
        }

        .register {
            background: #546B41;
            color: white;
        }

        /* HERO */
        .hero {
            display: flex;
            padding: 60px;
            align-items: center;
            gap: 50px;
        }

        .hero-text {
            flex: 1;
        }

        .hero-text h1 {
            font-size: 48px;
            color: #2e3d27;
            margin-bottom: 20px;
        }

        .hero-text p {
            color: #555;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .hero-btn a {
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 10px;
            margin-right: 10px;
        }

        .btn-main {
            background: #546B41;
            color: white;
        }

        .btn-outline {
            border: 2px solid #546B41;
            color: #546B41;
        }

        /* IMAGES */
        .hero-img {
            flex: 1;
            display: flex;
            gap: 15px;
        }

        .hero-img img {
            width: 100%;
            border-radius: 15px;
        }

        .img-col {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .feature-card{
    transition:0.3s ease;
    animation:fadeUp 0.8s ease;
    text-align:center;
}

.feature-card:hover{
    transform:translateY(-8px);
    box-shadow:0 15px 35px rgba(0,0,0,0.15);
}

.feature-card:hover .feature-icon{
    transform:scale(1.1);
}

.feature-icon{
    font-size:60px;
    color:#546B41;
    margin-bottom:25px;
    display:block;
    transition:0.3s;
}

.hero-btn a{
    transition:0.3s;
}

.hero-btn a:hover{
    transform:translateY(-3px);
}

@keyframes fadeUp{

from{
    opacity:0;
    transform:translateY(30px);
}

to{
    opacity:1;
    transform:translateY(0);
}

}
@media (max-width: 767px) {

    /* =====================
       NAVBAR
    ===================== */

    .navbar {
        padding: 15px 16px;
        gap: 8px;
        align-items: center;
    }

    .logo {
        font-size: 14px;
        line-height: 1.2;
        flex: 1;
        min-width: 0;
    }

    .logo img {
        width: 30px;
        flex-shrink: 0;
    }

    .nav-btn {
        display: flex;
        align-items: center;
        gap: 5px;
        flex-shrink: 0;
    }

    .nav-btn a {
        margin-left: 0;
        padding: 8px 10px;
        font-size: 12px;
        white-space: nowrap;
    }


    /* =====================
       HERO
    ===================== */

    .hero {
        display: flex;
        flex-direction: column;
        padding: 40px 20px;
        gap: 30px;
        align-items: stretch;
    }

    .hero-text {
        width: 100%;
        box-sizing: border-box;
    }

    .hero-text h1 {
        font-size: 38px;
        line-height: 1.15;
        margin-top: 0;
        margin-bottom: 20px;
    }

    .hero-text p {
        font-size: 17px;
        line-height: 1.6;
        margin-bottom: 25px;
    }


    /* =====================
       HERO BUTTONS
    ===================== */

    .hero-btn {
        display: flex;
        width: 100%;
        gap: 10px;
        flex-wrap: nowrap;
    }

    .hero-btn a {
        flex: 1;
        min-width: 0;
        box-sizing: border-box;
        margin-right: 0;
        padding: 12px 10px;
        text-align: center;
        white-space: nowrap;
        font-size: 14px;
    }


    /* =====================
       HERO IMAGE
    ===================== */

    .hero-img {
        width: 100%;
        display: block;
    }

    .hero-img > img {
        display: block;
        width: 100%;
        height: 230px;
        object-fit: cover;
        border-radius: 15px;
    }

    /* Hide extra images on mobile */
    .img-col {
        display: none;
    }

}
    </style>
</head>

<body>

<!-- NAVBAR -->
<div class="navbar">
    <div class="logo">
        <img src="/images/logo.png" alt="logo">
        Hydroponic Environment Control
    </div>

   <div class="nav-btn">

@auth

    <a href="{{ route('dashboard') }}"
       class="register">
        Dashboard
    </a>

@endauth

@guest

    <a href="{{ route('login') }}"
       class="login">
        Login
    </a>

    <a href="{{ route('register') }}"
       class="register">
        Register
    </a>

@endguest

</div>
</div>

<!-- HERO SECTION -->
<div class="hero">

    <!-- TEXT -->
    <div class="hero-text">
        <h1>Smart Monitoring for Better Hydroponic Growth.</h1>

        <p>
            Monitor and control your hydroponic environment in real-time.
            Ensure optimal conditions for plant growth anytime and anywhere.
        </p>

        <div class="hero-btn">
            <a href="{{ route('login') }}" class="btn-main">
    Get Started
</a>
           <a href="#about" class="btn-outline">
    Learn More
</a>
        </div>
    </div>

    <!-- IMAGES -->
    <div class="hero-img">

        <img src="/images/img1.jpg">

        <div class="img-col">
            <img src="/images/img2.jpeg">
            <img src="/images/img1.jpg">
        </div>

    </div>

</div>

<!-- ABOUT SECTION -->

<div id="about"
style="
background:white;
padding:60px 60px;
text-align:center;
">

<h2 style="
font-size:40px;
color:#2e3d27;
margin-bottom:20px;
">
About The System
</h2>

<p style="
max-width:900px;
margin:auto;
font-size:18px;
line-height:1.8;
color:#555;
">
Hydroponic Environment Control System is designed
to monitor and manage hydroponic farming conditions
in real time. The system helps users monitor pH,
temperature, water level and nutrient concentration
to ensure healthy crop growth.
</p>

</div>

<!-- FEATURES -->

<div
style="
padding:80px 60px;
background:#eef5eb;
">

<h2 style="
text-align:center;
font-size:40px;
color:#2e3d27;
margin-bottom:50px;
">
System Features
</h2>

<div style="
display:flex;
gap:30px;
justify-content:center;
flex-wrap:wrap;
">

<div class="feature-card"
style="
background:white;
border:1px solid #dbe7d2;
padding:30px;
width:300px;
border-radius:15px;
box-shadow:0 4px 15px rgba(0,0,0,0.08);
">

<i class="fas fa-chart-line feature-icon"></i>

<h3>Sensor Monitoring</h3>

<p>
Monitor pH, temperature,
water level and TDS values
in real time.
</p>

</div>

<div class="feature-card"
style="
background:white;
padding:30px;
width:300px;
border-radius:15px;
box-shadow:0 4px 15px rgba(0,0,0,0.08);
">

<i class="fas fa-brain feature-icon"></i>
<h3>Predictive Monitoring</h3>

<p>
Predict potential issues
before they affect crop growth.
</p>

</div>

<div class="feature-card"
style="
background:white;
padding:30px;
width:300px;
border-radius:15px;
box-shadow:0 4px 15px rgba(0,0,0,0.08);
">

<i class="fas fa-seedling feature-icon"></i>
<h3>Crop Management</h3>

<p>
Manage crop information
and environmental thresholds.
</p>

</div>

</div>

</div>

<!-- SYSTEM STATS -->

<div style="
background:white;
padding:70px 60px;
">

<h2 style="
text-align:center;
font-size:40px;
color:#2e3d27;
margin-bottom:50px;
">
System Highlights
</h2>

<div style="
display:flex;
justify-content:center;
gap:80px;
flex-wrap:wrap;
text-align:center;
">

<div>
<h1 style="
font-size:50px;
color:#546B41;
margin:0;
">
24/7
</h1>

<p>
Monitoring
</p>
</div>

<div>
<h1 style="
font-size:50px;
color:#546B41;
margin:0;
">
Real-Time
</h1>

<p>
Sensor Tracking
</p>
</div>

<div>
<h1 style="
font-size:50px;
color:#546B41;
margin:0;
">
Smart
</h1>

<p>
Crop Management
</p>
</div>

</div>

</div>
</body>
</html>