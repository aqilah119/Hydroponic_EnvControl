<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
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
            width: 360px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
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

        button {
            width: 100%;
            padding: 12px;
            background: #546B41;
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: bold;
        }

        button:hover {
            background: #435535;
        }

        .error {
            color: red;
            font-size: 13px;
            text-align: left;
        }
    </style>
</head>

<body>

<div class="card">

    <div class="logo">
        <img src="/images/logo.png">
    </div>

    <h2>Please Enter Staff ID</h2>

    <form method="POST" action="{{ route('check.staff') }}">
        @csrf

        <input type="text" name="staff_id" placeholder="e.g. ST001" required>

        @error('staff_id')
            <div class="error">{{ $message }}</div>
        @enderror

        <button type="submit">Next</button>
    </form>

</div>

</body>
</html>