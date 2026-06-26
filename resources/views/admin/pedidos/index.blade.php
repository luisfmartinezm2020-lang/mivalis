<x-layouts.admin>
<div style="margin-bottom:24px; display:flex; justify-content:space-between; align-items:center;">
    <h1 style="font-size:16px; font-weight:500; color:#cdd6f4; letter-spacing:0.06em; text-transform:uppercase;">Pedidos</h1>
    <span style="font-size:12px; color:#6c7086;">{{ $pedidos->count() }} pedidos en total</span>
</div>

{{-- TABS --}}
<div style="display:flex; gap:4px; margin-bottom:20px; border-bottom:0.5px solid #313244;">
    <button onclick="filtrar('todos')" id="tab-todos"
        style="font-size:12px; padding:8px 16px; background:none; border:none; cursor:pointer; color:#cba6f7; border-bottom:2px solid #cba6f7; letter-spacing:0.06em; text-transform:uppercase;">
        Todos ({{ $pedidos->count() }})
    </button>
    <button onclick="filtrar('venta')" id="tab-venta"
        style="font-size:12px; padding:8px 16px; background:none; border:none; cursor:pointer; color:#6c7086; border-bottom:2px solid transparent; letter-spacing:0.06em; text-transform:uppercase;">
        Ventas ({{ $pedidos->where('tipo','venta')->count() }})
    </button>
    <button onclick="filtrar('alquiler')" id="tab-alquiler"
        style="font-size:12px; padding:8px 16px; background:none; border:none; cursor:pointer; color:#6c7086; border-bottom:2px solid transparent; letter-spacing:0.06em; text-transform:uppercase;">
        Alquileres ({{ $pedidos->where('tipo','alquiler')->count() }})
    </button>
</div>

{{-- LISTA DE PEDIDOS --}}
<div id="lista-pedidos">
@forelse($pedidos as $pedido)
<div class="pedido-card" data-tipo="{{ $pedido->tipo }}"
    style="background:#1e1e2e; border:0.5px solid #313244; border-radius:8px; margin-bottom:12px; overflow:hidden;">

    {{-- CABECERA --}}
    <div onclick="toggleDetalle({{ $pedido->id }})"
        style="display:flex; justify-content:space-between; align-items:center; padding:14px 16px; cursor:pointer;"
        onmouseover="this.style.background='#252538'" onmouseout="this.style.background='transparent'">

        <div style="display:flex; align-items:center; gap:16px;">
            <span style="font-size:12px; color:#6c7086;">#{{ str_pad($pedido->id, 3, '0', STR_PAD_LEFT) }}</span>
            <div>
                <p style="font-size:13px; color:#cdd6f4; margin:0;">{{ $pedido->nombre }}</p>
                <p style="font-size:11px; color:#6c7086; margin:2px 0 0;">{{ $pedido->celular }} · {{ $pedido->ciudad }}</p>
            </div>
        </div>

        <div style="display:flex; align-items:center; gap:12px;">
            {{-- TIPO --}}
            <span style="font-size:10px; padding:3px 8px; border-radius:20px; letter-spacing:0.06em; text-transform:uppercase;
                background:{{ $pedido->tipo === 'alquiler' ? '#1e3a5f' : '#1a3a2a' }};
                color:{{ $pedido->tipo === 'alquiler' ? '#89b4fa' : '#a6e3a1' }};">
                {{ $pedido->tipo }}
            </span>

            {{-- ESTADO --}}
            @php
                $colores = [
                    'pendiente'  => ['bg'=>'#3a2a1a','color'=>'#fab387'],
                    'confirmado' => ['bg'=>'#1a2a3a','color'=>'#89dceb'],
                    'entregado'  => ['bg'=>'#1a3a2a','color'=>'#a6e3a1'],
                    'alquilado'  => ['bg'=>'#2a1a3a','color'=>'#cba6f7'],
                    'devuelto'   => ['bg'=>'#1e1e2e','color'=>'#6c7086'],
                    'cancelado'  => ['bg'=>'#3a1a1a','color'=>'#f38ba8'],
                ];
                $c = $colores[$pedido->estado] ?? ['bg'=>'#313244','color'=>'#cdd6f4'];
            @endphp
            <span style="font-size:10px; padding:3px 8px; border-radius:20px; letter-spacing:0.06em; text-transform:uppercase;
                background:{{ $c['bg'] }}; color:{{ $c['color'] }};">
                {{ $pedido->estado }}
            </span>

            <span style="font-size:13px; color:#cdd6f4; font-weight:500;">
                ${{ number_format($pedido->total, 0, ',', '.') }}
            </span>
            <span style="font-size:16px; color:#6c7086;" id="arrow-{{ $pedido->id }}">▼</span>
        </div>
    </div>

    {{-- DETALLE (oculto por defecto) --}}
    <div id="detalle-{{ $pedido->id }}" style="display:none; border-top:0.5px solid #313244; padding:16px;">

        {{-- PRODUCTOS --}}
        <div style="margin-bottom:16px;">
            <p style="font-size:10px; color:#6c7086; letter-spacing:0.06em; text-transform:uppercase; margin:0 0 10px;">Productos</p>
            <div style="display:flex; flex-direction:column; gap:8px;">
                @foreach($pedido->productos as $producto)
                <div style="display:flex; align-items:center; gap:12px; background:#181825; border-radius:6px; padding:10px;">
                    @if($producto->imagen)
                    <img src="{{ asset('storage/'.$producto->imagen) }}" alt="{{ $producto->nombre }}"
                        style="width:48px; height:48px; object-fit:cover; border-radius:4px; border:0.5px solid #313244;">
                    @else
                    <div style="width:48px; height:48px; background:#313244; border-radius:4px; display:flex; align-items:center; justify-content:center;">
                        <span style="font-size:18px;">👗</span>
                    </div>
                    @endif
                    <div style="flex:1;">
                        <p style="font-size:13px; color:#cdd6f4; margin:0;">{{ $producto->nombre }}</p>
                        <p style="font-size:11px; color:#6c7086; margin:2px 0 0;">
                            Talla: <strong style="color:#a6adc8;">{{ $producto->pivot->talla }}</strong>
                            · Cantidad: <strong style="color:#a6adc8;">{{ $producto->pivot->cantidad }}</strong>
                            · ${{ number_format($producto->pivot->precio_unitario, 0, ',', '.') }} c/u
                        </p>
                    </div>
                    <span style="font-size:13px; color:#cba6f7; font-weight:500;">
                        ${{ number_format($producto->pivot->precio_unitario * $producto->pivot->cantidad, 0, ',', '.') }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- FECHAS --}}
        <div style="display:flex; gap:16px; margin-bottom:16px;">
            <div style="background:#181825; border-radius:6px; padding:10px 14px;">
                <p style="font-size:10px; color:#6c7086; margin:0 0 2px; text-transform:uppercase; letter-spacing:0.06em;">Fecha entrega</p>
                <p style="font-size:13px; color:#cdd6f4; margin:0;">
                    {{ $pedido->fecha_entrega ? \Carbon\Carbon::parse($pedido->fecha_entrega)->format('d/m/Y') : '—' }}
                </p>
            </div>
            @if($pedido->fecha_devolucion)
            <div style="background:#181825; border-radius:6px; padding:10px 14px;">
                <p style="font-size:10px; color:#6c7086; margin:0 0 2px; text-transform:uppercase; letter-spacing:0.06em;">Fecha devolución</p>
                <p style="font-size:13px; color:#89b4fa; margin:0;">
                    {{ \Carbon\Carbon::parse($pedido->fecha_devolucion)->format('d/m/Y') }}
                </p>
            </div>
            @endif
            <div style="background:#181825; border-radius:6px; padding:10px 14px;">
                <p style="font-size:10px; color:#6c7086; margin:0 0 2px; text-transform:uppercase; letter-spacing:0.06em;">Pedido realizado</p>
                <p style="font-size:13px; color:#cdd6f4; margin:0;">
                    {{ $pedido->created_at->format('d/m/Y H:i') }}
                </p>
            </div>
        </div>

        {{-- BOTONES DE ESTADO --}}
        <div style="display:flex; gap:8px; flex-wrap:wrap;">
            @foreach(['confirmado','entregado','alquilado','devuelto','cancelado'] as $estado)
            @if($pedido->estado !== $estado)
            <form method="POST" action="{{ route('admin.pedidos.estado', [$pedido->id, $estado]) }}">
                @csrf
                @php
                    $btnColores = [
                        'confirmado' => '#89dceb',
                        'entregado'  => '#a6e3a1',
                        'alquilado'  => '#cba6f7',
                        'devuelto'   => '#a6adc8',
                        'cancelado'  => '#f38ba8',
                    ];
                    $btnColor = $btnColores[$estado] ?? '#cdd6f4';
                @endphp
                <button type="submit"
                    style="font-size:11px; padding:6px 14px; background:transparent; border:0.5px solid {{ $btnColor }}; color:{{ $btnColor }}; border-radius:4px; cursor:pointer; letter-spacing:0.06em; text-transform:uppercase;"
                    onmouseover="this.style.background='{{ $btnColor }}20'"
                    onmouseout="this.style.background='transparent'">
                    {{ ucfirst($estado) }}
                </button>
            </form>
            @endif
            @endforeach
        </div>

    </div>
</div>
@empty
<div style="text-align:center; padding:48px; color:#6c7086;">
    <p style="font-size:13px;">No hay pedidos registrados aún.</p>
</div>
@endforelse
</div>

<script>
function toggleDetalle(id) {
    const detalle = document.getElementById('detalle-' + id);
    const arrow   = document.getElementById('arrow-' + id);
    if (detalle.style.display === 'none') {
        detalle.style.display = 'block';
        arrow.textContent = '▲';
    } else {
        detalle.style.display = 'none';
        arrow.textContent = '▼';
    }
}

function filtrar(tipo) {
    // Actualizar tabs
    ['todos','venta','alquiler'].forEach(t => {
        const tab = document.getElementById('tab-' + t);
        tab.style.color          = t === tipo ? '#cba6f7' : '#6c7086';
        tab.style.borderBottom   = t === tipo ? '2px solid #cba6f7' : '2px solid transparent';
    });

    // Filtrar cards
    document.querySelectorAll('.pedido-card').forEach(card => {
        if (tipo === 'todos' || card.dataset.tipo === tipo) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}
</script>
</x-layouts.admin>