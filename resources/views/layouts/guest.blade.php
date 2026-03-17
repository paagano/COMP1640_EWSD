<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>UoG Annual Magazine Portal</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>

    body{
        background: url("{{ asset('images/university-bg.jpg') }}") no-repeat center center fixed;
        background-size: cover;
    }

    /* Dark overlay to improve readability */
    /* body::before{
        content:"";
        position:fixed;
        top:0;
        left:0;
        width:100%;
        height:100%;
        background:rgba(0,0,0,0.45);
        z-index:-1;
    } */

    /* Center auth container */
    .auth-wrapper{
        min-height:100vh;
        display:flex;
        align-items:center;
        justify-content:center;
    }

    /* Login/Register card */
    .auth-card{
        width:420px;
        background:#ffffff;
        padding:35px;
        border-radius:10px;
        box-shadow:0 10px 35px rgba(0,0,0,0.25);
    }

    /* Logo */
    .auth-logo{
        text-align:center;
        margin-bottom:15px;
    }
    
    .auth-title{
        text-align:center;
        font-size:20px;
        font-weight:600;
        margin-bottom:25px;
        color:#1f2937;
    }

    </style>

</head>

<body>

<div class="auth-wrapper">

<div class="auth-card">

    <div class="auth-logo">

        <img src="{{ asset('images/university-logo.png') }}" height="70">

    </div>

    <div class="auth-title">

        Annual Magazine Portal

    </div>

    {{ $slot }}

</div>

</div>

</body>
</html>