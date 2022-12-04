<?php include("../modelo/conexion.php");?>
<?php include("../../../config-general/config.php");?>
<?php
$modulo = 1;
if($_GET["periodo"]==""){
	$periodoActual = 1;
}else{
	$periodoActual = $_GET["periodo"];
}

if($periodoActual==1) $periodoActuales = "Primero";
if($periodoActual==2) $periodoActuales = "Segundo";
if($periodoActual==3) $periodoActuales = "Tercero";
if($periodoActual==4) $periodoActuales = "Final";
//CONSULTA ESTUDIANTES MATRICULADOS
$matriculadosPorCurso = mysql_query("SELECT * FROM academico_matriculas 
INNER JOIN academico_grados ON gra_id=mat_grado
INNER JOIN academico_grupos ON gru_id=mat_grupo
INNER JOIN academico_cargas ON car_curso=mat_grado AND car_director_grupo=1
INNER JOIN usuarios ON uss_id=car_docente
WHERE mat_grado='".$_REQUEST["curso"]."' AND mat_eliminado=0 ORDER BY mat_grupo, mat_primer_apellido LIMIT 0,3",$conexion);
while($matriculadosDatos = mysql_fetch_array($matriculadosPorCurso)){
	//contadores
	$contador_periodos = 0;
	$contador_indicadores = 0;
	$materiasPerdidas = 0;
	if($matriculadosDatos[0]==""){?>
		<script type="text/javascript">window.close();</script>
	<?php
		exit();
	}	
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
    <div style="float:left; width:50%"><img src="../files/images/logo/<?=$informacion_inst["info_logo"]?>" width="350"></div>
    <div style="float:right; width:50%">
        <table width="100%" cellspacing="5" cellpadding="5" border="1" rules="all">
            <tr>
                <td>C&oacute;digo:<br> <?=number_format($matriculadosDatos["mat_documento"],0,",",".");?></td>
                <td>Nombre:<br> <?=strtoupper($matriculadosDatos[3]." ".$matriculadosDatos[4]." ".$matriculadosDatos["mat_nombres"]);?></td>
                <td>Grado:<br> <?=$matriculadosDatos["gra_nombre"]." ".$matriculadosDatos["gru_nombre"];?></td>
                <td>Puesto Curso:<br> 10</td>   
            </tr>
            
            <tr>
                <td>Jornada:<br> Mañana</td>
                <td>Sede:<br> <?=$informacion_inst["info_nombre"]?></td>
                <td>Periodo:<br> <b><?=$config["conf_periodo"]." (".date("Y").")";?></b></td>
                <td>Puesto Colegio:<br> 110</td>   
            </tr>
        </table>
        <p>&nbsp;</p>
    </div>
</div>

<table width="100%" cellspacing="5" cellpadding="5" rules="all" border="1">
    <thead>
        <tr style="font-weight:bold; text-align:center;">
            <td width="20%" rowspan="2">ASIGNATURAS</td>
            <td width="2%" rowspan="2">I.H.</td>
            
            <?php  for($j=1;$j<=$config["conf_periodo"];$j++){ ?>
                <td width="3%" colspan="3"><a href="<?=$_SERVER['PHP_SELF'];?>?id=<?=$matriculadosDatos[0];?>&periodo=<?=$j?>" style="color:#000; text-decoration:none;">Periodo <?=$j?></a></td>
            <?php }?>
            <td width="3%" colspan="3">Final</td>
        </tr> 
        
        <tr style="font-weight:bold; text-align:center;">
            <?php  for($j=1;$j<=$config["conf_periodo"];$j++){ ?>
                <td width="3%">Fallas</td>
                <td width="3%">Nota</td>
                <td width="3%">Nivel</td>
            <?php }?>
            <td width="3%">Nota</td>
            <td width="3%">Nivel</td>
            <td width="3%">Hab</td>
        </tr>
        
    </thead>
    
    <?php
	$contador=1;
	$conCargas = mysql_query("SELECT * FROM academico_cargas
	INNER JOIN academico_materias ON mat_id=car_materia
	WHERE car_curso='".$matriculadosDatos['mat_grado']."' AND car_grupo='".$matriculadosDatos['mat_grupo']."'",$conexion);
	while($datosCargas = mysql_fetch_array($conCargas)){
		if($contador%2==1){$fondoFila = '#EAEAEA';}else{$fondoFila = '#FFF';}
	?>
    <tbody>
        <tr style="background:<?=$fondoFila;?>">
            <td><?=$datosCargas['mat_nombre'];?></td>
            <td align="center"><?=$datosCargas['car_ih'];?></td> 
            <?php 
			$promedioMateria = 0;
			for($j=1;$j<=$config["conf_periodo"];$j++){
                $datosBoletin = mysql_fetch_array(mysql_query("SELECT * FROM academico_boletin 
                INNER JOIN academico_notas_tipos ON notip_categoria='".$config["conf_notas_categoria"]."' AND bol_nota>=notip_desde AND bol_nota<=notip_hasta
                WHERE bol_carga='".$datosCargas['car_id']."' AND bol_estudiante='".$matriculadosDatos['mat_id']."' AND bol_periodo='".$j."'",$conexion));
				
				$datosAusencias = mysql_fetch_array(mysql_query("SELECT sum(aus_ausencias) FROM academico_clases 
                INNER JOIN academico_ausencias ON aus_id_clase=cls_id AND aus_id_estudiante<='".$matriculadosDatos['mat_id']."'
                WHERE cls_id_carga='".$datosCargas['car_id']."' AND cls_periodo='".$j."'",$conexion));
				
				$promedioMateria +=$datosBoletin['bol_nota'];
            ?>
                <td align="center"><?=$datosAusencias[0];?></td>
                <td align="center"><?=$datosBoletin['bol_nota'];?></td>
                <td align="center"><?=$datosBoletin['notip_nombre'];?></td>
            <?php 
			}
			$promedioMateria = round($promedioMateria/($j-1),2);
			$promediosMateriaEstiloNota = mysql_fetch_array(mysql_query("SELECT * FROM academico_notas_tipos 
				WHERE notip_categoria='".$config["conf_notas_categoria"]."' AND '".$promedioMateria."'>=notip_desde AND '".$promedioMateria."'<=notip_hasta",$conexion));
			?>
            <td align="center"><?=$promedioMateria;?></td>
            <td align="center"><?=$promediosMateriaEstiloNota['notip_nombre'];?></td>
            <td align="center">-</td>
        </tr>
    </tbody>
    <?php 
		$contador++;
	}
	?>
    <tfoot>
    	<tr style="font-weight:bold; text-align:center;">
        	<td style="text-align:left;">PROMEDIO/TOTAL</td>
            <td>-</td> 
            <?php 
            for($j=1;$j<=$config["conf_periodo"];$j++){
				$promediosPeriodos = mysql_fetch_array(mysql_query("SELECT ROUND(AVG(bol_nota),2) as promedio FROM academico_boletin 
                WHERE bol_estudiante='".$matriculadosDatos['mat_id']."' AND bol_periodo='".$j."'",$conexion));
				
				$sumaAusencias = mysql_fetch_array(mysql_query("SELECT sum(aus_ausencias) FROM academico_clases 
                INNER JOIN academico_ausencias ON aus_id_clase=cls_id AND aus_id_estudiante<='".$matriculadosDatos['mat_id']."'
                WHERE cls_periodo='".$j."'",$conexion));
				
				$promediosEstiloNota = mysql_fetch_array(mysql_query("SELECT * FROM academico_notas_tipos 
				WHERE notip_categoria='".$config["conf_notas_categoria"]."' AND '".$promediosPeriodos['promedio']."'>=notip_desde AND '".$promediosPeriodos['promedio']."'<=notip_hasta",$conexion));
            ?>
                <td><?=$sumaAusencias[0];?></td>
                <td><?=$promediosPeriodos['promedio'];?></td>
                <td><?=$promediosEstiloNota['notip_nombre'];?></td>
            <?php }?>
            <td>-</td>
            <td>-</td>
            <td>-</td>
        </tr>
    </tfoot>
</table>
<p>&nbsp;</p>

<table width="100%" cellspacing="5" cellpadding="5" rules="none" border="0">
	<tr>
        <td width="40%">
            ________________________________________________________________<br>
            <?=strtoupper($matriculadosDatos['uss_nombre']);?><br>
            DIRECTOR DE CURSO
        </td>
        <td width="20%">
        	<table width="100%" cellspacing="5" cellpadding="5" rules="all" border="1">
            	<?php
				$contador=1;
				$estilosNota = mysql_query("SELECT * FROM academico_notas_tipos 
				WHERE notip_categoria='".$config["conf_notas_categoria"]."'
				ORDER BY notip_desde DESC",$conexion);
				while($eN = mysql_fetch_array($estilosNota)){
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
        	<p style="font-weight:bold;">Observaciones: PROMOVIDO</p>
            ______________________________________________________________________<br><br>
            ______________________________________________________________________<br><br>
            ______________________________________________________________________
        </td>
    </tr>
</table>

<div id="saltoPagina"></div>

<table width="100%" cellspacing="5" cellpadding="5" rules="all" border="1">
    <thead>
        <tr style="font-weight:bold; text-align:center;">
            <td width="30%">Asignaturas</td>
            <td width="70%">Contenidos Evaluados</td>
        </tr>     
    </thead>
    
    <?php
	$conCargas = mysql_query("SELECT * FROM academico_cargas
	INNER JOIN academico_materias ON mat_id=car_materia
	INNER JOIN usuarios ON uss_id=car_docente
	WHERE car_curso='".$matriculadosDatos['mat_grado']."' AND car_grupo='".$matriculadosDatos['mat_grupo']."'",$conexion);
	while($datosCargas = mysql_fetch_array($conCargas)){
	?>
    <tbody>
        <tr style="color:#585858;">
            <td><?=$datosCargas['mat_nombre'];?><br><span style="color:#C1C1C1;"><?=$datosCargas['uss_nombre'];?></span></td>
            <td>Comprende la importancia de lograr las metas propuestas. Clasifica las etapas de un noviazgo saludable. Reconoce la importancia de una sexualidad pura.
Conoce el buen desempeño de trabajar en equipo. Elije mantener buenas relaciones interpersonales</td> 
        </tr>
    </tbody>
    <?php 
	}
	?>
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
