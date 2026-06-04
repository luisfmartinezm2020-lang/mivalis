<x-layouts.tienda>
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

                @foreach($carrito as $item)
                    <div style="display:flex; gap:12px; margin-bottom:16px; padding-bottom:16px; border-bottom:0.5px solid #e5e5e5;">
                        <div style="width:60px; height:60px; background:#e8e6e2; position:relative; overflow:hidden; flex-shrink:0;">
                            @if($item['imagen'])
                                <img src="{{ Storage::url($item['imagen']) }}" style="width:100%; height:100%; object-fit:cover;">
                            @endif
                        </div>
                        <div>
                            <p style="font-size:12px; font-weight:500; text-transform:uppercase; margin:0 0 4px;">{{ $item['nombre'] }}</p>
                            @if($item['talla'])
                                <p style="font-size:12px; color:#666; margin:0 0 2px;">Talla: {{ $item['talla'] }}</p>
                            @endif
                            <p style="font-size:12px; color:#666; margin:0 0 2px;">Cantidad: {{ $item['cantidad'] }}</p>
                            <p style="font-size:12px; margin:0;">${{ number_format($item['precio'] * $item['cantidad'], 0, ',', '.') }}</p>
                        </div>
                    </div>
                @endforeach

                {{-- TOTAL --}}
                @php $total = array_sum(array_map(fn($item) => $item['precio'] * $item['cantidad'], $carrito)); @endphp
                <div style="display:flex; justify-content:space-between; padding-top:16px; border-top:1px solid #111;">
                    <span style="font-size:13px; font-weight:500; text-transform:uppercase; letter-spacing:0.06em;">Total</span>
                    <span style="font-size:16px; font-weight:500;">${{ number_format($total, 0, ',', '.') }}</span>
                </div>
            </div>

            {{-- FORMULARIO --}}
            <div style="flex:1 1 300px;">
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

                    <div style="margin-bottom:24px;">
                        <label style="display:block; font-size:11px; letter-spacing:0.08em; text-transform:uppercase; color:#666; margin-bottom:6px;">Ciudad</label>
                        <input type="text" name="ciudad" required style="width:100%; padding:12px; border:1px solid #e5e5e5; background:#fff; color:#111; font-size:14px; box-sizing:border-box;">
                    </div>

                    <button type="submit" style="width:100%; background:#111; color:#fff; padding:14px 32px; border:none; cursor:pointer; font-size:12px; letter-spacing:0.1em; text-transform:uppercase;">
                        CONFIRMAR PEDIDO
                    </button>
                </form>
            </div>

        </div>
    </div>
</x-layouts.tienda>