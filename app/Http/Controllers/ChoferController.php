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

        $chofer = $this->choferRepository->create($input);

        $this->guardarDocumentos($request, $chofer->id);

        Flash::success('Chofer saved successfully.');

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
            Flash::error('Chofer not found');

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
            Flash::error('Chofer not found');

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
            Flash::error('Chofer not found');

            return redirect(route('chofers.index'));
        }

        $chofer = $this->choferRepository->update($request->except('documentos'), $id);

        $this->guardarDocumentos($request, $chofer->id);

        Flash::success('Chofer updated successfully.');

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
                return response()->json(['message' => 'Documento not found'], 404);
            }

            Flash::error('Documento not found');

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

        Flash::success('Documento deleted successfully.');

        return redirect()->back();
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
            Flash::error('Chofer not found');

            return redirect(route('chofers.index'));
        }

        $this->choferRepository->delete($id);

        Flash::success('Chofer deleted successfully.');

        return redirect(route('chofers.index'));
    }
}
