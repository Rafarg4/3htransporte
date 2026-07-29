<!-- Encabezado -->
<div class="col-12">
    <div class="viatico-header">
        <div class="viatico-avatar">
            <i class="fas fa-money-bill-wave"></i>
        </div>
        <div>
            <h4 class="mb-0">Viático N° {{ $viatico->numero }}</h4>
        </div>
    </div>
</div>

<!-- Detalles -->
<div class="col-12">
    <h6 class="viatico-section-title">Detalles</h6>
</div>

<div class="col-sm-6 col-lg-4">
    <div class="viatico-field">
        <i class="fas fa-calendar-day viatico-field-icon"></i>
        <div>
            <small class="text-muted d-block">Fecha</small>
            <span>{{ $viatico->fecha }}</span>
        </div>
    </div>
</div>

<div class="col-sm-6 col-lg-4">
    <div class="viatico-field">
        <i class="fas fa-user viatico-field-icon"></i>
        <div>
            <small class="text-muted d-block">Chofer</small>
            <span>{{ $viatico->chofer ? trim($viatico->chofer->nombre . ' ' . $viatico->chofer->apellido) : '-' }}</span>
        </div>
    </div>
</div>

<div class="col-sm-6 col-lg-4">
    <div class="viatico-field">
        <i class="fas fa-hashtag viatico-field-icon"></i>
        <div>
            <small class="text-muted d-block">Numero Remision</small>
            <span>{{ $viatico->numero_remision }}</span>
        </div>
    </div>
</div>

<div class="col-sm-6 col-lg-4">
    <div class="viatico-field">
        <i class="fas fa-money-check-alt viatico-field-icon"></i>
        <div>
            <small class="text-muted d-block">Descripcion</small>
            <span>{{ $viatico->descripcion }}</span>
        </div>
    </div>
</div>

<div class="col-sm-6 col-lg-4">
    <div class="viatico-field">
        <i class="fas fa-clipboard viatico-field-icon"></i>
        <div>
            <small class="text-muted d-block">Orden de Carga</small>
            <span>{{ $viatico->ordenCarga ? 'OC-' . str_pad($viatico->ordenCarga->id, 6, '0', STR_PAD_LEFT) . ' - ' . $viatico->ordenCarga->destino : '-' }}</span>
        </div>
    </div>
</div>

<div class="col-sm-6 col-lg-4">
    <div class="viatico-field">
        <i class="fas fa-user-check viatico-field-icon"></i>
        <div>
            <small class="text-muted d-block">Cargado Por</small>
            <span>{{ $viatico->cargado_por }}</span>
        </div>
    </div>
</div>

<div class="col-sm-6 col-lg-4">
    <div class="viatico-field">
        <i class="fas fa-toggle-on viatico-field-icon"></i>
        <div>
            <small class="text-muted d-block">Estado</small>
            <span class="badge viatico-badge viatico-badge-{{ strtolower($viatico->estado) === 'activo' ? 'activo' : 'anulado' }}">
                {{ $viatico->estado }}
            </span>
        </div>
    </div>
</div>

<div class="col-sm-6 col-lg-4">
    <div class="viatico-field">
        <i class="fas fa-calendar-plus viatico-field-icon"></i>
        <div>
            <small class="text-muted d-block">Creado</small>
            <span>{{ $viatico->created_at ? $viatico->created_at->format('d/m/Y H:i') : '-' }}</span>
        </div>
    </div>
</div>

<div class="col-sm-6 col-lg-4">
    <div class="viatico-field">
        <i class="fas fa-calendar-check viatico-field-icon"></i>
        <div>
            <small class="text-muted d-block">Actualizado</small>
            <span>{{ $viatico->updated_at ? $viatico->updated_at->format('d/m/Y H:i') : '-' }}</span>
        </div>
    </div>
</div>

<!-- Documentos -->
<div class="col-12">
    <h6 class="viatico-section-title">Documentos</h6>
    <div class="d-flex flex-wrap">
        @forelse($viatico->documentos as $documento)
            <a href="{{ asset('documento_viatico/' . $documento->nombre_archivo) }}" target="_blank" class="documento-card">
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
    .viatico-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1.25rem;
        border-bottom: 1px solid #e9ecef;
    }
    .viatico-avatar {
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
    .viatico-badge {
        font-size: .8rem;
        padding: .4rem .75rem;
        border-radius: 1rem;
    }
    .viatico-badge-activo {
        background: #d4edda;
        color: #155724;
    }
    .viatico-badge-anulado {
        background: #f1f3f5;
        color: #6c757d;
    }
    .viatico-section-title {
        text-transform: uppercase;
        font-size: .75rem;
        font-weight: 700;
        letter-spacing: .05em;
        color: #6c757d;
        margin: 1.25rem 0 .75rem;
    }
    .viatico-field {
        display: flex;
        align-items: center;
        gap: .75rem;
        margin-bottom: 1.25rem;
    }
    .viatico-field-icon {
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
