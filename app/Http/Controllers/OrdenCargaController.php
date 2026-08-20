<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOrdenCargaRequest;
use App\Http\Requests\UpdateOrdenCargaRequest;
use App\Models\Camion;
use App\Models\Empresa;
use App\Models\OrdenCarga;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Repositories\OrdenCargaRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;
use Barryvdh\DomPDF\Facade\Pdf;

class OrdenCargaController extends AppBaseController
{
    /** @var OrdenCargaRepository $ordenCargaRepository*/
    private $ordenCargaRepository;

    public function __construct(OrdenCargaRepository $ordenCargaRepo)
    {
        $this->ordenCargaRepository = $ordenCargaRepo;
    }

    /**
     * Display a listing of the OrdenCarga.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $ordenCargas = $this->ordenCargaRepository->all();

        return view('orden_cargas.index')
            ->with('ordenCargas', $ordenCargas);
    }

    /**
     * Show the form for creating a new OrdenCarga.
     *
     * @return Response
     */
    public function create()
    {
        return view('orden_cargas.create')
            ->with($this->getListasParaSelect())
            ->with('proximoNumero', $this->getProximoNumero());
    }

    /**
     * Next numero, calculated from the amount of ordenes de carga already
     * registered (including soft-deleted ones, so a number is never reused).
     *
     * @return int
     */
    private function getProximoNumero()
    {
        return OrdenCarga::withTrashed()->count() + 1;
    }

    /**
     * Store a newly created OrdenCarga in storage.
     *
     * @param CreateOrdenCargaRequest $request
     *
     * @return Response
     */
    public function store(CreateOrdenCargaRequest $request)
    {
        $input = $request->all();
        $input['estado'] = 'Activo';
        $input['numero'] = $this->getProximoNumero();

        $ordenCarga = $this->ordenCargaRepository->create($input);

        Flash::success('Orden Carga guardada correctamente.');

        return redirect(route('ordenCargas.index'));
    }

    /**
     * Display the specified OrdenCarga.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $ordenCarga = $this->ordenCargaRepository->find($id);

        if (empty($ordenCarga)) {
            Flash::error('Orden Carga no encontrada');

            return redirect(route('ordenCargas.index'));
        }

        return view('orden_cargas.show')->with('ordenCarga', $ordenCarga);
    }

    /**
     * Generate a PDF with the Orden Carga details.
     *
     * @param int $id
     *
     * @return Response
     */
    public function pdf($id)
    {
        $ordenCarga = OrdenCarga::with(['proveedor', 'producto', 'camion.chofer', 'camion.propietario'])->findOrFail($id);
        $empresa = Empresa::first();
        $numero = str_pad($ordenCarga->numero, 6, '0', STR_PAD_LEFT);

        $pdf = Pdf::loadView('orden_cargas.pdf', [
            'ordenCarga' => $ordenCarga,
            'empresa' => $empresa,
            'numero' => $numero,
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('Orden de Carga ' . $numero . '.pdf');
    }

    /**
     * Show the form for editing the specified OrdenCarga.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $ordenCarga = $this->ordenCargaRepository->find($id);

        if (empty($ordenCarga)) {
            Flash::error('Orden Carga no encontrada');

            return redirect(route('ordenCargas.index'));
        }

        if (strtolower($ordenCarga->estado) === 'anulado') {
            Flash::error('La Orden Carga ya está anulada.');

            return redirect(route('ordenCargas.index'));
        }

        return view('orden_cargas.edit')
            ->with('ordenCarga', $ordenCarga)
            ->with($this->getListasParaSelect());
    }

    /**
     * Build the option lists used by the Proveedor, Producto and Camion selects.
     *
     * @return array
     */
    private function getListasParaSelect()
    {
        $proveedores = Proveedor::orderBy('nombre')->get()->mapWithKeys(function ($proveedor) {
            return [$proveedor->id => $proveedor->documento . ' - ' . $proveedor->nombre];
        });

        $productos = Producto::orderBy('nombre')->pluck('nombre', 'id');

        $camiones = Camion::orderBy('chapa')->pluck('chapa', 'id');

        return [
            'proveedores' => $proveedores,
            'productos' => $productos,
            'camiones' => $camiones,
        ];
    }

    /**
     * Update the specified OrdenCarga in storage.
     *
     * @param int $id
     * @param UpdateOrdenCargaRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateOrdenCargaRequest $request)
    {
        $ordenCarga = $this->ordenCargaRepository->find($id);

        if (empty($ordenCarga)) {
            Flash::error('Orden Carga no encontrada');

            return redirect(route('ordenCargas.index'));
        }

        if (strtolower($ordenCarga->estado) === 'anulado') {
            Flash::error('La Orden Carga ya está anulada.');

            return redirect(route('ordenCargas.index'));
        }

        $ordenCarga = $this->ordenCargaRepository->update($request->all(), $id);

        Flash::success('Orden Carga actualizada correctamente.');

        return redirect(route('ordenCargas.index'));
    }

    /**
     * Instead of deleting, mark the specified OrdenCarga as Anulado.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $ordenCarga = $this->ordenCargaRepository->find($id);

        if (empty($ordenCarga)) {
            Flash::error('Orden Carga no encontrada');

            return redirect(route('ordenCargas.index'));
        }

        if (strtolower($ordenCarga->estado) === 'anulado') {
            Flash::error('La Orden Carga ya está anulada.');

            return redirect(route('ordenCargas.index'));
        }

        $ordenCarga->estado = 'Anulado';
        $ordenCarga->save();

        Flash::success('Orden Carga anulada correctamente.');

        return redirect(route('ordenCargas.index'));
    }
}
