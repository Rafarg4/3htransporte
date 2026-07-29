<!-- Tipo Field -->
<div class="form-group col-sm-6">
    {!! Form::label('tipo', 'Tipo:') !!}
    {!! Form::select('tipo', ['Persona' => 'Persona', 'Empresa' => 'Empresa'], null, ['class' => 'form-control', 'id' => 'tipo', 'placeholder' => 'Seleccione una opción', 'required' => 'required']) !!}
</div>

<!-- Documento Field -->
<div class="form-group col-sm-6">
    {!! Form::label('documento', 'Documento / RUC:') !!}
    {!! Form::text('documento', null, ['class' => 'form-control', 'required' => 'required']) !!}
</div>

<!-- Nombre Field -->
<div class="form-group col-sm-6">
    {!! Form::label('nombre', 'Nombre:') !!}
    {!! Form::text('nombre', null, ['class' => 'form-control', 'required' => 'required']) !!}
</div>

<!-- Apellido Field -->
<div class="form-group col-sm-6" id="apellido-group">
    {!! Form::label('apellido', 'Apellido:') !!}
    {!! Form::text('apellido', null, ['class' => 'form-control', 'id' => 'apellido', 'required' => 'required']) !!}
</div>

<script>
    (function () {
        var tipoSelect = document.getElementById('tipo');
        var apellidoGroup = document.getElementById('apellido-group');
        var apellidoInput = document.getElementById('apellido');

        function toggleApellido() {
            if (tipoSelect.value === 'Empresa') {
                apellidoGroup.classList.add('d-none');
                apellidoInput.removeAttribute('required');
                apellidoInput.value = '';
            } else {
                apellidoGroup.classList.remove('d-none');
                apellidoInput.setAttribute('required', 'required');
            }
        }

        tipoSelect.addEventListener('change', toggleApellido);
        toggleApellido();
    })();
</script>