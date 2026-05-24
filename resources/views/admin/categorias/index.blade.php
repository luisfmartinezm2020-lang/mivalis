<x-layouts.admin>
    <div>
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:24px;">
            <h1 style="font-size:20px; font-weight:500; color:#cdd6f4;">Categorías</h1>
            <a href="{{ route('admin.categorias.create') }}" style="background:#cba6f7; color:#1e1e2e; padding:8px 16px; border-radius:6px; font-size:13px; text-decoration:none; font-weight:500;">
                + Nueva categoría
            </a>
        </div>

        <div style="background:#1e1e2e; border:0.5px solid #313244; border-radius:8px; overflow:hidden;">
            <table style="width:100%; font-size:13px; border-collapse:collapse;">
                <thead>
                    <tr style="border-bottom:0.5px solid #313244;">
                        <th style="padding:12px 16px; text-align:left; color:#6c7086; font-weight:400;">Id</th>
                        <th style="padding:12px 16px; text-align:left; color:#6c7086; font-weight:400;">Nombre</th>
                        <th style="padding:12px 16px; text-align:left; color:#6c7086; font-weight:400;">Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categorias as $categoria)
                        <tr style="border-bottom:0.5px solid #313244;">
                            <td style="padding:12px 16px; color:#a6adc8;">{{ $categoria->id }}</td>
                            <td style="padding:12px 16px; color:#cdd6f4;">{{ $categoria->nombre }}</td>
                            <td style="padding:12px 16px; display:flex; gap:8px;">
                                <a href="{{ route('admin.categorias.edit', $categoria) }}" style="font-size:12px; padding:4px 12px; border-radius:4px; background:#313244; color:#cdd6f4; text-decoration:none;">
                                    Editar
                                </a>
                                <form action="{{ route('admin.categorias.destroy', $categoria) }}" method="POST" class="delete-form" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="font-size:12px; padding:4px 12px; border-radius:4px; background:#f38ba8; color:#1e1e2e; border:none; cursor:pointer;">
                                        Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.admin>