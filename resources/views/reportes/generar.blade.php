@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Generar Reporte</h1>
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-success float-right ml-2"
                       href="{{ route('reportes.generar.excel', $filtros) }}">
                        <i class="far fa-file-excel"></i> CSV
                    </a>
                    <a class="btn btn-danger float-right"
                       href="{{ route('reportes.generar.pdf', $filtros) }}" target="_blank">
                        <i class="far fa-file-pdf"></i> PDF
                    </a>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('flash::message')

        <div class="clearfix"></div>

        <div class="card">
            <div class="card-body">
                {!! Form::open(['route' => 'reportes.generar', 'method' => 'get', 'class' => 'form-row align-items-end']) !!}
                    <div class="form-group col-md-3">
                        <label>Desde</label>
                        {!! Form::date('fecha_desde', $filtros['fecha_desde'] ?? null, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group col-md-3">
                        <label>Hasta</label>
                        {!! Form::date('fecha_hasta', $filtros['fecha_hasta'] ?? null, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group col-md-6">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filtrar</button>
                        <a href="{{ route('reportes.generar') }}" class="btn btn-secondary">Limpiar</a>
                    </div>
                {!! Form::close() !!}
            </div>

            <div class="card-body p-0">
                <div class="table-responsive" style="padding:15px;font-size: 12px;">
                    <table class="table" id="table">
                        <thead>
                        <tr>
                            <th>Nro. Remisión</th>
                            <th>Fecha</th>
                            <th>Propietario</th>
                            <th>Chapa</th>
                            <th>Chofer</th>
                            <th>Producto</th>
                            <th>Tramo</th>
                            <th>Kg Origen</th>
                            <th>Kg Llegada</th>
                            <th>Precio</th>
                            <th>Monto</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($reportes as $reporte)
                            <tr>
                                <td>{{ $reporte->nro_remision }}</td>
                                <td>{{ $reporte->fecha }}</td>
                                <td>{{ $reporte->cliente ? trim($reporte->cliente->nombre . ' ' . $reporte->cliente->apellido) : '-' }}</td>
                                <td>{{ $reporte->camion->chapa ?? '-' }}</td>
                                <td>{{ $reporte->chofer ? trim($reporte->chofer->nombre . ' ' . $reporte->chofer->apellido) : '-' }}</td>
                                <td>{{ $reporte->producto->nombre ?? '-' }}</td>
                                <td>{{ $reporte->tramo }}</td>
                                <td>{{ $reporte->kg_origen }}</td>
                                <td>{{ $reporte->kg_llegada }}</td>
                                <td>{{ $reporte->precio }}</td>
                                <td>{{ $reporte->monto }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center">No hay reportes para los filtros seleccionados.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="card-footer clearfix">
                    Total de registros: {{ $reportes->count() }}
                </div>
            </div>
        </div>
    </div>

    @push('third_party_stylesheets')
        @include('layouts.datatables_css')
    @endpush

    @push('third_party_scripts')
        @include('layouts.datatables_js')

        <script>
            $(function () {
                $('#table').DataTable({
                    language: {
                        url: '{{ asset('vendor/datatables/i18n/es-ES.json') }}'
                    },
                    paging: false,
                    info: false
                });
            });
        </script>
    @endpush
@endsection
