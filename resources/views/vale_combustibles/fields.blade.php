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
    {!! Form::select('id_camion', $camions, null, ['class' => 'form-control select2', 'id' => 'id_camion', 'style' => 'width: 100%', 'placeholder' => 'Seleccione un camión', 'required' => 'required']) !!}
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
    <div class="input-group">
        {!! Form::select('direccion', $direcciones, null, ['class' => 'form-control', 'id' => 'direccion', 'placeholder' => 'Seleccione una opción', 'required' => 'required']) !!}
        <div class="input-group-append">
            <button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#direccion-modal" title="Agregar nueva dirección">
                <i class="fas fa-plus"></i>
            </button>
        </div>
    </div>
</div>

<div class="modal fade" id="direccion-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nueva Dirección</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group mb-0">
                    <label for="direccion-nombre-nueva">Nombre</label>
                    <input type="text" class="form-control" id="direccion-nombre-nueva" placeholder="Ej: Vacay">
                    <small class="text-danger d-none" id="direccion-nombre-error"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="direccion-guardar-btn">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- Producto Field -->
<div class="form-group col-sm-4">
    {!! Form::label('producto', 'Producto:') !!}
    <div class="input-group">
        {!! Form::select('producto', $productoVales, null, ['class' => 'form-control', 'id' => 'producto', 'placeholder' => 'Seleccione una opción', 'required' => 'required']) !!}
        <div class="input-group-append">
            <button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#producto-modal" title="Agregar nuevo producto">
                <i class="fas fa-plus"></i>
            </button>
        </div>
    </div>
</div>

<div class="modal fade" id="producto-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nuevo Producto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group mb-0">
                    <label for="producto-nombre-nueva">Nombre</label>
                    <input type="text" class="form-control" id="producto-nombre-nueva" placeholder="Ej: Nafta">
                    <small class="text-danger d-none" id="producto-nombre-error"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="producto-guardar-btn">Guardar</button>
            </div>
        </div>
    </div>
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

<style>
    #id_camion + .select2-container .select2-selection--single {
        height: calc(1.5em + .75rem + 2px);
        border: 1px solid #ced4da;
        border-radius: .25rem;
    }
    #id_camion + .select2-container .select2-selection--single .select2-selection__rendered {
        line-height: calc(1.5em + .75rem);
        padding-left: .75rem;
        color: #495057;
    }
    #id_camion + .select2-container .select2-selection--single .select2-selection__arrow {
        height: calc(1.5em + .75rem);
        right: 6px;
    }
    #id_camion + .select2-container--default.select2-container--focus .select2-selection--single,
    #id_camion + .select2-container--default .select2-selection--single:focus {
        border-color: #80bdff;
        outline: 0;
        box-shadow: 0 0 0 .2rem rgba(0,123,255,.25);
    }
</style>

{{-- jQuery/Select2 solo estan disponibles despues de @yield('content'), asi que este
     script se registra via @push('third_party_scripts') para ejecutarse recien al final
     del body, cuando esas librerias ya cargaron. --}}
@push('third_party_scripts')
    <script>
        $(function () {
            var $camionSelect = $('#id_camion');

            if (!$camionSelect.length) {
                return;
            }

            $camionSelect.select2({
                width: '100%',
                placeholder: 'Seleccione un camión',
                allowClear: true
            });
        });
    </script>
@endpush

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

    // --- Alta rapida de Estacion/Direccion/Producto desde el modal, sin salir de este formulario ---
    (function () {
        function configurarAltaRapida(opciones) {
            var guardarBtn = document.getElementById(opciones.guardarBtnId);
            var nombreInput = document.getElementById(opciones.nombreInputId);
            var errorEl = document.getElementById(opciones.errorElId);
            var select = document.getElementById(opciones.selectId);

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

                fetch(opciones.url, {
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
                                : opciones.errorGenerico;
                            mostrarError(mensaje);
                            return;
                        }

                        var opcion = new Option(resultado.datos.nombre, resultado.datos.nombre, true, true);
                        select.appendChild(opcion);
                        select.value = resultado.datos.nombre;

                        nombreInput.value = '';
                        document.querySelector('#' + opciones.modalId + ' [data-dismiss="modal"]').click();
                    })
                    .catch(function () {
                        guardarBtn.disabled = false;
                        mostrarError('Error de conexión. Intentá de nuevo.');
                    });
            });
        }

        configurarAltaRapida({
            guardarBtnId: 'estacion-guardar-btn',
            nombreInputId: 'estacion-nombre-nueva',
            errorElId: 'estacion-nombre-error',
            selectId: 'nombre_estacion',
            modalId: 'estacion-modal',
            url: "{{ route('estaciones.store') }}",
            errorGenerico: 'No se pudo guardar la estación.'
        });

        configurarAltaRapida({
            guardarBtnId: 'direccion-guardar-btn',
            nombreInputId: 'direccion-nombre-nueva',
            errorElId: 'direccion-nombre-error',
            selectId: 'direccion',
            modalId: 'direccion-modal',
            url: "{{ route('direcciones.store') }}",
            errorGenerico: 'No se pudo guardar la dirección.'
        });

        configurarAltaRapida({
            guardarBtnId: 'producto-guardar-btn',
            nombreInputId: 'producto-nombre-nueva',
            errorElId: 'producto-nombre-error',
            selectId: 'producto',
            modalId: 'producto-modal',
            url: "{{ route('productoVales.store') }}",
            errorGenerico: 'No se pudo guardar el producto.'
        });
    })();
</script>