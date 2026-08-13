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
            <th>Acción</th>
        </tr>
        </thead>
        <tbody>
        @foreach($reportes as $reporte)
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
                <td width="220">
                    {!! Form::open(['route' => ['reportes.destroy', $reporte->id], 'method' => 'delete']) !!}
                    <div class="action-buttons d-flex justify-content-center">
                        <a href="{{ route('reportes.edit', [$reporte->id]) }}" class="btn btn-warning">
                            <i class="far fa-edit"></i> Editar
                        </a>
                        {!! Form::button('<i class="far fa-trash-alt"></i> Eliminar', [
                            'type' => 'submit',
                            'class' => 'btn btn-danger',
                            'onclick' => "return confirm('¿Estás seguro de eliminar este registro?')",
                        ]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<style>
    .action-buttons .btn {
        margin: 0 3px;
        padding: .25rem .6rem;
        border-radius: .25rem;
        font-size: .75rem;
        white-space: nowrap;
    }
    .estado-badge {
        font-size: .75rem;
        padding: .35rem .65rem;
        border-radius: 1rem;
    }
    .estado-badge-activo {
        background: #d4edda;
        color: #155724;
    }
    .estado-badge-inactivo {
        background: #f8d7da;
        color: #721c24;
    }
</style>

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
                columnDefs: [
                    {orderable: false, targets: -1}
                ]
            });
        });
    </script>
@endpush