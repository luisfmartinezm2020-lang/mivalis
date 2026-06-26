<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>MiValis Admin</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased" style="background:#181825; color:#cdd6f4;">
    <div class="flex min-h-screen">

        <aside style="width:220px; background:#1e1e2e; border-right:0.5px solid #313244; display:flex; flex-direction:column;">
            <div style="padding:16px; border-bottom:0.5px solid #313244;">
                <span style="font-size:16px; font-weight:500; color:#cdd6f4;">MiValis Admin</span>
            </div>
            <nav style="flex:1; padding:12px 0;">
                <p style="font-size:10px; color:#6c7086; padding:8px 16px 4px; letter-spacing:0.06em; text-transform:uppercase;">Principal</p>
                <a href="{{ route('dashboard') }}" style="display:block; font-size:13px; color:#a6adc8; padding:8px 16px;" onmouseover="this.style.background='#313244'" onmouseout="this.style.background='transparent'">Inicio</a>
                <p style="font-size:10px; color:#6c7086; padding:12px 16px 4px; letter-spacing:0.06em; text-transform:uppercase;">Tienda</p>
                <a href="{{ route('admin.categorias.index') }}" style="display:block; font-size:13px; color:#a6adc8; padding:8px 16px;" onmouseover="this.style.background='#313244'" onmouseout="this.style.background='transparent'">Categorías</a>
                <a href="{{ route('admin.productos.index') }}" style="display:block; font-size:13px; color:#a6adc8; padding:8px 16px;" onmouseover="this.style.background='#313244'" onmouseout="this.style.background='transparent'">Productos</a>
                <a href="{{ route('admin.pedidos.index') }}" style="display:block; font-size:13px; color:#a6adc8; padding:8px 16px;" onmouseover="this.style.background='#313244'" onmouseout="this.style.background='transparent'">Pedidos</a>
                <a href="{{ route('admin.ajustes.index') }}" style="display:block; font-size:13px; color:#a6adc8; padding:8px 16px;" onmouseover="this.style.background='#313244'" onmouseout="this.style.background='transparent'">Ajustes</a>
            </nav>
            <div style="padding:16px; border-top:0.5px solid #313244;">
                <p style="font-size:12px; color:#a6adc8;">{{ auth()->user()->name }}</p>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" style="font-size:12px; color:#f38ba8; background:none; border:none; cursor:pointer; padding:0; margin-top:4px;">Cerrar sesión</button>
                </form>
            </div>
        </aside>

        <main style="flex:1; padding:24px;">
            {{ $slot }}
        </main>
    </div>

    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: '¡Bien hecho!',
            text: "{{ session('success') }}",
            background: '#1e1e2e',
            color: '#cdd6f4',
            confirmButtonColor: '#cba6f7',
        });
    </script>
    @endif

    <script>
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: 'No podrás revertir esto.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#cba6f7',
                    cancelButtonColor: '#f38ba8',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar',
                    background: '#1e1e2e',
                    color: '#cdd6f4',
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
</body>
</html>