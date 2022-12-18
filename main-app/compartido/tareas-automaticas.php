<?php
include("../modelo/conexion.php");
//PARA DOCENTES
//Generar informes
$cargasConsulta = mysql_query("SELECT DATEDIFF(car_fecha_generar_informe_auto, now()), car_id, car_periodo, car_curso, car_grupo, car_docente FROM academico_cargas
WHERE car_fecha_generar_informe_auto IS NOT NULL AND car_fecha_generar_informe_auto!='0000-00-00'
",$conexion);
if(mysql_errno()!=0){echo mysql_error(); exit();}

while($cargasDatos = mysql_fetch_array($cargasConsulta)){
	if($cargasDatos[0]==0){
		
		mysql_query("DELETE FROM academico_boletin WHERE bol_carga=".$cargasDatos['car_id']." AND bol_periodo='".$cargasDatos['car_periodo']."'",$conexion);
		if(mysql_errno()!=0){echo mysql_error(); exit();}
		
		$consulta = mysql_query("SELECT * FROM academico_matriculas 
		WHERE mat_grado='".$cargasDatos['car_curso']."' AND mat_grupo='".$cargasDatos['car_grupo']."' AND (mat_estado_matricula=1 OR mat_estado_matricula=2) AND mat_eliminado=0 ORDER BY mat_primer_apellido",$conexion);
		if(mysql_errno()!=0){echo mysql_error(); exit();}
		
		 $pararProceso = 2;
		 while($resultado = mysql_fetch_array($consulta)){
			//DEFINITIVAS
			$carga = $cargasDatos['car_id'];
			$periodo = $cargasDatos['car_periodo'];
			$estudiante = $resultado[0];
			include("../definitivas.php");	
			if($porcentajeActual<96){
				mysql_query("DELETE FROM academico_boletin WHERE bol_carga=".$cargasDatos['car_id']." AND bol_periodo='".$cargasDatos['car_periodo']."'",$conexion);
				if(mysql_errno()!=0){echo mysql_error(); exit();}
				
				if($porcentajeActual>0){
					$porcentajeFaltante = (100 - $porcentajeActual);
					mysql_query("INSERT INTO general_alertas (alr_nombre, alr_descripcion, alr_tipo, alr_usuario, alr_fecha_envio, alr_categoria, alr_importancia, alr_url_acceso, alr_vista)
					VALUES('No se pudo generar el informe', 'No se pudo generar el informe para la carga ".$cargasDatos['car_id']." con el estudiante ".$resultado['mat_nombres'].". Le falta un ".$porcentajeFaltante."% para completar el 100% de sus notas.', 2, '".$cargasDatos['car_docente']."', now(), 3, 2, 'calificaciones.php?carga=".$cargasDatos['car_id']."&periodo=".$cargasDatos['car_periodo']."', 0)",$conexion);
					if(mysql_errno()!=0){echo mysql_error(); exit();}
				}

				$pararProceso = 1;
				continue;
			}
			if($pararProceso==2){
				//INSERTAR LOS DATOS EN LA TABLA BOLETIN	
				mysql_query("INSERT INTO academico_boletin(bol_carga, bol_estudiante, bol_periodo, bol_nota, bol_tipo)
				VALUES('".$cargasDatos['car_id']."','".$resultado[0]."','".$cargasDatos['car_periodo']."','".$definitiva."',1)",$conexion);	
				if(mysql_errno()!=0){echo mysql_error(); exit();}
			}
		}
		if($pararProceso==2){
			mysql_query("UPDATE academico_cargas SET car_periodo=car_periodo+1 WHERE car_id='".$cargasDatos['car_id']."'",$conexion);
			if(mysql_errno()!=0){echo mysql_error(); exit();}
		}
	}
}
?>