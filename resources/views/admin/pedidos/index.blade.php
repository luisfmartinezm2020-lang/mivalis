<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Pedidos
        </h2>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
        @endif

        @if($pedidos->isEmpty())
            <p class="text-gray-500">No hay pedidos aún.</p>
        @else
            <div class="overflow-x-auto bg-white shadow rounded-lg">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">#</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Cliente</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Contacto</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Ciudad</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Total</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Estado</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Fecha</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($pedidos as $pedido)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 font-mono text-gray-500">{{ $pedido->id }}</td>
                                <td class="px-4 py-3 font-medium">{{ $pedido->nombre }}</td>
                                <td class="px-4 py-3 text-gray-600">
                                    {{ $pedido->celular }}<br>
                                    <span class="text-xs text-gray-400">{{ $pedido->correo }}</span>
                                </td>
                                <td class="px-4 py-3">{{ $pedido->ciudad }}</td>
                                <td class="px-4 py-3 font-semibold">${{ number_format($pedido->total, 0, ',', '.') }}</td>
                                <td class="px-4 py-3">
                                    @if($pedido->estado === 'pendiente')
                                        <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-800 rounded-full">Pendiente</span>
                                    @elseif($pedido->estado === 'confirmado')
                                        <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-full">Confirmado</span>
                                    @else
                                        <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">Entregado</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-gray-500 text-xs">{{ $pedido->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-4 py-3">
                                    <details class="group">
                                        <summary class="cursor-pointer text-indigo-600 hover:underline text-xs mb-1">Ver productos</summary>
                                        <ul class="mt-1 text-xs text-gray-600 space-y-1">
                                            @foreach($pedido->productos as $prod)
                                                <li>• {{ $prod->nombre }}
                                                    @if($prod->pivot->talla) talla {{ $prod->pivot->talla }}@endif
                                                    x{{ $prod->pivot->cantidad }}
                                                    — ${{ number_format($prod->pivot->precio_unitario, 0, ',', '.') }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </details>

                                    <div class="flex gap-2 mt-2">
                                        @if($pedido->estado === 'pendiente')
                                            <form method="POST" action="{{ route('admin.pedidos.confirmar', $pedido) }}">
                                                @csrf
                                                <button class="text-xs px-2 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">Confirmar</button>
                                            </form>
                                        @endif
                                        @if($pedido->estado === 'confirmado')
                                            <form method="POST" action="{{ route('admin.pedidos.entregar', $pedido) }}">
                                                @csrf
                                                <button class="text-xs px-2 py-1 bg-green-600 text-white rounded hover:bg-green-700">Entregar</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-app-layout>
