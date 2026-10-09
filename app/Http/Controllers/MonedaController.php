<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMonedaRequest;
use App\Http\Requests\UpdateMonedaRequest;
use App\Repositories\MonedaRepository;
use App\Http\Controllers\AppBaseController;
use App\Models\Moneda;
use Illuminate\Http\Request;
use Flash;
use Response;

class MonedaController extends AppBaseController
{
    /** @var MonedaRepository $monedaRepository*/
    private $monedaRepository;

    public function __construct(MonedaRepository $monedaRepo)
    {
        $this->monedaRepository = $monedaRepo;
    }

    /**
     * Display a listing of the Moneda.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $monedas = Moneda::actuales();

        return view('monedas.index')
            ->with('monedas', $monedas)
            ->with('faltantes', $this->monedasFaltantes());
    }

    /**
     * Show the form for creating a new Moneda.
     *
     * @return Response
     */
    public function create()
    {
        $faltantes = $this->monedasFaltantes();

        if (empty($faltantes)) {
            Flash::info('Todas las monedas ya están cargadas. Para cambiar la cotización usá el botón "Actualizar cotización".');

            return redirect(route('monedas.index'));
        }

        return view('monedas.create')->with('opciones', $faltantes);
    }

    /**
     * Monedas que todavia no tienen ninguna cotizacion cargada.
     */
    private function monedasFaltantes()
    {
        $cargadas = Moneda::pluck('tipo_moneda')->unique()->all();

        return array_diff_key(Moneda::NOMBRES, array_flip($cargadas));
    }

    /**
     * Store a newly created Moneda in storage.
     *
     * @param CreateMonedaRequest $request
     *
     * @return Response
     */
    public function store(CreateMonedaRequest $request)
    {
        $input = $request->all();

        // Si la moneda ya existe no se duplica: se actualiza su cotizacion.
        $existente = Moneda::where('tipo_moneda', $input['tipo_moneda'])->orderByDesc('id')->first();

        if ($existente) {
            $this->monedaRepository->update(['monto' => $input['monto']], $existente->id);

            Flash::info('La moneda ' . $existente->nombre . ' ya existía, así que se actualizó su cotización a ' . $input['monto'] . '.');

            return redirect(route('monedas.index'));
        }

        $moneda = $this->monedaRepository->create($input);

        Flash::success('Moneda ' . $moneda->nombre . ' creada correctamente.');

        return redirect(route('monedas.index'));
    }

    /**
     * Display the specified Moneda.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $moneda = $this->monedaRepository->find($id);

        if (empty($moneda)) {
            Flash::error('Moneda not found');

            return redirect(route('monedas.index'));
        }

        return view('monedas.show')->with('moneda', $moneda);
    }

    /**
     * Show the form for editing the specified Moneda.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $moneda = $this->monedaRepository->find($id);

        if (empty($moneda)) {
            Flash::error('Moneda not found');

            return redirect(route('monedas.index'));
        }

        return view('monedas.edit')->with('moneda', $moneda);
    }

    /**
     * Update the specified Moneda in storage.
     *
     * @param int $id
     * @param UpdateMonedaRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMonedaRequest $request)
    {
        $moneda = $this->monedaRepository->find($id);

        if (empty($moneda)) {
            Flash::error('Moneda not found');

            return redirect(route('monedas.index'));
        }

        // Solo se cambia la cotizacion; el tipo de moneda queda fijo.
        $moneda = $this->monedaRepository->update(['monto' => $request->input('monto')], $id);

        Flash::success('Cotización de ' . $moneda->nombre . ' actualizada a ' . $moneda->monto . '.');

        return redirect(route('monedas.index'));
    }

    /**
     * Remove the specified Moneda from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $moneda = $this->monedaRepository->find($id);

        if (empty($moneda)) {
            Flash::error('Moneda not found');

            return redirect(route('monedas.index'));
        }

        $this->monedaRepository->delete($id);

        Flash::success('Moneda deleted successfully.');

        return redirect(route('monedas.index'));
    }
}
