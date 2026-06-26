<x-layouts.admin>
    <div style="max-width:700px;">

        <h1 style="font-size:20px; font-weight:500; color:#cdd6f4; margin-bottom:24px;">Ajustes</h1>

        {{-- TABS --}}
        <div style="display:flex; gap:4px; margin-bottom:24px; border-bottom:1px solid #313244;">
            <button onclick="mostrarTab('general')" id="tab-general"
                style="padding:8px 16px; font-size:12px; letter-spacing:0.06em; text-transform:uppercase;
                       background:none; border:none; cursor:pointer; color:#cba6f7; border-bottom:2px solid #cba6f7;">
                General
            </button>
            <button onclick="mostrarTab('redes')" id="tab-redes"
                style="padding:8px 16px; font-size:12px; letter-spacing:0.06em; text-transform:uppercase;
                       background:none; border:none; cursor:pointer; color:#6c7086; border-bottom:2px solid transparent;">
                Redes sociales
            </button>
            <button onclick="mostrarTab('password')" id="tab-password"
                style="padding:8px 16px; font-size:12px; letter-spacing:0.06em; text-transform:uppercase;
                       background:none; border:none; cursor:pointer; color:#6c7086; border-bottom:2px solid transparent;">
                Contraseña
            </button>
        </div>

        {{-- TAB: GENERAL --}}
        <div id="panel-general">
            <form action="{{ route('admin.ajustes.actualizar') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div style="background:#1e1e2e; border:0.5px solid #313244; border-radius:8px; padding:24px; display:flex; flex-direction:column; gap:16px;">

                    <div>
                        <label style="display:block; font-size:11px; color:#6c7086; letter-spacing:0.06em; text-transform:uppercase; margin-bottom:6px;">Nombre de la tienda</label>
                        <input type="text" name="nombre_tienda" value="{{ $config['nombre_tienda'] ?? '' }}"
                            style="width:100%; padding:10px 12px; background:#181825; border:1px solid #313244; border-radius:6px; color:#cdd6f4; font-size:13px; box-sizing:border-box;">
                    </div>

                    <div>
                        <label style="display:block; font-size:11px; color:#6c7086; letter-spacing:0.06em; text-transform:uppercase; margin-bottom:6px;">WhatsApp (con código de país)</label>
                        <input type="text" name="whatsapp" value="{{ $config['whatsapp'] ?? '' }}"
                            style="width:100%; padding:10px 12px; background:#181825; border:1px solid #313244; border-radius:6px; color:#cdd6f4; font-size:13px; box-sizing:border-box;">
                        <p style="font-size:11px; color:#6c7086; margin:4px 0 0;">Ejemplo: 573044229882</p>
                    </div>

                    <div>
                        <label style="display:block; font-size:11px; color:#6c7086; letter-spacing:0.06em; text-transform:uppercase; margin-bottom:6px;">Logo</label>
                        @if(!empty($config['logo']))
                            <img src="{{ Storage::url($config['logo']) }}" style="width:80px; height:80px; object-fit:cover; border-radius:6px; margin-bottom:8px; display:block;">
                        @endif
                        <input type="file" name="logo" accept="image/*" style="font-size:12px; color:#a6adc8;">
                    </div>

                    <input type="hidden" name="instagram" value="{{ $config['instagram'] ?? '' }}">
                    <input type="hidden" name="tiktok" value="{{ $config['tiktok'] ?? '' }}">

                    <button type="submit"
                        style="background:#cba6f7; color:#1e1e2e; padding:10px 20px; border:none; border-radius:6px; font-size:12px; font-weight:500; cursor:pointer; letter-spacing:0.06em; text-transform:uppercase; align-self:flex-start;">
                        Guardar cambios
                    </button>
                </div>
            </form>
        </div>

        {{-- TAB: REDES --}}
        <div id="panel-redes" style="display:none;">
            <form action="{{ route('admin.ajustes.actualizar') }}" method="POST">
                @csrf
                <div style="background:#1e1e2e; border:0.5px solid #313244; border-radius:8px; padding:24px; display:flex; flex-direction:column; gap:16px;">

                    <div>
                        <label style="display:block; font-size:11px; color:#6c7086; letter-spacing:0.06em; text-transform:uppercase; margin-bottom:6px;">Instagram</label>
                        <input type="text" name="instagram" value="{{ $config['instagram'] ?? '' }}" placeholder="@mivalis"
                            style="width:100%; padding:10px 12px; background:#181825; border:1px solid #313244; border-radius:6px; color:#cdd6f4; font-size:13px; box-sizing:border-box;">
                    </div>

                    <div>
                        <label style="display:block; font-size:11px; color:#6c7086; letter-spacing:0.06em; text-transform:uppercase; margin-bottom:6px;">TikTok</label>
                        <input type="text" name="tiktok" value="{{ $config['tiktok'] ?? '' }}" placeholder="@mivalis"
                            style="width:100%; padding:10px 12px; background:#181825; border:1px solid #313244; border-radius:6px; color:#cdd6f4; font-size:13px; box-sizing:border-box;">
                    </div>

                    <input type="hidden" name="nombre_tienda" value="{{ $config['nombre_tienda'] ?? '' }}">
                    <input type="hidden" name="whatsapp" value="{{ $config['whatsapp'] ?? '' }}">

                    <button type="submit"
                        style="background:#cba6f7; color:#1e1e2e; padding:10px 20px; border:none; border-radius:6px; font-size:12px; font-weight:500; cursor:pointer; letter-spacing:0.06em; text-transform:uppercase; align-self:flex-start;">
                        Guardar cambios
                    </button>
                </div>
            </form>
        </div>

        {{-- TAB: CONTRASEÑA --}}
        <div id="panel-password" style="display:none;">
            <form action="{{ route('admin.ajustes.password') }}" method="POST">
                @csrf
                <div style="background:#1e1e2e; border:0.5px solid #313244; border-radius:8px; padding:24px; display:flex; flex-direction:column; gap:16px;">

                    @error('password_actual')
                        <p style="font-size:12px; color:#f38ba8; margin:0;">{{ $message }}</p>
                    @enderror

                    <div>
                        <label style="display:block; font-size:11px; color:#6c7086; letter-spacing:0.06em; text-transform:uppercase; margin-bottom:6px;">Contraseña actual</label>
                        <input type="password" name="password_actual"
                            style="width:100%; padding:10px 12px; background:#181825; border:1px solid #313244; border-radius:6px; color:#cdd6f4; font-size:13px; box-sizing:border-box;">
                    </div>

                    <div>
                        <label style="display:block; font-size:11px; color:#6c7086; letter-spacing:0.06em; text-transform:uppercase; margin-bottom:6px;">Nueva contraseña</label>
                        <input type="password" name="password_nuevo"
                            style="width:100%; padding:10px 12px; background:#181825; border:1px solid #313244; border-radius:6px; color:#cdd6f4; font-size:13px; box-sizing:border-box;">
                    </div>

                    <div>
                        <label style="display:block; font-size:11px; color:#6c7086; letter-spacing:0.06em; text-transform:uppercase; margin-bottom:6px;">Confirmar nueva contraseña</label>
                        <input type="password" name="password_nuevo_confirmation"
                            style="width:100%; padding:10px 12px; background:#181825; border:1px solid #313244; border-radius:6px; color:#cdd6f4; font-size:13px; box-sizing:border-box;">
                    </div>

                    <button type="submit"
                        style="background:#cba6f7; color:#1e1e2e; padding:10px 20px; border:none; border-radius:6px; font-size:12px; font-weight:500; cursor:pointer; letter-spacing:0.06em; text-transform:uppercase; align-self:flex-start;">
                        Cambiar contraseña
                    </button>
                </div>
            </form>
        </div>

    </div>

    <script>
    function mostrarTab(tab) {
        const paneles = ['general', 'redes', 'password'];
        paneles.forEach(p => {
            document.getElementById(`panel-${p}`).style.display = p === tab ? 'block' : 'none';
            const btn = document.getElementById(`tab-${p}`);
            btn.style.color        = p === tab ? '#cba6f7' : '#6c7086';
            btn.style.borderBottom = p === tab ? '2px solid #cba6f7' : '2px solid transparent';
        });
    }
    </script>
</x-layouts.admin>