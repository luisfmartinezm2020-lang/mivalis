<x-layouts.admin>
    <div style="max-width:600px; margin:0 auto; background:#1e1e2e; border:0.5px solid #313244; border-radius:8px; padding:24px;">
        <h1 style="font-size:20px; font-weight:500; color:#cdd6f4; margin-bottom:16px;">Crear nueva categoría</h1>

        <form action="{{ route('admin.categorias.store') }}" method="POST">
            @csrf
            <div style="margin-bottom:16px;">
                <label for="nombre" style="display:block; font-size:13px; color:#6c7086; margin-bottom:4px;">Nombre de la categoría</label>
                <input type="text" name="nombre" id="nombre" required style="width:100%; padding:8px 12px; border-radius:4px; border:0.5px solid #313244; background:#313244; color:#cdd6f4;">
            </div>
            <button type="submit" style="background:#cba6f7; color:#1e1e2e; padding:8px 16px; border-radius:6px; font-size:13px; font-weight:500;">
                Crear categoría
            </button>
        </form>



    </div>
    </x-layouts.admin>