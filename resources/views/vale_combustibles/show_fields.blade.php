<!-- Numero Vale Field -->
<div class="col-sm-12">
    {!! Form::label('numero_vale', 'Numero Vale:') !!}
    <p>{{ $valeCombustible->numero_vale }}</p>
</div>

<!-- Vigencia Desde Field -->
<div class="col-sm-12">
    {!! Form::label('vigencia_desde', 'Vigencia Desde:') !!}
    <p>{{ $valeCombustible->vigencia_desde }}</p>
</div>

<!-- Vigencia Hasta Field -->
<div class="col-sm-12">
    {!! Form::label('vigencia_hasta', 'Vigencia Hasta:') !!}
    <p>{{ $valeCombustible->vigencia_hasta }}</p>
</div>

<!-- Id Camion Field -->
<div class="col-sm-12">
    {!! Form::label('id_camion', 'Id Camion:') !!}
    <p>{{ $valeCombustible->id_camion }}</p>
</div>

<!-- Nombre Estacion Field -->
<div class="col-sm-12">
    {!! Form::label('nombre_estacion', 'Nombre Estacion:') !!}
    <p>{{ $valeCombustible->nombre_estacion }}</p>
</div>

<!-- Codigo Field -->
<div class="col-sm-12">
    {!! Form::label('codigo', 'Codigo:') !!}
    <p>{{ $valeCombustible->codigo }}</p>
</div>

<!-- Direccion Field -->
<div class="col-sm-12">
    {!! Form::label('direccion', 'Direccion:') !!}
    <p>{{ $valeCombustible->direccion }}</p>
</div>

<!-- Producto Field -->
<div class="col-sm-12">
    {!! Form::label('producto', 'Producto:') !!}
    <p>{{ $valeCombustible->producto }}</p>
</div>

<!-- Importe Field -->
<div class="col-sm-12">
    {!! Form::label('importe', 'Importe:') !!}
    <p>{{ $valeCombustible->importe }}</p>
</div>

<!-- Litros Field -->
<div class="col-sm-12">
    {!! Form::label('litros', 'Litros:') !!}
    <p>{{ $valeCombustible->litros }}</p>
</div>

<!-- Realizado Por Field -->
<div class="col-sm-12">
    {!! Form::label('realizado_por', 'Realizado Por:') !!}
    <p>{{ $valeCombustible->realizado_por }}</p>
</div>

<!-- Estado Field -->
<div class="col-sm-12">
    {!! Form::label('estado', 'Estado:') !!}
    <p>
        <span class="badge estado-badge estado-badge-{{ strtolower($valeCombustible->estado) === 'activo' ? 'activo' : 'anulado' }}">
            {{ $valeCombustible->estado }}
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
    <p>{{ $valeCombustible->created_at }}</p>
</div>

<!-- Updated At Field -->
<div class="col-sm-12">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{{ $valeCombustible->updated_at }}</p>
</div>

