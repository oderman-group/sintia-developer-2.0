<?php
function msj($idMensaje){
	switch($idMensaje){
		case 1:
		echo "Hay estudiantes que no tienen todas las notas registradas";
		break;
		
		case 2:
		echo "A&uacute;n no se ha registrado el 100% de las notas";
		break;
	}
}
?>