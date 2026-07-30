<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateLiquidacionRequest;
use App\Models\Camion;
use App\Models\Cliente;
use App\Models\Liquidacion;
use App\Models\LiquidacionCombustible;
use App\Models\LiquidacionDescuento;
use App\Models\LiquidacionFlete;
use App\Models\LiquidacionGastoAdministrativo;
use App\Models\LiquidacionViatico;
use App\Models\Empresa;
use App\Repositories\LiquidacionRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Flash;
use Response;
use Barryvdh\DomPDF\Facade\Pdf;

class LiquidacionController extends AppBaseController
{
    /** @var LiquidacionRepository $liquidacionRepository*/
    private $liquidacionRepository;

    public function __construct(LiquidacionRepository $liquidacionRepo)
    {
        $this->liquidacionRepository = $liquidacionRepo;
    }

    /**
     * Display a listing of the Liquidacion.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $liquidacions = Liquidacion::with([
            'cliente',
            'fletes.camion',
            'descuentos.camion',
            'gastosAdministrativos.camion',
            'viaticos.camion',
            'combustibles.camion',
        ])->orderByDesc('id')->get();

        return view('liquidacions.index')
            ->with('liquidacions', $liquidacions);
    }

    /**
     * Show the form for creating a new Liquidacion.
     *
     * @return Response
     */
    public function create()
    {
        return view('liquidacions.create')
            ->with('clientes', $this->getClientesParaSelect())
            ->with('camions', $this->getCamionesParaSelect());
    }

    /**
     * Build the list of Cliente options for the select field.
     *
     * @return array
     */
    private function getClientesParaSelect()
    {
        return Cliente::orderBy('nombre')->get()->mapWithKeys(function ($cliente) {
            return [$cliente->id => trim($cliente->nombre . ' ' . $cliente->apellido)];
        })->toArray();
    }

    /**
     * Build the list of Camion (chapa) options for the select field.
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
     * Store a newly created Liquidacion in storage.
     *
     * @param CreateLiquidacionRequest $request
     *
     * @return Response
     */
    public function store(CreateLiquidacionRequest $request)
    {
        DB::transaction(function () use ($request) {
            $liquidacion = Liquidacion::create([
                'id_cliente' => $request->input('id_cliente'),
                'fecha' => $request->input('fecha'),
                'estado' => 'Activo',
            ]);

            $this->guardarLinea($liquidacion, LiquidacionFlete::class, $request->input('flete', []), ['id_camion', 'fecha', 'tramo', 'kg_origen', 'kg_destino', 'precio', 'valor']);
            $this->guardarLinea($liquidacion, LiquidacionDescuento::class, $request->input('descuento', []), ['id_camion', 'fecha', 'concepto', 'valor']);
            $this->guardarLinea($liquidacion, LiquidacionViatico::class, $request->input('viatico', []), ['id_camion', 'fecha', 'descripcion', 'valor']);
            $this->guardarLinea($liquidacion, LiquidacionCombustible::class, $request->input('combustible', []), ['id_camion', 'fecha', 'nombre_estacion', 'litros', 'precio', 'valor']);
            $this->guardarLinea($liquidacion, LiquidacionGastoAdministrativo::class, $request->input('gasto_administrativo', []), ['id_camion', 'fecha', 'concepto', 'valor']);
        });

        Flash::success('Liquidación guardada correctamente.');

        return redirect(route('liquidacions.index'));
    }

    /**
     * Create the single child row for a section of the Liquidacion form,
     * skipping it entirely if the user left it empty.
     *
     * @param Liquidacion $liquidacion
     * @param string $modelClass
     * @param array $fila
     * @param array $campos
     *
     * @return void
     */
    private function guardarLinea(Liquidacion $liquidacion, string $modelClass, array $fila, array $campos)
    {
        $vacia = collect($campos)->every(function ($campo) use ($fila) {
            return empty($fila[$campo] ?? null);
        });

        if ($vacia) {
            return;
        }

        $datos = collect($fila)->only($campos)->toArray();
        $datos['id_liquidacion'] = $liquidacion->id;

        $modelClass::create($datos);
    }

    /**
     * Generate a PDF with the Liquidacion details.
     *
     * @param int $id
     *
     * @return Response
     */
    public function pdf($id)
    {
        $liquidacion = Liquidacion::with([
            'cliente',
            'fletes.camion',
            'descuentos.camion',
            'viaticos.camion',
            'combustibles.camion',
            'gastosAdministrativos.camion',
        ])->findOrFail($id);

        $empresa = Empresa::first();

        $pdf = Pdf::loadView('liquidacions.pdf', [
            'liquidacion' => $liquidacion,
            'empresa' => $empresa,
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('Liquidacion ' . $liquidacion->id . '.pdf');
    }

    /**
     * Instead of deleting, mark the specified Liquidacion as Anulado.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $liquidacion = $this->liquidacionRepository->find($id);

        if (empty($liquidacion)) {
            Flash::error('Liquidación no encontrada');

            return redirect(route('liquidacions.index'));
        }

        $liquidacion->estado = 'Anulado';
        $liquidacion->save();

        Flash::success('Liquidación anulada correctamente.');

        return redirect(route('liquidacions.index'));
    }
}
