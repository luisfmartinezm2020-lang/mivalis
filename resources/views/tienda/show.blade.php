{{-- LIGHTBOX --}}
<div id="lightbox" onclick="cerrarLightbox()"
     style="display:none; position:fixed; top:0; left:0; width:100%; height:100%;
            background:rgba(0,0,0,0.9); z-index:9999; cursor:zoom-out;
            align-items:center; justify-content:center;">
    <img id="lightbox-img" src="" style="max-width:90%; max-height:90%; object-fit:contain;">
</div>

<script>
function abrirLightbox(src) {
    document.getElementById('lightbox-img').src = src;
    document.getElementById('lightbox').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}
function cerrarLightbox() {
    document.getElementById('lightbox').style.display = 'none';
    document.body.style.overflow = '';
}
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') cerrarLightbox();
});
</script>

<x-layouts.tienda>
    <div style="max-width:900px; margin:0 auto; padding:24px;">

        <a href="{{ route('catalogo') }}" style="display:inline-block; margin-bottom:24px; font-size:11px; color:#111; text-decoration:none; letter-spacing:0.05em;">
            &larr; VOLVER AL CATÁLOGO
        </a>

        <div style="display:flex; flex-wrap:wrap; gap:40px;">

            {{-- IMAGEN --}}
            <div style="flex:1 1 350px; background:#e8e6e2; position:relative; overflow:hidden; min-height:450px;">
                @if($producto->imagen)
                    <img src="{{ Storage::url($producto->imagen) }}"
                         style="width:100%; height:100%; object-fit:cover; position:absolute; top:0; left:0;
                         transition:transform 0.4s ease;"
                         onmouseover="this.style.transform='scale(1.08)'"
                         onmouseout="this.style.transform='scale(1)'"
                         onclick="abrirLightbox(this.src)">
                @endif
            </div>

            {{-- INFORMACIÓN --}}
            <div style="flex:1 1 300px; display:flex; flex-direction:column; gap:16px;">

                <h1 style="font-size:22px; font-weight:500; letter-spacing:0.06em; text-transform:uppercase; color:#111; margin:0;">
                    {{ $producto->nombre }}
                </h1>

                <p style="font-size:18px; color:#111; margin:0;">
                    ${{ number_format($producto->precio, 0, ',', '.') }}
                </p>

                {{-- TALLAS --}}
                @if($producto->tallas->count() > 0)
                    <div>
                        <p style="font-size:11px; letter-spacing:0.08em; color:#666; margin:0 0 8px;">TALLA</p>
                        <div style="display:flex; gap:8px; flex-wrap:wrap;">
                            @foreach($producto->tallas as $talla)
                                <button
                                    type="button"
                                    class="talla-btn"
                                    {{ $talla->stock == 0 ? 'disabled' : '' }}
                                    style="padding:10px 16px; border:1px solid {{ $talla->stock == 0 ? '#ccc' : '#111' }};
                                    background:#fff; color:#111; font-size:12px; letter-spacing:0.05em;
                                    cursor:{{ $talla->stock == 0 ? 'not-allowed' : 'pointer' }};
                                    opacity:{{ $talla->stock == 0 ? '.4' : '1' }};">
                                    {{ $talla->talla }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- STOCK --}}
                @php $stockTotal = $producto->tallas->sum('stock'); @endphp
                @if($producto->tallas->count() > 0)
                    @if($stockTotal == 0)
                        <span style="color:red; font-size:12px; font-weight:500;">AGOTADO</span>
                    @elseif($stockTotal <= 5)
                        <span style="color:orange; font-size:12px; font-weight:500;">⚠️ Pocas unidades disponibles</span>
                    @else
                        <span style="color:green; font-size:12px; font-weight:500;">✓ Disponible</span>
                    @endif
                @else
                    @if($producto->stock == 0)
                        <span style="color:red; font-size:12px; font-weight:500;">AGOTADO</span>
                    @elseif($producto->stock <= 3)
                        <span style="color:orange; font-size:12px; font-weight:500;">⚠️ Pocas unidades: {{ $producto->stock }} disponibles</span>
                    @else
                        <span style="color:green; font-size:12px; font-weight:500;">✓ Disponible</span>
                    @endif
                @endif

                {{-- DESCRIPCIÓN --}}
                <p style="font-size:14px; color:#666; line-height:1.6; margin:0;">
                    {{ $producto->descripcion }}
                </p>
            {{-- BOTONES --}}
@if($stockTotal > 0 || $producto->stock > 0)
    <div style="display:flex; flex-direction:column; gap:8px; margin-top:8px;">

        {{-- AGREGAR AL CARRITO --}}
        <button type="button"
            onclick="
                const talla = document.querySelector('.talla-btn.selected')?.textContent.trim() || '';
                agregarAlCarrito({{ $producto->id }}, talla, 1);
            "
            style="background:#fff; color:#111; padding:14px 32px; border:1px solid #111; cursor:pointer;
                   font-size:12px; letter-spacing:0.1em; text-transform:uppercase; width:100%;">
            AGREGAR AL CARRITO
        </button>

        {{-- COMPRAR --}}
        <button id="btn-comprar" onclick="comprarWhatsApp()"
            style="background:#111; color:#fff; padding:14px 32px; border:none; cursor:pointer;
                   font-size:12px; letter-spacing:0.1em; text-transform:uppercase; width:100%;">
            {{ $producto->tipo == 'venta' ? 'COMPRAR' : 'ALQUILAR' }}
        </button>

    </div>
@endif  
            </div>
        </div>
    </div>
<script>
    function cambiarCantidad(valor) {
    const input = document.getElementById('cantidad-input');
    const nueva = Math.max(1, parseInt(input.value) + valor);
    input.value = nueva;
}
    function agregarCarrito(btn) {
        const tallaSeleccionada = document.querySelector('.talla-btn.selected');
        if (!tallaSeleccionada && {{ $producto->tallas->count() }} > 0) {
            alert('Por favor selecciona una talla');
            return false;
        }
        document.getElementById('talla-input').value = tallaSeleccionada ? tallaSeleccionada.textContent.trim() : '';
        return true;
    }

    function comprarWhatsApp() {
        @auth
        const tallaSeleccionada = document.querySelector('.talla-btn.selected');
        const talla = tallaSeleccionada ? tallaSeleccionada.textContent.trim() : '';
        const url = "{{ route('checkout.create', $producto) }}" + "?talla=" + talla;
        window.location.href = url;
        @else
            window.location.href = "{{ route('login')}}"
        @endauth
    }

    document.querySelectorAll('.talla-btn').forEach(btn => {
        btn.addEventListener('mouseenter', () => {
            if (!btn.disabled && !btn.classList.contains('selected')) {
                btn.style.background = '#111';
                btn.style.color = '#fff';
            }
        });
        btn.addEventListener('mouseleave', () => {
            if (!btn.classList.contains('selected')) {
                btn.style.background = '#fff';
                btn.style.color = '#111';
            }
        });
        btn.addEventListener('click', () => {
            if (btn.classList.contains('selected')) {
                btn.classList.remove('selected');
                btn.style.background = '#fff';
                btn.style.color = '#111';
            } else {
                document.querySelectorAll('.talla-btn').forEach(b => {
                    b.classList.remove('selected');
                    b.style.background = '#fff';
                    b.style.color = '#111';
                });
                btn.classList.add('selected');
                btn.style.background = '#111';
                btn.style.color = '#fff';
            }
        });
    });
</script>
</x-layouts.tienda>