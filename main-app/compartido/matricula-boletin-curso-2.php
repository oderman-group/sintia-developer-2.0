<?php
session_start();
include("../../config-general/config.php");
include("../../config-general/consulta-usuario-actual.php");
require_once("../class/Estudiantes.php");
require_once(ROOT_PATH."/main-app/class/Boletin.php");
    
$year=$_SESSION["bd"];
if(isset($_GET["year"])){
$year=base64_decode($_GET["year"]);
}
$BD=$_SESSION["inst"]."_".$year;

$modulo = 1;
if(empty($_GET["periodo"])){
	$periodoActual = 1;
}else{
	$periodoActual = base64_decode($_GET["periodo"]);
}
//$periodoActual=2;
if($periodoActual==1) $periodoActuales = "Primero";
if($periodoActual==2) $periodoActuales = "Segundo";
if($periodoActual==3) $periodoActuales = "Tercero";
if($periodoActual==4) $periodoActuales = "Final";?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
<?php
//CONSULTA ESTUDIANTES MATRICULADOS
$filtro = '';
if(!empty($_GET["id"])){$filtro .= " AND mat_id='".base64_decode($_GET["id"])."'";}
if(!empty($_REQUEST["curso"])){$filtro .= " AND mat_grado='".base64_decode($_REQUEST["curso"])."'";}
if(!empty($_REQUEST["grupo"])){$filtro .= " AND mat_grupo='".base64_decode($_REQUEST["grupo"])."'";}

$matriculadosPorCurso = Estudiantes::estudiantesMatriculados($filtro, $BD);
while($matriculadosDatos = mysqli_fetch_array($matriculadosPorCurso, MYSQLI_BOTH)){
//contador materias
$cont_periodos=0;
$contador_indicadores=0;
$materiasPerdidas=0;
//======================= DATOS DEL ESTUDIANTE MATRICULADO =========================
$usr =Estudiantes::obtenerDatosEstudiantesParaBoletin($matriculadosDatos[0],$BD);
$num_usr=mysqli_num_rows($usr);
$datosUsr=mysqli_fetch_array($usr, MYSQLI_BOTH);
$nombre = Estudiantes::NombreCompletoDelEstudiante($datosUsr);	
if($num_usr==0)
{
?>
	<script type="text/javascript">
		window.close();
	</script>
<?php
	exit();
}

$contador_periodos=0;

$contp = 1;
$puestoCurso = 0;
$promedioPuesto = 0;
$puestos = mysqli_query($conexion, "SELECT mat_id, bol_estudiante, bol_carga, mat_nombres, mat_grado, bol_periodo, avg(bol_nota) as prom,
ROW_NUMBER() OVER(ORDER BY prom desc) as puesto 
	FROM $BD.academico_matriculas
INNER JOIN $BD.academico_boletin ON bol_estudiante=mat_id AND bol_periodo='".$_GET["periodo"]."'
WHERE  mat_grado='".$matriculadosDatos['mat_grado']."' AND mat_grupo='".$matriculadosDatos['mat_grupo']."'
GROUP BY mat_id 
ORDER BY prom DESC");

while($puesto = mysqli_fetch_array($puestos, MYSQLI_BOTH)){
	if($puesto['bol_estudiante']==$matriculadosDatos['mat_id']){
		$puestoCurso = $puesto['puesto'];
		$promedioPuesto = round($puesto['prom'],2);
	}
	$contp ++;
}
?>
<!doctype html>
<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
	<meta name="tipo_contenido"  content="text/html;" http-equiv="content-type" charset="utf-8">
<style>
#saltoPagina
{
	PAGE-BREAK-AFTER: always;
}
</style>
</head>

<body style="font-family:Arial;">
<?php
//CONSULTA QUE ME TRAE EL DESEMPEÑO
$consulta_desempeno=mysqli_query($conexion, "SELECT notip_id, notip_nombre, notip_desde, notip_hasta FROM $BD.academico_notas_tipos WHERE notip_categoria=".$config["conf_notas_categoria"].";");	
//CONSULTA QUE ME TRAE LAS areas DEL ESTUDIANTE
$consulta_mat_area_est=mysqli_query($conexion, "SELECT ar_id, car_ih FROM $BD.academico_cargas ac
INNER JOIN $BD.academico_materias am ON am.mat_id=ac.car_materia
INNER JOIN $BD.academico_areas ar ON ar.ar_id= am.mat_area
WHERE  car_curso=".$datosUsr["mat_grado"]." AND car_grupo=".$datosUsr["mat_grupo"]." GROUP BY ar.ar_id ORDER BY ar.ar_posicion ASC;");
//$numero_periodos=$config["conf_periodos_maximos"];
$numero_periodos=$periodoActual;
 ?>

<?php
$nombreInforme = "BOLETÍN DE CALIFICACIONES";
include("../compartido/head-informes.php") ?>

<table width="100%" cellspacing="0" cellpadding="0" border="0" align="left" style="font-size:12px;">
    <tr>
    	<td>C&oacute;digo: <b><?=$datosUsr["mat_matricula"];?></b></td>
        <td colspan="2">Nombre: <b><?=$nombre?></b></td>   
    </tr>
    
    <tr>
    	<td>Grado: <b><?=$datosUsr["gra_nombre"]." ".$datosUsr["gru_nombre"];?></b></td>
        <td>Periodo: <b><?=strtoupper($periodoActuales);?></b></td>
        <td>Puesto Curso:<br> <?=$puestoCurso?></td>    
    </tr>
</table>
<br>
<table width="100%" id="tblBoletin" cellspacing="0" cellpadding="0" rules="all" border="1" align="left">
<tr style="font-weight:bold; background:#EAEAEA; border-color:#000; height:20px; color:#000; font-size:12px;">
<td width="20%" align="center">AREAS/ ASIGNATURAS</td>
<td width="2%" align="center">I.H</td>


<?php $columnas=5; for($j=1;$j<=$numero_periodos;$j++){?>
<td width="3%" align="center"><a href="<?=$_SERVER['PHP_SELF'];?>?id=<?=base64_encode($matriculadosDatos[0]);?>&periodo=<?=base64_encode($j)?>" style="color:#000; text-decoration:underline;"><?=$j?>P</a></td>
<?php $columnas++;}?>



<td width="4%" align="center">PRO</td>
<!--<td width="5%" align="center">PER</td>-->
<td width="8%" align="center">DESEMPE&Ntilde;O</td>   
<td width="5%" align="center">AUS</td>
</tr> 

    <tr style="background:#F06;">
    	<td class="area" id="" colspan="<?=$columnas;?>" style="font-size:12px; font-weight:bold;"></td>
        <!--<td colspan="3"></td>-->
    </tr>
        <!-- Aca ira un while con los indiracores, dentro de los cuales debera ir otro while con las notas de los indicadores-->
        <?php while($fila = mysqli_fetch_array($consulta_mat_area_est, MYSQLI_BOTH)){
		
		if($periodoActual==1){
			$condicion="1";
			$condicion2="1";
			}
		if($periodoActual==2){
			$condicion="1,2";
			$condicion2="2";
		}
		if($periodoActual==3){
			$condicion="1,2,3";
			$condicion2="3";
		}
		if($periodoActual==4){
			$condicion="1,2,3,4";
			$condicion2="4";
		}
		
//CONSULTA QUE ME TRAE EL NOMBRE Y EL PROMEDIO DEL AREA
$consulta_notdef_area=mysqli_query($conexion, "SELECT (SUM(bol_nota)/COUNT(bol_nota)) as suma,ar_nombre FROM $BD.academico_materias am
INNER JOIN $BD.academico_areas a ON a.ar_id=am.mat_area
INNER JOIN $BD.academico_cargas ac ON ac.car_materia=am.mat_id
INNER JOIN $BD.academico_boletin ab ON ab.bol_carga=ac.car_id
WHERE bol_estudiante='".$matriculadosDatos[0]."' and a.ar_id=".$fila["ar_id"]." and bol_periodo in (".$condicion.")
GROUP BY ar_id;");
//CONSULTA QUE ME TRAE LA DEFINITIVA POR MATERIA Y NOMBRE DE LA MATERIA
$consulta_a_mat=mysqli_query($conexion, "SELECT (SUM(bol_nota)/COUNT(bol_nota)) as suma,ar_nombre,mat_nombre,mat_id,car_id FROM $BD.academico_materias am
INNER JOIN $BD.academico_areas a ON a.ar_id=am.mat_area
INNER JOIN $BD.academico_cargas ac ON ac.car_materia=am.mat_id
INNER JOIN $BD.academico_boletin ab ON ab.bol_carga=ac.car_id
WHERE bol_estudiante='".$matriculadosDatos[0]."' and a.ar_id=".$fila["ar_id"]." and bol_periodo in (".$condicion.")
GROUP BY mat_id
ORDER BY mat_id;");
//CONSULTA QUE ME TRAE LAS DEFINITIVAS POR PERIODO
$consulta_a_mat_per=mysqli_query($conexion, "SELECT bol_nota,bol_periodo,ar_nombre,mat_nombre,mat_id FROM $BD.academico_materias am
INNER JOIN $BD.academico_areas a ON a.ar_id=am.mat_area
INNER JOIN $BD.academico_cargas ac ON ac.car_materia=am.mat_id
INNER JOIN $BD.academico_boletin ab ON ab.bol_carga=ac.car_id
WHERE bol_estudiante='".$matriculadosDatos[0]."' and a.ar_id=".$fila["ar_id"]." and bol_periodo in (".$condicion.")
ORDER BY mat_id,bol_periodo
;");


//CONSULTA QUE ME TRAE LOS INDICADORES DE CADA MATERIA
$consulta_a_mat_indicadores=mysqli_query($conexion, "SELECT mat_nombre,mat_area,mat_id,ind_nombre,ipc_periodo,(SUM(cal_nota)/COUNT(cal_nota))as nota FROM $BD.academico_materias am
INNER JOIN $BD.academico_areas a ON a.ar_id=am.mat_area
INNER JOIN $BD.academico_cargas ac ON ac.car_materia=am.mat_id
INNER JOIN ".BD_ACADEMICA.".academico_indicadores_carga aic ON aic.ipc_carga=ac.car_id AND aic.institucion={$config['conf_id_institucion']} AND aic.year={$year}
INNER JOIN $BD.academico_indicadores ai ON aic.ipc_indicador=ai.ind_id
INNER JOIN $BD.academico_actividades aa ON aa.act_id_tipo=aic.ipc_indicador AND act_id_carga=car_id
INNER JOIN $BD.academico_calificaciones aac ON aac.cal_id_actividad=aa.act_id
WHERE car_curso=".$datosUsr["mat_grado"]."  and car_grupo=".$datosUsr["mat_grupo"]." and mat_area=".$fila["ar_id"]." AND ipc_periodo in (".$condicion.") AND cal_id_estudiante='".$matriculadosDatos[0]."' and act_periodo=".$condicion2."
group by act_id_tipo, act_id_carga
order by mat_id,ipc_periodo,ind_id;");

$numIndicadores=mysqli_num_rows($consulta_a_mat_indicadores);

$resultado_not_area=mysqli_fetch_array($consulta_notdef_area, MYSQLI_BOTH);
$numfilas_not_area=mysqli_num_rows($consulta_notdef_area);
$total_promedio=0;
if(!empty($resultado_not_area['suma'])){
	$total_promedio=round( $resultado_not_area["suma"],1);
}

if($total_promedio==1)	$total_promedio="1.0";	if($total_promedio==2)	$total_promedio="2.0";		if($total_promedio==3)	$total_promedio="3.0";	if($total_promedio==4)	$total_promedio="4.0";	if($total_promedio==5)	$total_promedio="5.0";
	if($numfilas_not_area>0){
			?>
  <tr bgcolor="#ABABAB" style="font-size:12px;">
            <td style="font-size:12px; height:25px; font-weight:bold;"><?php echo $resultado_not_area["ar_nombre"];?></td> 
            <td align="center" style="font-weight:bold; font-size:12px;"></td>
            <?php for($k=1;$k<=$numero_periodos;$k++){ 
			?>
			<td class=""  align="center" style="font-weight:bold;"></td>
            <?php }?>
        <td align="center" style="font-weight:bold;"><?php 
		$consultaDesempenoNotaPromArea=mysqli_query($conexion, "SELECT * FROM academico_notas_tipos WHERE notip_categoria='".$config[22]."' AND ".$total_promedio.">=notip_desde AND ".$total_promedio."<=notip_hasta");
		$desempenoNotaPromArea = mysqli_fetch_array($consultaDesempenoNotaPromArea, MYSQLI_BOTH);
		
		if($datosUsr["mat_grado"]>11){
				$notaFA = ceil($total_promedio);
			/*
				switch($notaFA){
					case 1: echo "D"; break;
					case 2: echo "I"; break;
					case 3: echo "A"; break;
					case 4: echo "S"; break;
					case 5: echo "E"; break;
				}
				*/
			echo $desempenoNotaPromArea['notip_nombre'];
				}else{
				$totalPromedioFinal=$total_promedio;
				if($config['conf_forma_mostrar_notas'] == CUALITATIVA){
				$estiloNota = Boletin::obtenerDatosTipoDeNotas($config['conf_notas_categoria'], $total_promedio, $BD);
				$totalPromedioFinal= !empty($estiloNota['notip_nombre']) ? $estiloNota['notip_nombre'] : "";
				}
		echo $totalPromedioFinal;
				}
		
		?></td>
         <td align="center" style="font-weight:bold;"></td>
          <td align="center" style="font-weight:bold;"></td>
	</tr>
<?php

while($fila2=mysqli_fetch_array($consulta_a_mat, MYSQLI_BOTH)){ 
	$contador_periodos=0;
	mysqli_data_seek($consulta_a_mat_per,0);
	//CONSULTAR NOTA POR PERIODO
	while($fila3=mysqli_fetch_array($consulta_a_mat_per, MYSQLI_BOTH)){
		if($fila2["mat_id"]==$fila3["mat_id"]){
			$contador_periodos++;
			$nota_periodo=round($fila3["bol_nota"],1);
			if($nota_periodo==1)$nota_periodo="1.0";	if($nota_periodo==2)$nota_periodo="2.0";	if($nota_periodo==3)$nota_periodo="3.0";	if($nota_periodo==4)$nota_periodo="4.0";	if($nota_periodo==5)$nota_periodo="5.0";
			$notas[$contador_periodos] =$nota_periodo;
		}
	}//FIN FILA3
?>
 <tr bgcolor="#EAEAEA" style="font-size:12px;">
            <td style="font-size:12px; height:35px; font-weight:bold;background:#EAEAEA;">&raquo;<?php echo $fila2["car_id"]." - ".$fila2["mat_nombre"];?></td> 
            <td align="center" style="font-weight:bold; font-size:12px;background:#EAEAEA;"><?php echo $fila["car_ih"];?></td>
<?php 
for($l=1;$l<=$numero_periodos;$l++){
	$consultaNotaEstudiante=mysqli_query($conexion, "SELECT * FROM $BD.academico_boletin WHERE bol_carga='".$fila2['car_id']."' AND bol_estudiante='".$matriculadosDatos[0]."' AND bol_periodo='".$l."'");
	$notaDelEstudiante = mysqli_fetch_array($consultaNotaEstudiante, MYSQLI_BOTH);
?>
			<td class=""  align="center" style="font-weight:bold; background:#EAEAEA; font-size:16px;">
			<?php 
			if($notaDelEstudiante['bol_nota']!=""){
				if($datosUsr["mat_grado"]>11){
					$notaF = ceil($notaDelEstudiante['bol_nota']);
					/*
					switch($notaF){
						case 1: echo "D"; break;
						case 2: echo "I"; break;
						case 3: echo "A"; break;
						case 4: echo "S"; break;
						case 5: echo "E"; break;
					}
					*/
					echo $desempenoNotaP[1];
				}else{
					$desempenoNotaP = Boletin::obtenerDatosTipoDeNotas($config['conf_notas_categoria'], $notaDelEstudiante['bol_nota'], $BD);
					if($config['conf_forma_mostrar_notas'] == CUALITATIVA){
						echo $desempenoNotaP['notip_nombre'];
					}else{
						echo $notaDelEstudiante['bol_nota']."<br>".$desempenoNotaP['notip_nombre'];
					}
				}

				if (!isset($promedios[$l])) {
					$promedios[$l] = 0;
				}
				if (!isset($contpromedios[$l])) {
					$contpromedios[$l] = 0;
				}
				if (!empty($notaDelEstudiante['bol_nota'])) {
					$promedios[$l] += $notaDelEstudiante['bol_nota'];
				}
				$contpromedios[$l]+=1;
			}else{
					echo "-";
			}
			?>
            </td>
        <?php
		
		
	}
	 ?>
      <?php 
	  $total_promedio2=round( $fila2["suma"],1);
	   
	   if($total_promedio2==1)	$total_promedio2="1.0";	if($total_promedio2==2)	$total_promedio2="2.0";		if($total_promedio2==3)	$total_promedio2="3.0";	if($total_promedio2==4)	$total_promedio2="4.0";	if($total_promedio2==5)	$total_promedio2="5.0";
	    $msj='';
	   if($total_promedio2<$config[5]){
			$consultaNivelaciones=mysqli_query($conexion, "SELECT * FROM  ".BD_ACADEMICA.".academico_nivelaciones WHERE niv_id_asg='".$fila2['mat_id']."' AND niv_cod_estudiante='".$matriculadosDatos[0]."' AND institucion={$config['conf_id_institucion']} AND year={$year}");
		   $nivelaciones = mysqli_fetch_array($consultaNivelaciones, MYSQLI_BOTH);

			if(!empty($nivelaciones['niv_definitiva'])){
				if($nivelaciones['niv_definitiva']<$config[5]){
					$materiasPerdidas++;
				}else{
					$total_promedio2 = $nivelaciones['niv_definitiva'];
					$msj='Niv';
				}
			}
		}
	   ?>
       
        <td align="center" style="font-weight:bold; background:#EAEAEA;"><?php 
		$consultaDesmpenoNotaXasig=mysqli_query($conexion, "SELECT * FROM $BD.academico_notas_tipos WHERE notip_categoria='".$config[22]."' AND ".$total_promedio2.">=notip_desde AND ".$total_promedio2."<=notip_hasta");
		$desempenoNotaXasig = mysqli_fetch_array($consultaDesmpenoNotaXasig, MYSQLI_BOTH);
	
					if($datosUsr["mat_grado"]>11){
				$notaFI = ceil($total_promedio2);
						/*
				switch($notaFI){
					case 1: echo "D"; break;
					case 2: echo "I"; break;
					case 3: echo "A"; break;
					case 4: echo "S"; break;
					case 5: echo "E"; break;
				}
				*/
						echo $desempenoNotaXasig['notip_nombre'];
						
				}else{
						$totalPromedio2Final=$total_promedio2;
						if($config['conf_forma_mostrar_notas'] == CUALITATIVA){
						$estiloNota = Boletin::obtenerDatosTipoDeNotas($config['conf_notas_categoria'], $total_promedio2, $BD);
						$totalPromedio2Final= !empty($estiloNota['notip_nombre']) ? $estiloNota['notip_nombre'] : "";
						}
				echo $totalPromedio2Final;
				}
		
		?></td>
        <td align="center" style="font-weight:bold; background:#EAEAEA;"><?php //DESEMPEÑO
		while($r_desempeno=mysqli_fetch_array($consulta_desempeno, MYSQLI_BOTH)){
			if($total_promedio2>=$r_desempeno["notip_desde"] && $total_promedio2<=$r_desempeno["notip_hasta"]){
				if($datosUsr["mat_grado"]>11){
					/*
					$notaFD = ceil($total_promedio2);
				switch($notaFD){
					case 1: echo "BAJO"; break;
					case 2: echo "BAJO"; break;
					case 3: echo "B&Aacute;SICO"; break;
					case 4: echo "ALTO"; break;
					case 5: echo "SUPERIOR"; break;					
				}
				*/
				echo $r_desempeno["notip_nombre"];

				}else{
					
						echo $r_desempeno["notip_nombre"];
					}
				}
			}
			mysqli_data_seek($consulta_desempeno,0);
			$matmaxaus='';
			if(!empty($fila2["matmaxaus"])){ $matmaxaus=$fila2["matmaxaus"];}
		 ?></td>
        <td align="center" style="font-weight:bold; background:#EAEAEA;"><?php if(!empty($r_ausencias[0]) && $r_ausencias[0]>0){ echo $r_ausencias[0]."/".$matmaxaus;} else{ echo "0.0/".$matmaxaus;}?></td>
	
	</tr>
	
<?php
if($numIndicadores>0){
	 mysqli_data_seek($consulta_a_mat_indicadores,0);
	 $contador_indicadores=0;
	while($fila4=mysqli_fetch_array($consulta_a_mat_indicadores, MYSQLI_BOTH)){
	if($fila4["mat_id"]==$fila2["mat_id"]){
		$contador_indicadores++;
		$nota_indicador=round($fila4["nota"],1);
		 if($nota_indicador==1)	$nota_indicador="1.0";	if($nota_indicador==2)	$nota_indicador="2.0";		if($nota_indicador==3)	$nota_indicador="3.0";	if($nota_indicador==4)	$nota_indicador="4.0";	if($nota_indicador==5)	$nota_indicador="5.0";

		 $consultaDesmpenoNotaInd=mysqli_query($conexion, "SELECT * FROM ".$BD.".academico_notas_tipos WHERE notip_categoria='".$config[22]."' AND notip_desde<='".$nota_indicador."' AND notip_hasta>='".$nota_indicador."'");
		
		$desempenoNotaIndNombre2 = mysqli_fetch_array($consultaDesmpenoNotaInd, MYSQLI_BOTH);
	?>
<tr bgcolor="#FFF" style="font-size:12px;">
            <td style="font-size:12px; height:15px;"><?php echo $contador_indicadores.".".$fila4["ind_nombre"];?></td> 
            <td align="center" style="font-weight:bold; font-size:12px;"></td>
            <?php for($m=1;$m<=$numero_periodos;$m++){ 
			$desempenoNotaInd = mysqli_fetch_array($consultaDesmpenoNotaInd, MYSQLI_BOTH);
			?>
			<td class=""  align="center" style="font-weight:bold;"><?php if($periodoActual==$m){
				if($datosUsr["mat_grado"]>11){
				$notaFII = ceil($nota_indicador);
					/*
				switch($notaFII){
					case 1: echo "D"; break;
					case 2: echo "I"; break;
					case 3: echo "A"; break;
					case 4: echo "S"; break;
					case 5: echo "E"; break;
				}
				*/
					echo $desempenoNotaInd['notip_nombre'];
			}else{
				$notaIndicadorFinal=$nota_indicador;
				if($config['conf_forma_mostrar_notas'] == CUALITATIVA){
				  $estiloNota = Boletin::obtenerDatosTipoDeNotas($config['conf_notas_categoria'], $nota_indicador, $BD);
				  $notaIndicadorFinal= !empty($estiloNota['notip_nombre']) ? $estiloNota['notip_nombre'] : "";
				}
				echo $notaIndicadorFinal;
			}
				
				} ?></td>
            <?php } ?>
 <td align="center" style="font-weight:bold;"></td>
        <td align="center" style="font-weight:bold;"></td>
        <td align="center" style="font-weight:bold;"></td>
<?php
	}//fin if
	}
}
?>
	<!-- observaciones de la asignatura-->
	<?php
	$consultaObservaciones=mysqli_query($conexion, "SELECT * FROM $BD.academico_boletin
	WHERE bol_carga='".$fila2["car_id"]."' AND bol_periodo='".$_GET["periodo"]."' AND bol_estudiante='".$matriculadosDatos[0]."'");
	$observacion = mysqli_fetch_array($consultaObservaciones, MYSQLI_BOTH);
	if(!empty($observacion['bol_observaciones_boletin'])){
	?>
	<tr>
		<td colspan="7">
			<h5 align="center">Observaciones</h5>
			<p style="margin-left: 5px; font-size: 11px; margin-top: -10px; margin-bottom: 5px; font-style: italic;">
				<?=$observacion['bol_observaciones_boletin'];?>
			</p>
		</td>
	</tr>
	<?php }?>
	
<?php	
}//while fin materias
?>  
<?php }}//while fin areas?>
	 

          

            

    <tr align="center" style="font-size:12px; font-weight:bold;">
        <td colspan="2" align="right">PROMEDIO</td>

		<?php for($n=1;$n<=$numero_periodos;$n++){ 
		$notaFFF =0;
		if(!empty($contpromedios[$n])){
			$notaFFF = round(($promedios[$n]/$contpromedios[$n]),1);
		}
		$consultaDesempenoNotaProm=mysqli_query($conexion, "SELECT * FROM $BD.academico_notas_tipos WHERE notip_categoria='".$config[22]."' AND ".$notaFFF.">=notip_desde AND ".$notaFFF."<=notip_hasta");
		$desempenoNotaProm = mysqli_fetch_array($consultaDesempenoNotaProm, MYSQLI_BOTH);
		?>
        <td style="font-size:16px;">
        	<?php 
		if($promedios[$n]!=0){
			if($datosUsr["mat_grado"]>11){
				$notaFF = ceil(round(($promedios[$n]/$contpromedios[$n]),1));
				echo $desempenoNotaProm['notip_nombre'];
			}else{
				$promedioTotal= round(($promedios[$n]/$contpromedios[$n]),1);
				$promedioTotalFinal=$promedioTotal;
				if($config['conf_forma_mostrar_notas'] == CUALITATIVA){
				  $estiloNota = Boletin::obtenerDatosTipoDeNotas($config['conf_notas_categoria'], $promedioTotal, $BD);
				  $promedioTotalFinal= !empty($estiloNota['notip_nombre']) ? $estiloNota['notip_nombre'] : "";
				}
				echo $promedioTotalFinal;
			}
			
			}?></td>
        <?php } ?>
        <td></td>
        <td colspan="2">&nbsp;</td>
    </tr>
    
</table>

<?php for($n=1;$n<=$numero_periodos;$n++){if($promedios[$n]!=0){$promedios[$n]=0; $contpromedios[$n]=0;} } ?>

<p>&nbsp;</p>
<?php 
$cndisiplina = mysqli_query($conexion, "SELECT * FROM ".BD_DISCIPLINA.".disiplina_nota WHERE dn_cod_estudiante='".$matriculadosDatos[0]."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]} AND dn_periodo in(".$condicion.");");
if(mysqli_num_rows($cndisiplina)>0){
?>
<table width="100%" id="tblBoletin" cellspacing="0" cellpadding="0" rules="all" border="1" align="center">

    <tr style="font-weight:bold; background:#036; border-color:#036; height:40px; color:#FC0; font-size:12px; text-align:center">
    	<td colspan="3">NOTA DE COMPORTAMIENTO</td>
    </tr>
    
    <tr style="font-weight:bold; background:#F06; border-color:#F06; height:25px; color:#FFF; font-size:12px; text-align:center">
        <td width="8%">Periodo</td>
        <!--<td width="8%">Nota</td>-->
        <td>Observaciones</td>
    </tr>
<?php while($rndisiplina=mysqli_fetch_array($cndisiplina, MYSQLI_BOTH)){
// $consultaDesempenoND=mysqli_query($conexion, "SELECT * FROM $BD.academico_notas_tipos WHERE notip_categoria='".$config[22]."' AND ".$rndisiplina["dn_nota"].">=notip_desde AND ".$rndisiplina["dn_nota"]."<=notip_hasta");
// $desempenoND = mysqli_fetch_array($consultaDesempenoND, MYSQLI_BOTH);
?>
    <tr align="center" style="font-weight:bold; font-size:12px; height:20px;">
        <td><?=$rndisiplina["dn_periodo"]?></td>
        <!--<td><?=$desempenoND[1]?></td>-->
        <td align="left"><?=$rndisiplina["dn_observacion"]?></td>
    </tr>
<?php }?>
</table>

<?php }?>
<!--<hr align="center" width="100%">-->
<div align="center">
<table width="100%" cellspacing="0" cellpadding="0"  border="0" style="text-align:center; font-size:12px;">
  <tr>
    <td style="font-weight:bold;" align="left">
    
    <!-- <?php if($num_observaciones>0){?>COMPORTAMIENTO:<?php }?> <b><u><?=strtoupper($r_diciplina[3]);?></u></b><br> -->
    	<?php
	?>
    </td>
  </tr>
</table>
<?php
//print_r($vectorT);
?>
</div>
<!--
<div>
<table width="100%" cellspacing="0" cellpadding="0"  border="0" style="text-align:center; font-size:12px;">
  <tr>
    <td style="font-weight:bold;" align="left">
    OBSERVACIONES:_____________________________________________________________________________________________________________<br><br>
    ____________________________________________________________________________________________________________________________<br><br>
    ____________________________________________________________________________________________________________________________<br>
    </td>
  </tr>
</table>

</div>
-->

<p>&nbsp;</p>
<table width="100%" cellspacing="0" cellpadding="0" rules="none" border="0" style="text-align:center; font-size:10px;">
	<tr>
		<td align="center">_________________________________<br><!--<?=strtoupper("");?><br>-->Rector(a)</td>
		<td align="center">_________________________________<br><!--<?=strtoupper("");?><br>-->Director(a) de grupo</td>
    </tr>
</table> 

<!--
<br>
<div align="center">
<table width="100%" cellspacing="0" cellpadding="0"  border="1" style="text-align:center; font-size:8px; background:#FFFFCC;">
  <tr style="text-transform:uppercase;">
    <td style="font-weight:bold;" align="right">ESCALA NACIONAL</td><td>Desempe&ntilde;o Superior</td><td>Desempe&ntilde;o Alto</td><td>Desempe&ntilde;o B&aacute;sico</td><td>Desempe&ntilde;o Bajo</td>
  </tr>
  
  <tr>
  	<td style="font-weight:bold;" align="right">RANGO INSTITUCIONAL</td>
  	<td>NO HAY</td><td>NO HAY</td><td>NO HAY</td><td>NO HAY</td>  
  </tr>

</table>
-->




</div>  
<?php 
if($periodoActual==4){
	if($materiasPerdidas>=$config["conf_num_materias_perder_agno"])
		$msj = "<center>EL (LA) ESTUDIANTE ".strtoupper($datosUsr[3]." ".$datosUsr[4]." ".$datosUsr["mat_nombres"])." NO FUE PROMOVIDO(A) AL GRADO SIGUIENTE</center>";
	elseif($materiasPerdidas<$config["conf_num_materias_perder_agno"] and $materiasPerdidas>0)
		$msj = "<center>EL (LA) ESTUDIANTE ".strtoupper($datosUsr[3]." ".$datosUsr[4]." ".$datosUsr["mat_nombres"])." DEBE NIVELAR LAS MATERIAS PERDIDAS</center>";
	else
		$msj = "<center>EL (LA) ESTUDIANTE ".strtoupper($datosUsr[3]." ".$datosUsr[4]." ".$datosUsr["mat_nombres"])." FUE PROMOVIDO(A) AL GRADO SIGUIENTE</center>";	
}
?>


<?php include("../compartido/footer-informes.php") ?>;
				                   
<!-- 
<div align="center" style="font-size:10px; margin-top:10px;">
                                        <img src="../files/images/sintia.png" height="50" width="100"><br>
                                        SINTIA -  SISTEMA INTEGRAL DE GESTI&Oacute;N INSTITUCIONAL - <?=date("l, d-M-Y");?>
                                    </div>
                                    -->
 <div id="saltoPagina"></div>
                                    
<?php
 }// FIN DE TODOS LOS MATRICULADOS
?>
<script type="application/javascript">
print();
</script>                                    
                          
</body>
</html>
