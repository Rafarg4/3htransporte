<div class="table-responsive" style="padding:15px;font-size: 12px;">
    <table class="table" id="table">
        <thead>
        <tr>
            <th>Propietario</th>
            <th>Camión</th>
            <th>Chofer</th>
            <th>Orden de Carga</th>
            <th>Fecha</th>
            <th>Créditos</th>
            <th>Débitos</th>
            <th>Saldo</th>
            <th>Estado</th>
            <th>Acción</th>
        </tr>
        </thead>
        <tbody>
        @foreach($liquidacions as $liquidacion)
            <tr>
                <td>{{ $liquidacion->cliente ? trim($liquidacion->cliente->nombre . ' ' . $liquidacion->cliente->apellido) : '-' }}</td>
                <td>{{ $liquidacion->camion->chapa ?? '-' }}</td>
                <td>{{ $liquidacion->chofer ? trim($liquidacion->chofer->nombre . ' ' . $liquidacion->chofer->apellido) : '-' }}</td>
                <td>{{ $liquidacion->ordenCarga ? 'OC-' . str_pad($liquidacion->ordenCarga->id, 6, '0', STR_PAD_LEFT) : '-' }}</td>
                <td>{{ $liquidacion->fecha }}</td>
                <td>{{ number_format($liquidacion->total_creditos, 0, ',', '.') }}</td>
                <td>{{ number_format($liquidacion->total_debitos, 0, ',', '.') }}</td>
                <td>{{ number_format($liquidacion->saldo, 0, ',', '.') }}</td>
                <td>
                    <span class="badge estado-badge estado-badge-{{ strtolower($liquidacion->estado) === 'activo' ? 'activo' : 'anulado' }}">
                        {{ $liquidacion->estado }}
                    </span>
                </td>
                <td width="200">
                    <div class="action-buttons d-flex justify-content-center">
                        <a href="{{ route('liquidacions.pdf', $liquidacion->id) }}" class="btn btn-danger" target="_blank">
                            <i class="far fa-file-pdf"></i> PDF
                        </a>
                        @if(strtolower($liquidacion->estado) === 'activo')
                            {!! Form::open(['route' => ['liquidacions.destroy', $liquidacion->id], 'method' => 'delete']) !!}
                            {!! Form::button('<i class="fas fa-ban"></i> Anular', [
                                'type' => 'submit',
                                'class' => 'btn btn-info',
                                'onclick' => "return confirm('¿Anular esta liquidación?')",
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
