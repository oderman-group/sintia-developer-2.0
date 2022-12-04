<?php
if($datosUsuarioActual['uss_bloqueado']==1)
{
	echo "Tu usuario est&aacute; bloqueado!<br>
	<a href='../controlador/salir.php'>Salir</a>";
	exit();		
}
?>