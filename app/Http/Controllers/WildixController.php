<?php

namespace App\Http\Controllers;


use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\EnVivo;
use App\PlazasWildix;



class WildixController extends Controller
{
	public function indexget(){
		echo "OK";
	}
	public function index(Request $request){
      	
      	
      	$payload = $request->all();


      	#LOG ----------------------------------------------------------------------------
      	/*
	     	$filename = '../storage/app/calls/data_' . date('Ymd', time()) . '.json';
			$jsonData = json_encode($payload);
		    $linea    = date("H:i:s", time()) . ": LOG DATA: " . $jsonData . "\n";
	        $fp       = fopen($filename,"a+");
		    fwrite($fp, $linea);
		    fclose($fp);	
		  */  
		
		#END LOG -------------------------------------------------------------------------


        // Campos base del payload
        $connectTimeIso = null;
        $id      		= isset($payload['id']) ? $payload['id'] : null;
        $pbx     		= isset($payload['pbx']) ? $payload['pbx'] : null;
        $company 		= isset($payload['company']) ? $payload['company'] : null;
        $time    		= isset($payload['time']) ? $payload['time'] : null;
        $type    		= isset($payload['type']) ? $payload['type'] : null;
        $data    		= isset($payload['data']) ? $payload['data'] : [];
        $event 			= isset($data['event']) ? $data['event'] : null;           // event/eventTrigger pueden no existir en algunos webhooks
        $eventTrigger 	= isset($data['eventTrigger']) ? $data['eventTrigger'] : null;
        $callerPhone 	= null; 
        $destination 	= isset($data['destination']) ? $data['destination'] : null;
        $status 		= isset($data['status']) ? $data['status'] : null;
        $flowData       = null;
        $flow0          = null;
        $response       = null;
        $connectTime 	= null;


         if (isset($data['flows'][0]) ){
				$connectTimeIso = data_get($data, 'flows.0.statusChangeDate'); // Ej: "2025-11-11T00:59:20.863Z"


	        	$flow0 = isset($data['flows'][0]) ? $data['flows'][0] : null;

	        	$flow_data_json = json_encode([
											        'talkTime' 		=> $flow0['talkTime'] ?? null,
											        'queueTime' 	=> $flow0['queueTime'] ?? null,
											        'holdTime'  	=> $flow0['holdTime'] ?? null,
											        'duration'  	=> $flow0['duration'] ?? null,
											        'connectTime' 	=> $flow0['connectTime'] ?? null,
											    ]);   

				$data['statusChangeDate'] 	= $connectTimeIso ?? null;
				$data['flowData']          	= $flow_data_json ?? null;
				$connectTime 				= $flow0['connectTime'] ?? null;
         }

        if (isset($data['caller']) && is_array($data['caller']) && isset($data['caller']['phone'])) {
            $callerPhone = $data['caller']['phone'];

        } elseif (isset($data['flows']) && is_array($data['flows']) && isset($data['flows'][0]['caller']['phone'])) {
            $callerPhone = $data['flows'][0]['caller']['phone'];
        	
        } elseif (isset($data['flows']) && is_array($data['flows']) && isset($data['flows'][0]['caller']['phone'])) {
            $callerPhone = $data['flows'][0]['caller']['phone'];
        }

        $data['id']= $id;
        $data['company'] = $company;
        $data['callerPhone']   	= $callerPhone;
        $data['connectTime'] 	= $connectTime;
        $data['json']   		= json_encode($payload);

 		if ($eventTrigger) { 
            switch ($eventTrigger) {
                case 'call.update':

                	if($data['flowData'] != null && $data['statusChangeDate'] != null)
                    	$response = $this->update($data);
                break;
                default:
                    $response = array('status' => 'ignored eventTrigger');
                    break;
            }


        #	CASE EVENT TYPE Y NO HAY EVENT TRIGGER
        } else {

        	

        	
        	//dd('NO HAY EVENTTRIGGER', $data);
            switch ($type) {
                case 'call:start':
                    $response = $this->insert($data);

                break;

                case 'call:end':
                    // cuando termina la llamada, envia el evento call:end
                    $response = $this->update($data, true);
                    break;

				
                default:
                    $response = array('status' => 'ignored type');
                    break;
            }
            
        }


        #dd($type, $eventTrigger, $response);
        $response['type'] = $type;
        $response['eventTrigger'] = $eventTrigger;
 		return response( $response , 200)->header('Content-Type', 'text/plain');

	}


  	private function insert($params){

		
		$nuevoTicket    = null;
		$ticket 		= EnVivo::where('callid', $params['id'])->first();
		$filename 		= '../storage/app/calls/data_' . date('Ymd', time()) . '.json';
		$linea0   		= date("H:i:s", time()) . ": JSON DATA: " . $params['json'] . "\n";
		$destination    = $params['destination'] ?? null;
		$plaza          = $this->getPlaza($destination);

		#dd($destination, $plaza, $ticket);
		if( isset($ticket) ){   

	        #LOG ----------------------------------------------------------------------------
	        /*
	        	
				$jsonData = json_encode($ticket);

			    $linea    = date("H:i:s", time()) . ": INSERT DOBLE TICKET: " . $jsonData . "\n\n\n";
		        $fp       = fopen($filename,"a+");
			    fwrite($fp, $linea);
			    fclose($fp);
			#END LOG ------------------------------------------------------------------------
			*/
			    return false;
		}


		$input['Fechatms'] 		= date('Y-m-d H:i:s.000', time());
		$input['CallerID'] 		= $params['callerPhone'] ??   null ;
		$input['Duracion'] 		= null;
		$input['Plaza'] 		= $plaza['IdPlaza'];
		$input['IdStattick'] 	= null;
		$input['callid'] 		= $params['id'];
		$input['IdPlaza'] 		= $plaza['IdPlaza'];
		$input['IdCompania'] 	= $plaza['IdCompania'];
		$input['IdExt'] 		= isset($params['flows'][0]['callee']['userExtension'])? $params['flows'][0]['callee']['userExtension'] : null ;
		$input['statusChangeDate'] 	= ( isset( $params['statusChangeDate'] ) )? $params['statusChangeDate'] : null;
		$input['flowData'] 			= ( isset( $params['flowData'] ) ) ?  $params['flowData']  : null;
		#dd($input);

        #LOG ----------------------------------------------------------------------------
        	$filename = '../storage/app/calls/data_' . date('Ymd', time()) . '.json';
			$jsonData = json_encode($input);
			$linea0   = date("H:i:s", time()) . "  JSON DATA: " . $params['json'] . "\n" . $input['flowData']  . "\n";
		    $linea    = date("H:i:s", time()) . ": INSERT DATA: " . $jsonData . "\n\n";
	        $fp       = fopen($filename,"a+");

	        fwrite($fp, $linea0);
		    fwrite($fp, $linea);
		    fclose($fp);
		    
		#END LOG ------------------------------------------------------------------------



		#$nuevoTicket            = true; 
		$nuevoTicket            = Envivo::create($input);
		
		if( isset($nuevoTicket) ){
			$array['status'] = 'success';
			$array['ticket'] = $input;
		}else{
			$array['status'] = 'error';
		}

		return $array;
	}
	private function update($params, $end = 0){ #>
		


		$ticket 		= null;
		$diffInSeconds 	= null;

		$ticket 		= EnVivo::where('callid', $params['id'])->first();
		
		$doit   		= null;
		$now 			= Carbon::now(); // mejor usar UTC también para evitar desfases
		
		#$callee			= isset($params['callee'])? $params['callee']   : null;
		#$userExtension 	= isset($callee['userExtension']) ? $callee['userExtension']      : null;
		$userExtension      = data_get($params, 'flows.0.callee.userExtension') ?? 0;

		#dd($ticket, $params, $params['id']);

			#LOG ----------------------------------------------------------------------------
	        	$filename = '../storage/app/calls/data_' . date('Ymd', time()) . '.json';
		    #END LOG -------------------------------------------------------------------------



		if( isset($ticket)  && ($params['connectTime'] != null || $end ) ){

				$statusChangeDate 	= $now->format('Y-m-d H:i:s');


				if($ticket->duracion <= 0){
					$diffInSeconds 	= $this->diffInSecondsFromNow($ticket->Fechatms);
				}else
					$diffInSeconds  = $ticket->duracion;



				if( isset($params['flowData']) && $params['flowData'] != null ){
					$flow_data = $params['flowData'];
				}else{
					$flow_data = $ticket->flowData;
				}


				if( $ticket->IdExt != null || $ticket->IdExt != ""){
					$userExtension = $ticket->IdExt;
				}


				$dataArrayUpdate = [
					'statusChangeDate' => $statusChangeDate,
					'flowData' => $flow_data,
					'Duracion' => (int)$diffInSeconds,
					'IdExt'    => $userExtension
				];

				$doit = $ticket->update($dataArrayUpdate);


				#LOG ----------------------------------------------------------------------------
				
					$jsonData = json_encode($dataArrayUpdate);
				    $linea0   = date("H:i:s", time()) . " " . $end . ": JSON DATA: " . $params['json'] . "\n";
				    $linea    = date("H:i:s", time()) . " " . $end . ": UPDATE DATA: " . $jsonData . "\n";
			        $fp       = fopen($filename,"a+");
			        fwrite($fp, $linea0);
				    fwrite($fp, $linea);
				    fclose($fp);		
				    	
			    #END LOG -------------------------------------------------------------------------
		}
		
		 
		if( $doit  != null  ){
			$array['status'] = 'success';
			$array['ticket'] = $ticket;
		}else{

			#SI NO ENCUENTRA LA LLAMADA LA INSERTA Y ACTUALIZA LA DURACION
			//dd( $ticket, $doit);
			$array  = $this->insert($params);
			if( !isset($array) ){
				$array['status'] = 'error';
			}

		}
		/*
		#LOG ----------------------------------------------------------------------------
			$jsonData = json_encode($array);
			$linea    = date("H:i:s", time()) . ":       UPDATE RESPONSE: " . $jsonData . "\n\n\n";
			$fp       = fopen($filename,"a+");
			fwrite($fp, $linea);
			fclose($fp);		
		*/
		#END LOG -------------------------------------------------------------------------

		return $array;
	}
	private function getPlaza($company){
		$cia = null;
		$cia = PlazasWildix::where('clave', $company)->first();

		if( !isset( $cia )){
				$cia['IdPlaza']    = 'CJS';
				$cia['IdCompania'] = 2;
				return $cia;
		}else{
			return $cia;
		}
	}


	private function diffInSecondsFromNow($dateString)
	{
	    try {
	        $givenDate = Carbon::parse($dateString);
	        $now = Carbon::now();
	        return $now->diffInSeconds($givenDate);
	    } catch (\Exception $e) {
	        // En caso de que la fecha sea inválida
	        return null;
	    }
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

}#END OF CLASS