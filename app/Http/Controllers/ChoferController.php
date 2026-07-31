<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateChoferRequest;
use App\Http\Requests\UpdateChoferRequest;
use App\Models\DocumentoChofer;
use App\Repositories\ChoferRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class ChoferController extends AppBaseController
{
    /** @var ChoferRepository $choferRepository*/
    private $choferRepository;

    public function __construct(ChoferRepository $choferRepo)
    {
        $this->choferRepository = $choferRepo;
    }

    /**
     * Display a listing of the Chofer.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $chofers = $this->choferRepository->all();

        return view('chofers.index')
            ->with('chofers', $chofers);
    }

    /**
     * Show the form for creating a new Chofer.
     *
     * @return Response
     */
    public function create()
    {
        return view('chofers.create');
    }

    /**
     * Store a newly created Chofer in storage.
     *
     * @param CreateChoferRequest $request
     *
     * @return Response
     */
    public function store(CreateChoferRequest $request)
    {
        $input = $request->except('documentos');
        $input['estado'] = $input['estado'] ?? 'Activo';

        $chofer = $this->choferRepository->create($input);

        $this->guardarDocumentos($request, $chofer->id);

        if ($request->expectsJson()) {
            return response()->json([
                'id' => $chofer->id,
                'nombre' => $chofer->nombre,
                'apellido' => $chofer->apellido,
                'documento' => $chofer->documento,
            ]);
        }

        Flash::success('Chofer guardado correctamente.');

        return redirect(route('chofers.index'));
    }

    /**
     * Display the specified Chofer.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $chofer = $this->choferRepository->find($id);

        if (empty($chofer)) {
            Flash::error('Chofer no encontrado');

            return redirect(route('chofers.index'));
        }

        return view('chofers.show')->with('chofer', $chofer);
    }

    /**
     * Show the form for editing the specified Chofer.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $chofer = $this->choferRepository->find($id);

        if (empty($chofer)) {
            Flash::error('Chofer no encontrado');

            return redirect(route('chofers.index'));
        }

        return view('chofers.edit')->with('chofer', $chofer);
    }

    /**
     * Update the specified Chofer in storage.
     *
     * @param int $id
     * @param UpdateChoferRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateChoferRequest $request)
    {
        $chofer = $this->choferRepository->find($id);

        if (empty($chofer)) {
            Flash::error('Chofer no encontrado');

            return redirect(route('chofers.index'));
        }

        $chofer = $this->choferRepository->update($request->except('documentos'), $id);

        $this->guardarDocumentos($request, $chofer->id);

        Flash::success('Chofer actualizado correctamente.');

        return redirect(route('chofers.index'));
    }

    /**
     * Store the uploaded documents for the given Chofer.
     *
     * @param Request $request
     * @param int $idChofer
     *
     * @return void
     */
    private function guardarDocumentos(Request $request, $idChofer)
    {
        if (!$request->hasFile('documentos')) {
            return;
        }

        foreach ($request->file('documentos') as $documento) {
            if (!$documento->isValid()) {
                continue;
            }

            $nombreArchivo = uniqid() . '_' . $documento->getClientOriginalName();
            $documento->move(public_path('documento_chofer'), $nombreArchivo);

            DocumentoChofer::create([
                'id_chofer' => $idChofer,
                'nombre_archivo' => $nombreArchivo,
            ]);
        }
    }

    /**
     * Remove a single uploaded document from the given Chofer.
     *
     * @param Request $request
     * @param int $documento
     *
     * @return Response
     */
    public function destroyDocumento(Request $request, $documento)
    {
        $documento = DocumentoChofer::find($documento);

        if (empty($documento)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Documento no encontrado'], 404);
            }

            Flash::error('Documento no encontrado');

            return redirect()->back();
        }

        $path = public_path('documento_chofer/' . $documento->nombre_archivo);

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
     * Toggle the estado (Activo/Inactivo) of the specified Chofer.
     *
     * @param int $id
     *
     * @return Response
     */
    public function cambiarEstado($id)
    {
        $chofer = $this->choferRepository->find($id);

        if (empty($chofer)) {
            Flash::error('Chofer no encontrado');

            return redirect(route('chofers.index'));
        }

        $chofer->estado = $chofer->estado === 'Activo' ? 'Inactivo' : 'Activo';
        $chofer->save();

        Flash::success('Chofer ' . ($chofer->estado === 'Activo' ? 'activado' : 'inactivado') . ' correctamente.');

        return redirect(route('chofers.index'));
    }

    /**
     * Remove the specified Chofer from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $chofer = $this->choferRepository->find($id);

        if (empty($chofer)) {
            Flash::error('Chofer no encontrado');

            return redirect(route('chofers.index'));
        }

        $this->choferRepository->delete($id);

        Flash::success('Chofer eliminado correctamente.');

        return redirect(route('chofers.index'));
    }
}
