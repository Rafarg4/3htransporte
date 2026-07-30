<!-- Cliente Field -->
<div class="form-group col-sm-6">
    {!! Form::label('id_cliente', 'Cliente:') !!}
    {!! Form::select('id_cliente', $clientes, null, ['class' => 'form-control', 'placeholder' => 'Seleccione un cliente', 'required' => 'required']) !!}
</div>

<!-- Fecha Field -->
<div class="form-group col-sm-6">
    {!! Form::label('fecha', 'Fecha:') !!}
    {!! Form::date('fecha', now()->format('Y-m-d'), ['class' => 'form-control', 'required' => 'required']) !!}
</div>

<!-- FLETE -->
<div class="col-sm-12">
    <hr>
    <h5>Flete</h5>

    <div class="form-row">
        <div class="form-group col-sm-2">
            <label>Chapa</label>
            <select name="flete[id_camion]" class="form-control">
                <option value="">Seleccione</option>
                @foreach($camions as $id => $chapa)
                    <option value="{{ $id }}">{{ $chapa }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-sm-2">
            <label>Fecha</label>
            <input type="date" name="flete[fecha]" class="form-control">
        </div>
        <div class="form-group col-sm-2">
            <label>Tramo</label>
            <input type="text" name="flete[tramo]" class="form-control" placeholder="Origen a destino">
        </div>
        <div class="form-group col-sm-1">
            <label>Kg Origen</label>
            <input type="number" step="0.01" name="flete[kg_origen]" class="form-control">
        </div>
        <div class="form-group col-sm-1">
            <label>Kg Destino</label>
            <input type="number" step="0.01" name="flete[kg_destino]" class="form-control">
        </div>
        <div class="form-group col-sm-2">
            <label>Precio</label>
            <input type="number" step="0.01" name="flete[precio]" class="form-control">
        </div>
        <div class="form-group col-sm-2">
            <label>Valor</label>
            <input type="number" step="0.01" name="flete[valor]" class="form-control liquidacion-credito">
        </div>
    </div>
</div>

<!-- DESCUENTO -->
<div class="col-sm-12">
    <hr>
    <h5>Descuento</h5>

    <div class="form-row">
        <div class="form-group col-sm-3">
            <label>Chapa</label>
            <select name="descuento[id_camion]" class="form-control">
                <option value="">Seleccione</option>
                @foreach($camions as $id => $chapa)
                    <option value="{{ $id }}">{{ $chapa }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-sm-3">
            <label>Fecha</label>
            <input type="date" name="descuento[fecha]" class="form-control">
        </div>
        <div class="form-group col-sm-3">
            <label>Concepto</label>
            <select name="descuento[concepto]" class="form-control">
                <option value="">Seleccione un concepto</option>
                <option value="Multa">Multa</option>
                <option value="Anticipo">Anticipo</option>
                <option value="Faltante de Carga">Faltante de Carga</option>
                <option value="Otro">Otro</option>
            </select>
        </div>
        <div class="form-group col-sm-3">
            <label>Valor</label>
            <input type="number" step="0.01" name="descuento[valor]" class="form-control liquidacion-debito">
        </div>
    </div>
</div>

<!-- VIATICO -->
<div class="col-sm-12">
    <hr>
    <h5>Viático</h5>

    <div class="form-row">
        <div class="form-group col-sm-3">
            <label>Chapa</label>
            <select name="viatico[id_camion]" class="form-control">
                <option value="">Seleccione</option>
                @foreach($camions as $id => $chapa)
                    <option value="{{ $id }}">{{ $chapa }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-sm-3">
            <label>Fecha</label>
            <input type="date" name="viatico[fecha]" class="form-control">
        </div>
        <div class="form-group col-sm-3">
            <label>Descripción</label>
            <select name="viatico[descripcion]" class="form-control">
                <option value="">Seleccione una opción</option>
                <option value="Transferencia">Transferencia</option>
                <option value="Giro">Giro</option>
                <option value="Efectivo">Efectivo</option>
            </select>
        </div>
        <div class="form-group col-sm-3">
            <label>Valor</label>
            <input type="number" step="0.01" name="viatico[valor]" class="form-control liquidacion-debito">
        </div>
    </div>
</div>

<!-- COMBUSTIBLE -->
<div class="col-sm-12">
    <hr>
    <h5>Combustible</h5>

    <div class="form-row">
        <div class="form-group col-sm-2">
            <label>Chapa</label>
            <select name="combustible[id_camion]" class="form-control">
                <option value="">Seleccione</option>
                @foreach($camions as $id => $chapa)
                    <option value="{{ $id }}">{{ $chapa }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-sm-2">
            <label>Fecha</label>
            <input type="date" name="combustible[fecha]" class="form-control">
        </div>
        <div class="form-group col-sm-2">
            <label>Estación</label>
            <input type="text" name="combustible[nombre_estacion]" class="form-control">
        </div>
        <div class="form-group col-sm-2">
            <label>Litros</label>
            <input type="number" step="0.01" name="combustible[litros]" class="form-control">
        </div>
        <div class="form-group col-sm-2">
            <label>Precio</label>
            <input type="number" step="0.01" name="combustible[precio]" class="form-control">
        </div>
        <div class="form-group col-sm-2">
            <label>Valor</label>
            <input type="number" step="0.01" name="combustible[valor]" class="form-control liquidacion-debito">
        </div>
    </div>
</div>

<!-- GASTOS ADMINISTRATIVOS -->
<div class="col-sm-12">
    <hr>
    <h5>Gastos Administrativos</h5>

    <div class="form-row">
        <div class="form-group col-sm-3">
            <label>Chapa</label>
            <select name="gasto_administrativo[id_camion]" class="form-control">
                <option value="">Seleccione</option>
                @foreach($camions as $id => $chapa)
                    <option value="{{ $id }}">{{ $chapa }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-sm-3">
            <label>Fecha</label>
            <input type="date" name="gasto_administrativo[fecha]" class="form-control">
        </div>
        <div class="form-group col-sm-3">
            <label>Concepto</label>
            <select name="gasto_administrativo[concepto]" class="form-control">
                <option value="">Seleccione un concepto</option>
                <option value="Administración">Administración</option>
                <option value="Comisión">Comisión</option>
                <option value="Otro">Otro</option>
            </select>
        </div>
        <div class="form-group col-sm-3">
            <label>Valor</label>
            <input type="number" step="0.01" name="gasto_administrativo[valor]" class="form-control liquidacion-debito">
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
            document.querySelectorAll('.liquidacion-debito').forEach(function (input) {
                debitos += parseFloat(input.value) || 0;
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

        recalcularTotales();
    })();
</script>
