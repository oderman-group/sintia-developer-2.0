<?php
if($periodoConsultaActual>=$datosCargaActual['car_periodo'])
{
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=219";</script>';
	exit();		
}
?>