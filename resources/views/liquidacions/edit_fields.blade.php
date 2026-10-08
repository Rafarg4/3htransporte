{{-- Formulario de EDICION (copia de fields.blade.php, que es el de alta y no se toca).
     $dato() reemplaza a old(): devuelve lo que se mando si se vuelve de un error de validacion,
     y si no los datos guardados de la liquidacion ($datosEdicion, ver getDatosEdicion()). --}}
@php
    $datosFormulario = session()->hasOldInput() ? old() : $datosEdicion;
    $dato = function ($clave, $default = null) use ($datosFormulario) {
        return data_get($datosFormulario, $clave, $default);
    };
@endphp

<!-- Propietario Field -->
<div class="form-group col-sm-3">
    {!! Form::label('id_cliente', 'Propietario:') !!}
    {!! Form::select('id_cliente', $clientes, $dato('id_cliente'), ['class' => 'form-control', 'id' => 'id_cliente', 'placeholder' => 'Seleccione un propietario', 'required' => 'required']) !!}
</div>

<!-- Camion Field -->
<div class="form-group col-sm-3">
    <label for="camiones-select">Camión: <small class="text-muted font-weight-normal">(podés elegir varias)</small></label>
    <select name="camion_ids[]" id="camiones-select" class="form-control" multiple="multiple" style="width:100%;">
        @php
            $camionesTildados = array_map('strval', $dato('camion_ids', $dato('id_camion') ? [$dato('id_camion')] : []));
        @endphp
        @foreach($camions as $camion)
            <option value="{{ $camion->id }}" data-cliente="{{ $camion->id_cliente }}" {{ in_array((string) $camion->id, $camionesTildados) ? 'selected' : '' }}>
                {{ $camion->chapa }}
            </option>
        @endforeach
    </select>
    {!! Form::hidden('id_camion', $dato('id_camion'), ['id' => 'id_camion']) !!}
</div>

<!-- Chofer Field -->
<div class="form-group col-sm-3">
    <label for="choferes-select">Chofer: <small class="text-muted font-weight-normal">(podés elegir varios)</small></label>
    <select name="chofer_ids[]" id="choferes-select" class="form-control" multiple="multiple" style="width:100%;">
        @php
            $choferesTildados = array_map('strval', $dato('chofer_ids', $dato('id_chofer') ? [$dato('id_chofer')] : []));
        @endphp
        @foreach($choferes as $chofer)
            <option value="{{ $chofer->id }}" {{ in_array((string) $chofer->id, $choferesTildados) ? 'selected' : '' }}>
                {{ trim($chofer->nombre . ' ' . $chofer->apellido) }} - {{ $chofer->documento }}
            </option>
        @endforeach
    </select>
    {!! Form::hidden('id_chofer', $dato('id_chofer'), ['id' => 'id_chofer']) !!}
</div>

<!-- Fecha Field -->
<div class="form-group col-sm-3">
    {!! Form::label('fecha', 'Fecha:') !!}
    {!! Form::date('fecha', $dato('fecha', now()->format('Y-m-d')), ['class' => 'form-control', 'required' => 'required']) !!}
</div>

<!-- Moneda Field: Precio/Valor/Precio Recargo del flete se cargan en la moneda elegida (al guardar
     se pasan a guaranies, ver guardarDetalle()). Si se mantiene la moneda con la que se
     guardo la liquidacion, se siguen usando las cotizaciones guardadas en ella (monto_moneda y
     cotizacion_usd); si se cambia, las vigentes (igual que update() en el controlador). -->
@php
    $monedaOriginal = $liquidacion->moneda;
    $monedaOriginalEnLista = $monedaOriginal === 'PYG' || $monedas->contains('tipo_moneda', $monedaOriginal);
@endphp
<div class="form-group col-sm-3">
    <label for="moneda-select">Moneda:</label>
    <select name="moneda" id="moneda-select" class="form-control" required
            data-cotizacion-usd="{{ $cotizacionUsd }}" data-cotizacion-pyg="{{ $cotizacionPyg }}"
            data-moneda-original="{{ $monedaOriginal }}" data-cotizacion-items-original="{{ $liquidacion->cotizacion_usd }}">
        <option value="" {{ $dato('moneda') ? '' : 'selected' }}>Seleccione una moneda</option>
        <option value="PYG" data-cotizacion="1" {{ $dato('moneda') === 'PYG' ? 'selected' : '' }}>
            Guaraníes{{ $monedaGuaranies ? ' (cotización ' . $monedaGuaranies->monto . ')' : '' }}
        </option>
        @foreach($monedas as $moneda)
            @php
                $esMonedaOriginal = $moneda->tipo_moneda === $monedaOriginal && $liquidacion->monto_moneda > 0;
                $cotizacionOpcion = $esMonedaOriginal ? $liquidacion->monto_moneda : $moneda->cotizacion;
            @endphp
            <option value="{{ $moneda->tipo_moneda }}" data-cotizacion="{{ $cotizacionOpcion }}" {{ $dato('moneda') === $moneda->tipo_moneda ? 'selected' : '' }}>
                {{ $moneda->tipo_moneda }} (cotización {{ $esMonedaOriginal ? $liquidacion->monto_moneda : $moneda->monto }})
            </option>
        @endforeach
        @if($monedaOriginal && !$monedaOriginalEnLista && $liquidacion->monto_moneda > 0)
            <option value="{{ $monedaOriginal }}" data-cotizacion="{{ $liquidacion->monto_moneda }}" {{ $dato('moneda') === $monedaOriginal ? 'selected' : '' }}>
                {{ $monedaOriginal }} (cotización {{ $liquidacion->monto_moneda }})
            </option>
        @endif
    </select>
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
     data-old-flete="{{ collect($dato('flete', []))->toJson() }}"
     data-old-orden-carga="{{ collect($dato('orden_carga', []))->toJson() }}"
     style="display:none;"></div>

<!-- FLETE (uno por cada chapa tildada en Camión) -->
<div class="col-sm-12">
    <hr>
    <h5>Flete <small class="text-muted font-weight-normal">(un bloque por cada chapa tildada, Tramo y Valor obligatorios; usá "Otro flete" si una chapa hizo más de un viaje)</small></h5>
    <small class="text-muted d-block mb-2">
        Diferencia negativa: se genera solo un Descuento por "Faltante de Carga" para esa chapa, con Diferencia + (Tolerancia &times; Precio).
        Tolerancia y Precio Recargo se cargan por defecto desde <a href="{{ route('parametrizaciones.edit') }}" target="_blank">Parametrizaciones</a>, pero pueden ajustarse por chapa.
    </small>

    <p class="text-muted mb-0" id="flete-blocks-empty-hint">Tildá una o varias chapas en "Camión" para cargar su Flete.</p>
    <div id="flete-blocks"></div>
</div>

<template id="flete-block-template">
    <div class="flete-bloque border rounded p-2 mb-3" data-flete-bloque>
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="mb-0">Flete <span class="text-primary" data-role="chapa-heading"></span></h6>
            <div>
                <button type="button" class="btn btn-sm btn-outline-primary" data-role="agregar-flete" title="Agregar otro flete para esta chapa">
                    <i class="fas fa-plus"></i> Otro flete
                </button>
                <button type="button" class="btn btn-sm btn-outline-danger" data-role="eliminar-bloque" title="Eliminar este flete">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
        <input type="hidden" data-role="id-camion" name="flete[__BLOQUE_ID__][id_camion]">
        <div class="form-row">
            <div class="form-group col-sm-2">
                <label>Fecha</label>
                <input type="date" class="form-control" data-role="fecha" name="flete[__BLOQUE_ID__][fecha]">
            </div>
            <div class="form-group col-sm-3">
                <label>Orden de Carga</label>
                <select class="form-control" data-role="orden-carga" name="orden_carga[__BLOQUE_ID__]">
                    <option value="">Seleccione una orden de carga</option>
                </select>
            </div>
            <div class="form-group col-sm-3">
                <label>Tramo</label>
                <input type="text" class="form-control" data-role="tramo" name="flete[__BLOQUE_ID__][tramo]" placeholder="Origen a destino">
            </div>
            <div class="form-group col-sm-1">
                <label>Kg Origen</label>
                <input type="number" step="0.01" class="form-control" data-role="kg-origen" name="flete[__BLOQUE_ID__][kg_origen]">
            </div>
            <div class="form-group col-sm-1">
                <label>Kg Destino</label>
                <input type="number" step="0.01" class="form-control" data-role="kg-destino" name="flete[__BLOQUE_ID__][kg_destino]">
            </div>
            <div class="form-group col-sm-2">
                <label>Diferencia</label>
                <input type="text" class="form-control" data-role="diferencia" readonly tabindex="-1">
                <input type="hidden" data-role="diferencia-raw" name="flete[__BLOQUE_ID__][diferencia]">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-sm-2">
                <label>Precio <small class="text-muted" data-role="moneda-flete"></small></label>
                <input type="number" step="0.01" class="form-control" data-role="precio" name="flete[__BLOQUE_ID__][precio]">
                <small class="text-muted d-none" data-equivalente-de="precio"></small>
            </div>
            <div class="form-group col-sm-2">
                <label>Valor <small class="text-muted" data-role="moneda-flete"></small></label>
                <input type="number" step="0.01" class="form-control liquidacion-credito" data-role="valor" name="flete[__BLOQUE_ID__][valor]">
                <small class="text-muted d-none" data-equivalente-de="valor"></small>
            </div>
            <div class="form-group col-sm-2">
                <label>Tolerancia (Kg)</label>
                <input type="number" step="0.01" class="form-control" data-role="recargo-tolerancia" name="flete[__BLOQUE_ID__][recargo_tolerancia]" value="{{ $parametrizacion->recargo_tolerancia }}">
            </div>
            <div class="form-group col-sm-2">
                <label>Precio Recargo <small class="text-muted" data-role="moneda-flete"></small></label>
                <input type="number" step="0.01" class="form-control" data-role="recargo-precio" name="flete[__BLOQUE_ID__][recargo_precio]" value="{{ $parametrizacion->recargo_precio }}">
                <small class="text-muted d-none" data-equivalente-de="recargo-precio"></small>
            </div>
            <div class="form-group col-sm-4">
                <label>Recargo (Faltante de Carga) <i class="fas fa-lock fa-xs text-muted" title="Se calcula automaticamente"></i></label>
                <input type="text" class="form-control bg-light" data-role="recargo-preview" readonly tabindex="-1" placeholder="Sin recargo">
                <input type="hidden" data-role="descuento-fecha" name="descuento_auto[__BLOQUE_ID__][fecha]">
                <input type="hidden" class="liquidacion-debito" data-role="descuento-valor" name="descuento_auto[__BLOQUE_ID__][valor]">
            </div>
        </div>
    </div>
</template>

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
                        <th>Moneda</th>
                        <th class="text-right">Monto</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($viaticosDisponibles as $viatico)
                        @php
                            // Se manda el monto tal como se cargo + su moneda; el JS lo pasa a Gs. con la
                            // cotizacion que corresponde a la moneda elegida (ver valorEnGuaranies()).
                            $viaticoEnUsd = $viatico->tipo_moneda === 'USD';
                            $viaticoMonedaOriginal = $viaticoEnUsd ? 'USD' : 'PYG';
                            $viaticoOriginal = $viaticoEnUsd
                                ? number_format((float) $viatico->monto, 2, ',', '.') . ' USD'
                                : number_format((float) $viatico->monto, 0, ',', '.') . ' Gs.';
                        @endphp
                        <tr class="liquidacion-select-row" data-chofer="{{ $viatico->id_chofer }}">
                            <td>
                                <input type="checkbox"
                                       class="liquidacion-debito-checkbox"
                                       name="viatico_ids[]"
                                       value="{{ $viatico->id }}"
                                       data-valor-original="{{ (float) $viatico->monto }}"
                                       data-moneda-original="{{ $viaticoMonedaOriginal }}"
                                       @if($viaticoEnUsd && !$cotizacionUsd && !$cotizacionPyg) disabled title="Falta cargar la cotización en Monedas" @endif
                                       {{ in_array($viatico->id, $dato('viatico_ids', [])) ? 'checked' : '' }}>
                            </td>
                            <td>{{ $viatico->fecha }}</td>
                            <td>{{ $viatico->chofer ? trim($viatico->chofer->nombre . ' ' . $viatico->chofer->apellido) : '-' }}</td>
                            <td>{{ $viatico->descripcion }}</td>
                            <td>{{ $viaticoEnUsd ? 'Dólares' : 'Guaraníes' }}</td>
                            <td class="text-right" data-monto-original="{{ (float) $viatico->monto }}"
                                data-moneda-original="{{ $viaticoMonedaOriginal }}" data-original="{{ $viaticoOriginal }}">{{ $viaticoOriginal }}</td>
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
                        <th>Moneda</th>
                        <th class="text-right">Precio</th>
                        <th class="text-right">Valor</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($valeCombustiblesDisponibles as $vale)
                        @php
                            // Igual que en Viatico: monto original + moneda, el JS lo pasa a Gs.
                            $valeEnUsd = $vale->tipo_moneda === 'USD';
                            $valeMonedaOriginal = $valeEnUsd ? 'USD' : 'PYG';
                            $valeValorOriginal = (float) $vale->litros * (float) $vale->importe;
                            $valeImporteTexto = $valeEnUsd
                                ? number_format((float) $vale->importe, 2, ',', '.') . ' USD'
                                : number_format((float) $vale->importe, 0, ',', '.') . ' Gs.';
                            $valeValorTexto = $valeEnUsd
                                ? number_format($valeValorOriginal, 2, ',', '.') . ' USD'
                                : number_format($valeValorOriginal, 0, ',', '.') . ' Gs.';
                        @endphp
                        <tr class="liquidacion-select-row" data-camion="{{ $vale->id_camion }}">
                            <td>
                                <input type="checkbox"
                                       class="liquidacion-debito-checkbox"
                                       name="vale_combustible_ids[]"
                                       value="{{ $vale->id }}"
                                       data-valor-original="{{ $valeValorOriginal }}"
                                       data-moneda-original="{{ $valeMonedaOriginal }}"
                                       @if($valeEnUsd && !$cotizacionUsd && !$cotizacionPyg) disabled title="Falta cargar la cotización en Monedas" @endif
                                       {{ in_array($vale->id, $dato('vale_combustible_ids', [])) ? 'checked' : '' }}>
                            </td>
                            <td>{{ $vale->vigencia_desde }}</td>
                            <td>{{ $vale->camion->chapa ?? '-' }}</td>
                            <td>{{ $vale->nombre_estacion }}</td>
                            <td>{{ $vale->litros }} L</td>
                            <td>{{ $valeEnUsd ? 'Dólares' : 'Guaraníes' }}</td>
                            <td class="text-right" data-monto-original="{{ (float) $vale->importe }}"
                                data-moneda-original="{{ $valeMonedaOriginal }}" data-original="{{ $valeImporteTexto }}">{{ $valeImporteTexto }}</td>
                            <td class="text-right" data-monto-original="{{ $valeValorOriginal }}"
                                data-moneda-original="{{ $valeMonedaOriginal }}" data-original="{{ $valeValorTexto }}">{{ $valeValorTexto }}</td>
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
    <small class="text-muted d-block mb-2">El monto se multiplica por la cantidad de fletes cargados (una chapa puede tener más de uno).</small>

    <div class="form-row">
        <div class="form-group col-sm-3">
            <label>Fecha</label>
            <input type="date" name="gasto_administrativo[fecha]" class="form-control" value="{{ $dato('gasto_administrativo.fecha') }}">
        </div>
        <div class="form-group col-sm-3">
            <label>Concepto</label>
            <select name="gasto_administrativo[concepto]" class="form-control">
                <option value="">Seleccione un concepto</option>
                @php
                    // Si el concepto/monto guardado no esta entre las opciones fijas, se agrega para no perderlo.
                    $conceptosGasto = collect(['Administración', 'Comisión', 'Otro'])
                        ->push($dato('gasto_administrativo.concepto'))->filter()->unique()->values();
                    $montosGasto = collect([25000, 30000, 50000])
                        ->push($dato('gasto_administrativo.monto_unitario'))->filter()
                        ->unique(function ($monto) { return (string) $monto; })->values();
                @endphp
                @foreach($conceptosGasto as $concepto)
                    <option value="{{ $concepto }}" {{ $dato('gasto_administrativo.concepto') === $concepto ? 'selected' : '' }}>{{ $concepto }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-sm-3">
            <label>Monto (por flete)</label>
            <select name="gasto_administrativo[monto_unitario]" id="gasto-administrativo-monto-unitario" class="form-control">
                <option value="">Seleccione un monto</option>
                @foreach($montosGasto as $monto)
                    <option value="{{ $monto }}" data-monto-gs="{{ $monto }}" {{ (string) $dato('gasto_administrativo.monto_unitario') === (string) $monto ? 'selected' : '' }}>{{ number_format($monto, 0, ',', '.') }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-sm-3">
            <label>Valor total <i class="fas fa-lock fa-xs text-muted" title="Se calcula automaticamente: monto x cantidad de fletes"></i></label>
            <input type="text" class="form-control bg-light" id="gasto-administrativo-valor-preview" readonly tabindex="-1" placeholder="Sin fletes">
            <input type="hidden" name="gasto_administrativo[valor]" id="gasto-administrativo-valor" class="liquidacion-debito" data-en-guaranies="1">
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
    <small id="total-moneda-info" class="text-muted d-block text-right mt-1" style="display:none !important;"></small>
</div>

<!-- Facturado: se define recien al confirmar el modal de Guardar -->
{!! Form::hidden('facturado', $dato('facturado'), ['id' => 'facturado-input']) !!}
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
            // Valor del flete y descuento por faltante estan en la moneda elegida: se pasan a Gs.
            // para sumar. El gasto administrativo (data-en-guaranies) ya esta en Gs.
            var factor = factorMoneda();
            var creditos = 0;
            document.querySelectorAll('.liquidacion-credito').forEach(function (input) {
                creditos += (parseFloat(input.value) || 0) * factor;
            });

            var debitos = 0;
            document.querySelectorAll('.liquidacion-debito:not([disabled])').forEach(function (input) {
                debitos += (parseFloat(input.value) || 0) * (input.dataset.enGuaranies ? 1 : factor);
            });

            document.querySelectorAll('.liquidacion-debito-checkbox:checked').forEach(function (checkbox) {
                debitos += valorEnGuaranies(parseFloat(checkbox.dataset.valorOriginal) || 0, checkbox.dataset.monedaOriginal);
            });

            var moneda = monedaSeleccionada();
            var info = document.getElementById('total-moneda-info');

            if (moneda.codigo === 'PYG' || !moneda.cotizacion) {
                document.getElementById('total-creditos').textContent = formatoNumero(creditos);
                document.getElementById('total-debitos').textContent = formatoNumero(debitos);
                document.getElementById('total-saldo').textContent = formatoNumero(creditos - debitos);
                info.textContent = '';
                info.style.setProperty('display', 'none', 'important');
                return;
            }

            document.getElementById('total-creditos').textContent = formatoMoneda(creditos / moneda.cotizacion, moneda.codigo);
            document.getElementById('total-debitos').textContent = formatoMoneda(debitos / moneda.cotizacion, moneda.codigo);
            document.getElementById('total-saldo').textContent = formatoMoneda((creditos - debitos) / moneda.cotizacion, moneda.codigo);
            info.textContent = 'Cotización ' + moneda.codigo + ': ' + new Intl.NumberFormat('es-PY', { maximumFractionDigits: 2 }).format(moneda.cotizacion)
                + ' Gs. — Saldo en guaraníes: ' + formatoNumero(creditos - debitos);
            info.style.setProperty('display', 'block', 'important');
        }

        function monedaSeleccionada() {
            var select = document.getElementById('moneda-select');
            var opcion = select ? select.options[select.selectedIndex] : null;
            return {
                codigo: opcion ? opcion.value : 'PYG',
                cotizacion: opcion ? (parseFloat(opcion.dataset.cotizacion) || 0) : 1
            };
        }

        function formatoMoneda(valor, codigo) {
            return new Intl.NumberFormat('es-PY', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(valor) + ' ' + codigo;
        }

        // Cotizacion de la moneda elegida (1 en guaranies o sin elegir). Precio, Valor y Precio
        // Recargo del flete se cargan en esa moneda; multiplicando por esto quedan en Gs.
        function factorMoneda() {
            var moneda = monedaSeleccionada();
            return (moneda.codigo && moneda.codigo !== 'PYG' && moneda.cotizacion) ? moneda.cotizacion : 1;
        }

        // Monto que ya esta en la moneda elegida (campos del flete, recargo).
        function formatoEnMoneda(valor) {
            return factorMoneda() === 1 ? formatoNumero(valor) : formatoMoneda(valor, monedaSeleccionada().codigo);
        }

        function redondear(valor, decimales) {
            var f = Math.pow(10, decimales);
            return Math.round(valor * f) / f;
        }

        function actualizarEtiquetasMonedaFlete(raiz) {
            var texto = factorMoneda() === 1 ? '(Gs.)' : '(' + monedaSeleccionada().codigo + ')';
            (raiz || document).querySelectorAll('[data-role="moneda-flete"]').forEach(function (el) {
                el.textContent = texto;
            });
        }

        // Al cambiar de moneda, Precio/Valor/Precio Recargo ya cargados se pasan de la moneda
        // anterior a la nueva (el recargo despues se recalcula con el nuevo Precio Recargo).
        // Cada campo recuerda su valor exacto en Gs. mientras no se edite, para que ir y volver
        // de moneda no acumule redondeos (ej. 2.700 Gs. -> 0,4671 USD -> 2.700 Gs.).
        function convertirCamposFlete(factorAnterior, factorNuevo) {
            if (factorAnterior === factorNuevo) {
                return;
            }
            var decimalesValor = factorNuevo === 1 ? 0 : 2;
            [['precio', 4], ['valor', decimalesValor], ['recargo-precio', 4]].forEach(function (par) {
                fleteBlocksContainer.querySelectorAll('[data-role="' + par[0] + '"]').forEach(function (input) {
                    var valor = parseFloat(input.value);
                    if (isNaN(valor)) {
                        return;
                    }
                    var sinEditar = input.dataset.gsExacto && input.dataset.ultimoValor === input.value;
                    var gsExacto = sinEditar ? parseFloat(input.dataset.gsExacto) : valor * factorAnterior;
                    input.value = redondear(gsExacto / factorNuevo, par[1]);
                    input.dataset.gsExacto = gsExacto;
                    input.dataset.ultimoValor = input.value;
                });
            });
        }

        // Monto en guaranies formateado segun la moneda elegida (en Gs. queda como siempre).
        // Los precios unitarios usan 4 decimales porque en USD quedan muy chicos.
        function formatoMonto(valorGs, decimales) {
            var moneda = monedaSeleccionada();
            if (moneda.codigo === 'PYG' || !moneda.cotizacion) {
                return formatoNumero(valorGs);
            }
            var d = decimales || 2;
            return new Intl.NumberFormat('es-PY', { minimumFractionDigits: 2, maximumFractionDigits: d }).format(valorGs / moneda.cotizacion) + ' ' + moneda.codigo;
        }

        // Montos de solo lectura (viaticos, combustible, opciones de gasto administrativo):
        // guardan su valor en Gs. en data-monto-gs y solo se reescribe el texto.
        // Viaticos/vales cargados en USD se pasan a Gs.: si la liquidacion es en USD con la cotizacion
        // del dolar (asi 100 USD vuelve a mostrarse como 100 USD); si no, con la cotizacion cargada
        // como Guaranies en Monedas (o la del dolar si no hay). Mismo criterio que el backend.
        function cotizacionParaItemsEnUsd() {
            var select = document.getElementById('moneda-select');
            var usd = parseFloat(select.dataset.cotizacionUsd) || 0;
            var pyg = parseFloat(select.dataset.cotizacionPyg) || 0;
            var original = parseFloat(select.dataset.cotizacionItemsOriginal) || 0;
            // Misma moneda con la que se guardo: se usa la cotizacion guardada en la liquidacion.
            if (original && select.dataset.monedaOriginal && monedaSeleccionada().codigo === select.dataset.monedaOriginal) {
                return original;
            }
            if (monedaSeleccionada().codigo === 'USD') {
                return usd;
            }
            return pyg || usd;
        }

        function valorEnGuaranies(valorOriginal, monedaOriginal) {
            return monedaOriginal === 'USD' ? valorOriginal * cotizacionParaItemsEnUsd() : valorOriginal;
        }

        function actualizarMontosConvertibles() {
            var codigo = monedaSeleccionada().codigo;

            // Viaticos/vales: sin moneda elegida, o si ya estan en la moneda elegida, se muestran
            // tal cual se cargaron; si no, se convierten y se aclara el monto original.
            document.querySelectorAll('[data-monto-original]').forEach(function (el) {
                if (!codigo || el.dataset.monedaOriginal === codigo) {
                    el.textContent = el.dataset.original;
                    return;
                }
                var montoGs = valorEnGuaranies(parseFloat(el.dataset.montoOriginal) || 0, el.dataset.monedaOriginal);
                var convertido = codigo === 'PYG' ? formatoNumero(montoGs) + ' Gs.' : formatoMonto(montoGs);
                el.textContent = convertido + ' (' + el.dataset.original + ')';
            });

            // Opciones de Gastos Administrativos (siempre en Gs.).
            document.querySelectorAll('[data-monto-gs]').forEach(function (el) {
                el.textContent = formatoMonto(parseFloat(el.dataset.montoGs) || 0);
            });
        }

        // Campos editables del Flete: se cargan en la moneda elegida; si no es guaranies, debajo
        // se muestra el equivalente en Gs. (que es como se guarda).
        function actualizarEquivalente(input, destino) {
            var factor = factorMoneda();
            var valor = parseFloat(input.value);
            var mostrar = factor !== 1 && !isNaN(valor);
            destino.classList.toggle('d-none', !mostrar);
            destino.textContent = mostrar ? '≈ ' + formatoNumero(valor * factor) + ' Gs.' : '';
        }

        var factorMonedaAnterior = factorMoneda();
        document.getElementById('moneda-select').addEventListener('change', function () {
            convertirCamposFlete(factorMonedaAnterior, factorMoneda());
            factorMonedaAnterior = factorMoneda();
            actualizarEtiquetasMonedaFlete();
            actualizarMontosConvertibles();
            document.dispatchEvent(new CustomEvent('liquidacion:moneda-cambiada'));
            actualizarGastoAdministrativo();
            recalcularTotales();
        });

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
                    // Precio Recargo esta en la moneda elegida, asi que el recargo tambien.
                    valorRecargo = factorMoneda() === 1 ? valorRecargo : redondear(valorRecargo, 2);
                    recargoPreview.value = formatoEnMoneda(valorRecargo);
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
                    valor.value = redondear(destino * precioValor, factorMoneda() === 1 ? 0 : 2);
                    recalcularTotales();
                }
                actualizarEquivalentesBloque();
            }

            function actualizarEquivalentesBloque() {
                actualizarEquivalente(precio, bloque.querySelector('[data-equivalente-de="precio"]'), 4);
                actualizarEquivalente(valor, bloque.querySelector('[data-equivalente-de="valor"]'));
                actualizarEquivalente(recargoPrecio, bloque.querySelector('[data-equivalente-de="recargo-precio"]'), 4);
            }

            [precio, valor, recargoPrecio].forEach(function (input) {
                input.addEventListener('input', actualizarEquivalentesBloque);
            });

            document.addEventListener('liquidacion:moneda-cambiada', function () {
                if (bloque.isConnected) {
                    actualizarEquivalentesBloque();
                    actualizarDiferencia();
                }
            });

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
            actualizarEquivalentesBloque();
        }

        function bloqueTieneDatos(bloque) {
            var roles = ['fecha', 'tramo', 'kg-origen', 'kg-destino', 'precio', 'valor'];
            return roles.some(function (rol) {
                var el = bloque.querySelector('[data-role="' + rol + '"]');
                return el && el.value;
            }) || bloque.querySelector('[data-role="orden-carga"]').value;
        }

        // Cada bloque de Flete tiene un id propio (bloqueId), independiente de la chapa: una
        // chapa tildada puede tener varios bloques (boton "Otro flete"). bloqueId es solo la
        // clave usada en flete[<bloqueId>]/orden_carga[<bloqueId>]/descuento_auto[<bloqueId>];
        // la chapa real de cada bloque va en su propio hidden id_camion (ver crearBloqueFlete),
        // que es lo que el backend usa para saber a que camion pertenece.
        function nuevoBloqueId() {
            return 'n' + Date.now().toString(36) + Math.random().toString(36).slice(2, 8);
        }

        function crearBloqueFlete(camionId, chapaTexto, bloqueId) {
            var fragmento = fleteBlockTemplate.content.cloneNode(true);
            var bloque = fragmento.querySelector('[data-flete-bloque]');
            bloque.dataset.camionId = camionId;
            bloque.dataset.bloqueId = bloqueId;

            bloque.querySelectorAll('[name]').forEach(function (el) {
                el.name = el.name.replace(/__BLOQUE_ID__/g, bloqueId);
            });

            bloque.querySelector('[data-role="id-camion"]').value = camionId;
            bloque.querySelector('[data-role="chapa-heading"]').textContent = chapaTexto ? '— ' + chapaTexto : '';

            var ordenCargaSelect = bloque.querySelector('[data-role="orden-carga"]');
            ordenCargasOriginales.forEach(function (oc) {
                if (oc.camion === camionId) {
                    ordenCargaSelect.appendChild(new Option(oc.texto, oc.id));
                }
            });

            var datosViejos = oldFleteData[bloqueId];
            if (datosViejos) {
                ['fecha', 'tramo', 'kg_origen', 'kg_destino', 'precio', 'valor', 'recargo_tolerancia', 'recargo_precio'].forEach(function (campo) {
                    var rol = campo.replace(/_/g, '-');
                    var el = bloque.querySelector('[data-role="' + rol + '"]');
                    if (el && datosViejos[campo] !== null && datosViejos[campo] !== undefined && datosViejos[campo] !== '') {
                        el.value = datosViejos[campo];
                    }
                });
            }
            if (oldOrdenCargaData[bloqueId]) {
                ordenCargaSelect.value = oldOrdenCargaData[bloqueId];
            }

            // El Precio Recargo por defecto (Parametrizaciones) esta en Gs.: en moneda extranjera
            // se convierte, salvo que el bloque ya venga con un valor cargado.
            var recargoPrecioInput = bloque.querySelector('[data-role="recargo-precio"]');
            if (!(datosViejos && datosViejos.recargo_precio) && factorMoneda() !== 1 && recargoPrecioInput.value !== '') {
                var recargoPrecioGs = parseFloat(recargoPrecioInput.value);
                recargoPrecioInput.value = redondear(recargoPrecioGs / factorMoneda(), 4);
                recargoPrecioInput.dataset.gsExacto = recargoPrecioGs;
                recargoPrecioInput.dataset.ultimoValor = recargoPrecioInput.value;
            }
            actualizarEtiquetasMonedaFlete(bloque);

            bloque.querySelector('[data-role="agregar-flete"]').addEventListener('click', function () {
                agregarFleteAdicional(camionId, chapaTexto);
            });

            bloque.querySelector('[data-role="eliminar-bloque"]').addEventListener('click', function () {
                eliminarBloquePorId(bloqueId);
            });

            fleteBlocksContainer.appendChild(bloque);
            inicializarCalculoBloque(bloque);
            actualizarHintBloquesFlete();
        }

        // Crea el/los bloque(s) de una chapa recien tildada. Si esa chapa ya tiene fletes en la
        // liquidacion guardada (o en lo enviado, si se vuelve de un error de validacion), los
        // recrea todos; si no hay datos previos, crea un unico bloque en blanco (el default).
        function crearBloquesParaCamion(camionId, chapaTexto) {
            var bloqueIdsViejos = Object.keys(oldFleteData).filter(function (bloqueId) {
                return oldFleteData[bloqueId].id_camion === camionId;
            });

            if (bloqueIdsViejos.length > 0) {
                bloqueIdsViejos.forEach(function (bloqueId) {
                    crearBloqueFlete(camionId, chapaTexto, bloqueId);
                });
            } else {
                crearBloqueFlete(camionId, chapaTexto, nuevoBloqueId());
            }
        }

        // Boton "Otro flete": agrega un bloque en blanco mas para la misma chapa.
        function agregarFleteAdicional(camionId, chapaTexto) {
            crearBloqueFlete(camionId, chapaTexto, nuevoBloqueId());
            recalcularTotales();
            actualizarGastoAdministrativo();
        }

        // Elimina un unico bloque por su bloqueId (boton papelera). No se puede dejar una chapa
        // tildada sin ningun bloque de Flete (es obligatorio), asi que si es el ultimo bloque de
        // esa chapa se bloquea: para sacarlo hay que destildar la chapa en Camión.
        function eliminarBloquePorId(bloqueId) {
            var bloque = fleteBlocksContainer.querySelector('[data-bloque-id="' + bloqueId + '"]');
            if (!bloque) {
                return;
            }

            var camionId = bloque.dataset.camionId;
            var bloquesDeLaChapa = fleteBlocksContainer.querySelectorAll('[data-camion-id="' + camionId + '"]');

            if (bloquesDeLaChapa.length <= 1) {
                alert('Esta chapa necesita al menos un Flete. Si no querés cargarle Flete, destildala en Camión.');
                return;
            }

            if (!confirm('¿Eliminar este Flete adicional?')) {
                return;
            }

            bloque.remove();
            actualizarHintBloquesFlete();
            recalcularTotales();
            actualizarGastoAdministrativo();
        }

        function eliminarBloquesDeCamion(camionId) {
            fleteBlocksContainer.querySelectorAll('[data-camion-id="' + camionId + '"]').forEach(function (bloque) {
                bloque.remove();
            });
            actualizarHintBloquesFlete();
        }

        // Agrega el/los bloque(s) por defecto de cada chapa recien tildada y quita TODOS los
        // bloques (incluidos los adicionales agregados con "Otro flete") de las destildadas, sin
        // tocar los bloques de chapas que siguen tildadas (no se pierden datos ya cargados).
        function sincronizarBloquesFlete() {
            var seleccionados = obtenerCamionesSeleccionados();
            var camionesRenderizados = Array.prototype.map.call(
                fleteBlocksContainer.querySelectorAll('[data-flete-bloque]'),
                function (b) { return b.dataset.camionId; }
            );

            camionesRenderizados
                .filter(function (id, indice) { return camionesRenderizados.indexOf(id) === indice; })
                .filter(function (id) { return seleccionados.indexOf(id) === -1; })
                .forEach(eliminarBloquesDeCamion);

            seleccionados
                .filter(function (id) { return camionesRenderizados.indexOf(id) === -1; })
                .forEach(function (id) {
                    var camion = camionesOriginales.find(function (c) { return c.value === id; });
                    crearBloquesParaCamion(id, camion ? camion.text : '');
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
                gastoAdminValorPreview.value = formatoMonto(total) + ' (' + formatoMonto(montoUnitario) + ' x ' + cantidadFletes + ' flete' + (cantidadFletes === 1 ? '' : 's') + ')';
            } else {
                gastoAdminValorHidden.value = '';
                gastoAdminValorPreview.value = '';
            }

            recalcularTotales();
        }

        gastoAdminMontoSelect.addEventListener('change', actualizarGastoAdministrativo);

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
                var bloques = fleteBlocksContainer.querySelectorAll('[data-camion-id="' + id + '"]');
                return Array.prototype.some.call(bloques, bloqueTieneDatos);
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
        // (Flete/Gasto Administrativo), para que el usuario nunca llegue a ver un error del
        // backend en el uso normal del formulario. El backend se deja como respaldo ante datos
        // manipulados o JS deshabilitado.
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

        actualizarEtiquetasMonedaFlete();
        actualizarMontosConvertibles();
        recalcularTotales();
    })();
</script>
@endpush
