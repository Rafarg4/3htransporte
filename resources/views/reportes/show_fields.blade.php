<!-- Nro Remision Field -->
<div class="col-sm-12">
    {!! Form::label('nro_remision', 'Nro. Remisión:') !!}
    <p>{{ $reporte->nro_remision }}</p>
</div>

<!-- Fecha Field -->
<div class="col-sm-12">
    {!! Form::label('fecha', 'Fecha:') !!}
    <p>{{ $reporte->fecha }}</p>
</div>

<!-- Propietario Field -->
<div class="col-sm-12">
    {!! Form::label('id_cliente', 'Propietario:') !!}
    <p>{{ $reporte->cliente ? trim($reporte->cliente->nombre . ' ' . $reporte->cliente->apellido) : '-' }}</p>
</div>

<!-- Chapa Field -->
<div class="col-sm-12">
    {!! Form::label('id_camion', 'Chapa:') !!}
    <p>{{ $reporte->camion->chapa ?? '-' }}</p>
</div>

<!-- Chofer Field -->
<div class="col-sm-12">
    {!! Form::label('id_chofer', 'Chofer:') !!}
    <p>{{ $reporte->chofer ? trim($reporte->chofer->nombre . ' ' . $reporte->chofer->apellido) : '-' }}</p>
</div>

<!-- Producto Field -->
<div class="col-sm-12">
    {!! Form::label('id_producto', 'Producto:') !!}
    <p>{{ $reporte->producto->nombre ?? '-' }}</p>
</div>

<!-- Tramo Field -->
<div class="col-sm-12">
    {!! Form::label('tramo', 'Tramo:') !!}
    <p>{{ $reporte->tramo }}</p>
</div>

<!-- Kg Origen Field -->
<div class="col-sm-12">
    {!! Form::label('kg_origen', 'Kg Origen:') !!}
    <p>{{ $reporte->kg_origen }}</p>
</div>

<!-- Kg Llegada Field -->
<div class="col-sm-12">
    {!! Form::label('kg_llegada', 'Kg Llegada:') !!}
    <p>{{ $reporte->kg_llegada }}</p>
</div>

<!-- Precio Field -->
<div class="col-sm-12">
    {!! Form::label('precio', 'Precio:') !!}
    <p>{{ $reporte->precio }}</p>
</div>

<!-- Monto Field -->
<div class="col-sm-12">
    {!! Form::label('monto', 'Monto:') !!}
    <p>{{ $reporte->monto }}</p>
</div>

<!-- Created At Field -->
<div class="col-sm-12">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{{ $reporte->created_at }}</p>
</div>

<!-- Updated At Field -->
<div class="col-sm-12">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{{ $reporte->updated_at }}</p>
</div>
