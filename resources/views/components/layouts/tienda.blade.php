<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MiValis</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.5.0/css/all.css">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body style="margin:0; padding:0; background:#f0efed;">

    @include('tienda.partials.navbar')

    <main>
        {{ $slot }}
    </main>

</body>
</html>