@extends('layouts.app')

@section('content')

{{-- Font Awesome --}}
<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>

<div class="container-fluid">

    <h3 class="mb-4">Dashboard</h3>

    {{-- CARDS SUPERIORES --}}
    <div class="row">

        <div class="col-lg-3 col-md-6 mb-4">
            <a href="{{ route('clientes.index') }}" class="stat-card">
                <div class="stat-icon bg-primary">
                    <i class="fa fa-users"></i>
                </div>
                <div class="stat-content">
                    <h4>{{ $totalPropietarios }}</h4>
                    <span>Propietarios</span>
                </div>
            </a>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <a href="{{ route('chofers.index') }}" class="stat-card">
                <div class="stat-icon bg-info">
                    <i class="fa fa-id-card"></i>
                </div>
                <div class="stat-content">
                    <h4>{{ $totalChoferes }}</h4>
                    <span>Chóferes</span>
                </div>
            </a>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <a href="{{ route('camions.index') }}" class="stat-card">
                <div class="stat-icon bg-warning">
                    <i class="fa fa-truck"></i>
                </div>
                <div class="stat-content">
                    <h4>{{ $totalCamiones }}</h4>
                    <span>Camiones</span>
                </div>
            </a>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <a href="{{ route('ordenCargas.index') }}" class="stat-card">
                <div class="stat-icon bg-danger">
                    <i class="fa fa-clipboard"></i>
                </div>
                <div class="stat-content">
                    <h4>{{ $ordenesActivas }}</h4>
                    <span>Órdenes de Carga Activas</span>
                </div>
            </a>
        </div>

    </div>

    {{-- ACCESOS RAPIDOS --}}
    <div class="row mt-4">

        <div class="col-lg-3 col-md-6 mb-4">
            <a href="{{ route('productos.index') }}" class="stat-card">
                <div class="stat-icon bg-success">
                    <i class="fa fa-cube"></i>
                </div>
                <div class="stat-content">
                    <h4>{{ $totalProductos }}</h4>
                    <span>Productos</span>
                </div>
            </a>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <a href="{{ route('proveedors.index') }}" class="stat-card">
                <div class="stat-icon bg-secondary">
                    <i class="fa fa-truck"></i>
                </div>
                <div class="stat-content">
                    <h4>{{ $totalProveedores }}</h4>
                    <span>Proveedores</span>
                </div>
            </a>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <a href="{{ route('empresas.index') }}" class="stat-card">
                <div class="stat-icon bg-dark">
                    <i class="fa fa-building"></i>
                </div>
                <div class="stat-content">
                    <span>Empresas</span>
                </div>
            </a>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <a href="{{ route('valeCombustibles.index') }}" class="stat-card">
                <div class="stat-icon bg-info">
                    <i class="fa fa-gas-pump"></i>
                </div>
                <div class="stat-content">
                    <h4>{{ $totalVales }}</h4>
                    <span>Vale Combustible</span>
                </div>
            </a>
        </div>

    </div>

    {{-- ULTIMOS REGISTROS --}}
    <div class="row mt-4">

        {{-- ULTIMAS ORDENES DE CARGA --}}
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white fw-bold d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-clipboard-list text-danger me-2"></i> Últimas Órdenes de Carga</span>
                    <a href="{{ route('ordenCargas.index') }}" class="btn btn-sm btn-outline-secondary">Ver más</a>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Proveedor</th>
                                <th>Producto</th>
                                <th>Camión</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ultimasOrdenes as $ordenCarga)
                                <tr>
                                    <td>{{ $ordenCarga->proveedor->nombre ?? '-' }}</td>
                                    <td>{{ $ordenCarga->producto->nombre ?? '-' }}</td>
                                    <td>{{ $ordenCarga->camion->chapa ?? '-' }}</td>
                                    <td>
                                        <span class="badge {{ $ordenCarga->estado === 'Anulado' ? 'bg-secondary' : 'bg-success' }}">
                                            {{ $ordenCarga->estado }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">Sin órdenes de carga registradas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ULTIMOS VALES DE COMBUSTIBLE --}}
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white fw-bold d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-gas-pump text-info me-2"></i> Últimos Vales de Combustible</span>
                    <a href="{{ route('valeCombustibles.index') }}" class="btn btn-sm btn-outline-secondary">Ver más</a>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>N° Vale</th>
                                <th>Camión</th>
                                <th>Importe</th>
                                <th>Realizado Por</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ultimosVales as $valeCombustible)
                                <tr>
                                    <td>{{ $valeCombustible->numero_vale }}</td>
                                    <td>{{ $valeCombustible->camion->chapa ?? '-' }}</td>
                                    <td>{{ number_format($valeCombustible->importe, 0, ',', '.') }}</td>
                                    <td>{{ $valeCombustible->realizado_por }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">Sin vales de combustible registrados.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

</div>

{{-- ESTILOS UNIFICADOS --}}
<style>
.stat-card {
    background: #fff;
    border-radius: 8px;
    display: flex;
    align-items: center;
    padding: 12px;
    height: 100%;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    text-decoration: none;
    color: inherit;
    transition: transform .15s ease, box-shadow .15s ease;
}

a.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 14px rgba(0,0,0,0.15);
    color: inherit;
}

.stat-icon {
    width: 42px;
    height: 42px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 18px;
    margin-right: 12px;
}

.stat-content h4 {
    margin: 0;
    font-size: 16px;
    font-weight: 600;
    color: #2c3e50;
}

.stat-content span {
    font-size: 12px;
    color: #6c757d;
}

/* colores */
.bg-info      { background-color: #17a2b8; }
.bg-success   { background-color: #28a745; }
.bg-warning   { background-color: #ffc107; }
.bg-danger    { background-color: #dc3545; }
.bg-primary   { background-color: #007bff; }
.bg-secondary { background-color: #6c757d; }
.bg-dark      { background-color: #343a40; }
</style>

@endsection
