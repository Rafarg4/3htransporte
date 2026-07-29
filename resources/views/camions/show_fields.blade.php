<!-- Encabezado -->
<div class="col-12">
    <div class="camion-header">
        <div class="camion-avatar">
            <i class="fas fa-truck"></i>
        </div>
        <div>
            <h4 class="mb-0">{{ $camion->marca }} {{ $camion->modelo }}</h4>
        </div>
    </div>
</div>

<!-- Datos -->
<div class="col-12">
    <h6 class="camion-section-title">Detalles</h6>
</div>

<div class="col-sm-4">
    <div class="camion-field">
        <i class="fas fa-user camion-field-icon"></i>
        <div>
            <small class="text-muted d-block">Chofer asignado</small>
            <span>{{ $camion->chofer ? trim($camion->chofer->nombre . ' ' . $camion->chofer->apellido) : 'Sin asignar' }}</span>
        </div>
    </div>
</div>

<div class="col-sm-4">
    <div class="camion-field">
        <i class="fas fa-hashtag camion-field-icon"></i>
        <div>
            <small class="text-muted d-block">Nro Chasis</small>
            <span>{{ $camion->nro_chasis }}</span>
        </div>
    </div>
</div>

<div class="col-sm-4">
    <div class="camion-field">
        <i class="fas fa-id-card camion-field-icon"></i>
        <div>
            <small class="text-muted d-block">Chapa</small>
            <span>{{ $camion->chapa }}</span>
        </div>
    </div>
</div>

<div class="col-sm-4">
    <div class="camion-field">
        <i class="fas fa-calendar-alt camion-field-icon"></i>
        <div>
            <small class="text-muted d-block">Año</small>
            <span>{{ $camion->anho }}</span>
        </div>
    </div>
</div>

<div class="col-sm-4">
    <div class="camion-field">
        <i class="fas fa-palette camion-field-icon"></i>
        <div>
            <small class="text-muted d-block">Color</small>
            <span>{{ $camion->color }}</span>
        </div>
    </div>
</div>

<div class="col-sm-4">
    <div class="camion-field">
        <i class="fas fa-calendar-plus camion-field-icon"></i>
        <div>
            <small class="text-muted d-block">Creado</small>
            <span>{{ $camion->created_at ? $camion->created_at->format('d/m/Y H:i') : '-' }}</span>
        </div>
    </div>
</div>

<div class="col-sm-4">
    <div class="camion-field">
        <i class="fas fa-calendar-check camion-field-icon"></i>
        <div>
            <small class="text-muted d-block">Actualizado</small>
            <span>{{ $camion->updated_at ? $camion->updated_at->format('d/m/Y H:i') : '-' }}</span>
        </div>
    </div>
</div>

<!-- Carreta -->
<div class="col-12">
    <h6 class="camion-section-title">Carreta</h6>
</div>

<div class="col-sm-4">
    <div class="camion-field">
        <i class="fas fa-truck-loading camion-field-icon"></i>
        <div>
            <small class="text-muted d-block">Tipo</small>
            <span>{{ $camion->tipo ?? '-' }}</span>
        </div>
    </div>
</div>

<div class="col-sm-4">
    <div class="camion-field">
        <i class="fas fa-cog camion-field-icon"></i>
        <div>
            <small class="text-muted d-block">Ejes</small>
            <span>{{ $camion->ejes ?? '-' }}</span>
        </div>
    </div>
</div>

<div class="col-sm-4">
    <div class="camion-field">
        <i class="fas fa-id-card camion-field-icon"></i>
        <div>
            <small class="text-muted d-block">Chapa de Carreta</small>
            <span>{{ $camion->carreta_chapa ?? '-' }}</span>
        </div>
    </div>
</div>

<!-- Documentos -->
<div class="col-12">
    <h6 class="camion-section-title">Documentos</h6>
    <div class="d-flex flex-wrap">
        @forelse($camion->documentos as $documento)
            <a href="{{ asset('documento_camiones/' . $documento->nombre_archivo) }}" target="_blank" class="documento-card">
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
    .camion-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1.25rem;
        border-bottom: 1px solid #e9ecef;
    }
    .camion-avatar {
        flex-shrink: 0;
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: #007bff;
        color: #fff;
        font-size: 1.3rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .camion-badge {
        font-size: .8rem;
        padding: .4rem .75rem;
        border-radius: .25rem;
        background: #f1f3f5;
        color: #495057;
        border: 1px solid #dee2e6;
    }
    .camion-section-title {
        text-transform: uppercase;
        font-size: .75rem;
        font-weight: 700;
        letter-spacing: .05em;
        color: #6c757d;
        margin: 1.25rem 0 .75rem;
    }
    .camion-field {
        display: flex;
        align-items: center;
        gap: .75rem;
        margin-bottom: 1.25rem;
    }
    .camion-field-icon {
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
