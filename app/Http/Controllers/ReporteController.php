<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateReporteRequest;
use App\Http\Requests\UpdateReporteRequest;
use App\Models\Empresa;
use App\Models\Reporte;
use App\Repositories\ReporteRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;
use Barryvdh\DomPDF\Facade\Pdf;

class ReporteController extends AppBaseController
{
    /** @var ReporteRepository $reporteRepository*/
    private $reporteRepository;

    public function __construct(ReporteRepository $reporteRepo)
    {
        $this->reporteRepository = $reporteRepo;
    }

    /**
     * Display a listing of the Reporte.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $reportes = $this->reporteRepository->all();

        return view('reportes.index')
            ->with('reportes', $reportes);
    }

    /**
     * Show the form for creating a new Reporte.
     *
     * @return Response
     */
    public function create()
    {
        return view('reportes.create');
    }

    /**
     * Store a newly created Reporte in storage.
     *
     * @param CreateReporteRequest $request
     *
     * @return Response
     */
    public function store(CreateReporteRequest $request)
    {
        $input = $request->all();

        $reporte = $this->reporteRepository->create($input);

        Flash::success('Reporte saved successfully.');

        return redirect(route('reportes.index'));
    }

    /**
     * Display the specified Reporte.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $reporte = $this->reporteRepository->find($id);

        if (empty($reporte)) {
            Flash::error('Reporte not found');

            return redirect(route('reportes.index'));
        }

        return view('reportes.show')->with('reporte', $reporte);
    }

    /**
     * Show the form for editing the specified Reporte.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $reporte = $this->reporteRepository->find($id);

        if (empty($reporte)) {
            Flash::error('Reporte not found');

            return redirect(route('reportes.index'));
        }

        return view('reportes.edit')->with('reporte', $reporte);
    }

    /**
     * Update the specified Reporte in storage.
     *
     * @param int $id
     * @param UpdateReporteRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateReporteRequest $request)
    {
        $reporte = $this->reporteRepository->find($id);

        if (empty($reporte)) {
            Flash::error('Reporte not found');

            return redirect(route('reportes.index'));
        }

        $reporte = $this->reporteRepository->update($request->all(), $id);

        Flash::success('Reporte updated successfully.');

        return redirect(route('reportes.index'));
    }

    /**
     * Remove the specified Reporte from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $reporte = $this->reporteRepository->find($id);

        if (empty($reporte)) {
            Flash::error('Reporte not found');

            return redirect(route('reportes.index'));
        }

        $this->reporteRepository->delete($id);

        Flash::success('Reporte deleted successfully.');

        return redirect(route('reportes.index'));
    }

    /**
     * Show the Generar Reporte screen: a filtered listing of Reporte with
     * links to export it as PDF or Excel.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function generar(Request $request)
    {
        $reportes = $this->filtrarReportes($request)->get();

        return view('reportes.generar')
            ->with('reportes', $reportes)
            ->with('filtros', $this->getFiltros($request));
    }

    /**
     * Stream the filtered Reporte listing as a PDF.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function generarPdf(Request $request)
    {
        $reportes = $this->filtrarReportes($request)->get();
        $empresa = Empresa::first();

        $pdf = Pdf::loadView('reportes.generar_pdf', [
            'reportes' => $reportes,
            'empresa' => $empresa,
            'filtros' => $this->getFiltros($request),
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('Reporte ' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Download the filtered Reporte listing as a CSV file, with the Empresa
     * data (no logo: CSV is plain text) as header rows above the table.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function generarExcel(Request $request)
    {
        $reportes = $this->filtrarReportes($request)->get();
        $empresa = Empresa::first();

        $nombreArchivo = 'Reporte ' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($reportes, $empresa) {
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

            fputcsv($handle, ['Fecha', 'Kg Origen', 'Kg Llegada', 'Precio Real Flete', 'Precio Fletero'], ';');

            foreach ($reportes as $reporte) {
                fputcsv($handle, [
                    $reporte->created_at ? $reporte->created_at->format('d/m/Y') : '-',
                    $reporte->kg_origen,
                    $reporte->kg_llegada,
                    $reporte->precio_real_flete,
                    $reporte->precio_fletero,
                ], ';');
            }

            fclose($handle);
        }, $nombreArchivo, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * Build the Reporte query, applying the fecha_desde/fecha_hasta filters
     * if present in the request.
     *
     * @param Request $request
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function filtrarReportes(Request $request)
    {
        $query = Reporte::orderByDesc('id');

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
    private function getFiltros(Request $request)
    {
        return $request->only(['fecha_desde', 'fecha_hasta']);
    }
}
