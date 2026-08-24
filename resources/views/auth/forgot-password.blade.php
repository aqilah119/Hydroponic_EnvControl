<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
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

        .container {
            background: rgba(255,255,255,0.9);
            backdrop-filter: blur(10px);
            padding: 35px 40px;
            border-radius: 20px;
            width: 380px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            animation: fadeIn 0.8s ease;
        }

        @keyframes fadeIn {
            from { opacity:0; transform: translateY(30px);}
            to { opacity:1; transform: translateY(0);}
        }

        .logo img {
            width: 60px;
            margin-bottom: 8px;
        }

        h2 {
            margin: 10px 0 15px;
            color: #546B41;
        }

        p {
            font-size: 14px;
            color: #555;
            margin-bottom: 20px;
        }

        input {
            width: 100%;
            padding: 12px;
            border-radius: 10px;
            border: 1px solid #ddd;
            margin-bottom: 15px;
        }

        input:focus {
            border-color: #546B41;
            box-shadow: 0 0 0 2px rgba(84,107,65,0.2);
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
        }

        button:hover {
            background: #435535;
        }

        .success {
            color: green;
            font-size: 13px;
            margin-bottom: 10px;
        }

        .error {
            color: red;
            font-size: 13px;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>

<div class="container">

    <!-- 🌱 LOGO -->
    <div class="logo">
        <img src="/images/logo.png">
    </div>

    <h2>Forgot Password</h2>

    <p>
        Enter your email and we’ll send you a reset link.
    </p>

    <!-- SUCCESS MESSAGE -->
    @if (session('status'))
        <div class="success">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <input type="email" name="email" placeholder="Enter your email" required>

        @error('email')
            <div class="error">{{ $message }}</div>
        @enderror

        <button type="submit">
            Send Reset Link
        </button>
    </form>

</div>

</body>
</html>