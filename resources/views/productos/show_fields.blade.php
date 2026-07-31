<!-- Nombre Field -->
<div class="col-sm-12">
    {!! Form::label('nombre', 'Nombre:') !!}
    <p>{{ $producto->nombre }}</p>
</div>

<!-- Estado Field -->
<div class="col-sm-12">
    {!! Form::label('estado', 'Estado:') !!}
    <p>
        <span class="badge estado-badge estado-badge-{{ $producto->estado === 'Activo' ? 'activo' : 'inactivo' }}">
            {{ $producto->estado }}
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
    .estado-badge-inactivo {
        background: #f8d7da;
        color: #721c24;
    }
</style>

<!-- Created At Field -->
<div class="col-sm-12">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{{ $producto->created_at }}</p>
</div>

<!-- Updated At Field -->
<div class="col-sm-12">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{{ $producto->updated_at }}</p>
</div>

