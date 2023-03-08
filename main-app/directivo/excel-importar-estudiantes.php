<?php
include("session.php");
include("../class/Usuarios.php");
include("../class/Estudiantes.php");
require '../../librerias/Excel/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$temName=$_FILES['planilla']['tmp_name'];
$archivo = $_FILES['planilla']['name'];
$destino = "../files/excel/";
$explode = explode(".", $archivo);
$extension = end($explode);
$fullArchivo = uniqid('importado_').".".$extension;
$nombreArchivo= $destino.$fullArchivo;

if($extension == 'xlsx'){

	if (move_uploaded_file($temName, $nombreArchivo)) {		
		
		if ($_FILES['planilla']['error'] === UPLOAD_ERR_OK){

			$documento= IOFactory::load($nombreArchivo);
			$totalHojas= $documento->getSheetCount();

			$hojaActual= $documento->getSheet(0);
			$numFilas= 208 + 3;
			$letraColumnas= $hojaActual->getHighestDataColumn();
			$f=3;
			$numAsociadosBien = 0;
			$numEstudiantesNoEncontrados = 0;
			$numInfoIncompleta = 0;
			$numAcudientesYaExistentes = 0;
			while($f<=$numFilas){
				
				$documentoEstudiante = $hojaActual->getCell('B'.$f)->getValue();

				$documentoAcudiente = $hojaActual->getCell('R'.$f)->getValue();
				$primerNombre       = $hojaActual->getCell('S'.$f)->getValue();
				$segundoNombre      = $hojaActual->getCell('T'.$f)->getValue();
				$primerApellido     = $hojaActual->getCell('U'.$f)->getValue();
				$segundoApellido    = $hojaActual->getCell('V'.$f)->getValue();
				
				if(!empty($documentoEstudiante) && !empty($documentoAcudiente)) {
				
					//Paso 0: Preguntar si el acudiente existe
					$acudienteExiste = Usuarios::validarExistenciaUsuario($documentoAcudiente);

					if($acudienteExiste > 0) {
						$datosAcudiente = Usuarios::obtenerDatosUsuario($documentoAcudiente);
						$idAcudiente    = $datosAcudiente['uss_id'];
						$numAcudientesYaExistentes ++;
						echo "Acudiente ya existe: ".$documentoAcudiente."<br>";
					} else {
						//Paso 1: Insertamos al acudiente
						mysqli_query($conexion, "INSERT INTO usuarios(uss_usuario, uss_clave, uss_tipo, uss_nombre, uss_estado, uss_idioma, uss_bloqueado, uss_fecha_registro, uss_responsable_registro, uss_intentos_fallidos, uss_tipo_documento, uss_apellido1, uss_apellido2, uss_nombre2, uss_documento) VALUES ('".$documentoAcudiente."', '".$clavePorDefectoUsuarios."', 3, '".$primerNombre."', 1, 1, 0, NOW(), '".$_SESSION['id']."', 0, '105', '".$primerApellido."', '".$segundoApellido."', '".$segundoNombre."', '".$documentoAcudiente."')");
						//Paso 2: Obtenemos el ID del acudiente
						$idAcudiente = mysqli_insert_id($conexion);
					}

					//Paso 3: Obtenemos datos del estudiante
					$datosEstudiante = Estudiantes::obtenerDatosEstudiante($documentoEstudiante);

					if(is_array($datosEstudiante)) {
						//Paso 4: Actualizamos la matricula
						mysqli_query($conexion, "UPDATE academico_matriculas SET mat_acudiente='".$idAcudiente ."' WHERE mat_id='".$datosEstudiante['mat_id']."'");

						//Paso 5: Hacemos la asociación
						mysqli_query($conexion, "DELETE FROM usuarios_por_estudiantes WHERE upe_id_usuario='".$idAcudiente."' AND upe_id_estudiante='".$datosEstudiante['mat_id']."'");

						mysqli_query($conexion, "INSERT INTO usuarios_por_estudiantes(upe_id_usuario, upe_id_estudiante)VALUES('".$idAcudiente."', '".$datosEstudiante['mat_id']."')");

						$numAsociadosBien ++;

						echo "Asociados correctamente ({$documentoAcudiente} - {$documentoEstudiante})<hr>";

					} else {
						$numEstudiantesNoEncontrados ++;
					}
					
				} else {
					$numInfoIncompleta ++;
				}

				$f++;
			}
			
			if(file_exists($nombreArchivo)){
				unlink($nombreArchivo);
			}

			echo "Asociados BIEN: ".$numAsociadosBien."<br>";
			echo "Estudiantes no encontrados: ".$numEstudiantesNoEncontrados."<br>";
			echo "Info Imcompleta: ".$numInfoIncompleta."<br>";
			echo "Acudientes ya existentes: ".$numAcudientesYaExistentes."<br>"; 
			
			

		}else{
			switch ($_FILES['planilla']['error']) {
				case UPLOAD_ERR_INI_SIZE:
					$message = "El fichero subido excede la directiva upload_max_filesize de php.ini.";
					break;
				case UPLOAD_ERR_FORM_SIZE:
					$message = "El fichero subido excede la directiva MAX_FILE_SIZE especificada en el formulario HTML.";
					break;
		
				case UPLOAD_ERR_PARTIAL:
					$message = "El fichero fue sólo parcialmente subido.";
					break;
		
				case UPLOAD_ERR_NO_FILE:
					$message = "No se subió ningún fichero.";
					break;
		
				case UPLOAD_ERR_NO_TMP_DIR:
					$message = "Falta la carpeta temporal.";
					break;
		
				case UPLOAD_ERR_CANT_WRITE:
					$message = "No se pudo escribir el fichero en el disco.";
					break;
				case UPLOAD_ERR_EXTENSION:
					$message = "Una extensión de PHP detuvo la subida de ficheros. PHP no proporciona una forma de determinar la extensión que causó la parada de la subida de ficheros; el examen de la lista de extensiones cargadas con phpinfo() puede ayudar.";
					break;
			}
			echo '<script type="text/javascript">window.location.href="estudiantes-importar-excel.php?error=ER_DT_7&msj='.$message.'";</script>';
			exit();
		}
	}else{
		echo '<script type="text/javascript">window.location.href="estudiantes-importar-excel.php?error=ER_DT_8";</script>';
		exit();
	}	
}else{
	$message = "Este archivo no es admitido, por favor verifique que el archivo a importar sea un excel (.xlsx)";
	echo '<script type="text/javascript">window.location.href="estudiantes-importar-excel.php?error=ER_DT_7&msj='.$message.'";</script>';
	exit();
}