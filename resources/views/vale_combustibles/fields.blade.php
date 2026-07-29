<!-- Numero Vale Field -->
<div class="form-group col-sm-4">
    {!! Form::label('numero_vale', 'Numero Vale:') !!}
    {!! Form::text('numero_vale', isset($valeCombustible) ? null : '138', ['class' => 'form-control', 'required' => 'required']) !!}
</div>

<!-- Vigencia Desde Field -->
<div class="form-group col-sm-4">
    {!! Form::label('vigencia_desde', 'Vigencia Desde:') !!}
    {!! Form::date('vigencia_desde', null, ['class' => 'form-control', 'required' => 'required']) !!}
</div>

<!-- Vigencia Hasta Field -->
<div class="form-group col-sm-4">
    {!! Form::label('vigencia_hasta', 'Vigencia Hasta:') !!}
    {!! Form::date('vigencia_hasta', null, ['class' => 'form-control', 'required' => 'required']) !!}
</div>

<!-- Id Camion Field -->
<div class="form-group col-sm-4">
    {!! Form::label('id_camion', 'Camión:') !!}
    {!! Form::select('id_camion', $camions, null, ['class' => 'form-control', 'placeholder' => 'Seleccione un camión', 'required' => 'required']) !!}
</div>

<!-- Nombre Estacion Field -->
<div class="form-group col-sm-4">
    {!! Form::label('nombre_estacion', 'Nombre Estacion:') !!}
    {!! Form::select('nombre_estacion', ['Ecop' => 'Ecop', 'Petrobras' => 'Petrobras'], null, ['class' => 'form-control', 'placeholder' => 'Seleccione una opción', 'required' => 'required']) !!}
</div>

<!-- Codigo Field -->
<div class="form-group col-sm-4">
    {!! Form::label('codigo', 'Codigo:') !!}
    {!! Form::text('codigo', null, ['class' => 'form-control', 'required' => 'required']) !!}
</div>

<!-- Direccion Field -->
<div class="form-group col-sm-4">
    {!! Form::label('direccion', 'Direccion:') !!}
    {!! Form::select('direccion', ['Encarnación' => 'Encarnación'], null, ['class' => 'form-control', 'placeholder' => 'Seleccione una opción', 'required' => 'required']) !!}
</div>

<!-- Producto Field -->
<div class="form-group col-sm-4">
    {!! Form::label('producto', 'Producto:') !!}
    {!! Form::select('producto', ['Diesel S50' => 'Diesel S50', 'Nafta' => 'Nafta'], null, ['class' => 'form-control', 'placeholder' => 'Seleccione una opción', 'required' => 'required']) !!}
</div>

<!-- Importe Field -->
<div class="form-group col-sm-4">
    {!! Form::label('importe', 'Importe:') !!}
    {!! Form::text('importe', null, ['class' => 'form-control', 'id' => 'importe', 'required' => 'required']) !!}
</div>

<!-- Litros Field -->
<div class="form-group col-sm-4">
    {!! Form::label('litros', 'Litros:') !!}
    {!! Form::text('litros', null, ['class' => 'form-control', 'required' => 'required']) !!}
</div>

<!-- Realizado Por Field -->
<div class="form-group col-sm-4">
    {!! Form::label('realizado_por', 'Realizado Por:') !!}
    {!! Form::text('realizado_por', isset($valeCombustible) ? null : Auth::user()->name, ['class' => 'form-control', 'readonly' => 'readonly', 'required' => 'required']) !!}
</div>

<script>
    (function () {
        var importeInput = document.getElementById('importe');
        if (!importeInput) {
            return;
        }

        function formatearMiles(valor) {
            var digitos = valor.replace(/\D/g, '');
            return digitos ? new Intl.NumberFormat('es-PY').format(digitos) : '';
        }

        importeInput.value = formatearMiles(importeInput.value);

        importeInput.addEventListener('input', function () {
            var posicionDesdeFinal = importeInput.value.length - importeInput.selectionStart;
            importeInput.value = formatearMiles(importeInput.value);
            var posicion = importeInput.value.length - posicionDesdeFinal;
            importeInput.setSelectionRange(posicion, posicion);
        });

        if (importeInput.form) {
            importeInput.form.addEventListener('submit', function () {
                importeInput.value = importeInput.value.replace(/\D/g, '');
            });
        }
    })();
</script>