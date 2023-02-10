<?php
include("session.php");
require '../../librerias/Excel/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$temName=$_FILES['planilla']['tmp_name'];
$archivo = $_FILES['planilla']['name'];
$destino = "../files/excel/";
$nombreArchivo= $destino.$archivo;

if (move_uploaded_file($temName, $nombreArchivo)) {
	
	if ($_FILES['planilla']['error'] === UPLOAD_ERR_OK){

		$documento= IOFactory::load($nombreArchivo);
		$totalHojas= $documento->getSheetCount();

		$hojaActual= $documento->getSheet(0);
		$numFilas= $hojaActual->getHighestDataRow();
		$letraColumnas= $hojaActual->getHighestDataColumn();
		$f=2;
		$si=0;
		$no=0;
		while($f<=$numFilas){
			$A= $hojaActual->getCell('A'.$f)->getValue();
			$B= $hojaActual->getCell('B'.$f)->getValue();
			$C= $hojaActual->getCell('C'.$f)->getValue();
			$D= $hojaActual->getCell('D'.$f)->getValue();
			$E= $hojaActual->getCell('E'.$f)->getValue();
			$F= $hojaActual->getCell('F'.$f)->getValue();
			$G= $hojaActual->getCell('G'.$f)->getValue();
			$H= $hojaActual->getCell('H'.$f)->getFormattedValue();
			$I= $hojaActual->getCell('I'.$f)->getValue();
			$J= $hojaActual->getCell('J'.$f)->getValue();
			$K= $hojaActual->getCell('K'.$f)->getValue();
			$L= $hojaActual->getCell('L'.$f)->getValue();
			$M= $hojaActual->getCell('M'.$f)->getValue();
			$N= $hojaActual->getCell('N'.$f)->getValue();
			$O= $hojaActual->getCell('O'.$f)->getValue();
			$P= $hojaActual->getCell('P'.$f)->getValue();
			$Q= $hojaActual->getCell('Q'.$f)->getValue();
			
			if($A!='' OR $B!='' OR $C!='' OR $D!='' OR $E!='' OR $F!='' OR $G!='' OR $H!='' OR $I!='' OR $J!='' OR $K!='' OR $L!='' OR $M!='' OR $N!='' OR $O!='' OR $P!='' OR $Q!=''){
				if($B!='' OR $C!='' OR $E!='' OR $I!=''){
					$tDocumento = "";
					if(isset($A) AND $A!='') {
						switch($A){
							case 'RC';
								$tDocumento = 108;
							break;

							case 'CC';
								$tDocumento = 105;
							break;
							
							case 'CE';
								$tDocumento = 109;
							break;
							
							case 'TI';
								$tDocumento = 107;
							break;
							
							case 'PP';
								$tDocumento = 110;
							break;
							
							case 'PE';
								$tDocumento = 139;
							break;
							
							case 'NUIP';
								$tDocumento = 106;
							break;
						}
						
					}
					
					$documento = "";
					if(isset($B) AND $B!='') {
						$documento = $B;
					}

					$nombre1 = "";
					if(isset($C) AND $C!='') {
						$nombre1 = $C;
					}
					
					$nombre2 = "";
					if(isset($D) AND $D!='') {
						$nombre2 = $D;
					}
					
					$apellido1 = "";
					if(isset($E) AND $E!='') {
						$apellido1 = $E;
					}
					
					$apellido2 = "";
					if(isset($F) AND $F!='') {
						$apellido2 = $F;
					}
					
					$genero = "";
					if(isset($G) AND $G!='') {
						switch($G){
							case 'M';
								$genero = 126;
							break;

							case 'F';
								$genero = 127;
							break;
						}
					}
					
					$fNacimiento = "0000-00-00";
					if(isset($H) AND $H!='') {
						$fecha = explode ("/", $H); 
						$dia   = $fecha[0];  
						$mes = $fecha[1];  
						$year  = $fecha[2];
						$fNacimiento = $year.'-'.$mes.'-'.$dia;
					}

					$grado = "";
					if(isset($I) AND $I!='') {
						
						try{
							$consulta= mysqli_query($conexion, "SELECT * FROM academico_grados WHERE gra_nombre='".$I."'");
						} catch (Exception $e) {
							echo 'Excepción capturada: ',  $e->getMessage(), "\n";
							exit();
						}
						$datos=mysqli_fetch_array($consulta, MYSQLI_BOTH);
						$grado = $datos['gra_id'];
					}
					
					$grupo = 4;
					if(isset($J) AND $J!='') {
						switch($J){
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
					
					$direccion = "";
					if(isset($K) AND $K!='') {
						$direccion = $K;
					}
					
					$barrio = "";
					if(isset($L) AND $L!='') {
						$barrio = $L;
					}
					
					$celular = "";
					if(isset($M) AND $M!='') {
						$celular = $M;
					}
					
					$email = "notiene@notiene.com";
					if(isset($N) AND $N!='') {
						$email = $N;
					}
					
					$estrato = 116;
					if(isset($O) AND $O!='') {
						switch($O){
							case 1;
								$estrato = 114;
							break;

							case 2;
								$estrato = 115;
							break;

							case 3;
								$estrato = 116;
							break;
							case 4;
								$estrato = 117;
							break;

							case 5;
								$estrato = 118;
							break;

							case 6;
								$estrato = 119;
							break;
							case 7;
								$estrato = 120;
							break;

							case 8;
								$estrato = 121;
							break;

							case 9;
								$estrato = 122;
							break;
							case 10;
								$estrato = 123;
							break;

							case 11;
								$estrato = 124;
							break;

							case 12;
								$estrato = 125;
							break;
						}
					}
					
					$tSangre = "";
					if(isset($P) AND $P!='') {
						$tSangre = $P;
					}
					
					$eps = "";
					if(isset($Q) AND $Q!='') {
						$eps = $Q;
					}

					try{
						mysqli_query($conexion, "INSERT INTO usuarios(uss_usuario,uss_clave,uss_tipo,uss_nombre,uss_estado,uss_foto,uss_portada,uss_idioma,uss_email,uss_fecha_nacimiento,uss_celular,uss_genero,uss_bloqueado,uss_fecha_registro,uss_responsable_registro,uss_direccion,uss_intentos_fallidos,uss_apellido1,uss_apellido2,uss_nombre2) VALUES ('".$documento."', '12345678', 4, '".$nombre1."',0,'default.png','default.png',1,'".$email."','".$fNacimiento."','".$celular."', '".$genero."',0, now(),'".$_SESSION["id"]."', '".$direccion."',0, '".$apellido1."','".$apellido2."','".$nombre2."')");
					} catch (Exception $e) {
						echo 'Excepción capturada: ',  $e->getMessage(), "\n";
						exit();
					}
					$idRegistro = mysqli_insert_id($conexion);

					try{
						mysqli_query($conexion, "INSERT INTO academico_matriculas(mat_fecha,mat_primer_apellido,mat_segundo_apellido,mat_nombres,mat_grado,mat_grupo,mat_genero,mat_fecha_nacimiento,mat_tipo_documento,mat_documento,mat_direccion,mat_barrio,mat_celular,mat_estrato,mat_tipo,mat_estado_matricula,mat_id_usuario,mat_eliminado,mat_email,mat_inclusion,mat_extranjero,mat_estado_agno,mat_solicitud_inscripcion,mat_tipo_sangre,mat_eps,mat_nombre2) VALUES (now(), '".$apellido1."','".$apellido2."','".$nombre1."','".$grado."','".$grupo."', '".$genero."','".$fNacimiento."','".$tDocumento."','".$documento."', '".$direccion."','".$barrio."','".$celular."','".$estrato."',128,4,'".$idRegistro."',0,'".$email."',0,0,0,0,'".$tSangre."','".$eps."','".$nombre2."')");
					} catch (Exception $e) {
						echo 'Excepción capturada: ',  $e->getMessage(), "\n";
						exit();
					}
					$si++;
				}else{
					$no++;
				}
			}
			$f++;
		}
		echo '<script type="text/javascript">window.location.href="estudiantes.php?cantidad=10&success=SC_DT_4&si='.$si.'&no='.$no.'";</script>';
		exit();
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