<?php
session_start();
include("../../config-general/config.php");
include("../../config-general/consulta-usuario-actual.php");
include("../class/Estudiantes.php");
?>

<head>
    <title>SINTIA | Defintivas del año</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="shortcut icon" href="<?=$Plataforma->logo;?>">
</head>

<body style="font-family:Arial;">
<?php
$nombreInforme = "ESTUDIANTES CON ASIGNATURAS PERDIDAS";
include("../compartido/head-informes.php") ?>

<table width="100%" cellspacing="5" cellpadding="5" rules="all" 
  style="
  border:solid; 
  border-color:<?=$Plataforma->colorUno;?>; 
  font-size:11px;
  ">

        <tr style="font-weight:bold; height:30px; background:<?=$Plataforma->colorUno;?>; color:#FFF;">
            <th style="font-size:9px;">Mat</th>
            <th style="font-size:9px;">Estudiante</th>
            <?php
			if(isset($_GET["curso"])){$curso=$_GET["curso"]; $grupo=$_GET["grupo"];}
			if(isset($_POST["grado"])){$curso=$_POST["grado"]; $grupo=$_POST["grupo"];}
			$cargas = mysqli_query($conexion, "SELECT * FROM academico_cargas WHERE car_curso='".$curso."' AND car_grupo='".$grupo."' AND car_activa=1");
			//SACAMOS EL NUMERO DE CARGAS O MATERIAS QUE TIENE UN CURSO PARA QUE SIRVA DE DIVISOR EN LA DEFINITIVA POR ESTUDIANTE
			$numCargasPorCurso = mysqli_num_rows($cargas); 
			while($carga = mysqli_fetch_array($cargas, MYSQLI_BOTH)){
				$consultaMaterias=mysqli_query($conexion, "SELECT * FROM academico_materias WHERE mat_id='".$carga[4]."'");
				$materia = mysqli_fetch_array($consultaMaterias, MYSQLI_BOTH);
			?>
            <th style="font-size:9px; text-align:center; border:groove;" width="5%"><?=$materia[2];?></th>
            <?php
			}
			?>
            <th style="text-align:center;">PROM</th>
            <th style="text-align:center;">#MP</th>
        </tr>
        <?php
		$filtroAdicional= "AND mat_grado='".$curso."' AND mat_grupo='".$grupo."' AND (mat_estado_matricula=1 OR mat_estado_matricula=2)";
		$consulta =Estudiantes::listarEstudiantesEnGrados($filtroAdicional,"");
		while($resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH)){
		$nombreCompleto =Estudiantes::NombreCompletoDelEstudiante($resultado);
		$defPorEstudiante = 0;
		$materiasPerdidas = 0;	 
		?>
        <tr style="border-color:<?=$Plataforma->colorDos;?>;">
            <td style="font-size:9px;"><?=$resultado[1];?></td>
            <td style="font-size:9px;"><?=$nombreCompleto?></td>
            <?php
			$cargas = mysqli_query($conexion, "SELECT * FROM academico_cargas WHERE car_curso='".$curso."' AND car_grupo='".$grupo."' AND car_activa=1"); 
			while($carga = mysqli_fetch_array($cargas, MYSQLI_BOTH)){
				//PRUEBA CONSULTA PHP 8
				$consultaMaterias= mysqli_query($conexion, "SELECT * FROM academico_materias WHERE mat_id='".$carga[4]."'");
				$materia = mysqli_fetch_array($consultaMaterias, MYSQLI_BOTH);
				$p = 1;
				$porcPeriodo = array("",0.25,0.25,0.25,0.25);
				$defPorMateria = 0;
				//PERIODOS DE CADA MATERIA
				while($p<=$config[19]){
					$consultaBoletin=mysqli_query($conexion, "SELECT * FROM academico_boletin WHERE bol_carga='".$carga[0]."' AND bol_estudiante='".$resultado[0]."' AND bol_periodo='".$p."'");
					$boletin = mysqli_fetch_array($consultaBoletin, MYSQLI_BOTH);
					if($boletin[4]<$config[5] and $boletin[4]!=""){$color = $config[6];} elseif($boletin[4]>=$config[5]) {$color = $config[7];}
					//$defPorMateria += $boletin[4];
					$defPorMateria += ($boletin[4]*$porcPeriodo[$p]);
					//DEFINITIVA DE CADA PERIODO
					$p++;
				}
				//$defPorMateria = round($defPorMateria/$config[19],2);
				$defPorMateria = round($defPorMateria,2);
					//DEFINITIVA DE CADA MATERIA
					if($defPorMateria<$config[5] and $defPorMateria!=""){$color = $config[6]; $fondoColor = '#FFC'; $materiasPerdidas++;} elseif($defPorMateria>=$config[5]) {$color = $config[7]; $fondoColor = '#FFF';}
				?>
            <td style="text-align:center; background:<?=$fondoColor;?>; color:<?=$color;?>; text-decoration:underline;">
                <?=$defPorMateria;?></td>
            <?php
				//DEFINITIVA POR CADA ESTUDIANTE DE TODAS LAS MATERIAS Y PERIODOS
				$defPorEstudiante += $defPorMateria;   
			}
				$defPorEstudiante = round($defPorEstudiante/$numCargasPorCurso,2);
				
				if($defPorEstudiante<$config[5] and $defPorEstudiante!="")$color = $config[6]; elseif($defPorEstudiante>=$config[5]) $color = $config[7];
			?>
            <td style="text-align:center; width:40px; font-weight:bold; color:<?=$color;?>"><?=$defPorEstudiante;?></td>
            <td style="text-align:center; width:40px; font-weight:bold; background-color: gainsboro;">
                <?=$materiasPerdidas;?></td>
        </tr>
        <?php }?>
    </table>
    </center>
	<?php include("../compartido/footer-informes.php") ?>;		
</body>

</html>