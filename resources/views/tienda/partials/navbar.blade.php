<nav style="background:#fff; border-bottom:0.5px solid #e5e5e5; display:flex; align-items:center; justify-content:space-between; padding:0 24px; height:48px;">
    
    <a href="{{ route('inicio') }}" style="font-size:14px; font-weight:500; letter-spacing:0.1em; color:#111; text-decoration:none;">
        MIVALIS
    </a>

    <div style="display:flex; gap:24px;">
        <a href="{{ route('inicio') }}" style="font-size:11px; letter-spacing:0.08em; color:#111; text-decoration:none; text-transform:uppercase;">Inicio</a>
        <a href="{{ route('catalogo') }}" style="font-size:11px; letter-spacing:0.08em; color:#111; text-decoration:none; text-transform:uppercase;">Catálogo</a>
    </div>

    <div style="display:flex; gap:16px; align-items:center;">

        {{-- CARRITO --}}
        @php $cantidadCarrito = count(session()->get('carrito', [])); @endphp
        <a href="{{ route('carrito.index') }}" style="font-size:11px; letter-spacing:0.08em; color:#111; text-decoration:none; text-transform:uppercase; position:relative;">
            <i class="fas fa-shopping-bag"></i>
            @if($cantidadCarrito > 0)
                <span style="position:absolute; top:-8px; right:-8px; background:#111; color:#fff; font-size:9px; width:16px; height:16px; border-radius:50%; display:flex; align-items:center; justify-content:center;">
                    {{ $cantidadCarrito }}
                </span>
            @endif
        </a>

        @auth
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('admin.categorias.index') }}" style="font-size:11px; letter-spacing:0.08em; color:#111; text-decoration:none; text-transform:uppercase;">Panel</a>
            @endif
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" style="font-size:11px; letter-spacing:0.08em; color:#111; background:none; border:none; cursor:pointer; text-transform:uppercase;">Salir</button>
            </form>
        @else
            <a href="{{ route('login') }}" style="font-size:11px; letter-spacing:0.08em; color:#111; text-decoration:none; text-transform:uppercase;">Iniciar sesión</a>
        @endauth

    </div>
    <span id="carrito-contador" style="...">{{ $cantidadCarrito }}</span>

</nav>