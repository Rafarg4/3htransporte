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
        // `numero` es una columna de texto: se castea para ordenar numericamente.
        $ordenCargas = OrdenCarga::with(['proveedor', 'producto', 'camion'])
            ->orderByRaw('CAST(numero AS UNSIGNED) DESC')
            ->get();

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
     * Display the Reporte screen: OrdenCarga listing filtered by
     * proveedor and fecha de carga.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function reporte(Request $request)
    {
        $ordenCargas = $this->filtrarReporte($request)->get();

        return view('orden_cargas.reporte')
            ->with('ordenCargas', $ordenCargas)
            ->with('proveedores', $this->getListasParaSelect()['proveedores'])
            ->with('filtros', $this->getFiltrosReporte($request));
    }

    /**
     * Stream the filtered OrdenCarga listing as a PDF.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function reportePdf(Request $request)
    {
        $ordenCargas = $this->filtrarReporte($request)->get();
        $empresa = Empresa::first();

        $pdf = Pdf::loadView('orden_cargas.reporte_pdf', [
            'ordenCargas' => $ordenCargas,
            'empresa' => $empresa,
            'filtros' => $this->getFiltrosReporte($request),
            'proveedor' => $request->filled('id_proveedor') ? Proveedor::find($request->input('id_proveedor')) : null,
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('Reporte de Ordenes de Carga ' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Download the filtered OrdenCarga listing as a CSV file, with the
     * Empresa data as header rows above the table.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function reporteExcel(Request $request)
    {
        $ordenCargas = $this->filtrarReporte($request)->get();
        $empresa = Empresa::first();

        $nombreArchivo = 'Reporte de Ordenes de Carga ' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($ordenCargas, $empresa) {
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
                'Numero', 'Fecha', 'Proveedor', 'Producto', 'Origen', 'Destino', 'Camión', 'Obs', 'Estado',
            ], ';');

            foreach ($ordenCargas as $ordenCarga) {
                fputcsv($handle, [
                    $ordenCarga->numero,
                    $ordenCarga->created_at ? $ordenCarga->created_at->format('d/m/Y') : '-',
                    $ordenCarga->proveedor->nombre ?? '-',
                    $ordenCarga->producto->nombre ?? '-',
                    $ordenCarga->origen,
                    $ordenCarga->destino,
                    $ordenCarga->camion->chapa ?? '-',
                    $ordenCarga->observacion,
                    $ordenCarga->estado,
                ], ';');
            }

            fclose($handle);
        }, $nombreArchivo, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * Build the OrdenCarga query for the Reporte screen, applying the
     * id_proveedor/fecha_desde/fecha_hasta filters if present in the
     * request. La fecha de carga es la fecha de creacion de la orden.
     *
     * @param Request $request
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function filtrarReporte(Request $request)
    {
        $query = OrdenCarga::with(['proveedor', 'producto', 'camion'])
            ->orderByRaw('CAST(numero AS UNSIGNED) DESC');

        if ($request->filled('id_proveedor')) {
            $query->where('id_proveedor', $request->input('id_proveedor'));
        }

        if ($request->filled('fecha_desde')) {
            $query->whereDate('created_at', '>=', $request->input('fecha_desde'));
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('created_at', '<=', $request->input('fecha_hasta'));
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
        return $request->only(['id_proveedor', 'fecha_desde', 'fecha_hasta']);
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
     * Mark the specified OrdenCarga as Anulado.
     *
     * @param int $id
     *
     * @return Response
     */
    public function anular($id)
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

    /**
     * Remove the specified OrdenCarga from storage. Only allowed once the
     * order is already Anulado, since an Activo order must be anulada first.
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

        if (strtolower($ordenCarga->estado) !== 'anulado') {
            Flash::error('Solo se puede eliminar una Orden Carga que ya está anulada.');

            return redirect(route('ordenCargas.index'));
        }

        $this->ordenCargaRepository->delete($id);

        Flash::success('Orden Carga eliminada correctamente.');

        return redirect(route('ordenCargas.index'));
    }
}
