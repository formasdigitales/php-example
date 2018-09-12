<?php	
require_once('./cliente_formas_digitales.php');
	header ('Content-type: text/html; charset=utf-8');	
	try {
	
		set_time_limit(0);
		date_default_timezone_set("America/Mexico_City");

		/* carga archivo xml */
		//$xml = simplexml_load_file ("C:\\xmls\\08F72325-37B4-47C1-8B5F-354D04FA7DF5.xml");
				
		/* Esto es para cargar el xml en una sola cadena, tal como lo necesita el web service
		   en esta parte se recomienda que los caracteres raros y acentuados se metan con 
		   secuencia de escape para xml.
		   aqui se pueden dar una idea... http://xml.osmosislatina.com/curso/basico.htm		   
		*/
		$filename = dirname(__FILE__) . "/resources/tmpxml.xml"; 
		$certFile =  dirname(__FILE__) . "/resources/CSD_Pruebas_CFDI_LAN7008173R5.cer";
		$keyFile =  dirname(__FILE__) . "/resources/CSD_Pruebas_CFDI_LAN7008173R5.key.pem";
		/*$output=""; 
		$file = fopen($filename, "r"); 
		while(!feof($file)) { 
			//read file line by line into variable 
		  $output = $output . fgets($file, 4096); 
		} 
		fclose ($file); */
		//echo $output; 
	
		
		$clienteFD = new ClienteFormasDigitales($filename);

		/* esto es solo para informativo */
		//var_dump($client->__getFunctions());

		/* se le pasan los datos de acceso */
		$autentica = new Autenticar();
		$autentica->usuario = "pruebasWS";
		$autentica->password = "pruebasWS";

		$parametros = new Parametros();

		$parametros->accesos = $autentica;
		$parametros->comprobante = $clienteFD->sellarXML($certFile, $keyFile);;
		
		/* se manda el xml a timbrar */
		$responseTimbre = $clienteFD->timbrar($parametros); 
		
		//var_dump($responseTimbre);

		
		//$timbrar->token = $responseAutentica->return->token; 
		
		/* cacha la respuesta */
		//$responseTimbre = $client->Timbrar($timbrar);
		//var_dump($responseTimbre);
		//echo "<br><br><br>MSG SOAP REQUEST:<br><br>" . $client->__getLastRequest() . "\n";
		//echo "<br><br><br>MSG SOAP REQUEST:<br><br>" . $client->__getLastResponse() . "\n";
		
		
		/* solo informativo... muestra el codigo de error en caso de existir y resultados */
		if(isset($responseTimbre->acuseCFDI->error)){
			echo "codigoErr: " . $responseTimbre->acuseCFDI->error. "<br>";
		}

		if($responseTimbre->acuseCFDI->xmlTimbrado){
			echo 'XML TMIBRADO:<BR> <textarea>' . $responseTimbre->acuseCFDI->xmlTimbrado . '</textarea>';
		}

	
		
		
	} catch (SoapFault $e) {
		print("Auth Error:::: $e");
	}


	
class Autenticar{
	public $usuario;
	public $password;
}


class Parametros{
	public $accesos;
	public $comprobante;
}
?>