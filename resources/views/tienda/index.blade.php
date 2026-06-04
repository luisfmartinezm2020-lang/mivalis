<x-layouts.tienda>

    {{-- HERO --}}
    <section style="background:#f0efed; padding:80px 24px; text-align:center;">
        <h1 style="font-size:32px; font-weight:500; letter-spacing:0.1em; text-transform:uppercase; color:#111; margin:0 0 16px;">
            TENEMOS LO QUE BUSCAS
        </h1>
        <p style="font-size:14px; color:#666; max-width:500px; margin:0 auto 24px;">
            Venta y alquiler de disfraces, trajes típicos y ropa de baile. Toda la cultura colombiana en un solo lugar.
        </p>
        <a href="{{ route('catalogo') }}" style="background:#111; color:#fff; padding:12px 32px; text-decoration:none; font-size:12px; letter-spacing:0.1em; text-transform:uppercase;">
            VER CATÁLOGO
        </a>
    </section>
  

    {{-- CATEGORÍAS --}}
   <section style="padding:40px 24px;">
    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:4px;">
        @foreach($categorias as $categoria)
            <a href="{{ route('catalogo', ['categoria' => $categoria->id]) }}" 
            style="display:block; height:200px; background:#ccc; position:relative; text-decoration:none; overflow:hidden;">
    
     @if($categoria->imagen)
      <img src="{{ Storage::url($categoria->imagen) }}" 
         style="width:100%; height:100%; object-fit:cover; position:absolute; top:0; left:0;">
             @endif

     <div style="position:absolute; bottom:0; left:0; right:0; padding:16px; background:rgba(0,0,0,0.4);">
        <span style="color:#fff; font-size:12px; font-weight:500; letter-spacing:0.1em; text-transform:uppercase;">
            {{ $categoria->nombre }}
        </span>
            </div>
    </a>
        @endforeach
    </div>
</section>

    {{-- DESTACADOS --}}
    <section style="padding:40px 24px;">
        <h2 style="font-size:20px; font-weight:500; color:#111; margin-bottom:24px;">Destacados</h2>
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:16px;">
            @foreach($productos as $producto)
                <a href="{{ route('producto', $producto) }}" style="display:block; background:#ccc; height:300px; position:relative; text-decoration:none; overflow:hidden;">
                    @if($producto->imagen)
                    <img src="{{ Storage::url($producto->imagen) }}" style="width:100%; height:100%; object-fit:cover; position:absolute; top:0; left:0;">
                    @endif
                    <div style="position:absolute; bottom:0; left:0; right:0; padding:16px; background:rgba(0,0,0,0.4);">
                        <span style="color:#fff; font-size:12px; font-weight:500;">
                            {{ $producto->nombre }}
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
    </section>
<footer style="background:#111; color:#fff; padding:40px 24px; margin-top:40px;">
    
    {{-- REDES SOCIALES CENTRADAS ARRIBA --}}
    <div style="text-align:center; margin-bottom:32px; width:100%;">
        <img src="/img/logo.jpg" style="height:80px; margin-bottom:16px; display:block; margin-left:auto; margin-right:auto;">
        <div style="display:flex;justify-content:center; gap:20px;">
            <a href="https://wa.me/573044229882" style="color:#fff; font-size:20px;"><i class="fab fa-whatsapp"></i></a>
            <a href="https://www.instagram.com/crisdisfraces?igsh=ZzZrbnB1NmNjNjQ2" style="color:#fff; font-size:20px;"><i class="fab fa-instagram"></i></a>
            <a href="https://www.google.com/maps/place/MiValis/@10.644273,-75.062345,15z/data=!4m2!3m1!1s0x8ef42d8c9d5f8f8f:0x8f8f8f8f8f8f8f8f" style="color:#fff; font-size:20px;"><i class="fab fa-ubicacion"></i></a>


        </div>
    </div>

    {{-- COLUMNAS --}}
    <div style="max-width:900px; margin:0 auto; display:flex; flex-wrap:wrap; gap:32px; justify-content:space-between;">
        
        <div>
            <p style="font-size:12px; font-weight:500; letter-spacing:0.08em; margin:0 0 12px;">SOBRE NOSOTROS</p>
            <p style="font-size:12px; color:#aaa; margin:0;">Venta y alquiler de disfraces</p>
            <p style="font-size:12px; color:#aaa; margin:4px 0 0;">y trajes típicos colombianos</p>
        </div>

        <div>
            <p style="font-size:12px; color:#aaa; margin:0;"><i class="fab fa-whatsapp"></i> WhatsApp: +57 3044229882</p>
            <p style="font-size:12px; color:#aaa; margin:4px 0 0;"><i class="fas fa-phone"></i> Teléfono: +57 3044229882</p>
            <p style="font-size:12px; color:#aaa; margin:4px 0 0;"><i class="fas fa-envelope"></i> mivalis@gmail.com</p>
            <p style="font-size:12px; color:#aaa; margin:4px 0 0;"><i class="fas fa-map-marker-alt"></i> Cl. 32 #2-13, Montería, Córdoba</p>
            <p style="font-size:12px; color:#aaa; margin:4px 0 0;"><i class="fas fa-clock"></i> Lun-Vie: 9am - 6pm</p>
            <p style="font-size:12px; color:#aaa; margin:4px 0 0;"><i class="fas fa-clock"></i> Sáb: 10am - 2pm</p>
        </div>

        <div>
            <p style="font-size:12px; font-weight:500; letter-spacing:0.08em; margin:0 0 12px;">LINKS</p>
            <a href="{{ route('inicio') }}" style="display:block; font-size:12px; color:#aaa; text-decoration:none; margin-bottom:4px;">Inicio</a>
            <a href="{{ route('catalogo') }}" style="display:block; font-size:12px; color:#aaa; text-decoration:none;">Catálogo</a>
        </div>

    </div>

    <div style="max-width:900px; margin:24px auto 0; border-top:0.5px solid #333; padding-top:16px; text-align:center;">
        <p style="font-size:11px; color:#666; margin:0;">© {{ date('Y') }} MiValis — Todos los derechos reservados</p>
    </div>

</footer>

</x-layouts.tienda>