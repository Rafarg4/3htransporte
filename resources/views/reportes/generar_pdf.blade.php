<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte</title>
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
            <h2>Reporte</h2>
            <div>Generado: {{ now()->format('d/m/Y H:i') }}</div>
            @if(!empty($filtros['fecha_desde']) || !empty($filtros['fecha_hasta']))
                <div>Período: {{ $filtros['fecha_desde'] ?? '...' }} al {{ $filtros['fecha_hasta'] ?? '...' }}</div>
            @endif
        </td>
    </tr>
</table>

<hr>

<table class="data-table">
    <thead>
    <tr>
        <th>Fecha</th>
        <th>Kg Origen</th>
        <th>Kg Llegada</th>
        <th>Precio Real Flete</th>
        <th>Precio Fletero</th>
    </tr>
    </thead>
    <tbody>
    @forelse($reportes as $reporte)
        <tr>
            <td>{{ $reporte->created_at ? $reporte->created_at->format('d/m/Y') : '-' }}</td>
            <td>{{ $reporte->kg_origen }}</td>
            <td>{{ $reporte->kg_llegada }}</td>
            <td>{{ $reporte->precio_real_flete }}</td>
            <td>{{ $reporte->precio_fletero }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="5" style="text-align:center;">No hay reportes para los filtros seleccionados.</td>
        </tr>
    @endforelse
    </tbody>
</table>

<div class="total">Total de registros: {{ $reportes->count() }}</div>

</body>
</html>
