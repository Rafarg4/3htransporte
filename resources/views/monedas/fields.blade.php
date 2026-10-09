<!-- Tipo Moneda Field -->
<div class="form-group col-sm-6">
    {!! Form::label('tipo_moneda', 'Tipo Moneda:') !!}
    @if(isset($moneda))
        {{-- Al editar solo se cambia la cotizacion, la moneda queda fija --}}
        {!! Form::hidden('tipo_moneda') !!}
        <input type="text" class="form-control" value="{{ $moneda->nombre }} ({{ $moneda->tipo_moneda }})" readonly>
    @else
        {!! Form::select('tipo_moneda', $opciones, null, ['class' => 'form-control', 'placeholder' => 'Seleccione una moneda']) !!}
        <small class="form-text text-muted">
            Solo aparecen las monedas que todavía no están cargadas. Para cambiar la cotización de una moneda existente,
            volvé al listado y usá "Actualizar cotización".
        </small>
    @endif
</div>

<!-- Monto Field -->
<div class="form-group col-sm-6">
    {!! Form::label('monto', isset($moneda) ? 'Nueva cotización:' : 'Cotización:') !!}
    {!! Form::text('monto', null, ['class' => 'form-control', 'autofocus' => isset($moneda)]) !!}
    @isset($moneda)
        <small class="form-text text-muted">Cotización actual: {{ $moneda->monto }}</small>
    @endisset
</div>
