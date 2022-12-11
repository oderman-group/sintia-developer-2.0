<?php
include("session.php");
include("../modelo/conexion.php");

	//SE CREA MATRICULA EN AÑO SIGUIENTE
	mysql_query("INSERT INTO ".$bdApasar.".academico_matriculas SELECT * FROM ".$bdActual.".academico_matriculas WHERE mat_id='".$_GET["matricula"]."' ",$conexion);
	if(mysql_errno()!=0){echo "7". mysql_error(); exit();}

	mysql_query("UPDATE ".$bdApasar.".academico_matriculas SET mat_estado_matricula=4, mat_grupo=1 WHERE mat_id='".$_GET["matricula"]."'",$conexion);
	if(mysql_errno()!=0){echo "10". mysql_error(); exit();}

	//CONSULTAMOS DATOS DEL ESTUDIANTE
	$consultaMatricula=mysql_query("SELECT * FROM ".$bdActual.".academico_matriculas WHERE mat_id='".$_GET["matricula"]."' ",$conexion);
	$datosMatricula = mysql_fetch_array($consultaMatricula);

	//SE CREA EL USUARIO DEL ESTUDIANTE
	mysql_query("INSERT INTO ".$bdApasar.".usuarios SELECT * FROM ".$bdActual.".usuarios WHERE uss_id='".$datosMatricula["mat_id_usuario"]."' ",$conexion);
	if(mysql_errno()!=0){echo "18".mysql_error(); exit();}

	//SE CREA EL USUARIO DEL ACUDIENTE
	mysql_query("INSERT INTO ".$bdApasar.".usuarios SELECT * FROM ".$bdActual.".usuarios WHERE uss_id='".$datosMatricula["mat_acudiente"]."' ",$conexion);
	if(mysql_errno()!=0){echo "22".mysql_error(); exit();}

	//SE CREA EL USUARIO DEL PADRE
	mysql_query("INSERT INTO ".$bdApasar.".usuarios SELECT * FROM ".$bdActual.".usuarios WHERE uss_id='".$datosMatricula["mat_padre"]."' ",$conexion);
	if(mysql_errno()!=0){echo "26".mysql_error(); exit();}

	//SE CREA EL USUARIO DE LA MADRE
	mysql_query("INSERT INTO ".$bdApasar.".usuarios SELECT * FROM ".$bdActual.".usuarios WHERE uss_id='".$datosMatricula["mat_madre"]."' ",$conexion);
	if(mysql_errno()!=0){echo "30".mysql_error(); exit();}

	//SE CREA RELACION ENTRE ACUDIENTE Y ESTUDIANTE
	mysql_query("INSERT INTO ".$bdApasar.".usuarios_por_estudiantes(upe_id_usuario, upe_id_estudiante)VALUES('".$datosMatricula["mat_acudiente"]."', '".$datosMatricula["mat_id_usuario"]."')",$conexion);
	if(mysql_errno()!=0){echo "34".mysql_error(); exit();}

	//SE ACTUALIZA EL ESTADO DEL ASPIRANTE
	mysql_query("UPDATE ".$baseDatosAdmisiones.".aspirantes SET asp_estado_solicitud=9 WHERE asp_id='".$datosMatricula["mat_solicitud_inscripcion"]."'",$conexion);
	if(mysql_errno()!=0){echo "38".mysql_error(); exit();}
	
	echo '<script type="text/javascript">window.location.href="inscripciones.php";</script>';
	exit();