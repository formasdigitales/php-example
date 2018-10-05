#Ejemplo de timbrado en php para consumir webservice

En PHP tenemos la clase _ClienteFormasDigitales_ que nos ayuda en el proceso de timbrar el cfdi.

La clase la inicializamos pasandole de parametro el path del xml que vamos a timbrar

```PHP
$clienteFD = new ClienteFormasDigitales($xmlPath);
```

Tambie tenemos el metodo _sellarXML_ al que le pasamos de parametros el path del certificado y el path de la llave privada para generar el sello, obtener el numero de certificado y el certificado en base64.

```PHP
$clienteFD->sellarXML($certFile, $keyFile);
```
Este metodo nos devuelve el xml en string para posteriormente enviarlo al servicio web para timbrar el cfdi.

```PHP
$autentica = new Autenticar();
$autentica->usuario = "pruebasWS";
$autentica->password = "pruebasWS";

$parametros = new Parametros();

$parametros->accesos = $autentica;
$parametros->comprobante = $clienteFD->sellarXML($certFile, $keyFile);
		
		/* se manda el xml a timbrar */
$responseTimbre = $clienteFD->timbrar($parametros); 
 ```
 
 Depende si todo salio bien el _$responseTimbre_ tendra una variable que se llama _xmlTimbrado_ y si algo salio mal regresara una variable que se llama _error_ donde viene descrito por que no timbro el xml 
 
 ```PHP
 	/* solo informativo... muestra el codigo de error en caso de existir y resultados */
		if(isset($responseTimbre->acuseCFDI->error)){
			echo "codigoErr: " . $responseTimbre->acuseCFDI->error. "<br>";
		}

		if($responseTimbre->acuseCFDI->xmlTimbrado){
			echo 'XML TMIBRADO:<BR> <textarea>' . $responseTimbre->acuseCFDI->xmlTimbrado . '</textarea>';
		}

 ```
