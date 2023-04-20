<?php
include($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/constantes.php");
//include(ROOT_PATH."/conexion-datos.php");
$conexionBaseDatosServicios = mysqli_connect($servidorConexion, $usuarioConexion, $claveConexion, $baseDatosServicios);

$consultaEmpresas = mysqli_query($conexionBaseDatosServicios,"SELECT * FROM datos_contacto 
WHERE dtc_id=1");
$datosEmpresa = mysqli_fetch_array($consultaEmpresas, MYSQLI_BOTH);

//echo "Envia: ".$datosEmpresa['dtc_clave_email'];

//Server settings
$mail->SMTPDebug = 2;                                       // Enable verbose debug output
$mail->isSMTP();                                            // Set mailer to use SMTP
$mail->Host       = 'jemima.dongee.com';  	// Specify main and backup SMTP servers
$mail->SMTPAuth   = true;                                   // Enable SMTP authentication
$mail->Username   = $datosEmpresa['dtc_email'];                  
$mail->Password   = $datosEmpresa['dtc_clave_email'];                      
$mail->SMTPSecure = 'ssl';                                  // Enable TLS encryption, `ssl` also accepted
$mail->Port       = 465;

//Recipients
$mail->setFrom($datosEmpresa['dtc_email'], $datosEmpresa['dtc_nombre']);
