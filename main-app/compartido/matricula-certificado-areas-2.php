<?php include("../directivo/session.php");?>
<?php include("../../config-general/config.php");?>

<?php

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

</head>



<body style="font-family:Arial;">





<div align="center" style="margin-bottom:20px; margin-top:20px;">

<img src="https://plataformasintia.com/innovadores/files/images/logo/WhatsApp%20Image%202022-01-19%20at%2011.21.24%20AM.jpeg" width="100"><br><br>
    
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

	mysql_select_db($config['conf_base_datos']."_".$inicio, $conexion);

	$estudianteC = mysql_query("SELECT mat_id, mat_primer_apellido, mat_segundo_apellido, mat_nombres, mat_grado, mat_grupo, gra_nombre, gru_nombre FROM academico_matriculas
	INNER JOIN academico_grados ON gra_id=mat_grado
	INNER JOIN academico_grupos ON gru_id=mat_grupo
	WHERE mat_id='".$_POST["id"]."' AND mat_eliminado=0",$conexion);
	if(mysql_errno()!=0){echo mysql_error(); exit();}
	
	$estudiante = mysql_fetch_array($estudianteC);
	
	if($estudiante["mat_grado"]>=1 and $estudiante["mat_grado"]<=5) {$educacion = "BÁSICA PRIMARIA"; $horasT = 30;}	
	elseif($estudiante["mat_grado"]>=6 and $estudiante["mat_grado"]<=9) {$educacion = "BÁSICA SECUNDARIA"; $horasT = 35;}
	elseif($estudiante["mat_grado"]>=10 and $estudiante["mat_grado"]<=11) {$educacion = "MEDIA"; $horasT = 35;}	
	elseif($estudiante["mat_grado"]>=12 and $estudiante["mat_grado"]<=15) {$educacion = "PREESCOLAR"; $horasT = 25;}											

	if($i<$restaAgnos)

		$grados .= $estudiante["gra_nombre"].", "; 

	else

		$grados .= $estudiante["gra_nombre"];		

	$inicio++;

	$i++;										

}

?>

	

    <p>Que, <b><?=strtoupper($estudiante["mat_primer_apellido"]." ".$estudiante["mat_segundo_apellido"]." ".$estudiante["mat_nombres"]);?></b> cursó en esta Institución <b><?=strtoupper($grados);?> GRADO DE EDUCACIÓN <?=$educacion;?></b>  y obtuvo las siguientes calificaciones:</p>

    

<?php												

$restaAgnos = ($_POST["hasta"]-$_POST["desde"])+1;

$i=1;

$inicio = $_POST["desde"];

while($i<=$restaAgnos){

	mysql_select_db($config['conf_base_datos']."_".$inicio, $conexion);

	//SELECCIONO EL ESTUDIANTE, EL GRADO Y EL GRUPO

	$matricula = mysql_fetch_array(mysql_query("SELECT mat_id, mat_matricula, mat_folio, mat_primer_apellido, mat_segundo_apellido, mat_nombres, mat_grado, mat_grupo, gra_nombre, gru_nombre, gra_id, gru_id FROM academico_matriculas
	INNER JOIN academico_grados ON gra_id=mat_grado
	INNER JOIN academico_grupos ON gru_id=mat_grupo
	WHERE mat_id='".$_POST["id"]."' AND mat_eliminado=0",$conexion));

?>

    <p align="center" style="font-weight:bold;">

    	<?=strtoupper($matricula["gra_nombre"]);?> GRADO DE EDUCACIÓN BÁSICA SECUNDARIA <?=$inicio;?><br>  

		MATRÍCULA <?=strtoupper($matricula["mat_matricula"]);?> FOLIO <?=strtoupper($matricula["mat_folio"]);?>

    </p>

	

	<?php 
	$configAA=mysql_fetch_array(mysql_query("SELECT * FROM configuracion WHERE conf_id=1",$conexion));
	if($inicio<=$config[1] and $configAA[2]==5){?>

        <table width="100%" cellspacing="0" cellpadding="0" rules="all" border="1" align="left">

            <tr style="font-weight:bold; font-size:11px;">

                <td>ÁREAS/ASIGNATURAS</td>

                <td>CALIFICACIONES</td>

                <td>HORAS</td>

            </tr>

            <?php

            //SELECCION LAS CARGAS DEL ESTUDIANTE, MATERIAS, AREAS

            $cargasAcademicas = mysql_query("SELECT car_id, car_materia, car_ih, mat_id, mat_nombre, mat_area, ar_nombre, ar_id FROM academico_cargas 

                                            INNER JOIN academico_materias ON mat_id=car_materia

                                            INNER JOIN academico_areas ON ar_id=mat_area

                                            WHERE car_curso='".$matricula["mat_grado"]."' AND car_grupo='".$matricula["mat_grupo"]."' GROUP BY mat_area",$conexion);

            $materiasPerdidas = 0;

            while($cargas=mysql_fetch_array($cargasAcademicas)){	

                //CONSULTAMOS LAS MATERIAS DEL AREA

				$materias = mysql_query("SELECT car_id FROM academico_materias, academico_cargas WHERE mat_area='".$cargas["ar_id"]."' AND mat_id=car_materia AND car_curso='".$matricula["gra_id"]."' AND car_grupo='".$matricula["gru_id"]."'",$conexion);

				$numMat = mysql_num_rows($materias);

				//REPETIMOS LAS CARGAS DONDE HAYA MATERIAS DE LA MISMA AREA Y LAS METEMOS EN UNA SOLA VARIABLE

				$mate="";

				$j=1;

				while($mat=mysql_fetch_array($materias)){if($j<$numMat)$mate .=$mat[0].","; else $mate .=$mat[0]; $j++;}

				//OBTENEMOS EL PROMEDIO DE LAS CALIFICACIONES DE TODAS LAS MATERIAS DE UNA MISMA AREA

                $boletin = mysql_fetch_array(mysql_query("SELECT avg(bol_nota) FROM academico_boletin WHERE bol_estudiante='".$_POST["id"]."' AND bol_carga IN(".$mate.")",$conexion));

                $nota = round($boletin[0],1);
				for($n=0; $n<=5; $n++){
					if($nota==$n) $nota=$nota.".0";
				}
				$desempenoA = mysql_fetch_array(mysql_query("SELECT * FROM academico_notas_tipos WHERE notip_categoria='".$config[22]."' AND notip_desde<='".$nota."' AND notip_hasta>='".$nota."'",$conexion));				   

            ?>

            <tr style="font-size:11px; font-weight:bold;"> 

                <td><?=strtoupper($cargas["ar_nombre"]);?></td>

                <td><?=$nota;?> (<?=strtoupper($desempenoA[1]);?>)</td>

                <td><?=$cargas["car_ih"]." (".$horas[$cargas["car_ih"]].")";?></td>

            </tr>
            
            <?php
			//INCLUIR LA MATERIA, LA DEFINITIVA Y LA I.H POR CADA ÁREA
			$materiasDA = mysql_query("SELECT car_id, mat_nombre, ipc_intensidad FROM academico_materias, academico_cargas, academico_intensidad_curso WHERE mat_area='".$cargas["ar_id"]."' AND mat_id=car_materia AND car_curso='".$matricula["gra_id"]."' AND car_grupo='".$matricula["gru_id"]."' AND ipc_curso='".$matricula["mat_grado"]."' AND ipc_materia=mat_id",$conexion);
			if(mysql_errno()!=0){echo mysql_error(); exit();}
			while($mda = mysql_fetch_array($materiasDA)){
				$notaDefMateria = mysql_fetch_array(mysql_query("SELECT avg(bol_nota) FROM academico_boletin WHERE bol_estudiante='".$_POST["id"]."' AND bol_carga='".$mda["car_id"]."'",$conexion));
				$notaDefMateria = round($notaDefMateria[0],1);
				for($n=0; $n<=5; $n++){
					if($notaDefMateria==$n) $notaDefMateria=$notaDefMateria.".0";
				}
				if($notaDefMateria<$config[5]){
                    $materiasPerdidas++;
                }
				$desempeno = mysql_fetch_array(mysql_query("SELECT * FROM academico_notas_tipos WHERE notip_categoria='".$config[22]."' AND notip_desde<='".$notaDefMateria."' AND notip_hasta>='".$notaDefMateria."'",$conexion));
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
                    <td><?=$notaDefMateria;?> <?php if($matricula["gra_id"]<12){?> (<?=strtoupper($desempeno[1]);?>) <?php }?></td>
                    <td><?=$mda["ipc_intensidad"]." (".$horas[$mda["ipc_intensidad"]].")";?></td>
                </tr>
            <?php }?>

            <?php

            }

            ?>

            

        </table>

        

        <p>&nbsp;</p>

    	<?php

		$nivelaciones = mysql_query("SELECT niv_definitiva, niv_acta, niv_fecha_nivelacion, mat_nombre FROM academico_nivelaciones 

									INNER JOIN academico_cargas ON car_id=niv_id_asg

									INNER JOIN academico_materias ON mat_id=car_materia

									WHERE niv_cod_estudiante='".$_POST["id"]."'",$conexion);

		if(mysql_errno()!=0){echo mysql_error(); exit();}							

		$numNiv = mysql_num_rows($nivelaciones);

		if($numNiv>0){	

			echo "El(la) Estudiante niveló las siguientes materias:<br>";						

			while($niv=mysql_fetch_array($nivelaciones)){

				echo "<b>".strtoupper($niv["mat_nombre"])." (".$niv["niv_definitiva"].")</b> Segun acta ".$niv["niv_acta"]." en la fecha de ".$niv["niv_fecha_nivelacion"]."<br>";

			}

		}

		?>

		<?php 
		// SABER QUE MATERIAS TIENE PERDIDAS
				$cargasAcademicasC = mysql_query("SELECT car_id FROM academico_cargas WHERE car_curso='".$matricula["mat_grado"]."' AND car_grupo='".$matricula["mat_grupo"]."'",$conexion);
				$materiasPerdidas = 0;
				$vectorMP = array();
				while($cargasC=mysql_fetch_array($cargasAcademicasC)){	
					//OBTENEMOS EL PROMEDIO DE LAS CALIFICACIONES
					$boletinC = mysql_fetch_array(mysql_query("SELECT avg(bol_nota) FROM academico_boletin WHERE bol_estudiante='".$_POST["id"]."' AND bol_carga='".$cargasC["car_id"]."'",$conexion));
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
				$nMP = mysql_query("SELECT * FROM academico_nivelaciones WHERE niv_cod_estudiante='".$_POST["id"]."' AND niv_id_asg='".$vectorMP[$m]."' AND niv_definitiva>='".$config[5]."'",$conexion);
				$numNivMP = mysql_num_rows($nMP);
				if($numNivMP>0){
					$niveladas++;
				}
				$m++;						
			}
		}
		   if($materiasPerdidas==0 or $niveladas>=$materiasPerdidas)
                $msj = "<center>EL (LA) ESTUDIANTE ".strtoupper($datos_usr[3]." ".$datos_usr[4]." ".$datos_usr["matri_solo_nombre"])." FUE PROMOVIDO(A) AL GRADO SIGUIENTE</center>"; 
            /*elseif($materiasPerdidas<$config["conf_num_materias_perder_agno"] and $materiasPerdidas>0)
                $msj = "<center>EL (LA) ESTUDIANTE ".strtoupper($datos_usr[3]." ".$datos_usr[4]." ".$datos_usr["mat_nombres"])." DEBE NIVELAR LAS MATERIAS PERDIDAS</center>";*/
            else
                $msj = "<center>EL (LA) ESTUDIANTE ".strtoupper($datos_usr[3]." ".$datos_usr[4]." ".$datos_usr["matri_solo_nombre"])." NO FUE PROMOVIDO(A) AL GRADO SIGUIENTE</center>";	
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

            $cargasAcademicas = mysql_query("SELECT car_id, car_materia, car_ih, mat_id, mat_nombre, mat_area FROM academico_cargas 

                                            INNER JOIN academico_materias ON mat_id=car_materia

                                            INNER JOIN academico_areas ON ar_id=mat_area

                                            WHERE car_curso='".$matricula["mat_grado"]."' AND car_grupo='".$matricula["mat_grupo"]."'",$conexion);

			while($cargas=mysql_fetch_array($cargasAcademicas)){	

                //OBTENEMOS EL PROMEDIO DE LAS CALIFICACIONES

                $boletin = mysql_fetch_array(mysql_query("SELECT avg(bol_nota) FROM academico_boletin WHERE bol_estudiante='".$_POST["id"]."' AND bol_carga='".$cargas["car_id"]."'",$conexion));

                $nota = round($boletin[0],1);

				$desempeno = mysql_fetch_array(mysql_query("SELECT * FROM academico_notas_tipos WHERE notip_categoria='".$config[22]."' AND ".$nota.">=notip_desde AND ".$nota."<=notip_hasta",$conexion));					   

            ?>

            <tr style="text-align:center;">

                <td style="text-align:left;"><?=strtoupper($cargas["mat_nombre"]);?></td>

                <td><?=$cargas["car_ih"];?></td>

				<?php

                    $p = 1;

                    //PERIODOS

                    while($p<=$config[19]){

                        $notasPeriodo = mysql_fetch_array(mysql_query("SELECT bol_nota FROM academico_boletin WHERE bol_estudiante='".$_POST["id"]."' AND bol_carga='".$cargas["car_id"]."' AND bol_periodo='".$p."'",$conexion));

						echo '<td>'.$notasPeriodo[0].'</td>';

                        $p++;

                    }

                ?>    

                <td><?=$nota;?></td>

                <td><?=$desempeno[1];?></td>  

            </tr>

            <?php

            }

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

    <img src="../files/images/sintia.png" height="50" width="100"><br>

    SINTIA -  SISTEMA INTEGRAL DE GESTI&Oacute;N INSTITUCIONAL - <?=date("l, d-M-Y");?>

</div>

                          

</body>

</html>