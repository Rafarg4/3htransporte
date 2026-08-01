@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>Parametrizaciones</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('adminlte-templates::common.errors')

        <div class="card">

            {!! Form::model($parametrizacion, ['route' => 'parametrizaciones.update', 'method' => 'put']) !!}

            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <h6 class="text-muted text-uppercase" style="font-size:.75rem; letter-spacing:.05em;">Recargo por Diferencia de Flete</h6>
                        <p class="text-muted">
                            Cuando la Diferencia (Kg Origen - Kg Destino) de un Flete es negativa, la Liquidación
                            calcula un recargo automático como: <strong>Diferencia + (Tolerancia &times; Precio)</strong>.
                        </p>
                    </div>

                    <div class="form-group col-sm-4">
                        {!! Form::label('recargo_tolerancia', 'Tolerancia (Kg):') !!}
                        {!! Form::number('recargo_tolerancia', null, ['class' => 'form-control', 'step' => '0.01', 'required' => 'required']) !!}
                    </div>

                    <div class="form-group col-sm-4">
                        {!! Form::label('recargo_precio', 'Precio Recargo (Gs. por Kg):') !!}
                        {!! Form::number('recargo_precio', null, ['class' => 'form-control', 'step' => '0.01', 'required' => 'required']) !!}
                    </div>
                </div>
            </div>

            <div class="card-footer">
                {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
            </div>

            {!! Form::close() !!}

        </div>
    </div>
@endsection
