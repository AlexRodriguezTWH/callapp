<?php
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', function () {    return view('welcome');    })->name('login.index');

// routes/web.php
Route::get('/_diag/db', function () {
    return response()->json([
        'php_version' => PHP_VERSION,
        'db_default' => config('database.default'),
        'host' => config('database.connections.mysql.host'),
        'port' => config('database.connections.mysql.port'),
        'database' => config('database.connections.mysql.database'),
        'username' => config('database.connections.mysql.username'),
        'socket' => config('database.connections.mysql.unix_socket') ?? null,
    ]);
});

Route::get('wildix/webhook', 'WildixController@indexget')->name('api.wildix.get');
Route::post('wildix/webhook', 'WildixController@index')->name('api.wildix');
Route::get('wildix/log/{file} ', 'WildixController@log')->name('api.wildix.log');
 

Auth::routes();
Route::get('/home', 'TicketsController@index')->name('home');
Route::post('gr/login', 'LoginGRController@login')->name('gr.login');
Route::get('page/error', function(){    return view('error.sin-permiso');  })->name('permiso.error');

#RUTAS PARA EL DASHBOARD BUSSINESS
###############################################################################
Route::group(['middleware' => 'auth'], function() {

  Route::get('gr/logout', 'LoginGRController@logout')->name('gr.logout');

  # TICKETS
  Route::get('tickets/envivo', 'TicketsController@index')->name('tickets.envivo');
  Route::get('tickets/show/{id}', 'TicketsController@view')->name('ticket.view');
  Route::post('tickets/show/{id}', 'TicketsController@viewStore')->name('ticket.store.view');
  Route::get('tickets/pendientes', 'TicketsController@pendientes')->name('tickets.pendientes');
  Route::get('tickets/historial', 'TicketsController@historial')->name('tickets.historial');
  Route::post('tickets/update/{id}', 'TicketsController@update')->name('ticket.update');
  // BUSQUEDA
  Route::get('tickets/busqueda', 'TicketsController@search')->name('tickets.search');
  Route::post('tickets/busqueda', 'TicketsController@search')->name('tickets.search');

  //SERVICIOS
  Route::get('servicios', 'TiposDeServicioController@index')->name('servicio.index');
  Route::get('servicios/nuevo', 'TiposDeServicioController@create')->name('servicio.create');
  Route::post('servicios/nuevo', 'TiposDeServicioController@store')->name('servicio.store');
  Route::get('servicios/editar/{token}', 'TiposDeServicioController@edit')->name('servicio.editar');
  Route::post('servicios/editar/{token}', 'TiposDeServicioController@update')->name('servicio.update');
  Route::delete('servicios/delete/{token}', 'TiposDeServicioController@destroy')->name('servicio.destroy');

  // DIDS
  Route::get('dids', 'DIDsController@index')->name('dids.index');
  Route::get('dids/nuevo', 'DIDsController@create')->name('dids.create');
  Route::post('dids/nuevo', 'DIDsController@store')->name('dids.store');
  Route::get('dids/editar/{token}', 'DIDsController@edit')->name('dids.editar');
  Route::post('dids/editar/{token}', 'DIDsController@update')->name('dids.update');
  Route::delete('dids/delete/{token}', 'DIDsController@destroy')->name('dids.destroy');

  // MOTIVOS LLAMADA
  Route::get('motivosllamada', 'MotivosLlamadaController@index')->name('motivos.index');
  Route::get('motivosllamada/nuevo', 'MotivosLlamadaController@create')->name('motivos.create');
  Route::post('motivosllamada/nuevo', 'MotivosLlamadaController@store')->name('motivos.store');
  Route::get('motivosllamada/editar/{token}', 'MotivosLlamadaController@edit')->name('motivos.editar');
  Route::post('motivosllamada/editar/{token}', 'MotivosLlamadaController@update')->name('motivos.update');
  Route::delete('motivosllamada/delete/{token}', 'MotivosLlamadaController@destroy')->name('motivos.destroy');


  // FALLAS
  Route::get('tipo_falla/tecnico', 'FallasController@indextecnico')->name('fallas.index.tecnico');
  Route::get('tipo_falla/nuevo', 'FallasController@create')->name('fallas.create');
  Route::post('tipo_falla/nuevo', 'FallasController@store')->name('fallas.store');
  Route::get('tipo_falla/editar/{token}', 'FallasController@edit')->name('fallas.editar');
  Route::post('tipo_falla/editar/{token}', 'FallasController@update')->name('fallas.update');
  Route::delete('tipo_falla/delete/{token}', 'FallasController@destroy')->name('fallas.destroy');


  #AJAX REQUEST
  Route::group(['prefix'=>'ajax', 'as'=>'ajax::'], function(){

      Route::get('data/ticket/{id}', 'TicketsController@show')->name('ajax.data.show');
      Route::get('data/puntos/{idPlaza}', 'TicketsController@ajaxPuntos')->name('ajax.data.puntos');
      Route::get('data/punto/{plaza}/{codigo}', 'TicketsController@ajaxGetPorPunto')->name('ajax.data.codigo');
      Route::get('data/codigo/{plaza}/{codigo}', 'TicketsController@ajaxGetPorCodigo')->name('ajax.data.codigo');
      Route::get('data/motivos/{servicio}', 'TicketsController@ajaxMotivos')->name('ajax.data.motivos');
      Route::get('data/falla', 'TicketsController@ajaxFalla')->name('ajax.data.falla');
      
      Route::get('data/falla/serviciosFallitas/{idServicio}/{idMotivo}/{idTipoReporte}', 'TicketsController@ajaxFallasXServicio')->name('ajax.data.falla.servicios'); //-----------

      Route::get('data/envivo', 'TicketsController@ajaxEnVivo')->name('ajax.data.envivo');


      Route::post('ticket/store', 'TicketsController@store')->name('ticket.store');
      Route::post('ticket/update/{ticket}', 'TicketsController@update')->name('ticket.update');
  });


});
