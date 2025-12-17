<?php

namespace App\Http\Controllers;

use App\MotivosLlamada;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MotivosLlamadaController extends Controller
{
  public function index()
  {
      $motivos = MotivosLlamada::orderBy('descripcion', 'ASC')->get();
      return view('motivos.index', compact('motivos'));
  }
  public function create()
  {
    return view('motivos.create');
  }

  public function store(Request $request)
  {
      $request->validate([
        'descripcion' => 'required',
        'usuarioAlta' => 'required',
        'estado' => 'required']);

      $input = $request->all();
      $input['tokenx'] = Str::random(30);

      $obj = MotivosLlamada::create($input);
      if( !isset($obj)){
        return redirect()->route('motivos.index')->with('error','Intenta mas tarde!');
      }
      return redirect()->route('motivos.index')->with('success','Creado con éxito.');
  }

  public function edit($token)
  {
      $motivo = MotivosLlamada::where('tokenx', $token)->first();
      return view('motivos.edit', compact('motivo'));
  }


  public function update(Request $request, $token)
  {
     $input    = $request->all();
     $request->validate([
       'descripcion' => 'required',
       'usuarioAlta' => 'required',
       'estado' => 'required']);
     $motivo = MotivosLlamada::where('tokenx', $token)->first();

     $motivo->descripcion = $input['descripcion'];
     $motivo->usuarioAlta = $input['usuarioAlta'];
     $motivo->estado      = $input['estado'];

     $doit = $motivo->save();
     if( $doit )
      return redirect()->route('motivos.index')->with('success','Actualizado con éxito.');
     else
      return redirect()->route('motivos.index')->with('error','No se puede actualizar');
  }


  public function destroy($token)
  {
      $obj = MotivosLlamada::where('tokenx', $token)->first();
      if( $obj->delete() && isset($obj) )
       return redirect()->route('motivos.index')->with('success','Eliminado con éxito.');
      else
       return redirect()->route('motivos.index')->with('error','No se puede eliminar');
  }
}
