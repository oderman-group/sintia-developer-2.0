<?php include("../modelo/conexion.php");?>
<?php include("../../../config-general/config.php");?>
<?php

$datosCargaActual = mysql_fetch_array(mysql_query("SELECT * FROM academico_cargas WHERE car_id='".$_POST["carga"]."' AND car_activa=1",$conexion));
?>
<?php
if(trim($_POST["nota"])==""){
    echo "<span style='color:red; font-size:16px;'>Digite una nota correcta</span>";
	exit();
}
if($_POST["nota"]>$config[4]) $_POST["nota"] = $config[4]; if($_POST["nota"]<1) $_POST["nota"] = 1;




$consulta = mysql_query("SELECT * FROM academico_boletin WHERE bol_estudiante=".$_POST["codEst"]." AND bol_carga=".$_POST["carga"]." AND bol_periodo=".$_POST["per"],$conexion);
if(mysql_errno()!=0){echo mysql_error(); exit();}
$num = mysql_num_rows($consulta);
$rB = mysql_fetch_array($consulta);
//echo $num; exit();
if($num==0){
	mysql_query("DELETE FROM academico_boletin WHERE bol_id='".$rB[0]."'",$conexion);
	if(mysql_errno()!=0){echo mysql_error(); exit();}
	mysql_query("INSERT INTO academico_boletin(bol_carga, bol_estudiante, bol_periodo, bol_nota, bol_tipo, bol_observaciones)VALUES('".$_POST["carga"]."','".$_POST["codEst"]."','".$_POST["per"]."','".$_POST["nota"]."', 4, 'Colocada DEF. por docente.')",$conexion);
	if(mysql_errno()!=0){echo mysql_error(); exit();}
}else{
	mysql_query("UPDATE academico_boletin SET bol_nota_anterior=bol_nota, bol_nota='".$_POST["nota"]."', bol_observaciones='Colocada DEF. por docente.', bol_tipo=4, bol_actualizaciones=bol_actualizaciones+1, bol_ultima_actualizacion=now() WHERE bol_id=".$rB[0],$conexion);
	if(mysql_errno()!=0){echo mysql_error(); exit();}
}	
if(mysql_errno()!=0){echo "ERROR: ".mysql_errno()." - ".mysql_error();exit();}
else{
	
?>
    <div class="alert alert-success">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		<i class="icon-exclamation-sign"></i><strong>INFORMACI&Oacute;N:</strong> Los cambios se ha guardado correctamente!.
	</div>
<?php	
	exit();
}
?>