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
    <label for="choferes-select">Chofer: <small class="text-muted font-weight-normal">(podés elegir varios)</small></label>
    <select name="chofer_ids[]" id="choferes-select" class="form-control" multiple="multiple" style="width:100%;">
        @php
            $choferesTildados = array_map('strval', old('chofer_ids', old('id_chofer') ? [old('id_chofer')] : []));
        @endphp
        @foreach($choferes as $chofer)
            <option value="{{ $chofer->id }}" {{ in_array((string) $chofer->id, $choferesTildados) ? 'selected' : '' }}>
                {{ trim($chofer->nombre . ' ' . $chofer->apellido) }} - {{ $chofer->documento }}
            </option>
        @endforeach
    </select>
    {!! Form::hidden('id_chofer', old('id_chofer'), ['id' => 'id_chofer']) !!}
</div>

<!-- Fecha Field -->
<div class="form-group col-sm-3">
    {!! Form::label('fecha', 'Fecha:') !!}
    {!! Form::date('fecha', old('fecha', now()->format('Y-m-d')), ['class' => 'form-control', 'required' => 'required']) !!}
</div>

@php
    $ordenCargasData = $ordenCargas->map(function ($ordenCarga) {
        return [
            'id' => (string) $ordenCarga->id,
            'camion' => (string) $ordenCarga->id_camion,
            'texto' => 'OC-' . str_pad($ordenCarga->id, 6, '0', STR_PAD_LEFT) . ' - ' . $ordenCarga->destino,
        ];
    })->values();
@endphp
<div id="liquidacion-form-data"
     data-ordenes-carga="{{ $ordenCargasData->toJson() }}"
     data-old-flete="{{ collect(old('flete', []))->toJson() }}"
     data-old-orden-carga="{{ collect(old('orden_carga', []))->toJson() }}"
     style="display:none;"></div>

<!-- FLETE (uno por cada chapa tildada en Camión) -->
<div class="col-sm-12">
    <hr>
    <h5>Flete <small class="text-muted font-weight-normal">(un bloque por cada chapa tildada, Tramo y Valor obligatorios)</small></h5>
    <small class="text-muted d-block mb-2">
        Diferencia negativa: se genera solo un Descuento por "Faltante de Carga" para esa chapa, con Diferencia + (Tolerancia &times; Precio).
        Tolerancia y Precio Recargo se cargan por defecto desde <a href="{{ route('parametrizaciones.edit') }}" target="_blank">Parametrizaciones</a>, pero pueden ajustarse por chapa.
    </small>

    <p class="text-muted mb-0" id="flete-blocks-empty-hint">Tildá una o varias chapas en "Camión" para cargar su Flete.</p>
    <div id="flete-blocks"></div>
</div>

<template id="flete-block-template">
    <div class="flete-bloque border rounded p-2 mb-3" data-flete-bloque>
        <h6 class="mb-2">Flete <span class="text-primary" data-role="chapa-heading"></span></h6>
        <div class="form-row">
            <div class="form-group col-sm-2">
                <label>Fecha</label>
                <input type="date" class="form-control" data-role="fecha" name="flete[__CAMION_ID__][fecha]">
            </div>
            <div class="form-group col-sm-3">
                <label>Orden de Carga</label>
                <select class="form-control" data-role="orden-carga" name="orden_carga[__CAMION_ID__]">
                    <option value="">Seleccione una orden de carga</option>
                </select>
            </div>
            <div class="form-group col-sm-3">
                <label>Tramo</label>
                <input type="text" class="form-control" data-role="tramo" name="flete[__CAMION_ID__][tramo]" placeholder="Origen a destino">
            </div>
            <div class="form-group col-sm-1">
                <label>Kg Origen</label>
                <input type="number" step="0.01" class="form-control" data-role="kg-origen" name="flete[__CAMION_ID__][kg_origen]">
            </div>
            <div class="form-group col-sm-1">
                <label>Kg Destino</label>
                <input type="number" step="0.01" class="form-control" data-role="kg-destino" name="flete[__CAMION_ID__][kg_destino]">
            </div>
            <div class="form-group col-sm-2">
                <label>Diferencia</label>
                <input type="text" class="form-control" data-role="diferencia" readonly tabindex="-1">
                <input type="hidden" data-role="diferencia-raw" name="flete[__CAMION_ID__][diferencia]">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-sm-2">
                <label>Precio</label>
                <input type="number" step="0.01" class="form-control" data-role="precio" name="flete[__CAMION_ID__][precio]">
            </div>
            <div class="form-group col-sm-2">
                <label>Valor</label>
                <input type="number" step="0.01" class="form-control liquidacion-credito" data-role="valor" name="flete[__CAMION_ID__][valor]">
            </div>
            <div class="form-group col-sm-2">
                <label>Tolerancia (Kg)</label>
                <input type="number" step="0.01" class="form-control" data-role="recargo-tolerancia" name="flete[__CAMION_ID__][recargo_tolerancia]" value="{{ $parametrizacion->recargo_tolerancia }}">
            </div>
            <div class="form-group col-sm-2">
                <label>Precio Recargo</label>
                <input type="number" step="0.01" class="form-control" data-role="recargo-precio" name="flete[__CAMION_ID__][recargo_precio]" value="{{ $parametrizacion->recargo_precio }}">
            </div>
            <div class="form-group col-sm-4">
                <label>Recargo (Faltante de Carga) <i class="fas fa-lock fa-xs text-muted" title="Se calcula automaticamente"></i></label>
                <input type="text" class="form-control bg-light" data-role="recargo-preview" readonly tabindex="-1" placeholder="Sin recargo">
                <input type="hidden" data-role="descuento-fecha" name="descuento_auto[__CAMION_ID__][fecha]">
                <input type="hidden" class="liquidacion-debito" data-role="descuento-valor" name="descuento_auto[__CAMION_ID__][valor]">
            </div>
        </div>
    </div>
</template>

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
                {{-- "Faltante de Carga" no esta en esta lista a proposito: se calcula solo, por
                     chapa, desde el Recargo de cada bloque de Flete (ver actualizarRecargo() mas
                     abajo). Ofrecerlo tambien aca duplicaba el descuento si se cargaba a mano. --}}
                @foreach(['Multa', 'Anticipo', 'Otro'] as $concepto)
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
        <p class="text-muted mb-0 mt-2 d-none" id="viaticos-empty-hint">Los choferes tildados no tienen viáticos disponibles.</p>
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
    <small class="text-muted d-block mb-2">El monto se multiplica por la cantidad de fletes tildados (una chapa tildada en Camión = un flete).</small>

    <div class="form-row">
        <div class="form-group col-sm-3">
            <label>Fecha</label>
            <input type="date" name="gasto_administrativo[fecha]" class="form-control" value="{{ old('gasto_administrativo.fecha') }}">
        </div>
        <div class="form-group col-sm-3">
            <label>Concepto</label>
            <select name="gasto_administrativo[concepto]" class="form-control">
                <option value="">Seleccione un concepto</option>
                @foreach(['Administración', 'Comisión', 'Otro'] as $concepto)
                    <option value="{{ $concepto }}" {{ old('gasto_administrativo.concepto') === $concepto ? 'selected' : '' }}>{{ $concepto }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-sm-3">
            <label>Monto (por flete)</label>
            <select name="gasto_administrativo[monto_unitario]" id="gasto-administrativo-monto-unitario" class="form-control">
                <option value="">Seleccione un monto</option>
                @foreach([25000, 30000, 50000] as $monto)
                    <option value="{{ $monto }}" {{ (string) old('gasto_administrativo.monto_unitario') === (string) $monto ? 'selected' : '' }}>{{ number_format($monto, 0, ',', '.') }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-sm-3">
            <label>Valor total <i class="fas fa-lock fa-xs text-muted" title="Se calcula automaticamente: monto x cantidad de fletes"></i></label>
            <input type="text" class="form-control bg-light" id="gasto-administrativo-valor-preview" readonly tabindex="-1" placeholder="Sin fletes">
            <input type="hidden" name="gasto_administrativo[valor]" id="gasto-administrativo-valor" class="liquidacion-debito">
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

    /* Camion/Chofer (select2): tildar opciones con color primario, bien visible */
    #camiones-select + .select2-container .select2-selection--multiple,
    #choferes-select + .select2-container .select2-selection--multiple {
        min-height: calc(1.5em + .6rem + 2px);
        border: 1px solid #ced4da;
    }
    #camiones-select + .select2-container .select2-selection__choice,
    #choferes-select + .select2-container .select2-selection__choice {
        background-color: #007bff;
        border: 1px solid #007bff;
        color: #fff;
        border-radius: .25rem;
        padding: 1px 8px;
    }
    #camiones-select + .select2-container .select2-selection__choice__remove,
    #choferes-select + .select2-container .select2-selection__choice__remove {
        color: #fff;
        margin-right: 6px;
        font-weight: bold;
    }
    #camiones-select + .select2-container .select2-selection__choice__remove:hover,
    #choferes-select + .select2-container .select2-selection__choice__remove:hover {
        color: #f8d7da;
    }
    #camiones-select + .select2-container.select2-container--focus .select2-selection--multiple,
    #choferes-select + .select2-container.select2-container--focus .select2-selection--multiple {
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
            if (event.target.classList.contains('liquidacion-debito-checkbox')) {
                recalcularTotales();
            }
        });

        // --- Calculo asistido de Flete ---
        var fechaCabecera = document.querySelector('input[name="fecha"]');
        var fleteBlocksContainer = document.getElementById('flete-blocks');
        var fleteBlockTemplate = document.getElementById('flete-block-template');
        var fleteBlocksEmptyHint = document.getElementById('flete-blocks-empty-hint');

        var formData = document.getElementById('liquidacion-form-data').dataset;
        var ordenCargasOriginales = JSON.parse(formData.ordenesCarga || '[]');
        var oldFleteData = JSON.parse(formData.oldFlete || '{}');
        var oldOrdenCargaData = JSON.parse(formData.oldOrdenCarga || '{}');

        function actualizarHintBloquesFlete() {
            if (fleteBlocksEmptyHint) {
                fleteBlocksEmptyHint.classList.toggle('d-none', fleteBlocksContainer.children.length > 0);
            }
        }

        // Diferencia = KgDestino - KgOrigen. Cuando la Diferencia es negativa (faltante de carga),
        // Perdida = -Diferencia; si Perdida supera la Tolerancia, Recargo = (Perdida - Tolerancia) x Precio.
        // Si la Perdida no supera la Tolerancia, no hay recargo. El recargo no tiene campo propio
        // "guardable": se vuelca directo a los hidden descuento_auto[<camion>][fecha|valor] de este
        // bloque, que el backend guarda como su propia linea de Descuento "Faltante de Carga".
        function inicializarCalculoBloque(bloque) {
            var kgOrigen = bloque.querySelector('[data-role="kg-origen"]');
            var kgDestino = bloque.querySelector('[data-role="kg-destino"]');
            var diferencia = bloque.querySelector('[data-role="diferencia"]');
            var diferenciaRaw = bloque.querySelector('[data-role="diferencia-raw"]');
            var precio = bloque.querySelector('[data-role="precio"]');
            var valor = bloque.querySelector('[data-role="valor"]');
            var recargoTolerancia = bloque.querySelector('[data-role="recargo-tolerancia"]');
            var recargoPrecio = bloque.querySelector('[data-role="recargo-precio"]');
            var recargoPreview = bloque.querySelector('[data-role="recargo-preview"]');
            var descuentoFecha = bloque.querySelector('[data-role="descuento-fecha"]');
            var descuentoValor = bloque.querySelector('[data-role="descuento-valor"]');
            var bloqueFecha = bloque.querySelector('[data-role="fecha"]');

            function actualizarRecargo(valorDiferencia) {
                var tolerancia = parseFloat(recargoTolerancia.value) || 0;
                var precioRecargo = parseFloat(recargoPrecio.value) || 0;
                var perdida = (valorDiferencia !== null && valorDiferencia < 0) ? -valorDiferencia : 0;
                var valorRecargo = (perdida > tolerancia)
                    ? ((perdida - tolerancia) * precioRecargo)
                    : null;

                if (valorRecargo !== null) {
                    recargoPreview.value = formatoNumero(valorRecargo);
                    descuentoValor.value = valorRecargo;
                    descuentoFecha.value = bloqueFecha.value || (fechaCabecera ? fechaCabecera.value : '');
                } else {
                    recargoPreview.value = '';
                    descuentoValor.value = '';
                    descuentoFecha.value = '';
                }

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

            actualizarDiferencia();
        }

        function bloqueTieneDatos(bloque) {
            var roles = ['fecha', 'tramo', 'kg-origen', 'kg-destino', 'precio', 'valor'];
            return roles.some(function (rol) {
                var el = bloque.querySelector('[data-role="' + rol + '"]');
                return el && el.value;
            }) || bloque.querySelector('[data-role="orden-carga"]').value;
        }

        function crearBloqueFlete(camionId, chapaTexto) {
            var fragmento = fleteBlockTemplate.content.cloneNode(true);
            var bloque = fragmento.querySelector('[data-flete-bloque]');
            bloque.dataset.camionId = camionId;

            bloque.querySelectorAll('[name]').forEach(function (el) {
                el.name = el.name.replace(/__CAMION_ID__/g, camionId);
            });

            bloque.querySelector('[data-role="chapa-heading"]').textContent = chapaTexto ? '— ' + chapaTexto : '';

            var ordenCargaSelect = bloque.querySelector('[data-role="orden-carga"]');
            ordenCargasOriginales.forEach(function (oc) {
                if (oc.camion === camionId) {
                    ordenCargaSelect.appendChild(new Option(oc.texto, oc.id));
                }
            });

            var datosViejos = oldFleteData[camionId];
            if (datosViejos) {
                ['fecha', 'tramo', 'kg_origen', 'kg_destino', 'precio', 'recargo_tolerancia', 'recargo_precio'].forEach(function (campo) {
                    var rol = campo.replace(/_/g, '-');
                    var el = bloque.querySelector('[data-role="' + rol + '"]');
                    if (el && datosViejos[campo]) {
                        el.value = datosViejos[campo];
                    }
                });
            }
            if (oldOrdenCargaData[camionId]) {
                ordenCargaSelect.value = oldOrdenCargaData[camionId];
            }

            fleteBlocksContainer.appendChild(bloque);
            inicializarCalculoBloque(bloque);
            actualizarHintBloquesFlete();
        }

        function eliminarBloqueFlete(camionId) {
            var bloque = fleteBlocksContainer.querySelector('[data-camion-id="' + camionId + '"]');
            if (bloque) {
                bloque.remove();
            }
            actualizarHintBloquesFlete();
        }

        // Agrega un bloque de Flete por cada chapa recien tildada y quita el de las destildadas,
        // sin tocar los bloques de chapas que siguen tildadas (no se pierden datos ya cargados).
        function sincronizarBloquesFlete() {
            var seleccionados = obtenerCamionesSeleccionados();
            var renderizados = Array.prototype.map.call(
                fleteBlocksContainer.querySelectorAll('[data-flete-bloque]'),
                function (b) { return b.dataset.camionId; }
            );

            renderizados
                .filter(function (id) { return seleccionados.indexOf(id) === -1; })
                .forEach(eliminarBloqueFlete);

            seleccionados
                .filter(function (id) { return renderizados.indexOf(id) === -1; })
                .forEach(function (id) {
                    var camion = camionesOriginales.find(function (c) { return c.value === id; });
                    crearBloqueFlete(id, camion ? camion.text : '');
                });

            actualizarGastoAdministrativo();
        }

        // --- Gastos Administrativos: Valor total = Monto (por flete) x cantidad de fletes tildados ---
        var gastoAdminMontoSelect = document.getElementById('gasto-administrativo-monto-unitario');
        var gastoAdminValorHidden = document.getElementById('gasto-administrativo-valor');
        var gastoAdminValorPreview = document.getElementById('gasto-administrativo-valor-preview');

        function actualizarGastoAdministrativo() {
            var montoUnitario = parseFloat(gastoAdminMontoSelect.value) || 0;
            var cantidadFletes = fleteBlocksContainer.querySelectorAll('[data-flete-bloque]').length;

            if (montoUnitario && cantidadFletes) {
                var total = montoUnitario * cantidadFletes;
                gastoAdminValorHidden.value = total;
                gastoAdminValorPreview.value = formatoNumero(total) + ' (' + formatoNumero(montoUnitario) + ' x ' + cantidadFletes + ' flete' + (cantidadFletes === 1 ? '' : 's') + ')';
            } else {
                gastoAdminValorHidden.value = '';
                gastoAdminValorPreview.value = '';
            }

            recalcularTotales();
        }

        gastoAdminMontoSelect.addEventListener('change', actualizarGastoAdministrativo);

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

        // --- Filtros: Propietario -> Camion; Camion/Chofer -> Combustible/Viatico (checkboxes) ---
        // Acepta varios valores validos a la vez (Camion y Chofer tildan varias chapas/choferes,
        // y deben mostrar filas de cualquiera de los tildados).
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
        var idChoferHidden = document.getElementById('id_chofer');

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
        // es la que se guarda en la Liquidacion (y, si su bloque de Flete no tiene Orden de
        // Carga, se usa la primera que si tenga, ver store()). Las demas chapas tildadas
        // amplian que Vales de Combustible se muestran/pueden tildar, y cada una tiene su
        // propio bloque de Flete (ver sincronizarBloquesFlete).
        function obtenerCamionesSeleccionados() {
            return $camionSelect.val() || [];
        }

        function actualizarCamionPrincipalYFiltros() {
            var seleccionados = obtenerCamionesSeleccionados();

            idCamionHidden.value = seleccionados[0] || '';

            filtrarFilasPorAtributoMultiple('vales-list', 'vales-empty-hint', 'camion', seleccionados);
            recalcularTotales();
        }

        // Reconstruye las opciones del select2 con solo las chapas del propietario elegido,
        // preservando las que ya estaban tildadas y siguen siendo validas. Si el cambio de
        // Propietario va a destildar (y por lo tanto borrar el bloque de Flete de) alguna
        // chapa que ya tiene datos cargados, pide confirmacion antes de aplicar el filtro.
        var clienteAnterior = clienteSelect.value;

        function filtrarCamionesPorCliente(valorFiltro) {
            var seleccionActual = obtenerCamionesSeleccionados();

            var idsAEliminar = camionesOriginales
                .filter(function (camion) { return valorFiltro && camion.cliente !== valorFiltro; })
                .map(function (camion) { return camion.value; })
                .filter(function (id) { return seleccionActual.indexOf(id) !== -1; });

            var hayDatosEnRiesgo = idsAEliminar.some(function (id) {
                var bloque = fleteBlocksContainer.querySelector('[data-camion-id="' + id + '"]');
                return bloque && bloqueTieneDatos(bloque);
            });

            if (hayDatosEnRiesgo && !confirm('Cambiar el Propietario va a quitar del formulario el Flete ya cargado para alguna de las chapas tildadas. ¿Querés continuar?')) {
                clienteSelect.value = clienteAnterior;
                return;
            }

            clienteAnterior = valorFiltro;

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

        $camionSelect.on('change', function () {
            actualizarCamionPrincipalYFiltros();
            sincronizarBloquesFlete();
        });

        // --- Chofer: select2 multiple ---
        var $choferSelect = $('#choferes-select');

        $choferSelect.select2({
            width: '100%',
            placeholder: 'Seleccione uno o varios choferes',
            allowClear: true
        });

        // El primer chofer tildado (en orden de la lista) queda como "chofer principal":
        // es el que se guarda en la Liquidacion (id_chofer). Los demas choferes tildados
        // amplian que Viaticos se muestran/pueden tildar (cualquiera de los tildados).
        function obtenerChoferesSeleccionados() {
            return $choferSelect.val() || [];
        }

        function actualizarChoferPrincipalYFiltros() {
            var seleccionados = obtenerChoferesSeleccionados();

            idChoferHidden.value = seleccionados[0] || '';

            filtrarFilasPorAtributoMultiple('viaticos-list', 'viaticos-empty-hint', 'chofer', seleccionados);
            recalcularTotales();
        }

        $choferSelect.on('change', function () {
            actualizarChoferPrincipalYFiltros();
        });

        // --- Validaciones que reflejan en el navegador las reglas de CreateLiquidacionRequest
        // (Flete/Descuento/Gasto Administrativo), para que el usuario nunca llegue a ver un
        // error del backend en el uso normal del formulario. El backend se deja como respaldo
        // ante datos manipulados o JS deshabilitado.
        //
        // Flete es obligatorio SIEMPRE (Tramo y Valor) para cada chapa tildada en Camión, sin
        // excepcion: no existe la posibilidad de tildar una chapa y dejar su bloque vacio.
        function validarBloquesFlete() {
            var bloques = fleteBlocksContainer.querySelectorAll('[data-flete-bloque]');

            for (var i = 0; i < bloques.length; i++) {
                var bloque = bloques[i];
                var tramo = bloque.querySelector('[data-role="tramo"]');
                var valor = bloque.querySelector('[data-role="valor"]');

                if (!tramo.value || !valor.value) {
                    var chapaHeading = bloque.querySelector('[data-role="chapa-heading"]');
                    var chapa = chapaHeading ? chapaHeading.textContent.replace(/^—\s*/, '') : '';
                    return 'Flete' + (chapa ? ' (' + chapa + ')' : '') + ': completá Tramo y Valor.';
                }
            }

            return null;
        }

        function validarDescuentoManual() {
            if (!descuentoCheckbox.checked) {
                return null;
            }

            var fecha = descuentoCampos.querySelector('input[name="descuento[fecha]"]');
            var concepto = descuentoCampos.querySelector('select[name="descuento[concepto]"]');
            var valor = descuentoCampos.querySelector('input[name="descuento[valor]"]');

            if (!fecha.value && !concepto.value && !valor.value) {
                return null;
            }

            if (!concepto.value || !valor.value) {
                return 'Descuento: completá Concepto y Valor (o destildá "Incluir descuento").';
            }

            return null;
        }

        function validarGastoAdministrativo() {
            var fecha = document.querySelector('input[name="gasto_administrativo[fecha]"]');
            var concepto = document.querySelector('select[name="gasto_administrativo[concepto]"]');

            if (!fecha.value && !concepto.value && !gastoAdminMontoSelect.value) {
                return null;
            }

            if (!concepto.value || !gastoAdminMontoSelect.value) {
                return 'Gastos Administrativos: completá Concepto y Monto.';
            }

            return null;
        }

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

                if (!idChoferHidden.value) {
                    event.preventDefault();
                    alert('Seleccioná al menos un chofer.');
                    return;
                }

                var errorFlete = validarBloquesFlete();
                if (errorFlete) {
                    event.preventDefault();
                    alert(errorFlete);
                    return;
                }

                var errorDescuento = validarDescuentoManual();
                if (errorDescuento) {
                    event.preventDefault();
                    alert(errorDescuento);
                    return;
                }

                var errorGasto = validarGastoAdministrativo();
                if (errorGasto) {
                    event.preventDefault();
                    alert(errorGasto);
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

        // Si el formulario se recarga tras un error de validación, reaplicar los
        // filtros para que las selecciones restauradas (Propietario/Camión/Chofer)
        // sigan siendo coherentes entre sí.
        clienteSelect.dispatchEvent(new Event('change'));
        actualizarChoferPrincipalYFiltros();

        recalcularTotales();
    })();
</script>
@endpush
