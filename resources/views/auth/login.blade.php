<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MiValis — Iniciar sesión</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body style="margin:0; padding:0; background:#f0efed; font-family:'Figtree', sans-serif; min-height:100vh; display:flex; align-items:center; justify-content:center;">

    <div style="width:100%; max-width:400px; padding:24px;">

        {{-- LOGO --}}
        <div style="text-align:center; margin-bottom:32px;">
            <a href="{{ route('inicio') }}">
                <img src="/img/Logo.jpg" style="height:60px;">
            </a>
        </div>

        <h1 style="font-size:16px; font-weight:500; letter-spacing:0.1em; text-transform:uppercase; color:#111; text-align:center; margin-bottom:24px;">
            INICIAR SESIÓN
        </h1>

        {{-- ERRORES --}}
        @if($errors->any())
            <div style="background:#fee2e2; color:#dc2626; padding:12px; font-size:12px; margin-bottom:16px; border-radius:4px;">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div style="margin-bottom:16px;">
                <label style="display:block; font-size:11px; letter-spacing:0.08em; text-transform:uppercase; color:#666; margin-bottom:6px;">
                    Correo electrónico
                </label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                       style="width:100%; padding:12px; border:1px solid #e5e5e5; background:#fff; color:#111; font-size:14px; box-sizing:border-box;">
            </div>

            <div style="margin-bottom:24px;">
                <label style="display:block; font-size:11px; letter-spacing:0.08em; text-transform:uppercase; color:#666; margin-bottom:6px;">
                    Contraseña
                </label>
                <input type="password" name="password" required
                       style="width:100%; padding:12px; border:1px solid #e5e5e5; background:#fff; color:#111; font-size:14px; box-sizing:border-box;">
            </div>

            <button type="submit" style="width:100%; background:#111; color:#fff; padding:14px; border:none; cursor:pointer; font-size:12px; letter-spacing:0.1em; text-transform:uppercase;">
                ENTRAR
            </button>
        </form>

        <p style="text-align:center; margin-top:16px; font-size:12px; color:#666;">
            ¿No tienes cuenta?
            <a href="{{ route('register') }}" style="color:#111; font-weight:500;">Regístrate</a>
        </p>

        <p style="text-align:center; margin-top:8px; font-size:12px;">
            <a href="{{ route('inicio') }}" style="color:#666;">← Volver a la tienda</a>
        </p>

    </div>

</body>
</html>