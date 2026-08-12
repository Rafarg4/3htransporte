<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDireccionRequest;
use App\Repositories\DireccionRepository;
use App\Http\Controllers\AppBaseController;
use Flash;
use Response;

/**
 * Sin index/edit/show propios a proposito: las Direcciones no tienen una
 * pantalla de administracion propia, se cargan solo desde el "+" del campo
 * Direccion en el formulario de Vale Combustible (ver fields.blade.php).
 */
class DireccionController extends AppBaseController
{
    /** @var DireccionRepository $direccionRepository*/
    private $direccionRepository;

    public function __construct(DireccionRepository $direccionRepo)
    {
        $this->direccionRepository = $direccionRepo;
    }

    /**
     * Store a newly created Direccion in storage.
     *
     * @param CreateDireccionRequest $request
     *
     * @return Response
     */
    public function store(CreateDireccionRequest $request)
    {
        $direccion = $this->direccionRepository->create($request->only('nombre'));

        if ($request->expectsJson()) {
            return response()->json(['id' => $direccion->id, 'nombre' => $direccion->nombre]);
        }

        Flash::success('Dirección guardada correctamente.');

        return redirect()->back();
    }
}
