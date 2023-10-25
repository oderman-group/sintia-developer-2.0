<?php
try {
	mysqli_query($conexion, "BEGIN");

	if($nueva!=1){//SI ES 0 LA INSTITUCION ES ANTIGUA Y SE EJECUTA EL SIGUIENTE SCRIPT

		//CURSOS
		try{
			mysqli_query($conexion, "DELETE FROM $bd.academico_grados");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
		try{
			mysqli_query($conexion, "INSERT INTO $bd.academico_grados SELECT * FROM $bdAnterior.academico_grados");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
		
		//AREAS
		try{
			mysqli_query($conexion, "DELETE FROM $bd.academico_areas");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
		try{
			mysqli_query($conexion, "INSERT INTO $bd.academico_areas SELECT * FROM $bdAnterior.academico_areas");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
		
		//MATERIAS
		try{
			mysqli_query($conexion, "DELETE FROM $bd.academico_materias");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
		try{
			mysqli_query($conexion, "INSERT INTO $bd.academico_materias SELECT * FROM $bdAnterior.academico_materias");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
		
		//TODOS LOS USUARIOS
		try{
			mysqli_query($conexion, "DELETE FROM $bd.usuarios WHERE uss_id !=1");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
		try{
			mysqli_query($conexion, "INSERT INTO $bd.usuarios SELECT * FROM $bdAnterior.usuarios WHERE uss_id !=1");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
		
		//TODOS LAS MATRICULAS
		try{
			mysqli_query($conexion, "DELETE FROM $bd.academico_matriculas");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
		try{
			mysqli_query($conexion, "INSERT INTO $bd.academico_matriculas SELECT * FROM $bdAnterior.academico_matriculas");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
		try{
			mysqlI_query($conexion, "UPDATE $bd.academico_matriculas SET mat_fecha='0000-00-00', mat_estado_matricula=4, mat_promocionado=0, mat_estado_agno=0");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
		
		//TODOS LOS USUARIOS POR ESTUDIANTES
		try{
			mysqli_query($conexion, "INSERT INTO $bd.usuarios_por_estudiantes SELECT * FROM $bdAnterior.usuarios_por_estudiantes");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}

		//CARGAS
		try{
			mysqli_query($conexion, "DELETE FROM $bd.academico_cargas");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
		try{
			mysqli_query($conexion, "INSERT INTO $bd.academico_cargas SELECT * FROM $bdAnterior.academico_cargas");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
		try{
			mysqli_query($conexion, "UPDATE $bd.academico_cargas SET car_periodo=1");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}

		//BUSCAMOS DATOS DE CONFIGURACIÓN DEL AÑO ANTERIOR
		try{
			$confAnterior=mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".configuracion WHERE conf_agno='".$yearAnterior."' AND conf_id_institucion='".$idInsti."'");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
		$datosConfAnterior = mysqli_fetch_array($confAnterior, MYSQLI_BOTH);

		//CREAMOS LA NUEVA CONFIGURACIÓN DE LA INSTITUCIÓN
		try{
			mysqli_query($conexion, "INSERT INTO ".$baseDatosServicios.".configuracion (conf_agno,conf_periodo,conf_nota_desde,conf_nota_hasta,conf_nota_minima_aprobar,conf_color_perdida,conf_color_ganada,conf_saldo_pendiente,conf_num_restaurar,conf_restaurar_cantidad,conf_color_borde,conf_color_encabezado,conf_tam_borde,conf_num_materias_perder_agno,conf_inicio_matrucula,conf_fin_matricula,conf_apertura_academica,conf_clausura_academica,conf_periodos_maximos,conf_num_indicadores,conf_valor_indicadores,conf_notas_categoria,conf_id_institucion,conf_base_datos,conf_servidor,conf_num_registros,conf_agregar_porcentaje_asignaturas,conf_fecha_parcial,conf_descripcion_parcial,conf_ancho_imagen,conf_alto_imagen,conf_mostrar_nombre,conf_deuda,conf_permiso_eliminar_cargas,conf_concepto,conf_inicio_recibos_ingreso,conf_inicio_recibos_egreso,conf_decimales_notas,conf_activar_encuesta,conf_sin_nota_numerica,conf_numero_factura,conf_max_peso_archivos,conf_informe_parcial,conf_ver_observador,conf_ficha_estudiantil,conf_orden_nombre_estudiantes,conf_editar_definitivas_consolidado,conf_solicitar_acudiente_2,conf_mostrar_campos,conf_calificaciones_acudientes,conf_mostrar_calificaciones_estudiantes,conf_observaciones_multiples_comportamiento,conf_cambiar_nombre_usuario,conf_cambiar_clave_estudiantes,conf_permiso_descargar_boletin,conf_certificado,conf_firma_estudiante_informe_asistencia,conf_permiso_edicion_years_anteriores,conf_porcentaje_completo_generar_informe,conf_ver_promedios_sabanas_docentes) VALUES ('".$year."',1,'".$datosConfAnterior['conf_nota_desde']."','".$datosConfAnterior['conf_nota_hasta']."','".$datosConfAnterior['conf_nota_minima_aprobar']."','#e10000','#0000d5','".$datosConfAnterior['conf_saldo_pendiente']."','".$datosConfAnterior['conf_num_restaurar']."','".$datosConfAnterior['conf_restaurar_cantidad']."','#000000','#ff0080',1,3,'".$fecha."','".$fecha."','".$fecha."','".$fecha."',4,'".$datosConfAnterior['conf_num_indicadores']."','".$datosConfAnterior['conf_valor_indicadores']."','".$datosConfAnterior['conf_notas_categoria']."','".$idInsti."','".$siglasBD."',NULL,'".$datosConfAnterior['conf_num_registros']."',NULL,NULL,NULL,'200','150',1,'".$datosConfAnterior['conf_deuda']."','".$datosConfAnterior['conf_permiso_eliminar_cargas']."',NULL,NULL,NULL,'".$datosConfAnterior['conf_decimales_notas']."','".$datosConfAnterior['conf_activar_encuesta']."','".$datosConfAnterior['conf_sin_nota_numerica']."','".$datosConfAnterior['conf_numero_factura']."','".$datosConfAnterior['conf_max_peso_archivos']."','".$datosConfAnterior['conf_informe_parcial']."','".$datosConfAnterior['conf_ver_observador']."','".$datosConfAnterior['conf_ficha_estudiantil']."','".$datosConfAnterior['conf_orden_nombre_estudiantes']."','".$datosConfAnterior['conf_editar_definitivas_consolidado']."','".$datosConfAnterior['conf_solicitar_acudiente_2']."','".$datosConfAnterior['conf_mostrar_campos']."','".$datosConfAnterior['conf_calificaciones_acudientes']."','".$datosConfAnterior['conf_mostrar_calificaciones_estudiantes']."','".$datosConfAnterior['conf_observaciones_multiples_comportamiento']."','".$datosConfAnterior['conf_cambiar_nombre_usuario']."','".$datosConfAnterior['conf_cambiar_clave_estudiantes']."','".$datosConfAnterior['conf_permiso_descargar_boletin']."','".$datosConfAnterior['conf_certificado']."','".$datosConfAnterior['conf_firma_estudiante_informe_asistencia']."','".$datosConfAnterior['conf_permiso_edicion_years_anteriores']."','".$datosConfAnterior['conf_porcentaje_completo_generar_informe']."','".$datosConfAnterior['conf_ver_promedios_sabanas_docentes']."')");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}

        //CONSULTAMOS AÑO INICIAL Y ACTUAL DE LA INSTITUCION EN EL CAMPO ins_years
		try{
			$consultaInsti = mysqli_query($conexion, "SELECT ins_years FROM ".$baseDatosServicios.".instituciones WHERE ins_id='".$idInsti."'");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
        $datosInsti = mysqli_fetch_array($consultaInsti, MYSQLI_BOTH);
        $yearArray = explode(",", $datosInsti['ins_years']);
        $yearStart = $yearArray[0];

        //AÑADIMOS EL NUEVO AÑO AL CAMPO ins_years
		try{
			mysqli_query($conexion, "UPDATE ".$baseDatosServicios.".instituciones SET ins_years='".$yearStart.",".$year."' WHERE ins_id='".$idInsti."'");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}

		//BUSCAMOS DATOS DE LA INFORMACIÓN DEL AÑO ANTERIOR
		try{
			$infoAnterior=mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".general_informacion WHERE info_institucion='".$idInsti."' AND info_year='".$yearAnterior."'");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
		$datosInfoAnterior = mysqli_fetch_array($infoAnterior, MYSQLI_BOTH);

		//CREAMOS LA NUEVA INFORMACIÓN DE LA INSTITUCIÓN
		try{
			mysqli_query($conexion, "INSERT INTO ".$baseDatosServicios.".general_informacion (info_rector,info_secretaria_academica,info_logo,info_nit,info_nombre,info_direccion,info_telefono,info_clase,info_caracter,info_calendario,info_jornada,info_horario,info_niveles,info_modalidad,info_propietario,info_coordinador_academico,info_tesorero,info,info_institucion,info_year) VALUES ('".$datosInfoAnterior['info_rector']."','".$datosInfoAnterior['info_secretaria_academica']."','".$datosInfoAnterior['info_logo']."','".$datosInfoAnterior['info_nit']."','".$datosInfoAnterior['info_nombre']."','".$datosInfoAnterior['info_direccion']."','".$datosInfoAnterior['info_telefono']."','".$datosInfoAnterior['info_clase']."','".$datosInfoAnterior['info_caracter']."','".$datosInfoAnterior['info_calendario']."','".$datosInfoAnterior['info_jornada']."','".$datosInfoAnterior['info_horario']."','".$datosInfoAnterior['info_niveles']."','".$datosInfoAnterior['info_modalidad']."','".$datosInfoAnterior['info_propietario']."','".$datosInfoAnterior['info_coordinador_academico']."','".$datosInfoAnterior['info_tesorero']."','".$datosInfoAnterior['info']."','".$idInsti."','".$year."')");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}

	} else {
		
		//SI ES 1 LA INSTITUCION ES NUEVA Y SE EJECUTA EL SIGUIENTE SCRIPT

		//CREAMOS LA INSTITUCIÓN
		try{			
			// Definir un array asociativo con los campos y valores a insertar
			$dataToInsert = array(
				'ins_nombre' => $nombreInsti,
				'ins_fecha_inicio' => $fechaCompleta,
				'ins_telefono_principal' => NULL,
				'ins_contacto_principal' => NULL,
				'ins_cargo_contacto' => NULL,
				'ins_celular_contacto' => NULL,
				'ins_email_contacto' => NULL,
				'ins_email_institucion' => NULL,
				'ins_ciudad' => NULL,
				'ins_enviroment' => ENVIROMENT,
				'ins_nit' => NULL,
				'ins_medio_info' => NULL,
				'ins_estado' => 1,
				'ins_url_acceso' => NULL,
				'ins_bd' => $bdInstitucion,
				'ins_deuda' => NULL,
				'ins_valor_deuda' => NULL,
				'ins_concepto_deuda' => NULL,
				'ins_bloqueada' => 0,
				'ins_years' => $year . "," . $year,
				'ins_notificaciones_acudientes' => 0,
				'ins_siglas' => $siglasInst,
				'ins_fecha_renovacion' => $fechaCompleta,
				'ins_id_plan' => 1
			);

			// Crear la consulta SQL
			$query = "INSERT INTO ".$baseDatosServicios.".instituciones (";
			$columns = array_keys($dataToInsert);
			$values = array_values($dataToInsert);

			$query .= implode(', ', $columns);
			$query .= ") VALUES (";
			$query .= "'" . implode("', '", $values) . "'";
			$query .= ")";

			// Ejecutar la consulta SQL
			mysqli_query($conexion, $query);

		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
		$idInsti = mysqli_insert_id($conexion);

		//ASIGNAMOS MODULOS A LA INSTITUCIÓN
		try{
			mysqli_query($conexion, "INSERT INTO ".$baseDatosServicios.".instituciones_modulos (ipmod_institucion,ipmod_modulo) VALUES ($idInsti,1),($idInsti,2),($idInsti,3),($idInsti,4),($idInsti,5),($idInsti,6),($idInsti,7)");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}

		//CREAMOS CONFIGURACIÓN DE LA INSTITUCIÓN
		try{
			mysqli_query($conexion, "INSERT INTO ".$baseDatosServicios.".configuracion (conf_agno,conf_periodo,conf_nota_desde,conf_nota_hasta,conf_nota_minima_aprobar,conf_color_perdida,conf_color_ganada,conf_saldo_pendiente,conf_num_restaurar,conf_restaurar_cantidad,conf_color_borde,conf_color_encabezado,conf_tam_borde,conf_num_materias_perder_agno,conf_inicio_matrucula,conf_fin_matricula,conf_apertura_academica,conf_clausura_academica,conf_periodos_maximos,conf_num_indicadores,conf_valor_indicadores,conf_notas_categoria,conf_id_institucion,conf_base_datos,conf_servidor,conf_num_registros,conf_agregar_porcentaje_asignaturas,conf_fecha_parcial,conf_descripcion_parcial,conf_ancho_imagen,conf_alto_imagen,conf_mostrar_nombre,conf_deuda,conf_permiso_eliminar_cargas,conf_concepto,conf_inicio_recibos_ingreso,conf_inicio_recibos_egreso,conf_decimales_notas,conf_activar_encuesta,conf_sin_nota_numerica,conf_numero_factura,conf_max_peso_archivos,conf_informe_parcial,conf_ver_observador,conf_ficha_estudiantil,conf_solicitar_acudiente_2,conf_mostrar_campos,conf_calificaciones_acudientes,conf_mostrar_calificaciones_estudiantes,conf_orden_nombre_estudiantes,conf_editar_definitivas_consolidado,conf_observaciones_multiples_comportamiento,conf_cambiar_nombre_usuario,conf_cambiar_clave_estudiantes,conf_permiso_descargar_boletin,conf_certificado,conf_firma_estudiante_informe_asistencia,conf_permiso_edicion_years_anteriores,conf_porcentaje_completo_generar_informe,conf_ver_promedios_sabanas_docentes) VALUES ('".$year."',1,1,5,3,'#e10000','#0000d5',NULL,NULL,NULL,'#000000','#ff0080',1,3,'".$fecha."','".$fecha."','".$fecha."','".$fecha."',4,NULL,NULL,NULL,'".$idInsti."','".$bdInstitucion."',NULL,20,'NO',NULL,NULL,'200','150',1,NULL,'NO',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'5',0,0,0,'NO',1,1,1,1,0,0,'SI','SI',1,1,1,1,1,1)");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}

		//CREAMOS LA NUEVA INFORMACIÓN DE LA INSTITUCIÓN
		try{
			mysqli_query($conexion, "INSERT INTO ".$baseDatosServicios.".general_informacion (info_rector,info_secretaria_academica,info_logo,info_nit,info_nombre,info_direccion,info_telefono,info_clase,info_caracter,info_calendario,info_jornada,info_horario,info_niveles,info_modalidad,info_propietario,info_coordinador_academico,info_tesorero,info,info_institucion,info_year) VALUES ('2','2','sintia-logo-2023.png','0000000000-0','".$nombreInsti."','Cra 00 # 00-00','(000)000-0000','Privado','Mixto','A','Mañana','6:00 am - 12:30 pm','Preescolar, Basica, Media','Academica','PROPIETARIO PRUEBA','2','2','1','".$idInsti."','".$year."')");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
	}

	mysqli_query($conexion, "COMMIT");

} catch(Exception $e){
	mysqli_query($conexion, "ROLLBACK");
	echo $e->getMessage();
	exit();
}	