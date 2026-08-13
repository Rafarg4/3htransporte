<!-- Nro Remision Field -->
<div class="form-group col-sm-4">
    {!! Form::label('nro_remision', 'Nro. Remisión:') !!}
    {!! Form::text('nro_remision', null, ['class' => 'form-control', 'required' => 'required']) !!}
</div>

<!-- Fecha Field -->
<div class="form-group col-sm-4">
    {!! Form::label('fecha', 'Fecha:') !!}
    {!! Form::date('fecha', old('fecha', now()->format('Y-m-d')), ['class' => 'form-control', 'required' => 'required']) !!}
</div>

<!-- Propietario Field -->
<div class="form-group col-sm-4">
    {!! Form::label('id_cliente', 'Propietario:') !!}
    {!! Form::select('id_cliente', $clientes, null, ['class' => 'form-control select2', 'style' => 'width:100%;', 'placeholder' => 'Seleccione un propietario', 'required' => 'required']) !!}
</div>

<!-- Camion (Chapa) Field -->
<div class="form-group col-sm-4">
    {!! Form::label('id_camion', 'Chapa:') !!}
    {!! Form::select('id_camion', $camiones, null, ['class' => 'form-control select2', 'style' => 'width:100%;', 'placeholder' => 'Seleccione una chapa', 'required' => 'required']) !!}
</div>

<!-- Chofer Field -->
<div class="form-group col-sm-4">
    {!! Form::label('id_chofer', 'Chofer:') !!}
    {!! Form::select('id_chofer', $choferes, null, ['class' => 'form-control select2', 'style' => 'width:100%;', 'placeholder' => 'Seleccione un chofer', 'required' => 'required']) !!}
</div>

<!-- Producto Field -->
<div class="form-group col-sm-4">
    {!! Form::label('id_producto', 'Producto:') !!}
    {!! Form::select('id_producto', $productos, null, ['class' => 'form-control select2', 'style' => 'width:100%;', 'placeholder' => 'Seleccione un producto', 'required' => 'required']) !!}
</div>

<!-- Tramo Field -->
<div class="form-group col-sm-4">
    {!! Form::label('tramo', 'Tramo:') !!}
    {!! Form::text('tramo', null, ['class' => 'form-control', 'placeholder' => 'Origen a destino', 'required' => 'required']) !!}
</div>

<!-- Kg Origen Field -->
<div class="form-group col-sm-4">
    {!! Form::label('kg_origen', 'Kg Origen:') !!}
    {!! Form::text('kg_origen', null, ['class' => 'form-control', 'required' => 'required']) !!}
</div>

<!-- Kg Llegada Field -->
<div class="form-group col-sm-4">
    {!! Form::label('kg_llegada', 'Kg Llegada:') !!}
    {!! Form::text('kg_llegada', null, ['class' => 'form-control', 'required' => 'required']) !!}
</div>

<!-- Precio Field -->
<div class="form-group col-sm-4">
    {!! Form::label('precio', 'Precio:') !!}
    {!! Form::text('precio', null, ['class' => 'form-control', 'required' => 'required']) !!}
</div>

<!-- Monto Field -->
<div class="form-group col-sm-4">
    {!! Form::label('monto', 'Monto:') !!}
    {!! Form::text('monto', null, ['class' => 'form-control', 'required' => 'required']) !!}
</div>

{{-- jQuery/Select2 solo estan disponibles despues de @yield('content'), asi que este script
     se registra via @push('third_party_scripts') para ejecutarse recien al final del body,
     cuando esas librerias ya cargaron (mismo patron que liquidacions/fields.blade.php). --}}
@push('third_party_scripts')
<script>
    (function () {
        $('.select2').select2({
            width: '100%',
            allowClear: true
        });
    })();
</script>
@endpush
