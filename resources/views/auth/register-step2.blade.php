<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - Step 2</title>
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

        .card {
            background: rgba(255,255,255,0.95);
            border-radius: 20px;
            padding: 40px;
            width: 380px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            animation: fadeIn 0.6s ease;
        }

        @keyframes fadeIn {
            from {opacity:0; transform:translateY(20px);}
            to {opacity:1; transform:translateY(0);}
        }

        .logo img {
            width: 60px;
            margin-bottom: 10px;
        }

        h2 {
            color: #546B41;
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

        .readonly {
            background: #f3f3f3;
            color: #555;
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

        .error {
            color: red;
            font-size: 13px;
            text-align: left;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>

<div class="card">

    <div class="logo">
        <img src="/images/logo.png">
    </div>

    <h2>🌱 Complete Registration</h2>

    <form method="POST" action="/complete-register">
        @csrf

        <!-- hidden staff_id -->
        <input type="hidden" name="staff_id" value="{{ $staff->staff_id }}">

        <!-- Name -->
        <input type="text" value="{{ $staff->name }}" class="readonly" disabled>

        <!-- Email -->
        <input type="email" name="email" placeholder="Email" required>
        @error('email')
            <div class="error">{{ $message }}</div>
        @enderror

        <!-- Password -->
        <input type="password" name="password" placeholder="Password" required>
        @error('password')
            <div class="error">{{ $message }}</div>
        @enderror

        <!-- Confirm -->
        <input type="password" name="password_confirmation" placeholder="Confirm Password" required>

        <button type="submit">Register</button>

    </form>

</div>

</body>
</html>