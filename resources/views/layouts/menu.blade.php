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
    <a href="{{ route('proveedors.index') }}"
       class="nav-link {{ Request::is('proveedors*') ? 'active' : '' }}">
        <i class="nav-icon fa fa-truck"></i>
        <p>Proveedores</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('empresas.index') }}"
       class="nav-link {{ Request::is('empresas*') ? 'active' : '' }}">
        <i class="nav-icon fa fa-building"></i>
        <p>Empresas</p>
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



