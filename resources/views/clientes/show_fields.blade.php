<ul class="nav nav-tabs" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" data-toggle="tab" href="#tab-propietario" role="tab">
            <i class="fas fa-user mr-1"></i> Propietario
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#tab-camiones" role="tab">
            <i class="fas fa-truck mr-1"></i> Camiones
        </a>
    </li>
</ul>

<div class="tab-content pt-3">
<div class="tab-pane fade show active" id="tab-propietario" role="tabpanel">
<div class="row">

<!-- Encabezado -->
<div class="col-12">
    <div class="cliente-header">
        <div class="cliente-avatar">
            {{ strtoupper(substr($cliente->nombre, 0, 1) . substr($cliente->apellido, 0, 1)) }}
        </div>
        <div>
            <h4 class="mb-0">{{ $cliente->nombre }} {{ $cliente->apellido }}</h4>
            <span class="text-muted"><i class="fas fa-id-card mr-1"></i>{{ $cliente->documento }}</span>
        </div>
    </div>
</div>

<!-- Datos de contacto -->
<div class="col-12">
    <h6 class="cliente-section-title">Datos de contacto</h6>
</div>

<div class="col-sm-6 col-lg-4">
    <div class="cliente-field">
        <i class="fas fa-user-tag cliente-field-icon"></i>
        <div>
            <small class="text-muted d-block">Tipo Propietario</small>
            <span>{{ $cliente->tipo_propietario }}</span>
        </div>
    </div>
</div>

<div class="col-sm-6 col-lg-4">
    <div class="cliente-field">
        <i class="fas fa-map-marker-alt cliente-field-icon"></i>
        <div>
            <small class="text-muted d-block">Dirección</small>
            <span>{{ $cliente->direccion }}</span>
        </div>
    </div>
</div>

<div class="col-sm-6 col-lg-4">
    <div class="cliente-field">
        <i class="fas fa-phone cliente-field-icon"></i>
        <div>
            <small class="text-muted d-block">Teléfono</small>
            <span>{{ $cliente->telefono }}</span>
        </div>
    </div>
</div>

<div class="col-sm-6 col-lg-4">
    <div class="cliente-field">
        <i class="fas fa-hashtag cliente-field-icon"></i>
        <div>
            <small class="text-muted d-block">Id Document Propietario</small>
            <span>{{ $cliente->id_document_cliente }}</span>
        </div>
    </div>
</div>

<!-- Registro -->
<div class="col-12">
    <h6 class="cliente-section-title">Registro</h6>
</div>

<div class="col-sm-6 col-lg-4">
    <div class="cliente-field">
        <i class="fas fa-calendar-plus cliente-field-icon"></i>
        <div>
            <small class="text-muted d-block">Creado</small>
            <span>{{ $cliente->created_at ? $cliente->created_at->format('d/m/Y H:i') : '-' }}</span>
        </div>
    </div>
</div>

<div class="col-sm-6 col-lg-4">
    <div class="cliente-field">
        <i class="fas fa-calendar-check cliente-field-icon"></i>
        <div>
            <small class="text-muted d-block">Actualizado</small>
            <span>{{ $cliente->updated_at ? $cliente->updated_at->format('d/m/Y H:i') : '-' }}</span>
        </div>
    </div>
</div>

<!-- Documentos -->
<div class="col-12">
    <h6 class="cliente-section-title">Documentos</h6>
    <div class="d-flex flex-wrap">
        @forelse($cliente->documentos as $documento)
            <a href="{{ asset('documento_clientes/' . $documento->nombre_archivo) }}" target="_blank" class="documento-card">
                <div class="documento-icon">
                    @if(Str::endsWith(strtolower($documento->nombre_archivo), ['.jpg', '.jpeg', '.png', '.gif']))
                        <i class="fas fa-file-image"></i>
                    @elseif(Str::endsWith(strtolower($documento->nombre_archivo), ['.pdf']))
                        <i class="fas fa-file-pdf"></i>
                    @else
                        <i class="fas fa-file"></i>
                    @endif
                </div>
                <small class="d-block text-truncate">{{ $documento->nombre_archivo }}</small>
            </a>
        @empty
            <p class="text-muted mb-0">No hay documentos cargados.</p>
        @endforelse
    </div>
</div>

</div>
</div>

<div class="tab-pane fade" id="tab-camiones" role="tabpanel">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h6 class="cliente-section-title mb-0">Camiones asignados</h6>
        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modal-asignar-camion">
            <i class="fas fa-plus"></i> Asignar camión
        </button>
    </div>

    <div class="table-responsive">
        <table class="table table-sm" id="tabla-camiones">
            <thead>
                <tr>
                    <th>Chapa</th>
                    <th>Marca / Modelo</th>
                    <th>Chofer</th>
                    <th width="60"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($cliente->camiones as $camion)
                    <tr>
                        <td>{{ $camion->chapa }}</td>
                        <td>{{ $camion->marca }} {{ $camion->modelo }}</td>
                        <td>{{ $camion->chofer ? trim($camion->chofer->nombre . ' ' . $camion->chofer->apellido) : 'Sin asignar' }}</td>
                        <td>
                            {!! Form::open(['route' => ['clientes.camiones.destroy', $cliente->id, $camion->id], 'method' => 'delete']) !!}
                                {!! Form::button('<i class="fas fa-times"></i>', [
                                    'type' => 'submit',
                                    'class' => 'btn btn-sm btn-outline-danger',
                                    'onclick' => "return confirm('¿Quitar este camión del propietario?')",
                                ]) !!}
                            {!! Form::close() !!}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-muted">Sin camiones asignados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
</div>

<!-- Modal: Asignar camión -->
<div class="modal fade" id="modal-asignar-camion" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            {!! Form::open(['route' => ['clientes.camiones.store', $cliente->id], 'method' => 'post']) !!}
                <div class="modal-header">
                    <h5 class="modal-title">Asignar camión</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {!! Form::label('camion_id', 'Chapa:') !!}
                    {!! Form::select('camion_id', $camionesDisponibles, null, ['class' => 'form-control', 'placeholder' => 'Seleccione un camión']) !!}
                    @if($camionesDisponibles->isEmpty())
                        <small class="text-muted d-block mt-2">No hay camiones disponibles para asignar.</small>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    {!! Form::submit('Asignar', ['class' => 'btn btn-primary']) !!}
                </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

<style>
    .cliente-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1.25rem;
        border-bottom: 1px solid #e9ecef;
    }
    .cliente-avatar {
        flex-shrink: 0;
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: #007bff;
        color: #fff;
        font-weight: 600;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .cliente-section-title {
        text-transform: uppercase;
        font-size: .75rem;
        font-weight: 700;
        letter-spacing: .05em;
        color: #6c757d;
        margin: 1.25rem 0 .75rem;
    }
    .cliente-field {
        display: flex;
        align-items: center;
        gap: .75rem;
        margin-bottom: 1.25rem;
    }
    .cliente-field-icon {
        flex-shrink: 0;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #f1f3f5;
        color: #007bff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .9rem;
    }
    .documento-card {
        width: 100px;
        border: 1px solid #dee2e6;
        border-radius: .25rem;
        padding: .25rem;
        margin: .25rem;
        text-align: center;
        color: inherit;
        display: block;
    }
    .documento-card:hover {
        border-color: #007bff;
        text-decoration: none;
        color: inherit;
    }
    .documento-icon {
        height: 70px;
        font-size: 2rem;
        color: #6c757d;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>

@push('third_party_stylesheets')
    @include('layouts.datatables_css')
@endpush

@push('third_party_scripts')
    @include('layouts.datatables_js')

    <script>
        $(function () {
            var tablaCamiones = null;

            $('a[href="#tab-camiones"]').on('shown.bs.tab', function () {
                if (tablaCamiones === null) {
                    tablaCamiones = $('#tabla-camiones').DataTable({
                        language: {
                            url: '//cdn.datatables.net/plug-ins/1.10.21/i18n/es-ES.json'
                        },
                        columnDefs: [
                            {orderable: false, targets: -1}
                        ]
                    });
                } else {
                    tablaCamiones.columns.adjust();
                }
            });
        });
    </script>
@endpush
