<!-- Numero Field -->
<div class="col-sm-12">
    {!! Form::label('numero', 'Numero:') !!}
    <p>{{ $ordenCarga->numero }}</p>
</div>

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
    <p>
        <span class="badge estado-badge estado-badge-{{ strtolower($ordenCarga->estado) === 'activo' ? 'activo' : 'anulado' }}">
            {{ $ordenCarga->estado }}
        </span>
    </p>
</div>

<style>
    .estado-badge {
        font-size: .8rem;
        padding: .4rem .75rem;
        border-radius: 1rem;
    }
    .estado-badge-activo {
        background: #d4edda;
        color: #155724;
    }
    .estado-badge-anulado {
        background: #f1f3f5;
        color: #6c757d;
    }
</style>

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

