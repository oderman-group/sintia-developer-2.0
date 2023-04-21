<?php
try {
	mysqli_query($conexion, "BEGIN");

	if($nueva!=1){//SI ES 0 LA INSTITUCION ES ANTIGUA Y SE EJECUTA EL SIGUIENTE SCRIPT

		//CURSOS
		mysqli_query($conexion, "DELETE FROM $bd.academico_grados");
		mysqli_query($conexion, "INSERT INTO $bd.academico_grados(gra_id, gra_codigo, gra_nombre, gra_formato_boletin, gra_valor_matricula, gra_valor_pension, gra_estado)SELECT gra_id, gra_codigo, gra_nombre, gra_formato_boletin, gra_valor_matricula, gra_valor_pension, gra_estado FROM $bdAnterior.academico_grados");
		echo "<pre>Se insertaron los grados del año anterior.</pre>";
		
		//AREAS
		mysqli_query($conexion, "DELETE FROM $bd.academico_areas");
		mysqli_query($conexion, "INSERT INTO $bd.academico_areas(ar_id, ar_nombre, ar_posicion)SELECT ar_id, ar_nombre, ar_posicion FROM $bdAnterior.academico_areas");
		echo "<pre>Se insertaron las áreas del año anterior.</pre>";
		
		//MATERIAS
		mysqli_query($conexion, "DELETE FROM $bd.academico_materias");
		mysqli_query($conexion, "INSERT INTO $bd.academico_materias(mat_id, mat_codigo, mat_nombre, mat_siglas, mat_area)SELECT mat_id, mat_codigo, mat_nombre, mat_siglas, mat_area FROM $bdAnterior.academico_materias");
		echo "<pre>Se insertaron las materias del año anterior.</pre>";
		
		//TODOS LOS USUARIOS
		mysqli_query($conexion, "DELETE FROM $bd.usuarios WHERE uss_id !=1");
		mysqli_query($conexion, "INSERT INTO $bd.usuarios(uss_id, uss_usuario, uss_clave, uss_tipo, uss_nombre, uss_estado, uss_foto, uss_portada, uss_idioma, uss_tema, uss_perfil, uss_ocupacion, uss_email, uss_fecha_nacimiento, uss_permiso1, uss_celular, uss_genero, uss_ultimo_ingreso, uss_ultima_salida, uss_telefono, uss_bloqueado, uss_fecha_registro, uss_responsable_registro) SELECT uss_id, uss_usuario, uss_clave, uss_tipo, uss_nombre, uss_estado, uss_foto, uss_portada, uss_idioma, uss_tema, uss_perfil, uss_ocupacion, uss_email, uss_fecha_nacimiento, uss_permiso1, uss_celular, uss_genero, uss_ultimo_ingreso, uss_ultima_salida, uss_telefono, uss_bloqueado, uss_fecha_registro, uss_responsable_registro FROM $bdAnterior.usuarios WHERE uss_id !=1");
		
		//TODOS LAS MATRICULAS
		mysqli_query($conexion, "DELETE FROM $bd.academico_matriculas");
		mysqli_query($conexion, "INSERT INTO $bd.academico_matriculas(mat_id, mat_matricula, mat_fecha, mat_primer_apellido, mat_segundo_apellido, mat_nombres, mat_grado, mat_grupo, mat_genero, mat_fecha_nacimiento, mat_lugar_nacimiento, mat_tipo_documento, mat_documento, mat_lugar_expedicion, mat_religion, mat_direccion, mat_barrio, mat_telefono, mat_celular, mat_estrato, mat_foto, mat_tipo, mat_estado_matricula, mat_id_usuario, mat_eliminado, mat_email, mat_acudiente, mat_privilegio1, mat_privilegio2, mat_privilegio3, mat_uso_sintia, mat_inicio, mat_meses, mat_fin, mat_folio, mat_codigo_tesoreria) SELECT mat_id, mat_matricula, mat_fecha, mat_primer_apellido, mat_segundo_apellido, mat_nombres, mat_grado, mat_grupo, mat_genero, mat_fecha_nacimiento, mat_lugar_nacimiento, mat_tipo_documento, mat_documento, mat_lugar_expedicion, mat_religion, mat_direccion, mat_barrio, mat_telefono, mat_celular, mat_estrato, mat_foto, mat_tipo, mat_estado_matricula, mat_id_usuario, mat_eliminado, mat_email, mat_acudiente, mat_privilegio1, mat_privilegio2, mat_privilegio3, mat_uso_sintia, mat_inicio, mat_meses, mat_fin, mat_folio, mat_codigo_tesoreria FROM $bdAnterior.academico_matriculas");
		mysqlI_query($conexion, "UPDATE $bd.academico_matriculas SET mat_fecha='0000-00-00', mat_estado_matricula=4, mat_promocionado=0, mat_estado_agno=0");
		
		//TODOS LOS USUARIOS POR ESTUDIANTES
		mysqli_query($conexion, "INSERT INTO $bd.usuarios_por_estudiantes(upe_id, upe_id_usuario, upe_id_estudiante) SELECT upe_id, upe_id_usuario, upe_id_estudiante FROM $bdAnterior.usuarios_por_estudiantes");

		//CARGAS
		mysqli_query($conexion, "DELETE FROM $bd.academico_cargas");
		mysqli_query($conexion, "INSERT INTO $bd.academico_cargas(car_id, car_docente, car_curso, car_grupo, car_materia, car_periodo, car_activa, car_permiso1, car_director_grupo, car_ih, car_fecha_creada, car_responsable)SELECT car_id, car_docente, car_curso, car_grupo, car_materia, car_periodo, car_activa, car_permiso1, car_director_grupo, car_ih, car_fecha_creada, car_responsable FROM $bdAnterior.academico_cargas");
		mysqli_query($conexion, "UPDATE $bd.academico_cargas SET car_periodo=1");
		
		//BUSCAMOS DATOS DE CONFIGURACIÓN DEL AÑO ANTERIOR
		$confAnterior=mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".configuracion WHERE conf_agno='".$yearAnterior."' AND conf_id_institucion='".$idInsti."'");
		$datosConfAnterior = mysqli_fetch_array($confAnterior, MYSQLI_BOTH);

		//CREAMOS LA NUEVA CONFIGURACIÓN DE LA INSTITUCIÓN
		mysqli_query($conexion, "INSERT INTO ".$baseDatosServicios.".configuracion (conf_agno,conf_periodo,conf_nota_desde,conf_nota_hasta,conf_nota_minima_aprobar,conf_color_perdida,conf_color_ganada,conf_saldo_pendiente,conf_num_restaurar,conf_restaurar_cantidad,conf_color_borde,conf_color_encabezado,conf_tam_borde,conf_num_materias_perder_agno,conf_inicio_matrucula,conf_fin_matricula,conf_apertura_academica,conf_clausura_academica,conf_periodos_maximos,conf_num_indicadores,conf_valor_indicadores,conf_notas_categoria,conf_id_institucion,conf_base_datos,conf_servidor,conf_num_registros,conf_agregar_porcentaje_asignaturas,conf_fecha_parcial,conf_descripcion_parcial,conf_ancho_imagen,conf_alto_imagen,conf_mostrar_nombre,conf_deuda,conf_permiso_eliminar_cargas,conf_concepto,conf_inicio_recibos_ingreso,conf_inicio_recibos_egreso,conf_decimales_notas,conf_activar_encuesta,conf_sin_nota_numerica,conf_numero_factura,conf_max_peso_archivos,conf_informe_parcial,conf_ver_observador,conf_ficha_estudiantil,conf_orden_nombre_estudiantes,conf_editar_definitivas_consolidado) VALUES ('".$year."',1,'".$datosConfAnterior['conf_nota_desde']."','".$datosConfAnterior['conf_nota_hasta']."','".$datosConfAnterior['conf_nota_minima_aprobar']."','#e10000','#0000d5','".$datosConfAnterior['conf_saldo_pendiente']."','".$datosConfAnterior['conf_num_restaurar']."','".$datosConfAnterior['conf_restaurar_cantidad']."','#000000','#ff0080',1,3,'".$fecha."','".$fecha."','".$fecha."','".$fecha."',4,'".$datosConfAnterior['conf_num_indicadores']."','".$datosConfAnterior['conf_valor_indicadores']."','".$datosConfAnterior['conf_notas_categoria']."','".$idInsti."','".BD_PREFIX.$siglasBD."',NULL,'".$datosConfAnterior['conf_num_registros']."',NULL,NULL,NULL,'200','150',1,'".$datosConfAnterior['conf_deuda']."','".$datosConfAnterior['conf_permiso_eliminar_cargas']."',NULL,NULL,NULL,'".$datosConfAnterior['conf_decimales_notas']."','".$datosConfAnterior['conf_activar_encuesta']."','".$datosConfAnterior['conf_sin_nota_numerica']."','".$datosConfAnterior['conf_numero_factura']."','".$datosConfAnterior['conf_max_peso_archivos']."','".$datosConfAnterior['conf_informe_parcial']."','".$datosConfAnterior['conf_ver_observador']."','".$datosConfAnterior['conf_ficha_estudiantil']."','".$datosConfAnterior['conf_orden_nombre_estudiantes']."','".$datosConfAnterior['conf_editar_definitivas_consolidado']."')");

        //CONSULTAMOS AÑO INICIAL Y ACTUAL DE LA INSTITUCION EN EL CAMPO ins_years
        $consultaInsti = mysqli_query($conexion, "SELECT ins_years FROM ".$baseDatosServicios.".instituciones WHERE ins_id='".$idInsti."'");
        $datosInsti = mysqli_fetch_array($consultaInsti, MYSQLI_BOTH);
        $yearArray = explode(",", $datosInsti['ins_years']);
        $yearStart = $yearArray[0];

        //AÑADIMOS EL NUEVO AÑO AL CAMPO ins_years
		mysqli_query($conexion, "UPDATE ".$baseDatosServicios.".instituciones SET ins_years='".$yearStart.",".$year."' WHERE ins_id='".$idInsti."'");

		//BUSCAMOS DATOS DE LA INFORMACIÓN DEL AÑO ANTERIOR
		$infoAnterior=mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".general_informacion WHERE info_institucion='".$idInsti."' AND info_year='".$yearAnterior."'");
		$datosInfoAnterior = mysqli_fetch_array($infoAnterior, MYSQLI_BOTH);

		//CREAMOS LA NUEVA INFORMACIÓN DE LA INSTITUCIÓN
		mysqli_query($conexion, "INSERT INTO ".$baseDatosServicios.".general_informacion (info_rector,info_secretaria_academica,info_logo,info_nit,info_nombre,info_direccion,info_telefono,info_clase,info_caracter,info_calendario,info_jornada,info_horario,info_niveles,info_modalidad,info_propietario,info_coordinador_academico,info_tesorero,info,info_institucion,info_year) VALUES ('".$datosInfoAnterior['info_rector']."','".$datosInfoAnterior['info_secretaria_academica']."','".$datosInfoAnterior['info_logo']."','".$datosInfoAnterior['info_nit']."','".$datosInfoAnterior['info_nombre']."','".$datosInfoAnterior['info_direccion']."','".$datosInfoAnterior['info_telefono']."','".$datosInfoAnterior['info_clase']."','".$datosInfoAnterior['info_caracter']."','".$datosInfoAnterior['info_calendario']."','".$datosInfoAnterior['info_jornada']."','".$datosInfoAnterior['info_horario']."','".$datosInfoAnterior['info_niveles']."','".$datosInfoAnterior['info_modalidad']."','".$datosInfoAnterior['info_propietario']."','".$datosInfoAnterior['info_coordinador_academico']."','".$datosInfoAnterior['info_tesorero']."','".$datosInfoAnterior['info']."','".$idInsti."','".$year."')");

	}else{//SI ES 1 LA INSTITUCION ES NUEVA Y SE EJECUTA EL SIGUIENTE SCRIPT

		//CREAMOS LA INSTITUCIÓN
		mysqli_query($conexion, "INSERT INTO ".$baseDatosServicios.".instituciones (ins_nombre,ins_fecha_inicio,ins_telefono_principal,ins_contacto_principal,ins_cargo_contacto,ins_celular_contacto,ins_email_contacto,ins_email_institucion,ins_ciudad,ins_url_carpeta,ins_nit,ins_medio_info,ins_estado,ins_url_acceso,ins_bd,ins_deuda,ins_valor_deuda,ins_concepto_deuda,ins_bloqueada,ins_years,ins_notificaciones_acudientes,ins_siglas,ins_fecha_renovacion,ins_id_plan) VALUES ('".$nombreInsti."','".$fechaCompleta."',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'".BD_PREFIX.$siglasBD."',NULL,NULL,NULL,0,'".$year.",".$year."',0,'".$siglasInst."','".$fechaCompleta."',1)");
		$idInsti = mysqli_insert_id($conexion);

		//ASIGNAMOS MODULOS A LA INSTITUCIÓN
		mysqli_query($conexion, "INSERT INTO ".$baseDatosServicios.".instituciones_modulos (ipmod_institucion,ipmod_modulo) VALUES ($idInsti,1),($idInsti,2),($idInsti,3),($idInsti,4),($idInsti,5),($idInsti,6),($idInsti,7)");

		//CREAMOS CONFIGURACIÓN DE LA INSTITUCIÓN
		mysqli_query($conexion, "INSERT INTO ".$baseDatosServicios.".configuracion (conf_agno,conf_periodo,conf_nota_desde,conf_nota_hasta,conf_nota_minima_aprobar,conf_color_perdida,conf_color_ganada,conf_saldo_pendiente,conf_num_restaurar,conf_restaurar_cantidad,conf_color_borde,conf_color_encabezado,conf_tam_borde,conf_num_materias_perder_agno,conf_inicio_matrucula,conf_fin_matricula,conf_apertura_academica,conf_clausura_academica,conf_periodos_maximos,conf_num_indicadores,conf_valor_indicadores,conf_notas_categoria,conf_id_institucion,conf_base_datos,conf_servidor,conf_num_registros,conf_agregar_porcentaje_asignaturas,conf_fecha_parcial,conf_descripcion_parcial,conf_ancho_imagen,conf_alto_imagen,conf_mostrar_nombre,conf_deuda,conf_permiso_eliminar_cargas,conf_concepto,conf_inicio_recibos_ingreso,conf_inicio_recibos_egreso,conf_decimales_notas,conf_activar_encuesta,conf_sin_nota_numerica,conf_numero_factura,conf_max_peso_archivos,conf_informe_parcial,conf_ver_observador,conf_ficha_estudiantil,conf_solicitar_acudiente_2,conf_mostrar_campos,conf_calificaciones_acudientes,conf_mostrar_calificaciones_estudiantes,conf_orden_nombre_estudiantes,conf_editar_definitivas_consolidado) VALUES ('".$year."',1,1,5,3,'#e10000','#0000d5',NULL,NULL,NULL,'#000000','#ff0080',1,3,'".$fecha."','".$fecha."','".$fecha."','".$fecha."',4,NULL,NULL,NULL,'".$idInsti."','".BD_PREFIX.$siglasBD."',NULL,20,'NO',NULL,NULL,'200','150',1,NULL,'NO',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'5',0,0,0,'NO',1,1,1,1,0)");

		//CREAMOS LA NUEVA INFORMACIÓN DE LA INSTITUCIÓN
		mysqli_query($conexion, "INSERT INTO ".$baseDatosServicios.".general_informacion (info_rector,info_secretaria_academica,info_logo,info_nit,info_nombre,info_direccion,info_telefono,info_clase,info_caracter,info_calendario,info_jornada,info_horario,info_niveles,info_modalidad,info_propietario,info_coordinador_academico,info_tesorero,info,info_institucion,info_year) VALUES ('2','2','sintia-logo-2023.png','0000000000-0','".$nombreInsti."','Cra 00 # 00-00','(000)000-0000','Privado','Mixto','A','Mañana','6:00 am - 12:30 pm','Preescolar, Basica, Media','Academica','PROPIETARIO PRUEBA','2','2','1','".$idInsti."','".$year."')");
	}

	mysqli_query($conexion, "COMMIT");

} catch(Exception $e){
	mysqli_query($conexion, "ROLLBACK");
	echo $e->getMessage();
	exit();
}	