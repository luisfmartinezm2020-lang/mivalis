<nav style="background:#fff; border-bottom:0.5px solid #e5e5e5; display:flex; align-items:center; justify-content:space-between; padding:0 24px; height:48px;">
    
    <a href="{{ route('inicio') }}" style="font-size:14px; font-weight:500; letter-spacing:0.1em; color:#111; text-decoration:none;">
        MIVALIS
    </a>

    <div style="display:flex; gap:24px;">
        <a href="{{ route('inicio') }}" style="font-size:11px; letter-spacing:0.08em; color:#111; text-decoration:none; text-transform:uppercase;">Inicio</a>
        <a href="{{ route('catalogo') }}" style="font-size:11px; letter-spacing:0.08em; color:#111; text-decoration:none; text-transform:uppercase;">Catálogo</a>
    </div>

    <div style="display:flex; gap:16px; align-items:center;">
        @auth
            <a href="{{ route('dashboard') }}" style="font-size:11px; letter-spacing:0.08em; color:#111; text-decoration:none; text-transform:uppercase;">Panel</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" style="font-size:11px; letter-spacing:0.08em; color:#111; background:none; border:none; cursor:pointer; text-transform:uppercase;">Salir</button>
            </form>
        @else
            <a href="{{ route('login') }}" style="font-size:11px; letter-spacing:0.08em; color:#111; text-decoration:none; text-transform:uppercase;">Iniciar sesión</a>
        @endauth
    </div>

</nav>