@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Reporte de Liquidaciones</h1>
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-success float-right ml-2"
                       href="{{ route('liquidacions.reporte.excel', $filtros) }}">
                        <i class="far fa-file-excel"></i> CSV
                    </a>
                    <a class="btn btn-danger float-right"
                       href="{{ route('liquidacions.reporte.pdf', $filtros) }}" target="_blank">
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
                {!! Form::open(['route' => 'liquidacions.reporte', 'method' => 'get', 'class' => 'form-row align-items-end']) !!}
                    <div class="form-group col-md-2">
                        <label>Desde</label>
                        {!! Form::date('fecha_desde', $filtros['fecha_desde'] ?? null, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group col-md-2">
                        <label>Hasta</label>
                        {!! Form::date('fecha_hasta', $filtros['fecha_hasta'] ?? null, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group col-md-2">
                        <label>Facturado</label>
                        {!! Form::select('facturado', ['' => 'Todos', 'Si' => 'Sí', 'No' => 'No'], $filtros['facturado'] ?? null, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group col-md-2">
                        <label>Pagado</label>
                        {!! Form::select('pagado', ['' => 'Todos', 'Si' => 'Sí', 'No' => 'No'], $filtros['pagado'] ?? null, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group col-md-4">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filtrar</button>
                        <a href="{{ route('liquidacions.reporte') }}" class="btn btn-secondary">Limpiar</a>
                    </div>
                {!! Form::close() !!}
            </div>

            <div class="card-body p-0">
                <div class="table-responsive" style="padding:15px;font-size: 12px;">
                    <table class="table" id="table">
                        <thead>
                        <tr>
                            <th>Nro.</th>
                            <th>Fecha</th>
                            <th>Propietario</th>
                            <th>Chapa</th>
                            <th>Créditos</th>
                            <th>Débitos</th>
                            <th>Saldo</th>
                            <th>Facturado</th>
                            <th>Pagado</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($liquidacions as $liquidacion)
                            <tr>
                                <td>{{ $liquidacion->id }}</td>
                                <td>{{ $liquidacion->fecha }}</td>
                                <td>{{ $liquidacion->cliente ? trim($liquidacion->cliente->nombre . ' ' . $liquidacion->cliente->apellido) : '-' }}</td>
                                <td>{{ $liquidacion->chapas ?? '-' }}</td>
                                <td>{{ number_format($liquidacion->total_creditos, 0, ',', '.') }}</td>
                                <td>{{ number_format($liquidacion->total_debitos, 0, ',', '.') }}</td>
                                <td>{{ number_format($liquidacion->saldo, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge {{ $liquidacion->facturado === 'Si' ? 'badge-success' : 'badge-secondary' }}">
                                        {{ $liquidacion->facturado === 'Si' ? 'Sí' : 'No' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $liquidacion->pagado === 'Si' ? 'badge-success' : 'badge-secondary' }}">
                                        {{ $liquidacion->pagado === 'Si' ? 'Sí' : 'No' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">No hay liquidaciones para los filtros seleccionados.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="card-footer clearfix">
                    Total de registros: {{ $liquidacions->count() }}
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
