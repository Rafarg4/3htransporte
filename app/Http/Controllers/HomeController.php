<?php

namespace App\Http\Controllers;

use App\Models\Camion;
use App\Models\Chofer;
use App\Models\Cliente;
use App\Models\OrdenCarga;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\ValeCombustible;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $totalCamiones = Camion::count();
        $totalChoferes = Chofer::count();
        $totalPropietarios = Cliente::count();
        $ordenesActivas = OrdenCarga::where('estado', '!=', 'Anulado')->count();
        $totalProductos = Producto::count();
        $totalProveedores = Proveedor::count();
        $totalVales = ValeCombustible::count();

        $ultimasOrdenes = OrdenCarga::with(['proveedor', 'producto', 'camion'])
            ->latest()
            ->take(5)
            ->get();

        $ultimosVales = ValeCombustible::with('camion')
            ->latest()
            ->take(5)
            ->get();

        return view('home')
            ->with('totalCamiones', $totalCamiones)
            ->with('totalChoferes', $totalChoferes)
            ->with('totalPropietarios', $totalPropietarios)
            ->with('ordenesActivas', $ordenesActivas)
            ->with('totalProductos', $totalProductos)
            ->with('totalProveedores', $totalProveedores)
            ->with('totalVales', $totalVales)
            ->with('ultimasOrdenes', $ultimasOrdenes)
            ->with('ultimosVales', $ultimosVales);
    }
}
