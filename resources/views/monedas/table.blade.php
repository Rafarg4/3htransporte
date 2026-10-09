 <div class="table-responsive" style="padding:15px;font-size: 12px;">
    <table class="table" id="table">
        <thead>
        <tr>
            <th>Moneda</th>
            <th>Cotización</th>
            <th>Última actualización</th>
            <th>Accion</th>
        </tr>
        </thead>
        <tbody>
        @foreach($monedas as $moneda)
            <tr>
                <td>{{ $moneda->nombre }} ({{ $moneda->tipo_moneda }})</td>
                <td>{{ $moneda->monto }}</td>
                <td data-order="{{ optional($moneda->updated_at)->timestamp }}">{{ optional($moneda->updated_at)->format('d/m/Y H:i') }}</td>
                <td width="220">
                    {!! Form::open(['route' => ['monedas.destroy', $moneda->id], 'method' => 'delete']) !!}
                    <div class='btn-group action-buttons'>
                        <a href="{{ route('monedas.edit', [$moneda->id]) }}"
                           class='btn btn-primary btn-xs'>
                            <i class="fas fa-sync-alt"></i> Actualizar cotización
                        </a>
                        {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'title' => 'Eliminar', 'onclick' => "return confirm('¿Seguro que desea eliminar esta moneda?')"]) !!}
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
