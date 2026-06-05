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
    {{-- PANEL LATERAL DEL CARRITO --}}
<div id="carrito-overlay" onclick="cerrarCarrito()" 
     style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.4); z-index:998;"></div>

<div id="carrito-panel" 
     style="position:fixed; top:0; right:-420px; width:400px; height:100vh; background:#fff; 
            z-index:999; padding:24px; overflow-y:auto; transition:right 0.3s ease; box-shadow:-4px 0 20px rgba(0,0,0,0.1);">
    
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
        <h2 style="font-size:14px; font-weight:500; letter-spacing:0.1em; text-transform:uppercase; margin:0;">
            BOLSA DE COMPRAS
        </h2>
        <button onclick="cerrarCarrito()" style="background:none; border:none; cursor:pointer; font-size:20px; color:#111;">
            ✕
        </button>
    </div>

    <div id="carrito-items"></div>

    <div id="carrito-footer" style="display:none; border-top:1px solid #e5e5e5; padding-top:16px; margin-top:16px;">
        <div style="display:flex; justify-content:space-between; margin-bottom:20px;">
            <span style="font-size:13px; font-weight:500; text-transform:uppercase; letter-spacing:0.06em;">TOTAL</span>
            <span id="carrito-total" style="font-size:16px; font-weight:500;"></span>
        </div>
        <a href="{{ route('carrito.checkout') }}" 
           style="display:block; width:100%; background:#111; color:#fff; padding:16px; text-align:center;
                  text-decoration:none; font-size:12px; letter-spacing:0.1em; text-transform:uppercase; box-sizing:border-box;">
            IR A PAGAR
        </a>
    </div>

</div>
<script>
function abrirCarrito() {
    document.getElementById('carrito-panel').style.right = '0';
    document.getElementById('carrito-overlay').style.display = 'block';
}

function cerrarCarrito() {
    document.getElementById('carrito-panel').style.right = '-420px';
    document.getElementById('carrito-overlay').style.display = 'none';
}

function agregarAlCarrito(productoId, talla) {
    if (!talla) {
        alert('Por favor selecciona una talla');
        return;
    }

    const datos = new FormData();
    datos.append('producto_id', productoId);
    datos.append('talla', talla);
    datos.append('_token', '{{ csrf_token() }}');

    fetch('{{ route("carrito.agregar") }}', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: datos
    })
    .then(r => r.json())
    .then(json => {
        if (json.estado === 'success') {
            renderizarCarrito(json.carrito, json.total, json.cantidad);
            abrirCarrito();
        }
    })
    .catch(err => console.error(err));
}

function renderizarCarrito(items, total, cantidad) {
    const contenedor = document.getElementById('carrito-items');
    const footer     = document.getElementById('carrito-footer');

    // Actualizar contador del navbar
    const contador = document.getElementById('carrito-contador');
    if (contador) contador.innerText = cantidad;

    if (items.length === 0) {
        contenedor.innerHTML = '<p style="color:#666; font-size:13px; text-align:center; padding:40px 0;">Tu bolsa está vacía</p>';
        footer.style.display = 'none';
        return;
    }

    let html = '';
    items.forEach(item => {
        const subtotal = (item.precio * item.cantidad).toLocaleString('es-CO');
        const imagen   = item.imagen 
            ? `/storage/${item.imagen}` 
            : '';

        html += `
            <div style="display:flex; gap:12px; margin-bottom:20px; padding-bottom:20px; border-bottom:0.5px solid #e5e5e5;">
                <div style="width:80px; height:80px; background:#e8e6e2; flex-shrink:0; overflow:hidden;">
                    ${imagen ? `<img src="${imagen}" style="width:100%; height:100%; object-fit:cover;">` : ''}
                </div>
                <div style="flex:1;">
                    <p style="font-size:12px; font-weight:500; text-transform:uppercase; margin:0 0 4px;">${item.nombre}</p>
                    <p style="font-size:12px; color:#666; margin:0 0 4px;">TALLA: ${item.talla || '—'}</p>
                    <p style="font-size:12px; color:#666; margin:0 0 8px;">Cantidad: ${item.cantidad}</p>
                    <p style="font-size:13px; margin:0;">$ ${subtotal}</p>
                </div>
            </div>`;
    });

    contenedor.innerHTML = html;
    document.getElementById('carrito-total').innerText = '$ ' + total;
    footer.style.display = 'block';
}
</script>
</head>
<body style="margin:0; padding:0; background:#f0efed;">

    @include('tienda.partials.navbar')

    <main>
        {{ $slot }}
    </main>

</body>
</html>