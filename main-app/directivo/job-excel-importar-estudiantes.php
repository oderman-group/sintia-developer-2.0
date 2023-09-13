<?php
include("session.php");
require_once("../class/Usuarios.php");
require_once("../class/Estudiantes.php");
require '../../librerias/Excel/vendor/autoload.php';
require_once("../class/Sysjobs.php");

use PhpOffice\PhpSpreadsheet\IOFactory;
Modulos::validarAccesoDirectoPaginas();

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
				$parametros = array(
					"nombreArchivo"=>$nombreArchivo,
					"filaFinal"=>$_POST["filaFinal"],
					"actualizarCampo"=>$_POST["actualizarCampo"]
				);
				try{
					$camposActualizar="";
					$Separador="";
					foreach ($_POST["actualizarCampo"] as $filtro) {
						
						if ($filtro == '1') {
							$camposActualizar =$camposActualizar.$Separador."Grado";
						}
						if ($filtro == '2') {
							$camposActualizar = $camposActualizar.$Separador."Grupo";
						}
						if ($filtro == '3') {
							$camposActualizar = $camposActualizar.$Separador."Tipo de Documento";
						}
						if ($filtro == '4') {
							$camposActualizar = $Separador.$camposActualiza."Acudiente";
						}
						if ($filtro == '5') {
							$camposActualizar = $camposActualizar.$Separador."Segundo nombre del estudiante";
						}
						if ($filtro == '6') {
							$camposActualizar = $camposActualizar.$Separador."Fecha de nacimiento";
						}
						if ($Separador == "") {
							$Separador=" , ";
						}
					}
					if(!empty($_POST["actualizarCampo"])){
						$camposActualizar="Campos a actualizar (".$camposActualizar.")";
					}
					$mensaje='Se generó Jobs para importar excel del archivo ['.$archivo.'] hasta la fila '.$_POST["filaFinal"].' '.$camposActualizar;
					$mensaje=SysJobs::registrar(JOBS_TIPO_IMPORTAR_ESTUDIANTES_EXCEL,JOBS_PRIORIDAD_BAJA,$parametros,$mensaje);	
					include("../compartido/guardar-historial-acciones.php");	
					echo '<script type="text/javascript">window.location.href="../directivo/estudiantes-importar-excel.php?success=SC_DT_4&summary=' . $mensaje.'";</script>';
					exit();
				} catch (Exception $e) {
					include("../compartido/error-catch-to-report.php");
				}
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