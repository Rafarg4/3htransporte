 <div class="table-responsive" style="padding:15px;font-size: 12px;">
    <table class="table" id="table">
        <thead>
        <tr>
        <th>Nombre</th>
        <th>Apellido</th>
        <th>Tipo Propietario</th>
        <th>Documento</th>
        <th>Direccion</th>
        <th>Telefono</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
        @foreach($clientes as $cliente)
            <tr>
                <td>{{ $cliente->nombre }}</td>
            <td>{{ $cliente->apellido }}</td>
            <td>{{ $cliente->tipo_propietario }}</td>
            <td>{{ $cliente->documento }}</td>
            <td>{{ $cliente->direccion }}</td>
            <td>{{ $cliente->telefono }}</td>
                <td width="220">
                    {!! Form::open(['route' => ['clientes.destroy', $cliente->id], 'method' => 'delete']) !!}
                    <div class="action-buttons d-flex justify-content-center">
                        <a href="{{ route('clientes.show', [$cliente->id]) }}" class="btn btn-primary">
                            <i class="fas fa-truck"></i> Camiones
                            <span class="badge badge-light ml-1">{{ $cliente->camiones_count }}</span>
                        </a>
                        <a href="{{ route('clientes.edit', [$cliente->id]) }}" class="btn btn-warning">
                            <i class="far fa-edit"></i> Editar
                        </a>
                        {!! Form::button('<i class="far fa-trash-alt"></i> Eliminar', [
                            'type' => 'submit',
                            'class' => 'btn btn-danger',
                            'onclick' => "return confirm('¿Estás seguro de eliminar este cliente?')",
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
