<!-- Encabezado -->
<div class="col-12">
    <div class="chofer-header">
        <div class="chofer-avatar">
            {{ strtoupper(substr($chofer->nombre, 0, 1) . substr($chofer->apellido, 0, 1)) }}
        </div>
        <div>
            <h4 class="mb-0">{{ $chofer->nombre }} {{ $chofer->apellido }}</h4>
        </div>
    </div>
</div>

<!-- Detalles -->
<div class="col-12">
    <h6 class="chofer-section-title">Detalles</h6>
</div>

<div class="col-sm-6 col-lg-4">
    <div class="chofer-field">
        <i class="fas fa-user chofer-field-icon"></i>
        <div>
            <small class="text-muted d-block">Nombre</small>
            <span>{{ $chofer->nombre }}</span>
        </div>
    </div>
</div>

<div class="col-sm-6 col-lg-4">
    <div class="chofer-field">
        <i class="fas fa-user chofer-field-icon"></i>
        <div>
            <small class="text-muted d-block">Apellido</small>
            <span>{{ $chofer->apellido }}</span>
        </div>
    </div>
</div>

<div class="col-sm-6 col-lg-4">
    <div class="chofer-field">
        <i class="fas fa-id-card chofer-field-icon"></i>
        <div>
            <small class="text-muted d-block">Documento</small>
            <span>{{ $chofer->documento }}</span>
        </div>
    </div>
</div>

<div class="col-sm-6 col-lg-4">
    <div class="chofer-field">
        <i class="fas fa-toggle-on chofer-field-icon"></i>
        <div>
            <small class="text-muted d-block">Estado</small>
            <span class="badge chofer-badge chofer-badge-{{ strtolower($chofer->estado) === 'activo' ? 'activo' : 'inactivo' }}">
                {{ $chofer->estado }}
            </span>
        </div>
    </div>
</div>

<div class="col-sm-6 col-lg-4">
    <div class="chofer-field">
        <i class="fas fa-calendar-plus chofer-field-icon"></i>
        <div>
            <small class="text-muted d-block">Creado</small>
            <span>{{ $chofer->created_at ? $chofer->created_at->format('d/m/Y H:i') : '-' }}</span>
        </div>
    </div>
</div>

<div class="col-sm-6 col-lg-4">
    <div class="chofer-field">
        <i class="fas fa-calendar-check chofer-field-icon"></i>
        <div>
            <small class="text-muted d-block">Actualizado</small>
            <span>{{ $chofer->updated_at ? $chofer->updated_at->format('d/m/Y H:i') : '-' }}</span>
        </div>
    </div>
</div>

<!-- Documentos -->
<div class="col-12">
    <h6 class="chofer-section-title">Documentos</h6>
    <div class="d-flex flex-wrap">
        @forelse($chofer->documentos as $documento)
            <a href="{{ asset('documento_chofer/' . $documento->nombre_archivo) }}" target="_blank" class="documento-card">
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

<style>
    .chofer-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1.25rem;
        border-bottom: 1px solid #e9ecef;
    }
    .chofer-avatar {
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
    .chofer-badge {
        font-size: .8rem;
        padding: .4rem .75rem;
        border-radius: 1rem;
    }
    .chofer-badge-activo {
        background: #d4edda;
        color: #155724;
    }
    .chofer-badge-inactivo {
        background: #f1f3f5;
        color: #6c757d;
    }
    .chofer-section-title {
        text-transform: uppercase;
        font-size: .75rem;
        font-weight: 700;
        letter-spacing: .05em;
        color: #6c757d;
        margin: 1.25rem 0 .75rem;
    }
    .chofer-field {
        display: flex;
        align-items: center;
        gap: .75rem;
        margin-bottom: 1.25rem;
    }
    .chofer-field-icon {
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
