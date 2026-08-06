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

<!-- Precio Real Flete Field -->
<div class="col-sm-12">
    {!! Form::label('precio_real_flete', 'Precio Real Flete:') !!}
    <p>{{ $reporte->precio_real_flete }}</p>
</div>

<!-- Precio Fletero Field -->
<div class="col-sm-12">
    {!! Form::label('precio_fletero', 'Precio Fletero:') !!}
    <p>{{ $reporte->precio_fletero }}</p>
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

