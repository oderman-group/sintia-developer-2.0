<?php
session_start();
include("../modelo/conexion.php");
include("../../config-general/config.php");
require_once(ROOT_PATH."/main-app/class/Utilidades.php");

$consultaDatosCargasActual=mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_cargas WHERE car_id='".$_POST["carga"]."' AND car_activa=1 AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
$datosCargaActual = mysqli_fetch_array($consultaDatosCargasActual, MYSQLI_BOTH);

if(trim($_POST["nota"])==""){
    echo "<span style='color:red; font-size:16px;'>Digite una nota correcta</span>";
	exit();
}
if($_POST["nota"]>$config[4]) $_POST["nota"] = $config[4]; if($_POST["nota"]<1) $_POST["nota"] = 1;

$consulta = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_boletin WHERE bol_estudiante='".$_POST["codEst"]."' AND bol_carga='".$_POST["carga"]."' AND bol_periodo='".$_POST["per"]."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");

$num = mysqli_num_rows($consulta);
$rB = mysqli_fetch_array($consulta, MYSQLI_BOTH);
// echo $num; exit();
if($num==0){
	if(!empty($rB['bol_id'])){
		mysqli_query($conexion, "DELETE FROM ".BD_ACADEMICA.".academico_boletin WHERE bol_id='".$rB['bol_id']."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
	}

	$codigoBOL=Utilidades::generateCode("BOL");
	mysqli_query($conexion, "INSERT INTO ".BD_ACADEMICA.".academico_boletin(bol_id, bol_carga, bol_estudiante, bol_periodo, bol_nota, bol_tipo, bol_observaciones, institucion, year)VALUES('".$codigoBOL."', '".$_POST["carga"]."','".$_POST["codEst"]."','".$_POST["per"]."','".$_POST["nota"]."', 4, 'Colocada DEF. por docente.', {$config['conf_id_institucion']}, {$_SESSION["bd"]})");
}else{
	mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_boletin SET bol_nota_anterior=bol_nota, bol_nota='".$_POST["nota"]."', bol_observaciones='Colocada DEF. por docente.', bol_tipo=4, bol_actualizaciones=bol_actualizaciones+1, bol_ultima_actualizacion=now() WHERE bol_id='".$rB['bol_id']."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
}
?>
    <div class="alert alert-success">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		<i class="icon-exclamation-sign"></i><strong>INFORMACI&Oacute;N:</strong> Los cambios se ha guardado correctamente!.
 	</div>
<?php	
	exit();
?>