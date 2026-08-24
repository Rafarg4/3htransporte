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

<!-- Camion (Chapa) Field -->
<div class="form-group col-sm-4">
    {!! Form::label('id_camion', 'Chapa:') !!}
    @php $idCamionActual = old('id_camion', isset($reporte) ? $reporte->id_camion : null); @endphp
    <select name="id_camion" id="id_camion" class="form-control select2" style="width:100%;" data-placeholder="Seleccione una chapa" required="required">
        <option value=""></option>
        @foreach($camiones as $camion)
            <option value="{{ $camion->id }}" data-cliente="{{ $camion->id_cliente }}" data-chofer="{{ $camion->id_chofer }}" {{ (string) $idCamionActual === (string) $camion->id ? 'selected' : '' }}>
                {{ $camion->chapa }}
            </option>
        @endforeach
    </select>
</div>

<!-- Propietario Field (se completa solo al elegir la Chapa; select bloqueado con readonly) -->
<div class="form-group col-sm-4">
    {!! Form::label('id_cliente', 'Propietario:') !!}
    @php $idClienteActual = old('id_cliente', isset($reporte) ? $reporte->id_cliente : null); @endphp
    <select id="id_cliente_readonly" class="form-control select2" style="width:100%;" data-placeholder="Se completa al elegir la chapa" disabled="disabled">
        <option value=""></option>
        @foreach($clientes as $id => $nombre)
            <option value="{{ $id }}" {{ (string) $idClienteActual === (string) $id ? 'selected' : '' }}>{{ $nombre }}</option>
        @endforeach
    </select>
    {!! Form::hidden('id_cliente', $idClienteActual, ['id' => 'id_cliente']) !!}
</div>

<!-- Chofer Field (se completa solo al elegir la Chapa; select bloqueado con readonly) -->
<div class="form-group col-sm-4">
    {!! Form::label('id_chofer', 'Chofer:') !!}
    @php $idChoferActual = old('id_chofer', isset($reporte) ? $reporte->id_chofer : null); @endphp
    <select id="id_chofer_readonly" class="form-control select2" style="width:100%;" data-placeholder="Se completa al elegir la chapa" disabled="disabled">
        <option value=""></option>
        @foreach($choferes as $id => $nombre)
            <option value="{{ $id }}" {{ (string) $idChoferActual === (string) $id ? 'selected' : '' }}>{{ $nombre }}</option>
        @endforeach
    </select>
    {!! Form::hidden('id_chofer', $idChoferActual, ['id' => 'id_chofer']) !!}
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
    {!! Form::text('kg_origen', null, ['class' => 'form-control', 'id' => 'kg_origen', 'required' => 'required']) !!}
</div>

<!-- Kg Llegada Field -->
<div class="form-group col-sm-4">
    {!! Form::label('kg_llegada', 'Kg Llegada:') !!}
    {!! Form::text('kg_llegada', null, ['class' => 'form-control', 'id' => 'kg_llegada', 'required' => 'required']) !!}
</div>

<!-- Precio Field -->
<div class="form-group col-sm-4">
    {!! Form::label('precio', 'Precio:') !!}
    {!! Form::text('precio', null, ['class' => 'form-control', 'id' => 'precio', 'required' => 'required']) !!}
</div>

<!-- Monto Field -->
<div class="form-group col-sm-4">
    {!! Form::label('monto', 'Monto:') !!}
    {!! Form::text('monto', null, ['class' => 'form-control', 'id' => 'monto', 'readonly' => 'readonly', 'required' => 'required']) !!}
</div>

<style>
    select.select2 + .select2-container .select2-selection--single {
        height: calc(1.5em + .75rem + 2px);
        border: 1px solid #ced4da;
        border-radius: .25rem;
    }
    select.select2 + .select2-container .select2-selection--single .select2-selection__rendered {
        line-height: calc(1.5em + .75rem);
        padding-left: .75rem;
        color: #495057;
    }
    select.select2 + .select2-container .select2-selection--single .select2-selection__arrow {
        height: calc(1.5em + .75rem);
        right: 6px;
    }
    select.select2 + .select2-container--default.select2-container--focus .select2-selection--single,
    select.select2 + .select2-container--default .select2-selection--single:focus {
        border-color: #80bdff;
        outline: 0;
        box-shadow: 0 0 0 .2rem rgba(0,123,255,.25);
    }
</style>

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

        // --- Chapa -> Propietario/Chofer: al elegir una chapa, se autocompletan
        // el propietario y el chofer registrados para ese camion. Los selects
        // visibles quedan bloqueados (disabled); el valor real que se envia
        // en el formulario va en los hidden id_cliente/id_chofer. ---
        var $camionSelect = $('#id_camion');
        var $clienteSelectReadonly = $('#id_cliente_readonly');
        var $choferSelectReadonly = $('#id_chofer_readonly');
        var clienteHidden = document.getElementById('id_cliente');
        var choferHidden = document.getElementById('id_chofer');

        function autocompletarPropietarioYChofer() {
            var opcion = $camionSelect.find('option:selected');
            var idCliente = opcion.data('cliente') || '';
            var idChofer = opcion.data('chofer') || '';

            clienteHidden.value = idCliente;
            $clienteSelectReadonly.val(idCliente).trigger('change');

            choferHidden.value = idChofer;
            $choferSelectReadonly.val(idChofer).trigger('change');
        }

        $camionSelect.on('change', autocompletarPropietarioYChofer);

        // --- Monto = Kg Llegada x Precio ---
        var kgLlegadaInput = document.getElementById('kg_llegada');
        var precioInput = document.getElementById('precio');
        var montoInput = document.getElementById('monto');

        function calcularMonto() {
            var kgLlegada = parseFloat(kgLlegadaInput.value) || 0;
            var precio = parseFloat(precioInput.value) || 0;

            if (kgLlegada && precio) {
                montoInput.value = kgLlegada * precio;
            }
        }

        [kgLlegadaInput, precioInput].forEach(function (input) {
            input.addEventListener('input', calcularMonto);
        });
    })();
</script>
@endpush
