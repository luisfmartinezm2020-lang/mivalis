<x-layouts.admin>
    <div style="max-width:600px; margin:0 auto; background:#1e1e2e; border:0.5px solid #313244; border-radius:8px; padding:24px;">
        <h1 style="font-size:20px; font-weight:500; color:#cdd6f4; margin-bottom:16px;">Crear nuevo producto</h1>

        <form action="{{ route('admin.productos.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div style="margin-bottom:16px;">
                <label for="nombre" style="display:block; font-size:13px; color:#6c7086; margin-bottom:4px;">Nombre del producto</label>
                <input type="text" name="nombre" id="nombre" required style="width:100%; padding:8px 12px; border-radius:4px; border:0.5px solid #313244; background:#313244; color:#cdd6f4;">
            </div>

            <div style="margin-bottom:16px;">
                <label for="descripcion" style="display:block; font-size:13px; color:#6c7086; margin-bottom:4px;">Descripción</label>
                <textarea name="descripcion" id="descripcion" rows="3" style="width:100%; padding:8px 12px; border-radius:4px; border:0.5px solid #313244; background:#313244; color:#cdd6f4;"></textarea>
            </div>

            <div style="margin-bottom:16px;">
                <label for="precio" style="display:block; font-size:13px; color:#6c7086; margin-bottom:4px;">Precio</label>
                <input type="number" name="precio" id="precio" step="0.01" min="0" required style="width:100%; padding:8px 12px; border-radius:4px; border:0.5px solid #313244; background:#313244; color:#cdd6f4;">
            </div>

            <div style="margin-bottom:16px;">
                <label for="imagen" style="display:block; font-size:13px; color:#6c7086; margin-bottom:4px;">Imagen</label>
                <input type="file" name="imagen" id="imagen" accept="image/*" style="width:100%; padding:8px 12px; border-radius:4px; border:0.5px solid #313244; background:#313244; color:#cdd6f4;">
            </div>

            <div style="margin-bottom:16px;">
                <label for="tipo" style="display:block; font-size:13px; color:#6c7086; margin-bottom:4px;">Tipo</label>
                <select name="tipo" id="tipo" required style="width:100%; padding:8px 12px; border-radius:4px; border:0.5px solid #313244; background:#313244; color:#cdd6f4;">
                    <option value="venta">Venta</option>
                    <option value="alquiler">Alquiler</option>
                </select>
            </div>

            <div style="margin-bottom:16px;">
                <label for="genero" style="display:block; font-size:13px; color:#6c7086; margin-bottom:4px;">Género</label>
                <select name="genero" id="genero" required style="width:100%; padding:8px 12px; border-radius:4px; border:0.5px solid #313244; background:#313244; color:#cdd6f4;">
                    <option value="unisex">Unisex</option>
                    <option value="hombre">Hombre</option>
                    <option value="mujer">Mujer</option>
                </select>
            </div>

            <div style="margin-bottom:16px;">
                <label for="categoria_id" style="display:block; font-size:13px; color:#6c7086; margin-bottom:4px;">Categoría</label>
                <select name="categoria_id" id="categoria_id" required style="width:100%; padding:8px 12px; border-radius:4px; border:0.5px solid #313244; background:#313244; color:#cdd6f4;">
                    <option value="">Seleccionar categoría</option>
                    @foreach($categorias as $categoria)
                        <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" style="background:#cba6f7; color:#1e1e2e; padding:8px 16px; border-radius:6px; font-size:13px; font-weight:500;">
                Crear producto
            </button>
            <a href="{{ route('admin.productos.index') }}" style="display:inline-block; padding:8px 16px; font-size:13px; color:#a6adc8; text-decoration:none;">
                 ← Volver
            </a>
        </form>
    </div>
</x-layouts.admin>