<!-- Tipo Moneda Field -->
<div class="col-sm-12">
    {!! Form::label('tipo_moneda', 'Tipo Moneda:') !!}
    <p>{{ $moneda->tipo_moneda }}</p>
</div>

<!-- Monto Field -->
<div class="col-sm-12">
    {!! Form::label('monto', 'Monto:') !!}
    <p>{{ $moneda->monto }}</p>
</div>

<!-- Created At Field -->
<div class="col-sm-12">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{{ $moneda->created_at }}</p>
</div>

<!-- Updated At Field -->
<div class="col-sm-12">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{{ $moneda->updated_at }}</p>
</div>

