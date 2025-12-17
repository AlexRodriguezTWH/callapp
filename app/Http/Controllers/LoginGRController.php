<?php

namespace App\Http\Controllers;

use App\User;
use App\LoginGR;
use App\UsuarioGR;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessBuilder;
use Symfony\Component\Process\Exception\ProcessFailedException;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;



class LoginGRController extends Controller
{

    public function login(Request $request)
    {
      $input = $request->all();
      $data= null;
      if( !isset($input['usuario']) ){
        return redirect()->route('login.index')->with('error','GR: Usuario y contraseña incorrecto');
      }

      if( !isset($input['pass']) ){
        return redirect()->route('login.index')->with('error','WEB: Has olvidado incluir tu contraseña');
      }

      $data = UsuarioGR::DataUserGR($input['usuario']);
      if( !isset($data) || $data==null ){
          return redirect()->route('login.index')->with('error','GR: Usuario no encontrado');
      }



        # PROCESO DE LOGEO
        #dd($data);
        $authjar           = realpath(    base_path("resources/gr/Authentication.jar")   );
        $usuario           = $data[0]->IdUsuario;
        $SalContrasena     = $data[0]->SalContrasena;
        $encryptedPassword = $data[0]->Contrasena;
        $nombre            = $data[0]->Nombre;

        $java    = "C:\PROGRA~1\Java\jre1.8.0_471\bin\java.exe";
        $comando = $java . " -jar " . $authjar . " " . $usuario . " " . $input['pass']. " " . $SalContrasena . " " . $encryptedPassword ;
        #dd($comando);
        $process = Process::fromShellCommandline($comando);
        $process->run();

        if(!$process->isSuccessful())
          throw new ProcessFailedException($process);

        $salida = $process->getOutput();
        if( !isset($salida) || $salida == "NOK" ){
          return redirect()->route('login.index')->with('error','GR: Contraseña Incorrecta');
        }


        // USER_WH_USER
        $data    = ['email' => env('USER_WH_USER'), 'password' => env('USER_WH_PASS')];

        if (Auth::attempt($data)) {// Como segundo parámetro pasámos el checkbox para sabes si queremos recordar la contraseña{
          session()->put('gr_user', $usuario);
          session()->put('gr_name', $nombre);

          $message = "Bienvenido $nombre/$usuario";


          # OBTENER EL ROL DEL USUARIO PARA CALLCENTER
          # SETEAR EL ROL DEFAULT
          $roles   =  collect( UsuarioGR::Rol($usuario) );
          if(!isset($roles)){
            return redirect()->route('login.index')->with('error','GR: No tienes acceso a estas funciones de Call Center');
          }else{
            $rol_predeterminado = $roles->where('IdRol', 14)->first();

            if(!$rol_predeterminado){
                $rol_predeterminado = $roles[0];
            }
          }

          # OBTENER LAS PLAZAS ASIGNADAS AL USUARIO
          # OBTENER UNA PLAZA DEFAULT.-
          # DEFAULT = ASIGNADA EN EL ROL DE CALLCENTER 13 O 14
          $plazas  =  UsuarioGR::PlazasAsignadas($usuario);
          if(  !isset($plazas)   ){
            return redirect()->route('login.index')->with('error','GR: No tienes asignadas plazas');
          }else{
            $plaza_predeterminada = $rol_predeterminado->IdCompania;
            $arrPlazas            = null;
            $arrPlazas            = collect($plazas)->pluck('IdAlmacen', 'IdCompania')->toArray();
          }

          # OBTENER PERMISOS DEL ROL DEFAULT
          $permisos = null;
          $permisos = collect(UsuarioGR::Permisos($rol_predeterminado->IdRol, $rol_predeterminado->IdCompania));

          //dd($roles, $rol_predeterminado, $plazas, $permisos);
          // CALLUI001       TICKETS EN VIVO
                // CALLUI001A      AGREGAR TICKET
                // CALLUI001E      EDITAR TICKET
          // CALLUI002       TICKET PENDIENTES
          // CALLUI003       HISTORIAL DE TICKETS

          $home        = $permisos->where('IdPrograma', 'CAL_UI001');
          $home_add    = $permisos->where('IdPrograma', 'CAL_UI001A');
          $home_update = $permisos->where('IdPrograma', 'CAL_UI001E');
          $pendientes  = $permisos->where('IdPrograma', 'CAL_UI002');
          $historial   = $permisos->where('IdPrograma', 'CAL_UI003');

          if($home_add->isNotEmpty())
            session()->put('opcion_ticket_store',  $home_add);

          if($home_update->isNotEmpty() )
            session()->put('opcion_ticket_update', $home_update);

          if( $home->isNotEmpty()  )
            session()->put('opcion_page_envivo',     $home);

          if( $pendientes->isNotEmpty()  )
            session()->put('opcion_page_pendiente', $pendientes);

          if(  $historial->isNotEmpty() )
            session()->put('opcion_page_historial', $historial);

          session()->put('gr_default_rol'  , $rol_predeterminado->IdRol);
          session()->put('gr_default_plaza', $rol_predeterminado->IdCompania);
          session()->put('gr_plazas', $arrPlazas);



          # SI TIENE HOME
          if( !$home->isEmpty() )
            return redirect()->route('home')->with('success', $message);

          if( $home->isEmpty()  && $pendientes->isNotEmpty() )
            return redirect()->route('tickets.pendientes')->with('success', $message);


          if( $home->isEmpty() && $historial->isNotEmpty() )
            return redirect()->route('tickets.historial')->with('success', $message);


          if( $home->isEmpty() && $pendientes->isEmpty() && $historial->isEmpty() )
            return redirect()->route('login.index')->with('error','No puedes visualizar ningún reporte');




        }else{
          return redirect()->route('login.index')->with('error','WEB Contraseña ADMIN Incorrecta');
        }

        return redirect()->route('login.index')->with('success','Contraseña CORRECTAAAAAA!!!!!');
    }

    public function logout(Request $request){
      $request->session()->forget('gr_user');
      $request->session()->forget('gr_name');
      $request->session()->forget('opcion_page_envivo');
      $request->session()->forget('opcion_ticket_store');
      $request->session()->forget('opcion_ticket_update');
      $request->session()->forget('gr_default_rol');
      $request->session()->forget('gr_default_plaza');
      $request->session()->forget('gr_plazas');

      Auth::logout();
      $request->session()->flush();
      return redirect()->route('login.index');
    }

}
