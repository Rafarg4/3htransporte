<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductoValeRequest;
use App\Repositories\ProductoValeRepository;
use App\Http\Controllers\AppBaseController;
use Flash;
use Response;

/**
 * Sin index/edit/show propios a proposito: los Productos de Vale no tienen una
 * pantalla de administracion propia, se cargan solo desde el "+" del campo
 * Producto en el formulario de Vale Combustible (ver fields.blade.php).
 */
class ProductoValeController extends AppBaseController
{
    /** @var ProductoValeRepository $productoValeRepository*/
    private $productoValeRepository;

    public function __construct(ProductoValeRepository $productoValeRepo)
    {
        $this->productoValeRepository = $productoValeRepo;
    }

    /**
     * Store a newly created ProductoVale in storage.
     *
     * @param CreateProductoValeRequest $request
     *
     * @return Response
     */
    public function store(CreateProductoValeRequest $request)
    {
        $productoVale = $this->productoValeRepository->create($request->only('nombre'));

        if ($request->expectsJson()) {
            return response()->json(['id' => $productoVale->id, 'nombre' => $productoVale->nombre]);
        }

        Flash::success('Producto guardado correctamente.');

        return redirect()->back();
    }
}
