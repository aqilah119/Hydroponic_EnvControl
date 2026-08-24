<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;

            background: linear-gradient(135deg, #7A9E5E, #A8C686);

            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 35px 40px;
            width: 360px;
            text-align: center;
            color: #333;

            box-shadow: 0 10px 30px rgba(0,0,0,0.15);

            animation: fadeIn 0.8s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* 🔧 FIX SPACING LOGO */
        .logo {
            margin-bottom: 5px;
        }

        .logo img {
            width: 60px;
        }

        h2 {
            margin-top: 5px;
            margin-bottom: 18px;
            color: #546B41;
        }

        input {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 10px;
            border: 1px solid #ddd;
            outline: none;
        }

        input:focus {
            border-color: #546B41;
            box-shadow: 0 0 0 2px rgba(84,107,65,0.2);
        }

        /* 🔥 PERFECT CENTER CHECKBOX */
        .remember {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            margin-bottom: 18px;
            color: #546B41;
        }

        .remember input {
            margin: 0;
            width: auto;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #546B41;
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #435535;
        }

        .extra {
            margin-top: 12px;
            font-size: 14px;
        }

        .extra a {
            color: #546B41;
            text-decoration: none;
            font-weight: 500; /* optional nampak lebih nice */
        }

        .extra a:hover {
            text-decoration: underline;
        }

        .error {
            font-size: 12px;
            color: red;
            text-align: left;
            margin-bottom: 10px;
        }

    </style>
</head>

<body>

<div class="login-container">

    <!-- 🌱 LOGO -->
    <div class="logo">
        <img src="/images/logo.png">
    </div>

    <h2>Welcome Back !</h2>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email -->
        <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>

        @error('email')
            <div class="error">{{ $message }}</div>
        @enderror

        <!-- Password -->
        <input type="password" name="password" placeholder="Password" required>

        @error('password')
            <div class="error">{{ $message }}</div>
        @enderror

        <!-- ✅ FIXED PERFECT -->
        <label class="remember">
            <input type="checkbox" name="remember">
            <span>Remember me</span>
        </label>

        <button type="submit">Log In</button>

        <div class="extra">
            <a href="{{ route('password.request') }}">Forgot password?</a>
        </div>

    </form>

</div>

</body>
</html>