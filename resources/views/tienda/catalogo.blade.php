<x-layouts.tienda>

    {{-- BARRA DE CATEGORÍAS --}}
    <div style="background:#fff; border-bottom:0.5px solid #e5e5e5; padding:0 24px; display:flex; gap:24px; overflow-x:auto;">
        <a href="{{ route('catalogo') }}"
           style="font-size:11px; font-weight:500; letter-spacing:0.08em; text-transform:uppercase; color:#111; text-decoration:none; padding:16px 0; white-space:nowrap; border-bottom:{{ !request('categoria') ? '2px solid #111' : 'none' }};">
            TODOS
        </a>
        @foreach($categorias as $categoria)
            <a href="{{ route('catalogo', ['categoria' => $categoria->id]) }}"
               style="font-size:11px; font-weight:500; letter-spacing:0.08em; text-transform:uppercase; color:#111; text-decoration:none; padding:16px 0; white-space:nowrap; border-bottom:{{ request('categoria') == $categoria->id ? '2px solid #111' : 'none' }};">
                {{ $categoria->nombre }}
            </a>
        @endforeach
    </div>

    {{-- GRID DE PRODUCTOS --}}
    <div style="padding:24px; display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:4px;">
        @foreach($productos as $producto)
            <a href="{{ route('producto', $producto) }}" style="display:block; text-decoration:none; color:#111;">

                <div style="height:320px; background:#e8e6e2; position:relative; overflow:hidden;">
                    @if($producto->imagen)
                        <img src="{{ Storage::url($producto->imagen) }}"
                             style="width:100%; height:100%; object-fit:cover; position:absolute; top:0; left:0; transition:transform 0.4s ease;"
                             onmouseover="this.style.transform='scale(1.08)'"
                             onmouseout="this.style.transform='scale(1)'">
                    @else
                        <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="none" viewBox="0 0 24 24" stroke="#bbb">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    @endif
                </div>

                <div style="padding:8px 4px;">
                    <p style="font-size:12px; font-weight:500; letter-spacing:0.04em; text-transform:uppercase; margin:0 0 4px;">
                        {{ $producto->nombre }}
                    </p>
                    <p style="font-size:12px; color:#444; margin:0;">
                        ${{ number_format($producto->precio, 0, ',', '.') }}
                    </p>
                </div>

            </a>
        @endforeach
    </div>

    {{-- PAGINACIÓN --}}
    @if($productos->hasPages())
        <div style="padding:24px; display:flex; justify-content:center;">
            {{ $productos->links() }}
        </div>
    @endif

</x-layouts.tienda>
