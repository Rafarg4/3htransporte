<div class="table-responsive" style="padding:15px;font-size: 12px;">
    <table class="table" id="table">
        <thead>
        <tr>
            <th>Numero</th>
        <th>Fecha</th>
        <th>Chofer</th>
        <th>Descripcion</th>
        <th>Monto</th>
        <th>Orden de Carga</th>
        <th>Cargado Por</th>
        <th>Estado</th>
            <th>Acción</th>
        </tr>
        </thead>
        <tbody>
        @foreach($viaticos as $viatico)
            <tr>
                <td>{{ $viatico->numero }}</td>
            <td>{{ $viatico->fecha }}</td>
            <td>{{ $viatico->chofer ? trim($viatico->chofer->nombre . ' ' . $viatico->chofer->apellido) : '-' }}</td>
            <td>{{ $viatico->descripcion }}</td>
            <td>{{ number_format((float) $viatico->monto, 0, ',', '.') }}</td>
            <td>{{ $viatico->ordenCarga ? 'OC-' . str_pad($viatico->ordenCarga->id, 6, '0', STR_PAD_LEFT) : '-' }}</td>
            <td>{{ $viatico->cargado_por }}</td>
            <td>
                <span class="badge estado-badge estado-badge-{{ strtolower($viatico->estado) === 'activo' ? 'activo' : 'anulado' }}">
                    {{ $viatico->estado }}
                </span>
            </td>
                <td width="320">
                    {!! Form::open(['route' => ['viaticos.destroy', $viatico->id], 'method' => 'delete']) !!}
                    <div class="action-buttons d-flex justify-content-center">
                        <a href="{{ route('viaticos.show', [$viatico->id]) }}" class="btn btn-info">
                            <i class="far fa-eye"></i> Ver
                        </a>
                        <a href="{{ route('viaticos.edit', [$viatico->id]) }}" class="btn btn-warning">
                            <i class="far fa-edit"></i> Editar
                        </a>
                        <a href="{{ route('viaticos.pdf', $viatico->id) }}" class="btn btn-danger" target="_blank">
                            <i class="far fa-file-pdf"></i> PDF
                        </a>
                        {!! Form::button('<i class="fas fa-ban"></i> Anular', [
                            'type' => 'submit',
                            'class' => 'btn btn-info',
                            'onclick' => "return confirm('¿Anular este viático?')",
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
    .estado-badge-anulado {
        background: #f1f3f5;
        color: #6c757d;
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
