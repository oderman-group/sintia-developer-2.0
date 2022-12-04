<?php
include("../../../config-general/config.php");

//PARA ESTUDIANTES
//Notificaciones de vencimiento de actividades
$notificacionID = mysql_fetch_array(mysql_query("SELECT notf_id, DATEDIFF(now(), notf_ultimo_envio), notf_periocidad FROM ".$baseDatosServicios.".notificaciones_automaticas 
WHERE notf_id=1",$conexion));

if($notificacionID[1]>=$notificacionID[2])
{
	mysql_query("UPDATE ".$baseDatosServicios.".notificaciones_automaticas SET notf_ultimo_envio=now()
	WHERE notf_id=1",$conexion);
	
	$enviosNotf = 0;
	
	$actividadesConsulta = mysql_query("SELECT DATEDIFF(tar_fecha_entrega, now()), tar_id_carga, tar_titulo, tar_id FROM academico_actividad_tareas
	WHERE tar_fecha_entrega IS NOT NULL AND tar_fecha_entrega!='0000-00-00'
	",$conexion);

	while($actividadesDatos = mysql_fetch_array($actividadesConsulta)){
		//Cuando faltan 2 días
		if($actividadesDatos[0]==2){
			$cargasConsulta = mysql_query("SELECT * FROM academico_cargas
			INNER JOIN academico_matriculas ON mat_grado=car_curso AND mat_grupo=car_grupo AND mat_eliminado=0
			WHERE car_id='".$actividadesDatos['tar_id_carga']."'
			",$conexion);

			while($cargasDatos = mysql_fetch_array($cargasConsulta)){
				$entregasDatos = mysql_fetch_array(mysql_query("SELECT * FROM academico_actividad_tareas_entregas 
				WHERE ent_id_actividad='".$actividadesDatos['tar_id']."' AND ent_id_estudiante='".$cargasDatos['mat_id']."'",$conexion));

				if($entregasDatos[0]==""){
					mysql_query("INSERT INTO general_alertas (alr_nombre, alr_descripcion, alr_tipo, alr_usuario, alr_fecha_envio, alr_categoria, alr_importancia, alr_vista)
					VALUES('Quedan 2 días para enviar la actividad', 'Quedan sólo 2 días para que se termine el plazo de enviar la actividad ".strtoupper($actividadesDatos['tar_titulo']).". Si no las has enviado aún, te sugerimos hacerlo.', 2, '".$cargasDatos['mat_id_usuario']."', now(), 3, 2, 0)",$conexion);

					$idNotify = mysql_insert_id();
					mysql_query("UPDATE general_alertas SET alr_url_acceso='actividades-ver.php?idNotify=".$idNotify."&idR=".$actividadesDatos['tar_id']."' WHERE alr_id='".$idNotify."'",$conexion);
					
					$enviosNotf ++;	
					
				}
			}
			mysql_free_result($cargasConsulta);
		}
		//Cuando llega el día de enviar la actividad
		if($actividadesDatos[0]==0){
			$cargasConsulta = mysql_query("SELECT * FROM academico_cargas
			INNER JOIN academico_matriculas ON mat_grado=car_curso AND mat_grupo=car_grupo AND mat_eliminado=0
			WHERE car_id='".$actividadesDatos['tar_id_carga']."'
			",$conexion);

			while($cargasDatos = mysql_fetch_array($cargasConsulta)){
				$entregasDatos = mysql_fetch_array(mysql_query("SELECT * FROM academico_actividad_tareas_entregas 
				WHERE ent_id_actividad='".$actividadesDatos['tar_id']."' AND ent_id_estudiante='".$cargasDatos['mat_id']."'",$conexion));

				if($entregasDatos[0]==""){
					mysql_query("INSERT INTO general_alertas (alr_nombre, alr_descripcion, alr_tipo, alr_usuario, alr_fecha_envio, alr_categoria, alr_importancia, alr_vista)
					VALUES('Hoy vence el plazo para enviar la actividad', 'Hoy es el último día para que se termine el plazo de enviar la actividad ".strtoupper($actividadesDatos['tar_titulo']).". Si no las has enviado aún, te sugerimos hacerlo AHORA.', 2, '".$cargasDatos['mat_id_usuario']."', now(), 3, 2, 0)",$conexion);

					$idNotify = mysql_insert_id();
					mysql_query("UPDATE general_alertas SET alr_url_acceso='actividades-ver.php?idNotify=".$idNotify."&idR=".$actividadesDatos['tar_id']."' WHERE alr_id='".$idNotify."'",$conexion);
					
					$enviosNotf ++;
				}
			}
			mysql_free_result($cargasConsulta);
		}
	}
	mysql_query("INSERT INTO ".$baseDatosServicios.".notificaciones_registros(notfr_notify_id, notfr_fecha, notfr_envios, notfr_institucion)VALUES(1, now(), '".$enviosNotf."', '".$config['conf_id_institucion']."')",$conexion);
	
	mysql_free_result($actividadesConsulta);
}

//PARA DOCENTES
//Instarlos a que hagan evaluaciones
/* ACTUALIZADO EL 08 DE SEP DE 2019 - PARA NO ENVIAR TANTAS NOTIFICACIONES
$notificacionID = mysql_fetch_array(mysql_query("SELECT notf_id, DATEDIFF(now(), notf_ultimo_envio), notf_periocidad FROM ".$baseDatosServicios.".notificaciones_automaticas 
WHERE notf_id=2",$conexion));

if($notificacionID[1]>=$notificacionID[2])
{
	mysql_query("UPDATE ".$baseDatosServicios.".notificaciones_automaticas SET notf_ultimo_envio=now()
	WHERE notf_id=2",$conexion);
	
	$enviosNotf = 0;
	
	$actividadesConsulta = mysql_query("SELECT * FROM academico_cargas
	INNER JOIN academico_materias ON mat_id=car_materia
	INNER JOIN academico_grados ON gra_id=car_curso
	INNER JOIN academico_grupos ON gru_id=car_grupo
	INNER JOIN usuarios ON uss_id=car_docente
	WHERE car_activa=1
	",$conexion);


	while($actividadesDatos = mysql_fetch_array($actividadesConsulta)){
		$evaNum = mysql_num_rows(mysql_query("SELECT * FROM academico_actividad_evaluaciones 
		WHERE eva_id_carga='".$actividadesDatos['car_id']."' AND (eva_formato IS NULL OR eva_formato='')",$conexion));

		$cursoExacto = strtoupper($actividadesDatos['mat_nombre']." - ".$actividadesDatos['gra_nombre']." ".$actividadesDatos['gru_nombre']);

		if($evaNum==0 and $actividadesDatos['uss_notificacion']==1){
			mysql_query("INSERT INTO general_alertas (alr_nombre, alr_descripcion, alr_tipo, alr_usuario, alr_fecha_envio, alr_categoria, alr_importancia, alr_vista)
			VALUES('Aún no has hecho tu primera evaluación automatizada en <b>".$cursoExacto."</b>', 'Te invitamos a que pruebes el módulo de evaluaciones virtuales, es genial. Te ayudará a realizar y calificar tus evaluaciones más rápido. A demás es automático. Pruébalo!', 2, '".$actividadesDatos['car_docente']."', now(), 3, 2, 0)",$conexion);
			$lineaError = __LINE__;
			include("reporte-errores.php");
			$idNotify = mysql_insert_id();
			
			mysql_query("UPDATE general_alertas SET alr_url_acceso='evaluaciones.php?idNotify=".$idNotify."&carga=".$actividadesDatos['car_id']."&periodo=".$actividadesDatos['car_periodo']."' WHERE alr_id='".$idNotify."'",$conexion);
			
			$enviosNotf ++;
		}
	}
	mysql_free_result($actividadesConsulta);
	
	mysql_query("INSERT INTO ".$baseDatosServicios.".notificaciones_registros(notfr_notify_id, notfr_fecha, notfr_envios, notfr_institucion)VALUES(2, now(), '".$enviosNotf."', '".$config['conf_id_institucion']."')",$conexion);
}
*/

//PARA TODOS LOS USUARIOS
//Cumpleaños
$notificacionID = mysql_fetch_array(mysql_query("SELECT notf_id, DATEDIFF(now(), notf_ultimo_envio), notf_periocidad FROM ".$baseDatosServicios.".notificaciones_automaticas 
WHERE notf_id=3",$conexion));

if($notificacionID[1]>=$notificacionID[2])
{
	mysql_query("UPDATE ".$baseDatosServicios.".notificaciones_automaticas SET notf_ultimo_envio=now()
	WHERE notf_id=3",$conexion);
	
	$enviosNotf = 0;
	
	$cumpleU = mysql_query("SELECT uss_nombre, uss_email, YEAR(uss_fecha_nacimiento) AS agno, uss_id FROM usuarios 
	WHERE MONTH(uss_fecha_nacimiento)='".date("m")."' AND DAY(uss_fecha_nacimiento)='".date("d")."'",$conexion);

	while($cumple = mysql_fetch_array($cumpleU)){
		$edad = date("Y") - $cumple['agno'];
		mysql_query("INSERT INTO general_alertas (alr_nombre, alr_descripcion, alr_tipo, alr_usuario, alr_fecha_envio, alr_categoria, alr_importancia, alr_url_acceso, alr_vista)
		VALUES('FELIZ CUMPLEAÑOS', '".$cumple['uss_nombre']." queremos desearte muchos éxitos, prosperidad y bendiciones en esta fecha especial.', 2, '".$cumple['uss_id']."', now(), 3, 2, 'notificaciones-lista.php', 0)",$conexion);
		
		$enviosNotf ++;


		$tituloMsj = "¡".strtoupper($cumple["uss_nombre"])." FELICITACIONES!";
		$bgTitulo = "#4086f4";
		$contenidoMsj = '
			<p>
				Hola!<br>
				<b>'.strtoupper($cumple["uss_nombre"]).'</b>, Queremos felicitarte en este día por motivo de tu cumple!<br>
				No todos los días se cumplen <b>'.$edad.' AÑOS.</b><br>
				Oye, que Dios te bendiga grandemente y prospere tus planes.
			</p>

			<div align="center"><img src="http://plataformasintia.com/files-general/email/cumple1.jpg" width="500"></div>

			<p>
				De todos los que hacemos parte de <b>PLATAFORMA SINTIA Y GRUPO ODERMAN</b>...<br>
				...MUCHAS FELICIDADES!!!
			</p>
		';

		$fin =  '<html><body style="background-color:#FFF;">';
		$fin .= '
					<center>
						<div style="width:600px; text-align:justify; padding:15px;">
							<img src="http://plataformasintia.com/images/logo.png" width="40">
						</div>

						<div style="font-family:arial; background:'.$bgTitulo.'; width:600px; color:#FFF; text-align:center; padding:15px;">
							<h3>'.$tituloMsj.'</h3>
						</div>

						<div style="font-family:arial; background:#FAFAFA; width:600px; color:#000; text-align:justify; padding:15px;">
							'.$contenidoMsj.'
						</div>

						<div align="center" style="width:600px; color:#000; text-align:center; padding:15px;">
								<img src="http://plataformasintia.com/images/logo.png" width="30"><br>
								¡Que tengas un excelente d&iacute;a!<br>
								<a href="https://plataformasintia.com/">www.plataformasintia.com</a>
						</div>
					</center>
					<p>&nbsp;</p>
				';	
		$fin .='';						
		$fin .=  '<html><body>';							
		$sfrom="info@plataformasintia.com"; //LA CUETA DEL QUE ENVIA EL MENSAJE			
		$sdestinatario=$cumple["uss_email"].", notify@plataformasintia.com"; //CUENTA DEL QUE RECIBE EL MENSAJE			
		$ssubject=$cumple["uss_nombre"].", FELIZ CUMPLE!"; //ASUNTO DEL MENSAJE 				
		$shtml=$fin; //MENSAJE EN SI			
		$sheader="From:".$sfrom."\nReply-To:".$sfrom."\n"; 			
		$sheader=$sheader."X-Mailer:PHP/".phpversion()."\n"; 			
		$sheader=$sheader."Mime-Version: 1.0\n"; 		
		$sheader=$sheader."Content-Type: text/html; charset=UTF-8\r\n"; 			
		@mail($sdestinatario,$ssubject,$shtml,$sheader);
	}
	mysql_free_result($cumpleU);
	
	mysql_query("INSERT INTO ".$baseDatosServicios.".notificaciones_registros(notfr_notify_id, notfr_fecha, notfr_envios, notfr_institucion)VALUES(3, now(), '".$enviosNotf."', '".$config['conf_id_institucion']."')",$conexion);
}

//Recordarles que cambien su foto actual
$notificacionID = mysql_fetch_array(mysql_query("SELECT notf_id, DATEDIFF(now(), notf_ultimo_envio), notf_periocidad FROM ".$baseDatosServicios.".notificaciones_automaticas 
WHERE notf_id=4",$conexion));

if($notificacionID[1]>=$notificacionID[2])
{
	mysql_query("UPDATE ".$baseDatosServicios.".notificaciones_automaticas SET notf_ultimo_envio=now()
	WHERE notf_id=4",$conexion);
	
	$enviosNotf = 0;
	
	$cumpleU = mysql_query("SELECT uss_nombre, uss_email, uss_id, uss_foto, uss_notificacion FROM usuarios 
	WHERE uss_foto='default.png' OR uss_foto=''",$conexion);

	while($cumple = mysql_fetch_array($cumpleU)){
		
		if($cumple['uss_notificacion']==1){
			mysql_query("INSERT INTO general_alertas (alr_nombre, alr_descripcion, alr_tipo, alr_usuario, alr_fecha_envio, alr_categoria, alr_importancia, alr_vista)
			VALUES('ACTUALIZA TU FOTO', 'Aún no has actualizado tu foto de perfil. Hazlo ahora!. Recuerda que debe ser una foto cuadrada. Ejemplo: 500px X 500px.', 2, '".$cumple['uss_id']."', now(), 3, 2, 0)",$conexion);

			$idNotify = mysql_insert_id();
			mysql_query("UPDATE general_alertas SET alr_url_acceso='perfil.php?idNotify=".$idNotify."' WHERE alr_id='".$idNotify."'",$conexion);

			$enviosNotf ++;
		}
	}
	mysql_free_result($cumpleU);
	
	mysql_query("INSERT INTO ".$baseDatosServicios.".notificaciones_registros(notfr_notify_id, notfr_fecha, notfr_envios, notfr_institucion)VALUES(4, now(), '".$enviosNotf."', '".$config['conf_id_institucion']."')",$conexion);
}


//Instarlos a que ingresen a la plataforma por primera vez
$notificacionID = mysql_fetch_array(mysql_query("SELECT notf_id, DATEDIFF(now(), notf_ultimo_envio), notf_periocidad FROM ".$baseDatosServicios.".notificaciones_automaticas 
WHERE notf_id=5",$conexion));

if($notificacionID[1]>=$notificacionID[2])
{
	mysql_query("UPDATE ".$baseDatosServicios.".notificaciones_automaticas SET notf_ultimo_envio=now()
	WHERE notf_id=5",$conexion);
	
	$enviosNotf = 0;
	
	$cumpleU = mysql_query("SELECT uss_nombre, uss_email, uss_id FROM usuarios 
	WHERE uss_ultimo_ingreso IS NULL OR uss_ultimo_ingreso=''",$conexion);

	while($cumple = mysql_fetch_array($cumpleU)){

		$tituloMsj = "¡".strtoupper($cumple["uss_nombre"])." AÚN NO HAS INGRESADO A SINTIA!";
		$bgTitulo = "#4086f4";
		$contenidoMsj = '
			Hola!<br>
			<b>'.strtoupper($cumple["uss_nombre"]).'</b>, Queremos que ingreses a la plataforma educativa SINTIA!<br>
			La plataforma te recordará tus credenciales de acceso en caso de que no las recuerdes.<br>
			Si tienes algún problema adicional que te impida el acceso puedes comunicarte con la Institución o con SINTIA SOPORTE para ayudarte.
			<p>
			<a href="'.$datosUnicosInstitucion["ins_url_acceso"].'">INGRESAR A SINTIA AHORA</a>
			</p>
		';

		$fin =  '<html><body style="background-color:#FFF;">';
		$fin .= '
					<center>
						<div style="width:600px; text-align:justify; padding:15px;">
							<img src="http://plataformasintia.com/images/logo.png" width="40">
						</div>

						<div style="font-family:arial; background:'.$bgTitulo.'; width:600px; color:#FFF; text-align:center; padding:15px;">
							<h3>'.$tituloMsj.'</h3>
						</div>

						<div style="font-family:arial; background:#FAFAFA; width:600px; color:#000; text-align:justify; padding:15px;">
							'.$contenidoMsj.'
						</div>

						<div align="center" style="width:600px; color:#000; text-align:center; padding:15px;">
								<img src="http://plataformasintia.com/images/logo.png" width="30"><br>
								¡Que tengas un excelente d&iacute;a!<br>
								<a href="https://plataformasintia.com/">www.plataformasintia.com</a>
						</div>
					</center>
					<p>&nbsp;</p>
				';	
		$fin .='';						
		$fin .=  '<html><body>';							
		$sfrom="info@plataformasintia.com"; //LA CUETA DEL QUE ENVIA EL MENSAJE			
		$sdestinatario=$cumple["uss_email"].", notify@plataformasintia.com"; //CUENTA DEL QUE RECIBE EL MENSAJE			
		$ssubject=$cumple["uss_nombre"].", INGRESA A SINTIA!"; //ASUNTO DEL MENSAJE 				
		$shtml=$fin; //MENSAJE EN SI			
		$sheader="From:".$sfrom."\nReply-To:".$sfrom."\n"; 			
		$sheader=$sheader."X-Mailer:PHP/".phpversion()."\n"; 			
		$sheader=$sheader."Mime-Version: 1.0\n"; 		
		$sheader=$sheader."Content-Type: text/html; charset=UTF-8\r\n"; 			
		@mail($sdestinatario,$ssubject,$shtml,$sheader);
		
		$enviosNotf ++;
	}
	mysql_free_result($cumpleU);
	
	mysql_query("INSERT INTO ".$baseDatosServicios.".notificaciones_registros(notfr_notify_id, notfr_fecha, notfr_envios, notfr_institucion)VALUES(5, now(), '".$enviosNotf."', '".$config['conf_id_institucion']."')",$conexion);
}



//Fechas especiales consultados en SINTIA ADMIN
$notificacionID = mysql_fetch_array(mysql_query("SELECT notf_id, DATEDIFF(now(), notf_ultimo_envio), notf_periocidad FROM ".$baseDatosServicios.".notificaciones_automaticas 
WHERE notf_id=6",$conexion));

if($notificacionID[1]>=$notificacionID[2])
{	
	mysql_query("UPDATE ".$baseDatosServicios.".notificaciones_automaticas SET notf_ultimo_envio=now()
	WHERE notf_id=6",$conexion);
	
	$enviosNotf = 0;
	
	$fechasEspeciales = mysql_query("SELECT fesp_id, fesp_titulo, fesp_contenido, fesp_fondo_titulo, fesp_asunto, fesp_fecha, fesp_genero, fesp_imagen FROM ".$baseDatosServicios.".fechas_especiales 
	WHERE MONTH(fesp_fecha)='".date("m")."' AND DAY(fesp_fecha)='".date("d")."'",$conexion);

	while($fechasE = mysql_fetch_array($fechasEspeciales)){
		$filtro = '';
		if($fechasE['fesp_genero']!="" and $fechasE['fesp_genero']!="0"){$filtro .= " AND uss_genero='".$fechasE['fesp_genero']."'";}
		$cumpleU = mysql_query("SELECT uss_nombre, uss_email FROM usuarios 
		WHERE uss_id=uss_id $filtro
		",$conexion);

		while($cumple = mysql_fetch_array($cumpleU)){
			mysql_query("INSERT INTO general_alertas (alr_nombre, alr_descripcion, alr_tipo, alr_usuario, alr_fecha_envio, alr_categoria, alr_importancia, alr_url_acceso, alr_vista)
			VALUES('FELIZ DÍA MUJER', '".$cumple['uss_nombre']." queremos felicitarte en este día especial de la mujer.', 2, '".$cumple['uss_id']."', now(), 3, 2, 'notificaciones-lista.php', 0)",$conexion);
			

			$tituloMsj = "¡".strtoupper($cumple["uss_nombre"])." ".$fechasE['fesp_titulo'];
			$bgTitulo = $fechasE['fesp_fondo_titulo'];
			$contenidoMsj = '
				<p>
					Hola!<br>
					<b>'.strtoupper($cumple["uss_nombre"]).'</b>, Queremos felicitarte en este día especial de la mujer!<br>
					¿Qué sería de este mundo sin ti, mujer?. Eres muy valiosa.</b><br>
					Oye, que Dios te bendiga grandemente y te proteja por siempre.
				</p>

				<div align="center"><img src="http://plataformasintia.com/files-general/email/'.$fechasE['fesp_imagen'].'" width="500"></div>

				<p>
					De todos los que hacemos parte de <b>PLATAFORMA SINTIA Y GRUPO ODERMAN</b>...<br>
					...MUCHAS FELICIDADES!!!
				</p>
			';

			$fin =  '<html><body style="background-color:#FFF;">';
			$fin .= '
						<center>
							<div style="width:600px; text-align:justify; padding:15px;">
								<img src="http://plataformasintia.com/images/logo.png" width="40">
							</div>

							<div style="font-family:arial; background:'.$bgTitulo.'; width:600px; color:#FFF; text-align:center; padding:15px;">
								<h3>'.$tituloMsj.'</h3>
							</div>

							<div style="font-family:arial; background:#FAFAFA; width:600px; color:#000; text-align:justify; padding:15px;">
								'.$contenidoMsj.'
							</div>

							<div align="center" style="width:600px; color:#000; text-align:center; padding:15px;">
									<img src="http://plataformasintia.com/images/logo.png" width="30"><br>
									¡Que tengas un excelente d&iacute;a!<br>
									<a href="https://plataformasintia.com/">www.plataformasintia.com</a>
							</div>
						</center>
						<p>&nbsp;</p>
					';	
			$fin .='';						
			$fin .=  '<html><body>';							
			$sfrom="info@plataformasintia.com"; //LA CUETA DEL QUE ENVIA EL MENSAJE			
			$sdestinatario=$cumple["uss_email"].", notify@plataformasintia.com"; //CUENTA DEL QUE RECIBE EL MENSAJE			
			$ssubject=$cumple["uss_nombre"].", ".$fechasE['fesp_asunto']; //ASUNTO DEL MENSAJE 				
			$shtml=$fin; //MENSAJE EN SI			
			$sheader="From:".$sfrom."\nReply-To:".$sfrom."\n"; 			
			$sheader=$sheader."X-Mailer:PHP/".phpversion()."\n"; 			
			$sheader=$sheader."Mime-Version: 1.0\n"; 		
			$sheader=$sheader."Content-Type: text/html; charset=UTF-8\r\n"; 			
			@mail($sdestinatario,$ssubject,$shtml,$sheader);
		}
	}
	
	mysql_query("INSERT INTO ".$baseDatosServicios.".notificaciones_registros(notfr_notify_id, notfr_fecha, notfr_envios, notfr_institucion)VALUES(6, now(), '".$enviosNotf."', '".$config['conf_id_institucion']."')",$conexion);
}

//Recordarles que mejoren su clave
$notificacionID = mysql_fetch_array(mysql_query("SELECT notf_id, DATEDIFF(now(), notf_ultimo_envio), notf_periocidad FROM ".$baseDatosServicios.".notificaciones_automaticas 
WHERE notf_id=7",$conexion));

if($notificacionID[1]>=$notificacionID[2])
{
	mysql_query("UPDATE ".$baseDatosServicios.".notificaciones_automaticas SET notf_ultimo_envio=now()
	WHERE notf_id=7",$conexion);
	
	$enviosNotf = 0;
	
	$cumpleU = mysql_query("SELECT uss_nombre, uss_email, uss_id, uss_clave, uss_notificacion FROM usuarios 
	WHERE (uss_clave='1234' OR uss_clave=uss_usuario OR LENGTH(uss_clave)<=4) AND (uss_tipo=2 or uss_tipo=5)",$conexion);

	while($cumple = mysql_fetch_array($cumpleU)){
		
		if($cumple['uss_notificacion']==1){
			mysql_query("INSERT INTO general_alertas (alr_nombre, alr_descripcion, alr_tipo, alr_usuario, alr_fecha_envio, alr_categoria, alr_importancia, alr_vista)
			VALUES('TU INFORMACIÓN ES IMPORTANTE. Cambia tu clave ahora!', 'Tu clave no debería ser igual a tu usuario o documento. Tampoco que sea la que te asignaron por defecto o que sea <b>1234</b>.<br>
			Te recomendamos una clave que sea mayor a 5 caracteres y que tenga combinación de letras y números.', 2, '".$cumple['uss_id']."', now(), 3, 2, 0)",$conexion);

			$idNotify = mysql_insert_id();
			mysql_query("UPDATE general_alertas SET alr_url_acceso='perfil.php?idNotify=".$idNotify."' WHERE alr_id='".$idNotify."'",$conexion);

			$tituloMsj = "¡".strtoupper($cumple["uss_nombre"])." TU INFORMACIÓN ES IMPORTANTE!";
			$bgTitulo = "#eb4132";
			$contenidoMsj = '
				Hola!<br>
				<b>'.strtoupper($cumple["uss_nombre"]).'</b>, Tu información es muy importante, por esa razón te sugerimos que cambies tu clave de acceso ahora!<br>
				Tu clave no debería ser igual a tu usuario o número de documento. Tampoco que sea la que te asignaron por defecto o que sea <b>1234</b>.<br>
				Te recomendamos colocar una clave que sea mayor a 5 caracteres y que tenga combinación de letras y números.<br>
				Tu clave actual es <b>'.$cumple["uss_clave"].'</b>
				<p>
				<a href="'.$datosUnicosInstitucion["ins_url_acceso"].'?urlDefault=perfil.php">INGRESA A SINTIA AHORA Y CAMBIA TU CLAVE</a>
				</p>
			';

			$fin =  '<html><body style="background-color:#FFF;">';
			$fin .= '
						<center>
							<div style="width:600px; text-align:justify; padding:15px;">
								<img src="http://plataformasintia.com/images/logo.png" width="40">
							</div>

							<div style="font-family:arial; background:'.$bgTitulo.'; width:600px; color:#FFF; text-align:center; padding:15px;">
								<h3>'.$tituloMsj.'</h3>
							</div>

							<div style="font-family:arial; background:#FAFAFA; width:600px; color:#000; text-align:justify; padding:15px;">
								'.$contenidoMsj.'
							</div>

							<div align="center" style="width:600px; color:#000; text-align:center; padding:15px;">
									<img src="http://plataformasintia.com/images/logo.png" width="30"><br>
									¡Que tengas un excelente d&iacute;a!<br>
									<a href="https://plataformasintia.com/">www.plataformasintia.com</a>
							</div>
						</center>
						<p>&nbsp;</p>
					';	
			$fin .='';						
			$fin .=  '<html><body>';							
			$sfrom="info@plataformasintia.com"; //LA CUETA DEL QUE ENVIA EL MENSAJE			
			$sdestinatario=$cumple["uss_email"].", notify@plataformasintia.com"; //CUENTA DEL QUE RECIBE EL MENSAJE			
			$ssubject=$cumple["uss_nombre"].", CAMBIA TU CLAVE AHORA!"; //ASUNTO DEL MENSAJE 				
			$shtml=$fin; //MENSAJE EN SI			
			$sheader="From:".$sfrom."\nReply-To:".$sfrom."\n"; 			
			$sheader=$sheader."X-Mailer:PHP/".phpversion()."\n"; 			
			$sheader=$sheader."Mime-Version: 1.0\n"; 		
			$sheader=$sheader."Content-Type: text/html; charset=UTF-8\r\n"; 			
			@mail($sdestinatario,$ssubject,$shtml,$sheader);

			$enviosNotf ++;
		}
	}
	mysql_free_result($cumpleU);
	
	mysql_query("INSERT INTO ".$baseDatosServicios.".notificaciones_registros(notfr_notify_id, notfr_fecha, notfr_envios, notfr_institucion)VALUES(7, now(), '".$enviosNotf."', '".$config['conf_id_institucion']."')",$conexion);
}
?>