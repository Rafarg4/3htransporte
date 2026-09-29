@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Reporte de Órdenes de Carga</h1>
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-success float-right ml-2"
                       href="{{ route('ordenCargas.reporte.excel', $filtros) }}">
                        <i class="far fa-file-excel"></i> CSV
                    </a>
                    <a class="btn btn-danger float-right"
                       href="{{ route('ordenCargas.reporte.pdf', $filtros) }}" target="_blank">
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
                {!! Form::open(['route' => 'ordenCargas.reporte', 'method' => 'get', 'class' => 'form-row align-items-end']) !!}
                    <div class="form-group col-md-4">
                        <label>Proveedor</label>
                        {!! Form::select('id_proveedor', $proveedores, $filtros['id_proveedor'] ?? null, ['class' => 'form-control', 'id' => 'id_proveedor', 'placeholder' => 'Todos', 'style' => 'width: 100%']) !!}
                    </div>
                    <div class="form-group col-md-2">
                        <label>Fecha carga desde</label>
                        {!! Form::date('fecha_desde', $filtros['fecha_desde'] ?? null, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group col-md-2">
                        <label>Fecha carga hasta</label>
                        {!! Form::date('fecha_hasta', $filtros['fecha_hasta'] ?? null, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group col-md-4">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filtrar</button>
                        <a href="{{ route('ordenCargas.reporte') }}" class="btn btn-secondary">Limpiar</a>
                    </div>
                {!! Form::close() !!}
            </div>

            <div class="card-body p-0">
                <div class="table-responsive" style="padding:15px;font-size: 12px;">
                    <table class="table" id="table">
                        <thead>
                        <tr>
                            <th>Numero</th>
                            <th>Fecha</th>
                            <th>Proveedor</th>
                            <th>Producto</th>
                            <th>Origen</th>
                            <th>Destino</th>
                            <th>Camión</th>
                            <th>Obs</th>
                            <th>Estado</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($ordenCargas as $ordenCarga)
                            <tr>
                                <td data-order="{{ (int) $ordenCarga->numero }}">{{ $ordenCarga->numero }}</td>
                                <td data-order="{{ $ordenCarga->created_at ? $ordenCarga->created_at->format('Y-m-d H:i') : '' }}">
                                    {{ $ordenCarga->created_at ? $ordenCarga->created_at->format('d/m/Y') : '-' }}
                                </td>
                                <td>{{ $ordenCarga->proveedor->nombre ?? '-' }}</td>
                                <td>{{ $ordenCarga->producto->nombre ?? '-' }}</td>
                                <td>{{ $ordenCarga->origen }}</td>
                                <td>{{ $ordenCarga->destino }}</td>
                                <td>{{ $ordenCarga->camion->chapa ?? '-' }}</td>
                                <td>{{ $ordenCarga->observacion ?: '-' }}</td>
                                <td>
                                    <span class="badge {{ strtolower($ordenCarga->estado) === 'activo' ? 'badge-success' : 'badge-secondary' }}">
                                        {{ $ordenCarga->estado }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">No hay órdenes de carga para los filtros seleccionados.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="card-footer clearfix">
                    Total de registros: {{ $ordenCargas->count() }}
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
                $('#id_proveedor').select2({ width: '100%', placeholder: 'Todos', allowClear: true });

                @if($ordenCargas->isNotEmpty())
                $('#table').DataTable({
                    language: {
                        url: '{{ asset('vendor/datatables/i18n/es-ES.json') }}'
                    },
                    order: [],
                    pageLength: 10,
                    lengthChange: false
                });
                @endif
            });
        </script>
    @endpush
@endsection
