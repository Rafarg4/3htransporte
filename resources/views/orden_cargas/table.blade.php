<div class="table-responsive" style="padding:15px;font-size: 12px;">
    <table class="table" id="table">
        <thead>
        <tr>
            <th>Numero</th>
        <th>Proveedor</th>
        <th>Producto</th>
        <th>Origen</th>
        <th>Destino</th>
        <th>Camion</th>
        <th>Estado</th>
            <th>Acción</th>
        </tr>
        </thead>
        <tbody>
        @foreach($ordenCargas as $ordenCarga)
            <tr>
                <td>{{ $ordenCarga->numero }}</td>
            <td>{{ $ordenCarga->proveedor->nombre ?? '-' }}</td>
            <td>{{ $ordenCarga->producto->nombre ?? '-' }}</td>
            <td>{{ $ordenCarga->origen }}</td>
            <td>{{ $ordenCarga->destino }}</td>
            <td>{{ $ordenCarga->camion->chapa ?? '-' }}</td>
            <td>
                <span class="badge estado-badge estado-badge-{{ strtolower($ordenCarga->estado) === 'activo' ? 'activo' : 'anulado' }}">
                    {{ $ordenCarga->estado }}
                </span>
            </td>
                <td width="240">
                    <div class="action-buttons d-flex justify-content-center">
                        <a href="{{ route('ordenCargas.pdf', $ordenCarga->id) }}" class="btn btn-danger" target="_blank">
                            <i class="far fa-file-pdf"></i> PDF
                        </a>
                        @if(strtolower($ordenCarga->estado) !== 'anulado')
                            <a href="{{ route('ordenCargas.edit', [$ordenCarga->id]) }}" class="btn btn-warning">
                                <i class="far fa-edit"></i> Editar
                            </a>
                            {!! Form::open(['route' => ['ordenCargas.anular', $ordenCarga->id], 'method' => 'put']) !!}
                            {!! Form::button('<i class="fas fa-ban"></i> Anular', [
                                'type' => 'submit',
                                'class' => 'btn btn-info',
                                'onclick' => "return confirm('¿Anular esta orden de carga?')",
                            ]) !!}
                            {!! Form::close() !!}
                        @else
                            {!! Form::open(['route' => ['ordenCargas.destroy', $ordenCarga->id], 'method' => 'delete']) !!}
                            {!! Form::button('<i class="fas fa-trash"></i> Eliminar', [
                                'type' => 'submit',
                                'class' => 'btn btn-secondary',
                                'onclick' => "return confirm('¿Eliminar definitivamente esta orden de carga?')",
                            ]) !!}
                            {!! Form::close() !!}
                        @endif
                    </div>
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