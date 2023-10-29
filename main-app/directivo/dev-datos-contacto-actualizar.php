<?php 
include("session.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DV0031';
include("../compartido/historial-acciones-guardar.php");

try {
	mysqli_query($conexion, "UPDATE ".$baseDatosServicios.".datos_contacto SET  
	dtc_nombre='" . $_POST["nombre"] . "',
	dtc_email='" . $_POST["email"] . "',
	dtc_telefono='" . $_POST["telPrincipal"] . "',
	dtc_whatsapp='" . $_POST["whatsapp"] . "',
	dtc_celular='" . $_POST["celular"] . "',
	dtc_clave_email='" . $_POST["clave"] . "',
	dtc_dominio='" . $_POST["dominio"] . "',
	dtc_asesor_ventas='" . $_POST["asesor"] . "',
	dtc_email_ventas='" . $_POST["emailVentas"] . "',
	dtc_animacion_login='" . $_POST["animacionLogin"] . "'

	WHERE dtc_id=1");

} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}	

include("../compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="dev-datos-contacto.php";</script>';
exit();