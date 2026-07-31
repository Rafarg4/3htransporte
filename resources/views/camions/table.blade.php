 <div class="table-responsive" style="padding:15px;font-size: 12px;">
    <table class="table" id="table">
        <thead>
        <tr>
            <th>Marca</th>
        <th>Modelo</th>
        <th>Tipo</th>
        <th>Año</th>
        <th>Color</th>
        <th>Ejes</th>
        <th>Nro Chasis</th>
        <th>Chofer</th>
        <th>Chapa</th>
        <th>Estado</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
        @foreach($camions as $camion)
            <tr>
                <td>{{ $camion->marca }}</td>
            <td>{{ $camion->modelo }}</td>
            <td>{{ $camion->tipo }}</td>
            <td>{{ $camion->anho }}</td>
            <td>{{ $camion->color }}</td>
            <td>{{ $camion->ejes }}</td>
            <td>{{ $camion->nro_chasis }}</td>
            <td>{{ $camion->chofer ? trim($camion->chofer->nombre . ' ' . $camion->chofer->apellido) : 'Sin asignar' }}</td>
            <td>{{ $camion->chapa }}</td>
            <td>
                <span class="badge estado-badge estado-badge-{{ $camion->estado === 'Activo' ? 'activo' : 'inactivo' }}">
                    {{ $camion->estado }}
                </span>
            </td>
                <td width="220">
                    {!! Form::open(['route' => ['camions.estado', $camion->id], 'method' => 'patch']) !!}
                    <div class="action-buttons d-flex justify-content-center">
                        <a href="{{ route('camions.show', [$camion->id]) }}" class="btn btn-info">
                            <i class="far fa-eye"></i> Ver
                        </a>
                        <a href="{{ route('camions.edit', [$camion->id]) }}" class="btn btn-warning">
                            <i class="far fa-edit"></i> Editar
                        </a>
                        @if($camion->estado === 'Activo')
                            {!! Form::button('<i class="fas fa-ban"></i> Inactivar', [
                                'type' => 'submit',
                                'class' => 'btn btn-danger',
                                'onclick' => "return confirm('¿Estás seguro de inactivar este camión?')",
                            ]) !!}
                        @else
                            {!! Form::button('<i class="fas fa-check"></i> Activar', [
                                'type' => 'submit',
                                'class' => 'btn btn-success',
                                'onclick' => "return confirm('¿Estás seguro de activar este camión?')",
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
