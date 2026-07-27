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
                <td width="190">
                    {!! Form::open(['route' => ['valeCombustibles.destroy', $valeCombustible->id], 'method' => 'delete']) !!}
                    <div class="action-buttons d-flex justify-content-center">
                        <a href="{{ route('valeCombustibles.edit', [$valeCombustible->id]) }}" class="btn btn-warning">
                            <i class="far fa-edit"></i> Editar
                        </a>
                        <a href="{{ route('valeCombustibles.pdf', [$valeCombustible->id]) }}" class="btn btn-info" target="_blank">
                            <i class="far fa-file-pdf"></i> PDF
                        </a>
                        {!! Form::button('<i class="far fa-trash-alt"></i> Eliminar', [
                            'type' => 'submit',
                            'class' => 'btn btn-danger',
                            'onclick' => "return confirm('¿Estás seguro de eliminar este vale?')",
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