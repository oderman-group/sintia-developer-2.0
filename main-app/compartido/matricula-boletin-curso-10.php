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

if($periodoActual==1) $periodoActuales = "Primero";
if($periodoActual==2) $periodoActuales = "Segundo";
if($periodoActual==3) $periodoActuales = "Tercero";
if($periodoActual==4) $periodoActuales = "Final";
//CONSULTA ESTUDIANTES MATRICULADOS
$filtro = '';
if(!empty($_GET["id"])){$filtro .= " AND mat_id='".base64_decode($_GET["id"])."'";}
if(!empty($_REQUEST["curso"])){$filtro .= " AND mat_grado='".base64_decode($_REQUEST["curso"])."'";}
if(!empty($_REQUEST["grupo"])){$filtro .= " AND mat_grupo='".base64_decode($_REQUEST["grupo"])."'";}

$matriculadosPorCurso = Estudiantes::estudiantesMatriculados($filtro, $BD);
$numMatriculados = mysqli_num_rows($matriculadosPorCurso);
while($matriculadosDatos = mysqli_fetch_array($matriculadosPorCurso, MYSQLI_BOTH)){
	//contadores
	$contador_periodos = 0;
	$contador_indicadores = 0;
	$materiasPerdidas = 0;
	if($matriculadosDatos[0]==""){?>
		<script type="text/javascript">window.close();</script>
	<?php
		//exit();
	}
$contp = 1;
$puestoCurso = 0;
$puestos = mysqli_query($conexion, "SELECT mat_id, bol_estudiante, bol_carga, mat_nombres, mat_grado, bol_periodo, avg(bol_nota) as prom FROM $BD.academico_matriculas
INNER JOIN $BD.academico_boletin ON bol_estudiante=mat_id AND bol_periodo='".$periodoActual."'
WHERE  mat_grado='".$matriculadosDatos['mat_grado']."' GROUP BY mat_id ORDER BY prom DESC");	
while($puesto = mysqli_fetch_array($puestos, MYSQLI_BOTH)){
	if($puesto['bol_estudiante']==$matriculadosDatos['mat_id']){$puestoCurso = $contp;}
	$contp ++;
}
//======================= DATOS DEL ESTUDIANTE MATRICULADO =========================
$usr =Estudiantes::obtenerDatosEstudiantesParaBoletin($matriculadosDatos[0],$BD);
$datosUsr = mysqli_fetch_array($usr, MYSQLI_BOTH);
$nombre = Estudiantes::NombreCompletoDelEstudiante($datosUsr);
?>
<!doctype html>
<html class="no-js" lang="en">
<head>
	<meta name="tipo_contenido"  content="text/html;" http-equiv="content-type" charset="utf-8">
	<style>
    	#saltoPagina{PAGE-BREAK-AFTER: always;}
    </style>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
</head>

<body style="font-family:Arial; font-size:9px;">

<div>
	
	<!--<div align="center" style="margin-bottom: 10px;"><img src="../files/images/logo/<?=$informacion_inst["info_logo"]?>" width="350"></div>-->
	
	<div align="center" style="margin-bottom: 10px;">
    <img src="../files/images/logo/<?=$informacion_inst["info_logo"]?>" height="150" width="200"><br>
    <!-- <?=$informacion_inst["info_nombre"]?><br>
    BOLETÍN DE CALIFICACIONES<br> --></div>
    
	<div style="width:100%">
        <table width="100%" cellspacing="5" cellpadding="5" border="1" rules="all">
            <tr>
                <td>C&oacute;digo:<br> <?=number_format($datosUsr["mat_documento"],0,",",".");?></td>
                <td>Nombre:<br> <?=$nombre?></td>
                <td>Grado:<br> <?=$datosUsr["gra_nombre"]." ".$datosUsr["gru_nombre"];?></td>
                <td>Puesto Curso:<br> <?=$puestoCurso;?></td>
            </tr>
            
            <tr>
                <td>Jornada:<br> Mañana</td>
                <td>Sede:<br> <?=$informacion_inst["info_nombre"]?></td>
                <td colspan="2">Periodo:<br> <b><?=$periodoActual." (".$year.")";?></b></td>
               <!-- <td>Puesto Colegio:<br> &nbsp;</td>   -->
            </tr>
        </table>
        <p>&nbsp;</p>
    </div>
</div>

<table width="100%" cellspacing="5" cellpadding="5" rules="all" border="1">
    <thead>
        <tr style="font-weight:bold; text-align:center; background-color: #74cc82;">
            <td width="20%" rowspan="2">AREAS / ASIGNATURAS</td>
            <td width="2%" rowspan="2">I.H.</td>
            
            <?php  
			for($j=1;$j<=$periodoActual;$j++){
			$consultaPeriodosCursos=mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_grados_periodos
			WHERE gvp_grado='".$datosUsr['gra_id']."' AND gvp_periodo='".$j."' AND institucion={$config['conf_id_institucion']} AND year={$year}");
			$periodosCursos = mysqli_fetch_array($consultaPeriodosCursos, MYSQLI_BOTH);
			$periodosCursos['gvp_valor'] = 25;
			?>
                <td width="3%" colspan="2"><a href="<?=$_SERVER['PHP_SELF'];?>?id=<?=$datosUsr[0];?>&periodo=<?=$j?>" style="color:#000; text-decoration:none;">Periodo <?=$j."<br>(".$periodosCursos['gvp_valor']."%)"?></a></td>
            <?php }?>
            <td width="3%" colspan="2">Acumulado</td>
        </tr> 
        
        <tr style="font-weight:bold; text-align:center; background-color: #74cc82;">
            <?php  for($j=1;$j<=$periodoActual;$j++){ ?>

                <td width="3%">Nota</td>
                <td width="3%">Desempeño</td>
            <?php }?>
            <td width="3%">Nota</td>
            <td width="3%">Desempeño</td>

        </tr>
        
    </thead>
    
    <?php
	$materiasPerdidas = 0;
	$colspan = 2 + (2 * $periodoActual);
	$conAreas = mysqli_query($conexion, "SELECT * FROM $BD.academico_cargas
	INNER JOIN ".BD_ACADEMICA.".academico_materias am ON am.mat_id=car_materia AND am.institucion={$config['conf_id_institucion']} AND am.year={$year}
	INNER JOIN ".BD_ACADEMICA.".academico_areas a ON a.ar_id=am.mat_area AND a.institucion={$config['conf_id_institucion']} AND a.year={$year}
	WHERE car_curso='".$datosUsr['mat_grado']."' AND car_grupo='".$datosUsr['mat_grupo']."'
	GROUP BY am.mat_area
	ORDER BY ar_posicion
	");
	while($datosAreas = mysqli_fetch_array($conAreas, MYSQLI_BOTH)){
	?>
    <tbody>
        <!-- AREAS -->
		<tr style="background: lightgray; color:black; height: 30px; font-weight: bold; font-size: 14px;">
            <td colspan="<?=$colspan;?>"><?=strtoupper($datosAreas['ar_nombre']);?></td> 
            <td align="center">&nbsp;</td>
            <td align="center">&nbsp;</td>

        </tr>
		
		<?php
		$contador=1;
		$conCargas = mysqli_query($conexion, "SELECT * FROM $BD.academico_cargas
		INNER JOIN ".BD_ACADEMICA.".academico_materias am ON am.mat_id=car_materia AND am.mat_area='".$datosAreas['ar_id']."' AND am.institucion={$config['conf_id_institucion']} AND am.year={$year}
		WHERE car_curso='".$datosUsr['mat_grado']."' AND car_grupo='".$datosUsr['mat_grupo']."'
		");
		while($datosCargas = mysqli_fetch_array($conCargas, MYSQLI_BOTH)){
		?>
		<!-- ASIGNATURAS -->
		<tr style="background:#fff; height: 25px; font-weight: bold;">
            <td><?=strtoupper($datosCargas['mat_nombre']);?></td>
            <td align="center"><?=$datosCargas['car_ih'];?></td> 
            <?php 
			$promedioMateria = 0;
			$sumaPorcentaje = 0;
			for($j=1;$j<=$periodoActual;$j++){
				$consultaPeriodosCursos=mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_grados_periodos
				WHERE gvp_grado='".$datosUsr['gra_id']."' AND gvp_periodo='".$j."' AND institucion={$config['conf_id_institucion']} AND year={$year}");
				$periodosCursos = mysqli_fetch_array($consultaPeriodosCursos, MYSQLI_BOTH);
				
				$periodosCursos['gvp_valor'] = 25;

				$decimal = $periodosCursos['gvp_valor']/100;
				
				$consultaBoletin=mysqli_query($conexion, "SELECT * FROM $BD.academico_boletin 
                INNER JOIN ".BD_ACADEMICA.".academico_notas_tipos ntp ON ntp.notip_categoria='".$config["conf_notas_categoria"]."' AND bol_nota>=ntp.notip_desde AND bol_nota<=ntp.notip_hasta AND ntp.institucion={$config['conf_id_institucion']} AND ntp.year={$year}
                WHERE bol_carga='".$datosCargas['car_id']."' AND bol_estudiante='".$datosUsr['mat_id']."' AND bol_periodo='".$j."'");
                $datosBoletin = mysqli_fetch_array($consultaBoletin, MYSQLI_BOTH);
				
				$consultaAusencias=mysqli_query($conexion, "SELECT sum(aus_ausencias) FROM ".BD_ACADEMICA.".academico_clases cls 
                INNER JOIN ".BD_ACADEMICA.".academico_ausencias aus ON aus.aus_id_clase=cls.cls_id AND aus.aus_id_estudiante<='".$datosUsr['mat_id']."' AND aus.institucion={$config['conf_id_institucion']} AND aus.year={$year}
                WHERE cls.cls_id_carga='".$datosCargas['car_id']."' AND cls.cls_periodo='".$j."' AND cls.institucion={$config['conf_id_institucion']} AND cls.year={$year}");
				$datosAusencias = mysqli_fetch_array($consultaAusencias, MYSQLI_BOTH);
				
				$promedioMateria +=$datosBoletin['bol_nota']*$decimal;
				$sumaPorcentaje += $decimal;
				$colorFondoNota = '';
				if($datosBoletin['bol_nota']!="" and $datosBoletin['bol_nota']<$config["conf_nota_minima_aprobar"]){$colorFondoNota = 'tomato';}
            ?>

                <td align="center" style="background-color: <?=$colorFondoNota;?>;"><?=$datosBoletin['bol_nota'];?></td>
                <td align="center"><?=$datosBoletin['notip_nombre'];?></td>
            <?php 
			}
			$promedioMateria = ($promedioMateria / $sumaPorcentaje);
			$promedioMateria = round(($promedioMateria), $config['conf_decimales_notas']);
			
			$colorFondoPromedioM = '';
			if($promedioMateria!="" and $promedioMateria<$config["conf_nota_minima_aprobar"]){$colorFondoPromedioM = 'tomato'; $materiasPerdidas++;}
			
			$consultaPromediosMateriasEstiloNotas=mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_notas_tipos 
			WHERE notip_categoria='".$config["conf_notas_categoria"]."' AND '".$promedioMateria."'>=notip_desde AND '".$promedioMateria."'<=notip_hasta AND institucion={$config['conf_id_institucion']} AND year={$year}");
			$promediosMateriaEstiloNota = mysqli_fetch_array($consultaPromediosMateriasEstiloNotas, MYSQLI_BOTH);
			?>
            <td align="center" style="background-color: <?=$colorFondoPromedioM;?>"><?=$promedioMateria;?></td>
            <td align="center"><?=$promediosMateriaEstiloNota['notip_nombre'];?></td>

        </tr>
		
		
		<?php
		$indicadores = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_indicadores_carga aic
		INNER JOIN ".BD_ACADEMICA.".academico_indicadores ai ON ai.ind_id=aic.ipc_indicador AND ai.institucion={$config['conf_id_institucion']} AND ai.year={$year}
		WHERE aic.ipc_carga='".$datosCargas['car_id']."' AND aic.ipc_periodo='".$periodoActual."' AND aic.institucion={$config['conf_id_institucion']} AND aic.year={$year}
		");
		while($ind = mysqli_fetch_array($indicadores, MYSQLI_BOTH)){
			$consultaCalificacionesIndicadores=mysqli_query($conexion, "SELECT ROUND(AVG(cal_nota),2) FROM ".BD_ACADEMICA.".academico_calificaciones aac
			INNER JOIN ".BD_ACADEMICA.".academico_actividades aa ON aa.act_id=aac.cal_id_actividad AND aa.act_id_tipo='".$ind['ipc_indicador']."' AND aa.act_id_carga='".$datosCargas['car_id']."' AND aa.act_periodo='".$periodoActual."' AND aa.act_estado=1 AND aa.institucion={$config['conf_id_institucion']} AND aa.year={$year}
			WHERE aac.cal_id_estudiante='".$datosUsr['mat_id']."' AND aac.institucion={$config['conf_id_institucion']} AND aac.year={$year}");
			$calificacionesIndicadores = mysqli_fetch_array($consultaCalificacionesIndicadores, MYSQLI_BOTH);
		?>
		<!-- INDICADORES -->
		<tr>
            <td><?=$ind['ipc_indicador'].") ".$ind['ind_nombre'];?></td>
            <td align="center"><?=$ind['ipc_valor']."%";?></td> 
            <?php 
			$promedioMateria = 0;
			for($j=1;$j<=$periodoActual;$j++){

				$notaIndicadorFinal="&nbsp;";
				if($j==$periodoActual){
					$notaIndicadorFinal=$calificacionesIndicadores[0];
					if($config['conf_forma_mostrar_notas'] == CUALITATIVA){
						$estiloNota = Boletin::obtenerDatosTipoDeNotas($config['conf_notas_categoria'], $calificacionesIndicadores[0], $BD);
						$notaIndicadorFinal= !empty($estiloNota['notip_nombre']) ? $estiloNota['notip_nombre'] : "";
					}
				}
            ?>
                <td align="center">&nbsp;</td>
                <td align="center"><?=$notaIndicadorFinal;?></td>

            <?php 
			}
			?>
            <td align="center">&nbsp;</td>
            <td align="center">&nbsp;</td>

        </tr>
		<?php }?>
		
		<?php }?>
		
		
    </tbody>
    <?php 
		$contador++;
	}
	?>

	<tfoot>
    	<tr style="font-weight:bold; text-align:center; font-size:13px;">
        	<td style="text-align:left;">PROMEDIO/TOTAL</td>
            <td>-</td> 

            <?php 
            for($j=1;$j<=$periodoActual;$j++){
				$consultaPromediosPeriodos=mysqli_query($conexion, "SELECT ROUND(AVG(bol_nota),2) as promedio FROM $BD.academico_boletin 
                WHERE bol_estudiante='".$datosUsr['mat_id']."' AND bol_periodo='".$j."'");
				$promediosPeriodos = mysqli_fetch_array($consultaPromediosPeriodos, MYSQLI_BOTH);
				
				$consultaPromediosEstiloNota=mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_notas_tipos 
				WHERE notip_categoria='".$config["conf_notas_categoria"]."' AND '".$promediosPeriodos['promedio']."'>=notip_desde AND '".$promediosPeriodos['promedio']."'<=notip_hasta AND institucion={$config['conf_id_institucion']} AND year={$year}");
				$promediosEstiloNota = mysqli_fetch_array($consultaPromediosEstiloNota, MYSQLI_BOTH);
            ?>
                <td><?=$promediosPeriodos['promedio'];?></td>
                <td><?=$promediosEstiloNota['notip_nombre'];?></td>
            <?php }?>

            <td>-</td>
            <td>-</td>
        </tr>
    </tfoot>

</table>
<p>&nbsp;</p>	

<?php
$estadoAgno = '';
if($periodoActual==$datosUsr['gra_periodos']){
	if($materiasPerdidas==0){$estadoAgno = 'PROMOVIDO';}
	elseif($materiasPerdidas>0 and $materiasPerdidas<$config["conf_num_materias_perder_agno"]){$estadoAgno = 'DEBE NIVELAR';}
	elseif($materiasPerdidas>=$config["conf_num_materias_perder_agno"]){$estadoAgno = 'NO FUE PROMOVIDO';}
}
?>
	
<table width="100%" cellspacing="5" cellpadding="5" rules="none" border="0">
	<tr>
        <td width="40%">
            ________________________________________________________________<br>
            DIRECTOR DE GRADO
        </td>
        <td width="20%">
        	<table width="100%" cellspacing="5" cellpadding="5" rules="all" border="1">
            	<?php
				$contador=1;
				$estilosNota = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_notas_tipos 
				WHERE notip_categoria='".$config["conf_notas_categoria"]."' AND institucion={$config['conf_id_institucion']} AND year={$year}
				ORDER BY notip_desde DESC");
				while($eN = mysqli_fetch_array($estilosNota, MYSQLI_BOTH)){
					if($contador%2==1){$fondoFila = '#EAEAEA';}else{$fondoFila = '#FFF';}
				?>
                <tr style="background:<?=$fondoFila;?>">
                	<td><?=$eN['notip_nombre'];?></td>
                    <td align="center"><?=$eN['notip_desde']." - ".$eN['notip_hasta'];?></td>
                </tr>
                <?php $contador++;}?>
            </table>
        </td>
        <td width="60%">
        	<p style="font-weight:bold;">Observaciones: <?=$estadoAgno;?></p>
            ______________________________________________________________________<br><br>
            ______________________________________________________________________<br><br>
            ______________________________________________________________________
        </td>
    </tr>
</table>

<div id="saltoPagina"></div>
                                   
<?php
}// FIN DE TODOS LOS MATRICULADOS
?>

<!--
<script type="application/javascript">
print();
</script>   
-->                                 
                          
</body>
</html>
