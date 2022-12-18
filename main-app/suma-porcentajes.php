<?php
$spcd = mysql_fetch_array(mysql_query("SELECT sum(act_valor) FROM academico_actividades WHERE act_id_carga='".$cargaSP."' AND act_estado=1 AND act_periodo='".$periodoSP."'",$conexion));
$spcr = mysql_fetch_array(mysql_query("SELECT sum(act_valor) FROM academico_actividades WHERE act_id_carga='".$cargaSP."' AND act_estado=1 AND act_periodo='".$periodoSP."' AND act_registrada=1",$conexion));
?>