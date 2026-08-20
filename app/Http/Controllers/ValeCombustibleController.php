<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateValeCombustibleRequest;
use App\Http\Requests\UpdateValeCombustibleRequest;
use App\Models\Camion;
use App\Models\Direccion;
use App\Models\Empresa;
use App\Models\Estacion;
use App\Models\ProductoVale;
use App\Models\ValeCombustible;
use App\Repositories\ValeCombustibleRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;
use Barryvdh\DomPDF\Facade\Pdf;

class ValeCombustibleController extends AppBaseController
{
    /** @var ValeCombustibleRepository $valeCombustibleRepository*/
    private $valeCombustibleRepository;

    public function __construct(ValeCombustibleRepository $valeCombustibleRepo)
    {
        $this->valeCombustibleRepository = $valeCombustibleRepo;
    }

    /**
     * Display a listing of the ValeCombustible.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $valeCombustibles = $this->valeCombustibleRepository->all()->load('camion');

        return view('vale_combustibles.index')
            ->with('valeCombustibles', $valeCombustibles);
    }

    /**
     * Show the form for creating a new ValeCombustible.
     *
     * @return Response
     */
    public function create()
    {
        $camions = $this->getCamionesParaSelect();

        return view('vale_combustibles.create')
            ->with('camions', $camions)
            ->with('estaciones', $this->getEstacionesParaSelect())
            ->with('direcciones', $this->getDireccionesParaSelect())
            ->with('productoVales', $this->getProductoValesParaSelect())
            ->with('proximoNumeroVale', $this->getProximoNumeroVale());
    }

    /**
     * Store a newly created ValeCombustible in storage.
     *
     * @param CreateValeCombustibleRequest $request
     *
     * @return Response
     */
    public function store(CreateValeCombustibleRequest $request)
    {
        $input = $request->all();
        $input['realizado_por'] = auth()->user()->name;
        $input['numero_vale'] = $this->getProximoNumeroVale();
        $input['estado'] = 'Activo';

        $valeCombustible = $this->valeCombustibleRepository->create($input);

        Flash::success('Vale Combustible guardado correctamente.');

        return redirect(route('valeCombustibles.index'));
    }

    /**
     * Display the specified ValeCombustible.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $valeCombustible = $this->valeCombustibleRepository->find($id);

        if (empty($valeCombustible)) {
            Flash::error('Vale Combustible no encontrado');

            return redirect(route('valeCombustibles.index'));
        }

        return view('vale_combustibles.show')->with('valeCombustible', $valeCombustible);
    }

    /**
     * Generate a PDF for the specified ValeCombustible.
     *
     * @param int $id
     *
     * @return Response
     */
    public function pdf($id)
    {
        $valeCombustible = ValeCombustible::with(['camion.chofer'])->findOrFail($id);
        $empresa = Empresa::first();

        $pdf = Pdf::loadView('vale_combustibles.pdf', [
            'valeCombustible' => $valeCombustible,
            'empresa' => $empresa,
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('Vale Combustible ' . $valeCombustible->numero_vale . '.pdf');
    }

    /**
     * Show the form for editing the specified ValeCombustible.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $valeCombustible = $this->valeCombustibleRepository->find($id);

        if (empty($valeCombustible)) {
            Flash::error('Vale Combustible no encontrado');

            return redirect(route('valeCombustibles.index'));
        }

        $camions = $this->getCamionesParaSelect($valeCombustible->id_camion);

        return view('vale_combustibles.edit')
            ->with('valeCombustible', $valeCombustible)
            ->with('camions', $camions)
            ->with('estaciones', $this->getEstacionesParaSelect())
            ->with('direcciones', $this->getDireccionesParaSelect())
            ->with('productoVales', $this->getProductoValesParaSelect());
    }

    /**
     * Build the list of Estacion (Nombre Estacion) options for the select field.
     *
     * @return array
     */
    private function getEstacionesParaSelect()
    {
        return Estacion::orderBy('nombre')->pluck('nombre', 'nombre')->toArray();
    }

    /**
     * Build the list of Direccion options for the select field.
     *
     * @return array
     */
    private function getDireccionesParaSelect()
    {
        return Direccion::orderBy('nombre')->pluck('nombre', 'nombre')->toArray();
    }

    /**
     * Build the list of ProductoVale (Producto) options for the select field.
     *
     * @return array
     */
    private function getProductoValesParaSelect()
    {
        return ProductoVale::orderBy('nombre')->pluck('nombre', 'nombre')->toArray();
    }

    /**
     * Build the list of Camion options for the select field.
     *
     * Only Activo camiones are listed, except $idCamionActual (the camion
     * already assigned to the ValeCombustible being edited), which is kept
     * even if it was inactivado in the meantime so the form doesn't lose
     * its value.
     *
     * @param int|null $idCamionActual
     *
     * @return array
     */
    private function getCamionesParaSelect($idCamionActual = null)
    {
        return Camion::where(function ($query) use ($idCamionActual) {
                $query->where('estado', 'Activo');

                if ($idCamionActual) {
                    $query->orWhere('id', $idCamionActual);
                }
            })
            ->orderBy('chapa')
            ->get()
            ->mapWithKeys(function ($camion) {
                return [$camion->id => $camion->chapa];
            })->toArray();
    }

    /**
     * Next numero_vale, calculated from the amount of vales already
     * registered (including soft-deleted vales, so a number is never reused).
     *
     * @return int
     */
    private function getProximoNumeroVale()
    {
        return ValeCombustible::withTrashed()->count() + 1;
    }

    /**
     * Update the specified ValeCombustible in storage.
     *
     * @param int $id
     * @param UpdateValeCombustibleRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateValeCombustibleRequest $request)
    {
        $valeCombustible = $this->valeCombustibleRepository->find($id);

        if (empty($valeCombustible)) {
            Flash::error('Vale Combustible no encontrado');

            return redirect(route('valeCombustibles.index'));
        }

        $valeCombustible = $this->valeCombustibleRepository->update($request->all(), $id);

        Flash::success('Vale Combustible actualizado correctamente.');

        return redirect(route('valeCombustibles.index'));
    }

    /**
     * Instead of deleting, mark the specified ValeCombustible as Anulado.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $valeCombustible = $this->valeCombustibleRepository->find($id);

        if (empty($valeCombustible)) {
            Flash::error('Vale Combustible no encontrado');

            return redirect(route('valeCombustibles.index'));
        }

        $valeCombustible->estado = 'Anulado';
        $valeCombustible->save();

        Flash::success('Vale Combustible anulado correctamente.');

        return redirect(route('valeCombustibles.index'));
    }
}
