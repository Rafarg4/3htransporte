<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Orden de Carga {{ $numero }}</title>
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

@if(strtolower($ordenCarga->estado) === 'anulado')
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
            <h2>Orden de Carga N° {{ $numero }}</h2>
            <div>Fecha: {{ $ordenCarga->created_at ? $ordenCarga->created_at->format('d/m/Y H:i') : '-' }}</div>
            <div>Estado: {{ $ordenCarga->estado }}</div>
        </td>
    </tr>
</table>

<hr>

<!-- Datos de la orden -->
<div class="section-title">Datos de la Orden</div>
<table class="data-table">
    <tr>
        <td class="label">Producto</td>
        <td>{{ $ordenCarga->producto->nombre ?? '-' }}</td>
    </tr>
    <tr>
        <td class="label">Origen</td>
        <td>{{ $ordenCarga->origen }}</td>
    </tr>
    <tr>
        <td class="label">Destino</td>
        <td>{{ $ordenCarga->destino }}</td>
    </tr>
</table>

<!-- Datos del proveedor -->
<div class="section-title">Datos del Proveedor</div>
<table class="data-table">
    <tr>
        <td class="label">Doc/Ruc</td>
        <td>{{ $ordenCarga->proveedor->documento ?? '-' }}</td>
    </tr>
    <tr>
        <td class="label">Cliente</td>
        <td>{{ trim(($ordenCarga->proveedor->nombre ?? '') . ' ' . ($ordenCarga->proveedor->apellido ?? '')) ?: '-' }}</td>
    </tr>
</table>

<!-- Datos del camion -->
<div class="section-title">Datos del Camión</div>
<table class="data-table">
    <tr>
        <td class="label">Chapa</td>
        <td>{{ $ordenCarga->camion->chapa ?? '-' }}</td>
    </tr>
    <tr>
        <td class="label">Marca / Modelo</td>
        <td>{{ $ordenCarga->camion->marca ?? '-' }} {{ $ordenCarga->camion->modelo ?? '' }}</td>
    </tr>
    <tr>
        <td class="label">Tipo</td>
        <td>{{ $ordenCarga->camion->tipo ?? '-' }}</td>
    </tr>
    <tr>
        <td class="label">Año</td>
        <td>{{ $ordenCarga->camion->anho ?? '-' }}</td>
    </tr>
    <tr>
        <td class="label">Color</td>
        <td>{{ $ordenCarga->camion->color ?? '-' }}</td>
    </tr>
    <tr>
        <td class="label">Ejes</td>
        <td>{{ $ordenCarga->camion->ejes ?? '-' }}</td>
    </tr>
    <tr>
        <td class="label">Nro Chasis</td>
        <td>{{ $ordenCarga->camion->nro_chasis ?? '-' }}</td>
    </tr>
    <tr>
        <td class="label">Propietario</td>
        <td>{{ trim(($ordenCarga->camion->propietario->nombre ?? '') . ' ' . ($ordenCarga->camion->propietario->apellido ?? '')) ?: 'Sin asignar' }}</td>
    </tr>
</table>

<!-- Datos del chofer -->
<div class="section-title">Datos del Chofer</div>
<table class="data-table">
    <tr>
        <td class="label">Nombre</td>
        <td>{{ $ordenCarga->camion->chofer->nombre ?? 'Sin asignar' }}</td>
    </tr>
    <tr>
        <td class="label">Apellido</td>
        <td>{{ $ordenCarga->camion->chofer->apellido ?? '-' }}</td>
    </tr>
    <tr>
        <td class="label">Documento</td>
        <td>{{ $ordenCarga->camion->chofer->documento ?? '-' }}</td>
    </tr>
</table>

</body>
</html>
