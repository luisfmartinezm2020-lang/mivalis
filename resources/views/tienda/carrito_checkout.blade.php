<x-layouts.tienda>
<script>
window.addEventListener('DOMContentLoaded', () => {
    const carrito  = @json(array_values(session()->get('carrito', [])));
    const total    = {{ array_sum(array_map(fn($i) => $i['precio'] * $i['cantidad'], session()->get('carrito', []))) }};
    const cantidad = {{ count(session()->get('carrito', [])) }};
    renderizarCarrito(carrito, total, cantidad);
});
</script>

    <div style="max-width:900px; margin:0 auto; padding:24px;">

        <h1 style="font-size:20px; font-weight:500; letter-spacing:0.06em; text-transform:uppercase; color:#111; margin-bottom:24px;">
            Finalizar compra
        </h1>

        <div style="display:flex; flex-wrap:wrap; gap:40px;">

            {{-- RESUMEN DEL CARRITO --}}
            <div style="flex:1 1 300px;">
                <h2 style="font-size:13px; letter-spacing:0.08em; text-transform:uppercase; color:#666; margin-bottom:16px;">
                    Tu pedido
                </h2>

                <div id="resumen-items">
                @foreach($carrito as $key => $item)
                    @php $clave = $item['id'] . '_' . $item['talla']; @endphp
                    <div id="resumen-{{ $clave }}" style="display:flex; gap:12px; margin-bottom:16px; padding-bottom:16px; border-bottom:0.5px solid #e5e5e5; align-items:flex-start;">
                        <div style="width:60px; height:60px; background:#e8e6e2; position:relative; overflow:hidden; flex-shrink:0;">
                            @if($item['imagen'])
                                <img src="{{ Storage::url($item['imagen']) }}" style="width:100%; height:100%; object-fit:cover;">
                            @endif
                        </div>
                        <div style="flex:1;">
                            <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                                <p style="font-size:12px; font-weight:500; text-transform:uppercase; margin:0 0 4px;">{{ $item['nombre'] }}</p>
                                <button onclick="eliminarYActualizarResumen('{{ $clave }}')"
                                    style="background:none; border:none; cursor:pointer; font-size:13px; color:#bbb; padding:0; line-height:1; margin-left:8px;">✕</button>
                            </div>
                            @if($item['talla'])
                                <p style="font-size:12px; color:#666; margin:0 0 4px;">Talla: {{ $item['talla'] }}</p>
                            @endif
                            {{-- Tipo de item --}}
                            @if(($item['tipo'] ?? 'venta') === 'alquiler')
                                <p style="font-size:11px; color:#fab387; margin:0 0 4px; text-transform:uppercase; letter-spacing:0.04em;">Alquiler</p>
                            @else
                                <p style="font-size:11px; color:#a6adc8; margin:0 0 4px; text-transform:uppercase; letter-spacing:0.04em;">Venta</p>
                            @endif
                            {{-- Cantidad con botones --}}
                            <div style="display:flex; align-items:center; gap:10px; margin-bottom:4px;">
                                <button onclick="cambiarYActualizarResumen('{{ $clave }}', -1)"
                                    style="width:24px; height:24px; border:1px solid #ddd; background:#fff; cursor:pointer; font-size:14px; display:flex; align-items:center; justify-content:center;">−</button>
                                <span id="cant-{{ $clave }}" style="font-size:12px; min-width:14px; text-align:center;">{{ $item['cantidad'] }}</span>
                                <button onclick="cambiarYActualizarResumen('{{ $clave }}', 1)"
                                    style="width:24px; height:24px; border:1px solid #ddd; background:#fff; cursor:pointer; font-size:14px; display:flex; align-items:center; justify-content:center;">+</button>
                            </div>
                            <p id="sub-{{ $clave }}" style="font-size:12px; margin:0;">${{ number_format($item['precio'] * $item['cantidad'], 0, ',', '.') }}</p>
                        </div>
                    </div>
                @endforeach
                </div>

                {{-- TOTAL --}}
                <div style="display:flex; justify-content:space-between; padding-top:16px; border-top:1px solid #111;">
                    <span style="font-size:13px; font-weight:500; text-transform:uppercase; letter-spacing:0.06em;">Total</span>
                    <span id="resumen-total" style="font-size:16px; font-weight:500;">${{ number_format($total, 0, ',', '.') }}</span>
                </div>
            </div>

            {{-- FORMULARIO --}}
            <div style="flex:1 1 300px;">
                @php $hayAlquiler = collect($carrito)->contains(fn($i) => ($i['tipo'] ?? 'venta') === 'alquiler'); @endphp

                <form action="{{ route('carrito.confirmar') }}" method="POST">
                    @csrf

                    <div style="margin-bottom:16px;">
                        <label style="display:block; font-size:11px; letter-spacing:0.08em; text-transform:uppercase; color:#666; margin-bottom:6px;">Nombre completo</label>
                        <input type="text" name="nombre" required style="width:100%; padding:12px; border:1px solid #e5e5e5; background:#fff; color:#111; font-size:14px; box-sizing:border-box;">
                    </div>

                    <div style="margin-bottom:16px;">
                        <label style="display:block; font-size:11px; letter-spacing:0.08em; text-transform:uppercase; color:#666; margin-bottom:6px;">Celular</label>
                        <input type="text" name="celular" required style="width:100%; padding:12px; border:1px solid #e5e5e5; background:#fff; color:#111; font-size:14px; box-sizing:border-box;">
                    </div>

                    <div style="margin-bottom:16px;">
                        <label style="display:block; font-size:11px; letter-spacing:0.08em; text-transform:uppercase; color:#666; margin-bottom:6px;">Correo electrónico</label>
                        <input type="email" name="correo" required style="width:100%; padding:12px; border:1px solid #e5e5e5; background:#fff; color:#111; font-size:14px; box-sizing:border-box;">
                    </div>

                    <div style="margin-bottom:16px;">
                        <label style="display:block; font-size:11px; letter-spacing:0.08em; text-transform:uppercase; color:#666; margin-bottom:6px;">Dirección</label>
                        <input type="text" name="direccion" required style="width:100%; padding:12px; border:1px solid #e5e5e5; background:#fff; color:#111; font-size:14px; box-sizing:border-box;">
                    </div>

                    <div style="margin-bottom:16px;">
                        <label style="display:block; font-size:11px; letter-spacing:0.08em; text-transform:uppercase; color:#666; margin-bottom:6px;">Ciudad</label>
                        <input type="text" name="ciudad" required style="width:100%; padding:12px; border:1px solid #e5e5e5; background:#fff; color:#111; font-size:14px; box-sizing:border-box;">
                    </div>

                    <div style="margin-bottom:16px;">
                        <label style="display:block; font-size:11px; letter-spacing:0.08em; text-transform:uppercase; color:#666; margin-bottom:6px;">Fecha de entrega</label>
                        <input type="date" name="fecha_entrega" required
                            min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                            style="width:100%; padding:12px; border:1px solid #e5e5e5; background:#fff; color:#111; font-size:14px; box-sizing:border-box;">
                    </div>

                    @if($hayAlquiler)
                    <div style="margin-bottom:16px;">
                        <label style="display:block; font-size:11px; letter-spacing:0.08em; text-transform:uppercase; color:#666; margin-bottom:6px;">
                            Fecha de devolución <span style="color:#fab387;">(alquiler)</span>
                        </label>
                        <input type="date" name="fecha_devolucion"
                            min="{{ date('Y-m-d', strtotime('+2 days')) }}"
                            style="width:100%; padding:12px; border:1px solid #e5e5e5; background:#fff; color:#111; font-size:14px; box-sizing:border-box;">
                        <p style="font-size:11px; color:#888; margin:4px 0 0;">Fecha en que devuelves las prendas en alquiler</p>
                    </div>
                    @endif

                    <button type="submit" style="width:100%; background:#111; color:#fff; padding:14px 32px; border:none; cursor:pointer; font-size:12px; letter-spacing:0.1em; text-transform:uppercase; margin-top:8px;">
                        CONFIRMAR PEDIDO
                    </button>
                </form>
            </div>

        </div>
    </div>

<script>
function eliminarYActualizarResumen(clave) {
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
            const el = document.getElementById(`resumen-${clave}`);
            if (el) el.remove();
            if (json.cantidad === 0) {
                window.location.href = '{{ route("catalogo") }}';
                return;
            }
            document.getElementById('resumen-total').innerText = '$ ' + Number(json.total).toLocaleString('es-CO');
            renderizarCarrito(json.carrito, json.total, json.cantidad);
        }
    });
}

function cambiarYActualizarResumen(clave, delta) {
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
            if (json.cantidad === 0) {
                window.location.href = '{{ route("catalogo") }}';
                return;
            }
            const items = Array.isArray(json.carrito) ? json.carrito : Object.values(json.carrito);
            const item  = items.find(i => (i.id + '_' + i.talla) === clave);
            if (item) {
                document.getElementById(`cant-${clave}`).innerText = item.cantidad;
                document.getElementById(`sub-${clave}`).innerText = '$ ' + (item.precio * item.cantidad).toLocaleString('es-CO');
            } else {
                const el = document.getElementById(`resumen-${clave}`);
                if (el) el.remove();
            }
            document.getElementById('resumen-total').innerText = '$ ' + Number(json.total).toLocaleString('es-CO');
            renderizarCarrito(json.carrito, json.total, json.cantidad);
        }
    });
}
</script>
</x-layouts.tienda>