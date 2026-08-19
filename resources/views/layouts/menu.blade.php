{{-- BUSCADOR SIDEBAR --}}
<div class="input-group" data-widget="sidebar-search">
    <input class="form-control form-control-sidebar"
           type="search"
           placeholder="Buscar..."
           aria-label="Search">
    <div class="input-group-append">
        <button class="btn btn-sidebar">
            <i class="fas fa-search fa-fw"></i>
        </button>
    </div>
</div>
<br>
{{-- DASHBOARD --}}
<li class="nav-item">
    <a href="{{ route('home') }}"
       class="nav-link {{ Request::is('home*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-tachometer-alt"></i>
        <p>Dashboard</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('clientes.index') }}"
       class="nav-link {{ Request::is('clientes*') ? 'active' : '' }}">
        <i class="nav-icon fa fa-users"></i>
        <p>Propietarios</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('chofers.index') }}"
       class="nav-link {{ Request::is('chofers*') ? 'active' : '' }}">
        <i class="nav-icon fa fa-id-card"></i>
        <p>Chóferes</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('camions.index') }}"
       class="nav-link {{ Request::is('camions*') ? 'active' : '' }}">
        <i class="nav-icon fa fa-truck"></i>
        <p>Camiones</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('productos.index') }}"
       class="nav-link {{ Request::is('productos*') ? 'active' : '' }}">
        <i class="nav-icon fa fa-cube"></i>
        <p>Productos</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('ordenCargas.index') }}"
       class="nav-link {{ Request::is('ordenCargas*') ? 'active' : '' }}">
        <i class="nav-icon fa fa-clipboard"></i>
        <p>Orden de Carga</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('valeCombustibles.index') }}"
       class="nav-link {{ Request::is('valeCombustibles*') ? 'active' : '' }}">
        <i class="nav-icon fa fa-gas-pump"></i>
        <p>Vales de Combustible</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('viaticos.index') }}"
       class="nav-link {{ Request::is('viaticos*') ? 'active' : '' }}">
        <i class="nav-icon fa fa-money-bill-wave"></i>
        <p>Viaticos</p>
    </a>
</li>
<li class="nav-item {{ Request::is('liquidacions*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link {{ Request::is('liquidacions*') ? 'active' : '' }}">
        <i class="nav-icon fa fa-file-invoice-dollar"></i>
        <p>
            Liquidaciones
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('liquidacions.index') }}"
               class="nav-link {{ Request::is('liquidacions') || Request::is('liquidacions/create') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Listado</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('liquidacions.reporte') }}"
               class="nav-link {{ Request::is('liquidacions/reporte*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Reporte</p>
            </a>
        </li>
    </ul>
</li>
<li class="nav-item">
    <a href="{{ route('proveedors.index') }}"
       class="nav-link {{ Request::is('proveedors*') ? 'active' : '' }}">
        <i class="nav-icon fa fa-truck"></i>
        <p>Proveedores</p>
    </a>
</li>
<li class="nav-item {{ Request::is('reportes*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link {{ Request::is('reportes*') ? 'active' : '' }}">
        <i class="nav-icon fa fa-bars" aria-hidden="true"></i>
        <p>
            Reportes
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('reportes.create') }}"
               class="nav-link {{ Request::is('reportes/create') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Cargar</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('reportes.generar') }}"
               class="nav-link {{ Request::is('reportes/generar*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Generar</p>
            </a>
        </li>
    </ul>
</li>
<li class="nav-item">
    <a href="{{ route('empresas.index') }}"
       class="nav-link {{ Request::is('empresas*') ? 'active' : '' }}">
        <i class="nav-icon fa fa-building"></i>
        <p>3H</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('users.index') }}"
       class="nav-link {{ Request::is('users*') ? 'active' : '' }}">
        <i class="nav-icon fa fa-user-cog"></i>
        <p>Usuarios</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('parametrizaciones.edit') }}"
       class="nav-link {{ Request::is('parametrizaciones*') ? 'active' : '' }}">
        <i class="nav-icon fa fa-sliders-h"></i>
        <p>Parametrizaciones</p>
    </a>
</li>
{{-- SALIR --}}
<li class="nav-item">
    <a href="#"
       class="nav-link"
       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="nav-icon fas fa-sign-out-alt"></i>
        <p>Salir</p>
    </a>
</li>


