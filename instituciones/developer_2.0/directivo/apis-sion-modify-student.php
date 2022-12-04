<?php
$student = [
    "Documento"=> $_POST["codTesoreria"],
    "MunicipioResidencia"=> $_POST["ciudadR"],
    "Direccion"=> $_POST["direccion"],
    "TelefonoFijo"=> $_POST["telefono"],
    "Correo"=> strtolower($_POST["email"]),
    "Celular"=> $_POST["celular"],

    "IdTipoDocumentoA"=> $_POST["tipoDAcudiente"],
    "DocumentoAcudiente"=> $_POST["documentoA"],
    
    "DocumentoUsuarioActualizador"=> "1120354377",

    "Token"=> "FA2BB41D20BC4675A9056EBCE5C3669B"
];


//echo "Ver la estructura";
//print_r(json_encode($student));

	
$service_url = 'http://sion.icolven.edu.co/Services/SINTIAService.svc/UpdateTercero';

$options = array(
    'http' => array(
        'header'  => "Content-type: application/json",
        'method'  => 'POST',
        'content' => json_encode($student),
    )
);

$context  = stream_context_create($options);

$result = file_get_contents($service_url, false, $context);

$jsonObject = json_decode($result);

foreach ($jsonObject as $k=>$v){
    //echo "$k : $v\n";
    if($k ==='Message'){
        $mensaje = $v;
    }

    if($k ==='State'){
        $estado = $v;
    }
}

//print_r($jsonObject); exit();