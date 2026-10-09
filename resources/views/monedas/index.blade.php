@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Monedas</h1>
                </div>
                <div class="col-sm-6">
                    @if(!empty($faltantes))
                        <a class="btn btn-primary float-right"
                           href="{{ route('monedas.create') }}">
                            Nuevo
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('flash::message')

        <div class="clearfix"></div>

        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i>
            Cuando cambie la cotización, <strong>no cargue una moneda nueva</strong>:
            presione <strong>"Actualizar cotización"</strong> en la moneda correspondiente.
        </div>

        <div class="card">
            <div class="card-body p-0">
                @include('monedas.table')

                <div class="card-footer clearfix">
                    <div class="float-right">
                        
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection

