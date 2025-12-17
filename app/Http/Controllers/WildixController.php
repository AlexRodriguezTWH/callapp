<?php

namespace App\Http\Controllers;


use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\EnVivo;



class WildixController extends Controller
{
	public function indexget(){
		echo "OK";
	}
	public function index(Request $request){
      	
      	$filename = '../storage/app/calls/data_' . date('Ymd', time()) . '.json';
		//$data = $request->all();


	    $headers = apache_request_headers();
		$jsonData = json_encode($headers, JSON_PRETTY_PRINT, 500);
		$linea    = date("H:i:s", time()) . ":" . $jsonData . "\n";
		$fp       = fopen($filename,"a+");
		fwrite($fp, $linea);
		fclose($fp);


		$jsonData = json_encode($request->all() );
	    $linea    = date("H:i:s", time()) . ":" . $jsonData . "\n\n\n";
	    
	    $fp       = fopen($filename,"a+");
	    fwrite($fp, $linea);
	    fclose($fp);

 		return response('OK Wildix', 200)->header('Content-Type', 'text/plain');

	    /*
		$array = json_decode(     file_get_contents("../storage/app/public/call_live_progress.json"), true      );
		if( isset($array) ){
			switch ($array['data']['event']) {
				
				#LLAMADA START/UPDATE
				case 'call':
				default:
					$response = null;
					switch(  isset($array['data']['eventTrigger'])  ){
						#LLAMADA START
						case 'call.start':
							$response = $this->insert($array['data']);
							//dd(   $response   );
						break;
						#LLAMADA UPDATE
						case 'call.update':
							$response = $this->update($array['data']);
						break;
					}

					#CONTROL DE RESPUESTA
					if( $response['status'] == 'success' ){
							return response('OK Wildix', 200)->header('Content-Type', 'text/plain');
					}else{
						return response('NOK Wildix', 200)->header('Content-Type', 'text/plain');
					}

					

				break;

				#LLAMADA COMPLETA
				case 'call_complete':
					$response = null;

					if( $response['status'] == 'success' ){
							return response('OK Wildix', 200)->header('Content-Type', 'text/plain');
					}else{
						return response('OK CALL-COMPLETE', 200)->header('Content-Type', 'text/plain');
					}

				break;
			}
		}#EN
  		*/

  		
  	}

  	public function log($file){
  		$fileName = 'calls/data_' . $file . '.json';
	    if(     Storage::exists($fileName))     {
	        // Si el archivo existe, lee su contenido y decodifícalo
	        $existingData = json_decode(Storage::get($fileName), true);
	    } else {
	        $existingData = [];
	    }
	    dd($existingData);
  	}
  	private function insert($params){

		$plaza                  = $this->getPlaza($params['company']);
		$nuevoTicket            = null;

		$input['Fechatms'] 		= date('Y-m-d H:i:s.000', time());
		$input['CallerID'] 		= $params['flows'][0]['destination'];
		$input['Duracion'] 		= $params['flows'][0]['duration'];
		$input['Plaza'] 		= $plaza['IdPlaza'];
		$input['IdStattick'] 	= '01';
		$input['callid'] 		= $params['id'];
		$input['IdPlaza'] 		= $plaza['IdPlaza'];
		$input['IdCompania'] 	= $plaza['IdCompania'];
		$input['IdExt'] 		= $params['flows'][0]['caller']['userExtension'];

		$nuevoTicket            = true; # Envivo::create($input);
		
		if( isset($nuevoTicket) ){
			$array['status'] = 'success';
			$array['ticket'] = $input;
		}else{
			$array['status'] = 'error';
		}

		return $array;
	}
	private function update($params){ #>
		$ticket = null;
		$ticket = EnVivo::where('callid', $params['id'])->update(['Duracion' => $params['flows'][0]['duration'] ]);
		
		if( isset($ticket) ){
			$array['status'] = 'success';
			$array['ticket'] = $ticket;
		}else{

			#SI NO ENCUENTRA LA LLAMADA LA INSERTA Y ACTUALIZA LA DURACION
			$call  = $this->insert($params);
			if( isset($call) ){
				$array['status'] = 'success';
			}else{
				$array['status'] = 'error';
			}

		}

		return $array;
	}
	private function getPlaza($company){
		switch ($company) {
			case 'it_w000001':
				$cia['IdPlaza']    = 'CJS';
				$cia['IdCompania'] = 2;
			break;
		}
		return $cia;
	}



}#END OF CLASS