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

    {{-- OVERLAY --}}
    <div id="carrito-overlay" onclick="cerrarCarrito()"
         style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.4); z-index:998;"></div>

    {{-- PANEL LATERAL --}}
    <div id="carrito-panel"
         style="position:fixed; top:0; right:-420px; width:400px; height:100vh; background:#fff;
                z-index:999; display:flex; flex-direction:column; transition:right 0.3s ease;
                box-shadow:-4px 0 20px rgba(0,0,0,0.1);">

        <div style="display:flex; justify-content:space-between; align-items:center; padding:20px 24px; border-bottom:1px solid #e5e5e5;">
            <h2 style="font-size:13px; font-weight:500; letter-spacing:0.1em; text-transform:uppercase; margin:0;">BOLSA DE COMPRAS</h2>
            <button onclick="cerrarCarrito()" style="background:none; border:none; cursor:pointer; font-size:20px; color:#111;">✕</button>
        </div>

        <div id="carrito-items" style="flex:1; overflow-y:auto; padding:16px 24px;"></div>

        <div id="carrito-footer" style="display:none; padding:20px 24px; border-top:1px solid #e5e5e5;">
            <div style="display:flex; justify-content:space-between; margin-bottom:16px;">
                <span style="font-size:13px; font-weight:500; text-transform:uppercase; letter-spacing:0.06em;">TOTAL</span>
                <span id="carrito-total" style="font-size:16px; font-weight:500;"></span>
            </div>
            <a href="{{ route('carrito.checkout') }}"
               style="display:block; background:#111; color:#fff; padding:14px; text-align:center;
                      text-decoration:none; font-size:12px; letter-spacing:0.1em; text-transform:uppercase; margin-bottom:10px;">
                IR A PAGAR
            </a>
            <a href="{{ route('carrito.index') }}"
               style="display:block; text-align:center; font-size:11px; color:#666; text-decoration:none;
                      letter-spacing:0.06em; text-transform:uppercase;">
                VER CARRITO COMPLETO
            </a>
        </div>
    </div>

    <script>
    function abrirCarrito() {
        document.getElementById('carrito-panel').style.right = '0';
        document.getElementById('carrito-overlay').style.display = 'block';
        document.body.style.overflow = 'hidden';
    }

    function cerrarCarrito() {
        document.getElementById('carrito-panel').style.right = '-420px';
        document.getElementById('carrito-overlay').style.display = 'none';
        document.body.style.overflow = '';
    }

    function agregarAlCarrito(productoId, talla, cantidad) {
        if (!talla) {
            alert('Por favor selecciona una talla');
            return;
        }

        const datos = new FormData();
        datos.append('producto_id', productoId);
        datos.append('talla', talla);
        datos.append('cantidad', cantidad || 1);
        datos.append('_token', '{{ csrf_token() }}');

        fetch('{{ route("carrito.agregar") }}', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: datos
        })
        .then(r => r.json())
        .then(json => {
            if (json.success) {
                renderizarCarrito(json.carrito, json.total, json.cantidad);
                abrirCarrito();
            }
        })
        .catch(err => console.error(err));
    }

    function eliminarDelCarrito(clave) {
        fetch(`/carrito/eliminar-ajax/${clave}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(r => r.json())
        .then(json => {
            if (json.success) {
                renderizarCarrito(json.carrito, json.total, json.cantidad);
            }
        });
    }

    function cambiarCantidad(clave, delta) {
        fetch('/carrito/actualizar-ajax', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ clave, delta })
        })
        .then(r => r.json())
        .then(json => {
            if (json.success) {
                renderizarCarrito(json.carrito, json.total, json.cantidad);
            }
        });
    }

    function renderizarCarrito(carrito, total, cantidad) {
        const contenedor = document.getElementById('carrito-items');
        const footer     = document.getElementById('carrito-footer');
        const contador   = document.getElementById('carrito-contador');

        if (contador) {
            contador.innerText = cantidad;
            contador.style.display = cantidad > 0 ? 'flex' : 'none';
        }

        const items = Array.isArray(carrito) ? carrito : Object.values(carrito);

        if (items.length === 0) {
            contenedor.innerHTML = '<p style="color:#666;font-size:13px;text-align:center;padding:40px 0;">Tu bolsa está vacía</p>';
            footer.style.display = 'none';
            return;
        }

        let html = '';
        items.forEach(item => {
            const subtotal = (item.precio * item.cantidad).toLocaleString('es-CO');
            const imagen   = item.imagen ? `/storage/${item.imagen}` : '';
            const clave    = item.id + '_' + item.talla;

            html += `
            <div style="display:flex; gap:12px; margin-bottom:16px; padding-bottom:16px; border-bottom:0.5px solid #e5e5e5;">
                <div style="width:72px; height:72px; background:#e8e6e2; flex-shrink:0; overflow:hidden;">
                    ${imagen ? `<img src="${imagen}" style="width:100%;height:100%;object-fit:cover;">` : ''}
                </div>
                <div style="flex:1;">
                    <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                        <p style="font-size:11px; font-weight:500; text-transform:uppercase; margin:0 0 2px; letter-spacing:0.04em;">${item.nombre}</p>
                        <button onclick="eliminarDelCarrito('${clave}')"
                            style="background:none; border:none; cursor:pointer; font-size:13px; color:#bbb; padding:0; line-height:1; margin-left:8px;">✕</button>
                    </div>
                    <p style="font-size:11px; color:#888; margin:0 0 8px;">TALLA: ${item.talla || '—'}</p>
                    <div style="display:flex; align-items:center; gap:10px; margin-bottom:6px;">
                        <button onclick="cambiarCantidad('${clave}', -1)"
                            style="width:26px; height:26px; border:1px solid #ddd; background:#fff; cursor:pointer; font-size:15px; display:flex; align-items:center; justify-content:center; line-height:1;">−</button>
                        <span style="font-size:12px; min-width:14px; text-align:center;">${item.cantidad}</span>
                        <button onclick="cambiarCantidad('${clave}', 1)"
                            style="width:26px; height:26px; border:1px solid #ddd; background:#fff; cursor:pointer; font-size:15px; display:flex; align-items:center; justify-content:center; line-height:1;">+</button>
                    </div>
                    <p style="font-size:12px; color:#111; margin:0;">$ ${subtotal}</p>
                </div>
            </div>`;
        });

        contenedor.innerHTML = html;
        document.getElementById('carrito-total').innerText = '$ ' + Number(total).toLocaleString('es-CO');
        footer.style.display = 'block';
    }

    window.addEventListener('DOMContentLoaded', () => {
        @php
            $carritoSession  = session()->get('carrito', []);
            $totalSession    = array_sum(array_map(fn($i) => $i['precio'] * $i['cantidad'], $carritoSession));
            $cantidadSession = count($carritoSession);
        @endphp

        const carritoInicial  = @json(array_values($carritoSession));
        const totalInicial    = {{ $totalSession }};
        const cantidadInicial = {{ $cantidadSession }};

        if (carritoInicial.length > 0) {
            renderizarCarrito(carritoInicial, totalInicial, cantidadInicial);
        }
    });
    </script>

</body>
</html>