<!-- Tipo Field -->
<div class="col-sm-12">
    {!! Form::label('tipo', 'Tipo:') !!}
    <p>{{ $proveedor->tipo ?? '-' }}</p>
</div>

<!-- Documento Field -->
<div class="col-sm-12">
    {!! Form::label('documento', 'Documento / RUC:') !!}
    <p>{{ $proveedor->documento }}</p>
</div>

<!-- Nombre Field -->
<div class="col-sm-12">
    {!! Form::label('nombre', 'Nombre:') !!}
    <p>{{ $proveedor->nombre }}</p>
</div>

@if($proveedor->tipo !== 'Empresa')
<!-- Apellido Field -->
<div class="col-sm-12">
    {!! Form::label('apellido', 'Apellido:') !!}
    <p>{{ $proveedor->apellido ?? '-' }}</p>
</div>
@endif

<!-- Created At Field -->
<div class="col-sm-12">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{{ $proveedor->created_at }}</p>
</div>

<!-- Updated At Field -->
<div class="col-sm-12">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{{ $proveedor->updated_at }}</p>
</div>

