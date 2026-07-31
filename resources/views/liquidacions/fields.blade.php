<!-- Propietario Field -->
<div class="form-group col-sm-3">
    {!! Form::label('id_cliente', 'Propietario:') !!}
    {!! Form::select('id_cliente', $clientes, null, ['class' => 'form-control', 'id' => 'id_cliente', 'placeholder' => 'Seleccione un propietario', 'required' => 'required']) !!}
</div>

<!-- Camion Field -->
<div class="form-group col-sm-3">
    <label for="id_camion">Camión:</label>
    <select name="id_camion" id="id_camion" class="form-control" required>
        <option value="">Seleccione una chapa</option>
        @foreach($camions as $camion)
            <option value="{{ $camion->id }}" data-cliente="{{ $camion->id_cliente }}">
                {{ $camion->chapa }}
            </option>
        @endforeach
    </select>
</div>

<!-- Chofer Field -->
<div class="form-group col-sm-3">
    {!! Form::label('id_chofer', 'Chofer:') !!}
    {!! Form::select('id_chofer', $choferes, null, ['class' => 'form-control', 'id' => 'id_chofer', 'placeholder' => 'Seleccione un chofer', 'required' => 'required']) !!}
</div>

<!-- Orden de Carga Field -->
<div class="form-group col-sm-3">
    <label for="id_orden_carga">Orden de Carga:</label>
    <select name="id_orden_carga" id="id_orden_carga" class="form-control">
        <option value="">Seleccione una orden de carga</option>
        @foreach($ordenCargas as $ordenCarga)
            <option value="{{ $ordenCarga->id }}" data-camion="{{ $ordenCarga->id_camion }}">
                OC-{{ str_pad($ordenCarga->id, 6, '0', STR_PAD_LEFT) }} - {{ $ordenCarga->destino }}
            </option>
        @endforeach
    </select>
</div>

<!-- Fecha Field -->
<div class="form-group col-sm-3">
    {!! Form::label('fecha', 'Fecha:') !!}
    {!! Form::date('fecha', now()->format('Y-m-d'), ['class' => 'form-control', 'required' => 'required']) !!}
</div>

<!-- FLETE -->
<div class="col-sm-12">
    <hr>
    <h5>Flete</h5>

    <div class="form-row">
        <div class="form-group col-sm-2">
            <label>Fecha</label>
            <input type="date" name="flete[fecha]" class="form-control">
        </div>
        <div class="form-group col-sm-3">
            <label>Tramo</label>
            <input type="text" name="flete[tramo]" class="form-control" placeholder="Origen a destino">
        </div>
        <div class="form-group col-sm-1">
            <label>Kg Origen</label>
            <input type="number" step="0.01" id="flete-kg-origen" name="flete[kg_origen]" class="form-control">
        </div>
        <div class="form-group col-sm-1">
            <label>Kg Destino</label>
            <input type="number" step="0.01" id="flete-kg-destino" name="flete[kg_destino]" class="form-control">
        </div>
        <div class="form-group col-sm-1">
            <label>Diferencia</label>
            <input type="text" id="flete-diferencia" class="form-control" readonly tabindex="-1">
        </div>
        <div class="form-group col-sm-2">
            <label>Precio</label>
            <input type="number" step="0.01" id="flete-precio" name="flete[precio]" class="form-control">
        </div>
        <div class="form-group col-sm-2">
            <label>Valor</label>
            <input type="number" step="0.01" id="flete-valor" name="flete[valor]" class="form-control liquidacion-credito">
        </div>
    </div>
</div>

<!-- DESCUENTO -->
<div class="col-sm-12">
    <hr>
    <h5>
        <div class="custom-control custom-checkbox d-inline-block align-middle mr-2">
            <input type="checkbox" class="custom-control-input" id="descuento-incluir">
            <label class="custom-control-label" for="descuento-incluir">Incluir descuento</label>
        </div>
    </h5>

    <div class="form-row" id="descuento-campos" style="display:none;">
        <div class="form-group col-sm-4">
            <label>Fecha</label>
            <input type="date" name="descuento[fecha]" class="form-control" disabled>
        </div>
        <div class="form-group col-sm-4">
            <label>Concepto</label>
            <select name="descuento[concepto]" class="form-control" disabled>
                <option value="">Seleccione un concepto</option>
                <option value="Multa">Multa</option>
                <option value="Anticipo">Anticipo</option>
                <option value="Faltante de Carga">Faltante de Carga</option>
                <option value="Otro">Otro</option>
            </select>
        </div>
        <div class="form-group col-sm-4">
            <label>Valor</label>
            <input type="number" step="0.01" name="descuento[valor]" class="form-control liquidacion-debito" disabled>
        </div>
    </div>
</div>

<!-- VIATICO -->
<div class="col-sm-12">
    <hr>
    <h5>Viático <small class="text-muted font-weight-normal">(tildá uno o varios)</small></h5>

    @if($viaticosDisponibles->isEmpty())
        <p class="text-muted mb-0">No hay viáticos disponibles para liquidar.</p>
    @else
        <div class="table-responsive liquidacion-select-table">
            <table class="table table-sm table-hover mb-0" id="viaticos-list">
                <thead>
                    <tr>
                        <th style="width:30px;"></th>
                        <th>Fecha</th>
                        <th>Chofer</th>
                        <th>Descripción</th>
                        <th class="text-right">Monto</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($viaticosDisponibles as $viatico)
                        <tr class="liquidacion-select-row" data-chofer="{{ $viatico->id_chofer }}">
                            <td>
                                <input type="checkbox"
                                       class="liquidacion-debito-checkbox"
                                       name="viatico_ids[]"
                                       value="{{ $viatico->id }}"
                                       data-valor="{{ $viatico->monto }}">
                            </td>
                            <td>{{ $viatico->fecha }}</td>
                            <td>{{ $viatico->chofer ? trim($viatico->chofer->nombre . ' ' . $viatico->chofer->apellido) : '-' }}</td>
                            <td>{{ $viatico->descripcion }}</td>
                            <td class="text-right">{{ number_format((float) $viatico->monto, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <p class="text-muted mb-0 mt-2 d-none" id="viaticos-empty-hint">Este chofer no tiene viáticos disponibles.</p>
    @endif
</div>

<!-- COMBUSTIBLE -->
<div class="col-sm-12">
    <hr>
    <h5>Combustible <small class="text-muted font-weight-normal">(tildá uno o varios)</small></h5>

    @if($valeCombustiblesDisponibles->isEmpty())
        <p class="text-muted mb-0">No hay vales de combustible disponibles para liquidar.</p>
    @else
        <div class="table-responsive liquidacion-select-table">
            <table class="table table-sm table-hover mb-0" id="vales-list">
                <thead>
                    <tr>
                        <th style="width:30px;"></th>
                        <th>Vigencia</th>
                        <th>Camión</th>
                        <th>Estación</th>
                        <th>Litros</th>
                        <th class="text-right">Precio</th>
                        <th class="text-right">Valor</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($valeCombustiblesDisponibles as $vale)
                        <tr class="liquidacion-select-row" data-camion="{{ $vale->id_camion }}">
                            <td>
                                <input type="checkbox"
                                       class="liquidacion-debito-checkbox"
                                       name="vale_combustible_ids[]"
                                       value="{{ $vale->id }}"
                                       data-valor="{{ (float) $vale->litros * (float) $vale->importe }}">
                            </td>
                            <td>{{ $vale->vigencia_desde }}</td>
                            <td>{{ $vale->camion->chapa ?? '-' }}</td>
                            <td>{{ $vale->nombre_estacion }}</td>
                            <td>{{ $vale->litros }} L</td>
                            <td class="text-right">{{ number_format((float) $vale->importe, 0, ',', '.') }}</td>
                            <td class="text-right">{{ number_format((float) $vale->litros * (float) $vale->importe, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <p class="text-muted mb-0 mt-2 d-none" id="vales-empty-hint">Esta chapa no tiene vales de combustible disponibles.</p>
    @endif
</div>

<!-- GASTOS ADMINISTRATIVOS -->
<div class="col-sm-12">
    <hr>
    <h5>Gastos Administrativos</h5>

    <div class="form-row">
        <div class="form-group col-sm-4">
            <label>Fecha</label>
            <input type="date" name="gasto_administrativo[fecha]" class="form-control">
        </div>
        <div class="form-group col-sm-4">
            <label>Concepto</label>
            <select name="gasto_administrativo[concepto]" class="form-control">
                <option value="">Seleccione un concepto</option>
                <option value="Administración">Administración</option>
                <option value="Comisión">Comisión</option>
                <option value="Otro">Otro</option>
            </select>
        </div>
        <div class="form-group col-sm-4">
            <label>Valor</label>
            <select name="gasto_administrativo[valor]" class="form-control liquidacion-debito-select">
                <option value="">Seleccione un monto</option>
                <option value="25000">25.000</option>
                <option value="30000">30.000</option>
                <option value="50000">50.000</option>
            </select>
        </div>
    </div>
</div>

<!-- TOTALES -->
<div class="col-sm-12">
    <hr>
    <div class="d-flex justify-content-end" style="gap: 2rem;">
        <div class="text-right">
            <small class="text-muted d-block">Créditos</small>
            <strong id="total-creditos">0</strong>
        </div>
        <div class="text-right">
            <small class="text-muted d-block">Débitos</small>
            <strong id="total-debitos">0</strong>
        </div>
        <div class="text-right">
            <small class="text-muted d-block">Saldo</small>
            <strong id="total-saldo" class="text-primary">0</strong>
        </div>
    </div>
</div>

<style>
    .liquidacion-select-table {
        max-height: 260px;
        overflow-y: auto;
        border: 1px solid #dee2e6;
        border-radius: .25rem;
    }
    .liquidacion-select-table table {
        margin-bottom: 0;
    }
    .liquidacion-select-table thead th {
        position: sticky;
        top: 0;
        background: #f8f9fa;
        font-size: .75rem;
        text-transform: uppercase;
        color: #6c757d;
        z-index: 1;
    }
    .liquidacion-select-row {
        cursor: pointer;
    }
    .liquidacion-select-row.is-selected {
        background-color: #eaf3ff;
    }
</style>

<script>
    (function () {
        function formatoNumero(valor) {
            return new Intl.NumberFormat('es-PY').format(Math.round(valor));
        }

        function recalcularTotales() {
            var creditos = 0;
            document.querySelectorAll('.liquidacion-credito').forEach(function (input) {
                creditos += parseFloat(input.value) || 0;
            });

            var debitos = 0;
            document.querySelectorAll('.liquidacion-debito:not([disabled])').forEach(function (input) {
                debitos += parseFloat(input.value) || 0;
            });

            document.querySelectorAll('.liquidacion-debito-select').forEach(function (select) {
                debitos += parseFloat(select.value) || 0;
            });

            document.querySelectorAll('.liquidacion-debito-checkbox:checked').forEach(function (checkbox) {
                debitos += parseFloat(checkbox.dataset.valor) || 0;
            });

            document.getElementById('total-creditos').textContent = formatoNumero(creditos);
            document.getElementById('total-debitos').textContent = formatoNumero(debitos);
            document.getElementById('total-saldo').textContent = formatoNumero(creditos - debitos);
        }

        document.addEventListener('input', function (event) {
            if (event.target.classList.contains('liquidacion-credito') || event.target.classList.contains('liquidacion-debito')) {
                recalcularTotales();
            }
        });

        document.addEventListener('change', function (event) {
            if (event.target.classList.contains('liquidacion-debito-select') || event.target.classList.contains('liquidacion-debito-checkbox')) {
                recalcularTotales();
            }
        });

        // --- Calculo asistido de Flete ---
        var kgOrigen = document.getElementById('flete-kg-origen');
        var kgDestino = document.getElementById('flete-kg-destino');
        var diferencia = document.getElementById('flete-diferencia');
        var precio = document.getElementById('flete-precio');
        var valor = document.getElementById('flete-valor');

        function actualizarDiferencia() {
            var origen = parseFloat(kgOrigen.value) || 0;
            var destino = parseFloat(kgDestino.value) || 0;
            diferencia.value = (origen && destino) ? formatoNumero(origen - destino) : '';
        }

        function actualizarValorFlete() {
            var destino = parseFloat(kgDestino.value) || 0;
            var precioValor = parseFloat(precio.value) || 0;
            if (destino && precioValor) {
                valor.value = Math.round(destino * precioValor);
                recalcularTotales();
            }
        }

        [kgOrigen, kgDestino].forEach(function (input) {
            input.addEventListener('input', actualizarDiferencia);
        });

        [kgDestino, precio].forEach(function (input) {
            input.addEventListener('input', actualizarValorFlete);
        });

        // --- Casillero Incluir descuento ---
        var descuentoCheckbox = document.getElementById('descuento-incluir');
        var descuentoCampos = document.getElementById('descuento-campos');

        descuentoCheckbox.addEventListener('change', function () {
            var incluir = descuentoCheckbox.checked;
            descuentoCampos.style.display = incluir ? '' : 'none';
            descuentoCampos.querySelectorAll('input, select').forEach(function (field) {
                field.disabled = !incluir;
                if (!incluir) {
                    field.value = '';
                }
            });
            recalcularTotales();
        });

        // --- Filtros: Propietario -> Camion -> Orden de Carga; Propietario -> Viatico / Combustible (checkboxes) ---
        function filtrarSelect(select, atributo, valorFiltro) {
            var opcionSeleccionadaValida = false;

            Array.prototype.forEach.call(select.options, function (opcion) {
                if (!opcion.value) {
                    return;
                }

                var mostrar = !valorFiltro || opcion.dataset[atributo] === valorFiltro;
                opcion.hidden = !mostrar;
                opcion.disabled = !mostrar;

                if (mostrar && opcion.selected) {
                    opcionSeleccionadaValida = true;
                }
            });

            if (!opcionSeleccionadaValida) {
                select.value = '';
            }
        }

        function filtrarFilasPorAtributo(listaId, hintId, atributo, valorFiltro) {
            var lista = document.getElementById(listaId);
            if (!lista) {
                return;
            }

            var visibles = 0;

            lista.querySelectorAll('.liquidacion-select-row').forEach(function (fila) {
                var mostrar = !valorFiltro || fila.dataset[atributo] === valorFiltro;
                fila.style.display = mostrar ? '' : 'none';

                if (mostrar) {
                    visibles++;
                } else {
                    var input = fila.querySelector('input[type="checkbox"]');
                    input.checked = false;
                    fila.classList.remove('is-selected');
                }
            });

            var hint = document.getElementById(hintId);
            if (hint) {
                hint.classList.toggle('d-none', !valorFiltro || visibles > 0);
            }
        }

        // --- Tildar/destildar una fila de Viatico/Combustible haciendo click en cualquier parte ---
        document.querySelectorAll('.liquidacion-select-row').forEach(function (fila) {
            var checkbox = fila.querySelector('input[type="checkbox"]');

            fila.addEventListener('click', function (event) {
                if (event.target !== checkbox) {
                    checkbox.checked = !checkbox.checked;
                    checkbox.dispatchEvent(new Event('change'));
                }
            });

            checkbox.addEventListener('change', function () {
                fila.classList.toggle('is-selected', checkbox.checked);
            });
        });

        var clienteSelect = document.getElementById('id_cliente');
        var camionSelect = document.getElementById('id_camion');
        var choferSelect = document.getElementById('id_chofer');
        var ordenCargaSelect = document.getElementById('id_orden_carga');

        clienteSelect.addEventListener('change', function () {
            filtrarSelect(camionSelect, 'cliente', clienteSelect.value);
            camionSelect.dispatchEvent(new Event('change'));
        });

        camionSelect.addEventListener('change', function () {
            filtrarSelect(ordenCargaSelect, 'camion', camionSelect.value);
            filtrarFilasPorAtributo('vales-list', 'vales-empty-hint', 'camion', camionSelect.value);
            recalcularTotales();
        });

        if (choferSelect) {
            choferSelect.addEventListener('change', function () {
                filtrarFilasPorAtributo('viaticos-list', 'viaticos-empty-hint', 'chofer', choferSelect.value);
                recalcularTotales();
            });
        }

        recalcularTotales();
    })();
</script>
