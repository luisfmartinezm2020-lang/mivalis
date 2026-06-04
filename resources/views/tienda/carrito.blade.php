<x-layouts.tienda>
    <div style="max-width:900px; margin:0 auto; padding:24px;">

        <h1 style="font-size:20px; font-weight:500; letter-spacing:0.06em; text-transform:uppercase; color:#111; margin-bottom:24px;">
            Mi carrito
        </h1>

        @if(count($carrito) > 0)

            {{-- LISTA DE PRODUCTOS --}}
            @foreach($carrito as $clave => $item)
                <div style="display:flex; gap:16px; align-items:center; padding:16px 0; border-bottom:0.5px solid #e5e5e5;">
                    
                    {{-- IMAGEN --}}
                    <div style="width:80px; height:80px; background:#e8e6e2; position:relative; overflow:hidden; flex-shrink:0;">
                        @if($item['imagen'])
                            <img src="{{ Storage::url($item['imagen']) }}" style="width:100%; height:100%; object-fit:cover;">
                        @endif
                    </div>

                    {{-- DATOS --}}
                    <div style="flex:1;">
                        <p style="font-size:13px; font-weight:500; text-transform:uppercase; letter-spacing:0.04em; margin:0 0 4px;">
                            {{ $item['nombre'] }}
                        </p>
                        <p style="font-size:12px; color:#666; margin:0 0 4px;">
                            Talla: {{ $item['talla'] }}
                        </p>
                        <p style="font-size:12px; color:#666; margin:0;">
                            Cantidad: {{ $item['cantidad'] }}
                        </p>
                    </div>

                    {{-- PRECIO --}}
                    <div style="text-align:right;">
                        <p style="font-size:13px; font-weight:500; margin:0 0 8px;">
                            ${{ number_format($item['precio'] * $item['cantidad'], 0, ',', '.') }}
                        </p>
                        <form action="{{ route('carrito.eliminar', $clave) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="font-size:11px; color:#999; background:none; border:none; cursor:pointer; text-decoration:underline;">
                                Eliminar
                            </button>
                        </form>
                    </div>

                </div>
            @endforeach

            {{-- TOTAL --}}
            <div style="display:flex; justify-content:space-between; align-items:center; padding:24px 0; border-top:1px solid #111; margin-top:8px;">
                <span style="font-size:14px; font-weight:500; letter-spacing:0.06em; text-transform:uppercase;">TOTAL</span>
                <span style="font-size:18px; font-weight:500;">${{ number_format($total, 0, ',', '.') }}</span>
            </div>

            {{-- BOTONES --}}
            <div style="display:flex; gap:12px; justify-content:space-between;">
                <form action="{{ route('carrito.vaciar') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="font-size:11px; color:#999; background:none; border:none; cursor:pointer; text-decoration:underline; letter-spacing:0.06em; text-transform:uppercase;">
                        Vaciar carrito
                    </button>
                </form>

                <a href="{{ route('carrito.checkout') }}" style="background:#111; color:#fff; padding:14px 32px; text-decoration:none; font-size:12px; letter-spacing:0.1em; text-transform:uppercase;">
                    IR A PAGAR
                </a>
            </div>

        @else
            {{-- CARRITO VACÍO --}}
            <div style="text-align:center; padding:60px 0;">
                <p style="font-size:14px; color:#666; margin-bottom:24px;">Tu carrito está vacío</p>
                <a href="{{ route('catalogo') }}" style="background:#111; color:#fff; padding:12px 32px; text-decoration:none; font-size:12px; letter-spacing:0.1em; text-transform:uppercase;">
                    VER CATÁLOGO
                </a>
            </div>
        @endif

    </div>
</x-layouts.tienda>