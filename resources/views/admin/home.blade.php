<x-layouts.admin>
    <div>
        <h1 style="font-size:20px; font-weight:500; color:#cdd6f4; margin-bottom:24px;">Inicio</h1>

        {{-- TARJETAS DE RESUMEN --}}
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(180px, 1fr)); gap:16px; margin-bottom:32px;">
            @php
                $pendientes  = \App\Models\Pedido::where('estado','pendiente')->count();
                $confirmados = \App\Models\Pedido::where('estado','confirmado')->count();
                $totalHoy    = \App\Models\Pedido::whereDate('created_at', today())->sum('total');
                $totalMes    = \App\Models\Pedido::whereMonth('created_at', now()->month)->sum('total');
            @endphp

            <div style="background:#1e1e2e; border:0.5px solid #313244; border-radius:8px; padding:16px;">
                <p style="font-size:11px; color:#6c7086; text-transform:uppercase; letter-spacing:0.06em; margin:0 0 8px;">Pedidos pendientes</p>
                <p style="font-size:28px; font-weight:600; color:#f38ba8; margin:0;">{{ $pendientes }}</p>
            </div>

            <div style="background:#1e1e2e; border:0.5px solid #313244; border-radius:8px; padding:16px;">
                <p style="font-size:11px; color:#6c7086; text-transform:uppercase; letter-spacing:0.06em; margin:0 0 8px;">Confirmados</p>
                <p style="font-size:28px; font-weight:600; color:#a6e3a1; margin:0;">{{ $confirmados }}</p>
            </div>

            <div style="background:#1e1e2e; border:0.5px solid #313244; border-radius:8px; padding:16px;">
                <p style="font-size:11px; color:#6c7086; text-transform:uppercase; letter-spacing:0.06em; margin:0 0 8px;">Ventas hoy</p>
                <p style="font-size:28px; font-weight:600; color:#cba6f7; margin:0;">${{ number_format($totalHoy, 0, ',', '.') }}</p>
            </div>

            <div style="background:#1e1e2e; border:0.5px solid #313244; border-radius:8px; padding:16px;">
                <p style="font-size:11px; color:#6c7086; text-transform:uppercase; letter-spacing:0.06em; margin:0 0 8px;">Ventas este mes</p>
                <p style="font-size:28px; font-weight:600; color:#89b4fa; margin:0;">${{ number_format($totalMes, 0, ',', '.') }}</p>
            </div>
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:24px;">

            {{-- STOCK CRÍTICO --}}
            @php
                $tallasStock = \App\Models\Talla::with('producto')
                    ->where('stock', '<=', 2)
                    ->orderBy('stock')
                    ->get();
            @endphp

            <div>
                <h2 style="font-size:13px; font-weight:500; color:#6c7086; text-transform:uppercase; letter-spacing:0.06em; margin:0 0 12px;">
                    ⚠ Stock crítico
                    @if($tallasStock->count() > 0)
                        <span style="background:#f38ba8; color:#1e1e2e; font-size:10px; font-weight:600; padding:1px 6px; border-radius:10px; margin-left:6px;">{{ $tallasStock->count() }}</span>
                    @endif
                </h2>

                <div style="background:#1e1e2e; border:0.5px solid #313244; border-radius:8px; overflow:hidden;">
                    @if($tallasStock->isEmpty())
                        <p style="padding:20px 16px; color:#6c7086; font-size:13px; margin:0;">Todo el stock está en orden.</p>
                    @else
                        <table style="width:100%; font-size:13px; border-collapse:collapse;">
                            <thead>
                                <tr style="border-bottom:0.5px solid #313244;">
                                    <th style="padding:10px 16px; text-align:left; color:#6c7086; font-weight:400;">Producto</th>
                                    <th style="padding:10px 16px; text-align:left; color:#6c7086; font-weight:400;">Talla</th>
                                    <th style="padding:10px 16px; text-align:left; color:#6c7086; font-weight:400;">Stock</th>
                                    <th style="padding:10px 16px; text-align:left; color:#6c7086; font-weight:400;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tallasStock as $t)
                                    <tr style="border-bottom:0.5px solid #313244;">
                                        <td style="padding:10px 16px; color:#cdd6f4;">{{ $t->producto->nombre ?? '—' }}</td>
                                        <td style="padding:10px 16px; color:#a6adc8;">{{ $t->talla }}</td>
                                        <td style="padding:10px 16px;">
                                            @if($t->stock === 0)
                                                <span style="color:#f38ba8; font-weight:600;">AGOTADO</span>
                                            @else
                                                <span style="color:#fab387; font-weight:600;">{{ $t->stock }}</span>
                                            @endif
                                        </td>
                                        <td style="padding:10px 16px;">
                                            <a href="{{ route('admin.productos.edit', $t->producto_id) }}"
                                               style="font-size:11px; padding:3px 10px; border-radius:4px; background:#313244; color:#cdd6f4; text-decoration:none;">
                                                Editar
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>

            {{-- ÚLTIMOS PEDIDOS --}}
            <div>
                <h2 style="font-size:13px; font-weight:500; color:#6c7086; text-transform:uppercase; letter-spacing:0.06em; margin:0 0 12px;">
                    Últimos pedidos
                </h2>

                <div style="background:#1e1e2e; border:0.5px solid #313244; border-radius:8px; overflow:hidden;">
                    @php $ultimos = \App\Models\Pedido::latest()->take(6)->get(); @endphp
                    @if($ultimos->isEmpty())
                        <p style="padding:20px 16px; color:#6c7086; font-size:13px; margin:0;">Aún no hay pedidos.</p>
                    @else
                        <table style="width:100%; font-size:13px; border-collapse:collapse;">
                            <thead>
                                <tr style="border-bottom:0.5px solid #313244;">
                                    <th style="padding:10px 16px; text-align:left; color:#6c7086; font-weight:400;">#</th>
                                    <th style="padding:10px 16px; text-align:left; color:#6c7086; font-weight:400;">Cliente</th>
                                    <th style="padding:10px 16px; text-align:left; color:#6c7086; font-weight:400;">Total</th>
                                    <th style="padding:10px 16px; text-align:left; color:#6c7086; font-weight:400;">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ultimos as $pedido)
                                    <tr style="border-bottom:0.5px solid #313244;">
                                        <td style="padding:10px 16px; color:#6c7086; font-family:monospace;">{{ $pedido->id }}</td>
                                        <td style="padding:10px 16px; color:#cdd6f4;">{{ $pedido->nombre }}</td>
                                        <td style="padding:10px 16px; color:#cdd6f4;">${{ number_format($pedido->total, 0, ',', '.') }}</td>
                                        <td style="padding:10px 16px;">
                                            @if($pedido->estado === 'pendiente')
                                                <span style="background:#45475a; color:#fab387; font-size:11px; padding:2px 8px; border-radius:4px;">pendiente</span>
                                            @elseif($pedido->estado === 'confirmado')
                                                <span style="background:#313244; color:#89b4fa; font-size:11px; padding:2px 8px; border-radius:4px;">confirmado</span>
                                            @else
                                                <span style="background:#313244; color:#a6e3a1; font-size:11px; padding:2px 8px; border-radius:4px;">entregado</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div style="padding:12px 16px; border-top:0.5px solid #313244;">
                            <a href="{{ route('admin.pedidos.index') }}" style="font-size:12px; color:#cba6f7; text-decoration:none;">Ver todos los pedidos →</a>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-layouts.admin>
