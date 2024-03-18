<?php
date_default_timezone_set("America/New_York"); //Zona horaria
include("session-compartida.php");
$idPaginaInterna = 'DT0225';

if ($datosUsuarioActual['uss_tipo'] == TIPO_DIRECTIVO && !Modulos::validarSubRol([$idPaginaInterna])) {
	echo '<script type="text/javascript">window.location.href="../directivo/page-info.php?idmsg=301";</script>';
	exit();
}
include(ROOT_PATH . "/main-app/compartido/historial-acciones-guardar.php");
require_once("../class/Estudiantes.php");
require_once(ROOT_PATH . "/main-app/class/Boletin.php");
require_once(ROOT_PATH . "/main-app/class/Usuarios.php");
require_once(ROOT_PATH . "/main-app/class/UsuariosPadre.php");
$Plataforma = new Plataforma;

$id="";
if(isset($_REQUEST["id"])){$id=base64_decode($_REQUEST["id"]);}
$desde="";
if(isset($_REQUEST["desde"])){$desde=base64_decode($_REQUEST["desde"]);}
$hasta="";
if(isset($_REQUEST["hasta"])){$hasta=base64_decode($_REQUEST["hasta"]);}
$estampilla="";
if(isset($_REQUEST["estampilla"])){$estampilla=base64_decode($_REQUEST["estampilla"]);}
?>
<!doctype html>

<head>
	<meta name="tipo_contenido" content="text/html;" http-equiv="content-type" charset="utf-8">
	<title>SINTIA - Certificados</title>
	<!-- favicon -->
	<link rel="shortcut icon" href="../sintia-icono.png" />
</head>

<body style="font-family:Arial;">
	<p>&nbsp;</p>
	<p>&nbsp;</p>
	<p>&nbsp;</p>
	<p>&nbsp;</p>
	<div align="justify" style="margin: auto;">
		EL SUSCRITO RECTOR DE <b><?= strtoupper($informacion_inst["info_nombre"]) ?></b> DEL MUNICIPIO DE <?= strtoupper($informacion_inst["ciu_nombre"]) ?>, CON
		RECONOCIMIENTO OFICIAL SEGÚN RESOLUCIÓN <?= strtoupper($informacion_inst["info_resolucion"]) ?>, EMANADA DE LA SECRETARÍA
		DE EDUCACIÓN DEPARTAMENTAL DE <?= strtoupper($informacion_inst["dep_nombre"]) ?>, CON DANE <?= $informacion_inst["info_dane"] ?> Y NIT <?= $informacion_inst["info_nit"] ?>, CELULAR <?= $informacion_inst["info_telefono"] ?>.
	</div>
	<p align="center"><b>CERTIFICA</b></p>
	<?php
	$meses = array(" ", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
	$horas[0] = 'CERO';
	$horas[1] = 'UNO';
	$horas[2] = 'DOS';
	$horas[3] = 'TRES';
	$horas[4] = 'CUATRO';
	$horas[5] = 'CINCO';
	$horas[6] = 'SEIS';
	$horas[7] = 'SIETE';
	$horas[8] = 'OCHO';
	$horas[9] = 'NUEVE';
	$horas[10] = 'DIEZ';
	$restaAgnos = ($hasta - $desde) + 1;
	$i = 1;

	$inicio = $desde;
	?>
	<?php
	$restaAgnos = ($hasta - $desde) + 1;
	$i = 1;
	$inicio = $desde;
	while ($i <= $restaAgnos) {
		//SELECCIONO EL ESTUDIANTE, EL GRADO Y EL GRUPO
		$matricula = Estudiantes::obtenerDatosEstudiante($id, $inicio);
		$nombre = Estudiantes::NombreCompletoDelEstudiante($matricula);

        $gradoActual = $matricula['mat_grado'];
        $grupoActual = $matricula['mat_grupo'];

		switch ($matricula["gra_nivel"]) {
			case PREESCOLAR: 
				$educacion = "preescolar"; 
			break;
	
			case BASICA_PRIMARIA: 
				$educacion = "básica primaria"; 
			break;
	
			case BASICA_SECUNDARIA: 
				$educacion = "básica secundaria"; 
			break;
	
			case MEDIA: 
				$educacion = "media"; 
			break;
			
			default: 
				$educacion = "básica"; 
			break;
		}
	?>
		<div align="justify" style="margin: auto;">
			Que <b><?= $nombre ?></b>, identificado con documento número <?= strtoupper($matricula["mat_documento"]); ?>, cursó y aprobó, en esta
			Institución Educativa, el grado <b><?= strtoupper($matricula["gra_nombre"]); ?></b> en año lectivo <?= $inicio; ?> de Educación <?= $educacion?> en la sede PRINCIPAL, con intensidad horaria de acuerdo al <?= $informacion_inst["info_decreto_plan_estudio"] ?>.
		</div>
		<div align="justify" style="margin: auto;"><b><?= strtoupper($matricula["gra_nombre"]); ?> <?= $inicio; ?></b></div>
		<br>
        <table width="100%" rules="all" border="1" style="font-size: 15px;">
            <thead>
                <tr style="font-weight:bold; text-align:center;">
                    <td width="20%">ASIGNATURAS</td>
                    <td width="3%">I.H</td>
                    <td width="3%">DEFINITIVA</a></td>
                    <td width="3%">NIVEL DE DESEMPEÑO</a></td>
                </tr>
            </thead>
            <tbody>
                <?php
                    $consultaAreas= mysqli_query($conexion,"SELECT ar_id, ar_nombre, count(*) AS numMaterias, car_curso, car_grupo FROM ".BD_ACADEMICA.".academico_materias am
                    INNER join ".BD_ACADEMICA.".academico_areas a ON a.ar_id = am.mat_area AND a.institucion={$config['conf_id_institucion']} AND a.year={$inicio}
                    INNER JOIN ".BD_ACADEMICA.".academico_cargas car on car_materia = am.mat_id and car_curso = '".$gradoActual."' AND car_grupo = '".$grupoActual."' AND car.institucion={$config['conf_id_institucion']} AND car.year={$inicio}
                    WHERE am.institucion={$config['conf_id_institucion']} AND am.year={$inicio}
                    GROUP by am.mat_area
                    ORDER BY a.ar_posicion");
                    $numAreas=mysqli_num_rows($consultaAreas);
                    $sumaPromedioGeneral=0;
					$materiasPerdidas = 0;
                    while($datosAreas = mysqli_fetch_array($consultaAreas, MYSQLI_BOTH)){

                        $consultaMaterias= mysqli_query($conexion,"SELECT car_id, car_ih, car_materia, car_docente, car_director_grupo,
                        mat_nombre, mat_area, mat_valor,
                        ar_nombre, ar_posicion
                        bol_estudiante, bol_periodo, bol_nota,
                        bol_nota * (mat_valor/100) AS notaArea
                        FROM ".BD_ACADEMICA.".academico_cargas car
                        INNER JOIN ".BD_ACADEMICA.".academico_materias am ON am.mat_id = car_materia AND am.institucion={$config['conf_id_institucion']} AND am.year={$inicio}
                        INNER JOIN ".BD_ACADEMICA.".academico_areas a ON a.ar_id = am.mat_area AND a.institucion={$config['conf_id_institucion']} AND a.year={$inicio}
                        LEFT JOIN ".BD_ACADEMICA.".academico_boletin bol ON bol_carga=car_id AND bol_periodo ='".$config["conf_periodos_maximos"]."' AND bol_estudiante = '".$matricula['mat_id']."' AND bol.institucion={$config['conf_id_institucion']} AND bol.year={$inicio}
                        WHERE car_curso = '".$datosAreas['car_curso']."' AND car_grupo = '".$datosAreas['car_grupo']."' AND car.institucion={$config['conf_id_institucion']} AND car.year={$inicio} AND am.mat_area = '".$datosAreas['ar_id']."'");
                        $notaArea=0;
                        $notaAreasPeriodos=0;
                        while($datosMaterias = mysqli_fetch_array($consultaMaterias, MYSQLI_BOTH)){
                            //DIRECTOR DE GRUPO
                            if($datosMaterias["car_director_grupo"]==1){
                                $idDirector=$datosMaterias["car_docente"];
                            }

                            //VARIABLES NECESARIAS
                            $background='';
                            $ih=$datosMaterias["car_ih"];
                            if($datosAreas['numMaterias']>1){
                ?>
                                <tr>
                                    <td><?=$datosMaterias['mat_nombre']?></td>
                                    <td align="center"><?=$datosMaterias['car_ih']?></td>
                                    <?php
                                        $notaMateriasPeriodosTotal=0;
                                        $ultimoPeriodo = $config["conf_periodos_maximos"];
                                        for($i=1;$i<=$config["conf_periodos_maximos"];$i++){
											$consultaPeriodos=mysqli_query($conexion,"SELECT * FROM ".BD_ACADEMICA.".academico_boletin WHERE bol_carga='".$datosMaterias['car_id']."' AND bol_periodo='".$i."' AND bol_estudiante = '".$matricula['mat_id']."' AND institucion={$config['conf_id_institucion']} AND year={$inicio}");
											$datosPeriodos=mysqli_fetch_array($consultaPeriodos, MYSQLI_BOTH);
											$notaMateriasPeriodos=$datosPeriodos['bol_nota'];
											$notaMateriasPeriodos=round($notaMateriasPeriodos, 1);
											$notaMateriasPeriodosTotal+=$notaMateriasPeriodos;

											if (empty($datosPeriodos['bol_periodo'])){
												$ultimoPeriodo -= 1;
											}
                                        }//FIN FOR

                                        //ACOMULADO PARA LAS MATERIAS
                                        $notaAcomuladoMateria = $notaMateriasPeriodosTotal / $ultimoPeriodo;
                                        $notaAcomuladoMateria = round($notaAcomuladoMateria,1);
                                        if(strlen($notaAcomuladoMateria) === 1 || $notaAcomuladoMateria == 10){
                                            $notaAcomuladoMateria = $notaAcomuladoMateria.".0";
                                        }
                                        $estiloNotaAcomuladoMaterias = Boletin::obtenerDatosTipoDeNotas($config['conf_notas_categoria'], $notaAcomuladoMateria,$inicio);
                                        if($notaAcomuladoMateria<10){
                                            $estiloNotaAcomuladoMaterias['notip_nombre']="Bajo";
                                        }
                                        if($notaAcomuladoMateria>50){
                                            $estiloNotaAcomuladoMaterias['notip_nombre']="Superior";
                                        }
                                    ?>
                                    <td align="center"><?=$notaAcomuladoMateria?></td>
                                    <td align="center"><?=$estiloNotaAcomuladoMaterias['notip_nombre']?></td>
                                </tr>
                    <?php
                            $ih="";
                            $ausencia="";
                            $background='style="background: #EAEAEA"';
                            }

                            //NOTA PARA LAS AREAS
                            if(!empty($datosMaterias['notaArea'])) $notaArea+=round($datosMaterias['notaArea'], 1);

                        } //FIN WHILE DE LAS MATERIAS
                    ?>
                    <!--********SE IMPRIME LO REFERENTE A LAS AREAS*******-->
						<tr>
							<td <?=$background?>><?=$datosAreas['ar_nombre']?></td>
							<td align="center"><?=$ih?></td>
							<?php
								$notaAreasPeriodosTotal=0;
								$ultimoPeriodoAreas = $config["conf_periodos_maximos"];
								for($i=1;$i<=$config["conf_periodos_maximos"];$i++){
									$consultaAreasPeriodos=mysqli_query($conexion,"SELECT mat_valor,
									bol_estudiante, bol_periodo, bol_nota,
									SUM(bol_nota * (mat_valor/100)) AS notaArea
									FROM ".BD_ACADEMICA.".academico_cargas car
									INNER JOIN ".BD_ACADEMICA.".academico_materias am ON am.mat_id = car_materia AND am.institucion={$config['conf_id_institucion']} AND am.year={$inicio}
									INNER JOIN ".BD_ACADEMICA.".academico_boletin bol ON bol_carga=car_id AND bol_periodo='".$i."' AND bol_estudiante = '".$matricula['mat_id']."' AND bol.institucion={$config['conf_id_institucion']} AND bol.year={$inicio}
									WHERE am.mat_area = '".$datosAreas['ar_id']."' AND car.institucion={$config['conf_id_institucion']} AND car.year={$inicio}
									GROUP BY am.mat_area");
									$datosAreasPeriodos=mysqli_fetch_array($consultaAreasPeriodos, MYSQLI_BOTH);
									if(!empty($datosAreasPeriodos['notaArea'])) $notaAreasPeriodos=round($datosAreasPeriodos['notaArea'], 1);
									$notaAreasPeriodosTotal+=$notaAreasPeriodos;

									if (empty($datosAreasPeriodos['bol_periodo'])){
										$ultimoPeriodoAreas -= 1;
									}
								}
						
								//ACOMULADO PARA LAS AREAS
								$notaAcomuladoArea = $notaAreasPeriodosTotal / $ultimoPeriodoAreas;
								$notaAcomuladoArea = round($notaAcomuladoArea,1);
								if(strlen($notaAcomuladoArea) === 1 || $notaAcomuladoArea == 10){
									$notaAcomuladoArea = $notaAcomuladoArea.".0";
								}
								$estiloNotaAcomuladoAreas = Boletin::obtenerDatosTipoDeNotas($config['conf_notas_categoria'], $notaAcomuladoArea,$inicio);
								if($notaAcomuladoArea<10){
									$estiloNotaAcomuladoAreas['notip_nombre']="Bajo";
								}
								if($notaAcomuladoArea>50){
									$estiloNotaAcomuladoAreas['notip_nombre']="Superior";
								}

								if($notaAcomuladoArea < $config['conf_nota_minima_aprobar']){
									$materiasPerdidas++;
								}
							?>
							<td align="center"><?=$notaAcomuladoArea?></td>
							<td align="center"><?=$estiloNotaAcomuladoAreas['notip_nombre']?></td>
						</tr>
                    <?php
                        } //FIN WHILE DE LAS AREAS
                    ?>
            </tbody>
            <tfoot style="font-size: 13px;">
				<tr style="color:#000;">
					<td style="padding-left: 10px; font-weight:bold; font-size:13px;" colspan="8" align="center">
						<mark>
							<?php
								$consultaEstiloNota = Boletin::listarTipoDeNotas($config["conf_notas_categoria"],$inicio);
								$numEstiloNota=mysqli_num_rows($consultaEstiloNota);
								$i=1;
								while($estiloNota = mysqli_fetch_array($consultaEstiloNota, MYSQLI_BOTH)){
									$diagonal=" / ";
									if($i==$numEstiloNota){
										$diagonal="<br>";
									}
									
									echo strtoupper($estiloNota['notip_nombre']).": ".$estiloNota['notip_desde']." - ".$estiloNota['notip_hasta'].$diagonal;
									$i++;
								}

								$consultaMaterias = mysqli_query($conexion, "SELECT mat_valor, mat_nombre, mat_area
								FROM ".BD_ACADEMICA.".academico_cargas ac
								INNER JOIN ".BD_ACADEMICA.".academico_materias am ON mat_id=car_materia AND am.institucion={$config['conf_id_institucion']} AND am.year={$inicio}
								WHERE ac.institucion={$config['conf_id_institucion']} AND ac.year={$inicio} AND car_curso='".$gradoActual."' AND car_grupo='".$grupoActual."'
								AND mat_area IN (
									SELECT mat_area
									FROM ".BD_ACADEMICA.".academico_materias
									WHERE institucion={$config['conf_id_institucion']} AND year={$inicio}
									GROUP BY mat_area
									HAVING COUNT(DISTINCT mat_id) > 1
								)
								ORDER BY mat_area, mat_valor");
								$numMaterias=mysqli_num_rows($consultaMaterias);
								$areaAnterior = null;
								$valorAreas = "PORCENTAJES ÁREAS:";
								while($datosArea = mysqli_fetch_array($consultaMaterias, MYSQLI_BOTH)){
									
									if(is_null($areaAnterior) || $areaAnterior == $datosArea['mat_area']){
										$areaAnterior = $datosArea['mat_area'];
									}

									$diagonal=" ";
									if(!is_null($areaAnterior) && $areaAnterior != $datosArea['mat_area']){
										$diagonal=" // ";
										$areaAnterior = $datosArea['mat_area'];
									}
									
									$valorAreas .= $diagonal.strtoupper($datosArea['mat_nombre'])." (".$datosArea['mat_valor'].")";
								}
								echo $valorAreas;
							?>
						</mark>
					</td>
				</tr>
            </tfoot>
        </table>

		<?php
		$nivelaciones = mysqli_query($conexion, "SELECT niv_definitiva, niv_acta, niv_fecha_nivelacion, mat_nombre FROM " . BD_ACADEMICA . ".academico_nivelaciones niv 
		INNER JOIN " . BD_ACADEMICA . ".academico_cargas car ON car_id=niv.niv_id_asg AND car.institucion={$config['conf_id_institucion']} AND car.year={$inicio}
		INNER JOIN " . BD_ACADEMICA . ".academico_materias am ON mat_id=car_materia AND am.institucion={$config['conf_id_institucion']} AND am.year={$inicio}
		WHERE niv.niv_cod_estudiante='" . $id . "' AND niv.institucion={$config['conf_id_institucion']} AND niv.year={$inicio}");
		$numNiv = mysqli_num_rows($nivelaciones);
		if ($numNiv > 0) {
			echo "El(la) Estudiante niveló las siguientes materias:<br>";
			while ($niv = mysqli_fetch_array($nivelaciones, MYSQLI_BOTH)) {
				echo "<b>" . strtoupper($niv["mat_nombre"]) . " (" . $niv["niv_definitiva"] . ")</b> Segun acta " . $niv["niv_acta"] . " en la fecha de " . $niv["niv_fecha_nivelacion"] . "<br>";
			}
		}
		// SABER QUE MATERIAS TIENE PERDIDAS
		$cargasAcademicasC = mysqli_query($conexion, "SELECT car_id FROM " . BD_ACADEMICA . ".academico_cargas WHERE car_curso='" . $matricula["mat_grado"] . "' AND car_grupo='" . $matricula["mat_grupo"] . "' AND institucion={$config['conf_id_institucion']} AND year={$inicio}");
		$materiasPerdidas = 0;
		$vectorMP = array();
		$periodoFinal = $config['conf_periodos_maximos'];
		while ($cargasC = mysqli_fetch_array($cargasAcademicasC, MYSQLI_BOTH)) {
			//OBTENEMOS EL PROMEDIO DE LAS CALIFICACIONES
			$consultaBoletinC = mysqli_query($conexion, "SELECT avg(bol_nota) AS promedio, MAX(bol_periodo) AS periodo FROM " . BD_ACADEMICA . ".academico_boletin WHERE bol_estudiante='" . $id . "' AND bol_carga='" . $cargasC["car_id"] . "' AND institucion={$config['conf_id_institucion']} AND year={$inicio}");
			$boletinC = mysqli_fetch_array($consultaBoletinC, MYSQLI_BOTH);
			$notaC = round($boletinC['promedio'], 1);
			if ($notaC < $config[5]) {
				$vectorMP[$materiasPerdidas] = $cargasC["car_id"];
				$materiasPerdidas++;
			}

			if ($boletinC['periodo'] < $config['conf_periodos_maximos']) {
				$periodoFinal = $boletinC['periodo'];
			}
		}
		//FIN DE LAS MATERIAS QUE
		if ($materiasPerdidas > 0) {
			$m = 0;
			$niveladas = 0;
			while ($m < $materiasPerdidas) {
				$nMP = mysqli_query($conexion, "SELECT * FROM " . BD_ACADEMICA . ".academico_nivelaciones WHERE niv_cod_estudiante='" . $id . "' AND niv_id_asg='" . $vectorMP[$m] . "' AND niv_definitiva>='" . $config[5] . "' AND institucion={$config['conf_id_institucion']} AND year={$inicio}");
				$numNivMP = mysqli_num_rows($nMP);
				if ($numNivMP > 0) {
					$niveladas++;
				}
				$m++;
			}
		}
		if ($materiasPerdidas == 0 || $niveladas >= $materiasPerdidas) {
			$msj = "<center>EL (LA) ESTUDIANTE " . $nombre . " FUE PROMOVIDO(A) AL GRADO SIGUIENTE</center>";
		} else {
			$msj = "<center>EL (LA) ESTUDIANTE " . $nombre . " NO FUE PROMOVIDO(A) AL GRADO SIGUIENTE</center>";
		}

		if ($periodoFinal < $config["conf_periodos_maximos"] && $matricula["mat_estado_matricula"] == CANCELADO) {
			$msj = "<center>EL(LA) ESTUDIANTE " . $nombre . " FUE RETIRADO SIN FINALIZAR AÑO LECTIVO</center>";
		}
		?>
		<div align="left" style="font-weight:bold; font-style:italic; font-size:12px; margin-bottom:10px;"><?= $msj; ?></div><br>
	<?php

		$inicio++;

		$i++;
	}

	?>
	<?php if (date('m') < 10) {
		$mes = substr(date('m'), 1);
	} else {
		$mes = date('m');
	} ?>
	<span style="font-size:16px; text-align:justify;">
		Se expide en <?= ucwords(strtolower($informacion_inst["ciu_nombre"])) ?> el <?= date("d"); ?> de <?= $meses[$mes]; ?> de <?= date("Y"); ?>, con destino al
		interesado. <?php if ($config['conf_estampilla_certificados'] == SI) { echo "Se anula estampilla número <mark>".$estampilla."</mark>, según ordenanza 012/05 y decreto 005/06."; } ?>
	</span>

	<p>&nbsp;</p>

	<table width="100%" cellspacing="0" cellpadding="0" rules="none" border="0" style="text-align:center; font-size:10px;">

		<tr>

			<td align="left">
				<p>&nbsp;</p>
				<p style="height:0px;"></p>_________________________________<br>
				<?php
					$rector = Usuarios::obtenerDatosUsuario($informacion_inst["info_rector"]);
					$nombreRector = UsuariosPadre::nombreCompletoDelUsuario($rector);
				?>
				<?= $nombreRector ?><br>
				Rector(a)
			</td>

		</tr>

	</table>
	<?php
	include(ROOT_PATH . "/main-app/compartido/guardar-historial-acciones.php");
	?>
	<script type="application/javascript">
		print();
	</script>

</body>

</html>