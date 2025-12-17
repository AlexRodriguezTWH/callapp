<?php

namespace App\Http\Controllers;

use App\TiposDeServicio;
use Illuminate\Support\Str;
use Illuminate\Http\Request;



class TiposDeServicioController extends Controller
{

    public function index()
    {
        $tipoServicios = TiposDeServicio::orderBy('servicio', 'ASC')->get();
        return view('servicios.index', compact('tipoServicios'));
    }


    public function create()
    {
      return view('servicios.create');
    }

    public function store(Request $request)
    {
        $request->validate(['servicio' => 'required', 'estado' => 'required']);

        $input = $request->all();
        $input['tokenx'] = Str::random(30);

        $servicio = TiposDeServicio::create($input);
        if( !isset($servicio)){
          return redirect()->route('servicio.index')->with('error','Intenta mas tarde!');
        }
        return redirect()->route('servicio.index')->with('success','Creado con éxito.');
    }

    public function edit($token)
    {
        $servicio = TiposDeServicio::where('tokenx', $token)->first();
        return view('servicios.edit', compact('servicio'));
    }


    public function update(Request $request, $token)
    {
       $input    = $request->all();
       $request->validate(['servicio' => 'required', 'estado' => 'required']);
       $servicio = TiposDeServicio::where('tokenx', $token)->first();

       $servicio->servicio = $input['servicio'];
       $servicio->estado   = $input['estado'];
       $doit = $servicio->save();
       if( $doit )
        return redirect()->route('servicio.index')->with('success','Creado con éxito.');
       else
        return redirect()->route('servicio.index')->with('error','No se puede actualizar');
    }


    public function destroy($token)
    {
        $servicio = TiposDeServicio::where('tokenx', $token)->first();

        if( $servicio->delete() && isset($servicio) )
         return redirect()->route('servicio.index')->with('success','Eliminado con éxito.');
        else
         return redirect()->route('servicio.index')->with('error','No se puede eliminar');
    }
}
