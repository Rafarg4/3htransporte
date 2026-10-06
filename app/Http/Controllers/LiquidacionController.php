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
use App\Models\Moneda;
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
        // camion/chofer/ordenCarga no se cargan aca: la tabla del listado (table.blade.php) ya
        // no muestra esas columnas (el detalle completo esta en el PDF de cada liquidacion).
        $liquidacions = Liquidacion::with([
            'cliente',
            'fletes',
            'descuentos',
            'gastosAdministrativos',
            'viaticos',
            'combustibles',
        ])->orderByDesc('fecha')->orderByDesc('id')->get();

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
            ->with('choferes', Chofer::orderBy('nombre')->get())
            ->with('ordenCargas', OrdenCarga::whereNull('liquidado')->orderByDesc('id')->get())
            ->with('viaticosDisponibles', $this->getViaticosDisponibles())
            ->with('valeCombustiblesDisponibles', $this->getValeCombustiblesDisponibles())
            ->with('parametrizacion', Parametrizacion::actual())
            ->with('monedas', Moneda::vigentes())
            ->with('monedaGuaranies', Moneda::where('tipo_moneda', 'PYG')->orderByDesc('id')->first())
            ->with('cotizacionUsd', $this->getCotizacionVigente('USD'))
            ->with('cotizacionPyg', $this->getCotizacionVigente('PYG'));
    }

    /**
     * Ultima cotizacion cargada en Monedas para ese tipo (0 si no hay ninguna valida).
     *
     * @param string $codigo
     *
     * @return float
     */
    private function getCotizacionVigente($codigo)
    {
        $moneda = Moneda::where('tipo_moneda', $codigo)->orderByDesc('id')->first();

        return $moneda ? max(0, $moneda->cotizacion) : 0;
    }

    /**
     * Cotizacion con la que se pasan a guaranies los viaticos/vales cargados en USD:
     * - liquidacion en USD: la del dolar, asi un viatico de 100 USD vuelve a mostrarse como 100 USD;
     * - cualquier otra: la cargada como Guaranies en Monedas (si no hay, la del dolar).
     *
     * @param string|null $codigoMoneda
     *
     * @return float
     */
    private function getCotizacionParaItemsEnUsd($codigoMoneda)
    {
        $cotizacionUsd = $this->getCotizacionVigente('USD');

        if (strtoupper((string) $codigoMoneda) === 'USD') {
            return $cotizacionUsd;
        }

        $cotizacionPyg = $this->getCotizacionVigente('PYG');

        return $cotizacionPyg > 0 ? $cotizacionPyg : $cotizacionUsd;
    }

    /**
     * Moneda elegida en el formulario + su cotizacion vigente, para congelarla en la liquidacion.
     * Guaranies guarda su cotizacion (si hay una cargada) solo como referencia para el PDF: los
     * montos ya estan en Gs. Una moneda sin cotizacion valida guarda ambos campos en null.
     *
     * @param string|null $codigo
     *
     * @return array
     */
    private function getMonedaParaGuardar($codigo)
    {
        $codigo = strtoupper((string) $codigo);

        if ($codigo === '') {
            return ['moneda' => null, 'monto_moneda' => null];
        }

        if ($codigo === 'PYG') {
            $cotizacionPyg = $this->getCotizacionVigente('PYG');

            return ['moneda' => 'PYG', 'monto_moneda' => $cotizacionPyg > 0 ? $cotizacionPyg : null];
        }

        $moneda = Moneda::where('tipo_moneda', $codigo)->orderByDesc('id')->first();

        if (!$moneda || $moneda->cotizacion <= 0) {
            return ['moneda' => null, 'monto_moneda' => null];
        }

        return ['moneda' => $moneda->tipo_moneda, 'monto_moneda' => $moneda->cotizacion];
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
     * Viaticos activos que todavia no fueron usados en ninguna liquidacion,
     * disponibles para tildar (filtrados por el Chofer de la cabecera en JS). Al editar,
     * tambien los que ya usa esa liquidacion ($idLiquidacion).
     *
     * @param int|null $idLiquidacion
     *
     * @return \Illuminate\Support\Collection
     */
    private function getViaticosDisponibles($idLiquidacion = null)
    {
        return Viatico::with('chofer')
            ->where(function ($query) use ($idLiquidacion) {
                $query->where(function ($disponibles) {
                    $disponibles->whereNull('liquidado')->where('estado', 'Activo');
                });

                if ($idLiquidacion) {
                    $query->orWhere('id_liquidacion', $idLiquidacion);
                }
            })
            ->orderByDesc('id')
            ->get();
    }

    /**
     * Vales de combustible activos que todavia no fueron usados en ninguna liquidacion,
     * disponibles para tildar (filtrados por el Camion de la cabecera en JS). Al editar,
     * tambien los que ya usa esa liquidacion ($idLiquidacion).
     *
     * @param int|null $idLiquidacion
     *
     * @return \Illuminate\Support\Collection
     */
    private function getValeCombustiblesDisponibles($idLiquidacion = null)
    {
        return ValeCombustible::with('camion')
            ->where(function ($query) use ($idLiquidacion) {
                $query->where(function ($disponibles) {
                    $disponibles->whereNull('liquidado')->where('estado', 'Activo');
                });

                if ($idLiquidacion) {
                    $query->orWhere('id_liquidacion', $idLiquidacion);
                }
            })
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
        // Viaticos/vales cargados en USD se pasan a guaranies con la cotizacion que corresponde a
        // la moneda elegida, y queda congelada en la liquidacion (cotizacion_usd). Sin cotizacion
        // no se pueden sumar.
        $cotizacionUsd = $this->getCotizacionParaItemsEnUsd($request->input('moneda'));
        $hayItemsEnUsd = Viatico::whereIn('id', $request->input('viatico_ids', []))->where('tipo_moneda', 'USD')->exists()
            || ValeCombustible::whereIn('id', $request->input('vale_combustible_ids', []))->where('tipo_moneda', 'USD')->exists();

        if ($hayItemsEnUsd && $cotizacionUsd <= 0) {
            return redirect()->back()->withInput()
                ->withErrors(['moneda' => 'Hay viáticos o vales de combustible en dólares: cargá la cotización en Parametrizaciones > Moneda antes de liquidar.']);
        }

        DB::transaction(function () use ($request, $cotizacionUsd) {
            $liquidacion = Liquidacion::create([
                'id_cliente' => $request->input('id_cliente'),
                'id_camion' => $request->input('id_camion'),
                'id_chofer' => $request->input('id_chofer'),
                'id_orden_carga' => $this->getOrdenCargaCabecera($request),
                'fecha' => $request->input('fecha'),
                'estado' => 'Activo',
                'facturado' => $request->input('facturado', 'No'),
                'pagado' => 'No',
                'cotizacion_usd' => $cotizacionUsd > 0 ? $cotizacionUsd : null,
            ] + $this->getMonedaParaGuardar($request->input('moneda')));

            $this->guardarDetalle($liquidacion, $request);
        });

        Flash::success('Liquidación guardada correctamente.');

        return redirect(route('liquidacions.index'));
    }

    /**
     * Show the form for editing the specified Liquidacion (vista edit_fields.blade.php,
     * separada del formulario de alta). Solo se pueden editar liquidaciones activas.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $liquidacion = Liquidacion::with(['fletes', 'descuentos', 'gastosAdministrativos', 'viaticos', 'combustibles'])->find($id);

        if (empty($liquidacion)) {
            Flash::error('Liquidación no encontrada');

            return redirect(route('liquidacions.index'));
        }

        if (strtolower($liquidacion->estado) !== 'activo') {
            Flash::error('Solo se puede editar una Liquidación activa.');

            return redirect(route('liquidacions.index'));
        }

        // Ademas de lo disponible, se listan las Ordenes de Carga / Viaticos / Vales que ya usa
        // esta liquidacion, para que sigan apareciendo (y tildados) en el formulario.
        $idsOrdenCarga = $this->getIdsOrdenCarga($liquidacion);

        return view('liquidacions.edit')
            ->with('liquidacion', $liquidacion)
            ->with('datosEdicion', $this->getDatosEdicion($liquidacion))
            ->with('clientes', $this->getClientesParaSelect())
            ->with('camions', Camion::orderBy('chapa')->get())
            ->with('choferes', Chofer::orderBy('nombre')->get())
            ->with('ordenCargas', OrdenCarga::where(function ($query) use ($idsOrdenCarga) {
                $query->whereNull('liquidado')->orWhereIn('id', $idsOrdenCarga);
            })->orderByDesc('id')->get())
            ->with('viaticosDisponibles', $this->getViaticosDisponibles($liquidacion->id))
            ->with('valeCombustiblesDisponibles', $this->getValeCombustiblesDisponibles($liquidacion->id))
            ->with('parametrizacion', Parametrizacion::actual())
            ->with('monedas', Moneda::vigentes())
            ->with('monedaGuaranies', Moneda::where('tipo_moneda', 'PYG')->orderByDesc('id')->first())
            ->with('cotizacionUsd', $this->getCotizacionVigente('USD'))
            ->with('cotizacionPyg', $this->getCotizacionVigente('PYG'));
    }

    /**
     * Update the specified Liquidacion: libera los viaticos/vales/ordenes de carga que usaba,
     * borra sus lineas y las vuelve a grabar con lo que llega del formulario (mismo proceso que
     * store()). Si se mantiene la moneda, se conservan las cotizaciones congeladas al crearla;
     * si se cambia, se toman las vigentes.
     *
     * @param int $id
     * @param CreateLiquidacionRequest $request
     *
     * @return Response
     */
    public function update($id, CreateLiquidacionRequest $request)
    {
        $liquidacion = Liquidacion::with('fletes')->find($id);

        if (empty($liquidacion)) {
            Flash::error('Liquidación no encontrada');

            return redirect(route('liquidacions.index'));
        }

        if (strtolower($liquidacion->estado) !== 'activo') {
            Flash::error('Solo se puede editar una Liquidación activa.');

            return redirect(route('liquidacions.index'));
        }

        $mismaMoneda = $liquidacion->moneda
            && strtoupper((string) $request->input('moneda')) === strtoupper($liquidacion->moneda);

        $cotizacionUsd = ($mismaMoneda && $liquidacion->cotizacion_usd > 0)
            ? $liquidacion->cotizacion_usd
            : $this->getCotizacionParaItemsEnUsd($request->input('moneda'));

        $datosMoneda = $mismaMoneda
            ? ['moneda' => $liquidacion->moneda, 'monto_moneda' => $liquidacion->monto_moneda]
            : $this->getMonedaParaGuardar($request->input('moneda'));

        $hayItemsEnUsd = Viatico::whereIn('id', $request->input('viatico_ids', []))->where('tipo_moneda', 'USD')->exists()
            || ValeCombustible::whereIn('id', $request->input('vale_combustible_ids', []))->where('tipo_moneda', 'USD')->exists();

        if ($hayItemsEnUsd && $cotizacionUsd <= 0) {
            return redirect()->back()->withInput()
                ->withErrors(['moneda' => 'Hay viáticos o vales de combustible en dólares: cargá la cotización en Parametrizaciones > Moneda antes de liquidar.']);
        }

        DB::transaction(function () use ($liquidacion, $request, $cotizacionUsd, $datosMoneda) {
            Viatico::where('id_liquidacion', $liquidacion->id)->update(['id_liquidacion' => null, 'liquidado' => null]);
            ValeCombustible::where('id_liquidacion', $liquidacion->id)->update(['id_liquidacion' => null, 'liquidado' => null]);

            $idsOrdenCarga = $this->getIdsOrdenCarga($liquidacion);
            if ($idsOrdenCarga->isNotEmpty()) {
                OrdenCarga::whereIn('id', $idsOrdenCarga)->update(['liquidado' => null]);
            }

            // Los descuentos que no son "Faltante de Carga" no se cargan desde el formulario,
            // asi que se conservan tal cual.
            $liquidacion->fletes()->delete();
            $liquidacion->descuentos()->where('concepto', 'Faltante de Carga')->delete();
            $liquidacion->gastosAdministrativos()->delete();

            $liquidacion->update([
                'id_cliente' => $request->input('id_cliente'),
                'id_camion' => $request->input('id_camion'),
                'id_chofer' => $request->input('id_chofer'),
                'id_orden_carga' => $this->getOrdenCargaCabecera($request),
                'fecha' => $request->input('fecha'),
                'cotizacion_usd' => $cotizacionUsd > 0 ? $cotizacionUsd : null,
            ] + $datosMoneda);

            $this->guardarDetalle($liquidacion, $request);
        });

        Flash::success('Liquidación actualizada correctamente.');

        return redirect(route('liquidacions.index'));
    }

    /**
     * Ordenes de Carga que usa la liquidacion (las de sus fletes + la de la cabecera).
     *
     * @param Liquidacion $liquidacion
     *
     * @return \Illuminate\Support\Collection
     */
    private function getIdsOrdenCarga(Liquidacion $liquidacion)
    {
        return $liquidacion->fletes->pluck('id_orden_carga')
            ->push($liquidacion->id_orden_carga)
            ->filter()
            ->unique()
            ->values();
    }

    /**
     * Datos guardados de la liquidacion con la misma forma que el request del formulario,
     * para precargar edit_fields.blade.php. Cada flete usa "e<id>" como bloqueId. Las chapas
     * tildadas salen de los fletes y los choferes de los viaticos usados (en la cabecera solo
     * se guarda la chapa y el chofer principal).
     *
     * @param Liquidacion $liquidacion
     *
     * @return array
     */
    private function getDatosEdicion(Liquidacion $liquidacion)
    {
        $fecha = function ($valor) {
            return $valor ? substr((string) $valor, 0, 10) : null;
        };

        $flete = [];
        $ordenCarga = [];

        foreach ($liquidacion->fletes as $fila) {
            $bloqueId = 'e' . $fila->id;

            $flete[$bloqueId] = [
                'id_camion' => (string) $fila->id_camion,
                'fecha' => $fecha($fila->fecha),
                'tramo' => $fila->tramo,
                'kg_origen' => $fila->kg_origen,
                'kg_destino' => $fila->kg_destino,
                'precio' => $fila->precio,
                'valor' => $fila->valor,
                'recargo_tolerancia' => $fila->recargo_tolerancia,
                'recargo_precio' => $fila->recargo_precio,
            ];
            $ordenCarga[$bloqueId] = $fila->id_orden_carga;
        }

        $gasto = $liquidacion->gastosAdministrativos->first();
        $gastoAdministrativo = [];

        if ($gasto) {
            $montoUnitario = round((float) $gasto->valor / max(1, $liquidacion->fletes->count()), 2);

            $gastoAdministrativo = [
                'fecha' => $fecha($gasto->fecha),
                'concepto' => $gasto->concepto,
                'monto_unitario' => $montoUnitario == (int) $montoUnitario ? (int) $montoUnitario : $montoUnitario,
            ];
        }

        $soloIds = function ($ids) {
            return collect($ids)->filter()->map(function ($id) {
                return (string) $id;
            })->unique()->values()->all();
        };

        return [
            'id_cliente' => $liquidacion->id_cliente,
            'id_camion' => $liquidacion->id_camion,
            'id_chofer' => $liquidacion->id_chofer,
            'camion_ids' => $soloIds(collect([$liquidacion->id_camion])->merge($liquidacion->fletes->pluck('id_camion'))),
            'chofer_ids' => $soloIds(collect([$liquidacion->id_chofer])->merge($liquidacion->viaticos->pluck('id_chofer'))),
            'fecha' => $fecha($liquidacion->fecha),
            'moneda' => $liquidacion->moneda,
            'facturado' => $liquidacion->facturado ?: 'No',
            'flete' => $flete,
            'orden_carga' => $ordenCarga,
            'gasto_administrativo' => $gastoAdministrativo,
            'viatico_ids' => $liquidacion->viaticos->pluck('id')->all(),
            'vale_combustible_ids' => $liquidacion->combustibles->pluck('id')->all(),
        ];
    }

    /**
     * Orden de Carga de la cabecera: primer bloque de Flete de la chapa principal que tenga
     * Orden de Carga cargada; si ninguno la tiene, la primera Orden de Carga de cualquier bloque.
     *
     * @param Request $request
     *
     * @return string|null
     */
    private function getOrdenCargaCabecera(Request $request)
    {
        $ordenCargaPorBloque = $request->input('orden_carga', []);
        $idCamionPrincipal = $request->input('id_camion');

        $idOrdenCargaCabecera = collect($request->input('flete', []))
            ->filter(function ($fila) use ($idCamionPrincipal) {
                return ($fila['id_camion'] ?? null) === $idCamionPrincipal;
            })
            ->keys()
            ->map(function ($bloqueId) use ($ordenCargaPorBloque) {
                return $ordenCargaPorBloque[$bloqueId] ?? null;
            })
            ->first(function ($valor) {
                return !empty($valor);
            });

        if (empty($idOrdenCargaCabecera)) {
            $idOrdenCargaCabecera = collect($ordenCargaPorBloque)->first(function ($valor) {
                return !empty($valor);
            });
        }

        return $idOrdenCargaCabecera;
    }

    /**
     * Graba las lineas de la liquidacion (fletes, descuentos, gastos administrativos) y marca
     * como liquidados los viaticos, vales y ordenes de carga elegidos. Lo usan store() y update().
     *
     * Cada bloque de Flete tiene su propio id (bloqueId), no la chapa: una chapa tildada puede
     * tener varios fletes (boton "Otro flete"), asi que flete/orden_carga/descuento_auto quedan
     * indexados por bloqueId, y cada fila de flete declara su propia chapa en id_camion.
     *
     * @param Liquidacion $liquidacion
     * @param Request $request
     *
     * @return void
     */
    private function guardarDetalle(Liquidacion $liquidacion, Request $request)
    {
        $fletesPorBloque = $request->input('flete', []);
        $ordenCargaPorBloque = $request->input('orden_carga', []);
        $fechaCabecera = $request->input('fecha');

        foreach ($fletesPorBloque as $bloqueId => $filaFlete) {
            $idCamion = $filaFlete['id_camion'] ?? null;
            $idOrdenCarga = $ordenCargaPorBloque[$bloqueId] ?? null;

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

        foreach ($request->input('descuento_auto', []) as $bloqueId => $filaDescuentoAuto) {
            $idCamion = $fletesPorBloque[$bloqueId]['id_camion'] ?? null;

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
     * Show the Reporte de Liquidaciones screen: a filtered listing (por
     * fecha, facturado y pagado) with links to export it as PDF or Excel.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function reporte(Request $request)
    {
        $liquidacions = $this->filtrarReporte($request)->get();

        return view('liquidacions.reporte')
            ->with('liquidacions', $liquidacions)
            ->with('filtros', $this->getFiltrosReporte($request));
    }

    /**
     * Stream the filtered Liquidacion listing as a PDF.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function reportePdf(Request $request)
    {
        $liquidacions = $this->filtrarReporte($request)->get();
        $empresa = Empresa::first();

        $pdf = Pdf::loadView('liquidacions.reporte_pdf', [
            'liquidacions' => $liquidacions,
            'empresa' => $empresa,
            'filtros' => $this->getFiltrosReporte($request),
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('Reporte de Liquidaciones ' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Download the filtered Liquidacion listing as a CSV file, with the
     * Empresa data as header rows above the table.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function reporteExcel(Request $request)
    {
        $liquidacions = $this->filtrarReporte($request)->get();
        $empresa = Empresa::first();

        $nombreArchivo = 'Reporte de Liquidaciones ' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($liquidacions, $empresa) {
            $handle = fopen('php://output', 'w');

            // BOM, para que Excel detecte UTF-8 y muestre bien los acentos.
            fwrite($handle, "\xEF\xBB\xBF");

            if ($empresa) {
                fputcsv($handle, [$empresa->nombre], ';');
                fputcsv($handle, ['RUC: ' . $empresa->ruc], ';');
                fputcsv($handle, [$empresa->direccion], ';');
                fputcsv($handle, ['Tel: ' . $empresa->telefono], ';');
                fputcsv($handle, [], ';');
            }

            fputcsv($handle, [
                'Nro.', 'Fecha', 'Propietario', 'Chapa', 'Créditos', 'Débitos', 'Saldo', 'Facturado', 'Pagado',
            ], ';');

            foreach ($liquidacions as $liquidacion) {
                fputcsv($handle, [
                    $liquidacion->id,
                    $liquidacion->fecha,
                    $liquidacion->cliente ? trim($liquidacion->cliente->nombre . ' ' . $liquidacion->cliente->apellido) : '-',
                    $liquidacion->chapas ?? '-',
                    $liquidacion->total_creditos,
                    $liquidacion->total_debitos,
                    $liquidacion->saldo,
                    $liquidacion->facturado === 'Si' ? 'Si' : 'No',
                    $liquidacion->pagado === 'Si' ? 'Si' : 'No',
                ], ';');
            }

            fclose($handle);
        }, $nombreArchivo, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * Build the Liquidacion query for the Reporte screen, applying the
     * fecha_desde/fecha_hasta/facturado/pagado filters if present in the
     * request. Compara directo contra la columna de texto `fecha`
     * (formato Y-m-d), que ordena igual que una fecha real.
     *
     * @param Request $request
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function filtrarReporte(Request $request)
    {
        $query = Liquidacion::with(['cliente', 'camion', 'fletes', 'descuentos', 'gastosAdministrativos', 'viaticos', 'combustibles'])
            ->orderByDesc('id');

        if ($request->filled('fecha_desde')) {
            $query->where('fecha', '>=', $request->input('fecha_desde'));
        }

        if ($request->filled('fecha_hasta')) {
            $query->where('fecha', '<=', $request->input('fecha_hasta'));
        }

        if ($request->filled('facturado')) {
            $query->where('facturado', $request->input('facturado'));
        }

        if ($request->filled('pagado')) {
            $query->where('pagado', $request->input('pagado'));
        }

        return $query;
    }

    /**
     * Pull the filter values out of the request, to echo back into the form
     * and forward to the PDF/Excel export links.
     *
     * @param Request $request
     *
     * @return array
     */
    private function getFiltrosReporte(Request $request)
    {
        return $request->only(['fecha_desde', 'fecha_hasta', 'facturado', 'pagado']);
    }

    /**
     * Mark the specified Liquidacion as Anulado and release the
     * Viatico/Vale de Combustible/Orden de Carga attached to it.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function anular($id)
    {
        $liquidacion = $this->liquidacionRepository->find($id);

        if (empty($liquidacion)) {
            Flash::error('Liquidación no encontrada');

            return redirect(route('liquidacions.index'));
        }

        if (strtolower($liquidacion->estado) === 'anulado') {
            Flash::error('La Liquidación ya está anulada.');

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
     * Remove the specified Liquidacion from storage, along with its
     * fletes/descuentos/gastos administrativos lines. Only allowed once the
     * liquidacion is already Anulado (the Viatico/Vale de Combustible/Orden
     * de Carga it used were already released back by anular()).
     *
     * @param int $id
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

        if (strtolower($liquidacion->estado) !== 'anulado') {
            Flash::error('Solo se puede eliminar una Liquidación que ya está anulada.');

            return redirect(route('liquidacions.index'));
        }

        DB::transaction(function () use ($liquidacion) {
            $liquidacion->fletes()->delete();
            $liquidacion->descuentos()->delete();
            $liquidacion->gastosAdministrativos()->delete();
            $liquidacion->delete();
        });

        Flash::success('Liquidación eliminada correctamente.');

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

    /**
     * Mark the specified Liquidacion as Pagado. One-way: once marcada, la UI
     * no ofrece forma de deshacerlo desde aca (mismo criterio que Facturado).
     *
     * @param int $id
     *
     * @return Response
     */
    public function togglePagado($id)
    {
        $liquidacion = $this->liquidacionRepository->find($id);

        if (empty($liquidacion)) {
            Flash::error('Liquidación no encontrada');

            return redirect(route('liquidacions.index'));
        }

        $liquidacion->pagado = 'Si';
        $liquidacion->save();

        Flash::success('Liquidación marcada como pagada.');

        return redirect(route('liquidacions.index'));
    }
}
