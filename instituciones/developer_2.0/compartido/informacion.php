<?php
include("../modell/conexion.php");
$informacionInstitucion = mysql_query("SELECT * FROM general_informacion WHERE info_id=1",$conexion);
$infoI = mysql_fetch_array($informacionInstitucion);
?>
