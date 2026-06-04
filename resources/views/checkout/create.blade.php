<x-layouts.tienda>
    <div style="max-width:900px; margin:0 auto; padding:24px;">
        
        <h1 style="font-size:20px; font-weight:500; letter-spacing:0.06em; text-transform:uppercase; color:#111; margin-bottom:24px;">
            Finalizar compra
        </h1>

        <div style="display:flex; flex-wrap:wrap; gap:40px;">

            {{-- RESUMEN DEL PRODUCTO --}}
            <div style="flex:1 1 300px;">
                <div style="height:320px; background:#e8e6e2; position:relative; overflow:hidden; margin-bottom:16px;">
                    @if($producto->imagen)
                        <img src="{{ Storage::url($producto->imagen) }}" 
                             style="width:100%; height:100%; object-fit:cover; position:absolute; top:0; left:0;">
                    @endif
                </div>

                <h2 style="font-size:18px; font-weight:500; letter-spacing:0.06em; text-transform:uppercase; color:#111; margin:0 0 8px;">
                    {{ $producto->nombre }}
                </h2>
                <p style="font-size:16px; color:#111; margin:0 0 4px;">
                    ${{ number_format($producto->precio, 0, ',', '.') }}
                </p>
                <p style="font-size:12px; color:#666; margin:0;">
                    {{ $producto->categoria->nombre ?? 'Sin categoría' }}
                </p>
            </div>

            {{-- FORMULARIO --}}
            <div style="flex:1 1 300px;">
                <form action="{{ route('checkout.store', $producto) }}" method="POST">
                    @csrf

                    <input type="hidden" name="producto_id" value="{{ $producto->id }}">

                    <div style="margin-bottom:16px;">
                        <label style="display:block; font-size:11px; letter-spacing:0.08em; text-transform:uppercase; color:#666; margin-bottom:6px;">Nombre completo</label>
                        <input type="text" name="nombre" required
                               style="width:100%; padding:12px; border:1px solid #e5e5e5; background:#fff; color:#111; font-size:14px; box-sizing:border-box;">
                    </div>

                    <div style="margin-bottom:16px;">
                        <label style="display:block; font-size:11px; letter-spacing:0.08em; text-transform:uppercase; color:#666; margin-bottom:6px;">Celular</label>
                        <input type="text" name="celular" required
                               style="width:100%; padding:12px; border:1px solid #e5e5e5; background:#fff; color:#111; font-size:14px; box-sizing:border-box;">
                    </div>

                    <div style="margin-bottom:16px;">
                        <label style="display:block; font-size:11px; letter-spacing:0.08em; text-transform:uppercase; color:#666; margin-bottom:6px;">Correo electrónico</label>
                        <input type="email" name="correo" required
                               style="width:100%; padding:12px; border:1px solid #e5e5e5; background:#fff; color:#111; font-size:14px; box-sizing:border-box;">
                    </div>

                    <div style="margin-bottom:16px;">
                        <label style="display:block; font-size:11px; letter-spacing:0.08em; text-transform:uppercase; color:#666; margin-bottom:6px;">Dirección</label>
                        <input type="text" name="direccion" required
                               style="width:100%; padding:12px; border:1px solid #e5e5e5; background:#fff; color:#111; font-size:14px; box-sizing:border-box;">
                    </div>

                    <div style="margin-bottom:16px;">
                        <label style="display:block; font-size:11px; letter-spacing:0.08em; text-transform:uppercase; color:#666; margin-bottom:6px;">Ciudad</label>
                        <input type="text" name="ciudad" required
                               style="width:100%; padding:12px; border:1px solid #e5e5e5; background:#fff; color:#111; font-size:14px; box-sizing:border-box;">
                    </div>

                    <div style="margin-bottom:16px;">
                        <label style="display:block; font-size:11px; letter-spacing:0.08em; text-transform:uppercase; color:#666; margin-bottom:6px;">Talla seleccionada</label>
                        <input type="text" name="talla" value="{{ $talla ?? '' }}" placeholder="Ej: S, M, L"
                               style="width:100%; padding:12px; border:1px solid #e5e5e5; background:#fff; color:#111; font-size:14px; box-sizing:border-box;">
                    </div>

                    <div style="margin-bottom:24px;">
                        <label style="display:block; font-size:11px; letter-spacing:0.08em; text-transform:uppercase; color:#666; margin-bottom:6px;">Cantidad</label>
                        <input type="number" name="cantidad" value="1" min="1" required
                               style="width:100%; padding:12px; border:1px solid #e5e5e5; background:#fff; color:#111; font-size:14px; box-sizing:border-box;">
                    </div>

                    <button type="submit" style="width:100%; background:#111; color:#fff; padding:14px 32px; border:none; cursor:pointer; font-size:12px; letter-spacing:0.1em; text-transform:uppercase;">
                        CONFIRMAR PEDIDO
                    </button>
                </form>
            </div>

        </div>
    </div>
</x-layouts.tienda>