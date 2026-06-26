<x-layouts.admin>
    @php
        $totalProductos  = \App\Models\Producto::count();
        $totalCategorias = \App\Models\Categoria::count();
        $agotados        = \App\Models\Talla::where('stock', 0)->count();
        $productosRecientes = \App\Models\Producto::with('categoria')->latest()->take(5)->get();

        $porCategoria = \App\Models\Categoria::withCount('productos')->get();
        $totalVenta   = \App\Models\Producto::where('tipo', 'venta')->count();
        $totalAlquier = \App\Models\Producto::where('tipo', 'alquiler')->count();
    @endphp

    {{-- TARJETAS --}}
    <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:16px; margin-bottom:24px;">

        <div style="background:#1e1e2e; border:0.5px solid #313244; border-radius:8px; padding:20px;">
            <p style="font-size:11px; color:#6c7086; letter-spacing:0.06em; text-transform:uppercase; margin:0 0 8px;">Productos</p>
            <p style="font-size:36px; font-weight:500; color:#cba6f7; margin:0;" id="cnt-productos">0</p>
            <p style="font-size:11px; color:#6c7086; margin:8px 0 0;">total en tienda</p>
        </div>

        <div style="background:#1e1e2e; border:0.5px solid #313244; border-radius:8px; padding:20px;">
            <p style="font-size:11px; color:#6c7086; letter-spacing:0.06em; text-transform:uppercase; margin:0 0 8px;">Categorías</p>
            <p style="font-size:36px; font-weight:500; color:#89b4fa; margin:0;" id="cnt-categorias">0</p>
            <p style="font-size:11px; color:#6c7086; margin:8px 0 0;">total registradas</p>
        </div>

        <div style="background:#1e1e2e; border:0.5px solid #313244; border-radius:8px; padding:20px;">
            <p style="font-size:11px; color:#6c7086; letter-spacing:0.06em; text-transform:uppercase; margin:0 0 8px;">Sin stock</p>
            <p style="font-size:36px; font-weight:500; color:#f38ba8; margin:0;" id="cnt-agotados">0</p>
            <p style="font-size:11px; color:#6c7086; margin:8px 0 0;">tallas agotadas</p>
        </div>

    </div>

    {{-- GRÁFICAS --}}
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:24px;">

        <div style="background:#1e1e2e; border:0.5px solid #313244; border-radius:8px; padding:20px;">
            <p style="font-size:11px; color:#6c7086; letter-spacing:0.06em; text-transform:uppercase; margin:0 0 16px;">Productos por categoría</p>
            <canvas id="chart-dona" height="200"></canvas>
        </div>

        <div style="background:#1e1e2e; border:0.5px solid #313244; border-radius:8px; padding:20px;">
            <p style="font-size:11px; color:#6c7086; letter-spacing:0.06em; text-transform:uppercase; margin:0 0 16px;">Distribución por tipo</p>
            <canvas id="chart-barras" height="200"></canvas>
        </div>

    </div>

    {{-- PRODUCTOS RECIENTES --}}
    <div style="background:#1e1e2e; border:0.5px solid #313244; border-radius:8px; overflow:hidden;">
        <div style="padding:16px 20px; border-bottom:0.5px solid #313244;">
            <p style="font-size:11px; color:#6c7086; letter-spacing:0.06em; text-transform:uppercase; margin:0;">Productos recientes</p>
        </div>
        <table style="width:100%; border-collapse:collapse; font-size:13px;">
            <thead>
                <tr style="border-bottom:0.5px solid #313244;">
                    <th style="padding:10px 20px; text-align:left; color:#6c7086; font-weight:400; width:60px;"></th>
                    <th style="padding:10px 20px; text-align:left; color:#6c7086; font-weight:400;">Nombre</th>
                    <th style="padding:10px 20px; text-align:left; color:#6c7086; font-weight:400;">Categoría</th>
                    <th style="padding:10px 20px; text-align:left; color:#6c7086; font-weight:400;">Precio</th>
                    <th style="padding:10px 20px; text-align:left; color:#6c7086; font-weight:400;">Tipo</th>
                    <th style="padding:10px 20px; text-align:left; color:#6c7086; font-weight:400;"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($productosRecientes as $producto)
                <tr style="border-bottom:0.5px solid #313244;">
                    <td style="padding:10px 20px;">
                        <div style="width:44px; height:44px; background:#313244; border-radius:4px; overflow:hidden; flex-shrink:0;">
                            @if($producto->imagen)
                                <img src="{{ Storage::url($producto->imagen) }}" style="width:100%; height:100%; object-fit:cover;">
                            @endif
                        </div>
                    </td>
                    <td style="padding:10px 20px; color:#cdd6f4; font-weight:500;">{{ $producto->nombre }}</td>
                    <td style="padding:10px 20px; color:#a6adc8;">{{ $producto->categoria->nombre ?? '—' }}</td>
                    <td style="padding:10px 20px; color:#cdd6f4;">${{ number_format($producto->precio, 0, ',', '.') }}</td>
                    <td style="padding:10px 20px;">
                        @if($producto->tipo === 'alquiler')
                            <span style="background:#45475a; color:#fab387; font-size:11px; padding:2px 8px; border-radius:4px;">alquiler</span>
                        @else
                            <span style="background:#313244; color:#a6adc8; font-size:11px; padding:2px 8px; border-radius:4px;">venta</span>
                        @endif
                    </td>
                    <td style="padding:10px 20px;">
                        <a href="{{ route('admin.productos.edit', $producto) }}" style="font-size:11px; color:#cba6f7; text-decoration:none;">Editar</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    function animarContador(id, fin, duracion) {
        const el = document.getElementById(id);
        if (fin === 0) { el.textContent = 0; return; }
        let inicio = 0;
        const paso = Math.max(10, duracion / fin);
        const timer = setInterval(() => {
            inicio++;
            el.textContent = inicio;
            if (inicio >= fin) clearInterval(timer);
        }, paso);
    }

    animarContador('cnt-productos',  {{ $totalProductos }},  800);
    animarContador('cnt-categorias', {{ $totalCategorias }}, 600);
    animarContador('cnt-agotados',   {{ $agotados }},        400);

    new Chart(document.getElementById('chart-dona'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($porCategoria->pluck('nombre')) !!},
            datasets: [{
                data: {!! json_encode($porCategoria->pluck('productos_count')) !!},
                backgroundColor: ['#cba6f7','#f38ba8','#89b4fa','#a6e3a1','#f9e2af','#fab387'],
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { color: '#a6adc8', font: { size: 11 }, padding: 12, boxWidth: 10 }
                }
            },
            cutout: '65%'
        }
    });

    new Chart(document.getElementById('chart-barras'), {
        type: 'bar',
        data: {
            labels: ['Venta', 'Alquiler'],
            datasets: [{
                data: [{{ $totalVenta }}, {{ $totalAlquier }}],
                backgroundColor: ['#cba6f7', '#fab387'],
                borderRadius: 4,
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                x: { ticks: { color: '#6c7086' }, grid: { display: false } },
                y: { ticks: { color: '#6c7086', stepSize: 1 }, grid: { color: '#313244' } }
            }
        }
    });
    </script>

</x-layouts.admin>