<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Liquidaciones</title>
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
        table.data-table td.numero {
            text-align: right;
        }
        table.data-table tr.totales-row td {
            font-weight: bold;
            border-top: 2px solid #999;
            border-bottom: none;
        }
        table.data-table tr.totales-row td.label-total {
            text-align: right;
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
            <h2>Reporte de Liquidaciones</h2>
            <div>Generado: {{ now()->format('d/m/Y H:i') }}</div>
            @if(!empty($filtros['fecha_desde']) || !empty($filtros['fecha_hasta']))
                <div>Período: {{ $filtros['fecha_desde'] ?? '...' }} al {{ $filtros['fecha_hasta'] ?? '...' }}</div>
            @endif
            @if(!empty($filtros['facturado']))
                <div>Facturado: {{ $filtros['facturado'] === 'Si' ? 'Sí' : 'No' }}</div>
            @endif
            @if(!empty($filtros['pagado']))
                <div>Pagado: {{ $filtros['pagado'] === 'Si' ? 'Sí' : 'No' }}</div>
            @endif
        </td>
    </tr>
</table>

<hr>

<table class="data-table">
    <thead>
    <tr>
        <th>Nro.</th>
        <th>Fecha</th>
        <th>Propietario</th>
        <th>Chapa</th>
        <th>Créditos</th>
        <th>Débitos</th>
        <th>Saldo</th>
        <th>Facturado</th>
        <th>Pagado</th>
    </tr>
    </thead>
    <tbody>
    @forelse($liquidacions as $liquidacion)
        <tr>
            <td>{{ $liquidacion->id }}</td>
            <td>{{ $liquidacion->fecha }}</td>
            <td>{{ $liquidacion->cliente ? trim($liquidacion->cliente->nombre . ' ' . $liquidacion->cliente->apellido) : '-' }}</td>
            <td>{{ $liquidacion->chapas ?? '-' }}</td>
            <td class="numero">{{ number_format($liquidacion->total_creditos, 0, ',', '.') }}</td>
            <td class="numero">{{ number_format($liquidacion->total_debitos, 0, ',', '.') }}</td>
            <td class="numero">{{ number_format($liquidacion->saldo, 0, ',', '.') }}</td>
            <td>{{ $liquidacion->facturado === 'Si' ? 'Sí' : 'No' }}</td>
            <td>{{ $liquidacion->pagado === 'Si' ? 'Sí' : 'No' }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="9" style="text-align:center;">No hay liquidaciones para los filtros seleccionados.</td>
        </tr>
    @endforelse
    </tbody>
    @if($liquidacions->isNotEmpty())
        <tfoot>
        <tr class="totales-row">
            <td colspan="4" class="label-total">Totales</td>
            <td class="numero">{{ number_format($liquidacions->sum('total_creditos'), 0, ',', '.') }}</td>
            <td class="numero">{{ number_format($liquidacions->sum('total_debitos'), 0, ',', '.') }}</td>
            <td class="numero">{{ number_format($liquidacions->sum('saldo'), 0, ',', '.') }}</td>
            <td colspan="2"></td>
        </tr>
        </tfoot>
    @endif
</table>

<div class="total">Total de registros: {{ $liquidacions->count() }}</div>

</body>
</html>
