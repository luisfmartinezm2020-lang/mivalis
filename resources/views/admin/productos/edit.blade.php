<x-layouts.admin>
    <div style="max-width:600px; margin:0 auto; background:#1e1e2e; border:0.5px solid #313244; border-radius:8px; padding:24px;">
        <h1 style="font-size:20px; font-weight:500; color:#cdd6f4; margin-bottom:16px;">Editar producto</h1>

        <form action="{{ route('admin.productos.update', $producto) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div style="margin-bottom:16px;">
                <label for="nombre" style="display:block; font-size:13px; color:#6c7086; margin-bottom:4px;">Nombre</label>
                <input type="text" name="nombre" id="nombre" value="{{ $producto->nombre }}" required style="width:100%; padding:8px 12px; border-radius:4px; border:0.5px solid #313244; background:#313244; color:#cdd6f4;">
            </div>

            <div style="margin-bottom:16px;">
                <label for="descripcion" style="display:block; font-size:13px; color:#6c7086; margin-bottom:4px;">Descripción</label>
                <textarea name="descripcion" id="descripcion" rows="3" style="width:100%; padding:8px 12px; border-radius:4px; border:0.5px solid #313244; background:#313244; color:#cdd6f4;">{{ $producto->descripcion }}</textarea>
            </div>

            <div style="margin-bottom:16px;">
                <label for="precio" style="display:block; font-size:13px; color:#6c7086; margin-bottom:4px;">Precio</label>
                <input type="number" name="precio" id="precio" step="0.01" min="0" value="{{ $producto->precio }}" required style="width:100%; padding:8px 12px; border-radius:4px; border:0.5px solid #313244; background:#313244; color:#cdd6f4;">
            </div>

            <div style="margin-bottom:16px;">
                <label for="stock" style="display:block; font-size:13px; color:#6c7086; margin-bottom:4px;">Stock</label>
                <input type="number" name="stock" id="stock" min="0" value="{{ $producto->stock }}" required style="width:100%; padding:8px 12px; border-radius:4px; border:0.5px solid #313244; background:#313244; color:#cdd6f4;">
            </div>

            <div style="margin-bottom:16px;">
                <label for="imagen" style="display:block; font-size:13px; color:#6c7086; margin-bottom:4px;">Imagen</label>
                <input type="file" name="imagen" id="imagen" accept="image/*" style="width:100%; padding:8px 12px; border-radius:4px; border:0.5px solid #313244; background:#313244; color:#cdd6f4;">
                @if($producto->imagen)
                    <p style="font-size:11px; color:#6c7086; margin-top:4px;">Imagen actual: {{ $producto->imagen }}</p>
                @endif
            </div>

            <div style="margin-bottom:16px;">
                <label for="tipo" style="display:block; font-size:13px; color:#6c7086; margin-bottom:4px;">Tipo</label>
                <select name="tipo" id="tipo" required style="width:100%; padding:8px 12px; border-radius:4px; border:0.5px solid #313244; background:#313244; color:#cdd6f4;">
                    <option value="venta" {{ $producto->tipo == 'venta' ? 'selected' : '' }}>Venta</option>
                    <option value="alquiler" {{ $producto->tipo == 'alquiler' ? 'selected' : '' }}>Alquiler</option>
                </select>
            </div>

            <div style="margin-bottom:16px;">
                <label for="genero" style="display:block; font-size:13px; color:#6c7086; margin-bottom:4px;">Género</label>
                <select name="genero" id="genero" required style="width:100%; padding:8px 12px; border-radius:4px; border:0.5px solid #313244; background:#313244; color:#cdd6f4;">
                    <option value="unisex" {{ $producto->genero == 'unisex' ? 'selected' : '' }}>Unisex</option>
                    <option value="hombre" {{ $producto->genero == 'hombre' ? 'selected' : '' }}>Hombre</option>
                    <option value="mujer" {{ $producto->genero == 'mujer' ? 'selected' : '' }}>Mujer</option>
                </select>
            </div>

            <div style="margin-bottom:16px;">
                <label for="categoria_id" style="display:block; font-size:13px; color:#6c7086; margin-bottom:4px;">Categoría</label>
                <select name="categoria_id" id="categoria_id" required style="width:100%; padding:8px 12px; border-radius:4px; border:0.5px solid #313244; background:#313244; color:#cdd6f4;">
                    <option value="">Seleccionar categoría</option>
                    @foreach($categorias as $categoria)
                        <option value="{{ $categoria->id }}" {{ $producto->categoria_id == $categoria->id ? 'selected' : '' }}>
                            {{ $categoria->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" style="background:#cba6f7; color:#1e1e2e; padding:8px 16px; border-radius:6px; font-size:13px; font-weight:500;">
                Actualizar producto
            </button>
        </form>
    </div>
</x-layouts.admin>