<?php
session_start();
include("../../config-general/config.php");
include("../../config-general/consulta-usuario-actual.php");
require_once("../class/Estudiantes.php");

$cursoV = '';
$grupoV = '';
if (!empty($_GET["curso"])) {
	$cursoV = base64_decode($_GET['curso']);
	$grupoV = base64_decode($_GET['grupo']);
}elseif(!empty($_POST["curso"])) {
	$cursoV = $_POST['curso'];
	$grupoV = $_POST['grupo'];
}
require_once("../class/servicios/GradoServicios.php");
?>
<head>
	<title>SINTIA | Consolidado Final</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="shortcut icon" href="../files/images/ico.png">
</head>
<body style="font-family:Arial;">
<?php
$nombreInforme = "INFORME NIVELACIONES";
include("../compartido/head-informes.php") ?>  
  <table  width="100%" cellspacing="5" cellpadding="5" rules="all" 
  style="
  border:solid; 
  border-color:<?=$Plataforma->colorUno;?>; 
  font-size:11px;
  " align="center">
<tr style="font-weight:bold; height:30px; background:<?=$Plataforma->colorUno;?>; color:#FFF;">
                                        <th rowspan="2" style="font-size:9px;">Mat</th>
                                        <th rowspan="2" style="font-size:9px;">Estudiante</th>
                                        <?php
										$cargas = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_cargas WHERE car_curso='".$cursoV."' AND car_grupo='".$grupoV."' AND car_activa=1 AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
										//SACAMOS EL NUMERO DE CARGAS O MATERIAS QUE TIENE UN CURSO PARA QUE SIRVA DE DIVISOR EN LA DEFINITIVA POR ESTUDIANTE
										$numCargasPorCurso = mysqli_num_rows($cargas); 
										while($carga = mysqli_fetch_array($cargas, MYSQLI_BOTH)){
											$consultaMateria=mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_materias WHERE mat_id='".$carga['car_materia']."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
											$materia = mysqli_fetch_array($consultaMateria, MYSQLI_BOTH);
										?>
                                        	<th style="font-size:9px; text-align:center; border:groove;" colspan="3" width="5%"><?=$materia['mat_nombre'];?></th>
                                        <?php
										}
										?>
                                        <th rowspan="2" style="text-align:center;">PROM</th>
                                        </tr>
                                        
                                        <tr style="font-weight:bold; font-size:12px;">
											<?php
                                            $cargas = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_cargas WHERE car_curso='".$cursoV."' AND car_grupo='".$grupoV."' AND car_activa=1 AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}"); 
                                            while($carga = mysqli_fetch_array($cargas, MYSQLI_BOTH)){
											?>	
                                               <th style="text-align:center;">DEF</th>
                                               <th style="text-align:center;">Acta</th>
                                               <th style="text-align:center;">Fecha</th>
                                           	<?php
                                            }
									
									$filtroAdicional = "";
									if(!empty($_REQUEST["curso"]) and !empty($_REQUEST["grupo"])){
										$filtroAdicional= "AND mat_grado='".$cursoV."' AND mat_grupo='".$grupoV."' AND (mat_estado_matricula=1 OR mat_estado_matricula=2)";
									}
									$cursoActual=GradoServicios::consultarCurso($cursoV);
									$consulta =Estudiantes::listarEstudiantesEnGrados($filtroAdicional,"",$cursoActual);
									 while($resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH)){
									$nombreCompleto =Estudiantes::NombreCompletoDelEstudiante($resultado);
									 $defPorEstudiante = 0;
									 ?>
                                      <tr style="border-color:<?=$Plataforma->colorDos;?>;">
                                        <td style="font-size:9px;"><?=$resultado['mat_matricula'];?></td>
                                        <td style="font-size:9px;"><?=$nombreCompleto?></td>
                                        <?php
										$cargas = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_cargas WHERE car_curso='".$cursoV."' AND car_grupo='".$grupoV."' AND car_activa=1 AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}"); 
										while($carga = mysqli_fetch_array($cargas, MYSQLI_BOTH)){
											$consultaMateria=mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_materias WHERE mat_id='".$carga['car_materia']."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
											$materia = mysqli_fetch_array($consultaMateria, MYSQLI_BOTH);
											$p = 1;
                                            $defPorMateria = 0;
											//PERIODOS DE CADA MATERIA
											while($p<=$config[19]){
												$consultaBoletin=mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_boletin WHERE bol_carga='".$carga['car_id']."' AND bol_estudiante='".$resultado['mat_id']."' AND bol_periodo='".$p."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
												$boletin = mysqli_fetch_array($consultaBoletin, MYSQLI_BOTH);
												if (!empty($boletin['bol_nota'])) {
													if ($boletin['bol_nota'] < $config[5]) $color = $config[6];
													elseif ($boletin['bol_nota'] >= $config[5]) $color = $config[7];
													$defPorMateria += $boletin['bol_nota'];
												}
												$p++;
                                            }
											$defPorMateria = round($defPorMateria/$config[19],2);
											//CONSULTAR NIVELACIONES
											$consultaNiv=mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_nivelaciones WHERE niv_cod_estudiante='".$resultado['mat_id']."' AND niv_id_asg='".$carga['car_id']."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
											$cNiv = mysqli_fetch_array($consultaNiv, MYSQLI_BOTH);
											if (!empty($cNiv['niv_definitiva']) && $cNiv['niv_definitiva'] > $defPorMateria) {
												$defPorMateria = $cNiv['niv_definitiva'];
												$msj = 'Nivelación';
											} else {
												$defPorMateria = $defPorMateria;
												$msj = '';
											}
											//DEFINITIVA DE CADA MATERIA
											if($defPorMateria<$config[5] and $defPorMateria!="")$color = $config[6]; elseif($defPorMateria>=$config[5]) $color = $config[7];
											?>
                                            	<td style="text-align:center; background:#FFC; color:<?=$color;?>;"><?=$defPorMateria;?><br><span style="font-size:10px; color:rgb(255,0,0);"><?=$msj;?></span></td>
                                                <td style="text-align:center;"><?php if (!empty($cNiv['niv_acta'])) echo $cNiv['niv_acta']; ?></td>
                                                <td style="text-align:center;"><?php if (!empty($cNiv['niv_fecha_nivelacion'])) echo $cNiv['niv_fecha_nivelacion']; ?></td>
                                        <?php
											//DEFINITIVA POR CADA ESTUDIANTE DE TODAS LAS MATERIAS Y PERIODOS
											$defPorEstudiante += $defPorMateria;   
										}
											$defPorEstudiante = round($defPorEstudiante/$numCargasPorCurso,2);
											if($defPorEstudiante<$config[5] and $defPorEstudiante!="")$color = $config[6]; elseif($defPorEstudiante>=$config[5]) $color = $config[7];
										?>
                                        	<td style="text-align:center; width:40px; font-weight:bold; color:<?=$color;?>"><?=$defPorEstudiante;?></td>
                                      </tr>
                                      <?php 
									  }
									  ?>
  </table>
  </center>
  <?php include("../compartido/footer-informes.php") ?>;
</body>
</html>