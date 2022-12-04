<?php
$modulo = 4; ?>
<?php include("session.php"); ?>
<?php include("../modelo/conexion.php"); ?>
<?php 
include("../../../config-general/config.php");

include("../compartido/sintia-funciones.php");
?>

<?php
if (isset($_POST["id"])) {
	mysql_query("INSERT INTO seguridad_historial_acciones(hil_usuario, hil_url, hil_titulo, hil_fecha)VALUES('" . $_SESSION["id"] . "', '" . $_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING'] . "', 'Acciones POST - " . $_SERVER['HTTP_REFERER'] . "', now())", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
} elseif ($_GET["get"]) {
	mysql_query("INSERT INTO seguridad_historial_acciones(hil_usuario, hil_url, hil_titulo, hil_fecha)VALUES('" . $_SESSION["id"] . "', '" . $_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING'] . "', 'Acciones GET - " . $_SERVER['HTTP_REFERER'] . "', now())", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
} else {
	mysql_query("INSERT INTO seguridad_historial_acciones(hil_usuario, hil_url, hil_titulo, hil_fecha)VALUES('" . $_SESSION["id"] . "', '" . $_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING'] . "', 'Acciones DESCONOCIDA - " . $_SERVER['HTTP_REFERER'] . "', now())", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
}
?>
<?php
//GUARDAR MOVIMIENTO
if ($_POST["id"] == 8) {
	if (trim($_POST["fecha"]) == "" or trim($_POST["detalle"]) == "" or trim($_POST["valor"]) == "" or trim($_POST["tipo"]) == "" or trim($_POST["forma"]) == "") {
		echo "<span style='font-family:Arial; color:red;'>Debe llenar todos los campos.</samp>";
		exit();
	}
	if ($_POST["tipo"] == 1) {
		$consecutivoActual = mysql_fetch_array(mysql_query("SELECT * FROM finanzas_cuentas WHERE fcu_tipo=1 ORDER BY fcu_id DESC", $conexion));
		if ($consecutivoActual['fcu_consecutivo'] == "") {
			$consecutivo = $config['conf_inicio_recibos_ingreso'];
		} else {
			$consecutivo = $consecutivoActual['fcu_consecutivo'] + 1;
		}
	}
	if ($_POST["tipo"] == 2) {
		$consecutivoActual = mysql_fetch_array(mysql_query("SELECT * FROM finanzas_cuentas WHERE fcu_tipo=2 ORDER BY fcu_id DESC", $conexion));
		if ($consecutivoActual['fcu_consecutivo'] == "") {
			$consecutivo = $config['conf_inicio_recibos_egreso'];
		} else {
			$consecutivo = $consecutivoActual['fcu_consecutivo'] + 1;
		}
	}
	mysql_query("INSERT INTO finanzas_cuentas(fcu_fecha, fcu_detalle, fcu_valor, fcu_tipo, fcu_observaciones, fcu_usuario, fcu_anulado, fcu_forma_pago, fcu_cerrado, fcu_consecutivo)VALUES('" . $_POST["fecha"] . "','" . $_POST["detalle"] . "','" . $_POST["valor"] . "','" . $_POST["tipo"] . "','" . $_POST["obs"] . "','" . $_POST["usuario"] . "',0,'" . $_POST["forma"] . "',0,'" . $consecutivo . "')", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="movimientos.php";</script>';
	exit();
}
//GUARDAR REPORTE
if ($_POST["id"] == 9) {
	if (trim($_POST["fecha"]) == "" or trim($_POST["codigo"]) == "" or trim($_POST["falta"]) == "" or trim($_POST["tipo"]) == "") {
		echo "<span style='font-family:Arial; color:red;'>Debe llenar todos los campos.</samp>";
		exit();
	}
	$usuarioResponsable = mysql_fetch_array(mysql_query("SELECT * FROM usuarios_por_estudiantes WHERE upe_id_estudiante='" . $_POST["codigo"] . "'", $conexion));
	mysql_query("INSERT INTO general_alertas(alr_nombre, alr_descripcion, alr_tipo, alr_usuario, alr_fecha_envio, alr_vista, alr_categoria, alr_importancia)VALUES('Reporte disciplinario','El estudiante " . $_POST["codigo"] . " le han hecho un reporte disciplinario',2,'" . $usuarioResponsable[1] . "',now(),0,2,2)", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	mysql_query("INSERT INTO disciplina_reportes(dr_fecha, dr_estudiante, dr_falta, dr_tipo, dr_usuario)VALUES('" . $_POST["fecha"] . "','" . $_POST["codigo"] . "','" . $_POST["falta"] . "','" . $_POST["tipo"] . "','" . $_SESSION["id"] . "')", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="disciplina-listado-reportes.php";</script>';
	exit();
}
//GUARDAR CONFIGURACION DEL SISTEMA
if ($_POST["id"] == 10) {/*
	if (trim($_POST["agno"]) == "" or trim($_POST["periodo"]) == "" or trim($_POST["desde"]) == "" or trim($_POST["hasta"]) == "" or trim($_POST["notaMinima"]) == "" or trim($_POST["perdida"]) == "" or trim($_POST["ganada"]) == "" or trim($_POST["periodoTrabajar"]) == "" or trim($_POST["numIndicadores"]) == "" or trim($_POST["valorIndicadores"]) == "" or trim($_POST["estiloNotas"]) == "") {
		echo "<span style='font-family:Arial; color:red;'>Debe llenar todos los campos.</samp>";
		exit();
	}

	mysql_query("UPDATE configuracion SET conf_agno='" . $_POST["agno"] . "', conf_periodo='" . $_POST["periodo"] . "', conf_nota_desde='" . $_POST["desde"] . "', conf_nota_hasta='" . $_POST["hasta"] . "', conf_nota_minima_aprobar='" . $_POST["notaMinima"] . "', conf_color_perdida='" . $_POST["perdida"] . "', conf_color_ganada='" . $_POST["ganada"] . "', conf_periodos_maximos='" . $_POST["periodoTrabajar"] . "', conf_num_indicadores='" . $_POST["numIndicadores"] . "', conf_valor_indicadores='" . $_POST["valorIndicadores"] . "', conf_notas_categoria='" . $_POST["estiloNotas"] . "', conf_fecha_parcial='" . $_POST["fechapa"] . "', conf_descripcion_parcial='" . $_POST["descrip"] . "', conf_ancho_imagen='" . $_POST["logoAncho"] . "', conf_alto_imagen='" . $_POST["logoAlto"] . "', conf_mostrar_nombre='" . $_POST["mostrarNombre"] . "', conf_inicio_recibos_ingreso='" . $_POST["iri"] . "', conf_inicio_recibos_egreso='" . $_POST["ire"] . "' WHERE conf_id=1", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");

	if ($_POST["claveSeguridad"] == "Oderman2014$") {
		mysql_query("UPDATE configuracion SET conf_base_datos='" . $_POST["baseDatos"] . "', conf_servidor='" . $_POST["servidorConexion"] . "', conf_usuario='" . $_POST["usuarioConexion"] . "', conf_clave='" . $_POST["claveConexion"] . "', conf_id_institucion='" . $_POST["idColegio"] . "' WHERE conf_id=1", $conexion);
		if (mysql_errno() != 0) {
			echo mysql_error();
			exit();
		}
	}

	echo '<script type="text/javascript">window.location.href="config-sistema.php";</script>';
	exit();*/
}
//ACTUALIZAR MATRICULA
if ($_POST["id"] == 11) {/*

	$_POST["ciudadR"] = trim($_POST["ciudadR"]);

	//COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
	if(trim($_POST["tipoD"])=="" or trim($_POST["nDoc"])=="" or trim($_POST["genero"])=="" or trim($_POST["apellido1"])=="" or trim($_POST["apellido2"])=="" or trim($_POST["nombres"])=="" or trim($_POST["grado"])=="" or trim($_POST["grupo"])=="" or trim($_POST["tipoEst"])=="" or trim($_POST["matestM"])==""){
		echo "<span style='font-family:Arial; color:red;'>Debe llenar todos los campos del estudiante.</samp>";
		exit();
	}
	$res_consultaid_acu=mysql_fetch_array(mysql_query("SELECT MAX(uss_id)+1 as iduss FROM usuarios;",$conexion));

	//require_once("apis-sion-modify-student.php");

	//Actualiza el usuario
	if($_POST["va_matricula"]==""){$_POST["va_matricula"]=0;}


	mysql_query("UPDATE academico_matriculas SET 
	mat_tipo_documento='".$_POST["tipoD"]."', 
	mat_documento='".$_POST["nDoc"]."', 
	mat_religion='".$_POST["religion"]."', 
	mat_email='".strtolower($_POST["email"])."', 
	mat_direccion='".$_POST["direccion"]."', 
	mat_barrio='".$_POST["barrio"]."', 
	mat_telefono='".$_POST["telefono"]."', 
	mat_celular='".$_POST["celular"]."', 
	mat_estrato='".$_POST["estrato"]."', 
	mat_genero='".$_POST["genero"]."', 
	mat_fecha_nacimiento='".$_POST["fNac"]."', 
	mat_primer_apellido='".$_POST["apellido1"]."', 
	mat_segundo_apellido='".$_POST["apellido2"]."', 
	mat_nombres='".$_POST["nombres"]."', 
	mat_grado='".$_POST["grado"]."', 
	mat_grupo='".$_POST["grupo"]."', 
	mat_tipo='".$_POST["tipoEst"]."',
	mat_lugar_expedicion='".$_POST["lugarD"]."',
	mat_lugar_nacimiento='".$_POST["lNac"]."',
	mat_estado_matricula=".$_POST["matestM"].", 
	mat_matricula='".$_POST["matricula"]."', 
	mat_folio='".$_POST["folio"]."', 
	mat_codigo_tesoreria='".$_POST["codTesoreria"]."', 
	mat_valor_matricula='".$_POST["va_matricula"]."', 
	mat_inclusion='".$_POST["inclusion"]."', 
	mat_extranjero='".$_POST["extran"]."', 
	mat_fecha=now(), 
	mat_numero_matricula='".$_POST["NumMatricula"]."', 
	mat_iniciar_proceso='".$_POST["iniciarProceso"]."',
	mat_actualizar_datos='".$_POST["actualizarDatos"]."',
	mat_pago_matricula='".$_POST["pagoMatricula"]."',
	mat_contrato='".$_POST["contrato"]."',
	mat_pagare='".$_POST["pagare"]."',
	mat_compromiso_academico='".$_POST["compromisoA"]."',
	mat_compromiso_convivencia='".$_POST["compromisoC"]."',
	mat_manual='".$_POST["manual"]."',
	mat_mayores14='".$_POST["contrato14"]."',
	mat_compromiso_convivencia_opcion='".$_POST["compromisoOpcion"]."',
	mat_hoja_firma='".$_POST["firmaHoja"]."',
	mat_estado_agno='".$_POST["estadoAgno"]."',
	mat_tipo_sangre='".$_POST["tipoSangre"]."', 
	mat_eps='".$_POST["eps"]."', 
	mat_celular2='".$_POST["celular2"]."', 
	mat_ciudad_residencia='".$_POST["ciudadR"]."'

	WHERE mat_id=".$_POST["idE"].";",$conexion);
	if(mysql_errno()!=0){echo mysql_error(); exit();}
	mysql_query("UPDATE usuarios SET uss_usuario='".$_POST["nDoc"]."' WHERE uss_id='".$_POST["idU"]."'",$conexion);
	
	//ACTUALIZAR EL ACUDIENTE 1	
	if($_POST["documentoA"]!=""){


		$acudiente = mysql_fetch_array(mysql_query("SELECT * FROM usuarios WHERE uss_usuario='".$_POST["documentoA"]."'"));
		if(mysql_errno()!=0){echo mysql_error(); exit();} 

		mysql_query("UPDATE academico_matriculas SET mat_acudiente='".$acudiente['uss_id']."' WHERE mat_id='".$_POST["idE"]."'",$conexion);
		if(mysql_errno()!=0){echo mysql_error(); exit();}


		mysql_query("DELETE FROM usuarios_por_estudiantes WHERE upe_id_estudiante='".$_POST["idE"]."'",$conexion);
		if(mysql_errno()!=0){echo mysql_error(); exit();}

		mysql_query("INSERT INTO usuarios_por_estudiantes(upe_id_usuario, upe_id_estudiante)VALUES('".$acudiente['uss_id']."', '".$_POST["idE"]."')",$conexion);
		if(mysql_errno()!=0){echo mysql_error(); exit();}

		mysql_query("UPDATE usuarios SET uss_usuario='".$_POST["documentoA"]."', uss_nombre='".$_POST["nombreA"]."', uss_email='".$_POST["email"]."', uss_ocupacion='".$_POST["ocupacionA"]."', uss_genero='".$_POST["generoA"]."', uss_celular='".$_POST["celular"]."', uss_lugar_expedicion='".$_POST["lugardA"]."', uss_tipo_documento='".$_POST["tipoDAcudiente"]."', uss_direccion='".$_POST["direccion"]."' WHERE uss_id='".$_POST["idAcudiente"]."'",$conexion);
		if(mysql_errno()!=0){echo mysql_error(); exit();}


	}


	//ACTUALIZAR EL ACUDIENTE 2
	if($_POST["idAcudiente2"]!=""){
		mysql_query("UPDATE usuarios SET uss_usuario='".$_POST["documentoA2"]."', uss_nombre='".$_POST["nombreA2"]."', uss_email='".$_POST["email"]."', uss_ocupacion='".$_POST["ocupacionA2"]."', uss_genero='".$_POST["generoA2"]."', uss_celular='".$_POST["celular"]."', uss_lugar_expedicion='".$_POST["lugardA2"]."', uss_direccion='".$_POST["direccion"]."' WHERE uss_id='".$_POST["idAcudiente2"]."'",$conexion);
		if(mysql_errno()!=0){echo mysql_error(); exit();}
	}else{
		if($_POST["documentoA2"]!=""){
			mysql_query("INSERT INTO usuarios(uss_usuario, uss_clave, uss_tipo, uss_nombre, uss_estado, uss_ocupacion, uss_email, uss_permiso1, uss_genero, uss_celular, uss_foto, uss_portada, uss_idioma, uss_tema, uss_lugar_expedicion, uss_direccion)VALUES('".$_POST["documentoA2"]."','1234',3,'".$_POST["nombreA2"]."',0,'".$_POST["ocupacionA2"]."','".$_POST["email"]."',0,'".$_POST["generoA2"]."','".$_POST["celular"]."', 'default.png', 'default.png', 1, 'green', '".$_POST["lugardA2"]."', '".$_POST["direccion"]."')",$conexion);
			if(mysql_errno()!=0){echo mysql_error(); exit();}
			$idAcudiente2 = mysql_insert_id();
			
			mysql_query("UPDATE academico_matriculas SET mat_acudiente2='".$idAcudiente2."'WHERE mat_id=".$_POST["idE"].";",$conexion);
			if(mysql_errno()!=0){echo mysql_error(); exit();}
		}
	}
	
	echo '<script type="text/javascript">window.location.href="estudiantes-editar.php?id='.$_POST["idE"].'";</script>';
	exit();*/
}
//GUARDAR MATRICULA
if ($_POST["id"] == 12) {/*

	//COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
	if(trim($_POST["tipoD"])=="" or trim($_POST["nDoc"])=="" or trim($_POST["genero"])=="" or trim($_POST["fNac"])=="" or trim($_POST["apellido1"])=="" or trim($_POST["apellido2"])=="" or trim($_POST["nombres"])=="" or trim($_POST["grado"])=="" or trim($_POST["tipoEst"])=="" or trim($_POST["documentoA"])==""){
		echo "<span style='font-family:Arial; color:red;'>Debe llenar todos los campos.</samp>";
		exit();
	}
	//VALIDAMOS QUE EL ESTUDIANTE NO SE ENCUENTRE CREADO
	$valiEstudiante=mysql_query("SELECT * FROM academico_matriculas WHERE mat_documento='".$_POST["nDoc"]."'",$conexion);
	if(mysql_num_rows($valiEstudiante)>0){
		echo "<span style='font-family:Arial; color:red;'>Este estudiante ya se ecuentra creado.</samp>";
		exit();
	}

	$result_numMat=mysql_fetch_array(mysql_query("SELECT MAX(mat_matricula)+1 AS num_mat FROM academico_matriculas",$conexion));
	if($result_numMat[0]=="") $result_numMat[0]=$config[1]."1";
	//COMPRBAR QUE NO SE VAYA A REPETIR EL NUMERO DE LA MATRICULA
	$i=1;
	while($i==1){
		$matriculados = mysql_query("SELECT * FROM academico_matriculas WHERE mat_matricula='".$result_numMat[0]."'",$conexion);
		if($matriculadosNum = mysql_num_rows($matriculados)>0){
			$result_numMat[0]++;
		}else{
			$i=0;
		}
	}
	if($_POST["va_matricula"]=="") $_POST["va_matricula"]=0;
	if($_POST["grupo"]=="") $_POST["grupo"]=4;

	//require_once("apis-sion-create-student.php");

	//INSERTAMOS EL USUARIO ESTUDIANTE
	mysql_query("INSERT INTO usuarios(uss_usuario, uss_clave, uss_tipo, uss_nombre, uss_estado, uss_email, uss_fecha_nacimiento, uss_permiso1, uss_genero, uss_celular, uss_foto, uss_portada, uss_idioma, uss_tema, uss_tipo_documento, uss_lugar_expedicion, uss_direccion)VALUES('".	$_POST["nDoc"]."','1234',4,'".$_POST["nombres"]." ".$_POST["apellido1"]." ".$_POST["apellido2"]."',0,'".strtolower($_POST["email"])."','".$_POST["fNac"]."',0,'".$_POST["genero"]."','".$_POST["celular"]."', 'default.png', 'default.png', 1, 'green','".$_POST["tipoD"]."','".$_POST["lugarD"]."', '".$_POST["direccion"]."')",$conexion);
	$idEstudianteU = mysql_insert_id();
	if(mysql_errno()!=0){echo mysql_error()."Liena 9"; exit();}
	$acudienteConsulta = mysql_query("SELECT * FROM usuarios WHERE uss_usuario='".$_POST["documentoA"]."'",$conexion);
	$acudienteNum = mysql_num_rows($acudienteConsulta);
	$acudienteDatos = mysql_fetch_array($acudienteConsulta);
	//PREGUNTAMOS SI EL ACUDIENTE EXISTE
	if($acudienteNum>0){			 
		 $idAcudiente = $acudienteDatos[0];
		 mysql_query("INSERT INTO academico_matriculas(mat_matricula, mat_fecha, mat_tipo_documento, mat_documento, mat_religion, mat_email, mat_direccion, mat_barrio, mat_telefono, mat_celular, mat_estrato, mat_genero, mat_fecha_nacimiento, mat_primer_apellido, mat_segundo_apellido, mat_nombres, mat_grado, mat_grupo, mat_tipo, mat_lugar_nacimiento, mat_lugar_expedicion, mat_acudiente, mat_estado_matricula, mat_id_usuario, mat_folio, mat_codigo_tesoreria, mat_valor_matricula, mat_inclusion, mat_extranjero, mat_tipo_sangre, mat_eps, mat_celular2, mat_ciudad_residencia)VALUES(".$result_numMat[0].",now(),".$_POST["tipoD"].",".$_POST["nDoc"].",".$_POST["religion"].",'".strtolower($_POST["email"])."','".$_POST["direccion"]."','".$_POST["barrio"]."','".$_POST["telefono"]."','".$_POST["celular"]."',".$_POST["estrato"].",".$_POST["genero"].", '".$_POST["fNac"]."', '".$_POST["apellido1"]."', '".$_POST["apellido2"]."', '".$_POST["nombres"]."','".$_POST["grado"]."','".$_POST["grupo"]."','".$_POST["tipoEst"]."','".$_POST["lNacM"]."','".$_POST["lugarD"]."',".$idAcudiente.",4, '".$idEstudianteU."', '".$_POST["folio"]."', '".$_POST["codTesoreria"]."', '".$_POST["va_matricula"]."', '".$_POST["inclusion"]."', '".$_POST["extran"]."', '".$_POST["tipoSangre"]."', '".$_POST["eps"]."', '".$_POST["celular2"]."', '".$_POST["ciudadR"]."')",$conexion);
		 $idEstudiante = mysql_insert_id();
		 if(mysql_errno()!=0){echo mysql_error()."Linea 159"; exit();}	 		
	}
	//SI EL ACUDIENTE NO EXISTE, LO CREAMOS
	else{
		//COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
	if(trim($_POST["documentoA"])=="" or trim($_POST["nombresA"])=="" or trim($_POST["generoA"])==""){
		echo "<span style='font-family:Arial; color:red;'>El acudiente no existe, por tanto debe llenar todos los campos para registrarlo.</samp>";
		exit();
	}
		mysql_query("INSERT INTO usuarios(uss_usuario, uss_clave, uss_tipo, uss_nombre, uss_estado, uss_ocupacion, uss_email, uss_fecha_nacimiento, uss_permiso1, uss_genero, uss_celular, uss_foto, uss_portada, uss_idioma, uss_tema, uss_tipo_documento, uss_lugar_expedicion, uss_direccion)VALUES('".$_POST["documentoA"]."','1234',3,'".$_POST["nombresA"]." ".$_POST["apellido1A"]." ".$_POST["apellido2A"]."',0,'".$_POST["ocupacionA"]."','".$_POST["email"]."','".$_POST["fechaNA"]."',0,'".$_POST["generoA"]."','".$_POST["celular"]."', 'default.png', 'default.png', 1, 'green','".$_POST["tipoDAcudiente"]."','".$_POST["lugarDa"]."', '".$_POST["direccion"]."')",$conexion);
		if(mysql_errno()!=0){echo mysql_error()."Linea 167"; exit();}
		$idAcudiente = mysql_insert_id();
		
		mysql_query("INSERT INTO academico_matriculas(mat_matricula, mat_fecha, mat_tipo_documento, mat_documento, mat_religion, mat_email, mat_direccion, mat_barrio, mat_telefono, mat_celular, mat_estrato, mat_genero, mat_fecha_nacimiento, mat_primer_apellido, mat_segundo_apellido, mat_nombres, mat_grado, mat_grupo, mat_tipo, mat_lugar_nacimiento, mat_lugar_expedicion, mat_acudiente, mat_estado_matricula, mat_id_usuario, mat_folio, mat_codigo_tesoreria, mat_valor_matricula, mat_inclusion, mat_extranjero, mat_tipo_sangre, mat_eps, mat_celular2, mat_ciudad_residencia)VALUES(".$result_numMat[0].",now(),".$_POST["tipoD"].",".$_POST["nDoc"].",".$_POST["religion"].",'".strtolower($_POST["email"])."','".$_POST["direccion"]."','".$_POST["barrio"]."','".$_POST["telefono"]."','".$_POST["celular"]."',".$_POST["estrato"].",".$_POST["genero"].", '".$_POST["fNac"]."', '".$_POST["apellido1"]."', '".$_POST["apellido2"]."', '".$_POST["nombres"]."',".$_POST["grado"].",".$_POST["grupo"].",".$_POST["tipoEst"].",'".$_POST["lNacM"]."','".$_POST["lugarD"]."',".$idAcudiente.",4, '".$idEstudianteU."', '".$_POST["folio"]."', '".$_POST["codTesoreria"]."', '".$_POST["va_matricula"]."', '".$_POST["inclusion"]."', '".$_POST["extran"]."', '".$_POST["tipoSangre"]."', '".$_POST["eps"]."', '".$_POST["celular2"]."', '".$_POST["ciudadR"]."')",$conexion);
		 $idEstudiante = mysql_insert_id();
		 if(mysql_errno()!=0){echo mysql_error()."Linea 165 "; mysql_errno(); exit();}
	}
	mysql_query("INSERT INTO usuarios_por_estudiantes(upe_id_usuario, upe_id_estudiante)VALUES('".$idAcudiente."', '".$idEstudiante."')",$conexion);
	if(mysql_errno()!=0){echo mysql_error()."Linea 171"; exit();}

	echo '<script type="text/javascript">window.location.href="estudiantes.php";</script>';
	exit();*/
}
//ACTUALIZAR CONFIGURACION REPORTES
if ($_POST["id"] == 13) {
	//COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
	if (trim($_POST["color_borde"]) == "" or trim($_POST["color_encabezado"]) == "" or trim($_POST["tborde"]) == "") {
		echo "<span style='font-family:Arial; color:red;'>Debe llenar todos los campos.</samp>";
		exit();
	}
	//mysql_query("UPDATE configuracion SET conf_color_borde='#009900', conf_color_encabezado='#00FF99',conf_tam_borde=3 WHERE conf_id=2;",$conexion);
	mysql_query("UPDATE configuracion SET conf_color_borde='" . $_POST["color_borde"] . "', conf_color_encabezado='" . $_POST["color_encabezado"] . "',conf_tam_borde=" . $_POST["tborde"] . " WHERE conf_id=1;", $conexion);
	echo '<script type="text/javascript">window.location.href="config-reporte.php";</script>';
	exit();
}
//ACTUALIZAR LOS CURSOS
if ($_POST["id"] == 14) {/*
	//COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
	if (trim($_POST["nombreC"]) == "" or trim($_POST["formatoB"]) == "" or trim($_POST["valorM"]) == "" or trim($_POST["valorP"]) == "") {
		echo "<span style='font-family:Arial; color:red;'>Debe llenar todos los campos.</samp>";
		exit();
	}
	mysql_query("UPDATE academico_grados SET gra_codigo='" . $_POST["codigoC"] . "', gra_nombre='" . $_POST["nombreC"] . "', gra_formato_boletin=" . $_POST["formatoB"] . ", gra_valor_matricula=" . $_POST["valorM"] . ",gra_valor_pension=" . $_POST["valorP"] . ",gra_grado_siguiente=" . $_POST["graSiguiente"] . " WHERE gra_id=" . $_POST["id_curso"] . ";", $conexion);
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();*/
}
//INSERTAR CURSO
if ($_POST["id"] == 15) {/*
	//COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
	if (trim($_POST["codigoC"]) == "" or trim($_POST["nombreC"]) == "" or trim($_POST["valorM"]) == "" or trim($_POST["valorP"]) == "") {
		echo "<span style='font-family:Arial; color:red;'>Debe llenar todos los campos.</samp>";
		exit();
	}
	mysql_query("INSERT INTO academico_grados (gra_codigo,gra_nombre,gra_formato_boletin,gra_valor_matricula,gra_valor_pension,gra_estado,gra_grado_siguiente)VALUES(" . $_POST["codigoC"] . ",'" . $_POST["nombreC"] . "','1'," . $_POST["valorM"] . "," . $_POST["valorP"] . ",1,'" . $_POST["graSiguiente"] . "')", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="academico-cursos.php";</script>';
	exit();*/
}
//ACTUALIZAR USUARIO
if ($_POST["id"] == 16) {
	//COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
	/*
	if(trim($_POST["usuario"])=="" or trim($_POST["clave"])=="" or trim($_POST["tipoU"])=="" or trim($_POST["nombre"])=="" or trim(strtolower($_POST["email"]))=="" or trim($_POST["fechaN"])=="" or trim($_POST["genero"])=="" or trim($_POST["celular"])=="" or trim($_POST["ocupacion"])==""){
		echo "<span style='font-family:Arial; color:red;'>Debe llenar todos los campos.</samp>";
		exit();
	}
	
	$emailNum = mysql_num_rows(mysql_query("SELECT * FROM usuarios WHERE uss_email='".strtolower($_POST["email"])."' AND uss_id!='".$_GET["id"]."'",$conexion));
	if($emailNum>0){
		echo "<span style='font-family:Arial; color:red;'>Este Email ya est&aacute; asociado a ".$emailNum." usuario(s) m&aacute;s. Por favor escriba otro Email correctamente.</samp>";
		exit();
	}*/

	validarClave($_POST["clave"]);

	mysql_query("UPDATE usuarios SET 
	uss_usuario='" . $_POST["usuario"] . "', 
	uss_clave='" . $_POST["clave"] . "', 
	uss_tipo=" . $_POST["tipoUsuario"] . ", 
	uss_nombre='" . $_POST["nombre"] . "', 
	uss_email='" . strtolower($_POST["email"]) . "', 
	uss_genero='" . $_POST["genero"] . "',
	uss_celular='" . $_POST["celular"] . "',
	uss_ocupacion='" . $_POST["ocupacion"] . "',
	uss_lugar_expedicion='" . $_POST["lExpedicion"] . "',
	uss_direccion='" . $_POST["direccion"] . "',
	uss_telefono='" . $_POST["telefono"] . "',

	uss_ultima_actualizacion=now()
	WHERE uss_id='" . $_POST["idR"] . "'", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");

	if ($_POST["tipoUsuario"] == 4) {
		mysql_query("UPDATE academico_matriculas SET mat_email='" . strtolower($_POST["email"]) . "'", $conexion);
		if (mysql_errno() != 0) {
			echo mysql_error();
			exit();
		}
	}

	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}

//MODIFICAR CARGAS ACADEMICAS
if ($_POST["id"] == 17) {
	mysql_query("UPDATE academico_cargas SET car_docente='" . $_POST["docente"] . "', car_curso='" . $_POST["curso"] . "', car_grupo='" . $_POST["grupo"] . "', car_materia='" . $_POST["asignatura"] . "', car_periodo='" . $_POST["periodo"] . "', car_director_grupo='" . $_POST["dg"] . "', car_ih=" . $_POST["ih"] . ", car_activa='" . $_POST["estado"] . "', car_maximos_indicadores='" . $_POST["maxIndicadores"] . "', car_maximas_calificaciones='" . $_POST["maxActividades"] . "', car_configuracion='" . $_POST["valorActividades"] . "', car_valor_indicador='" . $_POST["valorIndicadores"] . "', car_permiso1='" . $_POST["permiso1"] . "', car_permiso2='" . $_POST["permiso2"] . "', car_indicador_automatico='" . $_POST["indicadorAutomatico"] . "' WHERE car_id=" . $_POST["idR"] . ";", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");

	mysql_query("DELETE FROM academico_intensidad_curso WHERE ipc_curso='" . $_POST["curso"] . "' AND ipc_materia='" . $_POST["asignatura"] . "'", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");

	mysql_query("INSERT INTO academico_intensidad_curso(ipc_curso, ipc_materia, ipc_intensidad)VALUES('" . $_POST["curso"] . "','" . $_POST["asignatura"] . "','" . $_POST["ih"] . "')", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");

	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//CREAR CARGAS ACADEMICAS
if ($_POST["id"] == 18) {
	//$ipc = mysql_fetch_array(mysql_query("SELECT * FROM academico_intensidad_curso WHERE ipc_curso=".$_POST["curso"]." AND ipc_materia=".$_POST["materia"]."",$conexion));

	$numero = (count($_POST["grupo"]));
	$contador = 0;
	while ($contador < $numero) {
		mysql_query("INSERT INTO academico_cargas (car_docente, car_curso, car_grupo, car_materia, car_periodo, car_activa, car_permiso1, car_director_grupo, car_ih, car_fecha_creada, car_responsable, car_maximos_indicadores, car_maximas_calificaciones, car_configuracion, car_valor_indicador, car_permiso2, car_indicador_automatico)VALUES('" . $_POST["docente"] . "', '" . $_POST["curso"] . "', '" . $_POST["grupo"][$contador] . "','" . $_POST["asignatura"] . "', '" . $_POST["periodo"] . "', 1, 1, '" . $_POST["dg"] . "', '" . $_POST["ih"] . "', now(), '" . $_SESSION["id"] . "', '" . $_POST["maxIndicadores"] . "', '" . $_POST["maxActividades"] . "', '" . $_POST["valorActividades"] . "', '" . $_POST["valorIndicadores"] . "', '" . $_POST["permiso2"] . "', '" . $_POST["indicadorAutomatico"] . "')", $conexion);
		$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
		$contador++;
	}

	echo '<script type="text/javascript">window.location.href="cargas.php";</script>';
	exit();
}
//CREAR INGRESOS-EGRESOS
if ($_POST["id"] == 19) {
	//COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
	if (trim($_POST["fechaIE"]) == "" or trim($_POST["detalleIE"]) == "" or trim($_POST["valorIE"]) == "" or trim($_POST["tipoIE"]) == "") {
		echo "<span style='font-family:Arial; color:red;'>Debe llenar todos los campos.</samp>";
		exit();
	}
	mysql_query("INSERT INTO finanzas_ingresos_egresos(ieg_fecha, ieg_detalle, ieg_valor, ieg_tipo, ieg_observaciones) VALUES('" . $_POST["fechaIE"] . "','" . $_POST["detalleIE"] . "'," . $_POST["valorIE"] . "," . $_POST["tipoIE"] . ",'" . $_POST["obsIE"] . "');", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//CREAR INGRESOS-EGRESOS
if ($_POST["id"] == 20) {
	//COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
	if (trim($_POST["fechaIE"]) == "" or trim($_POST["detalleIE"]) == "" or trim($_POST["valorIE"]) == "" or trim($_POST["tipoIE"]) == "") {
		echo "<span style='font-family:Arial; color:red;'>Debe llenar todos los campos.</samp>";
		exit();
	}
	mysql_query("UPDATE finanzas_ingresos_egresos SET ieg_fecha='" . $_POST["fechaIE"] . "', ieg_detalle='" . $_POST["detalleIE"] . "', ieg_valor=" . $_POST["valorIE"] . ", ieg_tipo=" . $_POST["tipoIE"] . ", ieg_observaciones='" . $_POST["obsIE"] . "' WHERE ieg_id=" . $_POST["id_IE"] . ";", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//ACTUALIZAR CONFIGURACION INSTITUCION
if ($_POST["id"] == 21) {
	mysql_query("UPDATE configuracion SET conf_periodo=" . $_POST["periodoActualC"] . ", conf_nota_desde=" . $_POST["notaMinC"] . ", conf_nota_hasta=" . $_POST["notaMaxC"] . ", conf_nota_minima_aprobar=" . $_POST["notaMinAprobarC"] . ", conf_color_perdida='" . $_POST["colorNotasPC"] . "', conf_color_ganada='" . $_POST["colorNotasGC"] . "',conf_pie='" . $_POST["configPie"] . "', conf_num_materias_perder_ano=" . $_POST["numMateriasMinRC"] . ", conf_ini_matrucula='" . $_POST["iniciomatC"] . "', conf_fin_matricul='" . $_POST["finmatC"] . "', conf_apertura_academica='" . $_POST["aperturaacademicaAC"] . "', conf_clausura_academica='" . $_POST["clausuraacademicaAC"] . "'
WHERE conf_id=" . $_POST["id_IC"] . ";", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="configuracion-institucion.php";</script>';
	exit();
}
//ACTUALIZAR INFORMACION INSTITUCION
if ($_POST["id"] == 22) {
	//COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
	if (trim($_POST["rectorI"]) == "" or trim($_POST["secretarioI"]) == "" or trim($_POST["nitI"]) == "" or trim($_POST["nomInstI"]) == "" or trim($_POST["direccionI"]) == "" or trim($_POST["telI"]) == "" or trim($_POST["calseI"]) == "" or trim($_POST["caracterI"]) == "" or trim($_POST["calendarioI"]) == "" or trim($_POST["jornadaI"]) == "" or trim($_POST["horarioI"]) == "" or trim($_POST["nivelesI"]) == "" or trim($_POST["modalidadI"]) == "" or trim($_POST["propietarioI"]) == "" or trim($_POST["coordinadorI"]) == "" or trim($_POST["tesoreroI"]) == "") {
		echo "<span style='font-family:Arial; color:red;'>Debe llenar todos los campos.</samp>";
		exit();
	}
	if ($_FILES['logo']['name'] != "") {
		$archivo = $_FILES['logo']['name'];
		$archivoAnt = $_POST["logoAnterior"];
		$destino = "../files/images/logo/";
		@unlink($destino . "/" . $archivoAnt);
		move_uploaded_file($_FILES['logo']['tmp_name'], $destino . "/" . $archivo);
	} else {
		$archivo = $_POST["logoAnterior"];
	}
	mysql_query("UPDATE general_informacion SET info_rector='" . $_POST["rectorI"] . "', info_secretaria_academica='" . $_POST["secretarioI"] . "', info_logo='" . $archivo . "', info_nit='" . $_POST["nitI"] . "', info_nombre='" . $_POST["nomInstI"] . "', info_direccion='" . $_POST["direccionI"] . "', info_telefono='" . $_POST["telI"] . "', info_clase='" . $_POST["calseI"] . "', info_caracter='" . $_POST["caracterI"] . "',info_calendario='" . $_POST["calendarioI"] . "', info_jornada='" . $_POST["jornadaI"] . "', info_horario='" . $_POST["horarioI"] . "', info_niveles='" . $_POST["nivelesI"] . "', info_modalidad='" . $_POST["modalidadI"] . "', info_propietario='" . $_POST["propietarioI"] . "', info_coordinador_academico='" . $_POST["coordinadorI"] . "', info_tesorero='" . $_POST["tesoreroI"] . "'
WHERE info_id=" . $_POST["idCI"] . ";", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//GUARDAR MATRICULA CONDICIONAL
if ($_POST["id"] == 23) {
	//COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
	if (trim($_POST["fecha"]) == "" or trim($_POST["codigo"]) == "" or trim($_POST["obsMC"]) == "") {
		echo "<span style='font-family:Arial; color:red;'>Debe llenar todos los campos.</samp>";
		exit();
	}
	mysql_query("INSERT INTO disciplina_matricula_condicional(cond_fecha, cond_estudiante, cond_observacion, cond_usuario)VALUES('" . $_POST["fecha"] . "','" . $_POST["codigo"] . "','" . $_POST["obsMC"] . "','" . $_SESSION["id"] . "')", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="disiplina-matricula-condicional-lista.php";</script>';
	exit();
}
//CREAR USUARIO
if ($_POST["id"] == 24) {
	$consultaUsuarioA = mysql_query("SELECT * FROM usuarios WHERE uss_usuario='" . $_POST["usuario"] . "'", $conexion);
	$numUsuarioA = mysql_num_rows($consultaUsuarioA);
	$datosUsuarioA = mysql_fetch_array($consultaUsuarioA);
	if ($numUsuarioA > 0) {
		echo "<span style='font-family:Arial; color:red;'>Este nombre de usuario(<b>" . $_POST["usuario"] . "</b>) ya existe para otra persona. Cambie el nombre de usuario por favor.</samp>";
		exit();
	}
	/*
		if(trim($_POST["usuario"])=="" or trim($_POST["clave"])=="" or trim($_POST["tipoU"])=="" or trim($_POST["nombre"])=="" or trim(strtolower($_POST["email"]))=="" or trim($_POST["fechaN"])=="" or trim($_POST["genero"])==""){
			echo "<span style='font-family:Arial; color:red;'>Debe llenar todos los campos.</samp>";
			exit();
		}
		$emailNum = mysql_num_rows(mysql_query("SELECT * FROM usuarios WHERE uss_email='".strtolower($_POST["email"])."'",$conexion));
		if($emailNum>0){
			echo "<span style='font-family:Arial; color:red;'>Este Email ya est&aacute; asociado a ".$emailNum." usuario(s) m&aacute;s. Por favor escriba otro Email correctamente.</samp>";
			exit();
		}*/
	mysql_query("INSERT INTO usuarios (uss_usuario, uss_clave, uss_tipo, uss_nombre, uss_estado, uss_email, uss_celular, uss_genero, uss_foto, uss_portada, uss_idioma, uss_tema, uss_permiso1, uss_bloqueado, uss_fecha_registro, uss_responsable_registro, uss_ocupacion)VALUES(
		'" . $_POST["usuario"] . "',
		'" . $_POST["clave"] . "',
		" . $_POST["tipoUsuario"] . ",
		'" . $_POST["nombre"] . "',
		0,
		'" . strtolower($_POST["email"]) . "',
		'" . $_POST["celular"] . "',
		" . $_POST["genero"] . ",
		'default.png',
		'default.png',
		1, 
		'green', 
		1,
		0,
		now(),
		'" . $_SESSION["id"] . "', 
		'" . $_POST["ocupacion"] . "'
		)", $conexion);
	$idRegistro = mysql_insert_id();
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="usuarios-editar.php?id=' . $idRegistro . '";</script>';
	exit();
}
//CREAR HORARIO
if ($_POST["id"] == 25) {/*
	//COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
	if (trim($_POST["idH"]) == "" or trim($_POST["inicioH"]) == "" or trim($_POST["finH"]) == "") {
		echo "<span style='font-family:Arial; color:red;'>Debe llenar todos los campos.</samp>";
		exit();
	}
	$numero = (count($_POST["diaH"]));
	$contador = 0;
	while ($contador < $numero) {
		mysql_query("INSERT INTO academico_horarios(hor_id_carga, hor_dia, hor_desde, hor_hasta)VALUES(" . $_POST["idH"] . ",'" . $_POST["diaH"][$contador] . "','" . $_POST["inicioH"] . "','" . $_POST["finH"] . "');", $conexion);
		if (mysql_errno() != 0) {
			echo mysql_error();
			exit();
		}
		$contador++;
	}
	echo '<script type="text/javascript">window.location.href="cargas-horarios.php?id=' . $_POST["idH"] . '";</script>';
	exit();*/
}
//MODIFICAR HORARIO
if ($_POST["id"] == 26) {/*
	//COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
	if (trim($_POST["idH"]) == "" or trim($_POST["inicioH"]) == "" or trim($_POST["finH"]) == "") {
		echo "<span style='font-family:Arial; color:red;'>Debe llenar todos los campos.</samp>";
		exit();
	}
	mysql_query("UPDATE academico_horarios SET hor_dia=" . $_POST["diaH"] . ", hor_desde='" . $_POST["inicioH"] . "', hor_hasta='" . $_POST["finH"] . "' WHERE hor_id=" . $_POST["idH"] . ";", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="cargas-horarios.php?id=' . $_POST["idC"] . '";</script>';
	exit();*/
}
//MODIFICAR REPORTE
if ($_POST["id"] == 27) {
	//COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
	if (trim($_POST["fecha"]) == "" or trim($_POST["falta"]) == "" or trim($_POST["tipo"]) == "") {
		echo "<span style='font-family:Arial; color:red;'>Debe llenar todos los campos.</samp>";
		exit();
	}
	mysql_query("UPDATE disciplina_reportes SET dr_fecha='" . $_POST["fecha"] . "', dr_falta='" . $_POST["falta"] . "', dr_tipo=" . $_POST["tipo"] . " WHERE dr_id=" . $_POST["idR"] . "", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//CREAR AREAS
if ($_POST["id"] == 28) {
	//COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
	if (trim($_POST["nombreA"]) == "" or trim($_POST["posicionA"]) == "") {
		echo "<span style='font-family:Arial; color:red;'>Debe llenar todos los campos.</samp>";
		exit();
	}
	mysql_query("INSERT INTO academico_areas (ar_nombre,ar_posicion)VALUES('" . $_POST["nombreA"] . "'," . $_POST["posicionA"] . ");", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//MODIFICAR AREAS
if ($_POST["id"] == 29) {
	//COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
	if (trim($_POST["nombreA"]) == "" or trim($_POST["posicionA"]) == "") {
		echo "<span style='font-family:Arial; color:red;'>Debe llenar todos los campos.</samp>";
		exit();
	}
	mysql_query("UPDATE academico_areas SET ar_nombre='" . $_POST["nombreA"] . "', ar_posicion=" . $_POST["posicionA"] . " WHERE ar_id=" . $_POST["idA"] . ";", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//CREAR MATERIAS
if ($_POST["id"] == 30) {
	//COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
	if (trim($_POST["codigoM"]) == "" or trim($_POST["nombreM"]) == "" or trim($_POST["siglasM"]) == "" or trim($_POST["areaM"]) == "") {
		echo "<span style='font-family:Arial; color:red;'>Debe llenar todos los campos.</samp>";
		exit();
	}
	mysql_query("INSERT INTO academico_materias(mat_codigo, mat_nombre, mat_siglas, mat_area, mat_oficial) VALUES ('" . $_POST["codigoM"] . "','" . $_POST["nombreM"] . "','" . $_POST["siglasM"] . "','" . $_POST["areaM"] . "',1);", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//MODIFICAR MATERIAS
if ($_POST["id"] == 31) {
	//COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
	/*
		if(trim($_POST["codigoM"])=="" or trim($_POST["nombreM"])=="" or trim($_POST["siglasM"])=="" or trim($_POST["areaM"])=="" or trim($_POST["oficial"])==""){
			echo "<span style='font-family:Arial; color:red;'>Debe llenar todos los campos.</samp>";
			exit();
		}
		*/
	mysql_query("UPDATE academico_materias SET mat_codigo='" . $_POST["codigoM"] . "', mat_nombre='" . $_POST["nombreM"] . "', mat_siglas='" . $_POST["siglasM"] . "', mat_area=" . $_POST["areaM"] . ", mat_oficial=1 WHERE mat_id=" . $_POST["idM"] . ";", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//MODIFICAR INDICADORES OBLIGATORIOS
if ($_POST["id"] == 32) {/*
	//COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
	if (trim($_POST["nombre"]) == "" or trim($_POST["valor"]) == "") {
		echo "<span style='font-family:Arial; color:red;'>Debe llenar todos los campos.</samp>";
		exit();
	}
	$ind = mysql_fetch_array(mysql_query("SELECT sum(ind_valor)+" . $_POST["valor"] . " FROM academico_indicadores where ind_obligatorio=1 AND ind_id!='" . $_POST["idI"] . "'", $conexion));
	if ($ind[0] > 100) {
		echo "<span style='font-family:Arial; color:red;'>Los valores de los indicadores no deben superar el 100%.</samp>";
		exit();
	}
	mysql_query("UPDATE academico_indicadores SET ind_nombre='" . $_POST["nombre"] . "', ind_valor='" . $_POST["valor"] . "' WHERE ind_id='" . $_POST["idI"] . "'", $conexion);
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();*/
}
//CREAR INDICADORES OBLIGATORIOS
if ($_POST["id"] == 33) {/*
	//COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
	if (trim($_POST["nombre"]) == "" or trim($_POST["valor"]) == "") {
		echo "<span style='font-family:Arial; color:red;'>Debe llenar todos los campos.</samp>";
		exit();
	}
	$ind = mysql_fetch_array(mysql_query("SELECT sum(ind_valor)+" . $_POST["valor"] . " FROM academico_indicadores where ind_obligatorio=1", $conexion));
	if ($ind[0] > 100) {
		echo "<span style='font-family:Arial; color:red;'>Los valores de los indicadores no deben superar el 100%.</samp>";
		exit();
	}
	mysql_query("INSERT INTO academico_indicadores(ind_nombre, ind_valor, ind_obligatorio)VALUES('" . $_POST["nombre"] . "','" . $_POST["valor"] . "',1)", $conexion);
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();*/
}
//GUARDAR AUTOCONOCIMIENTO
if ($_POST["id"] == 34) {
	//Me gusta
	$preferencia = $_POST["gusto"];
	$longitud = count($preferencia);
	for ($i = 0; $i < $longitud; $i++) {
		$nPref = mysql_num_rows(mysql_query("SELECT * FROM social_preferencias_usuarios WHERE preu_usuario='" . $_SESSION["id"] . "' AND preu_gusto=1 AND preu_preferencia='" . $preferencia[$i] . "'", $conexion));
		if ($nPref == 0) {
			mysql_query("INSERT INTO social_preferencias_usuarios(preu_usuario, preu_preferencia, preu_gusto)VALUES('" . $_SESSION["id"] . "','" . $preferencia[$i] . "',1)", $conexion);
			if (mysql_errno() != 0) {
				echo mysql_error();
				exit();
			}
		}
	}
	if (trim($_POST["gustoAdicional"]) != "") {
		$cons = mysql_query("SELECT lower(prel_nombre) FROM social_preferencias_lista WHERE lower(prel_nombre)='" . strtolower($_POST["gustoAdicional"]) . "' LIMIT 0,1", $conexion);
		if (mysql_errno() != 0) {
			echo mysql_error();
			exit();
		}
		$numGustoAdicional = mysql_num_rows($cons);
		$dat = mysql_fetch_array($cons);
		if ($numGustoAdicional > 0) {
			similar_text($dat[0], strtolower($_POST["gustoAdicional"]), $parecido);
			if ($parecido < 80) {
				$permiso = 1;
			} else {
				$permiso = 0;
			}
		} else {
			$permiso = 1;
		}
		if ($permiso == 1) {
			mysql_query("INSERT INTO social_preferencias_lista(prel_nombre, prel_guardado, prel_fecha)VALUES('" . $_POST["gustoAdicional"] . "',2,now())", $conexion);
			$idInsercion = mysql_insert_id();
			if (mysql_errno() != 0) {
				echo mysql_error();
				exit();
			}
			mysql_query("INSERT INTO social_preferencias_usuarios(preu_usuario, preu_preferencia, preu_gusto)VALUES('" . $_SESSION["id"] . "','" . $idInsercion . "',1)", $conexion);
			if (mysql_errno() != 0) {
				echo mysql_error();
				exit();
			}
		}
	}

	// No me gusta
	$preferencia = $_POST["nogusto"];
	$longitud = count($preferencia);
	for ($i = 0; $i < $longitud; $i++) {
		$nPref = mysql_num_rows(mysql_query("SELECT * FROM social_preferencias_usuarios WHERE preu_usuario='" . $_SESSION["id"] . "' AND preu_no_gusto=1 AND preu_preferencia='" . $preferencia[$i] . "'", $conexion));
		if ($nPref == 0) {
			mysql_query("INSERT INTO social_preferencias_usuarios(preu_usuario, preu_preferencia, preu_no_gusto)VALUES('" . $_SESSION["id"] . "','" . $preferencia[$i] . "',1)", $conexion);
			if (mysql_errno() != 0) {
				echo mysql_error();
				exit();
			}
		}
	}

	// Conocimiento
	$preferencia = $_POST["conocimiento"];
	$longitud = count($preferencia);
	for ($i = 0; $i < $longitud; $i++) {
		$nPref = mysql_num_rows(mysql_query("SELECT * FROM social_preferencias_usuarios WHERE preu_usuario='" . $_SESSION["id"] . "' AND preu_conocimiento=1 AND preu_preferencia='" . $preferencia[$i] . "'", $conexion));
		if ($nPref == 0) {
			mysql_query("INSERT INTO social_preferencias_usuarios(preu_usuario, preu_preferencia, preu_conocimiento)VALUES('" . $_SESSION["id"] . "','" . $preferencia[$i] . "',1)", $conexion);
			if (mysql_errno() != 0) {
				echo mysql_error();
				exit();
			}
		}
	}

	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//CREAR CATEGORIAS NOTAS
if ($_POST["id"] == 35) {/*
	//COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
	if (trim($_POST["nombreCN"]) == "" or trim($_POST["ndesdeCN"]) == "" or trim($_POST["nhastaCN"]) == "" or trim($_POST["idCN"]) == "") {
		echo "<span style='font-family:Arial; color:red;'>Debe llenar todos los campos.<br>
			<a href='javascript:history.go(-1)'>[Volver al formulario]</a></samp>";
		exit();
	}
	mysql_query("INSERT INTO academico_notas_tipos (notip_nombre, notip_desde, notip_hasta,notip_categoria)VALUES('" . $_POST["nombreCN"] . "'," . $_POST["ndesdeCN"] . "," . $_POST["nhastaCN"] . "," . $_POST["idCN"] . ");", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="academico-categoria-notas-especifica.php?id=' . $_POST["idCN"] . '";</script>';
	exit();*/
}
//EDITAR CATEGORIAS NOTAS
if ($_POST["id"] == 36) {/*
	//COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
	if (trim($_POST["nombreCN"]) == "" or trim($_POST["ndesdeCN"]) == "" or trim($_POST["nhastaCN"]) == "" or trim($_POST["idCN"]) == "") {
		echo "<span style='font-family:Arial; color:red;'>Debe llenar todos los campos.<br>
			<a href='javascript:history.go(-1)'>[Volver al formulario]</a></samp>";
		exit();
	}
	mysql_query("UPDATE academico_notas_tipos SET notip_nombre='" . $_POST["nombreCN"] . "', notip_desde=" . $_POST["ndesdeCN"] . ", notip_hasta=" . $_POST["nhastaCN"] . " WHERE notip_id=" . $_POST["idN"] . ";", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="academico-categoria-notas-especifica.php?id=' . $_POST["idCN"] . '";</script>';
	exit();*/
}
//ASIGNAR EVALUACIONES
if ($_POST["id"] == 37) {
	if (trim($_POST["eva"]) == "" or trim($_POST["curso"]) == "" or trim($_POST["grupo"]) == "") {
		echo "<span style='font-family:Arial; color:red;'>Debe llenar todos los campos.<br>
			<a href='javascript:history.go(-1)'>[Volver al formulario]</a></samp>";
		exit();
	}
	$numero = (count($_POST["usuario"]));
	$contador = 0;
	while ($contador < $numero) {
		mysql_query("INSERT INTO general_evaluacion_asignar(epag_id_evaluacion, epag_curso, epag_grupo, epag_usuario)VALUES('" . $_POST["eva"] . "','" . $_POST["curso"] . "','" . $_POST["grupo"] . "','" . $_POST["usuario"][$contador] . "')", $conexion);
		if (mysql_errno() != 0) {
			echo mysql_error();
			exit();
		}
		$contador++;
	}
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//GUARDAR EVALUACIONES GENERALES
if ($_POST["id"] == 38) {
	if (trim($_POST["titulo"]) == "" or trim($_POST["contenido"]) == "") {
		echo "<span style='font-family:Arial; color:red;'>Debe llenar todos los campos.<br>
			<a href='javascript:history.go(-1)'>[Volver al formulario]</a></samp>";
		exit();
	}
	mysql_query("INSERT INTO general_evaluaciones(evag_nombre, evag_descripcion, evag_fecha, evag_creada)VALUES('" . $_POST["titulo"] . "','" . $_POST["contenido"] . "',now(),'" . $_SESSION["id"] . "')", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//ACTUALIZAR EVALUACIONES GENERALES
if ($_POST["id"] == 39) {
	if (trim($_POST["titulo"]) == "" or trim($_POST["contenido"]) == "") {
		echo "<span style='font-family:Arial; color:red;'>Debe llenar todos los campos.<br>
			<a href='javascript:history.go(-1)'>[Volver al formulario]</a></samp>";
		exit();
	}
	mysql_query("UPDATE general_evaluaciones SET evag_nombre='" . $_POST["titulo"] . "', evag_descripcion='" . $_POST["contenido"] . "', evag_editada='" . $_SESSION["id"] . "' WHERE evag_id='" . $_POST["idN"] . "'", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="general-evaluacion.php#N' . $_POST["idN"] . '";</script>';
	exit();
}
//GUARDAR PREGUNTAS GENERALES
if ($_POST["id"] == 40) {
	if (trim($_POST["contenido"]) == "") {
		echo "<span style='font-family:Arial; color:red;'>Debe llenar todos los campos.<br>
			<a href='javascript:history.go(-1)'>[Volver al formulario]</a></samp>";
		exit();
	}
	mysql_query("INSERT INTO general_preguntas(pregg_descripcion, pregg_id_evaluacion)VALUES('" . $_POST["contenido"] . "','" . $_POST["eva"] . "')", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="general-preguntas.php?eva=' . $_POST["eva"] . '";</script>';
	exit();
}
//ACTUALIZAR PREGUNTAS GENERALES
if ($_POST["id"] == 41) {
	if (trim($_POST["contenido"]) == "") {
		echo "<span style='font-family:Arial; color:red;'>Debe llenar todos los campos.</samp>";
		exit();
	}
	mysql_query("UPDATE general_preguntas SET pregg_descripcion='" . $_POST["contenido"] . "' WHERE pregg_id='" . $_POST["idN"] . "'", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="general-preguntas.php?eva=' . $_POST["eva"] . '";</script>';
	exit();
}
//GUARDAR RESPUESTAS GENERALES
if ($_POST["id"] == 42) {
	if (trim($_POST["respuesta"]) == "") {
		echo "<span style='font-family:Arial; color:red;'>Debe llenar todos los campos.</samp>";
		exit();
	}
	mysql_query("INSERT INTO general_respuestas(resg_descripcion, resg_id_pregunta)VALUES('" . $_POST["respuesta"] . "','" . $_POST["idPregunta"] . "')", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="general-preguntas.php?eva=' . $_POST["eva"] . '";</script>';
	exit();
}
//ASIGNAR ACUDIDOS A LOS ACUDIENTES
if ($_POST["id"] == 43) {
	$numero = (count($_POST["acudidos"]));
	$contador = 0;
	while ($contador < $numero) {
		mysql_query("DELETE FROM usuarios_por_estudiantes WHERE upe_id_usuario='" . $_POST["acudiente"] . "' AND upe_id_estudiante='" . $_POST["acudidos"][$contador] . "'", $conexion);
		mysql_query("INSERT INTO usuarios_por_estudiantes(upe_id_usuario, upe_id_estudiante)VALUES('" . $_POST["acudiente"] . "','" . $_POST["acudidos"][$contador] . "')", $conexion);
		mysql_query("UPDATE academico_matriculas SET mat_acudiente='" . $_POST["acudiente"] . "' WHERE mat_id='" . $_POST["acudidos"][$contador] . "'", $conexion);
		if (mysql_errno() != 0) {
			echo mysql_error();
			exit();
		}
		$contador++;
	}
	echo '<script type="text/javascript">window.location.href="usuarios-acudidos.php?id=' . $_POST["acudiente"] . '";</script>';
	exit();
}
//CREAR USUARIO AUTORIZADO
if ($_POST["id"] == 44) {
	//IMAGENES
	$archivo = $_FILES['imagen']['tmp_name'];
	$nombre = $_FILES['imagen']['name'];
	$destino = "../files/fotos/";
	move_uploaded_file($archivo, $destino . "/" . $nombre);

	mysql_query("INSERT INTO autorizados (aut_documento, aut_nombre, aut_foto, aut_estudiante, aut_estado, aut_parentezco)VALUES('" . $_POST["documento"] . "','" . $_POST["nombre"] . "','" . $nombre . "','" . $_POST["estudiante"] . "','" . $_POST["estado"] . "','" . $_POST["parentezco"] . "')", $conexion);
	$idIte = mysql_insert_id();
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");

	echo '<script type="text/javascript">window.location.href="usuarios-autorizados.php"</script>';
	exit();
}
//EDITAR USUARIO AUTORIZADO
if ($_POST["id"] == 45) {
	$conextra = "";
	//IMAGENES
	$archivo = $_FILES['imagen']['tmp_name'];
	$nombre = $_FILES['imagen']['name'];
	$destino = "../files/fotos/";
	move_uploaded_file($archivo, $destino . "/" . $nombre);

	if ($nombre != "") {
		$refoto = mysql_fetch_array(mysql_query("SELECT aut_foto FROM autorizados WHERE aut_id='" . $_POST["idaut"] . "'", $conexion));
		//@unlink("../../files/fotos/".$refoto["aut_foto"]."");
		$conextra = ", aut_foto='" . $nombre . "'";
	}
	mysql_query("UPDATE autorizados SET aut_documento='" . $_POST["documento"] . "', aut_nombre='" . $_POST["nombre"] . "' " . $conextra . ", aut_estudiante='" . $_POST["estudiante"] . "', aut_estado='" . $_POST["estado"] . "', aut_parentezco='" . $_POST["parentezco"] . "' WHERE aut_id='" . $_POST["idaut"] . "'", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="usuarios-autorizados.php"</script>';
	exit();
}
//CREAR OPCION GENERALS
if ($_POST["id"] == 46) {

	mysql_query("INSERT INTO opciones_generales (ogen_nombre, ogen_grupo)VALUES('" . $_POST["nombre"] . "','" . $_POST["grupo"] . "')", $conexion);
	$idIte = mysql_insert_id();
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");

	echo '<script type="text/javascript">window.location.href="opciones-generales.php"</script>';
	exit();
}
//EDITAR OPCION GENERALS
if ($_POST["id"] == 47) {
	mysql_query("UPDATE opciones_generales SET ogen_nombre='" . $_POST["nombre"] . "', ogen_grupo='" . $_POST["grupo"] . "' WHERE ogen_id='" . $_POST["idogen"] . "'", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="opciones-generales.php"</script>';
	exit();
}
//CREAR OBSERVACIÓN
if ($_POST["id"] == 48) {
	mysql_query("INSERT INTO observacion_docente (obs_fecha, obs_docente, obs_director, obs_descripcion)VALUES(now(),'" . $_POST["idDoc"] . "','" . $_SESSION["id"] . "','" . $_POST["descripcion"] . "')", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="observacion-docente.php?idDoc=' . $_POST["idDoc"] . '"</script>';
	exit();
}
//CREAR AUSENCIA
if ($_POST["id"] == 49) {
	mysql_query("INSERT INTO ausencia_docente (aus_fecha, aus_docente, aus_director, aus_fech_ini, aus_fech_fin, aus_motivo, aus_observacion)VALUES(now(),'" . $_POST["idDoc"] . "','" . $_SESSION["id"] . "','" . $_POST["fIni"] . "','" . $_POST["fFin"] . "','" . $_POST["motivo"] . "','" . $_POST["observacion"] . "')", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="ausencia-docente.php?idDoc=' . $_POST["idDoc"] . '"</script>';
	exit();
}
//GENERAR COBRO MASIVO 
if ($_POST["id"] == 50) {
	if (trim($_POST["grado"]) == "" or trim($_POST["fecha"]) == "" or trim($_POST["detalle"]) == "") {
		echo "<span style='font-family:Arial; color:red;'>Debe llenar todos los campos.<br>
			<a href='javascript:history.go(-1)'>[Volver al formulario]</a></samp>";
		exit();
	}
	$cValor = mysql_fetch_array(mysql_query("SELECT * FROM finanzas_cobros_masivos WHERE mas_id='" . $_POST["detalle"] . "'", $conexion));
	$valor = $cValor[2];
	$detalle = $cValor[1];
	$consulta = mysql_query("SELECT * FROM academico_matriculas WHERE mat_grado='" . $_POST["grado"] . "'", $conexion);
	while ($datosE = mysql_fetch_array($consulta)) {
		mysql_query("INSERT INTO finanzas_cuentas(fcu_fecha, fcu_detalle, fcu_valor, fcu_tipo, fcu_observaciones, fcu_usuario, fcu_anulado, fcu_forma_pago, fcu_cerrado)VALUES('" . $_POST["fecha"] . "','" . $detalle . "','" . $valor . "','" . $_POST["tipo"] . "','" . $_POST["obs"] . "','" . $datosE["mat_id_usuario"] . "',0,5,0)", $conexion);
		if (mysql_errno() != 0) {
			echo mysql_error();
			exit();
		}
	}
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//TRASFERIR CARGAS
if ($_POST["id"] == 51) {/*
	mysql_query("UPDATE academico_cargas SET car_docente='" . $_POST["para"] . "' WHERE car_docente='" . $_POST["de"] . "'", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");

	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();*/
}
//CREAR COBROS MASIVOS
if ($_POST["id"] == 52) {
	mysql_query("INSERT INTO finanzas_cobros_masivos (mas_nombre, mas_valor)VALUES('" . $_POST["nombre"] . "','" . $_POST["costo"] . "')", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '"</script>';
	exit();
}
//EDITAR COBROS MASIVOS
if ($_POST["id"] == 53) {
	mysql_query("UPDATE finanzas_cobros_masivos SET mas_nombre='" . $_POST["nombre"] . "', mas_valor='" . $_POST["costo"] . "' WHERE mas_id='" . $_POST["idMas"] . "'", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '"</script>';
	exit();
}
//CREAR CATEGORIA DE NOTAS
if ($_POST["id"] == 54) {/*
	if (trim($_POST["nombre"]) == "") {
		echo "<span style='font-family:Arial; color:red;'>Debe llenar todos los campos.<br>
		<a href='javascript:history.go(-1)'>[Volver al formulario]</a></samp>";
		exit();
	}
	mysql_query("INSERT INTO academico_categorias_notas (catn_nombre)VALUES('" . $_POST["nombre"] . "')", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '"</script>';
	exit();*/
}
//CAMBIO DE GRUPO A ESTUDIANTES
if ($_POST["id"] == 55) {/*
	$estudiante = mysql_fetch_array(mysql_query("SELECT * FROM academico_matriculas WHERE mat_id='" . $_POST["estudiante"] . "'", $conexion));
	$cargasConsulta = mysql_query("SELECT * FROM academico_cargas WHERE car_curso='" . $estudiante["mat_grado"] . "' AND car_grupo='" . $estudiante["mat_grupo"] . "'", $conexion);
	while ($cargasDatos = mysql_fetch_array($cargasConsulta)) {
		$cargasConsultaNuevo = mysql_fetch_array(mysql_query("SELECT * FROM academico_cargas 
		WHERE car_curso='" . $_POST["cursoNuevo"] . "' AND car_grupo='" . $_POST["grupoNuevo"] . "' AND car_materia='" . $cargasDatos["car_materia"] . "'", $conexion));

		mysql_query("UPDATE academico_boletin SET bol_carga='" . $cargasConsultaNuevo["car_id"] . "' 
		WHERE bol_carga='" . $cargasDatos["car_id"] . "' AND bol_estudiante='" . $_POST["estudiante"] . "'", $conexion);
	}
	mysql_query("UPDATE academico_matriculas SET mat_grado='" . $_POST["cursoNuevo"] . "', mat_grupo='" . $_POST["grupoNuevo"] . "' WHERE mat_id='" . $_POST["estudiante"] . "'", $conexion);
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '"</script>';
	exit();*/
}
//EDITAR INDICADORES DE LOS DOCENTES
if ($_POST["id"] == 56) {
	include("verificar-carga.php");
	//include("verificar-periodos-diferentes.php");


	$sumaIndicadores = mysql_fetch_array(mysql_query("SELECT
	(SELECT sum(ipc_valor) FROM academico_indicadores_carga 
	WHERE ipc_carga='" . $cargaConsultaActual . "' AND ipc_periodo='" . $periodoConsultaActual . "' AND ipc_creado=0),
	(SELECT sum(ipc_valor) FROM academico_indicadores_carga 
	WHERE ipc_carga='" . $cargaConsultaActual . "' AND ipc_periodo='" . $periodoConsultaActual . "' AND ipc_creado=1),
	(SELECT count(*) FROM academico_indicadores_carga 
	WHERE ipc_carga='" . $cargaConsultaActual . "' AND ipc_periodo='" . $periodoConsultaActual . "' AND ipc_creado=1)
	", $conexion));
	$porcentajePermitido = 100 - $sumaIndicadores[0];
	$porcentajeRestante = ($porcentajePermitido - $sumaIndicadores[1]);
	$porcentajeRestante = ($porcentajeRestante + $_POST["valorIndicador"]);


	mysql_query("UPDATE academico_indicadores SET ind_nombre='" . $_POST["contenido"] . "' WHERE ind_id='" . $_POST["idInd"] . "'", $conexion);
	$lineaError = __LINE__;
	include("../compartido/reporte-errores.php");

	//Si vamos a relacionar los indicadores con los SABERES
	if ($datosCargaActual['car_saberes_indicador'] == 1) {
		mysql_query("UPDATE academico_indicadores_carga SET ipc_evaluacion='" . $_POST["saberes"] . "' WHERE ipc_id='" . $_POST["idR"] . "'", $conexion);
		$lineaError = __LINE__;
		include("../compartido/reporte-errores.php");
	}

	//Para los DIRECTIVOS los valores de los indicadores son de forma manual
	if (!is_numeric($_POST["valor"])) {
		$_POST["valor"] = 1;
	}
	//Si el valor es mayor al adecuado lo ajustamos al porcentaje restante; Siempre que este último sea mayor a 0.
	if ($_POST["valor"] > $porcentajeRestante and $porcentajeRestante > 0) {
		$_POST["valor"] = $porcentajeRestante;
	}
	mysql_query("UPDATE academico_indicadores_carga SET ipc_valor='" . $_POST["valor"] . "', ipc_creado='" . $_POST["creado"] . "' WHERE ipc_id='" . $_POST["idR"] . "'", $conexion);
	$lineaError = __LINE__;
	include("../compartido/reporte-errores.php");


	echo '<script type="text/javascript">window.location.href="cargas-indicadores.php?carga=' . $_GET["carga"] . '&docente=' . $_GET["docente"] . '";</script>';
	exit();
}
//AGREGAR INDICADORES
if ($_POST["id"] == 57) {
	include("verificar-carga.php");
	//include("verificar-periodos-diferentes.php");

	$sumaIndicadores = mysql_fetch_array(mysql_query("SELECT
	(SELECT sum(ipc_valor) FROM academico_indicadores_carga 
	WHERE ipc_carga='" . $cargaConsultaActual . "' AND ipc_periodo='" . $periodoConsultaActual . "' AND ipc_creado=0),
	(SELECT sum(ipc_valor) FROM academico_indicadores_carga 
	WHERE ipc_carga='" . $cargaConsultaActual . "' AND ipc_periodo='" . $periodoConsultaActual . "' AND ipc_creado=1),
	(SELECT count(*) FROM academico_indicadores_carga 
	WHERE ipc_carga='" . $cargaConsultaActual . "' AND ipc_periodo='" . $periodoConsultaActual . "' AND ipc_creado=1)
	", $conexion));
	$lineaError = __LINE__;
	include("../compartido/reporte-errores.php");
	$porcentajePermitido = 100 - $sumaIndicadores[0];
	$porcentajeRestante = ($porcentajePermitido - $sumaIndicadores[1]);

	if ($sumaIndicadores[2] >= $datosCargaActual['car_maximos_indicadores']) {
		echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=209";</script>';
		exit();
	}



	mysql_query("INSERT INTO academico_indicadores(ind_nombre, ind_obligatorio, ind_publico) VALUES('" . $_POST["contenido"] . "', '" . $_POST["creado"] . "', 0)", $conexion);
	$lineaError = __LINE__;
	include("../compartido/reporte-errores.php");
	$idRegistro = mysql_insert_id();
	//Si decide poner los valores porcentuales de los indicadores de forma manual
	if ($datosCargaActual['car_valor_indicador'] == 1) {
		if ($porcentajeRestante <= 0) {
			echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=210&restante=' . $porcentajeRestante . '";</script>';
			exit();
		}
		if (!is_numeric($_POST["valor"])) {
			$_POST["valor"] = 1;
		}
		//Si el valor es mayor al adecuado lo ajustamos al porcentaje restante; Siempre que este último sea mayor a 0.
		if ($_POST["valor"] > $porcentajeRestante and $porcentajeRestante > 0) {
			$_POST["valor"] = $porcentajeRestante;
		}
		mysql_query("INSERT INTO academico_indicadores_carga(ipc_carga, ipc_indicador, ipc_valor, ipc_periodo, ipc_creado, ipc_evaluacion)
			VALUES('" . $cargaConsultaActual . "', '" . $idRegistro . "', '" . $_POST["valor"] . "', '" . $periodoConsultaActual . "', 1, '" . $_POST["saberes"] . "')", $conexion);
		$lineaError = __LINE__;
		include("../compartido/reporte-errores.php");
	}
	//El sistema reparte los porcentajes automáticamente y equitativamente.
	else {
		$valorIgualIndicador = ($porcentajePermitido / ($sumaIndicadores[2] + 1));
		mysql_query("INSERT INTO academico_indicadores_carga(ipc_carga, ipc_indicador, ipc_periodo, ipc_creado, ipc_evaluacion)
			VALUES('" . $cargaConsultaActual . "', '" . $idRegistro . "', '" . $periodoConsultaActual . "', 1, '" . $_POST["saberes"] . "')", $conexion);
		$lineaError = __LINE__;
		include("../compartido/reporte-errores.php");
		//Actualiza todos valores de la misma carga y periodo; incluyendo el que acaba de crear.
		mysql_query("UPDATE academico_indicadores_carga SET ipc_valor='" . $valorIgualIndicador . "' 
			WHERE ipc_carga='" . $cargaConsultaActual . "' AND ipc_periodo='" . $periodoConsultaActual . "' AND ipc_creado=1", $conexion);
		$lineaError = __LINE__;
		include("../compartido/reporte-errores.php");
	}




	//Si las calificaciones son de forma automática.
	if ($datosCargaActual['car_configuracion'] == 0) {
		//Repetimos la consulta de los indicadores porque los valores fueron actualizados
		$indicadoresConsultaActualizado = mysql_query("SELECT * FROM academico_indicadores_carga 
		WHERE ipc_carga='" . $cargaConsultaActual . "' AND ipc_periodo='" . $periodoConsultaActual . "' AND ipc_creado=1", $conexion);
		//Actualizamos todas las actividades por cada indicador
		while ($indicadoresDatos = mysql_fetch_array($indicadoresConsultaActualizado)) {
			$actividadesNum = mysql_num_rows(mysql_query("SELECT * FROM academico_actividades 
			WHERE act_id_tipo='" . $indicadoresDatos['ipc_indicador'] . "' AND act_periodo='" . $periodoConsultaActual . "' AND act_id_carga='" . $cargaConsultaActual . "' AND act_estado=1", $conexion));
			$lineaError = __LINE__;
			include("../compartido/reporte-errores.php");
			//Si hay actividades relacionadas al indicador, actualizamos su valor.
			if ($actividadesNum > 0) {
				$valorIgualActividad = ($indicadoresDatos['ipc_valor'] / $actividadesNum);
				mysql_query("UPDATE academico_actividades SET act_valor='" . $valorIgualActividad . "' 
				WHERE act_id_tipo='" . $indicadoresDatos['ipc_indicador'] . "' AND act_periodo='" . $periodoConsultaActual . "' AND act_id_carga='" . $cargaConsultaActual . "' AND act_estado=1", $conexion);
				$lineaError = __LINE__;
				include("../compartido/reporte-errores.php");
			}
		}
	}

	echo '<script type="text/javascript">window.location.href="cargas-indicadores.php?carga=' . $cargaConsultaActual . '&periodo=' . $periodoConsultaActual . '&docente=' . $_GET["docente"] . '";</script>';
	exit();
}
//ACTUALIZAR CATEGORÍAS FALTAS
if ($_POST["id"] == 58) {
	mysql_query("UPDATE disciplina_categorias SET dcat_nombre='" . $_POST["categoria"] . "' WHERE dcat_id=" . $_POST["idR"] . ";", $conexion);
	$lineaError = __LINE__;
	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//ACTUALIZAR FALTAS
if ($_POST["id"] == 59) {
	mysql_query("UPDATE disciplina_faltas SET dfal_codigo='" . $_POST["codigo"] . "', dfal_nombre='" . $_POST["nombre"] . "', dfal_id_categoria='" . $_POST["categoria"] . "' 
	WHERE dfal_id='" . $_POST["idR"] . "'", $conexion);
	$lineaError = __LINE__;
	include("../compartido/reporte-errores.php");

	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//AGREGAR FALTAS
if ($_POST["id"] == 60) {
	mysql_query("INSERT INTO disciplina_faltas(dfal_nombre, dfal_id_categoria, dfal_codigo)
			VALUES('" . $_POST["nombre"] . "', '" . $_POST["categoria"] . "', '" . $_POST["codigo"] . "')", $conexion);
	$lineaError = __LINE__;
	include("../compartido/reporte-errores.php");

	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//AGREGAR FALTAS
if ($_POST["id"] == 61) {
	mysql_query("INSERT INTO disciplina_categorias(dcat_nombre)
			VALUES('" . $_POST["categoria"] . "')", $conexion);
	$lineaError = __LINE__;
	include("../compartido/reporte-errores.php");

	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//========================================== GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET  GET GET GET GET GET GET GET GET GET GET GET GET GET ======================

//CAMBIAR DE ESTADO LAS NOTICIAS
if ($_GET["get"] == 1) {
	$consulta = mysql_query("SELECT * FROM social_noticias WHERE not_id='" . $_GET["id"] . "'", $conexion);
	$resultado = mysql_fetch_array($consulta);
	if ($resultado[5] == 0) $estado = 1;
	else $estado = 0;
	mysql_query("UPDATE social_noticias SET not_estado='" . $estado . "' WHERE not_id='" . $_GET["id"] . "'", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="noticias.php#N' . $_GET["id"] . '";</script>';
	exit();
}
//ELIMINAR NOTICIAS
if ($_GET["get"] == 2) {
	mysql_query("UPDATE social_noticias SET not_estado=2 WHERE not_id='" . $_GET["id"] . "'", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//MOSTRAR TODAS MIS NOTICIAS
if ($_GET["get"] == 3) {
	mysql_query("UPDATE social_noticias SET not_estado=1 WHERE not_usuario='" . $_SESSION["id"] . "' AND not_estado!=2", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//OCULTAR TODAS MIS NOTICIAS
if ($_GET["get"] == 4) {
	mysql_query("UPDATE social_noticias SET not_estado=0 WHERE not_usuario='" . $_SESSION["id"] . "' AND not_estado!=2", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//ELIMINAR TODAS MIS NOTICIAS
if ($_GET["get"] == 5) {
	mysql_query("UPDATE social_noticias SET not_estado=2 WHERE not_usuario='" . $_SESSION["id"] . "'", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//ELIMINAR USUARIOS
if ($_GET["get"] == 6) {
	mysql_query("DELETE FROM usuarios WHERE uss_id='".$_GET["id"]."' AND uss_tipo!=5",$conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="'.$_SERVER['HTTP_REFERER'].'";</script>';
	exit();
}


//ENVIAR SOLICITUD AMISTAD
if ($_GET["get"] == 7) {
	mysql_query("INSERT INTO social_amigos(ams_usuario, ams_amigo, ams_estado, ams_destacado)VALUES('" . $_SESSION["id"] . "', '" . $_GET["usuario"] . "', 0, 0)", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//MOSTRAR TODAS MIS NOTICIAS
if ($_GET["get"] == 8) {
	mysql_query("UPDATE social_noticias SET not_estado=1 WHERE not_estado!=2", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//OCULTAR TODAS MIS NOTICIAS
if ($_GET["get"] == 9) {
	mysql_query("UPDATE social_noticias SET not_estado=0 WHERE not_estado!=2", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//ELIMINAR TODAS MIS NOTICIAS
if ($_GET["get"] == 10) {
	mysql_query("UPDATE social_noticias SET not_estado=2", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//ANULAR MOVIMIENTO
if ($_GET["get"] == 11) {
	mysql_query("UPDATE finanzas_cuentas SET fcu_anulado=1 WHERE fcu_id='" . $_GET["idR"] . "'", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="movimientos.php?id=' . $_GET["id"] . '";</script>';
	exit();
}
//ELIMINAR REPORTE
if ($_GET["get"] == 12) {
	mysql_query("DELETE FROM disciplina_reportes WHERE dr_id='" . $_GET["idR"] . "'", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//DESHABILITAR CURSO
if ($_GET["get"] == 13) {/*
	mysql_query("UPDATE academico_grados SET gra_estado=0 WHERE gra_id='" . $_GET["id"] . "'", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();*/
}
//DESHABILITAR INGRESO-EGRESO
if ($_GET["get"] == 14) {
	mysql_query("UPDATE finanzas_cuentas SET fcu_anulado=1 WHERE fcu_id='" . $_GET["id"] . "';", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//CANCELAR MATRICULA CONDICIONAL
if ($_GET["get"] == 15) {
	mysql_query("UPDATE disciplina_matricula_condicional SET cond_estado=0 WHERE cond_id=" . $_GET["idMC"] . ";", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//DESHABILITAR HORARIO
if ($_GET["get"] == 16) {/*
	mysql_query("UPDATE academico_horarios SET hor_estado=0 WHERE hor_id=" . $_GET["idH"] . ";", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="cargas-horarios.php?id=' . $_GET["idC"] . '";</script>';
	exit();*/
}
//BLOQUEAR O DESBLOQUEAR UN USUARIO
if ($_GET["get"] == 17) {
	if ($_GET["lock"] == 1) $estado = 0;
	else $estado = 1;
	mysql_query("UPDATE usuarios SET uss_bloqueado='" . $estado . "' WHERE uss_id='" . $_GET["idR"] . "'", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//ELIMINAR CATEGORIA NOTAS
if ($_GET["get"] == 18) {/*
	mysql_query("DELETE FROM academico_notas_tipos WHERE notip_id=" . $_GET["idN"] . ";", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="academico-categoria-notas-especifica.php?id=' . $_GET["idNC"] . '";</script>';
	exit();*/
}
//ME GUSTA MURO
if ($_GET["get"] == 19) {
	include("../modelo/conexion-admon.php");
	$consulta = mysql_query("SELECT * FROM social_muro_acciones WHERE mpa_muro='" . $_GET["muro"] . "' AND mpa_usuario='" . $_SESSION["id"] . "' AND mpa_institucion='" . $_GET["i"] . "' AND mpa_accion='" . $_GET["ac"] . "'", $conexion_admon);
	if ($num = mysql_num_rows($consulta) == 0) {
		mysql_query("INSERT INTO social_muro_acciones(mpa_muro, mpa_usuario, mpa_fecha, mpa_accion, mpa_institucion)VALUES('" . $_GET["muro"] . "','" . $_SESSION["id"] . "',now(),'" . $_GET["ac"] . "','" . $_GET["i"] . "')", $conexion_admon);
		if (mysql_errno() != 0) {
			echo mysql_error();
			exit();
		}
	}
	echo '<script type="text/javascript">window.location.href="social-muro.php#M' . $_GET["muro"] . '";</script>';
	exit();
}
//ELIMINAR INDICADORES OBLIGATORIOS
if ($_GET["get"] == 20) {/*
	mysql_query("DELETE FROM academico_indicadores WHERE ind_id=" . $_GET["idN"] . ";", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();*/
}
//ELIMINAR RESPUESTAS GENERALES
if ($_GET["get"] == 21) {
	mysql_query("DELETE FROM general_respuestas WHERE resg_id=" . $_GET["idN"] . ";", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="general-preguntas.php?eva=' . $_GET["eva"] . '";</script>';
	exit();
}
//ELIMINAR PREGUNTAS
if ($_GET["get"] == 22) {
	mysql_query("DELETE FROM general_respuestas WHERE resg_id_pregunta=" . $_GET["idN"] . ";", $conexion);
	mysql_query("DELETE FROM general_preguntas WHERE pregg_id=" . $_GET["idN"] . ";", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="general-preguntas.php?eva=' . $_GET["eva"] . '";</script>';
	exit();
}
//ELIMINAR EVALUACIONES
if ($_GET["get"] == 23) {
	mysql_query("DELETE FROM general_preguntas WHERE pregg_id_evaluacion=" . $_GET["idN"] . ";", $conexion);
	mysql_query("DELETE FROM general_evaluacion_asignar WHERE epag_id_evaluacion=" . $_GET["idN"] . ";", $conexion);
	mysql_query("DELETE FROM general_evaluaciones WHERE evag_id=" . $_GET["idN"] . ";", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//ELIMINAR EVALUACIONES
if ($_GET["get"] == 24) {
	mysql_query("DELETE FROM general_evaluacion_asignar WHERE epag_id=" . $_GET["idN"] . ";", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="general-evaluacion.php";</script>';
	exit();
}
//ACTUALIZAR FOTO
if ($_GET["get"] == 25) {
	mysql_query("UPDATE usuarios SET uss_foto='default.png', uss_portada='banner-sintia.png'", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	mysql_query("UPDATE academico_matriculas SET mat_foto='default.png'", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//ELIMINAR ESTUDIANTE
if ($_GET["get"] == 26) {/*
	mysql_query("DELETE FROM academico_actividad_evaluaciones_resultados WHERE res_id_estudiante='" . $_GET["idE"] . "'", $conexion);
	mysql_query("DELETE FROM academico_actividad_foro_comentarios WHERE com_id_estudiante='" . $_GET["idE"] . "'", $conexion);
	mysql_query("DELETE FROM academico_actividad_foro_respuestas WHERE fore_id_estudiante='" . $_GET["idE"] . "'", $conexion);
	mysql_query("DELETE FROM academico_actividad_tareas_entregas WHERE ent_id_estudiante='" . $_GET["idE"] . "'", $conexion);
	mysql_query("DELETE FROM academico_ausencias WHERE aus_id_estudiante='" . $_GET["idE"] . "'", $conexion);
	mysql_query("DELETE FROM academico_boletin WHERE bol_id_estudiante='" . $_GET["idE"] . "'", $conexion);
	mysql_query("DELETE FROM academico_calificaciones WHERE cal_id_estudiante='" . $_GET["idE"] . "'", $conexion);
	mysql_query("UPDATE academico_matriculas SET mat_eliminado=1 WHERE mat_id='" . $_GET["idE"] . "'", $conexion);
	mysql_query("DELETE FROM academico_nivelaciones WHERE niv_cod_estudiante='" . $_GET["idE"] . "'", $conexion);
	mysql_query("DELETE FROM academico_recuperaciones_notas WHERE rec_cod_estudiante='" . $_GET["idE"] . "'", $conexion);
	mysql_query("DELETE FROM disciplina_matricula_condicional WHERE cond_estudiante='" . $_GET["idE"] . "'", $conexion);
	mysql_query("DELETE FROM disciplina_reportes WHERE dr_estudiante='" . $_GET["idE"] . "'", $conexion);
	mysql_query("DELETE FROM disiplina_nota WHERE dn_cod_estudiante='" . $_GET["idE"] . "'", $conexion);
	mysql_query("DELETE FROM finanzas_cuentas WHERE fcu_usuario='" . $_GET["idU"] . "'", $conexion);
	mysql_query("DELETE FROM general_resultados WHERE resg_id_estudiante='" . $_GET["idE"] . "'", $conexion);
	mysql_query("DELETE FROM seguridad_historial_acciones WHERE hil_usuario='" . $_GET["idU"] . "'", $conexion);
	mysql_query("DELETE FROM social_amigos WHERE ams_usuario='" . $_GET["idU"] . "' OR ams_amigo", $conexion);
	mysql_query("DELETE FROM social_noticias WHERE not_usuario='" . $_GET["idU"] . "'", $conexion);
	mysql_query("DELETE FROM social_visitas WHERE vis_usuario='" . $_GET["idU"] . "'", $conexion);
	mysql_query("DELETE FROM usuarios WHERE uss_id='" . $_GET["idU"] . "'", $conexion);
	mysql_query("DELETE FROM usuarios_por_estudiantes WHERE upe_id_estudiante='" . $_GET["idE"] . "'", $conexion);
	mysql_query("DELETE FROM social_preferencias_usuarios WHERE preu_usuario='" . $_GET["idU"] . "'", $conexion);
	mysql_query("DELETE FROM social_emails WHERE ema_de='" . $_GET["idU"] . "' OR ema_para='" . $_GET["idU"] . "'", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();*/
}
//BLOQUEAR TODO
if ($_GET["get"] == 27) {
	mysql_query("UPDATE usuarios SET uss_bloqueado=1 WHERE uss_tipo!=5 AND uss_tipo!=1", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//DESBLOQUEAR TODO
if ($_GET["get"] == 28) {
	mysql_query("UPDATE usuarios SET uss_bloqueado=0", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//APAGAR SESION
if ($_GET["get"] == 29) {
	mysql_query("UPDATE usuarios SET uss_estado=0", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//APAGAR SESION
if ($_GET["get"] == 30) {
	mysql_query("UPDATE usuarios SET uss_estado=1", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//ACTUALIZAR CLAVE
if ($_GET["get"] == 31) {
	mysql_query("UPDATE usuarios SET uss_clave='sintia1234'", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//MATRICULAR TODOS LOS ESTUDIANTES
if ($_GET["get"] == 32) {/*
	mysql_query("UPDATE academico_matriculas SET mat_estado_matricula=1", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();*/
}
//CANCELAR TODAS LAS MATRICULAS
if ($_GET["get"] == 33) {/*
	mysql_query("UPDATE academico_matriculas SET mat_estado_matricula=3", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();*/
}
//ELIMINAR ACUDIDOS DE LOS ACUDIENTES
if ($_GET["get"] == 34) {
	mysql_query("DELETE FROM usuarios_por_estudiantes WHERE upe_id=" . $_GET["id"] . ";", $conexion);
	mysql_query("UPDATE academico_matriculas SET mat_acudiente=null WHERE mat_id='" . $_GET["est"] . "'", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//ELIMINAR TODOS ACUDIDOS DE LOS ACUDIENTES
if ($_GET["get"] == 35) {
	mysql_query("DELETE FROM usuarios_por_estudiantes WHERE upe_id_usuario=" . $_GET["usr"] . ";", $conexion);
	mysql_query("UPDATE academico_matriculas SET mat_acudiente=null WHERE mat_acudiente='" . $_GET["usr"] . "'", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//TODOS ESTUDIANTES NUEVOS
if ($_GET["get"] == 36) {/*
	mysql_query("UPDATE academico_matriculas SET mat_tipo=128", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();*/
}
//TODOS ESTUDIANTES ANTIGUOS
if ($_GET["get"] == 37) {/*
	mysql_query("UPDATE academico_matriculas SET mat_tipo=129", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();*/
}
//FORMATO 1
if ($_GET["get"] == 38) {
	mysql_query("UPDATE academico_grados SET gra_formato_boletin=1", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//PONER EN 0 LOS VALORES DE LA MATRICULA
if ($_GET["get"] == 39) {
	mysql_query("UPDATE academico_grados SET gra_valor_matricula=0", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//PONER EN 0 LOS VALORES DE LA PENSIÓN
if ($_GET["get"] == 40) {
	mysql_query("UPDATE academico_grados SET gra_valor_pension=0", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//ELIMINAR TODOS LOS ESTUDIANTES DE VERDAD
if ($_GET["get"] == 41) {/*
	mysql_query("DELETE FROM academico_actividad_evaluaciones_resultados", $conexion);
	mysql_query("DELETE FROM academico_actividad_foro_comentarios", $conexion);
	mysql_query("DELETE FROM academico_actividad_foro_respuestas", $conexion);
	mysql_query("DELETE FROM academico_actividad_tareas_entregas", $conexion);
	mysql_query("DELETE FROM academico_ausencias", $conexion);
	mysql_query("DELETE FROM academico_boletin", $conexion);
	mysql_query("DELETE FROM academico_calificaciones", $conexion);
	mysql_query("DELETE FROM academico_matriculas", $conexion); //ELIMINA TODO
	mysql_query("DELETE FROM academico_nivelaciones", $conexion);
	mysql_query("DELETE FROM academico_recuperaciones_notas", $conexion);
	mysql_query("DELETE FROM disciplina_matricula_condicional", $conexion);
	mysql_query("DELETE FROM disciplina_reportes", $conexion);
	mysql_query("DELETE FROM disiplina_nota", $conexion);
	mysql_query("DELETE FROM general_resultados", $conexion);
	mysql_query("DELETE FROM usuarios WHERE uss_tipo=4", $conexion);
	mysql_query("DELETE FROM usuarios_por_estudiantes", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();*/
}
//ELIMINAR CARGA ACADEMICA
if ($_GET["get"] == 42) {
	mysql_query("DELETE FROM academico_actividad_evaluaciones WHERE eva_id_carga='" . $_GET["id"] . "'", $conexion);
	mysql_query("DELETE FROM academico_actividad_foro WHERE foro_id_carga='" . $_GET["id"] . "'", $conexion);
	mysql_query("DELETE FROM academico_actividad_foro WHERE foro_id_carga='" . $_GET["id"] . "'", $conexion);
	mysql_query("DELETE FROM academico_actividad_preguntas WHERE preg_id_carga='" . $_GET["id"] . "'", $conexion);
	mysql_query("DELETE FROM academico_actividad_tareas WHERE tar_id_carga='" . $_GET["id"] . "'", $conexion);
	mysql_query("DELETE FROM academico_actividades WHERE act_id_carga='" . $_GET["id"] . "'", $conexion);
	mysql_query("DELETE FROM academico_boletin WHERE bol_carga='" . $_GET["id"] . "'", $conexion);
	mysql_query("DELETE FROM academico_cargas WHERE car_id='" . $_GET["id"] . "'", $conexion);
	mysql_query("DELETE FROM academico_clases WHERE cls_id_carga='" . $_GET["id"] . "'", $conexion);
	mysql_query("DELETE FROM academico_cronograma WHERE cro_id_carga='" . $_GET["id"] . "'", $conexion);
	mysql_query("DELETE FROM academico_horarios WHERE hor_id_carga='" . $_GET["id"] . "'", $conexion);
	mysql_query("DELETE FROM academico_indicadores_carga WHERE ipc_carga='" . $_GET["id"] . "'", $conexion);
	mysql_query("DELETE FROM academico_nivelaciones WHERE niv_id_asg='" . $_GET["id"] . "'", $conexion);
	mysql_query("DELETE FROM academico_pclase WHERE pc_id_carga='" . $_GET["id"] . "'", $conexion);
	mysql_query("DELETE FROM disiplina_nota WHERE dn_id_carga='" . $_GET["id"] . "'", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//ELIMINAR TODAS LAS CARGAS
if ($_GET["get"] == 43) {/*
	mysql_query("DELETE FROM academico_actividad_evaluaciones", $conexion);
	mysql_query("DELETE FROM academico_actividad_foro", $conexion);
	mysql_query("DELETE FROM academico_actividad_foro", $conexion);
	mysql_query("DELETE FROM academico_actividad_preguntas", $conexion);
	mysql_query("DELETE FROM academico_actividad_tareas", $conexion);
	mysql_query("DELETE FROM academico_actividades", $conexion);
	mysql_query("DELETE FROM academico_boletin", $conexion);
	mysql_query("DELETE FROM academico_cargas", $conexion);
	mysql_query("DELETE FROM academico_clases", $conexion);
	mysql_query("DELETE FROM academico_cronograma", $conexion);
	mysql_query("DELETE FROM academico_horarios", $conexion);
	mysql_query("DELETE FROM academico_indicadores_carga", $conexion);
	mysql_query("DELETE FROM academico_nivelaciones", $conexion);
	mysql_query("DELETE FROM academico_pclase", $conexion);
	mysql_query("DELETE FROM academico_calificaciones", $conexion);
	mysql_query("DELETE FROM academico_actividad_evaluaciones_resultados", $conexion);
	mysql_query("DELETE FROM academico_actividad_foro_comentarios", $conexion);
	mysql_query("DELETE FROM academico_actividad_foro_respuestas", $conexion);
	mysql_query("DELETE FROM academico_ausencias", $conexion);
	mysql_query("DELETE FROM disiplina_nota", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();*/
}
//CIERRE DE CAJA
if ($_GET["get"] == 44) {
	mysql_query("UPDATE finanzas_cuentas SET fcu_cerrado=1, fcu_fecha_cerrado=now(), fcu_cerrado_usuario='" . $_SESSION["id"] . "' WHERE fcu_tipo=1 AND fcu_anulado=0 AND fcu_forma_pago=1 AND fcu_cerrado=0", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//TODOS ESTUDIANTES EN EL GRUPO A
if ($_GET["get"] == 45) {/*
	mysql_query("UPDATE academico_matriculas SET mat_grupo=1", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();*/
}
//ELIMINAR AREAS
if ($_GET["get"] == 46) {
	mysql_query("DELETE FROM academico_areas WHERE ar_id=" . $_GET["id"] . ";", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//ELIMINAR MATERIAS
if ($_GET["get"] == 47) {
	mysql_query("DELETE FROM academico_materias WHERE mat_id=" . $_GET["id"] . ";", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//ELIMINAR USUARIOS AUTORIZADOS
if ($_GET["get"] == 48) {
	mysql_query("DELETE FROM autorizados WHERE aut_id='" . $_GET["idaut"] . "'", $conexion);
	echo '<script type="text/javascript">window.location.href="usuarios-autorizados.php";</script>';
	exit();
}
//ELIMINAR USUARIOS
if ($_GET["get"] == 49) {
	mysql_query("DELETE FROM usuarios WHERE uss_id='" . $_GET["iduss"] . "'", $conexion);
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//ELIMINAR OPCION GENERAL
if ($_GET["get"] == 50) {
	mysql_query("DELETE FROM opciones_generales WHERE ogen_id='" . $_GET["idogen"] . "'", $conexion);
	echo '<script type="text/javascript">window.location.href="opciones-generales.php";</script>';
	exit();
}
//ELIMINAR AUSENCIA
if ($_GET["get"] == 51) {
	mysql_query("DELETE FROM ausencia_docente WHERE aus_id='" . $_GET["idaus"] . "'", $conexion);
	echo '<script type="text/javascript">window.location.href="ausencia-docente.php?idDoc=' . $_GET["idDoc"] . '";</script>';
	exit();
}
//ELIMINAR OBSERVACIONES
if ($_GET["get"] == 52) {
	mysql_query("DELETE FROM observacion_docente WHERE obs_id='" . $_GET["idobs"] . "'", $conexion);
	echo '<script type="text/javascript">window.location.href="observacion-docente.php?idDoc=' . $_GET["idDoc"] . '";</script>';
	exit();
}
//ELIMINAR MOVIMIENTOS FINANCIEROS
if ($_GET["get"] == 53) {
	mysql_query("DELETE FROM finanzas_cuentas", $conexion);
	echo '<script type="text/javascript">window.location.href="finanzas-movimientos-lista.php";</script>';
	exit();
}
//PROMOCIONAR ESTUDIANTES
if ($_GET["get"] == 54) {
	mysql_query("UPDATE academico_matriculas SET mat_promocionado=0", $conexion);
	$grados = mysql_query("SELECT * FROM academico_grados", $conexion);
	while ($g = mysql_fetch_array($grados)) {
		if ($g[7] != "") {
			mysql_query("UPDATE academico_matriculas SET mat_grado='" . $g[7] . "', mat_promocionado=1 WHERE mat_grado='" . $g[0] . "' AND mat_promocionado=0 AND mat_eliminado=0", $conexion);
		}
	}
	echo '<script type="text/javascript">window.location.href="estudiantes.php";</script>';
	exit();
}
//ELIMINAR COBRO MASIVO
if ($_GET["get"] == 55) {
	mysql_query("DELETE FROM finanzas_cobros_masivos WHERE mas_id='" . $_GET["idMas"] . "'", $conexion);
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//PROMOCIONAR ESTUDIANTES POR CURSO
if ($_GET["get"] == 56) {
	/*mysql_query("UPDATE academico_matriculas SET mat_promocionado=0 WHERE mat_grado='" . $_GET["curso"] . "'", $conexion);
	$g = mysql_fetch_array(mysql_query("SELECT * FROM academico_grados WHERE gra_id='" . $_GET["curso"] . "'", $conexion));
	if ($g[7] != "") {
		mysql_query("UPDATE academico_matriculas SET mat_grado='" . $g[7] . "', mat_promocionado=1 WHERE mat_grado='" . $g[0] . "' AND mat_promocionado=0 AND mat_eliminado=0", $conexion);
	}
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();*/
}
//ELIMINAR NOTA NIVELACION
if ($_GET["get"] == 57) {
	mysql_query("DELETE FROM academico_nivelaciones WHERE niv_id='" . $_GET["idNiv"] . "'", $conexion);
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//COLOCAR COMO USUARIO DE ACCESO EL DOCUMENTO
if ($_GET["get"] == 58) {/*
	mysql_query("UPDATE usuarios SET uss_usuario=(SELECT mat_documento FROM academico_matriculas WHERE mat_id_usuario=uss_id AND mat_documento!='') WHERE uss_tipo=4", $conexion);
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();*/
}
//ELIMINAR TODOS LOS ACUDIENTES
if ($_GET["get"] == 59) {
	mysql_query("DELETE FROM usuarios WHERE uss_tipo=3", $conexion);
	mysql_query("DELETE FROM usuarios_por_estudiantes", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//ELIMINAR CATEGORIAS NOTAS
if ($_GET["get"] == 60) {/*
	mysql_query("DELETE FROM academico_notas_tipos WHERE notip_categoria='" . $_GET["idR"] . "'", $conexion);
	mysql_query("DELETE FROM academico_categorias_notas WHERE catn_id='" . $_GET["idR"] . "'", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();*/
}
//LLENAR IPC DESDE LAS CARGAS
if ($_GET["get"] == 61) {/*
	$cargas = mysql_query("SELECT * FROM academico_cargas WHERE car_ih!=''", $conexion);
	while ($c = mysql_fetch_array($cargas)) {
		mysql_query("DELETE FROM academico_intensidad_curso WHERE ipc_curso='" . $c[2] . "' AND ipc_materia='" . $c[4] . "'", $conexion);
		if (mysql_errno() != 0) {
			echo mysql_error();
			exit();
		}
		mysql_query("INSERT INTO academico_intensidad_curso(ipc_curso, ipc_materia, ipc_intensidad)VALUES('" . $c[2] . "','" . $c[4] . "','" . $c['car_ih'] . "')", $conexion);
		if (mysql_errno() != 0) {
			echo mysql_error();
			exit();
		}
	}
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();*/
}
//CREAR USUARIO DE ESTUDIANTES
if ($_GET["get"] == 62) {/*
	$est = mysql_fetch_array(mysql_query("SELECT * FROM academico_matriculas WHERE mat_id='" . $_GET["id"] . "'", $conexion));
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");

	mysql_query("DELETE FROM usuarios WHERE uss_usuario='" . $est[12] . "'", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");

	mysql_query("INSERT INTO usuarios(uss_usuario, uss_clave, uss_tipo, uss_nombre, uss_estado, uss_foto, uss_portada, uss_idioma, uss_tema, uss_perfil, uss_ocupacion, uss_email, uss_fecha_nacimiento, uss_genero, uss_bloqueado, uss_fecha_registro, uss_responsable_registro)VALUES('" . $est[12] . "','1234',4,'" . $est[5] . " " . $est[3] . " " . $est[4] . "',0,'default.png','default.png',1,'blue',0,'Estudiante','notiene@gmail.com','" . $est[9] . "','" . $est[8] . "',0,now(),'" . $_SESSION["id"] . "')", $conexion);
	$idUsuario = mysql_insert_id();
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");

	mysql_query("UPDATE academico_matriculas SET mat_id_usuario='" . $idUsuario . "' WHERE mat_id='" . $_GET["id"] . "'", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="usuario-modificar.php?id=' . $idUsuario . '";</script>';
	exit();*/
}
//CREAR USUARIO DE TODOS LOS ESTUDIANTES QUE NO LO TENGAN
if ($_GET["get"] == 63) {/*
	$estud = mysql_query("SELECT * FROM academico_matriculas WHERE mat_eliminado=0", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");

	while ($est = mysql_fetch_array($estud)) {
		$usComp = mysql_num_rows(mysql_query("SELECT * FROM usuarios WHERE uss_id='" . $est["mat_id_usuario"] . "'", $conexion));
		if ($usComp == 0) {
			mysql_query("DELETE FROM usuarios WHERE uss_usuario='" . $est[12] . "'", $conexion);
			if (mysql_errno() != 0) {
				echo mysql_error();
				exit();
			}

			mysql_query("INSERT INTO usuarios(uss_usuario, uss_clave, uss_tipo, uss_nombre, uss_estado, uss_foto, uss_portada, uss_idioma, uss_tema, uss_perfil, uss_ocupacion, uss_email, uss_fecha_nacimiento, uss_genero, uss_bloqueado, uss_fecha_registro, uss_responsable_registro)VALUES('" . $est[12] . "','1234',4,'" . $est[5] . " " . $est[3] . " " . $est[4] . "',0,'default.png','default.png',1,'blue',0,'Estudiante','notiene@gmail.com','" . $est[9] . "','" . $est[8] . "',0,now(),'" . $_SESSION["id"] . "')", $conexion);
			$idUsuario = mysql_insert_id();
			if (mysql_errno() != 0) {
				echo mysql_error();
				exit();
			}

			mysql_query("UPDATE academico_matriculas SET mat_id_usuario='" . $idUsuario . "' WHERE mat_id='" . $est[0] . "'", $conexion);
			if (mysql_errno() != 0) {
				echo mysql_error();
				exit();
			}
		}
	}
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();*/
}
//DEVOLVER ESTUDIANTES
if ($_GET["get"] == 64) {
	mysql_query("UPDATE academico_matriculas SET mat_promocionado=0", $conexion);
	$grados = mysql_query("SELECT * FROM academico_grados", $conexion);
	while ($g = mysql_fetch_array($grados)) {
		if ($g[10] != "") {
			mysql_query("UPDATE academico_matriculas SET mat_grado='" . $g[10] . "', mat_promocionado=0 WHERE mat_grado='" . $g[0] . "' AND mat_eliminado=0", $conexion);
		}
	}
	echo '<script type="text/javascript">window.location.href="estudiantes.php";</script>';
	exit();
}
//BLOQUEAR ESTUDIANTES PARA MATRÍCULA
if ($_GET["get"] == 65) {/*
	mysql_query("UPDATE academico_matriculas SET mat_compromiso=1 WHERE mat_id='" . $_GET["id"] . "'", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="estudiantes.php";</script>';
	exit();*/
}
//ACTIVAR ESTUDIANTES PARA MATRÍCULA
if ($_GET["get"] == 66) {/*
	mysql_query("UPDATE academico_matriculas SET mat_compromiso=0 WHERE mat_id='" . $_GET["id"] . "'", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="estudiantes.php";</script>';
	exit();*/
}
//REACCIONES POR NOTICIA
if ($_GET["get"] == 67) {
	$reaccion = mysql_fetch_array(mysql_query("SELECT * FROM social_noticias_reacciones WHERE npr_usuario='" . $_SESSION["id"] . "' AND npr_noticia='" . $_GET["post"] . "'", $conexion));
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	if ($reaccion[0] == "") {
		mysql_query("INSERT INTO social_noticias_reacciones(npr_usuario, npr_noticia, npr_reaccion, npr_fecha, npr_estado)VALUES('" . $_SESSION["id"] . "', '" . $_GET["post"] . "','" . $_GET["r"] . "',now(),1)", $conexion);
		if (mysql_errno() != 0) {
			echo mysql_error();
			exit();
		}
	} else {
		mysql_query("UPDATE social_noticias_reacciones SET npr_reaccion='" . $_GET["r"] . "' WHERE npr_usuario='" . $_SESSION["id"] . "' AND npr_noticia='" . $_GET["post"] . "'", $conexion);
		if (mysql_errno() != 0) {
			echo mysql_error();
			exit();
		}
	}
	echo '<script type="text/javascript">window.location.href="noticias.php";</script>';
	exit();
}
//ELIMINAR INDICADORES DE LOS DOCENTES
if ($_GET["get"] == 68) {

	include("verificar-carga.php");
	//include("verificar-periodos-diferentes.php");


	$actividadesRelacionadasConsulta = mysql_query("SELECT * FROM academico_actividades 
	WHERE act_id_tipo='" . $_GET["idIndicador"] . "' AND act_id_carga='" . $_GET["carga"] . "' AND act_periodo='" . $_GET["periodo"] . "' AND act_estado=1", $conexion);
	$lineaError = __LINE__;
	include("../compartido/reporte-errores.php");
	while ($actividadesRelacionadasDatos = mysql_fetch_array($actividadesRelacionadasConsulta)) {

		mysql_query("UPDATE academico_actividades SET act_estado=0, act_fecha_eliminacion=now(), act_motivo_eliminacion='DIRECTIVO " . $_SESSION["id"] . ": Eliminar indicadores de carga: " . $cargaConsultaActual . ", del P: " . $periodoConsultaActual . "' WHERE act_id='" . $actividadesRelacionadasDatos['act_id'] . "'", $conexion);
		$lineaError = __LINE__;
		include("../compartido/reporte-errores.php");
	}

	mysql_query("DELETE FROM academico_indicadores_carga WHERE ipc_id='" . $_GET["idR"] . "'", $conexion);
	$lineaError = __LINE__;
	include("../compartido/reporte-errores.php");

	$sumaIndicadores = mysql_fetch_array(mysql_query("SELECT
	(SELECT sum(ipc_valor) FROM academico_indicadores_carga 
	WHERE ipc_carga='" . $cargaConsultaActual . "' AND ipc_periodo='" . $periodoConsultaActual . "' AND ipc_creado=0),
	(SELECT sum(ipc_valor) FROM academico_indicadores_carga 
	WHERE ipc_carga='" . $cargaConsultaActual . "' AND ipc_periodo='" . $periodoConsultaActual . "' AND ipc_creado=1),
	(SELECT count(*) FROM academico_indicadores_carga 
	WHERE ipc_carga='" . $cargaConsultaActual . "' AND ipc_periodo='" . $periodoConsultaActual . "' AND ipc_creado=1)
	", $conexion));
	$porcentajePermitido = 100 - $sumaIndicadores[0];
	$porcentajeRestante = ($porcentajePermitido - $sumaIndicadores[1]);

	//Si decide poner los valores porcentuales de los indicadores de forma manual
	if ($datosCargaActual['car_valor_indicador'] == 1) {
	}
	//El sistema reparte los porcentajes automáticamente y equitativamente.
	else {
		$valorIgualIndicador = ($porcentajePermitido / ($sumaIndicadores[2]));
		//Actualiza todos valores de la misma carga y periodo.
		mysql_query("UPDATE academico_indicadores_carga SET ipc_valor='" . $valorIgualIndicador . "' 
		WHERE ipc_carga='" . $cargaConsultaActual . "' AND ipc_periodo='" . $periodoConsultaActual . "' AND ipc_creado=1", $conexion);
		$lineaError = __LINE__;
		include("../compartido/reporte-errores.php");

		//Si decide que los valores de las calificaciones son de forma automática.
		if ($datosCargaActual['car_configuracion'] == 0) {
			//Repetimos la consulta de los indicadores porque los valores fueron actualizados
			$indicadoresConsultaActualizado = mysql_query("SELECT * FROM academico_indicadores_carga 
			WHERE ipc_carga='" . $cargaConsultaActual . "' AND ipc_periodo='" . $periodoConsultaActual . "' AND ipc_creado=1", $conexion);
			//Actualizamos todas las actividades por cada indicador
			while ($indicadoresDatos = mysql_fetch_array($indicadoresConsultaActualizado)) {
				$actividadesNum = mysql_num_rows(mysql_query("SELECT * FROM academico_actividades 
				WHERE act_id_tipo='" . $indicadoresDatos['ipc_indicador'] . "' AND act_periodo='" . $periodoConsultaActual . "' AND act_id_carga='" . $cargaConsultaActual . "' AND act_estado=1", $conexion));
				$lineaError = __LINE__;
				include("../compartido/reporte-errores.php");
				//Si hay actividades relacionadas al indicador, actualizamos su valor.
				if ($actividadesNum > 0) {
					$valorIgualActividad = ($indicadoresDatos['ipc_valor'] / $actividadesNum);
					mysql_query("UPDATE academico_actividades SET act_valor='" . $valorIgualActividad . "' 
					WHERE act_id_tipo='" . $indicadoresDatos['ipc_indicador'] . "' AND act_periodo='" . $periodoConsultaActual . "' AND act_id_carga='" . $cargaConsultaActual . "' AND act_estado=1", $conexion);
					$lineaError = __LINE__;
					include("../compartido/reporte-errores.php");
				}
			}
		}
	}

	echo '<script type="text/javascript">window.location.href="cargas-indicadores.php?carga=' . $_GET["carga"] . '&docente=' . $_GET["docente"] . '";</script>';
	exit();
}
//BLOQUEAR ESTUDIANTES
if ($_GET["get"] == 69) {
	mysql_query("UPDATE usuarios SET uss_bloqueado=1 WHERE uss_tipo=4", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="usuarios.php?tipo=4&cantidad=50";</script>';
	exit();
}
//DESBLOQUEAR ESTUDIANTES
if ($_GET["get"] == 70) {
	mysql_query("UPDATE usuarios SET uss_bloqueado=0 WHERE uss_tipo=4", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="usuarios.php?tipo=4&cantidad=50";</script>';
	exit();
}
//ENCUESTA RESERVA DE CUPO
if($_GET["get"]==71){
	//echo $_GET["idEstudiante"]; exit();

	mysql_query("INSERT INTO general_encuestas(genc_estudiante, genc_fecha, genc_respuesta, genc_comentario)
	VALUES('".$_GET["idEstudiante"]."', now(), 1, 'Reservado por un directivo.')",$conexion);
	
	if(mysql_errno()!=0){echo mysql_error(); exit();}
	echo '<script type="text/javascript">window.location.href="'.$_SERVER['HTTP_REFERER'].'";</script>';
	exit();
}
?>