<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Liquidacion {{ $liquidacion->id }}</title>
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
        table.data-table th,
        table.data-table td {
            padding: 4px 8px;
            border-bottom: 1px solid #eee;
            text-align: left;
        }
        table.data-table th {
            color: #666;
            font-weight: normal;
            font-size: 10px;
            text-transform: uppercase;
            border-bottom: 1px solid #ccc;
        }
        table.data-table td.numero {
            text-align: right;
        }
        .totales {
            width: 100%;
            margin-top: 20px;
        }
        .totales td {
            padding: 4px 8px;
        }
        .totales .label {
            text-align: right;
            color: #666;
        }
        .totales .valor {
            text-align: right;
            width: 120px;
            font-weight: bold;
        }
        .totales .saldo .valor {
            font-size: 14px;
            border-top: 2px solid #333;
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

@if(strtolower($liquidacion->estado) === 'anulado')
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
            <h2>Liquidación N° {{ $liquidacion->id }}</h2>
            <div>Fecha: {{ $liquidacion->fecha }}</div>
            <div>Estado: {{ $liquidacion->estado }}</div>
            <div>Facturado: {{ $liquidacion->facturado }}</div>
        </td>
    </tr>
</table>

<hr>

<div class="section-title">Datos de la Liquidación</div>
<table class="data-table">
    <tr>
        <td class="label">Propietario</td>
        <td>{{ $liquidacion->cliente ? trim($liquidacion->cliente->nombre . ' ' . $liquidacion->cliente->apellido) : '-' }}</td>
    </tr>
    <tr>
        <td class="label">Camión</td>
        <td>
            @php
                $chapas = $liquidacion->fletes->pluck('camion.chapa')->filter()->unique()->values();
            @endphp
            {{ $chapas->isNotEmpty() ? $chapas->implode(', ') : ($liquidacion->camion->chapa ?? '-') }}
        </td>
    </tr>
    <tr>
        <td class="label">Chofer</td>
        <td>
            @php
                // Solo cubre choferes con algun Viatico tildado en esta liquidacion: es el unico
                // lugar donde queda registrado que estuvieron; un chofer tildado sin Viatico no
                // deja rastro en ninguna tabla, asi que no se puede listar.
                $nombresChoferes = $liquidacion->viaticos
                    ->pluck('chofer')
                    ->filter()
                    ->unique('id')
                    ->map(fn ($chofer) => trim($chofer->nombre . ' ' . $chofer->apellido))
                    ->values();
            @endphp
            {{ $nombresChoferes->isNotEmpty() ? $nombresChoferes->implode(', ') : ($liquidacion->chofer ? trim($liquidacion->chofer->nombre . ' ' . $liquidacion->chofer->apellido) : '-') }}
        </td>
    </tr>
    <tr>
        <td class="label">Orden de Carga</td>
        <td>{{ $liquidacion->ordenCarga ? 'OC-' . str_pad($liquidacion->ordenCarga->id, 6, '0', STR_PAD_LEFT) . ' - ' . $liquidacion->ordenCarga->destino : '-' }}</td>
    </tr>
</table>

@if($liquidacion->fletes->isNotEmpty())
    <div class="section-title">Flete</div>
    <table class="data-table">
        <tr>
            <th>Camión</th>
            <th>Orden de Carga</th>
            <th>Fecha</th>
            <th>Tramo</th>
            <th>Kg Origen</th>
            <th>Kg Destino</th>
            <th>Diferencia</th>
            <th>Precio</th>
            <th>Valor</th>
        </tr>
        @foreach($liquidacion->fletes as $flete)
            <tr>
                <td>{{ $flete->camion->chapa ?? '-' }}</td>
                <td>{{ $flete->ordenCarga ? 'OC-' . str_pad($flete->ordenCarga->id, 6, '0', STR_PAD_LEFT) : '-' }}</td>
                <td>{{ $flete->fecha }}</td>
                <td>{{ $flete->tramo }}</td>
                <td class="numero">{{ number_format((float) $flete->kg_origen, 0, ',', '.') }}</td>
                <td class="numero">{{ number_format((float) $flete->kg_destino, 0, ',', '.') }}</td>
                <td class="numero">{{ $flete->diferencia !== null && $flete->diferencia !== '' ? number_format((float) $flete->diferencia, 0, ',', '.') : '-' }}</td>
                <td class="numero">{{ number_format((float) $flete->precio, 0, ',', '.') }}</td>
                <td class="numero">{{ number_format((float) $flete->valor, 0, ',', '.') }}</td>
            </tr>
        @endforeach
    </table>
@endif

@if($liquidacion->viaticos->isNotEmpty())
    <div class="section-title">Viático</div>
    <table class="data-table">
        <tr>
            <th>Numero</th>
            <th>Fecha</th>
            <th>Chofer</th>
            <th>Descripción</th>
            <th>Monto</th>
        </tr>
        @foreach($liquidacion->viaticos as $viatico)
            <tr>
                <td>{{ $viatico->numero }}</td>
                <td>{{ $viatico->fecha }}</td>
                <td>{{ $viatico->chofer ? trim($viatico->chofer->nombre . ' ' . $viatico->chofer->apellido) : '-' }}</td>
                <td>{{ $viatico->descripcion }}</td>
                <td class="numero">{{ number_format((float) $viatico->monto, 0, ',', '.') }}</td>
            </tr>
        @endforeach
    </table>
@endif

@if($liquidacion->combustibles->isNotEmpty())
    <div class="section-title">Combustible</div>
    <table class="data-table">
        <tr>
            <th>Vale N°</th>
            <th>Vigencia</th>
            <th>Estación</th>
            <th>Litros</th>
            <th>Precio</th>
            <th>Valor</th>
        </tr>
        @foreach($liquidacion->combustibles as $combustible)
            <tr>
                <td>{{ $combustible->numero_vale }}</td>
                <td>{{ $combustible->vigencia_desde }}</td>
                <td>{{ $combustible->nombre_estacion }}</td>
                <td class="numero">{{ number_format((float) $combustible->litros, 0, ',', '.') }}</td>
                <td class="numero">{{ number_format((float) $combustible->importe, 0, ',', '.') }}</td>
                <td class="numero">{{ number_format((float) $combustible->litros * (float) $combustible->importe, 0, ',', '.') }}</td>
            </tr>
        @endforeach
    </table>
@endif

@if($liquidacion->gastosAdministrativos->isNotEmpty())
    <div class="section-title">Gastos Administrativos</div>
    <table class="data-table">
        <tr>
            <th>Fecha</th>
            <th>Concepto</th>
            <th>Valor</th>
        </tr>
        @foreach($liquidacion->gastosAdministrativos as $gasto)
            <tr>
                <td>{{ $gasto->fecha }}</td>
                <td>{{ $gasto->concepto }}</td>
                <td class="numero">{{ number_format((float) $gasto->valor, 0, ',', '.') }}</td>
            </tr>
        @endforeach
    </table>
@endif

<table class="totales">
    <tr>
        <td class="label">Créditos</td>
        <td class="valor">{{ number_format($liquidacion->total_creditos, 0, ',', '.') }}</td>
    </tr>
    <tr>
        <td class="label">Débitos</td>
        <td class="valor">{{ number_format($liquidacion->total_debitos, 0, ',', '.') }}</td>
    </tr>
    <tr class="saldo">
        <td class="label">Saldo</td>
        <td class="valor">{{ number_format($liquidacion->saldo, 0, ',', '.') }}</td>
    </tr>
</table>

</body>
</html>
