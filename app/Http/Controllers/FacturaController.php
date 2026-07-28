<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateFacturaRequest;
use App\Http\Requests\UpdateFacturaRequest;
use App\Repositories\FacturaRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
class FacturaController extends AppBaseController
{
    /** @var FacturaRepository $facturaRepository*/
    private $facturaRepository;

    public function __construct(FacturaRepository $facturaRepo)
    {
        $this->facturaRepository = $facturaRepo;
    }

    /**
     * Display a listing of the Factura.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $facturas = $this->facturaRepository->all();

        return view('facturas.index')
            ->with('facturas', $facturas);
    }

    /**
     * Show the form for creating a new Factura.
     *
     * @return Response
     */
    public function create()
    {
        return view('facturas.create');
    }
    public function pdf($id)
    {
        $factura = DB::table('facturas')
            ->where('id', $id)
            ->first();

        $items = DB::table('factura_items')
            ->where('id_factura', $id)
            ->get();

        $pdf = Pdf::loadView('facturas.pdf_ticket', [
            'factura' => $factura,
            'items'   => $items
        ])->setPaper([0, 0, 226.77, 600], 'portrait'); 
        // 80mm aprox (ticket)

        return $pdf->stream('factura_'.$factura->numero.'.pdf');
    }
    /**
     * Store a newly created Factura in storage.
     *
     * @param CreateFacturaRequest $request
     *
     * @return Response
     */
   public function store(CreateFacturaRequest $request)
    {
        DB::beginTransaction();

        try {

            // 1️⃣ Guardar cabecera
            $input = $request->except('items');
            $factura = $this->facturaRepository->create($input);

            // 2️⃣ Guardar detalle
            if ($request->has('items')) {

                foreach ($request->items as $item) {

                    // evitar filas vacías
                    if (empty($item['descripcion'])) {
                        continue;
                    }

                    DB::table('factura_items')->insert([
                        'id_factura'     => $factura->id,
                        'descripcion'    => $item['descripcion'] ?? null,
                        'codigo'         => $item['codigo'] ?? null,
                        'cantidad'       => $item['cantidad'] ?? null,
                        'precioUnitario' => $item['precioUnitario'] ?? null,
                        'precioTotal'    => $item['precioTotal'] ?? null,
                        'tipoIva'        => $item['tipoIva'] ?? null,
                        'created_at'     => now(),
                    ]);
                }
            }

            DB::commit();

            Flash::success('Factura guardada correctamente.');

            return redirect(route('facturas.index'));

        } catch (\Exception $e) {

            DB::rollBack();

            Flash::error('Error al guardar la factura: ' . $e->getMessage());

            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified Factura.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $factura = $this->facturaRepository->find($id);

        if (empty($factura)) {
            Flash::error('Factura no encontrada');

            return redirect(route('facturas.index'));
        }

        return view('facturas.show')->with('factura', $factura);
    }

    /**
     * Show the form for editing the specified Factura.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $factura = $this->facturaRepository->find($id);

        if (empty($factura)) {
            Flash::error('Factura no encontrada');

            return redirect(route('facturas.index'));
        }

        return view('facturas.edit')->with('factura', $factura);
    }

    /**
     * Update the specified Factura in storage.
     *
     * @param int $id
     * @param UpdateFacturaRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateFacturaRequest $request)
    {
        $factura = $this->facturaRepository->find($id);

        if (empty($factura)) {
            Flash::error('Factura no encontrada');

            return redirect(route('facturas.index'));
        }

        $factura = $this->facturaRepository->update($request->all(), $id);

        Flash::success('Factura actualizada correctamente.');

        return redirect(route('facturas.index'));
    }

    /**
     * Remove the specified Factura from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $factura = $this->facturaRepository->find($id);

        if (empty($factura)) {
            Flash::error('Factura no encontrada');

            return redirect(route('facturas.index'));
        }

        $this->facturaRepository->delete($id);

        Flash::success('Factura eliminada correctamente.');

        return redirect(route('facturas.index'));
    }
}
