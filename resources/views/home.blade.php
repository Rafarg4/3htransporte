@extends('layouts.app')

@section('content')

{{-- Font Awesome --}}
<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>

<div class="container-fluid">

    <h3 class="mb-4">Dashboard</h3>

    {{-- CARDS SUPERIORES --}}
    <div class="row">
    <div class="row mt-4">

        <div class="col-lg-3 col-md-6 mb-4">
            <a href="{{ route('clientes.index') }}" class="stat-card">
                <div class="stat-icon bg-primary">
                    <i class="fa fa-users"></i>
                </div>
                <div class="stat-content">
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
                    <span>Camiones</span>
                </div>
            </a>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <a href="{{ route('productos.index') }}" class="stat-card">
                <div class="stat-icon bg-success">
                    <i class="fa fa-cube"></i>
                </div>
                <div class="stat-content">
                    <span>Productos</span>
                </div>
            </a>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <a href="{{ route('ordenCargas.index') }}" class="stat-card">
                <div class="stat-icon bg-danger">
                    <i class="fa fa-clipboard"></i>
                </div>
                <div class="stat-content">
                    <span>Orden de Carga</span>
                </div>
            </a>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <a href="{{ route('proveedors.index') }}" class="stat-card">
                <div class="stat-icon bg-secondary">
                    <i class="fa fa-truck"></i>
                </div>
                <div class="stat-content">
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

    </div>
</div>

{{-- ESTILOS UNIFICADOS --}}
<style>
.stat-card {
    background: #fff;
    border-radius: 8px;
    display: flex;
    align-items: center;
    padding: 18px;
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
    width: 58px;
    height: 58px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 26px;
    margin-right: 16px;
}

.stat-content h4 {
    margin: 0;
    font-size: 22px;
    font-weight: 600;
    color: #2c3e50;
}

.stat-content span {
    font-size: 14px;
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
