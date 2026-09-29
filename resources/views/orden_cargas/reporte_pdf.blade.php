<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Órdenes de Carga</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #333;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 10px;
        }
        .header-table td {
            vertical-align: middle;
            padding: 0;
        }
        .logo {
            max-height: 90px;
            max-width: 160px;
        }
        .empresa-nombre {
            font-size: 15px;
            font-weight: bold;
        }
        .documento-titulo {
            text-align: right;
        }
        .documento-titulo h2 {
            margin: 0;
            font-size: 16px;
        }
        hr {
            border: none;
            border-top: 2px solid #333;
            margin: 8px 0 16px;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
        }
        table.data-table th,
        table.data-table td {
            padding: 5px 6px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        table.data-table th {
            background: #f1f3f5;
            text-transform: uppercase;
            font-size: 10px;
        }
        .total {
            margin-top: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<table class="header-table">
    <tr>
        <td style="width: 1%; white-space: nowrap; padding-right: 10px;">
            @if($empresa && $empresa->logo && file_exists(public_path('imagenes/' . $empresa->logo)))
                <img class="logo" src="{{ public_path('imagenes/' . $empresa->logo) }}">
            @endif
        </td>
        <td>
            @if($empresa)
                <div class="empresa-nombre">{{ $empresa->nombre }}</div>
                <div>RUC: {{ $empresa->ruc }}</div>
                <div>{{ $empresa->direccion }}</div>
                <div>Tel: {{ $empresa->telefono }}</div>
            @endif
        </td>
        <td class="documento-titulo" style="width: 35%;">
            <h2>Reporte de Órdenes de Carga</h2>
            <div>Generado: {{ now()->format('d/m/Y H:i') }}</div>
            @if($proveedor)
                <div>Proveedor: {{ $proveedor->nombre }}</div>
            @endif
            @if(!empty($filtros['fecha_desde']) || !empty($filtros['fecha_hasta']))
                <div>Período: {{ !empty($filtros['fecha_desde']) ? \Carbon\Carbon::parse($filtros['fecha_desde'])->format('d/m/Y') : '...' }} al {{ !empty($filtros['fecha_hasta']) ? \Carbon\Carbon::parse($filtros['fecha_hasta'])->format('d/m/Y') : '...' }}</div>
            @endif
        </td>
    </tr>
</table>

<hr>

<table class="data-table">
    <thead>
    <tr>
        <th>Numero</th>
        <th>Fecha</th>
        <th>Proveedor</th>
        <th>Producto</th>
        <th>Origen</th>
        <th>Destino</th>
        <th>Camión</th>
        <th>Obs</th>
        <th>Estado</th>
    </tr>
    </thead>
    <tbody>
    @forelse($ordenCargas as $ordenCarga)
        <tr>
            <td>{{ $ordenCarga->numero }}</td>
            <td>{{ $ordenCarga->created_at ? $ordenCarga->created_at->format('d/m/Y') : '-' }}</td>
            <td>{{ $ordenCarga->proveedor->nombre ?? '-' }}</td>
            <td>{{ $ordenCarga->producto->nombre ?? '-' }}</td>
            <td>{{ $ordenCarga->origen }}</td>
            <td>{{ $ordenCarga->destino }}</td>
            <td>{{ $ordenCarga->camion->chapa ?? '-' }}</td>
            <td>{{ $ordenCarga->observacion ?: '-' }}</td>
            <td>{{ $ordenCarga->estado }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="9" style="text-align:center;">No hay órdenes de carga para los filtros seleccionados.</td>
        </tr>
    @endforelse
    </tbody>
</table>

<div class="total">Total de registros: {{ $ordenCargas->count() }}</div>

</body>
</html>
