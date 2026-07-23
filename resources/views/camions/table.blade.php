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
                <td width="190">
                    {!! Form::open(['route' => ['camions.destroy', $camion->id], 'method' => 'delete']) !!}
                    <div class="action-buttons d-flex justify-content-center">
                        <a href="{{ route('camions.show', [$camion->id]) }}" class="btn btn-info">
                            <i class="far fa-eye"></i> Ver
                        </a>
                        <a href="{{ route('camions.edit', [$camion->id]) }}" class="btn btn-warning">
                            <i class="far fa-edit"></i> Editar
                        </a>
                        {!! Form::button('<i class="far fa-trash-alt"></i> Eliminar', [
                            'type' => 'submit',
                            'class' => 'btn btn-danger',
                            'onclick' => "return confirm('¿Estás seguro de eliminar este camión?')",
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
                    url: '//cdn.datatables.net/plug-ins/1.10.21/i18n/es-ES.json'
                },
                columnDefs: [
                    {orderable: false, targets: -1}
                ]
            });
        });
    </script>
@endpush