<!-- Id Proveedor Field -->
<div class="col-sm-12">
    {!! Form::label('id_proveedor', 'Proveedor:') !!}
    <p>{{ $ordenCarga->proveedor->nombre ?? '-' }}</p>
</div>

<!-- Id Producto Field -->
<div class="col-sm-12">
    {!! Form::label('id_producto', 'Producto:') !!}
    <p>{{ $ordenCarga->producto->nombre ?? '-' }}</p>
</div>

<!-- Origen Field -->
<div class="col-sm-12">
    {!! Form::label('origen', 'Origen:') !!}
    <p>{{ $ordenCarga->origen }}</p>
</div>

<!-- Destino Field -->
<div class="col-sm-12">
    {!! Form::label('destino', 'Destino:') !!}
    <p>{{ $ordenCarga->destino }}</p>
</div>

<!-- Id Camion Field -->
<div class="col-sm-12">
    {!! Form::label('id_camion', 'Camión (Chapa):') !!}
    <p>{{ $ordenCarga->camion->chapa ?? '-' }}</p>
</div>

<!-- Estado Field -->
<div class="col-sm-12">
    {!! Form::label('estado', 'Estado:') !!}
    <p>{{ $ordenCarga->estado }}</p>
</div>

<!-- Created At Field -->
<div class="col-sm-12">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{{ $ordenCarga->created_at }}</p>
</div>

<!-- Updated At Field -->
<div class="col-sm-12">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{{ $ordenCarga->updated_at }}</p>
</div>

