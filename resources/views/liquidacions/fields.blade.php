<!-- Propietario Field -->
<div class="form-group col-sm-3">
    {!! Form::label('id_cliente', 'Propietario:') !!}
    {!! Form::select('id_cliente', $clientes, old('id_cliente'), ['class' => 'form-control', 'id' => 'id_cliente', 'placeholder' => 'Seleccione un propietario', 'required' => 'required']) !!}
</div>

<!-- Camion Field -->
<div class="form-group col-sm-3">
    <label for="camiones-select">Camión: <small class="text-muted font-weight-normal">(podés elegir varias)</small></label>
    <select name="camion_ids[]" id="camiones-select" class="form-control" multiple="multiple" style="width:100%;">
        @php
            $camionesTildados = array_map('strval', old('camion_ids', old('id_camion') ? [old('id_camion')] : []));
        @endphp
        @foreach($camions as $camion)
            <option value="{{ $camion->id }}" data-cliente="{{ $camion->id_cliente }}" {{ in_array((string) $camion->id, $camionesTildados) ? 'selected' : '' }}>
                {{ $camion->chapa }}
            </option>
        @endforeach
    </select>
    {!! Form::hidden('id_camion', old('id_camion'), ['id' => 'id_camion']) !!}
</div>

<!-- Chofer Field -->
<div class="form-group col-sm-3">
    {!! Form::label('id_chofer', 'Chofer:') !!}
    {!! Form::select('id_chofer', $choferes, old('id_chofer'), ['class' => 'form-control', 'id' => 'id_chofer', 'placeholder' => 'Seleccione un chofer', 'required' => 'required']) !!}
</div>

<!-- Orden de Carga Field -->
<div class="form-group col-sm-3">
    <label for="id_orden_carga">Orden de Carga:</label>
    <select name="id_orden_carga" id="id_orden_carga" class="form-control">
        <option value="">Seleccione una orden de carga</option>
        @foreach($ordenCargas as $ordenCarga)
            <option value="{{ $ordenCarga->id }}" data-camion="{{ $ordenCarga->id_camion }}" {{ (string) old('id_orden_carga') === (string) $ordenCarga->id ? 'selected' : '' }}>
                OC-{{ str_pad($ordenCarga->id, 6, '0', STR_PAD_LEFT) }} - {{ $ordenCarga->destino }}
            </option>
        @endforeach
    </select>
</div>

<!-- Fecha Field -->
<div class="form-group col-sm-3">
    {!! Form::label('fecha', 'Fecha:') !!}
    {!! Form::date('fecha', old('fecha', now()->format('Y-m-d')), ['class' => 'form-control', 'required' => 'required']) !!}
</div>

<!-- FLETE -->
<div class="col-sm-12">
    <hr>
    <h5>Flete</h5>

    <div class="form-row">
        <div class="form-group col-sm-2">
            <label>Fecha</label>
            <input type="date" name="flete[fecha]" class="form-control" value="{{ old('flete.fecha') }}">
        </div>
        <div class="form-group col-sm-3">
            <label>Tramo</label>
            <input type="text" name="flete[tramo]" class="form-control" placeholder="Origen a destino" value="{{ old('flete.tramo') }}">
        </div>
        <div class="form-group col-sm-1">
            <label>Kg Origen</label>
            <input type="number" step="0.01" id="flete-kg-origen" name="flete[kg_origen]" class="form-control" value="{{ old('flete.kg_origen') }}">
        </div>
        <div class="form-group col-sm-1">
            <label>Kg Destino</label>
            <input type="number" step="0.01" id="flete-kg-destino" name="flete[kg_destino]" class="form-control" value="{{ old('flete.kg_destino') }}">
        </div>
        <div class="form-group col-sm-1">
            <label>Diferencia</label>
            <input type="text" id="flete-diferencia" class="form-control" readonly tabindex="-1" value="{{ old('flete.diferencia') }}">
            <input type="hidden" name="flete[diferencia]" id="flete-diferencia-raw" value="{{ old('flete.diferencia') }}">
        </div>
        <div class="form-group col-sm-2">
            <label>Precio</label>
            <input type="number" step="0.01" id="flete-precio" name="flete[precio]" class="form-control" value="{{ old('flete.precio') }}">
        </div>
        <div class="form-group col-sm-2">
            <label>Valor</label>
            <input type="number" step="0.01" id="flete-valor" name="flete[valor]" class="form-control liquidacion-credito" value="{{ old('flete.valor') }}">
        </div>
    </div>

    <div class="form-row align-items-end">
        <div class="col-sm-12">
            <small class="text-muted d-block mb-1">
                Diferencia negativa: se completa solo un Descuento por "Faltante de Carga" con Diferencia + (Tolerancia &times; Precio).
                Los valores se cargan por defecto desde <a href="{{ route('parametrizaciones.edit') }}" target="_blank">Parametrizaciones</a>, pero pueden ajustarse para esta liquidación.
            </small>
        </div>
        <div class="form-group col-sm-2">
            <label>Tolerancia (Kg)</label>
            <input type="number" step="0.01" id="flete-recargo-tolerancia" name="flete[recargo_tolerancia]" class="form-control" value="{{ old('flete.recargo_tolerancia', $parametrizacion->recargo_tolerancia) }}">
        </div>
        <div class="form-group col-sm-2">
            <label>Precio Recargo</label>
            <input type="number" step="0.01" id="flete-recargo-precio" name="flete[recargo_precio]" class="form-control" value="{{ old('flete.recargo_precio', $parametrizacion->recargo_precio) }}">
        </div>
    </div>
</div>

<!-- DESCUENTO -->
@php $descuentoTieneDatos = old('descuento.fecha') || old('descuento.concepto') || old('descuento.valor'); @endphp
<div class="col-sm-12">
    <hr>
    <h5>
        <div class="custom-control custom-checkbox d-inline-block align-middle mr-2">
            <input type="checkbox" class="custom-control-input" id="descuento-incluir" {{ $descuentoTieneDatos ? 'checked' : '' }}>
            <label class="custom-control-label" for="descuento-incluir">Incluir descuento</label>
        </div>
    </h5>

    <div class="form-row" id="descuento-campos" style="{{ $descuentoTieneDatos ? '' : 'display:none;' }}">
        <div class="form-group col-sm-4">
            <label>Fecha</label>
            <input type="date" name="descuento[fecha]" class="form-control" value="{{ old('descuento.fecha') }}" {{ $descuentoTieneDatos ? '' : 'disabled' }}>
        </div>
        <div class="form-group col-sm-4">
            <label>Concepto</label>
            <select name="descuento[concepto]" class="form-control" {{ $descuentoTieneDatos ? '' : 'disabled' }}>
                <option value="">Seleccione un concepto</option>
                @foreach(['Multa', 'Anticipo', 'Faltante de Carga', 'Otro'] as $concepto)
                    <option value="{{ $concepto }}" {{ old('descuento.concepto') === $concepto ? 'selected' : '' }}>{{ $concepto }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-sm-4">
            <label>Valor</label>
            <input type="number" step="0.01" name="descuento[valor]" class="form-control liquidacion-debito" value="{{ old('descuento.valor') }}" {{ $descuentoTieneDatos ? '' : 'disabled' }}>
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
                                       data-valor="{{ $viatico->monto }}"
                                       {{ in_array($viatico->id, old('viatico_ids', [])) ? 'checked' : '' }}>
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
                                       data-valor="{{ (float) $vale->litros * (float) $vale->importe }}"
                                       {{ in_array($vale->id, old('vale_combustible_ids', [])) ? 'checked' : '' }}>
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
            <input type="date" name="gasto_administrativo[fecha]" class="form-control" value="{{ old('gasto_administrativo.fecha') }}">
        </div>
        <div class="form-group col-sm-4">
            <label>Concepto</label>
            <select name="gasto_administrativo[concepto]" class="form-control">
                <option value="">Seleccione un concepto</option>
                @foreach(['Administración', 'Comisión', 'Otro'] as $concepto)
                    <option value="{{ $concepto }}" {{ old('gasto_administrativo.concepto') === $concepto ? 'selected' : '' }}>{{ $concepto }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-sm-4">
            <label>Valor</label>
            <select name="gasto_administrativo[valor]" class="form-control liquidacion-debito-select">
                <option value="">Seleccione un monto</option>
                @foreach([25000, 30000, 50000] as $monto)
                    <option value="{{ $monto }}" {{ (string) old('gasto_administrativo.valor') === (string) $monto ? 'selected' : '' }}>{{ number_format($monto, 0, ',', '.') }}</option>
                @endforeach
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

<!-- Facturado: se define recien al confirmar el modal de Guardar -->
{!! Form::hidden('facturado', old('facturado'), ['id' => 'facturado-input']) !!}
<div class="modal fade" id="facturado-modal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">¿Esta liquidación ya está facturada?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cancelar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="mb-0">Indicá si esta liquidación ya fue facturada antes de guardarla.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="facturado-modal-no">No</button>
                <button type="button" class="btn btn-primary" id="facturado-modal-si">Sí</button>
            </div>
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

    /* Camion (select2): tildar chapas con color primario, bien visible */
    #camiones-select + .select2-container .select2-selection--multiple {
        min-height: calc(1.5em + .6rem + 2px);
        border: 1px solid #ced4da;
    }
    #camiones-select + .select2-container .select2-selection__choice {
        background-color: #007bff;
        border: 1px solid #007bff;
        color: #fff;
        border-radius: .25rem;
        padding: 1px 8px;
    }
    #camiones-select + .select2-container .select2-selection__choice__remove {
        color: #fff;
        margin-right: 6px;
        font-weight: bold;
    }
    #camiones-select + .select2-container .select2-selection__choice__remove:hover {
        color: #f8d7da;
    }
    #camiones-select + .select2-container.select2-container--focus .select2-selection--multiple {
        border-color: #80bdff;
    }
    /* Resultado ya tildado: no debe listarse de nuevo en el desplegable */
    .select2-results__option[aria-selected="true"] {
        display: none;
    }
</style>

{{-- jQuery/Select2 solo estan disponibles despues de @yield('content'), asi que este
     script se registra via @push('third_party_scripts') para ejecutarse recien al final
     del body, cuando esas librerias ya cargaron (mismo patron que liquidacions/table.blade.php). --}}
@push('third_party_scripts')
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
        var diferenciaRaw = document.getElementById('flete-diferencia-raw');
        var precio = document.getElementById('flete-precio');
        var valor = document.getElementById('flete-valor');
        var recargoTolerancia = document.getElementById('flete-recargo-tolerancia');
        var recargoPrecio = document.getElementById('flete-recargo-precio');
        var fleteFecha = document.querySelector('input[name="flete[fecha]"]');
        var fechaCabecera = document.querySelector('input[name="fecha"]');

        // Cuando hay recargo, el Descuento se completa solo como "Faltante de Carga"
        // con el mismo valor del recargo (el recargo en si es solo informativo), y con
        // la Fecha del Flete (o la de la cabecera si el Flete no tiene fecha cargada).
        function sincronizarDescuentoPorRecargo(valorRecargo) {
            var descuentoIncluir = document.getElementById('descuento-incluir');
            var descuentoCampos = document.getElementById('descuento-campos');
            var descuentoFecha = descuentoCampos.querySelector('input[name="descuento[fecha]"]');
            var descuentoConcepto = descuentoCampos.querySelector('select[name="descuento[concepto]"]');
            var descuentoValor = descuentoCampos.querySelector('input[name="descuento[valor]"]');
            var autoGestionado = descuentoIncluir.dataset.autoFaltante === '1';

            if (valorRecargo !== null) {
                var esVacioOAuto = autoGestionado || (!descuentoIncluir.checked && !descuentoConcepto.value && !descuentoValor.value);
                if (!esVacioOAuto) {
                    // El usuario ya cargo un descuento propio (Multa, Anticipo, etc.), no lo pisamos.
                    return;
                }

                descuentoIncluir.checked = true;
                descuentoIncluir.dataset.autoFaltante = '1';
                descuentoCampos.style.display = '';
                descuentoCampos.querySelectorAll('input, select').forEach(function (field) {
                    field.disabled = false;
                });
                descuentoFecha.value = (fleteFecha && fleteFecha.value) ? fleteFecha.value : (fechaCabecera ? fechaCabecera.value : '');
                descuentoConcepto.value = 'Faltante de Carga';
                descuentoValor.value = valorRecargo;
            } else if (autoGestionado) {
                descuentoIncluir.checked = false;
                descuentoIncluir.dataset.autoFaltante = '';
                descuentoCampos.style.display = 'none';
                descuentoCampos.querySelectorAll('input, select').forEach(function (field) {
                    field.disabled = true;
                    field.value = '';
                });
            }
        }

        // Recargo = Diferencia + (Tolerancia x Precio), solo cuando la Diferencia es negativa.
        // No tiene campo propio en pantalla: se vuelca directo al Descuento "Faltante de Carga".
        function actualizarRecargo(valorDiferencia) {
            var tolerancia = parseFloat(recargoTolerancia.value) || 0;
            var precioRecargo = parseFloat(recargoPrecio.value) || 0;
            var valorRecargo = null;

            if (valorDiferencia !== null && valorDiferencia < 0) {
                valorRecargo = valorDiferencia + (tolerancia * precioRecargo);
            }

            sincronizarDescuentoPorRecargo(valorRecargo);
            recalcularTotales();
        }

        function actualizarDiferencia() {
            var origen = parseFloat(kgOrigen.value) || 0;
            var destino = parseFloat(kgDestino.value) || 0;
            var valorDiferencia = (origen && destino) ? (destino - origen) : null;

            diferencia.value = valorDiferencia !== null ? formatoNumero(valorDiferencia) : '';
            diferenciaRaw.value = valorDiferencia !== null ? valorDiferencia : '';

            actualizarRecargo(valorDiferencia);
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

        [recargoTolerancia, recargoPrecio].forEach(function (input) {
            input.addEventListener('input', function () {
                var origen = parseFloat(kgOrigen.value) || 0;
                var destino = parseFloat(kgDestino.value) || 0;
                actualizarRecargo((origen && destino) ? (destino - origen) : null);
            });
        });

        // Recalcular al cargar por si el formulario vuelve con datos restaurados (old()).
        actualizarDiferencia();

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

        // Igual que filtrarFilasPorAtributo, pero acepta varios valores validos a la vez
        // (usado por Vale de Combustible, que debe mostrar filas de cualquiera de las chapas tildadas).
        function filtrarFilasPorAtributoMultiple(listaId, hintId, atributo, valoresFiltro) {
            var lista = document.getElementById(listaId);
            if (!lista) {
                return;
            }

            var visibles = 0;

            lista.querySelectorAll('.liquidacion-select-row').forEach(function (fila) {
                var mostrar = valoresFiltro.length === 0 || valoresFiltro.indexOf(fila.dataset[atributo]) !== -1;
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
                hint.classList.toggle('d-none', valoresFiltro.length === 0 || visibles > 0);
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
        var idCamionHidden = document.getElementById('id_camion');
        var choferSelect = document.getElementById('id_chofer');
        var ordenCargaSelect = document.getElementById('id_orden_carga');

        // --- Camion: select2 multiple ---
        var $camionSelect = $('#camiones-select');
        var camionesOriginales = $camionSelect.find('option').map(function () {
            return { value: this.value, text: this.text, cliente: this.dataset.cliente };
        }).get();

        $camionSelect.select2({
            width: '100%',
            placeholder: 'Seleccione una o varias chapas',
            allowClear: true
        });

        // La primera chapa tildada (en orden de la lista) queda como "chapa principal":
        // es la que se guarda en la Liquidacion y filtra la Orden de Carga. Las demas
        // chapas tildadas solo amplian que Vales de Combustible se muestran/pueden tildar.
        function obtenerCamionesSeleccionados() {
            return $camionSelect.val() || [];
        }

        function actualizarCamionPrincipalYFiltros() {
            var seleccionados = obtenerCamionesSeleccionados();

            idCamionHidden.value = seleccionados[0] || '';

            filtrarSelect(ordenCargaSelect, 'camion', idCamionHidden.value);
            filtrarFilasPorAtributoMultiple('vales-list', 'vales-empty-hint', 'camion', seleccionados);
            recalcularTotales();
        }

        // Reconstruye las opciones del select2 con solo las chapas del propietario elegido,
        // preservando las que ya estaban tildadas y siguen siendo validas.
        function filtrarCamionesPorCliente(valorFiltro) {
            var seleccionActual = obtenerCamionesSeleccionados();

            $camionSelect.empty();
            camionesOriginales.forEach(function (camion) {
                if (valorFiltro && camion.cliente !== valorFiltro) {
                    return;
                }
                var opcion = new Option(camion.text, camion.value, false, seleccionActual.indexOf(camion.value) !== -1);
                $camionSelect.append(opcion);
            });

            $camionSelect.trigger('change');
        }

        clienteSelect.addEventListener('change', function () {
            filtrarCamionesPorCliente(clienteSelect.value);
        });

        $camionSelect.on('change', actualizarCamionPrincipalYFiltros);

        var liquidacionForm = idCamionHidden.closest('form');
        var facturadoInput = document.getElementById('facturado-input');
        var $facturadoModal = $('#facturado-modal');

        if (liquidacionForm) {
            liquidacionForm.addEventListener('submit', function (event) {
                if (!idCamionHidden.value) {
                    event.preventDefault();
                    alert('Seleccioná al menos una chapa (Camión).');
                    return;
                }

                // Antes de guardar, preguntamos si ya esta facturada; recien al elegir
                // Si/No se completa el campo oculto y se reenvia el formulario.
                if (!facturadoInput.value) {
                    event.preventDefault();
                    $facturadoModal.modal('show');
                }
            });
        }

        document.getElementById('facturado-modal-si').addEventListener('click', function () {
            facturadoInput.value = 'Si';
            $facturadoModal.modal('hide');
            liquidacionForm.submit();
        });

        document.getElementById('facturado-modal-no').addEventListener('click', function () {
            facturadoInput.value = 'No';
            $facturadoModal.modal('hide');
            liquidacionForm.submit();
        });

        if (choferSelect) {
            choferSelect.addEventListener('change', function () {
                filtrarFilasPorAtributo('viaticos-list', 'viaticos-empty-hint', 'chofer', choferSelect.value);
                recalcularTotales();
            });
        }

        // Si el formulario se recarga tras un error de validación, reaplicar los
        // filtros para que las selecciones restauradas (Propietario/Camión/Chofer)
        // sigan siendo coherentes entre sí.
        clienteSelect.dispatchEvent(new Event('change'));
        if (choferSelect) {
            choferSelect.dispatchEvent(new Event('change'));
        }

        recalcularTotales();
    })();
</script>
@endpush
