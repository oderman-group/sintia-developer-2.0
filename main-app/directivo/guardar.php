<?php
include("session.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0130';
include("../compartido/historial-acciones-guardar.php");

include("../compartido/sintia-funciones.php");

if (!empty($_POST["id"])) {
//GUARDAR MOVIMIENTO
if ($_POST["id"] == 8) {
	if (trim($_POST["fecha"]) == "" or trim($_POST["detalle"]) == "" or trim($_POST["valor"]) == "" or trim($_POST["tipo"]) == "" or trim($_POST["forma"]) == "") {
		include("../compartido/guardar-historial-acciones.php");
		echo "<span style='font-family:Arial; color:red;'>Debe llenar todos los campos.</samp>";
		exit();
	}
	if ($_POST["tipo"] == 1) {

		try{
			$consultaConsecutivoActual=mysqli_query($conexion, "SELECT * FROM finanzas_cuentas WHERE fcu_tipo=1 ORDER BY fcu_id DESC");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
		$consecutivoActual = mysqli_fetch_array($consultaConsecutivoActual, MYSQLI_BOTH);
		if ($consecutivoActual['fcu_consecutivo'] == "") {
			$consecutivo = $config['conf_inicio_recibos_ingreso'];
		} else {
			$consecutivo = $consecutivoActual['fcu_consecutivo'] + 1;
		}
	}
	if ($_POST["tipo"] == 2) {

		try{
			$consultaConsecutivoActual=mysqli_query($conexion, "SELECT * FROM finanzas_cuentas WHERE fcu_tipo=2 ORDER BY fcu_id DESC");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
		$consecutivoActual = mysqli_fetch_array($consultaConsecutivoActual, MYSQLI_BOTH);
		if ($consecutivoActual['fcu_consecutivo'] == "") {
			$consecutivo = $config['conf_inicio_recibos_egreso'];
		} else {
			$consecutivo = $consecutivoActual['fcu_consecutivo'] + 1;
		}
	}

	try{
		mysqli_query($conexion, "INSERT INTO finanzas_cuentas(fcu_fecha, fcu_detalle, fcu_valor, fcu_tipo, fcu_observaciones, fcu_usuario, fcu_anulado, fcu_forma_pago, fcu_cerrado, fcu_consecutivo)VALUES('" . $_POST["fecha"] . "','" . $_POST["detalle"] . "','" . $_POST["valor"] . "','" . $_POST["tipo"] . "','" . $_POST["obs"] . "','" . $_POST["usuario"] . "',0,'" . $_POST["forma"] . "',0,'" . $consecutivo . "')");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="movimientos.php";</script>';
	exit();
}
//GUARDAR REPORTE
if ($_POST["id"] == 9) {
	if (trim($_POST["fecha"]) == "" or trim($_POST["codigo"]) == "" or trim($_POST["falta"]) == "" or trim($_POST["tipo"]) == "") {
		include("../compartido/guardar-historial-acciones.php");
		echo "<span style='font-family:Arial; color:red;'>Debe llenar todos los campos.</samp>";
		exit();
	}

	try{
		$consultaUsuarioResponsable=mysqli_query($conexion, "SELECT * FROM usuarios_por_estudiantes WHERE upe_id_estudiante='" . $_POST["codigo"] . "'");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}
	$usuarioResponsable = mysqli_fetch_array($consultaUsuarioResponsable, MYSQLI_BOTH);

	try{
		mysqli_query($conexion, "INSERT INTO ".$baseDatosServicios.".general_alertas(alr_nombre, alr_descripcion, alr_tipo, alr_usuario, alr_fecha_envio, alr_vista, alr_categoria, alr_importancia, alr_institucion, alr_year)VALUES('Reporte disciplinario','El estudiante " . $_POST["codigo"] . " le han hecho un reporte disciplinario',2,'" . $usuarioResponsable[1] . "',now(),0,2,2,'" . $config['conf_id_institucion'] . "','" . $_SESSION["bd"] . "')");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	try{
		mysqli_query($conexion, "INSERT INTO disciplina_reportes(dr_fecha, dr_estudiante, dr_falta, dr_tipo, dr_usuario)VALUES('" . $_POST["fecha"] . "','" . $_POST["codigo"] . "','" . $_POST["falta"] . "','" . $_POST["tipo"] . "','" . $_SESSION["id"] . "')");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="disciplina-listado-reportes.php";</script>';
	exit();
}
//ACTUALIZAR CONFIGURACION REPORTES
if ($_POST["id"] == 13) {
	//COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
	if (trim($_POST["color_borde"]) == "" or trim($_POST["color_encabezado"]) == "" or trim($_POST["tborde"]) == "") {
		include("../compartido/guardar-historial-acciones.php");
		echo "<span style='font-family:Arial; color:red;'>Debe llenar todos los campos.</samp>";
		exit();
	}

	try{
		mysqli_query($conexion, "UPDATE ".$baseDatosServicios.".configuracion SET conf_color_borde='" . $_POST["color_borde"] . "', conf_color_encabezado='" . $_POST["color_encabezado"] . "',conf_tam_borde=" . $_POST["tborde"] . " WHERE conf_id='".$config['conf_id']."';");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="config-reporte.php";</script>';
	exit();
}

//CREAR INGRESOS-EGRESOS
if ($_POST["id"] == 19) {
	//COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
	if (trim($_POST["fechaIE"]) == "" or trim($_POST["detalleIE"]) == "" or trim($_POST["valorIE"]) == "" or trim($_POST["tipoIE"]) == "") {
		include("../compartido/guardar-historial-acciones.php");
		echo "<span style='font-family:Arial; color:red;'>Debe llenar todos los campos.</samp>";
		exit();
	}

	try{
		mysqli_query($conexion, "INSERT INTO finanzas_ingresos_egresos(ieg_fecha, ieg_detalle, ieg_valor, ieg_tipo, ieg_observaciones) VALUES('" . $_POST["fechaIE"] . "','" . $_POST["detalleIE"] . "'," . $_POST["valorIE"] . "," . $_POST["tipoIE"] . ",'" . $_POST["obsIE"] . "');");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//CREAR INGRESOS-EGRESOS
if ($_POST["id"] == 20) {
	//COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
	if (trim($_POST["fechaIE"]) == "" or trim($_POST["detalleIE"]) == "" or trim($_POST["valorIE"]) == "" or trim($_POST["tipoIE"]) == "") {
		include("../compartido/guardar-historial-acciones.php");
		echo "<span style='font-family:Arial; color:red;'>Debe llenar todos los campos.</samp>";
		exit();
	}

	try{
		mysqli_query($conexion, "UPDATE finanzas_ingresos_egresos SET ieg_fecha='" . $_POST["fechaIE"] . "', ieg_detalle='" . $_POST["detalleIE"] . "', ieg_valor=" . $_POST["valorIE"] . ", ieg_tipo=" . $_POST["tipoIE"] . ", ieg_observaciones='" . $_POST["obsIE"] . "' WHERE ieg_id=" . $_POST["id_IE"] . ";");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//ACTUALIZAR CONFIGURACION INSTITUCION
if ($_POST["id"] == 21) {
	try{
		mysqli_query($conexion, "UPDATE ".$baseDatosServicios.".configuracion SET conf_periodo=" . $_POST["periodoActualC"] . ", conf_nota_desde=" . $_POST["notaMinC"] . ", conf_nota_hasta=" . $_POST["notaMaxC"] . ", conf_nota_minima_aprobar=" . $_POST["notaMinAprobarC"] . ", conf_color_perdida='" . $_POST["colorNotasPC"] . "', conf_color_ganada='" . $_POST["colorNotasGC"] . "',conf_pie='" . $_POST["configPie"] . "', conf_num_materias_perder_ano=" . $_POST["numMateriasMinRC"] . ", conf_ini_matrucula='" . $_POST["iniciomatC"] . "', conf_fin_matricul='" . $_POST["finmatC"] . "', conf_apertura_academica='" . $_POST["aperturaacademicaAC"] . "', conf_clausura_academica='" . $_POST["clausuraacademicaAC"] . "'
		WHERE conf_id=" . $_POST["id_IC"] . ";");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="configuracion-institucion.php";</script>';
	exit();
}
//ACTUALIZAR INFORMACION INSTITUCION
if ($_POST["id"] == 22) {
	//COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
	if (trim($_POST["rectorI"]) == "" or trim($_POST["secretarioI"]) == "" or trim($_POST["nitI"]) == "" or trim($_POST["nomInstI"]) == "" or trim($_POST["direccionI"]) == "" or trim($_POST["telI"]) == "" or trim($_POST["calseI"]) == "" or trim($_POST["caracterI"]) == "" or trim($_POST["calendarioI"]) == "" or trim($_POST["jornadaI"]) == "" or trim($_POST["horarioI"]) == "" or trim($_POST["nivelesI"]) == "" or trim($_POST["modalidadI"]) == "" or trim($_POST["propietarioI"]) == "" or trim($_POST["coordinadorI"]) == "" or trim($_POST["tesoreroI"]) == "") {
		include("../compartido/guardar-historial-acciones.php");
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

	try{
		mysqli_query($conexion, "UPDATE ".$baseDatosServicios.".general_informacion SET info_rector='" . $_POST["rectorI"] . "', info_secretaria_academica='" . $_POST["secretarioI"] . "', info_logo='" . $archivo . "', info_nit='" . $_POST["nitI"] . "', info_nombre='" . $_POST["nomInstI"] . "', info_direccion='" . $_POST["direccionI"] . "', info_telefono='" . $_POST["telI"] . "', info_clase='" . $_POST["calseI"] . "', info_caracter='" . $_POST["caracterI"] . "',info_calendario='" . $_POST["calendarioI"] . "', info_jornada='" . $_POST["jornadaI"] . "', info_horario='" . $_POST["horarioI"] . "', info_niveles='" . $_POST["nivelesI"] . "', info_modalidad='" . $_POST["modalidadI"] . "', info_propietario='" . $_POST["propietarioI"] . "', info_coordinador_academico='" . $_POST["coordinadorI"] . "', info_tesorero='" . $_POST["tesoreroI"] . "'
		WHERE info_id=" . $_POST["idCI"] . ";");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//GUARDAR MATRICULA CONDICIONAL
if ($_POST["id"] == 23) {
	//COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
	if (trim($_POST["fecha"]) == "" or trim($_POST["codigo"]) == "" or trim($_POST["obsMC"]) == "") {
		include("../compartido/guardar-historial-acciones.php");
		echo "<span style='font-family:Arial; color:red;'>Debe llenar todos los campos.</samp>";
		exit();
	}
	try{
		mysqli_query($conexion, "INSERT INTO disciplina_matricula_condicional(cond_fecha, cond_estudiante, cond_observacion, cond_usuario)VALUES('" . $_POST["fecha"] . "','" . $_POST["codigo"] . "','" . $_POST["obsMC"] . "','" . $_SESSION["id"] . "')");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="disiplina-matricula-condicional-lista.php";</script>';
	exit();
}
//MODIFICAR REPORTE
if ($_POST["id"] == 27) {
	//COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
	if (trim($_POST["fecha"]) == "" or trim($_POST["falta"]) == "" or trim($_POST["tipo"]) == "") {
		include("../compartido/guardar-historial-acciones.php");
		echo "<span style='font-family:Arial; color:red;'>Debe llenar todos los campos.</samp>";
		exit();
	}

	try{
		mysqli_query($conexion, "UPDATE disciplina_reportes SET dr_fecha='" . $_POST["fecha"] . "', dr_falta='" . $_POST["falta"] . "', dr_tipo=" . $_POST["tipo"] . " WHERE dr_id=" . $_POST["idR"] . "");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//CREAR AREAS
if ($_POST["id"] == 28) {
	//COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
	if (trim($_POST["nombreA"]) == "" or trim($_POST["posicionA"]) == "") {
		include("../compartido/guardar-historial-acciones.php");
		echo "<span style='font-family:Arial; color:red;'>Debe llenar todos los campos.</samp>";
		exit();
	}
	try{
		mysqli_query($conexion, "INSERT INTO academico_areas (ar_nombre,ar_posicion)VALUES('" . $_POST["nombreA"] . "'," . $_POST["posicionA"] . ");");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//MODIFICAR AREAS
if ($_POST["id"] == 29) {
	//COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
	if (trim($_POST["nombreA"]) == "" or trim($_POST["posicionA"]) == "") {
		include("../compartido/guardar-historial-acciones.php");
		echo "<span style='font-family:Arial; color:red;'>Debe llenar todos los campos.</samp>";
		exit();
	}
	try{
		mysqli_query($conexion, "UPDATE academico_areas SET ar_nombre='" . $_POST["nombreA"] . "', ar_posicion=" . $_POST["posicionA"] . " WHERE ar_id=" . $_POST["idA"] . ";");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//CREAR MATERIAS
if ($_POST["id"] == 30) {
	//COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
	if (trim($_POST["codigoM"]) == "" or trim($_POST["nombreM"]) == "" or trim($_POST["siglasM"]) == "" or trim($_POST["areaM"]) == "") {
		include("../compartido/guardar-historial-acciones.php");
		echo "<span style='font-family:Arial; color:red;'>Debe llenar todos los campos.</samp>";
		exit();
	}
	try{
		mysqli_query($conexion, "INSERT INTO academico_materias(mat_codigo, mat_nombre, mat_siglas, mat_area, mat_oficial) VALUES ('" . $_POST["codigoM"] . "','" . $_POST["nombreM"] . "','" . $_POST["siglasM"] . "','" . $_POST["areaM"] . "',1);");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//MODIFICAR MATERIAS
if ($_POST["id"] == 31) {
	//COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
	try{
		mysqli_query($conexion, "UPDATE academico_materias SET mat_codigo='" . $_POST["codigoM"] . "', mat_nombre='" . $_POST["nombreM"] . "', mat_siglas='" . $_POST["siglasM"] . "', mat_area=" . $_POST["areaM"] . ", mat_oficial=1 WHERE mat_id=" . $_POST["idM"] . ";");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//GUARDAR AUTOCONOCIMIENTO
if ($_POST["id"] == 34) {
	//Me gusta
	$preferencia = $_POST["gusto"];
	$longitud = count($preferencia);
	for ($i = 0; $i < $longitud; $i++) {
		try{
			$consultaNumPref=mysqli_query($conexion, "SELECT * FROM social_preferencias_usuarios WHERE preu_usuario='" . $_SESSION["id"] . "' AND preu_gusto=1 AND preu_preferencia='" . $preferencia[$i] . "'");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
		$nPref = mysqli_num_rows($consultaNumPref);
		if ($nPref == 0) {
			try{
				mysqli_query($conexion, "INSERT INTO social_preferencias_usuarios(preu_usuario, preu_preferencia, preu_gusto)VALUES('" . $_SESSION["id"] . "','" . $preferencia[$i] . "',1)");
			} catch (Exception $e) {
				include("../compartido/error-catch-to-report.php");
			}
			
		}
	}
	if (trim($_POST["gustoAdicional"]) != "") {
		try{
			$cons = mysqli_query($conexion, "SELECT lower(prel_nombre) FROM social_preferencias_lista WHERE lower(prel_nombre)='" . strtolower($_POST["gustoAdicional"]) . "' LIMIT 0,1");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
		
		$numGustoAdicional = mysqli_num_rows($cons);
		$dat = mysqli_fetch_array($cons, MYSQLI_BOTH);
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
			try{
				mysqli_query($conexion, "INSERT INTO social_preferencias_lista(prel_nombre, prel_guardado, prel_fecha)VALUES('" . $_POST["gustoAdicional"] . "',2,now())");
			} catch (Exception $e) {
				include("../compartido/error-catch-to-report.php");
			}
			$idInsercion = mysqli_insert_id($conexion);
			
			try{
				mysqli_query($conexion, "INSERT INTO social_preferencias_usuarios(preu_usuario, preu_preferencia, preu_gusto)VALUES('" . $_SESSION["id"] . "','" . $idInsercion . "',1)");
			} catch (Exception $e) {
				include("../compartido/error-catch-to-report.php");
			}
		}
	}

	// No me gusta
	$preferencia = $_POST["nogusto"];
	$longitud = count($preferencia);
	for ($i = 0; $i < $longitud; $i++) {
		try{
			$consultaNumPref=mysqli_query($conexion, "SELECT * FROM social_preferencias_usuarios WHERE preu_usuario='" . $_SESSION["id"] . "' AND preu_no_gusto=1 AND preu_preferencia='" . $preferencia[$i] . "'");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
		$nPref = mysqli_num_rows($consultaNumPref);
		if ($nPref == 0) {
			try{
				mysqli_query($conexion, "INSERT INTO social_preferencias_usuarios(preu_usuario, preu_preferencia, preu_no_gusto)VALUES('" . $_SESSION["id"] . "','" . $preferencia[$i] . "',1)");
			} catch (Exception $e) {
				include("../compartido/error-catch-to-report.php");
			}
		}
	}

	// Conocimiento
	$preferencia = $_POST["conocimiento"];
	$longitud = count($preferencia);
	for ($i = 0; $i < $longitud; $i++) {
		try{
			$consultaNumPref=mysqli_query($conexion, "SELECT * FROM social_preferencias_usuarios WHERE preu_usuario='" . $_SESSION["id"] . "' AND preu_conocimiento=1 AND preu_preferencia='" . $preferencia[$i] . "'");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
		$nPref = mysqli_num_rows($consultaNumPref);
		if ($nPref == 0) {
			try{
				mysqli_query($conexion, "INSERT INTO social_preferencias_usuarios(preu_usuario, preu_preferencia, preu_conocimiento)VALUES('" . $_SESSION["id"] . "','" . $preferencia[$i] . "',1)");
			} catch (Exception $e) {
				include("../compartido/error-catch-to-report.php");
			}			
		}
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//ASIGNAR EVALUACIONES
if ($_POST["id"] == 37) {
	if (trim($_POST["eva"]) == "" or trim($_POST["curso"]) == "" or trim($_POST["grupo"]) == "") {
		include("../compartido/guardar-historial-acciones.php");
		echo "<span style='font-family:Arial; color:red;'>Debe llenar todos los campos.<br>
			<a href='javascript:history.go(-1)'>[Volver al formulario]</a></samp>";
		exit();
	}
	$numero = (count($_POST["usuario"]));
	$contador = 0;
	while ($contador < $numero) {
		try{
			mysqli_query($conexion, "INSERT INTO ".$baseDatosServicios.".general_evaluacion_asignar(epag_id_evaluacion, epag_curso, epag_grupo, epag_usuario, epag_institucion, epag_year)VALUES('" . $_POST["eva"] . "','" . $_POST["curso"] . "','" . $_POST["grupo"] . "','" . $_POST["usuario"][$contador] . "','".$config['conf_id_institucion']."','".$_SESSION["bd"]."')");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
		$contador++;
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//GUARDAR EVALUACIONES GENERALES
if ($_POST["id"] == 38) {
	if (trim($_POST["titulo"]) == "" or trim($_POST["contenido"]) == "") {
		include("../compartido/guardar-historial-acciones.php");
		echo "<span style='font-family:Arial; color:red;'>Debe llenar todos los campos.<br>
			<a href='javascript:history.go(-1)'>[Volver al formulario]</a></samp>";
		exit();
	}
	try{
		mysqli_query($conexion, "INSERT INTO ".$baseDatosServicios.".general_evaluaciones(evag_nombre, evag_descripcion, evag_fecha, evag_creada, evag_institucion, evag_year)VALUES('" . $_POST["titulo"] . "','" . $_POST["contenido"] . "',now(),'" . $_SESSION["id"] . "','" . $config['conf_id_institucion'] . "','" . $_SESSION["bd"] . "')");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//ACTUALIZAR EVALUACIONES GENERALES
if ($_POST["id"] == 39) {
	if (trim($_POST["titulo"]) == "" or trim($_POST["contenido"]) == "") {
		include("../compartido/guardar-historial-acciones.php");
		echo "<span style='font-family:Arial; color:red;'>Debe llenar todos los campos.<br>
			<a href='javascript:history.go(-1)'>[Volver al formulario]</a></samp>";
		exit();
	}
	try{
		mysqli_query($conexion, "UPDATE ".$baseDatosServicios.".general_evaluaciones SET evag_nombre='" . $_POST["titulo"] . "', evag_descripcion='" . $_POST["contenido"] . "', evag_editada='" . $_SESSION["id"] . "' WHERE evag_id='" . $_POST["idN"] . "'");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="general-evaluacion.php#N' . $_POST["idN"] . '";</script>';
	exit();
}
//GUARDAR PREGUNTAS GENERALES
if ($_POST["id"] == 40) {
	if (trim($_POST["contenido"]) == "") {
		include("../compartido/guardar-historial-acciones.php");
		echo "<span style='font-family:Arial; color:red;'>Debe llenar todos los campos.<br>
			<a href='javascript:history.go(-1)'>[Volver al formulario]</a></samp>";
		exit();
	}
	try{
		mysqli_query($conexion, "INSERT INTO ".$baseDatosServicios.".general_preguntas(pregg_descripcion, pregg_id_evaluacion, pregg_institucion, pregg_year)VALUES('" . $_POST["contenido"] . "','" . $_POST["eva"] . "','" . $config['conf_id_institucion'] . "','" . $_SESSION["bd"] . "')");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="general-preguntas.php?eva=' . $_POST["eva"] . '";</script>';
	exit();
}
//ACTUALIZAR PREGUNTAS GENERALES
if ($_POST["id"] == 41) {
	if (trim($_POST["contenido"]) == "") {
		include("../compartido/guardar-historial-acciones.php");
		echo "<span style='font-family:Arial; color:red;'>Debe llenar todos los campos.</samp>";
		exit();
	}
	try{
		mysqli_query($conexion, "UPDATE ".$baseDatosServicios.".general_preguntas SET pregg_descripcion='" . $_POST["contenido"] . "' WHERE pregg_id='" . $_POST["idN"] . "'");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="general-preguntas.php?eva=' . $_POST["eva"] . '";</script>';
	exit();
}
//GUARDAR RESPUESTAS GENERALES
if ($_POST["id"] == 42) {
	if (trim($_POST["respuesta"]) == "") {
		include("../compartido/guardar-historial-acciones.php");
		echo "<span style='font-family:Arial; color:red;'>Debe llenar todos los campos.</samp>";
		exit();
	}
	try{
		mysqli_query($conexion, "INSERT INTO ".$baseDatosServicios.".general_respuestas(resg_descripcion, resg_id_pregunta, resg_institucion, resg_year)VALUES('" . $_POST["respuesta"] . "','" . $_POST["idPregunta"] . "','".$config['conf_id_institucion']."','".$_SESSION["bd"]."')");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="general-preguntas.php?eva=' . $_POST["eva"] . '";</script>';
	exit();
}
//ASIGNAR ACUDIDOS A LOS ACUDIENTES
if ($_POST["id"] == 43) {
	$numero = (count($_POST["acudidos"]));
	$contador = 0;
	while ($contador < $numero) {
		try{
			mysqli_query($conexion, "DELETE FROM usuarios_por_estudiantes WHERE upe_id_usuario='" . $_POST["acudiente"] . "' AND upe_id_estudiante='" . $_POST["acudidos"][$contador] . "'");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
		
		try{
			mysqli_query($conexion, "INSERT INTO usuarios_por_estudiantes(upe_id_usuario, upe_id_estudiante)VALUES('" . $_POST["acudiente"] . "','" . $_POST["acudidos"][$contador] . "')");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
		
		try{
			mysqli_query($conexion, "UPDATE academico_matriculas SET mat_acudiente='" . $_POST["acudiente"] . "' WHERE mat_id='" . $_POST["acudidos"][$contador] . "'");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
		$contador++;
	}

	include("../compartido/guardar-historial-acciones.php");
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

	try{
		mysqli_query($conexion, "INSERT INTO autorizados (aut_documento, aut_nombre, aut_foto, aut_estudiante, aut_estado, aut_parentezco)VALUES('" . $_POST["documento"] . "','" . $_POST["nombre"] . "','" . $nombre . "','" . $_POST["estudiante"] . "','" . $_POST["estado"] . "','" . $_POST["parentezco"] . "')");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}
	$idIte = mysqli_insert_id($conexion);
	
	include("../compartido/guardar-historial-acciones.php");
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
		try{
			$consultaRefoto=mysqli_query($conexion, "SELECT aut_foto FROM autorizados WHERE aut_id='" . $_POST["idaut"] . "'");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
		$refoto = mysqli_fetch_array($consultaRefoto, MYSQLI_BOTH);
		//@unlink("../../files/fotos/".$refoto["aut_foto"]."");
		$conextra = ", aut_foto='" . $nombre . "'";
	}
	try{
		mysqli_query($conexion, "UPDATE autorizados SET aut_documento='" . $_POST["documento"] . "', aut_nombre='" . $_POST["nombre"] . "' " . $conextra . ", aut_estudiante='" . $_POST["estudiante"] . "', aut_estado='" . $_POST["estado"] . "', aut_parentezco='" . $_POST["parentezco"] . "' WHERE aut_id='" . $_POST["idaut"] . "'");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="usuarios-autorizados.php"</script>';
	exit();
}
//CREAR OPCION GENERALS
if ($_POST["id"] == 46) {
	try{
		mysqli_query($conexion, "INSERT INTO ".$baseDatosServicios.".opciones_generales (ogen_nombre, ogen_grupo)VALUES('" . $_POST["nombre"] . "','" . $_POST["grupo"] . "')");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}
	$idIte = mysqli_insert_id($conexion);

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="configuracion-opciones-generales.php"</script>';
	exit();
}
//EDITAR OPCION GENERALS
if ($_POST["id"] == 47) {
	try{
		mysqli_query($conexion, "UPDATE ".$baseDatosServicios.".opciones_generales SET ogen_nombre='" . $_POST["nombre"] . "', ogen_grupo='" . $_POST["grupo"] . "' WHERE ogen_id='" . $_POST["idogen"] . "'");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="configuracion-opciones-generales.php"</script>';
	exit();
}
//CREAR OBSERVACIÓN
if ($_POST["id"] == 48) {
	try{
		mysqli_query($conexion, "INSERT INTO observacion_docente (obs_fecha, obs_docente, obs_director, obs_descripcion)VALUES(now(),'" . $_POST["idDoc"] . "','" . $_SESSION["id"] . "','" . $_POST["descripcion"] . "')");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="observacion-docente.php?idDoc=' . $_POST["idDoc"] . '"</script>';
	exit();
}
//CREAR AUSENCIA
if ($_POST["id"] == 49) {
	try{
		mysqli_query($conexion, "INSERT INTO ausencia_docente (aus_fecha, aus_docente, aus_director, aus_fech_ini, aus_fech_fin, aus_motivo, aus_observacion)VALUES(now(),'" . $_POST["idDoc"] . "','" . $_SESSION["id"] . "','" . $_POST["fIni"] . "','" . $_POST["fFin"] . "','" . $_POST["motivo"] . "','" . $_POST["observacion"] . "')");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="ausencia-docente.php?idDoc=' . $_POST["idDoc"] . '"</script>';
	exit();
}
//GENERAR COBRO MASIVO 
if ($_POST["id"] == 50) {
	if (trim($_POST["grado"]) == "" or trim($_POST["fecha"]) == "" or trim($_POST["detalle"]) == "") {
		include("../compartido/guardar-historial-acciones.php");
		echo "<span style='font-family:Arial; color:red;'>Debe llenar todos los campos.<br>
			<a href='javascript:history.go(-1)'>[Volver al formulario]</a></samp>";
		exit();
	}
	try{
		$consultaValor=mysqli_query($conexion, "SELECT * FROM finanzas_cobros_masivos WHERE mas_id='" . $_POST["detalle"] . "'");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}
	$cValor = mysqli_fetch_array($consultaValor, MYSQLI_BOTH);
	$valor = $cValor[2];
	$detalle = $cValor[1];
	try{
		$consulta = mysqli_query($conexion, "SELECT * FROM academico_matriculas WHERE mat_grado='" . $_POST["grado"] . "'");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}
	while ($datosE = mysqli_fetch_array($consulta, MYSQLI_BOTH)) {
		try{
			mysqli_query($conexion, "INSERT INTO finanzas_cuentas(fcu_fecha, fcu_detalle, fcu_valor, fcu_tipo, fcu_observaciones, fcu_usuario, fcu_anulado, fcu_forma_pago, fcu_cerrado)VALUES('" . $_POST["fecha"] . "','" . $detalle . "','" . $valor . "','" . $_POST["tipo"] . "','" . $_POST["obs"] . "','" . $datosE["mat_id_usuario"] . "',0,5,0)");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}		
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//CREAR COBROS MASIVOS
if ($_POST["id"] == 52) {
	try{
		mysqli_query($conexion, "INSERT INTO finanzas_cobros_masivos (mas_nombre, mas_valor)VALUES('" . $_POST["nombre"] . "','" . $_POST["costo"] . "')");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '"</script>';
	exit();
}
//EDITAR COBROS MASIVOS
if ($_POST["id"] == 53) {
	try{
		mysqli_query($conexion, "UPDATE finanzas_cobros_masivos SET mas_nombre='" . $_POST["nombre"] . "', mas_valor='" . $_POST["costo"] . "' WHERE mas_id='" . $_POST["idMas"] . "'");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '"</script>';
	exit();
}
//EDITAR INDICADORES DE LOS DOCENTES
if ($_POST["id"] == 56) {
	include("verificar-carga.php");
	//include("verificar-periodos-diferentes.php");
	try{
		$consultaSumaIndicadores=mysqli_query($conexion, "SELECT
		(SELECT sum(ipc_valor) FROM academico_indicadores_carga 
		WHERE ipc_carga='" . $cargaConsultaActual . "' AND ipc_periodo='" . $periodoConsultaActual . "' AND ipc_creado=0),
		(SELECT sum(ipc_valor) FROM academico_indicadores_carga 
		WHERE ipc_carga='" . $cargaConsultaActual . "' AND ipc_periodo='" . $periodoConsultaActual . "' AND ipc_creado=1),
		(SELECT count(*) FROM academico_indicadores_carga 
		WHERE ipc_carga='" . $cargaConsultaActual . "' AND ipc_periodo='" . $periodoConsultaActual . "' AND ipc_creado=1)");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}
	$sumaIndicadores = mysqli_fetch_array($consultaSumaIndicadores, MYSQLI_BOTH);
	$porcentajePermitido = 100 - $sumaIndicadores[0];
	$porcentajeRestante = ($porcentajePermitido - $sumaIndicadores[1]);
	$porcentajeRestante = ($porcentajeRestante + $_POST["valorIndicador"]);

	try{
		mysqli_query($conexion, "UPDATE academico_indicadores SET ind_nombre='" . $_POST["contenido"] . "' WHERE ind_id='" . $_POST["idInd"] . "'");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	//Si vamos a relacionar los indicadores con los SABERES
	if ($datosCargaActual['car_saberes_indicador'] == 1) {
		try{
			mysqli_query($conexion, "UPDATE academico_indicadores_carga SET ipc_evaluacion='" . $_POST["saberes"] . "' WHERE ipc_id='" . $_POST["idR"] . "'");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
	}

	//Para los DIRECTIVOS los valores de los indicadores son de forma manual
	if (!is_numeric($_POST["valor"])) {
		$_POST["valor"] = 1;
	}
	//Si el valor es mayor al adecuado lo ajustamos al porcentaje restante; Siempre que este último sea mayor a 0.
	if ($_POST["valor"] > $porcentajeRestante and $porcentajeRestante > 0) {
		$_POST["valor"] = $porcentajeRestante;
	}

	try{
		mysqli_query($conexion, "UPDATE academico_indicadores_carga SET ipc_valor='" . $_POST["valor"] . "', ipc_creado='" . $_POST["creado"] . "' WHERE ipc_id='" . $_POST["idR"] . "'");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="cargas-indicadores.php?carga=' . $_GET["carga"] . '&docente=' . $_GET["docente"] . '";</script>';
	exit();
}
//AGREGAR INDICADORES
if ($_POST["id"] == 57) {
	include("verificar-carga.php");
	//include("verificar-periodos-diferentes.php");
	try{
		$consultaSumaIndicadores=mysqli_query($conexion, "SELECT
		(SELECT sum(ipc_valor) FROM academico_indicadores_carga 
		WHERE ipc_carga='" . $cargaConsultaActual . "' AND ipc_periodo='" . $periodoConsultaActual . "' AND ipc_creado=0),
		(SELECT sum(ipc_valor) FROM academico_indicadores_carga 
		WHERE ipc_carga='" . $cargaConsultaActual . "' AND ipc_periodo='" . $periodoConsultaActual . "' AND ipc_creado=1),
		(SELECT count(*) FROM academico_indicadores_carga 
		WHERE ipc_carga='" . $cargaConsultaActual . "' AND ipc_periodo='" . $periodoConsultaActual . "' AND ipc_creado=1)");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}
	$sumaIndicadores = mysqli_fetch_array($consultaSumaIndicadores, MYSQLI_BOTH);
	
	$porcentajePermitido = 100 - $sumaIndicadores[0];
	$porcentajeRestante = ($porcentajePermitido - $sumaIndicadores[1]);

	if ($sumaIndicadores[2] >= $datosCargaActual['car_maximos_indicadores']) {
		include("../compartido/guardar-historial-acciones.php");
		echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=209";</script>';
		exit();
	}


	try{
		mysqli_query($conexion, "INSERT INTO academico_indicadores(ind_nombre, ind_obligatorio, ind_publico) VALUES('" . $_POST["contenido"] . "', '" . $_POST["creado"] . "', 0)");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}
	$idRegistro = mysqli_insert_id($conexion);
	//Si decide poner los valores porcentuales de los indicadores de forma manual
	if ($datosCargaActual['car_valor_indicador'] == 1) {
		if ($porcentajeRestante <= 0) {
			include("../compartido/guardar-historial-acciones.php");
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
		try{
			mysqli_query($conexion, "INSERT INTO academico_indicadores_carga(ipc_carga, ipc_indicador, ipc_valor, ipc_periodo, ipc_creado, ipc_evaluacion)
			VALUES('" . $cargaConsultaActual . "', '" . $idRegistro . "', '" . $_POST["valor"] . "', '" . $periodoConsultaActual . "', 1, '" . $_POST["saberes"] . "')");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
	}
	//El sistema reparte los porcentajes automáticamente y equitativamente.
	else {
		$valorIgualIndicador = ($porcentajePermitido / ($sumaIndicadores[2] + 1));
		try{
			mysqli_query($conexion, "INSERT INTO academico_indicadores_carga(ipc_carga, ipc_indicador, ipc_periodo, ipc_creado, ipc_evaluacion)
			VALUES('" . $cargaConsultaActual . "', '" . $idRegistro . "', '" . $periodoConsultaActual . "', 1, '" . $_POST["saberes"] . "')");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
		//Actualiza todos valores de la misma carga y periodo; incluyendo el que acaba de crear.
		try{
			mysqli_query($conexion, "UPDATE academico_indicadores_carga SET ipc_valor='" . $valorIgualIndicador . "' WHERE ipc_carga='" . $cargaConsultaActual . "' AND ipc_periodo='" . $periodoConsultaActual . "' AND ipc_creado=1");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
	}




	//Si las calificaciones son de forma automática.
	if ($datosCargaActual['car_configuracion'] == 0) {
		//Repetimos la consulta de los indicadores porque los valores fueron actualizados
		try{
			$indicadoresConsultaActualizado = mysqli_query($conexion, "SELECT * FROM academico_indicadores_carga 
			WHERE ipc_carga='" . $cargaConsultaActual . "' AND ipc_periodo='" . $periodoConsultaActual . "' AND ipc_creado=1");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
		//Actualizamos todas las actividades por cada indicador
		while ($indicadoresDatos = mysqli_fetch_array($indicadoresConsultaActualizado, MYSQLI_BOTH)) {
			try{
				$consultaNumActividades=mysqli_query($conexion, "SELECT * FROM academico_actividades 
				WHERE act_id_tipo='" . $indicadoresDatos['ipc_indicador'] . "' AND act_periodo='" . $periodoConsultaActual . "' AND act_id_carga='" . $cargaConsultaActual . "' AND act_estado=1");
			} catch (Exception $e) {
				include("../compartido/error-catch-to-report.php");
			}
			$actividadesNum = mysqli_num_rows($consultaNumActividades);
			//Si hay actividades relacionadas al indicador, actualizamos su valor.
			if ($actividadesNum > 0) {
				$valorIgualActividad = ($indicadoresDatos['ipc_valor'] / $actividadesNum);
				try{
					mysqli_query($conexion, "UPDATE academico_actividades SET act_valor='" . $valorIgualActividad . "' 
					WHERE act_id_tipo='" . $indicadoresDatos['ipc_indicador'] . "' AND act_periodo='" . $periodoConsultaActual . "' AND act_id_carga='" . $cargaConsultaActual . "' AND act_estado=1");
				} catch (Exception $e) {
					include("../compartido/error-catch-to-report.php");
				}
			}
		}
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="cargas-indicadores.php?carga=' . base64_encode($cargaConsultaActual) . '&periodo=' . base64_encode($periodoConsultaActual) . '&docente=' . $_GET["docente"] . '";</script>';
	exit();
}
//ACTUALIZAR CATEGORÍAS FALTAS
if ($_POST["id"] == 58) {
	try{
		mysqli_query($conexion, "UPDATE disciplina_categorias SET dcat_nombre='" . $_POST["categoria"] . "' WHERE dcat_id=" . $_POST["idR"] . ";");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="disciplina-categorias-editar.php?success=SC_DT_2&idR='.base64_encode($_POST["idR"]).'&id='.base64_encode($_POST["idR"]).'";</script>';
	exit();
}
//ACTUALIZAR FALTAS
if ($_POST["id"] == 59) {
	try{
		mysqli_query($conexion, "UPDATE disciplina_faltas SET dfal_codigo='" . $_POST["codigo"] . "', dfal_nombre='" . $_POST["nombre"] . "', dfal_id_categoria='" . $_POST["categoria"] . "' 
		WHERE dfal_id='" . $_POST["idR"] . "'");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="disciplina-faltas-editar.php?success=SC_DT_2&idR='.base64_encode($_POST["idR"]).'&id='.base64_encode($_POST["idR"]).'";</script>';
	exit();
}
//AGREGAR FALTAS
if ($_POST["id"] == 60) {
	try{
		mysqli_query($conexion, "INSERT INTO disciplina_faltas(dfal_nombre, dfal_id_categoria, dfal_codigo)
		VALUES('" . $_POST["nombre"] . "', '" . $_POST["categoria"] . "', '" . $_POST["codigo"] . "')");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}
	$idRegistro=mysqli_insert_id($conexion);

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="disciplina-faltas.php?success=SC_DT_1&id='.base64_encode($idRegistro).'";</script>';
	exit();
}
//AGREGAR categoria
if ($_POST["id"] == 61) {
	try{
		mysqli_query($conexion, "INSERT INTO disciplina_categorias(dcat_nombre)
		VALUES('" . $_POST["categoria"] . "')");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}
	$idRegistro=mysqli_insert_id($conexion);

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="disciplina-categorias.php?success=SC_DT_1&id='.base64_encode($idRegistro).'";</script>';
	exit();
}
}
//========================================== GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET  GET GET GET GET GET GET GET GET GET GET GET GET GET ======================

if (!empty($_GET["get"])) {
//CAMBIAR DE ESTADO LAS NOTICIAS
if ($_GET["get"] == 1) {
	try{
		$consulta = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".social_noticias WHERE not_id='" . $_GET["id"] . "'");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}
	$resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH);
	if ($resultado[5] == 0) $estado = 1;
	else $estado = 0;
	try{
		mysqli_query($conexion, "UPDATE ".$baseDatosServicios.".social_noticias SET not_estado='" . $estado . "' WHERE not_id='" . $_GET["id"] . "'");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="noticias.php#N' . $_GET["id"] . '";</script>';
	exit();
}
//ELIMINAR NOTICIAS
if ($_GET["get"] == 2) {
	try{
		mysqli_query($conexion, "UPDATE ".$baseDatosServicios.".social_noticias SET not_estado=2 WHERE not_id='" . $_GET["id"] . "'");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//MOSTRAR TODAS MIS NOTICIAS
if ($_GET["get"] == 3) {
	try{
		mysqli_query($conexion, "UPDATE ".$baseDatosServicios.".social_noticias SET not_estado=1 WHERE not_usuario='" . $_SESSION["id"] . "' AND not_estado!=2");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//OCULTAR TODAS MIS NOTICIAS
if ($_GET["get"] == 4) {
	try{
		mysqli_query($conexion, "UPDATE ".$baseDatosServicios.".social_noticias SET not_estado=0 WHERE not_usuario='" . $_SESSION["id"] . "' AND not_estado!=2");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//ELIMINAR TODAS MIS NOTICIAS
if ($_GET["get"] == 5) {
	try{
		mysqli_query($conexion, "UPDATE ".$baseDatosServicios.".social_noticias SET not_estado=2 WHERE not_usuario='" . $_SESSION["id"] . "'");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//ELIMINAR USUARIOS
if ($_GET["get"] == 6) {
	try{
		mysqli_query($conexion, "DELETE FROM usuarios WHERE uss_id='".$_GET["id"]."' AND uss_tipo!=5");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="'.$_SERVER['HTTP_REFERER'].'";</script>';
	exit();
}


//ENVIAR SOLICITUD AMISTAD
if ($_GET["get"] == 7) {
	try{
		mysqli_query($conexion, "INSERT INTO social_amigos(ams_usuario, ams_amigo, ams_estado, ams_destacado)VALUES('" . $_SESSION["id"] . "', '" . $_GET["usuario"] . "', 0, 0)");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//MOSTRAR TODAS MIS NOTICIAS
if ($_GET["get"] == 8) {
	try{
		mysqli_query($conexion, "UPDATE ".$baseDatosServicios.".social_noticias SET not_estado=1 WHERE not_estado!=2");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//OCULTAR TODAS MIS NOTICIAS
if ($_GET["get"] == 9) {
	try{
		mysqli_query($conexion, "UPDATE ".$baseDatosServicios.".social_noticias SET not_estado=0 WHERE not_estado!=2");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//ELIMINAR TODAS MIS NOTICIAS
if ($_GET["get"] == 10) {
	try{
		mysqli_query($conexion, "UPDATE ".$baseDatosServicios.".social_noticias SET not_estado=2");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//ANULAR MOVIMIENTO
if (base64_decode($_GET["get"]) == 11) {
	try{
		mysqli_query($conexion, "UPDATE finanzas_cuentas SET fcu_anulado=1 WHERE fcu_id='" . base64_decode($_GET["idR"]) . "'");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="movimientos.php?id=' . $_GET["id"] . '";</script>';
	exit();
}
//ELIMINAR REPORTE
if ($_GET["get"] == 12) {
	try{
		mysqli_query($conexion, "DELETE FROM disciplina_reportes WHERE dr_id='" . $_GET["idR"] . "'");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//DESHABILITAR INGRESO-EGRESO
if ($_GET["get"] == 14) {
	try{
		mysqli_query($conexion, "UPDATE finanzas_cuentas SET fcu_anulado=1 WHERE fcu_id='" . $_GET["id"] . "';");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//CANCELAR MATRICULA CONDICIONAL
if ($_GET["get"] == 15) {
	try{
		mysqli_query($conexion, "UPDATE disciplina_matricula_condicional SET cond_estado=0 WHERE cond_id=" . $_GET["idMC"] . ";");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//BLOQUEAR O DESBLOQUEAR UN USUARIO
if (base64_decode($_GET["get"]) == 17) {
	if (base64_decode($_GET["lock"]) == 1) $estado = 0;
	else $estado = 1;
	try{
		mysqli_query($conexion, "UPDATE usuarios SET uss_bloqueado='" . $estado . "' WHERE uss_id='" . base64_decode($_GET["idR"]) . "'");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo $estado;
	exit();
}
//ME GUSTA MURO
if ($_GET["get"] == 19) {
	include("../modelo/conexion-admon.php");
	try{
		$consulta = mysqli_query($conexion, "SELECT * FROM social_muro_acciones WHERE mpa_muro='" . $_GET["muro"] . "' AND mpa_usuario='" . $_SESSION["id"] . "' AND mpa_institucion='" . $_GET["i"] . "' AND mpa_accion='" . $_GET["ac"] . "'");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}
	if ($num = mysqli_num_rows($consulta) == 0) {
		try{
			mysqli_query($conexion, "INSERT INTO social_muro_acciones(mpa_muro, mpa_usuario, mpa_fecha, mpa_accion, mpa_institucion)VALUES('" . $_GET["muro"] . "','" . $_SESSION["id"] . "',now(),'" . $_GET["ac"] . "','" . $_GET["i"] . "')");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="social-muro.php#M' . $_GET["muro"] . '";</script>';
	exit();
}
//ELIMINAR RESPUESTAS GENERALES
if ($_GET["get"] == 21) {
	try{
		mysqli_query($conexion, "DELETE FROM ".$baseDatosServicios.".general_respuestas WHERE resg_id=" . $_GET["idN"] . ";");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="general-preguntas.php?eva=' . $_GET["eva"] . '";</script>';
	exit();
}
//ELIMINAR PREGUNTAS
if ($_GET["get"] == 22) {
	try{
		mysqli_query($conexion, "DELETE FROM ".$baseDatosServicios.".general_respuestas WHERE resg_id_pregunta=" . $_GET["idN"] . ";");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	try{
		mysqli_query($conexion, "DELETE FROM ".$baseDatosServicios.".general_preguntas WHERE pregg_id=" . $_GET["idN"] . ";");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="general-preguntas.php?eva=' . $_GET["eva"] . '";</script>';
	exit();
}
//ELIMINAR EVALUACIONES
if ($_GET["get"] == 23) {
	try{
		mysqli_query($conexion, "DELETE FROM ".$baseDatosServicios.".general_preguntas WHERE pregg_id_evaluacion=" . $_GET["idN"] . ";");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	try{
		mysqli_query($conexion, "DELETE FROM ".$baseDatosServicios.".general_evaluacion_asignar WHERE epag_id_evaluacion=" . $_GET["idN"] . ";");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	try{
		mysqli_query($conexion, "DELETE FROM ".$baseDatosServicios.".general_evaluaciones WHERE evag_id=" . $_GET["idN"] . ";");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//ELIMINAR EVALUACIONES
if ($_GET["get"] == 24) {
	try{
		mysqli_query($conexion, "DELETE FROM ".$baseDatosServicios.".general_evaluacion_asignar WHERE epag_id=" . $_GET["idN"] . ";");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="general-evaluacion.php";</script>';
	exit();
}
//ACTUALIZAR FOTO
if ($_GET["get"] == 25) {
	try{
		mysqli_query($conexion, "UPDATE usuarios SET uss_foto='default.png', uss_portada='banner-sintia.png'");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	try{
		mysqli_query($conexion, "UPDATE academico_matriculas SET mat_foto='default.png'");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//BLOQUEAR TODO
if ($_GET["get"] == 27) {
	try{
		mysqli_query($conexion, "UPDATE usuarios SET uss_bloqueado=1 WHERE uss_tipo!=5 AND uss_tipo!=1");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//DESBLOQUEAR TODO
if ($_GET["get"] == 28) {
	try{
		mysqli_query($conexion, "UPDATE usuarios SET uss_bloqueado=0");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//APAGAR SESION
if ($_GET["get"] == 29) {
	try{
		mysqli_query($conexion, "UPDATE usuarios SET uss_estado=0");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//APAGAR SESION
if ($_GET["get"] == 30) {
	try{
		mysqli_query($conexion, "UPDATE usuarios SET uss_estado=1");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//ACTUALIZAR CLAVE
if ($_GET["get"] == 31) {
	try {
		mysqli_query($conexion, "UPDATE usuarios SET uss_clave=SHA1('sintia1234')");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//ELIMINAR ACUDIDOS DE LOS ACUDIENTES
if ($_GET["get"] == 34) {
	try{
		mysqli_query($conexion, "DELETE FROM usuarios_por_estudiantes WHERE upe_id=" . $_GET["id"] . ";");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}
	try{
		mysqli_query($conexion, "UPDATE academico_matriculas SET mat_acudiente=null WHERE mat_id='" . $_GET["est"] . "'");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//ELIMINAR TODOS ACUDIDOS DE LOS ACUDIENTES
if ($_GET["get"] == 35) {
	try{
		mysqli_query($conexion, "DELETE FROM usuarios_por_estudiantes WHERE upe_id_usuario=" . $_GET["usr"] . ";");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}
	try{
		mysqli_query($conexion, "UPDATE academico_matriculas SET mat_acudiente=null WHERE mat_acudiente='" . $_GET["usr"] . "'");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//FORMATO 1
if ($_GET["get"] == 38) {
	try{
		mysqli_query($conexion, "UPDATE academico_grados SET gra_formato_boletin=1");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//PONER EN 0 LOS VALORES DE LA MATRICULA
if ($_GET["get"] == 39) {
	try{
		mysqli_query($conexion, "UPDATE academico_grados SET gra_valor_matricula=0");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//PONER EN 0 LOS VALORES DE LA PENSIÓN
if ($_GET["get"] == 40) {
	try{
		mysqli_query($conexion, "UPDATE academico_grados SET gra_valor_pension=0");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}

//CIERRE DE CAJA
if ($_GET["get"] == 44) {
	try{
		mysqli_query($conexion, "UPDATE finanzas_cuentas SET fcu_cerrado=1, fcu_fecha_cerrado=now(), fcu_cerrado_usuario='" . $_SESSION["id"] . "' WHERE fcu_tipo=1 AND fcu_anulado=0 AND fcu_forma_pago=1 AND fcu_cerrado=0");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//ELIMINAR AREAS
if ($_GET["get"] == 46) {
	try{
		mysqli_query($conexion, "DELETE FROM academico_areas WHERE ar_id=" . $_GET["id"] . ";");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//ELIMINAR MATERIAS
if ($_GET["get"] == 47) {
	try{
		mysqli_query($conexion, "DELETE FROM academico_materias WHERE mat_id=" . $_GET["id"] . ";");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//ELIMINAR USUARIOS AUTORIZADOS
if ($_GET["get"] == 48) {
	try{
		mysqli_query($conexion, "DELETE FROM autorizados WHERE aut_id='" . $_GET["idaut"] . "'");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="usuarios-autorizados.php";</script>';
	exit();
}
//ELIMINAR USUARIOS
if ($_GET["get"] == 49) {
	try{
		mysqli_query($conexion, "DELETE FROM usuarios WHERE uss_id='" . $_GET["iduss"] . "'");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//ELIMINAR OPCION GENERAL
if ($_GET["get"] == 50) {
	try{
		mysqli_query($conexion, "DELETE FROM ".$baseDatosServicios.".opciones_generales WHERE ogen_id='" . $_GET["idogen"] . "'");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="configuracion-opciones-generales.php";</script>';
	exit();
}
//ELIMINAR AUSENCIA
if ($_GET["get"] == 51) {
	try{
		mysqli_query($conexion, "DELETE FROM ausencia_docente WHERE aus_id='" . $_GET["idaus"] . "'");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="ausencia-docente.php?idDoc=' . $_GET["idDoc"] . '";</script>';
	exit();
}
//ELIMINAR OBSERVACIONES
if ($_GET["get"] == 52) {
	try{
		mysqli_query($conexion, "DELETE FROM observacion_docente WHERE obs_id='" . $_GET["idobs"] . "'");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="observacion-docente.php?idDoc=' . $_GET["idDoc"] . '";</script>';
	exit();
}
//ELIMINAR MOVIMIENTOS FINANCIEROS
if ($_GET["get"] == 53) {
	try{
		mysqli_query($conexion, "DELETE FROM finanzas_cuentas");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="finanzas-movimientos-lista.php";</script>';
	exit();
}
//PROMOCIONAR ESTUDIANTES
if ($_GET["get"] == 54) {
	try{
		mysqli_query($conexion, "UPDATE academico_matriculas SET mat_promocionado=0");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	try{
		$grados = mysqli_query($conexion, "SELECT * FROM academico_grados");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}
	while ($g = mysqli_fetch_array($grados, MYSQLI_BOTH)) {
		if ($g[7] != "") {
			try{
				mysqli_query($conexion, "UPDATE academico_matriculas SET mat_grado='" . $g[7] . "', mat_promocionado=1 WHERE mat_grado='" . $g[0] . "' AND mat_promocionado=0 AND mat_eliminado=0");
			} catch (Exception $e) {
				include("../compartido/error-catch-to-report.php");
			}
		}
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="estudiantes.php";</script>';
	exit();
}
//ELIMINAR COBRO MASIVO
if ($_GET["get"] == 55) {
	try{
		mysqli_query($conexion, "DELETE FROM finanzas_cobros_masivos WHERE mas_id='" . $_GET["idMas"] . "'");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//ELIMINAR NOTA NIVELACION
if ($_GET["get"] == 57) {
	try{
		mysqli_query($conexion, "DELETE FROM academico_nivelaciones WHERE niv_id='" . $_GET["idNiv"] . "'");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="estudiantes-nivelaciones-registrar.php?curso='.$_GET["curso"].'&grupo='.$_GET["grupo"].'";</script>';
	exit();
}
//ELIMINAR TODOS LOS ACUDIENTES
if ($_GET["get"] == 59) {
	try{
		mysqli_query($conexion, "DELETE FROM usuarios WHERE uss_tipo=3");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}
	try{
		mysqli_query($conexion, "DELETE FROM usuarios_por_estudiantes");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//DEVOLVER ESTUDIANTES
if ($_GET["get"] == 64) {
	try{
		mysqli_query($conexion, "UPDATE academico_matriculas SET mat_promocionado=0");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	try{
		$grados = mysqli_query($conexion, "SELECT * FROM academico_grados");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}
	while ($g = mysqli_fetch_array($grados, MYSQLI_BOTH)) {
		if ($g[10] != "") {
			try{
				mysqli_query($conexion, "UPDATE academico_matriculas SET mat_grado='" . $g[10] . "', mat_promocionado=0 WHERE mat_grado='" . $g[0] . "' AND mat_eliminado=0");
			} catch (Exception $e) {
				include("../compartido/error-catch-to-report.php");
			}
		}
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="estudiantes.php";</script>';
	exit();
}
//REACCIONES POR NOTICIA
if ($_GET["get"] == 67) {
	try{
		$consultaReaccion=mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".social_noticias_reacciones WHERE npr_usuario='" . $_SESSION["id"] . "' AND npr_noticia='" . $_GET["post"] . "'");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}
	$reaccion = mysqli_fetch_array($consultaReaccion, MYSQLI_BOTH);
	if ($reaccion[0] == "") {
		try{
			mysqli_query($conexion, "INSERT INTO ".$baseDatosServicios.".social_noticias_reacciones(npr_usuario, npr_noticia, npr_reaccion, npr_fecha, npr_estado, npr_institucion, npr_year)VALUES('" . $_SESSION["id"] . "', '" . $_GET["post"] . "','" . $_GET["r"] . "',now(),1,'" . $config['conf_id_institucion'] . "','" . $_SESSION["bd"] . "')");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
	
	} else {
		try{
			mysqli_query($conexion, "UPDATE ".$baseDatosServicios.".social_noticias_reacciones SET npr_reaccion='" . $_GET["r"] . "' WHERE npr_usuario='" . $_SESSION["id"] . "' AND npr_noticia='" . $_GET["post"] . "'");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}		
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="noticias.php";</script>';
	exit();
}
//ELIMINAR INDICADORES DE LOS DOCENTES
if ($_GET["get"] == 68) {
	include("verificar-carga.php");
	//include("verificar-periodos-diferentes.php");

	try{
		$actividadesRelacionadasConsulta = mysqli_query($conexion, "SELECT * FROM academico_actividades 
		WHERE act_id_tipo='" . base64_decode($_GET["idIndicador"]) . "' AND act_id_carga='" . base64_decode($_GET["carga"]) . "' AND act_periodo='" . base64_decode($_GET["periodo"]) . "' AND act_estado=1");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}
	while ($actividadesRelacionadasDatos = mysqli_fetch_array($actividadesRelacionadasConsulta, MYSQLI_BOTH)) {
		try{
			mysqli_query($conexion, "UPDATE academico_actividades SET act_estado=0, act_fecha_eliminacion=now(), act_motivo_eliminacion='DIRECTIVO " . $_SESSION["id"] . ": Eliminar indicadores de carga: " . $cargaConsultaActual . ", del P: " . $periodoConsultaActual . "' WHERE act_id='" . $actividadesRelacionadasDatos['act_id'] . "'");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
	}

	try{
		mysqli_query($conexion, "DELETE FROM academico_indicadores_carga WHERE ipc_id='" . base64_decode($_GET["idR"]) . "'");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	try{
		$consultaSumaIndicadores=mysqli_query($conexion, "SELECT
		(SELECT sum(ipc_valor) FROM academico_indicadores_carga 
		WHERE ipc_carga='" . $cargaConsultaActual . "' AND ipc_periodo='" . $periodoConsultaActual . "' AND ipc_creado=0),
		(SELECT sum(ipc_valor) FROM academico_indicadores_carga 
		WHERE ipc_carga='" . $cargaConsultaActual . "' AND ipc_periodo='" . $periodoConsultaActual . "' AND ipc_creado=1),
		(SELECT count(*) FROM academico_indicadores_carga 
		WHERE ipc_carga='" . $cargaConsultaActual . "' AND ipc_periodo='" . $periodoConsultaActual . "' AND ipc_creado=1)");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}
	$sumaIndicadores = mysqli_fetch_array($consultaSumaIndicadores, MYSQLI_BOTH);
	$porcentajePermitido = 100 - $sumaIndicadores[0];
	$porcentajeRestante = ($porcentajePermitido - $sumaIndicadores[1]);

	//Si decide poner los valores porcentuales de los indicadores de forma manual
	if ($datosCargaActual['car_valor_indicador'] == 1) {
	}
	//El sistema reparte los porcentajes automáticamente y equitativamente.
	else {
		$valorIgualIndicador = ($porcentajePermitido / ($sumaIndicadores[2]));
		//Actualiza todos valores de la misma carga y periodo.
		try{
			mysqli_query($conexion, "UPDATE academico_indicadores_carga SET ipc_valor='" . $valorIgualIndicador . "' 
			WHERE ipc_carga='" . $cargaConsultaActual . "' AND ipc_periodo='" . $periodoConsultaActual . "' AND ipc_creado=1");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}

		//Si decide que los valores de las calificaciones son de forma automática.
		if ($datosCargaActual['car_configuracion'] == 0) {
			//Repetimos la consulta de los indicadores porque los valores fueron actualizados
			try{
				$indicadoresConsultaActualizado = mysqli_query($conexion, "SELECT * FROM academico_indicadores_carga 
				WHERE ipc_carga='" . $cargaConsultaActual . "' AND ipc_periodo='" . $periodoConsultaActual . "' AND ipc_creado=1");
			} catch (Exception $e) {
				include("../compartido/error-catch-to-report.php");
			}

			//Actualizamos todas las actividades por cada indicador
			while ($indicadoresDatos = mysqli_fetch_array($indicadoresConsultaActualizado, MYSQLI_BOTH)) {
				try{
					$consultaNumActividades=mysqli_query($conexion, "SELECT * FROM academico_actividades 
					WHERE act_id_tipo='" . $indicadoresDatos['ipc_indicador'] . "' AND act_periodo='" . $periodoConsultaActual . "' AND act_id_carga='" . $cargaConsultaActual . "' AND act_estado=1");
				} catch (Exception $e) {
					include("../compartido/error-catch-to-report.php");
				}
				$actividadesNum = mysqli_num_rows($consultaNumActividades);
				//Si hay actividades relacionadas al indicador, actualizamos su valor.
				if ($actividadesNum > 0) {
					$valorIgualActividad = ($indicadoresDatos['ipc_valor'] / $actividadesNum);
					try{
						mysqli_query($conexion, "UPDATE academico_actividades SET act_valor='" . $valorIgualActividad . "' 
						WHERE act_id_tipo='" . $indicadoresDatos['ipc_indicador'] . "' AND act_periodo='" . $periodoConsultaActual . "' AND act_id_carga='" . $cargaConsultaActual . "' AND act_estado=1");
					} catch (Exception $e) {
						include("../compartido/error-catch-to-report.php");
					}
				}
			}
		}
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="cargas-indicadores.php?carga=' . $_GET["carga"] . '&docente=' . $_GET["docente"] . '";</script>';
	exit();
}
//BLOQUEAR ESTUDIANTES
if (base64_decode($_GET["get"]) == 69) {
	try{
		mysqli_query($conexion, "UPDATE usuarios SET uss_bloqueado=1 WHERE uss_tipo='".base64_decode($_GET["tipo"])."'");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="usuarios.php?tipo='.$_GET["tipo"].'";</script>';
	exit();
}
//DESBLOQUEAR ESTUDIANTES
if (base64_decode($_GET["get"]) == 70) {
	try{
		mysqli_query($conexion, "UPDATE usuarios SET uss_bloqueado=0 WHERE uss_tipo='".base64_decode($_GET["tipo"])."'");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="usuarios.php?tipo='.$_GET["tipo"].'";</script>';
	exit();
}
//ENCUESTA RESERVA DE CUPO
if($_GET["get"]==71){
	//echo $_GET["idEstudiante"]; exit();
	try{
		mysqli_query($conexion, "INSERT INTO ".$baseDatosServicios.".general_encuestas(genc_estudiante, genc_fecha, genc_respuesta, genc_comentario, genc_institucion, genc_year)
		VALUES('".$_GET["idEstudiante"]."', now(), 1, 'Reservado por un directivo.','" . $config['conf_id_institucion'] . "','" . $_SESSION["bd"] . "')");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="'.$_SERVER['HTTP_REFERER'].'";</script>';
	exit();
}
}

//EN CASO DE QUE NO ENTRE POR NINGUNA DE LAS ANTERIORES
$_GET["get"] == 0;
include("../compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="https://plataformasintia.com?error=1";</script>';
exit();
?>