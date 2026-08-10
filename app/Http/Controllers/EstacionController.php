<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateEstacionRequest;
use App\Repositories\EstacionRepository;
use App\Http\Controllers\AppBaseController;
use Flash;
use Response;

/**
 * Sin index/edit/show propios a proposito: las Estaciones no tienen una
 * pantalla de administracion propia, se cargan solo desde el "+" del campo
 * Nombre Estacion en el formulario de Vale Combustible (ver fields.blade.php).
 */
class EstacionController extends AppBaseController
{
    /** @var EstacionRepository $estacionRepository*/
    private $estacionRepository;

    public function __construct(EstacionRepository $estacionRepo)
    {
        $this->estacionRepository = $estacionRepo;
    }

    /**
     * Store a newly created Estacion in storage.
     *
     * @param CreateEstacionRequest $request
     *
     * @return Response
     */
    public function store(CreateEstacionRequest $request)
    {
        $estacion = $this->estacionRepository->create($request->only('nombre'));

        if ($request->expectsJson()) {
            return response()->json(['id' => $estacion->id, 'nombre' => $estacion->nombre]);
        }

        Flash::success('Estación guardada correctamente.');

        return redirect()->back();
    }
}
