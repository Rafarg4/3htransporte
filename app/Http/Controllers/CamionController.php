<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCamionRequest;
use App\Http\Requests\UpdateCamionRequest;
use App\Models\Chofer;
use App\Models\DocumentoCamion;
use App\Repositories\CamionRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class CamionController extends AppBaseController
{
    /** @var CamionRepository $camionRepository*/
    private $camionRepository;

    public function __construct(CamionRepository $camionRepo)
    {
        $this->camionRepository = $camionRepo;
    }

    /**
     * Display a listing of the Camion.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $camions = $this->camionRepository->all();

        return view('camions.index')
            ->with('camions', $camions);
    }

    /**
     * Show the form for creating a new Camion.
     *
     * @return Response
     */
    public function create()
    {
        $choferes = $this->getChoferesParaSelect();

        return view('camions.create')
            ->with('choferes', $choferes);
    }

    /**
     * Store a newly created Camion in storage.
     *
     * @param CreateCamionRequest $request
     *
     * @return Response
     */
    public function store(CreateCamionRequest $request)
    {
        $input = $request->except('documentos');

        $camion = $this->camionRepository->create($input);

        $this->guardarDocumentos($request, $camion->id);

        if ($request->expectsJson()) {
            return response()->json([
                'id' => $camion->id,
                'chapa' => $camion->chapa,
                'id_cliente' => $camion->id_cliente,
                'id_chofer' => $camion->id_chofer,
            ]);
        }

        Flash::success('Camión guardado correctamente.');

        return redirect(route('camions.index'));
    }

    /**
     * Display the specified Camion.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $camion = $this->camionRepository->find($id);

        if (empty($camion)) {
            Flash::error('Camión no encontrado');

            return redirect(route('camions.index'));
        }

        return view('camions.show')->with('camion', $camion);
    }

    /**
     * Show the form for editing the specified Camion.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $camion = $this->camionRepository->find($id);

        if (empty($camion)) {
            Flash::error('Camión no encontrado');

            return redirect(route('camions.index'));
        }

        $choferes = $this->getChoferesParaSelect();

        return view('camions.edit')
            ->with('camion', $camion)
            ->with('choferes', $choferes);
    }

    /**
     * Build the list of Chofer options for the select field.
     *
     * @return array
     */
    private function getChoferesParaSelect()
    {
        return Chofer::orderBy('nombre')->get()->mapWithKeys(function ($chofer) {
            return [$chofer->id => trim($chofer->nombre . ' ' . $chofer->apellido) . ' - ' . $chofer->documento];
        })->toArray();
    }

    /**
     * Update the specified Camion in storage.
     *
     * @param int $id
     * @param UpdateCamionRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCamionRequest $request)
    {
        $camion = $this->camionRepository->find($id);

        if (empty($camion)) {
            Flash::error('Camión no encontrado');

            return redirect(route('camions.index'));
        }

        $camion = $this->camionRepository->update($request->except('documentos'), $id);

        $this->guardarDocumentos($request, $camion->id);

        Flash::success('Camión actualizado correctamente.');

        return redirect(route('camions.index'));
    }

    /**
     * Store the uploaded documents for the given Camion.
     *
     * @param Request $request
     * @param int $idCamion
     *
     * @return void
     */
    private function guardarDocumentos(Request $request, $idCamion)
    {
        if (!$request->hasFile('documentos')) {
            return;
        }

        foreach ($request->file('documentos') as $documento) {
            if (!$documento->isValid()) {
                continue;
            }

            $nombreArchivo = uniqid() . '_' . $documento->getClientOriginalName();
            $documento->move(public_path('documento_camiones'), $nombreArchivo);

            DocumentoCamion::create([
                'id_camion' => $idCamion,
                'nombre_archivo' => $nombreArchivo,
            ]);
        }
    }

    /**
     * Remove a single uploaded document from the given Camion.
     *
     * @param Request $request
     * @param int $documento
     *
     * @return Response
     */
    public function destroyDocumento(Request $request, $documento)
    {
        $documento = DocumentoCamion::find($documento);

        if (empty($documento)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Documento no encontrado'], 404);
            }

            Flash::error('Documento no encontrado');

            return redirect()->back();
        }

        $path = public_path('documento_camiones/' . $documento->nombre_archivo);

        if (file_exists($path)) {
            unlink($path);
        }

        $documento->delete();

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        Flash::success('Documento eliminado correctamente.');

        return redirect()->back();
    }

    /**
     * Remove the specified Camion from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $camion = $this->camionRepository->find($id);

        if (empty($camion)) {
            Flash::error('Camión no encontrado');

            return redirect(route('camions.index'));
        }

        $this->camionRepository->delete($id);

        Flash::success('Camión eliminado correctamente.');

        return redirect(route('camions.index'));
    }
}
