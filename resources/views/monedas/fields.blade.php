<!-- Tipo Moneda Field -->
<div class="form-group col-sm-6">
    {!! Form::label('tipo_moneda', 'Tipo Moneda:') !!}
    {!! Form::select('tipo_moneda', [
        'PYG' => 'Guaraníes',
        'USD' => 'Dólares',
        'EUR' => 'Euros',
        'ARS' => 'Pesos Argentinos',
        'BRL' => 'Reales Brasileros'
    ], null, ['class' => 'form-control', 'placeholder' => 'Seleccione una moneda']) !!}
</div>

<!-- Monto Field -->
<div class="form-group col-sm-6">
    {!! Form::label('monto', 'Monto:') !!}
    {!! Form::text('monto', null, ['class' => 'form-control']) !!}
</div>