<x-layouts.admin>
    <div style="max-width:600px; margin:0 auto; background:#1e1e2e; border:0.5px solid #313244; border-radius:8px; padding:24px;">
        <h1 style="font-size:20px; font-weight:500; color:#cdd6f4; margin-bottom:16px;">Editar producto</h1>

        <form action="{{ route('admin.productos.update', $producto) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div style="margin-bottom:16px;">
                <label style="display:block; font-size:13px; color:#6c7086; margin-bottom:4px;">Nombre</label>
                <input type="text" name="nombre" value="{{ $producto->nombre }}" required style="width:100%; padding:8px 12px; border-radius:4px; border:0.5px solid #313244; background:#313244; color:#cdd6f4;">
            </div>

            <div style="margin-bottom:16px;">
                <label style="display:block; font-size:13px; color:#6c7086; margin-bottom:4px;">Descripción</label>
                <textarea name="descripcion" rows="3" style="width:100%; padding:8px 12px; border-radius:4px; border:0.5px solid #313244; background:#313244; color:#cdd6f4;">{{ $producto->descripcion }}</textarea>
            </div>

            <div style="margin-bottom:16px;">
                <label style="display:block; font-size:13px; color:#6c7086; margin-bottom:4px;">Precio</label>
                <input type="number" name="precio" step="0.01" min="0" value="{{ $producto->precio }}" required style="width:100%; padding:8px 12px; border-radius:4px; border:0.5px solid #313244; background:#313244; color:#cdd6f4;">
            </div>

            <div style="margin-bottom:16px;">
                <label style="display:block; font-size:13px; color:#6c7086; margin-bottom:4px;">Imagen</label>
                <input type="file" name="imagen" accept="image/*" style="width:100%; padding:8px 12px; border-radius:4px; border:0.5px solid #313244; background:#313244; color:#cdd6f4;">
               @if($producto->imagen)
            <div style="margin-top:8px;">
                <img src="{{ Storage::url($producto->imagen) }}" 
             style="height:80px; width:80px; object-fit:cover; border-radius:4px; border:0.5px solid #313244;">
                <p style="font-size:11px; color:#6c7086; margin-top:4px;">Imagen actual</p>
            </div>
                @endif
            </div>

            <div style="margin-bottom:16px;">
                <label style="display:block; font-size:13px; color:#6c7086; margin-bottom:4px;">Tipo</label>
                <select name="tipo" required style="width:100%; padding:8px 12px; border-radius:4px; border:0.5px solid #313244; background:#313244; color:#cdd6f4;">
                    <option value="venta" {{ $producto->tipo == 'venta' ? 'selected' : '' }}>Venta</option>
                    <option value="alquiler" {{ $producto->tipo == 'alquiler' ? 'selected' : '' }}>Alquiler</option>
                </select>
            </div>

            <div style="margin-bottom:16px;">
                <label style="display:block; font-size:13px; color:#6c7086; margin-bottom:4px;">Género</label>
                <select name="genero" required style="width:100%; padding:8px 12px; border-radius:4px; border:0.5px solid #313244; background:#313244; color:#cdd6f4;">
                    <option value="unisex" {{ $producto->genero == 'unisex' ? 'selected' : '' }}>Unisex</option>
                    <option value="hombre" {{ $producto->genero == 'hombre' ? 'selected' : '' }}>Hombre</option>
                    <option value="mujer" {{ $producto->genero == 'mujer' ? 'selected' : '' }}>Mujer</option>
                </select>
            </div>

            <div style="margin-bottom:16px;">
                <label style="display:block; font-size:13px; color:#6c7086; margin-bottom:4px;">Categoría</label>
                <select name="categoria_id" required style="width:100%; padding:8px 12px; border-radius:4px; border:0.5px solid #313244; background:#313244; color:#cdd6f4;">
                    <option value="">Seleccionar categoría</option>
                    @foreach($categorias as $categoria)
                        <option value="{{ $categoria->id }}" {{ $producto->categoria_id == $categoria->id ? 'selected' : '' }}>
                            {{ $categoria->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div style="margin-bottom:16px; display:flex; align-items:center; gap:8px;">
           <input type="checkbox" name="destacado" id="destacado" value="1"
           {{ $producto->destacado ? 'checked' : '' }}
           style="width:16px; height:16px; cursor:pointer;">
             <label for="destacado" style="font-size:13px; color:#6c7086; cursor:pointer;">
                      Mostrar en destacados del inicio
            </label>
                </div>

            <button type="submit" style="background:#cba6f7; color:#1e1e2e; padding:8px 16px; border-radius:6px; font-size:13px; font-weight:500; border:none; cursor:pointer;">
                Actualizar producto
            </button>
            <a href="{{ route('admin.productos.index') }}" 
                onclick="return confirm('¿Estás seguro? Los cambios no guardados se perderán.')"
                style="display:inline-block; background:#313244; color:#cdd6f4; padding:8px 16px; border-radius:6px; font-size:13px; font-weight:500; text-decoration:none; margin-right:8px;">
                ← Volver
                    </a>
        </form>

        {{-- SECCIÓN DE TALLAS --}}
        <div style="margin-top:32px; border-top:0.5px solid #313244; padding-top:24px;">
            <h2 style="font-size:16px; font-weight:500; color:#cdd6f4; margin-bottom:16px;">Tallas disponibles</h2>

            @foreach($producto->tallas as $talla)
                <div style="display:flex; align-items:center; gap:8px; margin-bottom:8px;">
                    <span style="color:#cdd6f4; font-size:13px; background:#313244; padding:6px 12px; border-radius:4px;">
                        {{ $talla->talla }} — Stock: {{ $talla->stock }}
                    </span>
                    <form action="{{ route('admin.tallas.destroy', $talla) }}" method="POST" class="delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="background:#f38ba8; color:#1e1e2e; border:none; padding:6px 12px; border-radius:4px; font-size:12px; cursor:pointer;">
                            Eliminar
                        </button>
                    </form>
                </div>
            @endforeach

            <form action="{{ route('admin.tallas.store', $producto) }}" method="POST" style="display:flex; gap:8px; margin-top:16px;">
                @csrf
                <input type="text" name="talla" placeholder="Ej: S, M, L, XL" required style="padding:8px 12px; border-radius:4px; border:0.5px solid #313244; background:#313244; color:#cdd6f4;">
                <input type="number" name="stock" placeholder="Stock" min="0" required style="padding:8px 12px; border-radius:4px; border:0.5px solid #313244; background:#313244; color:#cdd6f4; width:100px;">
                <button type="submit" style="background:#cba6f7; color:#1e1e2e; padding:8px 16px; border-radius:6px; font-size:13px; font-weight:500; border:none; cursor:pointer;">
                    + Agregar talla
                </button>
            </form>
        </div>

    </div>
</x-layouts.admin>