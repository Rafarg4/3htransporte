<!-- Numero Vale Field -->
<div class="form-group col-sm-4">
    {!! Form::label('numero_vale', 'Numero Vale:') !!}
    {!! Form::text('numero_vale', null, ['class' => 'form-control']) !!}
</div>

<!-- Vigencia Desde Field -->
<div class="form-group col-sm-4">
    {!! Form::label('vigencia_desde', 'Vigencia Desde:') !!}
    {!! Form::date('vigencia_desde', null, ['class' => 'form-control']) !!}
</div>

<!-- Vigencia Hasta Field -->
<div class="form-group col-sm-4">
    {!! Form::label('vigencia_hasta', 'Vigencia Hasta:') !!}
    {!! Form::date('vigencia_hasta', null, ['class' => 'form-control']) !!}
</div>

<!-- Id Camion Field -->
<div class="form-group col-sm-4">
    {!! Form::label('id_camion', 'Camión:') !!}
    {!! Form::select('id_camion', $camions, null, ['class' => 'form-control', 'placeholder' => 'Seleccione un camión']) !!}
</div>

<!-- Nombre Estacion Field -->
<div class="form-group col-sm-4">
    {!! Form::label('nombre_estacion', 'Nombre Estacion:') !!}
    {!! Form::text('nombre_estacion', null, ['class' => 'form-control']) !!}
</div>

<!-- Codigo Field -->
<div class="form-group col-sm-4">
    {!! Form::label('codigo', 'Codigo:') !!}
    {!! Form::text('codigo', null, ['class' => 'form-control']) !!}
</div>

<!-- Direccion Field -->
<div class="form-group col-sm-4">
    {!! Form::label('direccion', 'Direccion:') !!}
    {!! Form::text('direccion', null, ['class' => 'form-control']) !!}
</div>

<!-- Producto Field -->
<div class="form-group col-sm-4">
    {!! Form::label('producto', 'Producto:') !!}
    {!! Form::text('producto', null, ['class' => 'form-control']) !!}
</div>

<!-- Importe Field -->
<div class="form-group col-sm-4">
    {!! Form::label('importe', 'Importe:') !!}
    {!! Form::text('importe', null, ['class' => 'form-control', 'id' => 'importe']) !!}
</div>

<!-- Litros Field -->
<div class="form-group col-sm-4">
    {!! Form::label('litros', 'Litros:') !!}
    {!! Form::text('litros', null, ['class' => 'form-control']) !!}
</div>

<!-- Realizado Por Field -->
<div class="form-group col-sm-4">
    {!! Form::label('realizado_por', 'Realizado Por:') !!}
    {!! Form::text('realizado_por', isset($valeCombustible) ? null : Auth::user()->name, ['class' => 'form-control', 'readonly' => 'readonly']) !!}
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