@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>Editar Liquidación #{{ $liquidacion->id }}</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('adminlte-templates::common.errors')

        <div class="card">

            {!! Form::open(['route' => ['liquidacions.update', $liquidacion->id], 'method' => 'put']) !!}

            <div class="card-body">

                <div class="row">
                    @include('liquidacions.edit_fields')
                </div>

            </div>

            <div class="card-footer">
                {!! Form::submit('Guardar cambios', ['class' => 'btn btn-primary']) !!}
                <a href="{{ route('liquidacions.index') }}" class="btn btn-default">Cancelar</a>
            </div>

            {!! Form::close() !!}

        </div>
    </div>
@endsection
