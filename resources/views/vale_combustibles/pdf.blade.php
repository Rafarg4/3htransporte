<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Vale Combustible {{ $valeCombustible->numero_vale }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
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
            max-height: 110px;
            max-width: 200px;
        }
        .empresa-nombre {
            font-size: 16px;
            font-weight: bold;
        }
        .documento-titulo {
            text-align: right;
        }
        .documento-titulo h2 {
            margin: 0;
            font-size: 18px;
        }
        hr {
            border: none;
            border-top: 2px solid #333;
            margin: 8px 0 16px;
        }
        .section-title {
            background: #f1f3f5;
            padding: 6px 10px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 11px;
            margin-top: 18px;
            margin-bottom: 6px;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
        }
        table.data-table td {
            padding: 4px 10px;
            border-bottom: 1px solid #eee;
        }
        table.data-table td.label {
            color: #666;
            width: 30%;
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
            <h2>Vale de Combustible N° {{ $valeCombustible->numero_vale }}</h2>
            <div>Vigencia: {{ $valeCombustible->vigencia_desde }} al {{ $valeCombustible->vigencia_hasta }}</div>
        </td>
    </tr>
</table>

<hr>

<!-- Datos de la estacion -->
<div class="section-title">Datos de la Estación</div>
<table class="data-table">
    <tr>
        <td class="label">Nombre</td>
        <td>{{ $valeCombustible->nombre_estacion }}</td>
    </tr>
    <tr>
        <td class="label">Código</td>
        <td>{{ $valeCombustible->codigo }}</td>
    </tr>
    <tr>
        <td class="label">Dirección</td>
        <td>{{ $valeCombustible->direccion }}</td>
    </tr>
</table>

<!-- Detalle del vale -->
<div class="section-title">Detalle del Vale</div>
<table class="data-table">
    <tr>
        <td class="label">Producto</td>
        <td>{{ $valeCombustible->producto }}</td>
    </tr>
    <tr>
        <td class="label">Importe</td>
        <td>{{ number_format($valeCombustible->importe, 0, ',', '.') }}</td>
    </tr>
    <tr>
        <td class="label">Litros</td>
        <td>{{ $valeCombustible->litros }}</td>
    </tr>
    <tr>
        <td class="label">Realizado Por</td>
        <td>{{ $valeCombustible->realizado_por }}</td>
    </tr>
</table>

<!-- Datos del camion -->
<div class="section-title">Datos del Camión</div>
<table class="data-table">
    <tr>
        <td class="label">Chapa</td>
        <td>{{ $valeCombustible->camion->chapa ?? '-' }}</td>
    </tr>
    <tr>
        <td class="label">Marca / Modelo</td>
        <td>{{ $valeCombustible->camion->marca ?? '-' }} {{ $valeCombustible->camion->modelo ?? '' }}</td>
    </tr>
</table>

<!-- Datos del chofer -->
<div class="section-title">Datos del Chofer</div>
<table class="data-table">
    <tr>
        <td class="label">Nombre</td>
        <td>{{ $valeCombustible->camion && $valeCombustible->camion->chofer ? trim($valeCombustible->camion->chofer->nombre . ' ' . $valeCombustible->camion->chofer->apellido) : 'Sin asignar' }}</td>
    </tr>
    <tr>
        <td class="label">Documento</td>
        <td>{{ $valeCombustible->camion->chofer->documento ?? '-' }}</td>
    </tr>
</table>

</body>
</html>
