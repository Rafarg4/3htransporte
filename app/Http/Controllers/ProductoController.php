<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductoRequest;
use App\Http\Requests\UpdateProductoRequest;
use App\Repositories\ProductoRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class ProductoController extends AppBaseController
{
    /** @var ProductoRepository $productoRepository*/
    private $productoRepository;

    public function __construct(ProductoRepository $productoRepo)
    {
        $this->productoRepository = $productoRepo;
    }

    /**
     * Display a listing of the Producto.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $productos = $this->productoRepository->all();

        return view('productos.index')
            ->with('productos', $productos);
    }

    /**
     * Show the form for creating a new Producto.
     *
     * @return Response
     */
    public function create()
    {
        return view('productos.create');
    }

    /**
     * Store a newly created Producto in storage.
     *
     * @param CreateProductoRequest $request
     *
     * @return Response
     */
    public function store(CreateProductoRequest $request)
    {
        $input = $request->all();
        $input['estado'] = $input['estado'] ?? 'Activo';

        $producto = $this->productoRepository->create($input);

        if ($request->expectsJson()) {
            return response()->json(['id' => $producto->id, 'nombre' => $producto->nombre]);
        }

        Flash::success('Producto guardado correctamente.');

        return redirect(route('productos.index'));
    }

    /**
     * Display the specified Producto.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $producto = $this->productoRepository->find($id);

        if (empty($producto)) {
            Flash::error('Producto no encontrado');

            return redirect(route('productos.index'));
        }

        return view('productos.show')->with('producto', $producto);
    }

    /**
     * Show the form for editing the specified Producto.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $producto = $this->productoRepository->find($id);

        if (empty($producto)) {
            Flash::error('Producto no encontrado');

            return redirect(route('productos.index'));
        }

        return view('productos.edit')->with('producto', $producto);
    }

    /**
     * Update the specified Producto in storage.
     *
     * @param int $id
     * @param UpdateProductoRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateProductoRequest $request)
    {
        $producto = $this->productoRepository->find($id);

        if (empty($producto)) {
            Flash::error('Producto no encontrado');

            return redirect(route('productos.index'));
        }

        $producto = $this->productoRepository->update($request->all(), $id);

        Flash::success('Producto actualizado correctamente.');

        return redirect(route('productos.index'));
    }

    /**
     * Toggle the estado (Activo/Inactivo) of the specified Producto.
     *
     * @param int $id
     *
     * @return Response
     */
    public function cambiarEstado($id)
    {
        $producto = $this->productoRepository->find($id);

        if (empty($producto)) {
            Flash::error('Producto no encontrado');

            return redirect(route('productos.index'));
        }

        $producto->estado = $producto->estado === 'Activo' ? 'Inactivo' : 'Activo';
        $producto->save();

        Flash::success('Producto ' . ($producto->estado === 'Activo' ? 'activado' : 'inactivado') . ' correctamente.');

        return redirect(route('productos.index'));
    }

    /**
     * Remove the specified Producto from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $producto = $this->productoRepository->find($id);

        if (empty($producto)) {
            Flash::error('Producto no encontrado');

            return redirect(route('productos.index'));
        }

        $this->productoRepository->delete($id);

        Flash::success('Producto eliminado correctamente.');

        return redirect(route('productos.index'));
    }
}
