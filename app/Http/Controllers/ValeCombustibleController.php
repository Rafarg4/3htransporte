<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateValeCombustibleRequest;
use App\Http\Requests\UpdateValeCombustibleRequest;
use App\Models\Camion;
use App\Models\Empresa;
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
            ->with('camions', $camions);
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

        $valeCombustible = $this->valeCombustibleRepository->create($input);

        Flash::success('Vale Combustible saved successfully.');

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
            Flash::error('Vale Combustible not found');

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
            Flash::error('Vale Combustible not found');

            return redirect(route('valeCombustibles.index'));
        }

        $camions = $this->getCamionesParaSelect();

        return view('vale_combustibles.edit')
            ->with('valeCombustible', $valeCombustible)
            ->with('camions', $camions);
    }

    /**
     * Build the list of Camion options for the select field.
     *
     * @return array
     */
    private function getCamionesParaSelect()
    {
        return Camion::orderBy('chapa')->get()->mapWithKeys(function ($camion) {
            return [$camion->id => $camion->chapa];
        })->toArray();
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
            Flash::error('Vale Combustible not found');

            return redirect(route('valeCombustibles.index'));
        }

        $valeCombustible = $this->valeCombustibleRepository->update($request->all(), $id);

        Flash::success('Vale Combustible updated successfully.');

        return redirect(route('valeCombustibles.index'));
    }

    /**
     * Remove the specified ValeCombustible from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $valeCombustible = $this->valeCombustibleRepository->find($id);

        if (empty($valeCombustible)) {
            Flash::error('Vale Combustible not found');

            return redirect(route('valeCombustibles.index'));
        }

        $this->valeCombustibleRepository->delete($id);

        Flash::success('Vale Combustible deleted successfully.');

        return redirect(route('valeCombustibles.index'));
    }
}
