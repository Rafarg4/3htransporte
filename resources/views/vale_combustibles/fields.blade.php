<!-- Numero Vale Field -->
<div class="form-group col-sm-4">
    {!! Form::label('numero_vale', 'Numero Vale:') !!}
    {!! Form::text('numero_vale', isset($valeCombustible) ? null : $proximoNumeroVale, ['class' => 'form-control', 'readonly' => 'readonly', 'required' => 'required']) !!}
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
    <div class="input-group">
        {!! Form::select('nombre_estacion', $estaciones, null, ['class' => 'form-control', 'id' => 'nombre_estacion', 'placeholder' => 'Seleccione una opción', 'required' => 'required']) !!}
        <div class="input-group-append">
            <button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#estacion-modal" title="Agregar nueva estación">
                <i class="fas fa-plus"></i>
            </button>
        </div>
    </div>
</div>

<div class="modal fade" id="estacion-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nueva Estación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group mb-0">
                    <label for="estacion-nombre-nueva">Nombre</label>
                    <input type="text" class="form-control" id="estacion-nombre-nueva" placeholder="Ej: Ecop">
                    <small class="text-danger d-none" id="estacion-nombre-error"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="estacion-guardar-btn">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- Codigo Field -->
<div class="form-group col-sm-4">
    {!! Form::label('codigo', 'Codigo:') !!}
    {!! Form::text('codigo', isset($valeCombustible) ? null : '138', ['class' => 'form-control', 'required' => 'required']) !!}
</div>

<!-- Direccion Field -->
<div class="form-group col-sm-4">
    {!! Form::label('direccion', 'Direccion:') !!}
    {!! Form::select('direccion', [
        'Vacay' => 'Vacay',
        'María Auxiliadora' => 'María Auxiliadora',
        'Yatytay' => 'Yatytay',
        'Edelira 60' => 'Edelira 60',
        'Obligado' => 'Obligado',
        'Santa Rita' => 'Santa Rita',
        'Santa Inés' => 'Santa Inés',
        'Capitán Meza' => 'Capitán Meza',
        'Capitán Miranda' => 'Capitán Miranda',
    ], null, ['class' => 'form-control', 'placeholder' => 'Seleccione una opción', 'required' => 'required']) !!}
</div>

<!-- Producto Field -->
<div class="form-group col-sm-4">
    {!! Form::label('producto', 'Producto:') !!}
    {!! Form::select('producto', ['Diesel S50' => 'Diesel S50', 'Nafta' => 'Nafta'], null, ['class' => 'form-control', 'placeholder' => 'Seleccione una opción', 'required' => 'required']) !!}
</div>

<!-- Importe Field -->
<div class="form-group col-sm-4">
    {!! Form::label('importe', 'Precio (por litro):') !!}
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

    // --- Alta rapida de Estacion desde el modal, sin salir de este formulario ---
    (function () {
        var guardarBtn = document.getElementById('estacion-guardar-btn');
        var nombreInput = document.getElementById('estacion-nombre-nueva');
        var errorEl = document.getElementById('estacion-nombre-error');
        var estacionSelect = document.getElementById('nombre_estacion');

        if (!guardarBtn) {
            return;
        }

        function mostrarError(mensaje) {
            errorEl.textContent = mensaje;
            errorEl.classList.remove('d-none');
        }

        function limpiarError() {
            errorEl.textContent = '';
            errorEl.classList.add('d-none');
        }

        guardarBtn.addEventListener('click', function () {
            var nombre = nombreInput.value.trim();
            limpiarError();

            if (!nombre) {
                mostrarError('Ingresá un nombre.');
                return;
            }

            guardarBtn.disabled = true;

            fetch("{{ route('estaciones.store') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ nombre: nombre })
            })
                .then(function (response) {
                    return response.json().then(function (datos) {
                        return { ok: response.ok, datos: datos };
                    });
                })
                .then(function (resultado) {
                    guardarBtn.disabled = false;

                    if (!resultado.ok) {
                        var mensaje = (resultado.datos.errors && resultado.datos.errors.nombre)
                            ? resultado.datos.errors.nombre[0]
                            : 'No se pudo guardar la estación.';
                        mostrarError(mensaje);
                        return;
                    }

                    var opcion = new Option(resultado.datos.nombre, resultado.datos.nombre, true, true);
                    estacionSelect.appendChild(opcion);
                    estacionSelect.value = resultado.datos.nombre;

                    nombreInput.value = '';
                    document.querySelector('#estacion-modal [data-dismiss="modal"]').click();
                })
                .catch(function () {
                    guardarBtn.disabled = false;
                    mostrarError('Error de conexión. Intentá de nuevo.');
                });
        });
    })();
</script>