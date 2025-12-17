<?php

namespace App\Http\Controllers;

use App\Fallas;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class FallasController extends Controller
{
  public function index()
  {
      $fallas = Fallas::where('tipo', 0)->orderBy('descripcion', 'ASC')->get();
      return view('fallas.index', compact('fallas'));
  }
  public function indextecnico()
  {
      $fallas = Fallas::where('tipo', 1)->orderBy('descripcion', 'ASC')->get();
      return view('fallas.index', compact('fallas'));
  }
  public function create()
  {
    return view('fallas.create');
  }

  public function store(Request $request)
  {
      $request->validate([
        'descripcion' => 'required',
        'tipo' => 'required',
        'estado' => 'required']);

      $input = $request->all();
      $input['tokenx'] = Str::random(30);
      $obj   = Fallas::create($input);

      if( !isset($obj) ){

        if($input['tipo'] == 0)
          return redirect()->route('fallas.index.cliente')->with('success','Actualizado con éxito.');
        else
          return redirect()->route('fallas.index.tecnico')->with('success','Actualizado con éxito.');

      }
      if($input['tipo'] == 0)
        return redirect()->route('fallas.index.cliente')->with('success','Actualizado con éxito.');
      else
        return redirect()->route('fallas.index.tecnico')->with('success','Actualizado con éxito.');

  }

  public function edit($token)
  {
      $falla = Fallas::where('tokenx', $token)->first();
      return view('fallas.edit', compact('falla'));
  }


  public function update(Request $request, $token)
  {
     $input    = $request->all();
     $request->validate([
       'descripcion' => 'required',
       'tipo' => 'required',
       'estado' => 'required']);
     $falla = Fallas::where('tokenx', $token)->first();

     $falla->descripcion = $input['descripcion'];
     $falla->tipo        = $input['tipo'];
     $falla->estado      = $input['estado'];

     $doit = $falla->save();
     if( $doit )
      if($input['tipo'] == 0)
        return redirect()->route('fallas.index.cliente')->with('success','Actualizado con éxito.');
      else
        return redirect()->route('fallas.index.tecnico')->with('success','Actualizado con éxito.');

     else
      return redirect()->route('fallas.index.cliente')->with('error','No se puede actualizar');
  }


  public function destroy($token)
  {
      $obj = Fallas::where('tokenx', $token)->first();
      if( $obj->delete() && isset($obj) ){

          if( $obj->tipo == 0)
            return redirect()->route('fallas.index.cliente')->with('success','Actualizado con éxito.');
          else
            return redirect()->route('fallas.index.tecnico')->with('success','Actualizado con éxito.');


      }else{

        if( $obj->tipo == 0)
          return redirect()->route('fallas.index.cliente')->with('success','Actualizado con éxito.');
        else
          return redirect()->route('fallas.index.tecnico')->with('success','Actualizado con éxito.');

      }


  }
}
