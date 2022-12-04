<?php
$student = [
    "Documento" => $_POST["codTesoreria"],
    "Apellido1" => $_POST["apellido1"],
    "Apellido2" => $_POST["apellido2"],
    "Nombre1" => $_POST["nombres"],
    "NombreOtros" => $_POST["nombre2"],
    "MunicipioResidencia" => $_POST["ciudadR"],
    "DireccionPrincipal" => $_POST["direccion"],
    "TelefonoPrincipal" => $_POST["telefono"],
    "Correo" => strtolower($_POST["email"]),
    "TelefonoCelular" => $_POST["celular"],
    "EstratoSocial" => $_POST["estrato"],
    "FechaNacimientoEstudiante" => $_POST["fNac"],
    "SexoEstudiante" => $_POST["genero"],
    
    "IdTipoDocumentoAcudiente" => $_POST["tipoDAcudiente"],
    "DocumentoAcudiente" => $_POST["documentoA"],
    "Apellido1Acudiente" => $_POST["apellido1A"],
    "Apellido2Acudiente" => $_POST["apellido2A"],
    "Nombre1Acudiente" => $_POST["nombresA"],
    "NombreOtrosAcuediente" => $_POST["nombre2A"],
    "SexoAcudiente" => $_POST["generoA"],
    "DocumentoUsuario" => "1120354377",
    "Token" => "4E0D689E2DD74471A41611E1D7960EA5"
];

/*echo "<pre>";
echo "Ver la estructura";
print_r(json_encode($student));
echo "<br>";*/
	
$service_url = 'http://sion.icolven.edu.co/Services/SINTIAService.svc/CreateTercero';

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