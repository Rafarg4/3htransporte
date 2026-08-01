<?php

namespace App\Http\Controllers;

use App\Models\Parametrizacion;
use Illuminate\Http\Request;
use Flash;
use Response;

class ParametrizacionController extends AppBaseController
{
    /**
     * Show the single Parametrizacion record for editing, creating it
     * with the default values if it doesn't exist yet.
     *
     * @return Response
     */
    public function edit()
    {
        $parametrizacion = Parametrizacion::actual();

        return view('parametrizaciones.edit')->with('parametrizacion', $parametrizacion);
    }

    /**
     * Update the single Parametrizacion record.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'recargo_tolerancia' => 'required|numeric',
            'recargo_precio' => 'required|numeric',
        ]);

        $parametrizacion = Parametrizacion::actual();
        $parametrizacion->update($request->only(['recargo_tolerancia', 'recargo_precio']));

        Flash::success('Parametrización actualizada correctamente.');

        return redirect(route('parametrizaciones.edit'));
    }
}
