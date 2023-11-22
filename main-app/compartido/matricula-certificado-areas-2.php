<?php
date_default_timezone_set("America/New_York");//Zona horaria
include("../directivo/session.php");
require_once("../class/Estudiantes.php");
require_once(ROOT_PATH."/main-app/class/Boletin.php");
$Plataforma = new Plataforma;

$modulo = 1;

?>

<!doctype html>

<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->

<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->

<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->

<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->

<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->

<head>

	<meta name="tipo_contenido"  content="text/html;" http-equiv="content-type" charset="utf-8">

    <title>SINTIA - Certificados</title>
	<!-- favicon -->
	<link rel="shortcut icon" href="../sintia-icono.png" />

</head>



<body style="font-family:Arial;">





<div align="center" style="margin-bottom:20px; margin-top:20px;">

<img src="https://main.plataformasintia.com/app-sintia/main-app/files/images/logo/WhatsApp%20Image%202022-01-19%20at%2011.21.24%20AM.jpeg" width="100"><br><br>
    
<b>LICEO INFANTIL GRANDES INNOVADORES</b><br>
Carácter Privado en Jornada Diurna<br>
Reconocimiento Oficial por resolución 023221 del 02 de Diciembre de 2015<br>
Secretaría de Educación de Santander<br>
Código DANE 368235800001-- NIT 32.858.419-1<br><br>

<b>LA DIRECTORA DEL LICEO INFANTIL GRANDES INNOVADORES</b>


</div>



<p align="center">C E R T I F I C A N</p>



<?php
$meses = array(" ","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
$horas[0]='CERO'; $horas[1]='UNO'; $horas[2]='DOS'; $horas[3]='TRES'; $horas[4]='CUATRO'; $horas[5]='CINCO'; $horas[6]='SEIS'; $horas[7]='SIETE'; $horas[8]='OCHO'; $horas[9]='NUEVE'; $horas[10]='DIEZ'; 

$restaAgnos = ($_POST["hasta"]-$_POST["desde"])+1;

$i=1;

$inicio = $_POST["desde"];

$grados = "";
while($i<=$restaAgnos){

	mysqli_select_db($conexion, $config['conf_base_datos']."_".$inicio);
	
	$estudiante = Estudiantes::obtenerDatosEstudiante($_POST["id"]);
	$nombre = Estudiantes::NombreCompletoDelEstudiante($estudiante);
	
	if($estudiante["mat_grado"]>=1 and $estudiante["mat_grado"]<=5) {$educacion = "PREESCOLAR"; $horasT = 25;}	
	elseif($estudiante["mat_grado"]>=6 and $estudiante["mat_grado"]<=10) {$educacion = "BÁSICA PRIMARIA"; $horasT = 30;}	
	elseif($estudiante["mat_grado"]>=11 and $estudiante["mat_grado"]<=14) {$educacion = "BÁSICA SECUNDARIA"; $horasT = 35;}
	elseif($estudiante["mat_grado"]>=15 and $estudiante["mat_grado"]<=16) {$educacion = "MEDIA"; $horasT = 35;}											

	if($i<$restaAgnos)

		$grados .= $estudiante["gra_nombre"].", "; 

	else

		$grados .= $estudiante["gra_nombre"];		

	$inicio++;

	$i++;										

}

?>

	


    <p>Que, <b><?=$nombre?></b> cursó en esta Institución <b><?=strtoupper($grados);?> GRADO DE EDUCACIÓN <?=$educacion;?></b>  y obtuvo las siguientes calificaciones:</p>

    

<?php												

$restaAgnos = ($_POST["hasta"]-$_POST["desde"])+1;

$i=1;

$inicio = $_POST["desde"];

while($i<=$restaAgnos){

	mysqli_select_db($conexion, $config['conf_base_datos']."_".$inicio);

	//SELECCIONO EL ESTUDIANTE, EL GRADO Y EL GRUPO
	$matricula = Estudiantes::obtenerDatosEstudiante($_POST["id"]);

?>

    <p align="center" style="font-weight:bold;">

    	<?=strtoupper($matricula["gra_nombre"]);?> GRADO DE EDUCACIÓN <?=$educacion." ".$inicio?><br>  

		MATRÍCULA <?=strtoupper($matricula["mat_matricula"]);?> FOLIO <?=strtoupper($matricula["mat_folio"]);?>

    </p>

	

	<?php 
	$consultaConfig=mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".configuracion WHERE conf_base_datos='".$_SESSION["inst"]."' AND conf_agno='".$_SESSION["bd"]."'");
	$configAA=mysqli_fetch_array($consultaConfig, MYSQLI_BOTH);
	if($inicio<$config[1] and $configAA[2]<5){?>

        <table width="100%" cellspacing="0" cellpadding="0" rules="all" border="1" align="left">

            <tr style="font-weight:bold; font-size:11px;">

                <td>ÁREAS/ASIGNATURAS</td>

                <td>CALIFICACIONES</td>

                <td>HORAS</td>

            </tr>

            <?php

            //SELECCION LAS CARGAS DEL ESTUDIANTE, MATERIAS, AREAS

            $cargasAcademicas = mysqli_query($conexion, "SELECT car_id, car_materia, car_ih, mat_id, mat_nombre, mat_area, ar_nombre, ar_id FROM academico_cargas 

                                            INNER JOIN ".BD_ACADEMICA.".academico_materias am ON am.mat_id=car_materia AND am.institucion={$config['conf_id_institucion']} AND am.year={$inicio}

                                            INNER JOIN ".BD_ACADEMICA.".academico_areas a ON a.ar_id=am.mat_area AND a.institucion={$config['conf_id_institucion']} AND a.year={$inicio}

                                            WHERE car_curso='".$matricula["mat_grado"]."' AND car_grupo='".$matricula["mat_grupo"]."' GROUP BY am.mat_area");

            $materiasPerdidas = 0;

            while($cargas=mysqli_fetch_array($cargasAcademicas, MYSQLI_BOTH)){	

                //CONSULTAMOS LAS MATERIAS DEL AREA

				$materias = mysqli_query($conexion, "SELECT car_id FROM ".BD_ACADEMICA.".academico_materias am, academico_cargas WHERE am.mat_area='".$cargas["ar_id"]."' AND am.mat_id=car_materia AND car_curso='".$matricula["gra_id"]."' AND car_grupo='".$matricula["gru_id"]."' AND am.institucion={$config['conf_id_institucion']} AND am.year={$inicio}");

				$numMat = mysqli_num_rows($materias);

				//REPETIMOS LAS CARGAS DONDE HAYA MATERIAS DE LA MISMA AREA Y LAS METEMOS EN UNA SOLA VARIABLE

				$mate="";

				$j=1;

				while($mat=mysqli_fetch_array($materias, MYSQLI_BOTH)){if($j<$numMat)$mate .=$mat[0].","; else $mate .=$mat[0]; $j++;}

				//OBTENEMOS EL PROMEDIO DE LAS CALIFICACIONES DE TODAS LAS MATERIAS DE UNA MISMA AREA

				$consultaBoletin=mysqli_query($conexion, "SELECT avg(bol_nota) FROM ".BD_ACADEMICA.".academico_boletin WHERE bol_estudiante='".$_POST["id"]."' AND bol_carga IN(".$mate.") AND institucion={$config['conf_id_institucion']} AND year={$inicio}");
                $boletin = mysqli_fetch_array($consultaBoletin, MYSQLI_BOTH);

                $nota = round($boletin[0],1);
				for($n=0; $n<=5; $n++){
					if($nota==$n) $nota=$nota.".0";
				}
				$consultaDesempeno=mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_notas_tipos WHERE notip_categoria='".$config[22]."' AND notip_desde<='".$nota."' AND notip_hasta>='".$nota."' AND institucion={$config['conf_id_institucion']} AND year={$inicio}");
				$desempenoA = mysqli_fetch_array($consultaDesempeno, MYSQLI_BOTH);				   

            ?>

            <tr style="font-size:11px; font-weight:bold;"> 

                <td><?=strtoupper($cargas["ar_nombre"]);?></td>

                <td><?=$nota;?> (<?=strtoupper($desempenoA['notip_nombre']);?>)</td>

                <td><?=$cargas["car_ih"]." (".$horas[$cargas["car_ih"]].")";?></td>

            </tr>
            
            <?php
			//INCLUIR LA MATERIA, LA DEFINITIVA Y LA I.H POR CADA ÁREA
			$materiasDA = mysqli_query($conexion, "SELECT car_id, am.mat_nombre, ipc.ipc_intensidad FROM ".BD_ACADEMICA.".academico_materias am, academico_cargas, ".BD_ACADEMICA.".academico_intensidad_curso ipc WHERE am.mat_area='".$cargas["ar_id"]."' AND am.mat_id=car_materia AND car_curso='".$matricula["gra_id"]."' AND car_grupo='".$matricula["gru_id"]."' AND ipc.ipc_curso='".$matricula["mat_grado"]."' AND ipc.ipc_materia=am.mat_id AND ipc.institucion={$config['conf_id_institucion']} AND ipc.year={$inicio} AND am.institucion={$config['conf_id_institucion']} AND am.year={$inicio}");
			
			while($mda = mysqli_fetch_array($materiasDA, MYSQLI_BOTH)){
				$consultaNotaDefMateria=mysqli_query($conexion, "SELECT avg(bol_nota) FROM ".BD_ACADEMICA.".academico_boletin WHERE bol_estudiante='".$_POST["id"]."' AND bol_carga='".$mda["car_id"]."' AND institucion={$config['conf_id_institucion']} AND year={$inicio}");
				$notaDefMateria = mysqli_fetch_array($consultaNotaDefMateria, MYSQLI_BOTH);
				$notaDefMateria = round($notaDefMateria[0],1);
				for($n=0; $n<=5; $n++){
					if($notaDefMateria==$n) $notaDefMateria=$notaDefMateria.".0";
				}
				if($notaDefMateria<$config[5]){
                    $materiasPerdidas++;
                }
				$consultaDesempeno=mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_notas_tipos WHERE notip_categoria='".$config[22]."' AND notip_desde<='".$notaDefMateria."' AND notip_hasta>='".$notaDefMateria."' AND institucion={$config['conf_id_institucion']} AND year={$inicio}");
				$desempeno = mysqli_fetch_array($consultaDesempeno, MYSQLI_BOTH);
				//PARA PREESCOLARES
				if($matricula["gra_id"]>=12 and $matricula["gra_id"]<=15){
					$nota = ceil($nota);
					if($notaDefMateria==1) $notaDefMateria = 'DEFICIENTE';
					if($notaDefMateria==2) $notaDefMateria = 'INSUFICIENTE';
					if($notaDefMateria==3) $notaDefMateria = 'ACEPTABLE';
					if($notaDefMateria==4) $notaDefMateria = 'SOBRESALIENTE';
					if($notaDefMateria==5) $notaDefMateria = 'EXCELENTE';
				}
			?>
                <tr style="font-size:11px;"> 
                    <td><?=$mda["mat_nombre"];?></td>
                    <td><?=$notaDefMateria;?> <?php if($matricula["gra_id"]<12){?> (<?=strtoupper($desempeno['notip_nombre']);?>) <?php }?></td>
                    <td><?=$mda["ipc_intensidad"]." (".$horas[$mda["ipc_intensidad"]].")";?></td>
                </tr>
            <?php }?>

            <?php

            }

			//MEDIA TECNICA
			if (array_key_exists(10, $_SESSION["modulos"])){
				$consultaEstudianteActualMT = MediaTecnicaServicios::existeEstudianteMT($config,$inicio,$_POST["id"]);
				while($datosEstudianteActualMT = mysqli_fetch_array($consultaEstudianteActualMT, MYSQLI_BOTH)){
					if(!empty($datosEstudianteActualMT)){

			//SELECCION LAS CARGAS DEL ESTUDIANTE, MATERIAS, AREAS
			$cargasAcademicas = mysqli_query($conexion, "SELECT car_id, car_materia, car_ih, mat_id, mat_nombre, mat_area, ar_nombre, ar_id FROM academico_cargas 

										INNER JOIN ".BD_ACADEMICA.".academico_materias am ON am.mat_id=car_materia AND am.institucion={$config['conf_id_institucion']} AND am.year={$inicio}

										INNER JOIN ".BD_ACADEMICA.".academico_areas a ON a.ar_id=am.mat_area AND a.institucion={$config['conf_id_institucion']} AND a.year={$inicio}

										WHERE car_curso='" . $datosEstudianteActualMT["matcur_id_curso"] . "' AND car_grupo='" . $datosEstudianteActualMT["matcur_id_grupo"] . "' GROUP BY am.mat_area");


			$materiasPerdidas = 0;

			while ($cargas = mysqli_fetch_array($cargasAcademicas, MYSQLI_BOTH)) {

				//CONSULTAMOS LAS MATERIAS DEL AREA

				$materias = mysqli_query($conexion, "SELECT car_id FROM ".BD_ACADEMICA.".academico_materias am, academico_cargas WHERE am.mat_area='" . $cargas["ar_id"] . "' AND am.mat_id=car_materia AND car_curso='" . $matricula["gra_id"] . "' AND car_grupo='" . $matricula["gru_id"] . "' AND am.institucion={$config['conf_id_institucion']} AND am.year={$inicio}");

				$numMat = mysqli_num_rows($materias);

				//REPETIMOS LAS CARGAS DONDE HAYA MATERIAS DE LA MISMA AREA Y LAS METEMOS EN UNA SOLA VARIABLE

				$mate = "";

				$j = 1;

				while ($mat = mysqli_fetch_array($materias, MYSQLI_BOTH)) {
					if ($j < $numMat) $mate .= $mat[0] . ",";
					else $mate .= $mat[0];
					$j++;
				}

				//OBTENEMOS EL PROMEDIO DE LAS CALIFICACIONES DE TODAS LAS MATERIAS DE UNA MISMA AREA

				$consultaBoletin = mysqli_query($conexion, "SELECT avg(bol_nota) FROM ".BD_ACADEMICA.".academico_boletin WHERE bol_estudiante='" . $_POST["id"] . "' AND bol_carga IN(" . $mate . ") AND institucion={$config['conf_id_institucion']} AND year={$inicio}");
				$boletin = mysqli_fetch_array($consultaBoletin, MYSQLI_BOTH);

				$nota = round($boletin[0], 1);
				for ($n = 0; $n <= 5; $n++) {
					if ($nota == $n) $nota = $nota . ".0";
				}
				$consultaDesempeno = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_notas_tipos WHERE notip_categoria='" . $config[22] . "' AND notip_desde<='" . $nota . "' AND notip_hasta>='" . $nota . "' AND institucion={$config['conf_id_institucion']} AND year={$inicio}");
				$desempenoA = mysqli_fetch_array($consultaDesempeno, MYSQLI_BOTH);

			?>

				<tr style="font-size:11px; font-weight:bold;">

					<td><?= strtoupper($cargas["ar_nombre"]); ?></td>

					<td><?= $nota; ?> (<?= strtoupper($desempenoA['notip_nombre']); ?>)</td>

					<td><?= $cargas["car_ih"] . " (" . $horas[$cargas["car_ih"]] . ")"; ?></td>

				</tr>

				<?php
				//INCLUIR LA MATERIA, LA DEFINITIVA Y LA I.H POR CADA ÁREA

				$materiasDA = mysqli_query($conexion, "SELECT car_id, mat_nombre, ipc_intensidad FROM ".BD_ACADEMICA.".academico_materias am, academico_cargas, ".BD_ACADEMICA.".academico_intensidad_curso ipc WHERE am.mat_area='" . $cargas["ar_id"] . "' AND am.mat_id=car_materia AND car_curso='" . $matricula["gra_id"] . "' AND car_grupo='" . $matricula["gru_id"] . "' AND ipc.ipc_curso='" . Utilidades::getToString($matricula["mat_grado"]) . "' AND ipc.ipc_materia=am.mat_id AND ipc.institucion={$config['conf_id_institucion']} AND ipc.year={$inicio} AND am.institucion={$config['conf_id_institucion']} AND am.year={$inicio}");

				while ($mda = mysqli_fetch_array($materiasDA, MYSQLI_BOTH)) {
					$consultaNotaDefMateria = mysqli_query($conexion, "SELECT avg(bol_nota) FROM ".BD_ACADEMICA.".academico_boletin WHERE bol_estudiante='" . $_POST["id"] . "' AND bol_carga='" . $mda["car_id"] . "' AND institucion={$config['conf_id_institucion']} AND year={$inicio}");
					$notaDefMateria = mysqli_fetch_array($consultaNotaDefMateria, MYSQLI_BOTH);
					$notaDefMateria = round($notaDefMateria[0], 1);
					for ($n = 0; $n <= 5; $n++) {
						if ($notaDefMateria == $n) $notaDefMateria = $notaDefMateria . ".0";
					}
					if ($notaDefMateria < $config[5]) {
						$materiasPerdidas++;
					}
					$consultaDesempeno = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_notas_tipos WHERE notip_categoria='" . $config[22] . "' AND notip_desde<='" . $notaDefMateria . "' AND notip_hasta>='" . $notaDefMateria . "' AND institucion={$config['conf_id_institucion']} AND year={$inicio}");
					$desempeno = mysqli_fetch_array($consultaDesempeno, MYSQLI_BOTH);
					//PARA PREESCOLARES
					if ($matricula["gra_id"] >= 12 and $matricula["gra_id"] <= 15) {
						$nota = ceil($nota);
						if ($notaDefMateria == 1) $notaDefMateria = 'DEFICIENTE';
						if ($notaDefMateria == 2) $notaDefMateria = 'INSUFICIENTE';
						if ($notaDefMateria == 3) $notaDefMateria = 'ACEPTABLE';
						if ($notaDefMateria == 4) $notaDefMateria = 'SOBRESALIENTE';
						if ($notaDefMateria == 5) $notaDefMateria = 'EXCELENTE';
					}
				?>
					<tr style="font-size:11px;">
						<td><?= $mda["mat_nombre"]; ?></td>
						<td><?= $notaDefMateria; ?> <?php if ($matricula["gra_id"] < 12) { ?> (<?= strtoupper($desempeno['notip_nombre']); ?>) <?php } ?></td>
						<td><?= $mda["ipc_intensidad"] . " (" . $horas[$mda["ipc_intensidad"]] . ")"; ?></td>
					</tr>
				<?php } ?>

			<?php

			}}}}

            ?>

            

        </table>

        

        <p>&nbsp;</p>

    	<?php

		$nivelaciones = mysqli_query($conexion, "SELECT niv_definitiva, niv_acta, niv_fecha_nivelacion, mat_nombre FROM ".BD_ACADEMICA.".academico_nivelaciones niv 

									INNER JOIN academico_cargas ON car_id=niv.niv_id_asg

									INNER JOIN ".BD_ACADEMICA.".academico_materias ON mat_id=car_materia

									WHERE niv.niv_cod_estudiante='".$_POST["id"]."' AND niv.institucion={$config['conf_id_institucion']} AND niv.year={$inicio}");

									

		$numNiv = mysqli_num_rows($nivelaciones);

		if($numNiv>0){	

			echo "El(la) Estudiante niveló las siguientes materias:<br>";						

			while($niv=mysqli_fetch_array($nivelaciones, MYSQLI_BOTH)){

				echo "<b>".strtoupper($niv["mat_nombre"])." (".$niv["niv_definitiva"].")</b> Segun acta ".$niv["niv_acta"]." en la fecha de ".$niv["niv_fecha_nivelacion"]."<br>";

			}

		}

		?>

		<?php 
		// SABER QUE MATERIAS TIENE PERDIDAS
				$cargasAcademicasC = mysqli_query($conexion, "SELECT car_id FROM academico_cargas WHERE car_curso='".$matricula["mat_grado"]."' AND car_grupo='".$matricula["mat_grupo"]."'");
				$materiasPerdidas = 0;
				$vectorMP = array();
				while($cargasC=mysqli_fetch_array($cargasAcademicasC, MYSQLI_BOTH)){	
					//OBTENEMOS EL PROMEDIO DE LAS CALIFICACIONES
					$consultaBoletinC=mysqli_query($conexion, "SELECT avg(bol_nota) FROM ".BD_ACADEMICA.".academico_boletin WHERE bol_estudiante='".$_POST["id"]."' AND bol_carga='".$cargasC["car_id"]."' AND institucion={$config['conf_id_institucion']} AND year={$inicio}");
					$boletinC = mysqli_fetch_array($consultaBoletinC, MYSQLI_BOTH);
					$notaC = round($boletinC[0],1);
					if($notaC<$config[5]){
						$vectorMP[$materiasPerdidas] = $cargasC["car_id"];
						$materiasPerdidas++;
					}
				}
		//FIN DE LAS MATERIAS QUE
		if($materiasPerdidas>0){
			$m=0;
			$niveladas=0;
			while($m<$materiasPerdidas){
				$nMP = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_nivelaciones WHERE niv_cod_estudiante='".$_POST["id"]."' AND niv_id_asg='".$vectorMP[$m]."' AND niv_definitiva>='".$config[5]."' AND institucion={$config['conf_id_institucion']} AND year={$inicio}");
				$numNivMP = mysqli_num_rows($nMP);
				if($numNivMP>0){
					$niveladas++;
				}
				$m++;						
			}
		}
		   if($materiasPerdidas==0 or $niveladas>=$materiasPerdidas)
                $msj = "<center>EL (LA) ESTUDIANTE ".$nombre." FUE PROMOVIDO(A) AL GRADO SIGUIENTE</center>"; 
            else
                $msj = "<center>EL (LA) ESTUDIANTE ".$nombre." NO FUE PROMOVIDO(A) AL GRADO SIGUIENTE</center>";	
        ?>
    
        <br><div align="left" style="font-weight:bold; font-style:italic; font-size:12px; margin-bottom:10px;"><?=$msj;?></div>

    

    <!-- SI ESTÁ EN EL AÑO ACTUAL Y ESTE NO HA TERMINADO -->

	<?php }else{?>

    	<table width="100%" cellspacing="0" cellpadding="0" rules="all" border="1" align="left">

            <tr style="font-weight:bold; text-align:center;">

                <td>ÁREAS/ASIGNATURAS</td>

                <td>HS</td>

                <?php

                $p = 1;

                //PERIODOS

				while($p<=$config[19]){

                	echo '<td>'.$p.'P</td>';

                	$p++;

                }

				?>

                <td>DEF</td>

                <td>DESEMPEÑO</td>  

            </tr>

            <?php

            //SELECCION LAS CARGAS DEL ESTUDIANTE, MATERIAS, AREAS

            $cargasAcademicas = mysqli_query($conexion, "SELECT car_id, car_materia, car_ih, mat_id, mat_nombre, mat_area FROM academico_cargas 

                                            INNER JOIN ".BD_ACADEMICA.".academico_materias am ON am.mat_id=car_materia AND am.institucion={$config['conf_id_institucion']} AND am.year={$inicio}

                                            INNER JOIN ".BD_ACADEMICA.".academico_areas a ON a.ar_id=am.mat_area AND a.institucion={$config['conf_id_institucion']} AND a.year={$inicio}

                                            WHERE car_curso='".$matricula["mat_grado"]."' AND car_grupo='".$matricula["mat_grupo"]."'");

			while($cargas=mysqli_fetch_array($cargasAcademicas, MYSQLI_BOTH)){	

                //OBTENEMOS EL PROMEDIO DE LAS CALIFICACIONES

				$consunltaBoletin=mysqli_query($conexion, "SELECT avg(bol_nota) FROM ".BD_ACADEMICA.".academico_boletin WHERE bol_estudiante='".$_POST["id"]."' AND bol_carga='".$cargas["car_id"]."' AND institucion={$config['conf_id_institucion']} AND year={$inicio}");
                $boletin = mysqli_fetch_array($consunltaBoletin, MYSQLI_BOTH);

                $nota = round($boletin[0],1);

				$consultaDesempeno=mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_notas_tipos WHERE notip_categoria='".$config[22]."' AND ".$nota.">=notip_desde AND ".$nota."<=notip_hasta AND institucion={$config['conf_id_institucion']} AND year={$inicio}");
				$desempeno = mysqli_fetch_array($consultaDesempeno, MYSQLI_BOTH);					   

            ?>

            <tr style="text-align:center;">

                <td style="text-align:left;"><?=strtoupper($cargas["mat_nombre"]);?></td>

                <td><?=$cargas["car_ih"];?></td>

				<?php

                    $p = 1;

                    //PERIODOS

                    while($p<=$config[19]){

						$consultaNotasPeriodo=mysqli_query($conexion, "SELECT bol_nota FROM ".BD_ACADEMICA.".academico_boletin WHERE bol_estudiante='".$_POST["id"]."' AND bol_carga='".$cargas["car_id"]."' AND bol_periodo='".$p."' AND institucion={$config['conf_id_institucion']} AND year={$inicio}");
                        $notasPeriodo = mysqli_fetch_array($consultaNotasPeriodo, MYSQLI_BOTH);

						$notasPeriodoFinal='';
						if(!empty($notasPeriodo[0])){
							$notasPeriodoFinal=$notasPeriodo[0];
							if($config['conf_forma_mostrar_notas'] == CUALITATIVA){
								$estiloNota = Boletin::obtenerDatosTipoDeNotas($config['conf_notas_categoria'], $notasPeriodo[0]);
								$notasPeriodoFinal= !empty($estiloNota['notip_nombre']) ? $estiloNota['notip_nombre'] : "";
							}
						}

						echo '<td>' . $notasPeriodoFinal . '</td>';

                        $p++;

                    }

                ?>    

                <td><?=$nota;?></td>

                <td><?=$desempeno['notip_nombre'];?></td>  

            </tr>

            <?php

            }

			//MEDIA TECNICA
			if (array_key_exists(10, $_SESSION["modulos"])){
				$consultaEstudianteActualMT = MediaTecnicaServicios::existeEstudianteMT($config,$inicio,$_POST["id"]);
				while($datosEstudianteActualMT = mysqli_fetch_array($consultaEstudianteActualMT, MYSQLI_BOTH)){
					if(!empty($datosEstudianteActualMT)){

			//SELECCION LAS CARGAS DEL ESTUDIANTE, MATERIAS, AREAS
			$cargasAcademicas = mysqli_query($conexion, "SELECT car_id, car_materia, car_ih, mat_id, mat_nombre, mat_area FROM academico_cargas 

										INNER JOIN ".BD_ACADEMICA.".academico_materias am ON am.mat_id=car_materia AND am.institucion={$config['conf_id_institucion']} AND am.year={$inicio}

										INNER JOIN ".BD_ACADEMICA.".academico_areas a ON a.ar_id=am.mat_area AND a.institucion={$config['conf_id_institucion']} AND a.year={$inicio}


										WHERE car_curso='" . $datosEstudianteActualMT["matcur_id_curso"] . "' AND car_grupo='" . $datosEstudianteActualMT["matcur_id_grupo"] . "'");


			while ($cargas = mysqli_fetch_array($cargasAcademicas, MYSQLI_BOTH)) {

				//OBTENEMOS EL PROMEDIO DE LAS CALIFICACIONES

				$consultaBoletin = mysqli_query($conexion, "SELECT avg(bol_nota) FROM ".BD_ACADEMICA.".academico_boletin WHERE bol_estudiante='" . $_POST["id"] . "' AND bol_carga='" . $cargas["car_id"] . "' AND institucion={$config['conf_id_institucion']} AND year={$inicio}");
				$boletin = mysqli_fetch_array($consultaBoletin, MYSQLI_BOTH);

				$nota = round($boletin[0], 1);

				$consultaDesempeno = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_notas_tipos WHERE notip_categoria='" . $config[22] . "' AND " . $nota . ">=notip_desde AND " . $nota . "<=notip_hasta AND institucion={$config['conf_id_institucion']} AND year={$inicio}");
				$desempeno = mysqli_fetch_array($consultaDesempeno, MYSQLI_BOTH);

			?>

				<tr style="text-align:center;">

					<td style="text-align:left;"><?= strtoupper($cargas["mat_nombre"]); ?></td>

					<td><?= $cargas["car_ih"]; ?></td>

					<?php

					$p = 1;

					//PERIODOS

					while ($p <= $config[19]) {

						$consultaNotasPeriodo = mysqli_query($conexion, "SELECT bol_nota FROM ".BD_ACADEMICA.".academico_boletin WHERE bol_estudiante='" . $_POST["id"] . "' AND bol_carga='" . $cargas["car_id"] . "' AND bol_periodo='" . $p . "' AND institucion={$config['conf_id_institucion']} AND year={$inicio}");
						$notasPeriodo = mysqli_fetch_array($consultaNotasPeriodo, MYSQLI_BOTH);

						$notasPeriodoFinal='';
						if(!empty($notasPeriodo[0])){
							$notasPeriodoFinal=$notasPeriodo[0];
							if($config['conf_forma_mostrar_notas'] == CUALITATIVA){
								$estiloNota = Boletin::obtenerDatosTipoDeNotas($config['conf_notas_categoria'], $notasPeriodo[0]);
								$notasPeriodoFinal= !empty($estiloNota['notip_nombre']) ? $estiloNota['notip_nombre'] : "";
							}
						}

						echo '<td>' . $notasPeriodoFinal . '</td>';

						$p++;
					}

					?>

					<td><?= $nota; ?></td>

					<td><?= $desempeno['notip_nombre']; ?></td>

				</tr>

			<?php

			}}}}

            ?>

            

        </table>

        

    <?php }?>

    

    



<?php	

	$inicio++;

	$i++;

}

?>





<p>&nbsp;</p>
<?php if(date('m')<10){$mes = substr(date('m'),1);}else{$mes = date('m');}?>	
<span style="font-size:16px; text-align:justify;">
PLAN DE ESTUDIOS: Ley 115 de Educación, artículo 23, Decreto 1860 de 1994. Decreto 1290 de 2009 y Decreto 3055 del 12 de diciembre de 2002. Intensidad horaria <?=$horasT;?> horas semanales de 55 minutos.<br><br>
Se expide el presente certificado en el Carmen de Chucurí  el <?=date("d");?> de <?php echo $meses[$mes];?> de <?=date("Y");?>. 
</span>





<p>&nbsp;</p>

<table width="100%" cellspacing="0" cellpadding="0" rules="none" border="0" style="text-align:center; font-size:10px;">

	<tr>

		<td align="left">_________________________________<br><!--<?=strtoupper("");?><br>-->Director(a)</td>

    </tr>

</table>  



			                   

 

<div align="center" style="font-size:10px; margin-top:10px;">

    <img src="<?=$Plataforma->logo?>" height="100"><br>

    SINTIA -  SISTEMA INTEGRAL DE GESTI&Oacute;N INSTITUCIONAL - <?=date("l, d-M-Y");?>

</div>


<script type="application/javascript">
	print();
</script>

</body>

</html>