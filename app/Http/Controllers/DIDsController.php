<?php

namespace App\Http\Controllers;

use App\DIDs;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DIDsController extends Controller
{

      public function index()
      {
          $dids = DIDs::orderBy('nombre', 'ASC')->get();
          return view('dids.index', compact('dids'));
      }
      public function create()
      {
        return view('dids.create');
      }

      public function store(Request $request)
      {
          $request->validate([
            'nombre' => 'required',
            'did' => 'required',
            'plaza' => 'required',
            'estado' => 'required']);

          $input = $request->all();
          $input['tokenx'] = Str::random(30);

          $obj = DIDs::create($input);
          if( !isset($obj)){
            return redirect()->route('dids.index')->with('error','Intenta mas tarde!');
          }
          return redirect()->route('dids.index')->with('success','Creado con éxito.');
      }

      public function edit($token)
      {
          $did = DIDs::where('tokenx', $token)->first();
          return view('dids.edit', compact('did'));
      }


      public function update(Request $request, $token)
      {
         $input    = $request->all();
         $request->validate([
           'nombre' => 'required',
           'did' => 'required',
           'plaza' => 'required',
           'estado' => 'required']);
         $did = DIDs::where('tokenx', $token)->first();

         $did->nombre = $input['nombre'];
         $did->did = $input['did'];
         $did->plaza = $input['plaza'];
         $did->estado = $input['estado'];

         $doit = $did->save();
         if( $doit )
          return redirect()->route('dids.index')->with('success','Actualizado con éxito.');
         else
          return redirect()->route('dids.index')->with('error','No se puede actualizar');
      }


      public function destroy($token)
      {
          $obj = DIDs::where('tokenx', $token)->first();

          if( $obj->delete() && isset($obj) )
           return redirect()->route('dids.index')->with('success','Eliminado con éxito.');
          else
           return redirect()->route('dids.index')->with('error','No se puede eliminar');
      }
}
