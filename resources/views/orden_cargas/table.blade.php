<div class="table-responsive" style="padding:15px;font-size: 12px;">
    <table class="table" id="table">
        <thead>
        <tr>
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
                <td>{{ $ordenCarga->proveedor->nombre ?? '-' }}</td>
            <td>{{ $ordenCarga->producto->nombre ?? '-' }}</td>
            <td>{{ $ordenCarga->origen }}</td>
            <td>{{ $ordenCarga->destino }}</td>
            <td>{{ $ordenCarga->camion->chapa ?? '-' }}</td>
            <td>{{ $ordenCarga->estado }}</td>
                <td width="240">
                    {!! Form::open(['route' => ['ordenCargas.destroy', $ordenCarga->id], 'method' => 'delete']) !!}
                    <div class="action-buttons d-flex justify-content-center">
                        <a href="{{ route('ordenCargas.edit', [$ordenCarga->id]) }}" class="btn btn-warning">
                            <i class="far fa-edit"></i> Editar
                        </a>
                        <a href="{{ route('ordenCargas.pdf', $ordenCarga->id) }}" class="btn btn-danger" target="_blank">
                            <i class="far fa-file-pdf"></i> PDF
                        </a>
                        {!! Form::button('<i class="fas fa-ban"></i> Anular', [
                            'type' => 'submit',
                            'class' => 'btn btn-info',
                            'onclick' => "return confirm('¿Anular esta orden de carga?')",
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