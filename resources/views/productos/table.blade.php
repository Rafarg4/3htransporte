 <div class="table-responsive" style="padding:15px;font-size: 12px;">
    <table class="table" id="table">
        <thead>
        <tr>
            <th>Nombre</th>
            <th>Estado</th>
            <th>Acción</th>
        </tr>
        </thead>
        <tbody>
        @foreach($productos as $producto)
            <tr>
                <td>{{ $producto->nombre }}</td>
                <td>
                    <span class="badge estado-badge estado-badge-{{ $producto->estado === 'Activo' ? 'activo' : 'inactivo' }}">
                        {{ $producto->estado }}
                    </span>
                </td>
                <td width="220">
                    {!! Form::open(['route' => ['productos.estado', $producto->id], 'method' => 'patch']) !!}
                    <div class="action-buttons d-flex justify-content-center">
                        <a href="{{ route('productos.edit', [$producto->id]) }}" class="btn btn-warning">
                            <i class="far fa-edit"></i> Editar
                        </a>
                        @if($producto->estado === 'Activo')
                            {!! Form::button('<i class="fas fa-ban"></i> Inactivar', [
                                'type' => 'submit',
                                'class' => 'btn btn-danger',
                                'onclick' => "return confirm('¿Estás seguro de inactivar este producto?')",
                            ]) !!}
                        @else
                            {!! Form::button('<i class="fas fa-check"></i> Activar', [
                                'type' => 'submit',
                                'class' => 'btn btn-success',
                                'onclick' => "return confirm('¿Estás seguro de activar este producto?')",
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
