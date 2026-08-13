<div class="table-responsive" style="padding:15px;font-size: 12px;">
    <table class="table" id="table">
        <thead>
        <tr>
            <th>Propietario</th>
            <th>Fecha</th>
            <th>Créditos</th>
            <th>Débitos</th>
            <th>Saldo</th>
            <th>Estado</th>
            <th>Facturado</th>
            <th>Pagado</th>
            <th>Acción</th>
        </tr>
        </thead>
        <tbody>
        @foreach($liquidacions as $liquidacion)
            <tr>
                <td>{{ $liquidacion->cliente ? trim($liquidacion->cliente->nombre . ' ' . $liquidacion->cliente->apellido) : '-' }}</td>
                <td>{{ $liquidacion->fecha }}</td>
                <td>{{ number_format($liquidacion->total_creditos, 0, ',', '.') }}</td>
                <td>{{ number_format($liquidacion->total_debitos, 0, ',', '.') }}</td>
                <td>{{ number_format($liquidacion->saldo, 0, ',', '.') }}</td>
                <td>
                    <span class="badge estado-badge estado-badge-{{ strtolower($liquidacion->estado) === 'activo' ? 'activo' : 'anulado' }}">
                        {{ $liquidacion->estado }}
                    </span>
                </td>
                <td>
                    <span class="badge estado-badge {{ $liquidacion->facturado === 'Si' ? 'estado-badge-activo' : 'estado-badge-anulado' }}">
                        {{ $liquidacion->facturado === 'Si' ? 'Sí' : 'No' }}
                    </span>
                </td>
                <td>
                    <span class="badge estado-badge {{ $liquidacion->pagado === 'Si' ? 'estado-badge-activo' : 'estado-badge-anulado' }}">
                        {{ $liquidacion->pagado === 'Si' ? 'Sí' : 'No' }}
                    </span>
                </td>
                <td width="60" class="text-center">
                    <div class="dropdown">
                        <button class="btn btn-secondary btn-sm" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Acciones">
                            <i class="fas fa-ellipsis-v"></i> Opcion
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="{{ route('liquidacions.pdf', $liquidacion->id) }}" target="_blank">
                                <i class="far fa-file-pdf"></i> PDF
                            </a>
                            {{-- Anulada: solo PDF. El resto de acciones no tiene sentido sobre una liquidacion sin vigencia. --}}
                            @if(strtolower($liquidacion->estado) === 'activo')
                                @if($liquidacion->facturado !== 'Si')
                                    {!! Form::open(['route' => ['liquidacions.facturado', $liquidacion->id], 'method' => 'post']) !!}
                                    <button type="submit" class="dropdown-item" onclick="return confirm('¿Confirmar marcar esta liquidación como facturada?')">
                                        <i class="fas fa-file-invoice-dollar"></i> Marcar Facturado
                                    </button>
                                    {!! Form::close() !!}
                                @endif
                                @if($liquidacion->pagado !== 'Si')
                                    {!! Form::open(['route' => ['liquidacions.pagado', $liquidacion->id], 'method' => 'post']) !!}
                                    <button type="submit" class="dropdown-item" onclick="return confirm('¿Confirmar marcar esta liquidación como pagada?')">
                                        <i class="fas fa-money-check-alt"></i> Marcar Pagado
                                    </button>
                                    {!! Form::close() !!}
                                @endif
                                <div class="dropdown-divider"></div>
                                {!! Form::open(['route' => ['liquidacions.destroy', $liquidacion->id], 'method' => 'delete']) !!}
                                <button type="submit" class="dropdown-item text-danger" onclick="return confirm('¿Anular esta liquidación?')">
                                    <i class="fas fa-ban"></i> Anular
                                </button>
                                {!! Form::close() !!}
                            @endif
                        </div>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<style>
    .dropdown-menu form {
        margin: 0;
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
