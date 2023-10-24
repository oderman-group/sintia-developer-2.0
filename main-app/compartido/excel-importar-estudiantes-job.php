<?php
$_SERVER['DOCUMENT_ROOT'] = dirname(dirname(dirname(dirname(__FILE__))));
require_once($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/constantes.php");
$conexion = mysqli_connect($servidorConexion, $usuarioConexion, $claveConexion);

require_once(ROOT_PATH."/main-app/class/Sysjobs.php");
require_once(ROOT_PATH."/main-app/class/Estudiantes.php");
require ROOT_PATH.'/librerias/Excel/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;
$parametrosBuscar = array(
	"tipo" =>JOBS_TIPO_IMPORTAR_ESTUDIANTES_EXCEL,
	"estado" =>JOBS_ESTADO_PENDIENTE
);										
$listadoCrobjobs=SysJobs::listar($parametrosBuscar);

while($resultadoJobs = mysqli_fetch_array($listadoCrobjobs, MYSQLI_BOTH)){
	// fecha1 es la primera fecha
	$fechaInicio = new DateTime();
	$finalizado = false;
	$parametros = json_decode($resultadoJobs["job_parametros"], true);
	$institucionId = $resultadoJobs["job_id_institucion"];
	$institucionBd = $resultadoJobs["ins_bd"];
	$anio = $resultadoJobs["job_year"];
	$institucionBdAnio = $resultadoJobs["ins_bd"]."_".$anio;
	$intento = intval($resultadoJobs["job_intentos"]);

	$nombreArchivo= $parametros["nombreArchivo"];
	$filaFinal=$parametros["filaFinal"];
	$actualizarCampo=$parametros["actualizarCampo"];

	mysqli_select_db($conexion,$institucionBdAnio);

	if(empty($config)){
		$configConsulta = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".configuracion WHERE conf_base_datos='".$institucionBd."' AND conf_agno='".$anio."'");
		$config = mysqli_fetch_array($configConsulta, MYSQLI_BOTH);
	}
	try{
		$archivo = explode("..", $nombreArchivo);
		$direccionArchivo=ROOT_PATH."/main-app".$archivo[1];
		
		$documento= IOFactory::load($direccionArchivo);
		$totalHojas= $documento->getSheetCount();
		$hojaActual = $documento->getSheet(0);
		$numFilas = $hojaActual->getHighestDataRow();
		if($filaFinal > 0){
			$numFilas = $filaFinal;
		}
		$letraColumnas= $hojaActual->getHighestDataColumn();
		$f=3;
		$arrayTodos = [];
		$claves_validar = array('mat_tipo_documento', 'mat_documento', 'mat_nombres', 'mat_primer_apellido', 'mat_grado');
		$tiposDocumento = [
			'RC'   => '108', 'CC'   => '105', 'CE'   => '109', 'TI'   => '107', 'PP'   => '110', 'PE'   => '139', 'NUIP' => '106', 'PPT'   => '139'
		];
		$tiposGenero = [
			'M'   => '126', 'F' => '127'
		];
		$estratosArray = array("", 114, 115, 116, 117, 118, 119, 120, 121, 122, 123, 124, 125);
		$sql = "INSERT INTO academico_matriculas(mat_matricula, mat_fecha, mat_primer_apellido, mat_segundo_apellido, mat_nombres, mat_grado, mat_id_usuario, mat_acudiente, mat_documento, mat_tipo_documento, mat_grupo, mat_direccion, mat_genero, mat_fecha_nacimiento, mat_barrio, mat_celular, mat_email, mat_estrato, mat_tipo_sangre, mat_eps, mat_nombre2) VALUES";
		$estudiantesCreados      = array();
		$estudiantesActualizados = array();
		$estudiantesNoCreados    = array();
		$acudientesCreados       = array();
		$acudientesExistentes    = array();
		$acudientesNoCreados     = array();
		while($f<=$numFilas){				
			/*
			***************ACUDIENTE********************
			*/
			$idAcudiente = '0000';

			//Validamos que el documento y el nombre del acudiente no venga vacío
			if(!empty($hojaActual->getCell('R'.$f)->getValue()) && !empty($hojaActual->getCell('S'.$f)->getValue())) {
				$datosAcudiente = [
					'uss_usuario' => $hojaActual->getCell('R'.$f)->getValue(),
					'uss_clave'   => $clavePorDefectoUsuarios,
					'uss_tipo'    => 3,
					'uss_nombre'  => $hojaActual->getCell('S'.$f)->getValue(),
				];

				$numUsuarioAcudiente  = Usuarios::validarExistenciaUsuario($datosAcudiente['uss_usuario']);
				if($numUsuarioAcudiente > 0) {
					$datosAcudienteExistente  = Usuarios::obtenerDatosUsuario($datosAcudiente['uss_usuario']);
					$idAcudiente = $datosAcudienteExistente['uss_id'];
					$acudientesExistentes["FILA_".$f] = $datosAcudienteExistente['uss_usuario'];
				} else {
					try{
						mysqli_query($conexion, "INSERT INTO usuarios(uss_usuario, uss_clave, uss_tipo, uss_nombre, uss_idioma) VALUES ('".$datosAcudiente['uss_usuario']."', '".$datosAcudiente['uss_clave']."', '".$datosAcudiente['uss_tipo']."', '".$datosAcudiente['uss_nombre']."', 1)");
					} catch (Exception $e) {
					SysJobs::actualizarMensaje($resultadoJobs['job_id'],$intento,$e->getMessage());
					}
					$idAcudiente = mysqli_insert_id($conexion);
					$acudientesCreados["FILA_".$f] = $datosAcudiente['uss_usuario'];
				}
			} else {
				$acudientesNoCreados[] = "FILA ".$f;
			}
			/*
			***************ESTUDIANTE********************
			*/
			$todoBien = true;
			$arrayIndividual = [
				'mat_matricula'		   => (strtotime("now")+$f),
				'mat_tipo_documento'   => $hojaActual->getCell('A'.$f)->getValue(),
				'mat_documento'        => $hojaActual->getCell('B'.$f)->getValue(),
				'mat_nombres'          => $hojaActual->getCell('C'.$f)->getValue(),
				'mat_nombre2'          => $hojaActual->getCell('D'.$f)->getValue(),
				'mat_primer_apellido'  => $hojaActual->getCell('E'.$f)->getValue(),
				'mat_segundo_apellido' => $hojaActual->getCell('F'.$f)->getValue(),
				'mat_genero'           => $hojaActual->getCell('G'.$f)->getValue(),
				'mat_fecha_nacimiento' => $hojaActual->getCell('H'.$f)->getFormattedValue(),
				'mat_grado'            => $hojaActual->getCell('I'.$f)->getValue(),
				'mat_grupo'            => $hojaActual->getCell('J'.$f)->getValue(),
				'mat_direccion'        => $hojaActual->getCell('K'.$f)->getValue(),
				'mat_barrio'           => $hojaActual->getCell('L'.$f)->getValue(),
				'mat_celular'          => $hojaActual->getCell('M'.$f)->getValue(),
				'mat_email'            => $hojaActual->getCell('N'.$f)->getValue(),
				'mat_estrato'          => $hojaActual->getCell('O'.$f)->getValue(),
				'mat_tipo_sangre'      => $hojaActual->getCell('P'.$f)->getValue(),
				'mat_eps'              => $hojaActual->getCell('Q'.$f)->getValue(),
				'mat_acudiente'        => $idAcudiente,
			];
			//Validamos que los campos más importantes no vengan vacios
			foreach ($claves_validar as $clave) {
				if (empty($arrayIndividual[$clave])) {
					$todoBien = false;
				}
			}

			$tipoDocumento = $tiposDocumento[$arrayIndividual['mat_tipo_documento']];
			$genero = $tiposGenero[$arrayIndividual['mat_genero']];
			$grado = "";
			if(!empty($arrayIndividual['mat_grado'])) {
				try{
					$consulta= mysqli_query($conexion, "SELECT * FROM academico_grados 
								WHERE gra_nombre='".$arrayIndividual['mat_grado']."'");
				} catch (Exception $e) {
					SysJobs::actualizarMensaje($resultadoJobs['job_id'],$intento,$e->getMessage());
				}
				$num = mysqli_num_rows($consulta);
				if($num > 0){
					$datos=mysqli_fetch_array($consulta, MYSQLI_BOTH);
					$grado = $datos['gra_id'];
				}
			}
			$grupo = 1;
			if(!empty($arrayIndividual['mat_grupo'])) {
				switch($arrayIndividual['mat_grupo']){
					case 'A';
					$grupo = 1;
					break;
					case 'B';
					$grupo = 2;
					break;
					case 'C';
					$grupo = 3;
					break;
				}
			}
			//Si los campos están completos entonces ordenamos los datos del estudiante
			if($todoBien) {
				$numMatricula = Estudiantes::validarExistenciaEstudiante($arrayIndividual['mat_documento']);
				if($numMatricula > 0) {
					$datosEstudianteExistente = Estudiantes::obtenerDatosEstudiante($arrayIndividual['mat_documento']);
					try {
						$camposActualizar = "";
						if(!empty($actualizarCampo)) {
							$camposFormulario = count($actualizarCampo);
							if($camposFormulario > 0) {
								$cont = 0;
								while ($cont < $camposFormulario) {
									if($actualizarCampo[$cont] == 1) {
										$camposActualizar .= ", mat_grado='".$grado."'";
									}
									if($actualizarCampo[$cont] == 2) {
										$camposActualizar .= ", mat_grupo='".$grupo."'";
									}
									if($actualizarCampo[$cont] == 3) {
										$camposActualizar .= ", mat_tipo_documento='".$tipoDocumento."'";
									}
									if($actualizarCampo[$cont] == 4) {
										$camposActualizar .= ", mat_acudiente='".$idAcudiente."'";
									}
									if($actualizarCampo[$cont] == 5) {
										$camposActualizar .= ", mat_nombre2='".$hojaActual->getCell('D'.$f)->getValue()."'";
									}
									if($actualizarCampo[$cont] == 6) {
										$matFechaNacimiento=$hojaActual->getCell('H'.$f)->getFormattedValue();
										$fNacimiento = "0000-00-00";
										if(!empty($matFechaNacimiento)) {
											$arrayBuscar = array('-', '.', ' ', '.-');
											$arrayReemplazar = array('/', '/', '/', '/');
											$fechaReplace = str_replace($arrayBuscar, $arrayReemplazar, $matFechaNacimiento);							
											$fecha = explode ("/", $fechaReplace);
											$dia   = $fecha[2];  
											$mes = $fecha[1];  
											$year  = $fecha[0];
											$fNacimiento = $year.'-'.$mes.'-'.$dia;
										}
										$camposActualizar .= ", mat_fecha_nacimiento='".$fNacimiento."'";
									}
									$cont ++;
								}
							}
						}
						//Actualizamos el acudiente y los datos del formulario
						try{
							mysqli_query($conexion, "UPDATE academico_matriculas SET mat_matricula=mat_matricula $camposActualizar
									WHERE mat_id='".$datosEstudianteExistente['mat_id']."'");
						} catch (Exception $e) {
							SysJobs::actualizarMensaje($resultadoJobs['job_id'],$intento,$e->getMessage());
						}
						//Verificamos que el array no venga vacio y adicionalmente que tenga el campo acudiente seleccionado para actualizarce
						if (!empty($actualizarCampo) && in_array(4, $actualizarCampo)) {
							//Borramos si hay alguna asociación igual y creamos la nueva
							try{
								mysqli_query($conexion, "DELETE FROM usuarios_por_estudiantes WHERE upe_id_usuario='".$idAcudiente."' AND upe_id_estudiante='".$datosEstudianteExistente['mat_id']."'");
							} catch (Exception $e) {
								SysJobs::actualizarMensaje($resultadoJobs['job_id'],$intento,$e->getMessage());
							}
							try{
								mysqli_query($conexion, "INSERT INTO usuarios_por_estudiantes(upe_id_usuario, upe_id_estudiante)VALUES('".$idAcudiente."', '".$datosEstudianteExistente['mat_id']."')");
							} catch (Exception $e) {
								SysJobs::actualizarMensaje($resultadoJobs['job_id'],$intento,$e->getMessage());
							}
						}
						$estudiantesActualizados["FILA_".$f] = $datosEstudianteExistente['mat_documento'];
					} catch (Exception $e) {
						SysJobs::actualizarMensaje($resultadoJobs['job_id'],$intento,$e->getMessage());
						exit();
					}
				} else {
					$fNacimiento = "0000-00-00";
					if(!empty($arrayIndividual['mat_fecha_nacimiento'])) {
						$arrayBuscar = array('-', '.', ' ', '.-');
						$arrayReemplazar = array('/', '/', '/', '/');
						$fechaReplace = str_replace($arrayBuscar, $arrayReemplazar, $arrayIndividual['mat_fecha_nacimiento']);							
						$fecha = explode ("/", $fechaReplace);
						$dia   = $fecha[2];  
						$mes = $fecha[1];  
						$year  = $fecha[0];
						$fNacimiento = $year.'-'.$mes.'-'.$dia;
					}
					$estrato = 116;
					if(!empty($arrayIndividual['mat_estrato'])) {
						$estrato = $estratosArray[$arrayIndividual['mat_estrato']];
					}
					$email = $arrayIndividual['mat_matricula'].'@plataformasintia.com';
					if(!empty($arrayIndividual['mat_email'])) {
						$email = strtolower($arrayIndividual['mat_email']);
					}
					$arrayTodos[$f] = $arrayIndividual;
					try{
						mysqli_query($conexion, "INSERT INTO usuarios(uss_usuario, uss_clave, uss_tipo, uss_nombre, uss_estado, uss_idioma, uss_bloqueado, uss_fecha_registro, uss_responsable_registro, uss_intentos_fallidos, uss_tipo_documento, uss_apellido1, uss_apellido2, uss_nombre2,uss_documento) VALUES ('".$arrayIndividual['mat_documento']."', '".$clavePorDefectoUsuarios."', 4, '".$arrayIndividual['mat_nombres']."', 0, 1, 0, now(), '".$_SESSION["id"]."', 0, '".$tipoDocumento."', '".$arrayIndividual['mat_primer_apellido']."', '".$arrayIndividual['mat_segundo_apellido']."', '".$arrayIndividual['mat_nombre2']."', '".$arrayIndividual['mat_documento']."')");
					} catch (Exception $e) {
						SysJobs::actualizarMensaje($resultadoJobs['job_id'],$intento,$e->getMessage());
					}
					$idUsuarioEstudiante = mysqli_insert_id($conexion);
					$sql .= "('".$arrayIndividual['mat_matricula']."', NOW(), '".$arrayIndividual['mat_primer_apellido']."', '".$arrayIndividual['mat_segundo_apellido']."', '".$arrayIndividual['mat_nombres']."', '".$grado."', '".$idUsuarioEstudiante."', '".$idAcudiente."', '".$arrayIndividual['mat_documento']."', '".$tipoDocumento."', '".$grupo."', '".$arrayIndividual['mat_direccion']."', '".$genero."', '".$fNacimiento."', '".$arrayIndividual['mat_barrio']."', '".$arrayIndividual['mat_celular']."', '".$email."', '".$estrato."', '".$arrayIndividual['mat_tipo_sangre']."', '".$arrayIndividual['mat_eps']."', '".$arrayIndividual['mat_nombre2']."'),";
					$estudiantesCreados["FILA_".$f] = $arrayIndividual['mat_documento'];
				}
			} else {
				$estudiantesNoCreados[] = "FILA ".$f;
			}
			$f++;
		}			
		$numeroEstudiantesCreados = 0;
		if(!empty($estudiantesCreados)){
			$numeroEstudiantesCreados = count($estudiantesCreados);
		}
		$numeroEstudiantesActualizados = 0;
		if(!empty($estudiantesActualizados)){
			$numeroEstudiantesActualizados = count($estudiantesActualizados);
		}
		$numeroEstudiantesNoCreados = 0;
		if(!empty($estudiantesNoCreados)){
			$numeroEstudiantesNoCreados = count($estudiantesNoCreados);
		}

		$numeroAcudientesCreados = 0;
		if(!empty($acudientesCreados)){
			$numeroAcudientesCreados = count($acudientesCreados);
		}

		$numeroAcudientesExistentes = 0;
		if(!empty($acudientesExistentes)){
			$numeroAcudientesExistentes = count($acudientesExistentes);
		}

		$numeroAcudientesNoCreados = 0;
		if(!empty($acudientesNoCreados)){
			$numeroAcudientesNoCreados = count($acudientesNoCreados);
		}

		$respuesta ="<br>Resumen del proceso:<br>
		-Total filas leidas: {$numFilas}<br><br>
				- Estudiantes creados nuevos: {$numeroEstudiantesCreados}<br>
				- Estudiantes que ya estaban creados y se les actualiz&oacute; alguna informaci&oacute;n seleccionada: {$numeroEstudiantesActualizados}<br>
				- Estudiantes que les falt&oacute; algun campo obligatorio: {$numeroEstudiantesNoCreados}<br><br>
				- Acudientes creados nuevos: {$numeroAcudientesCreados}<br>
				- Acudientes que ya estaban creados y no hubo necesidad de volverlos a crear: {$numeroAcudientesExistentes}<br>
				- Acudientes que les falt&oacute; el documento o el nombre: {$numeroAcudientesNoCreados}<br><br>";
		
		if(!empty($estudiantesCreados) && count($estudiantesCreados) > 0) {
			$sql = substr($sql, 0, -1);
			try {
				mysqli_query($conexion, $sql);
			} catch(Exception $e){
				print_r($sql);
				SysJobs::actualizarMensaje($resultadoJobs['job_id'],$intento,$e->getMessage());
				exit();
			}
		}
		if(file_exists($nombreArchivo)){
			unlink($nombreArchivo);
		}
		$finalizado=true;
		
		if($finalizado){
			// fecha2 en este caso es la fecha actual
			$fechaFinal = new DateTime();
			$tiempoTrasncurrido=minutosTranscurridos($fechaInicio,$fechaFinal); 
			$mensaje=$tiempoTrasncurrido."!".$respuesta;
			$datos = array(
				"id" => $resultadoJobs['job_id'],
				"mensaje" => $mensaje,
				"estado" =>JOBS_ESTADO_FINALIZADO,
			);
			SysJobs::actualizar($datos);
			SysJobs::enviarMensaje($resultadoJobs['job_responsable'],$mensaje,$resultadoJobs['job_id'],JOBS_TIPO_IMPORTAR_ESTUDIANTES_EXCEL,JOBS_ESTADO_FINALIZADO);			
		}	

	}catch (Exception $e) {
	SysJobs::actualizarMensaje($resultadoJobs['job_id'],$intento,$e->getMessage());
	}
	
	

}
exit();



function minutosTranscurridos($fecha_i,$fecha_f){
	$intervalo = $fecha_i->diff($fecha_f);
	$minutos = $intervalo->i;
	$segundos = $intervalo->s;
	return " Finalizo en: $minutos Min y $segundos Seg.";
}


?>