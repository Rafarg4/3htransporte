<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateClienteRequest;
use App\Http\Requests\UpdateClienteRequest;
use App\Models\Camion;
use App\Models\DocumentoCliente;
use App\Repositories\ClienteRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class ClienteController extends AppBaseController
{
    /** @var ClienteRepository $clienteRepository*/
    private $clienteRepository;

    public function __construct(ClienteRepository $clienteRepo)
    {
        $this->clienteRepository = $clienteRepo;
    }

    /**
     * Display a listing of the Cliente.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $clientes = $this->clienteRepository->all()->loadCount('camiones');

        return view('clientes.index')
            ->with('clientes', $clientes);
    }

    /**
     * Show the form for creating a new Cliente.
     *
     * @return Response
     */
    public function create()
    {
        return view('clientes.create');
    }

    /**
     * Store a newly created Cliente in storage.
     *
     * @param CreateClienteRequest $request
     *
     * @return Response
     */
    public function store(CreateClienteRequest $request)
    {
        $input = $request->except('documentos');

        $cliente = $this->clienteRepository->create($input);

        $this->guardarDocumentos($request, $cliente->id);

        Flash::success('Propietario guardado correctamente.');

        return redirect(route('clientes.index'));
    }

    /**
     * Display the specified Cliente.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $cliente = $this->clienteRepository->find($id);

        if (empty($cliente)) {
            Flash::error('Propietario no encontrado');

            return redirect(route('clientes.index'));
        }

        $camionesDisponibles = Camion::whereNull('id_cliente')
            ->orderBy('chapa')
            ->pluck('chapa', 'id');

        return view('clientes.show')
            ->with('cliente', $cliente)
            ->with('camionesDisponibles', $camionesDisponibles);
    }

    /**
     * Assign a Camion to the given Cliente.
     *
     * @param Request $request
     * @param int $id
     *
     * @return Response
     */
    public function asignarCamion(Request $request, $id)
    {
        $cliente = $this->clienteRepository->find($id);

        if (empty($cliente)) {
            Flash::error('Propietario no encontrado');

            return redirect(route('clientes.index'));
        }

        $request->validate([
            'camion_id' => 'required|exists:camions,id',
        ]);

        $camion = Camion::find($request->camion_id);
        $camion->id_cliente = $cliente->id;
        $camion->save();

        Flash::success('Camión asignado correctamente.');

        return redirect(route('clientes.show', $cliente->id));
    }

    /**
     * Remove a Camion assignment from the given Cliente.
     *
     * @param int $id
     * @param int $camionId
     *
     * @return Response
     */
    public function desasignarCamion($id, $camionId)
    {
        $camion = Camion::where('id', $camionId)->where('id_cliente', $id)->first();

        if (!empty($camion)) {
            $camion->id_cliente = null;
            $camion->save();

            Flash::success('Camión desasignado correctamente.');
        }

        return redirect(route('clientes.show', $id));
    }

    /**
     * Show the form for editing the specified Cliente.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $cliente = $this->clienteRepository->find($id);

        if (empty($cliente)) {
            Flash::error('Propietario no encontrado');

            return redirect(route('clientes.index'));
        }

        return view('clientes.edit')->with('cliente', $cliente);
    }

    /**
     * Update the specified Cliente in storage.
     *
     * @param int $id
     * @param UpdateClienteRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateClienteRequest $request)
    {
        $cliente = $this->clienteRepository->find($id);

        if (empty($cliente)) {
            Flash::error('Propietario no encontrado');

            return redirect(route('clientes.index'));
        }

        $cliente = $this->clienteRepository->update($request->except('documentos'), $id);

        $this->guardarDocumentos($request, $cliente->id);

        Flash::success('Propietario actualizado correctamente.');

        return redirect(route('clientes.index'));
    }

    /**
     * Store the uploaded documents for the given Cliente.
     *
     * @param Request $request
     * @param int $idCliente
     *
     * @return void
     */
    private function guardarDocumentos(Request $request, $idCliente)
    {
        if (!$request->hasFile('documentos')) {
            return;
        }

        foreach ($request->file('documentos') as $documento) {
            if (!$documento->isValid()) {
                continue;
            }

            $nombreArchivo = uniqid() . '_' . $documento->getClientOriginalName();
            $documento->move(public_path('documento_clientes'), $nombreArchivo);

            DocumentoCliente::create([
                'id_cliente' => $idCliente,
                'nombre_archivo' => $nombreArchivo,
            ]);
        }
    }

    /**
     * Remove a single uploaded document from the given Cliente.
     *
     * @param Request $request
     * @param int $documento
     *
     * @return Response
     */
    public function destroyDocumento(Request $request, $documento)
    {
        $documento = DocumentoCliente::find($documento);

        if (empty($documento)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Documento no encontrado'], 404);
            }

            Flash::error('Documento no encontrado');

            return redirect()->back();
        }

        $path = public_path('documento_clientes/' . $documento->nombre_archivo);

        if (file_exists($path)) {
            unlink($path);
        }

        $documento->delete();

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        Flash::success('Documento eliminado correctamente.');

        return redirect()->back();
    }

    /**
     * Remove the specified Cliente from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $cliente = $this->clienteRepository->find($id);

        if (empty($cliente)) {
            Flash::error('Propietario no encontrado');

            return redirect(route('clientes.index'));
        }

        $this->clienteRepository->delete($id);

        Flash::success('Propietario eliminado correctamente.');

        return redirect(route('clientes.index'));
    }
}
