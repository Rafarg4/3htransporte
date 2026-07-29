 <div class="table-responsive" style="padding:15px;font-size: 12px;">
    <table class="table" id="table">
        <thead>
        <tr>
            <th>Tipo</th>
        <th>Documento</th>
        <th>Nombre</th>
        <th>Apellido</th>
            <th>Acción</th>
        </tr>
        </thead>
        <tbody>
        @foreach($proveedors as $proveedor)
            <tr>
                <td>{{ $proveedor->tipo ?? '-' }}</td>
            <td>{{ $proveedor->documento }}</td>
            <td>{{ $proveedor->nombre }}</td>
            <td>{{ $proveedor->tipo === 'Empresa' ? '-' : ($proveedor->apellido ?? '-') }}</td>
                <td width="190">
                    {!! Form::open(['route' => ['proveedors.destroy', $proveedor->id], 'method' => 'delete']) !!}
                    <div class="action-buttons d-flex justify-content-center">
                       
                        <a href="{{ route('proveedors.edit', [$proveedor->id]) }}" class="btn btn-warning">
                            <i class="far fa-edit"></i> Editar
                        </a>
                        {!! Form::button('<i class="far fa-trash-alt"></i> Eliminar', [
                            'type' => 'submit',
                            'class' => 'btn btn-danger',
                            'onclick' => "return confirm('¿Estás seguro de eliminar este proveedor?')",
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
