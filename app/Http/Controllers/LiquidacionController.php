<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateLiquidacionRequest;
use App\Models\Camion;
use App\Models\Chofer;
use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\Liquidacion;
use App\Models\LiquidacionDescuento;
use App\Models\LiquidacionFlete;
use App\Models\LiquidacionGastoAdministrativo;
use App\Models\OrdenCarga;
use App\Models\Parametrizacion;
use App\Models\ValeCombustible;
use App\Models\Viatico;
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
            'camion',
            'chofer',
            'ordenCarga',
            'fletes',
            'descuentos',
            'gastosAdministrativos',
            'viaticos',
            'combustibles',
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
            ->with('camions', Camion::orderBy('chapa')->get())
            ->with('choferes', $this->getChoferesParaSelect())
            ->with('ordenCargas', OrdenCarga::whereNull('liquidado')->orderByDesc('id')->get())
            ->with('viaticosDisponibles', $this->getViaticosDisponibles())
            ->with('valeCombustiblesDisponibles', $this->getValeCombustiblesDisponibles())
            ->with('parametrizacion', Parametrizacion::actual());
    }

    /**
     * Build the list of Cliente (Propietario) options for the select field.
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
     * Viaticos activos que todavia no fueron usados en ninguna liquidacion,
     * disponibles para tildar (filtrados por el Chofer de la cabecera en JS).
     *
     * @return \Illuminate\Support\Collection
     */
    private function getViaticosDisponibles()
    {
        return Viatico::with('chofer')
            ->whereNull('liquidado')
            ->where('estado', 'Activo')
            ->orderByDesc('id')
            ->get();
    }

    /**
     * Vales de combustible que todavia no fueron usados en ninguna liquidacion,
     * disponibles para tildar (filtrados por el Camion de la cabecera en JS).
     *
     * @return \Illuminate\Support\Collection
     */
    private function getValeCombustiblesDisponibles()
    {
        return ValeCombustible::with('camion')
            ->whereNull('liquidado')
            ->orderByDesc('id')
            ->get();
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
            $fletesPorCamion = $request->input('flete', []);
            $ordenCargaPorCamion = $request->input('orden_carga', []);
            $idCamionPrincipal = $request->input('id_camion');

            $idOrdenCargaCabecera = $ordenCargaPorCamion[$idCamionPrincipal] ?? null;
            if (empty($idOrdenCargaCabecera)) {
                $idOrdenCargaCabecera = collect($ordenCargaPorCamion)->first(function ($valor) {
                    return !empty($valor);
                });
            }

            $liquidacion = Liquidacion::create([
                'id_cliente' => $request->input('id_cliente'),
                'id_camion' => $idCamionPrincipal,
                'id_chofer' => $request->input('id_chofer'),
                'id_orden_carga' => $idOrdenCargaCabecera,
                'fecha' => $request->input('fecha'),
                'estado' => 'Activo',
                'facturado' => $request->input('facturado', 'No'),
            ]);

            $fechaCabecera = $request->input('fecha');

            foreach ($fletesPorCamion as $idCamion => $filaFlete) {
                $idOrdenCarga = $ordenCargaPorCamion[$idCamion] ?? null;

                $this->guardarLinea(
                    $liquidacion,
                    LiquidacionFlete::class,
                    $filaFlete,
                    ['fecha', 'tramo', 'kg_origen', 'kg_destino', 'diferencia', 'precio', 'valor', 'recargo_tolerancia', 'recargo_precio'],
                    $fechaCabecera,
                    ['id_camion' => $idCamion, 'id_orden_carga' => $idOrdenCarga]
                );

                if (!empty($idOrdenCarga)) {
                    OrdenCarga::where('id', $idOrdenCarga)
                        ->whereNull('liquidado')
                        ->update(['liquidado' => 'S']);
                }
            }

            foreach ($request->input('descuento_auto', []) as $idCamion => $filaDescuentoAuto) {
                $this->guardarLinea(
                    $liquidacion,
                    LiquidacionDescuento::class,
                    $filaDescuentoAuto,
                    ['fecha', 'valor'],
                    $fechaCabecera,
                    ['id_camion' => $idCamion, 'concepto' => 'Faltante de Carga']
                );
            }

            $this->guardarLinea($liquidacion, LiquidacionDescuento::class, $request->input('descuento', []), ['fecha', 'concepto', 'valor'], $fechaCabecera);
            $this->guardarLinea($liquidacion, LiquidacionGastoAdministrativo::class, $request->input('gasto_administrativo', []), ['fecha', 'concepto', 'valor'], $fechaCabecera);

            Viatico::whereIn('id', $request->input('viatico_ids', []))
                ->whereNull('liquidado')
                ->update(['id_liquidacion' => $liquidacion->id, 'liquidado' => 'S']);

            ValeCombustible::whereIn('id', $request->input('vale_combustible_ids', []))
                ->whereNull('liquidado')
                ->update(['id_liquidacion' => $liquidacion->id, 'liquidado' => 'S']);
        });

        Flash::success('Liquidación guardada correctamente.');

        return redirect(route('liquidacions.index'));
    }

    /**
     * Create the single child row for a section of the Liquidacion form,
     * skipping it entirely if the business fields in $campos were left empty
     * (or unchecked, since disabled inputs are not submitted). If the row
     * itself has no Fecha, falls back to the Liquidacion's own Fecha so it
     * never gets saved null. $extra carries fields that are always attached
     * to the row (id_camion, forced concepto, etc.) without counting toward
     * the "is this row empty" check or needing to be listed in $campos.
     *
     * @param Liquidacion $liquidacion
     * @param string $modelClass
     * @param array $fila
     * @param array $campos
     * @param string|null $fechaCabecera
     * @param array $extra
     *
     * @return void
     */
    private function guardarLinea(Liquidacion $liquidacion, string $modelClass, array $fila, array $campos, $fechaCabecera = null, array $extra = [])
    {
        $vacia = collect($campos)->every(function ($campo) use ($fila) {
            return empty($fila[$campo] ?? null);
        });

        if ($vacia) {
            return;
        }

        $datos = collect($fila)->only($campos)->toArray();

        if (in_array('fecha', $campos) && empty($datos['fecha'])) {
            $datos['fecha'] = $fechaCabecera;
        }

        $datos = array_merge($datos, $extra);
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
            'camion',
            'chofer',
            'ordenCarga',
            'fletes.camion',
            'fletes.ordenCarga',
            'descuentos.camion',
            'viaticos.chofer',
            'combustibles.camion',
            'gastosAdministrativos',
        ])->findOrFail($id);

        $empresa = Empresa::first();

        $pdf = Pdf::loadView('liquidacions.pdf', [
            'liquidacion' => $liquidacion,
            'empresa' => $empresa,
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('Liquidacion ' . $liquidacion->id . '.pdf');
    }

    /**
     * Instead of deleting, mark the specified Liquidacion as Anulado and
     * release the Viatico/Vale de Combustible attached to it.
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

        DB::transaction(function () use ($liquidacion) {
            $liquidacion->estado = 'Anulado';
            $liquidacion->save();

            Viatico::where('id_liquidacion', $liquidacion->id)->update(['id_liquidacion' => null, 'liquidado' => null]);
            ValeCombustible::where('id_liquidacion', $liquidacion->id)->update(['id_liquidacion' => null, 'liquidado' => null]);

            $idsOrdenCarga = $liquidacion->fletes->pluck('id_orden_carga')->filter()->values();
            if ($liquidacion->id_orden_carga) {
                $idsOrdenCarga->push($liquidacion->id_orden_carga);
            }

            if ($idsOrdenCarga->isNotEmpty()) {
                OrdenCarga::whereIn('id', $idsOrdenCarga->unique())->update(['liquidado' => null]);
            }
        });

        Flash::success('Liquidación anulada correctamente.');

        return redirect(route('liquidacions.index'));
    }

    /**
     * Mark the specified Liquidacion as Facturado. One-way: once marked, the
     * UI no longer offers a way to undo it from here.
     *
     * @param int $id
     *
     * @return Response
     */
    public function toggleFacturado($id)
    {
        $liquidacion = $this->liquidacionRepository->find($id);

        if (empty($liquidacion)) {
            Flash::error('Liquidación no encontrada');

            return redirect(route('liquidacions.index'));
        }

        $liquidacion->facturado = 'Si';
        $liquidacion->save();

        Flash::success('Liquidación marcada como facturada.');

        return redirect(route('liquidacions.index'));
    }
}
