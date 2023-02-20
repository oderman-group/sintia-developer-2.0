<?php include("session.php"); ?>
<?php include("../modelo/conexion.php"); ?>
<?php
if (trim($_POST["periodo"]) == "" or trim($_POST["perdida"]) == "" or trim($_POST["ganada"]) == "" or trim($_POST["estiloNotas"]) == "") {
	echo "<span style='font-family:Arial; color:red;'>Debe llenar todos los campos.</samp>";
	exit();
}
if(empty($_POST["desde"])) {$_POST["desde"] = 1;}
if(empty($_POST["hasta"])) {$_POST["hasta"] = 5;}
if(empty($_POST["notaMinima"])) {$_POST["notaMinima"] = 3;}
if(empty($_POST["periodoTrabajar"])) {$_POST["periodoTrabajar"] = 4;}

try {
	mysqli_query($conexion, "UPDATE ".$baseDatosServicios.".configuracion SET 
	conf_periodo='" . $_POST["periodo"] . "', 
	conf_nota_desde='" . $_POST["desde"] . "', 
	conf_nota_hasta='" . $_POST["hasta"] . "', 
	conf_nota_minima_aprobar='" . $_POST["notaMinima"] . "', 
	conf_color_perdida='" . $_POST["perdida"] . "', 
	conf_color_ganada='" . $_POST["ganada"] . "', 
	conf_periodos_maximos='" . $_POST["periodoTrabajar"] . "',  
	conf_notas_categoria='" . $_POST["estiloNotas"] . "', 
	conf_fecha_parcial='" . $_POST["fechapa"] . "', 
	conf_descripcion_parcial='" . $_POST["descrip"] . "',
	conf_ancho_imagen='" . $_POST["logoAncho"] . "',
	conf_alto_imagen='" . $_POST["logoAlto"] . "', 
	conf_mostrar_nombre='" . $_POST["mostrarNombre"] . "',
	conf_calificaciones_acudientes='" . $_POST["caliAcudientes"] . "',
	conf_mostrar_calificaciones_estudiantes='" . $_POST["caliEstudiantes"] . "'
	WHERE conf_id='".$config['conf_id']."'");

	echo '<script type="text/javascript">window.location.href="configuracion-sistema.php";</script>';
	exit();
} catch (Exception $e) {
	$lineaError   = __LINE__;
	include("../compartido/error-catch-to-report.php");
}	