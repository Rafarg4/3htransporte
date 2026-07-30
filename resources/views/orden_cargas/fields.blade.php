<!-- Id Proveedor Field -->
<div class="form-group col-sm-6">
    {!! Form::label('id_proveedor', 'Proveedor:') !!}
    <div class="input-group">
        {!! Form::select('id_proveedor', $proveedores, null, ['class' => 'form-control', 'placeholder' => 'Seleccione un proveedor', 'id' => 'id_proveedor', 'required' => 'required']) !!}
        <div class="input-group-append">
            <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#modal-nuevo-proveedor">
                <i class="fas fa-plus"></i>
            </button>
        </div>
    </div>
</div>

<!-- Id Producto Field -->
<div class="form-group col-sm-6">
    {!! Form::label('id_producto', 'Producto:') !!}
    <div class="input-group">
        {!! Form::select('id_producto', $productos, null, ['class' => 'form-control', 'placeholder' => 'Seleccione un producto', 'id' => 'id_producto', 'required' => 'required']) !!}
        <div class="input-group-append">
            <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#modal-nuevo-producto">
                <i class="fas fa-plus"></i>
            </button>
        </div>
    </div>
</div>

<!-- Origen Field -->
<div class="form-group col-sm-6">
    {!! Form::label('origen', 'Origen:') !!}
    {!! Form::text('origen', null, ['class' => 'form-control', 'required' => 'required']) !!}
</div>

<!-- Destino Field -->
<div class="form-group col-sm-6">
    {!! Form::label('destino', 'Destino:') !!}
    {!! Form::text('destino', null, ['class' => 'form-control', 'required' => 'required']) !!}
</div>

<!-- Id Camion Field -->
<div class="form-group col-sm-6">
    {!! Form::label('id_camion', 'Camión (Chapa):') !!}
    {!! Form::select('id_camion', $camiones, null, ['class' => 'form-control', 'placeholder' => 'Seleccione un camión', 'required' => 'required']) !!}
</div>


<!-- Modal: Nuevo proveedor -->
<div class="modal fade" id="modal-nuevo-proveedor" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nuevo proveedor</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="nuevo-proveedor-tipo">Tipo:</label>
                    <select id="nuevo-proveedor-tipo" class="form-control">
                        <option value="Persona">Persona</option>
                        <option value="Empresa">Empresa</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="nuevo-proveedor-documento">Documento / RUC:</label>
                    <input type="text" id="nuevo-proveedor-documento" class="form-control">
                </div>
                <div class="form-group">
                    <label for="nuevo-proveedor-nombre">Nombre:</label>
                    <input type="text" id="nuevo-proveedor-nombre" class="form-control">
                </div>
                <div class="form-group mb-0" id="nuevo-proveedor-apellido-group">
                    <label for="nuevo-proveedor-apellido">Apellido:</label>
                    <input type="text" id="nuevo-proveedor-apellido" class="form-control">
                </div>
                <small id="nuevo-proveedor-error" class="text-danger d-none">Complete los campos requeridos.</small>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btn-guardar-proveedor">Guardar</button>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        var btnGuardar = document.getElementById('btn-guardar-proveedor');
        var tipoSelect = document.getElementById('nuevo-proveedor-tipo');
        var documentoInput = document.getElementById('nuevo-proveedor-documento');
        var nombreInput = document.getElementById('nuevo-proveedor-nombre');
        var apellidoGroup = document.getElementById('nuevo-proveedor-apellido-group');
        var apellidoInput = document.getElementById('nuevo-proveedor-apellido');
        var error = document.getElementById('nuevo-proveedor-error');
        var select = document.getElementById('id_proveedor');

        function toggleApellido() {
            if (tipoSelect.value === 'Empresa') {
                apellidoGroup.classList.add('d-none');
                apellidoInput.value = '';
            } else {
                apellidoGroup.classList.remove('d-none');
            }
        }

        tipoSelect.addEventListener('change', toggleApellido);
        toggleApellido();

        btnGuardar.addEventListener('click', function () {
            var tipo = tipoSelect.value;
            var documento = documentoInput.value.trim();
            var nombre = nombreInput.value.trim();
            var apellido = apellidoInput.value.trim();

            var falta = !documento || !nombre || (tipo !== 'Empresa' && !apellido);

            if (falta) {
                error.classList.remove('d-none');
                return;
            }

            error.classList.add('d-none');

            var tokenField = document.querySelector('input[name="_token"]');

            fetch('{{ route('proveedors.store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': tokenField ? tokenField.value : '',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({tipo: tipo, documento: documento, nombre: nombre, apellido: apellido}),
            })
                .then(function (response) {
                    return response.json();
                })
                .then(function (data) {
                    var option = document.createElement('option');
                    option.value = data.id;
                    option.text = data.documento + ' - ' + data.nombre;
                    option.selected = true;
                    select.appendChild(option);

                    documentoInput.value = '';
                    nombreInput.value = '';
                    apellidoInput.value = '';
                    $('#modal-nuevo-proveedor').modal('hide');
                })
                .catch(function () {
                    alert('No se pudo guardar el proveedor.');
                });
        });
    })();
</script>

<!-- Modal: Nuevo producto -->
<div class="modal fade" id="modal-nuevo-producto" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nuevo producto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group mb-0">
                    <label for="nuevo-producto-nombre">Nombre:</label>
                    <input type="text" id="nuevo-producto-nombre" class="form-control">
                    <small id="nuevo-producto-error" class="text-danger d-none">El nombre es requerido.</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btn-guardar-producto">Guardar</button>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        var btnGuardar = document.getElementById('btn-guardar-producto');
        var input = document.getElementById('nuevo-producto-nombre');
        var error = document.getElementById('nuevo-producto-error');
        var select = document.getElementById('id_producto');

        btnGuardar.addEventListener('click', function () {
            var nombre = input.value.trim();

            if (!nombre) {
                error.classList.remove('d-none');
                return;
            }

            error.classList.add('d-none');

            var tokenField = document.querySelector('input[name="_token"]');

            fetch('{{ route('productos.store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': tokenField ? tokenField.value : '',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({nombre: nombre}),
            })
                .then(function (response) {
                    return response.json();
                })
                .then(function (data) {
                    var option = document.createElement('option');
                    option.value = data.id;
                    option.text = data.nombre;
                    option.selected = true;
                    select.appendChild(option);

                    input.value = '';
                    $('#modal-nuevo-producto').modal('hide');
                })
                .catch(function () {
                    alert('No se pudo guardar el producto.');
                });
        });
    })();
</script>