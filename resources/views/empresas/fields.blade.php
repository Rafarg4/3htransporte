<!-- Ruc Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ruc', 'Ruc:') !!}
    {!! Form::text('ruc', null, ['class' => 'form-control', 'required' => 'required']) !!}
</div>

<!-- Nombre Field -->
<div class="form-group col-sm-6">
    {!! Form::label('nombre', 'Nombre:') !!}
    {!! Form::text('nombre', null, ['class' => 'form-control', 'required' => 'required']) !!}
</div>

<!-- Logo Field -->
<div class="form-group col-sm-6">
    {!! Form::label('logo', 'Logo:') !!}
    {!! Form::file('logo', ['class' => 'form-control-file', 'accept' => 'image/*'] + (isset($empresa) ? [] : ['required' => 'required'])) !!}
    @isset($empresa)
        @if($empresa->logo)
            <div class="mt-2">
                <img src="{{ asset('imagenes/' . $empresa->logo) }}" alt="Logo" style="max-height:80px;">
            </div>
        @endif
    @endisset
</div>

<!-- Direccion Field -->
<div class="form-group col-sm-6">
    {!! Form::label('direccion', 'Direccion:') !!}
    {!! Form::text('direccion', null, ['class' => 'form-control', 'required' => 'required']) !!}
</div>

<!-- Telefono Field -->
<div class="form-group col-sm-6">
    {!! Form::label('telefono', 'Telefono:') !!}
    {!! Form::text('telefono', null, ['class' => 'form-control', 'required' => 'required']) !!}
</div>