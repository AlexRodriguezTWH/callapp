<?php
#<>
#x-signature:  rCCjGdtVWsKtOTbX965BK1Waxk3xe5


    $filename = 'data_' . date('Ymd', time()) . '.json';


    # WRITE HEADERS
    /*
	    $headers = apache_request_headers();
		$jsonData = json_encode($headers, JSON_PRETTY_PRINT, 500);
		$linea    = date("H:i:s", time()) . ":" . $jsonData . "\n";
		$fp       = fopen($filename,"a+");
		fwrite($fp, $linea);
		fclose($fp);

		

		# WRITE BODY
		$data     = json_encode($_REQUEST, JSON_PRETTY_PRINT, 500);
		$linea    = date("H:i:s", time()) . ":" . $data . "\n\n\n";
		$fp       = fopen($filename,"a+");
		fwrite($fp, $linea);
		fclose($fp);
	*/




	$array = json_decode(     file_get_contents("call_live_progress.json"), true      );
	if( isset($array) ){
		switch ($array['data']['event']) {
			
			#LLAMADA START/UPDATE
			case 'call':
			default:

				switch(  isset($array['data']['eventTrigger'])  ){
					#LLAMADA START
					case 'call.start':
						$call = insert($array['data']);
						//print_r($call);
					break;
					#LLAMADA UPDATE
					case 'call.update':
					
					break;
				}


			break;

			#LLAMADA COMPLETA
			case 'call_complete':
			
			break;
		}
	}#END IF

	print_r($array['data']['flows'][0]['destination']);

	function insert($params){

		$plaza                  = getPlaza($params['company']);

		$input['Fechatms'] 		= date('Y-m-d H:i:s.000', time());
		$input['CallerID'] 		= $params['flows'][0]['destination'];
		$input['Duracion'] 		= 0; #$array['data']['flows'][0]['duration'];
		$input['Plaza'] 		= $params['company'];
		$input['IdStattick'] 	= '01';
		$input['callid'] 		= $params['id'];
		$input['IdPlaza'] 		= $plaza['IdPlaza'];
		$input['IdCompania'] 	= $plaza['IdCompania'];

		$input['IdExt'] 		= $params['flows'][0]['caller']['userExtension'];

		print_r($input);

		return $input;

	}

	function getPlaza($company){

		switch ($company) {
			case 'it_w000001':
				$cia['IdPlaza']    = 'CJS';
				$cia['IdCompania'] = 2;
			break;

		}

		return $cia;
	}

?>