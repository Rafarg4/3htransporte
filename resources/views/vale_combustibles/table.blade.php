<div class="table-responsive" style="padding:15px;font-size: 12px;">
    <table class="table" id="table">
        <thead>
        <tr>
            <th>Numero Vale</th>
        <th>Vigencia Desde</th>
        <th>Vigencia Hasta</th>
        <th>Camión</th>
        <th>Nombre Estacion</th>
        <th>Codigo</th>
        <th>Direccion</th>
        <th>Detalle</th>
        <th>Estado</th>
            <th>Acción</th>
        </tr>
        </thead>
        <tbody>
        @foreach($valeCombustibles as $valeCombustible)
            <tr>
                <td>{{ $valeCombustible->numero_vale }}</td>
            <td>{{ $valeCombustible->vigencia_desde }}</td>
            <td>{{ $valeCombustible->vigencia_hasta }}</td>
            <td>{{ $valeCombustible->camion->chapa ?? '-' }}</td>
            <td>{{ $valeCombustible->nombre_estacion }}</td>
            <td>{{ $valeCombustible->codigo }}</td>
            <td>{{ $valeCombustible->direccion }}</td>
            <td>
                <b>Producto:</b> {{ $valeCombustible->producto }}<br>
                <b>Importe:</b> {{ number_format($valeCombustible->importe, 0, ',', '.') }}<br>
                <b>Litros:</b> {{ $valeCombustible->litros }}
            </td>
            <td>
                <span class="badge estado-badge estado-badge-{{ strtolower($valeCombustible->estado) === 'activo' ? 'activo' : 'anulado' }}">
                    {{ $valeCombustible->estado }}
                </span>
            </td>
                <td width="220">
                    {!! Form::open(['route' => ['valeCombustibles.destroy', $valeCombustible->id], 'method' => 'delete']) !!}
                    <div class="action-buttons d-flex justify-content-center">
                        <a href="{{ route('valeCombustibles.edit', [$valeCombustible->id]) }}" class="btn btn-warning">
                            <i class="far fa-edit"></i> Editar
                        </a>
                        <a href="{{ route('valeCombustibles.pdf', [$valeCombustible->id]) }}" class="btn btn-info" target="_blank">
                            <i class="far fa-file-pdf"></i> PDF
                        </a>
                        @if(strtolower($valeCombustible->estado) === 'activo')
                            {!! Form::button('<i class="fas fa-ban"></i> Anular', [
                                'type' => 'submit',
                                'class' => 'btn btn-danger',
                                'onclick' => "return confirm('¿Anular este vale?')",
                            ]) !!}
                        @endif
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