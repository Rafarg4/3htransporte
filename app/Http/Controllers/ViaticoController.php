<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateViaticoRequest;
use App\Http\Requests\UpdateViaticoRequest;
use App\Models\Chofer;
use App\Models\DocumentoViatico;
use App\Models\Empresa;
use App\Models\OrdenCarga;
use App\Models\Viatico;
use App\Repositories\ViaticoRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;
use Barryvdh\DomPDF\Facade\Pdf;

class ViaticoController extends AppBaseController
{
    /** @var ViaticoRepository $viaticoRepository*/
    private $viaticoRepository;

    public function __construct(ViaticoRepository $viaticoRepo)
    {
        $this->viaticoRepository = $viaticoRepo;
    }

    /**
     * Display a listing of the Viatico.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $viaticos = $this->viaticoRepository->all()->load(['chofer', 'ordenCarga']);

        return view('viaticos.index')
            ->with('viaticos', $viaticos);
    }

    /**
     * Show the form for creating a new Viatico.
     *
     * @return Response
     */
    public function create()
    {
        return view('viaticos.create')
            ->with('choferes', $this->getChoferesParaSelect())
            ->with('ordenCargas', $this->getOrdenCargasParaSelect());
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
     * Build the list of OrdenCarga options for the select field.
     *
     * @return array
     */
    private function getOrdenCargasParaSelect()
    {
        return OrdenCarga::orderByDesc('id')->get()->mapWithKeys(function ($ordenCarga) {
            $numero = 'OC-' . str_pad($ordenCarga->id, 6, '0', STR_PAD_LEFT);

            return [$ordenCarga->id => $numero . ' - ' . $ordenCarga->destino];
        })->toArray();
    }

    /**
     * Store a newly created Viatico in storage.
     *
     * @param CreateViaticoRequest $request
     *
     * @return Response
     */
    public function store(CreateViaticoRequest $request)
    {
        $input = $request->except('documentos');
        $input['estado'] = 'Activo';

        $viatico = $this->viaticoRepository->create($input);

        $this->guardarDocumentos($request, $viatico->id);

        Flash::success('Viático guardado correctamente.');

        return redirect(route('viaticos.index'));
    }

    /**
     * Display the specified Viatico.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $viatico = $this->viaticoRepository->find($id);

        if (empty($viatico)) {
            Flash::error('Viático no encontrado');

            return redirect(route('viaticos.index'));
        }

        return view('viaticos.show')->with('viatico', $viatico->load('documentos'));
    }

    /**
     * Generate a PDF with the Viatico details.
     *
     * @param int $id
     *
     * @return Response
     */
    public function pdf($id)
    {
        $viatico = Viatico::with(['chofer', 'ordenCarga.camion.chofer'])->findOrFail($id);
        $empresa = Empresa::first();

        $pdf = Pdf::loadView('viaticos.pdf', [
            'viatico' => $viatico,
            'empresa' => $empresa,
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('Viatico ' . $viatico->numero . '.pdf');
    }

    /**
     * Show the form for editing the specified Viatico.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $viatico = $this->viaticoRepository->find($id);

        if (empty($viatico)) {
            Flash::error('Viático no encontrado');

            return redirect(route('viaticos.index'));
        }

        return view('viaticos.edit')
            ->with('viatico', $viatico->load('documentos'))
            ->with('choferes', $this->getChoferesParaSelect())
            ->with('ordenCargas', $this->getOrdenCargasParaSelect());
    }

    /**
     * Update the specified Viatico in storage.
     *
     * @param int $id
     * @param UpdateViaticoRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateViaticoRequest $request)
    {
        $viatico = $this->viaticoRepository->find($id);

        if (empty($viatico)) {
            Flash::error('Viático no encontrado');

            return redirect(route('viaticos.index'));
        }

        $viatico = $this->viaticoRepository->update($request->except('documentos'), $id);

        $this->guardarDocumentos($request, $viatico->id);

        Flash::success('Viático actualizado correctamente.');

        return redirect(route('viaticos.index'));
    }

    /**
     * Store the uploaded documents for the given Viatico.
     *
     * @param Request $request
     * @param int $idViatico
     *
     * @return void
     */
    private function guardarDocumentos(Request $request, $idViatico)
    {
        if (!$request->hasFile('documentos')) {
            return;
        }

        foreach ($request->file('documentos') as $documento) {
            if (!$documento->isValid()) {
                continue;
            }

            $nombreArchivo = uniqid() . '_' . $documento->getClientOriginalName();
            $documento->move(public_path('documento_viatico'), $nombreArchivo);

            DocumentoViatico::create([
                'id_viatico' => $idViatico,
                'nombre_archivo' => $nombreArchivo,
            ]);
        }
    }

    /**
     * Remove a single uploaded document from the given Viatico.
     *
     * @param Request $request
     * @param int $documento
     *
     * @return Response
     */
    public function destroyDocumento(Request $request, $documento)
    {
        $documento = DocumentoViatico::find($documento);

        if (empty($documento)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Documento no encontrado'], 404);
            }

            Flash::error('Documento no encontrado');

            return redirect()->back();
        }

        $path = public_path('documento_viatico/' . $documento->nombre_archivo);

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
     * Instead of deleting, mark the specified Viatico as Anulado.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $viatico = $this->viaticoRepository->find($id);

        if (empty($viatico)) {
            Flash::error('Viático no encontrado');

            return redirect(route('viaticos.index'));
        }

        $viatico->estado = 'Anulado';
        $viatico->save();

        Flash::success('Viático anulado correctamente.');

        return redirect(route('viaticos.index'));
    }
}
