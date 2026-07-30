<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Viatico {{ $viatico->numero }}</title>
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
        .watermark {
            position: fixed;
            top: 45%;
            left: 0;
            width: 100%;
            text-align: center;
            font-size: 90px;
            font-weight: bold;
            color: rgba(200, 0, 0, 0.25);
            transform: rotate(-30deg);
        }
    </style>
</head>
<body>

@if(strtolower($viatico->estado) === 'anulado')
    <div class="watermark">ANULADO</div>
@endif

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
            <h2>Viático N° {{ $viatico->numero }}</h2>
            <div>Fecha: {{ $viatico->fecha }}</div>
            <div>Estado: {{ $viatico->estado }}</div>
        </td>
    </tr>
</table>

<hr>

<!-- Datos del viatico -->
<div class="section-title">Datos del Viático</div>
<table class="data-table">
    <tr>
        <td class="label">Descripcion</td>
        <td>{{ $viatico->descripcion }}</td>
    </tr>
    <tr>
        <td class="label">Monto</td>
        <td>{{ number_format((float) $viatico->monto, 0, ',', '.') }}</td>
    </tr>
    <tr>
        <td class="label">Cargado Por</td>
        <td>{{ $viatico->cargado_por }}</td>
    </tr>
</table>

<!-- Datos del chofer -->
<div class="section-title">Datos del Chofer</div>
<table class="data-table">
    <tr>
        <td class="label">Nombre</td>
        <td>{{ $viatico->chofer->nombre ?? 'Sin asignar' }}</td>
    </tr>
    <tr>
        <td class="label">Apellido</td>
        <td>{{ $viatico->chofer->apellido ?? '-' }}</td>
    </tr>
    <tr>
        <td class="label">Documento</td>
        <td>{{ $viatico->chofer->documento ?? '-' }}</td>
    </tr>
</table>

<!-- Datos de la orden de carga -->
<div class="section-title">Datos de la Orden de Carga</div>
<table class="data-table">
    <tr>
        <td class="label">Orden de Carga</td>
        <td>{{ $viatico->ordenCarga ? 'OC-' . str_pad($viatico->ordenCarga->id, 6, '0', STR_PAD_LEFT) : '-' }}</td>
    </tr>
    <tr>
        <td class="label">Origen</td>
        <td>{{ $viatico->ordenCarga->origen ?? '-' }}</td>
    </tr>
    <tr>
        <td class="label">Destino</td>
        <td>{{ $viatico->ordenCarga->destino ?? '-' }}</td>
    </tr>
    <tr>
        <td class="label">Camión</td>
        <td>{{ $viatico->ordenCarga->camion->chapa ?? '-' }}</td>
    </tr>
</table>

</body>
</html>
