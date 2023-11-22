<?php
session_start();
include("../../config-general/config.php");
include("../../config-general/consulta-usuario-actual.php");?>
<?php
require_once("../class/Estudiantes.php");
require_once("../class/Grados.php");
require_once("../class/Grupos.php");
require_once(ROOT_PATH."/main-app/class/Boletin.php");
$year = $_SESSION["bd"];
$BD   = $_SESSION["inst"]."_".$agnoBD;
$bdConsulta = "";
if(!empty($_REQUEST["agno"])){
	$year = $_REQUEST["agno"];
	$BD   = $_SESSION["inst"]."_".$_REQUEST["agno"];
	$bdConsulta = $BD.".";
}

$cursoV = '';
$grupoV = '';
if (!empty($_GET["curso"])) {
	$cursoV = base64_decode($_GET['curso']);
	$grupoV = base64_decode($_GET['grupo']);
}elseif(!empty($_POST["curso"])) {
	$cursoV = $_POST['curso'];
	$grupoV = $_POST['grupo'];
}

  $consultaCurso = Grados::obtenerDatosGrados($cursoV);
	$curso = mysqli_fetch_array($consultaCurso, MYSQLI_BOTH);
  
  $consultaGrupo = Grupos::obtenerDatosGrupos($grupoV);
	$grupo = mysqli_fetch_array($consultaGrupo, MYSQLI_BOTH);
  ?>
<head>
	<title>SINTIA | Consolidado Final</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="shortcut icon" href="<?=$Plataforma->logo;?>">
</head>
<body style="font-family:Arial;">
<?php
$nombreInforme = "CONSOLIDADO FINAL " .$year."<br>" . "CURSO: " .Utilidades::getToString($curso['gra_nombre']). "<br>" . "GRUPO: ".Utilidades::getToString($grupo['gru_nombre']);
include("../compartido/head-informes.php") ?>


<table width="100%" cellspacing="5" cellpadding="5" rules="all" 
  style="
  border:solid; 
  border-color:<?=$Plataforma->colorUno;?>; 
  font-size:11px;
  ">

<tr style="font-weight:bold; height:30px; background:<?=$Plataforma->colorUno;?>; color:#FFF;">

                                        <th rowspan="2" style="font-size:9px;">Mat</th>
                                        <th rowspan="2" style="font-size:9px;">Estudiante</th>
                                        <?php
										$cargas = mysqli_query($conexion, "SELECT * FROM ".$BD.".academico_cargas WHERE car_curso='".$cursoV."' AND car_grupo='".$grupoV."' AND car_activa=1");
										//SACAMOS EL NUMERO DE CARGAS O MATERIAS QUE TIENE UN CURSO PARA QUE SIRVA DE DIVISOR EN LA DEFINITIVA POR ESTUDIANTE
										$numCargasPorCurso = mysqli_num_rows($cargas); 
										while($carga = mysqli_fetch_array($cargas, MYSQLI_BOTH)){
											$consultaMaterias=mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_materias WHERE mat_id='".$carga[4]."' AND institucion={$config['conf_id_institucion']} AND year={$year}");
											$materia = mysqli_fetch_array($consultaMaterias, MYSQLI_BOTH);
										?>
                                        	<th style="font-size:9px; text-align:center; border:groove;" colspan="<?=$config[19]+1;?>" width="5%"><?php if(!empty($materia['mat_nombre'])){echo $materia['mat_nombre'];}?></th>
                                        <?php
										}
										?>
                                        <th rowspan="2" style="text-align:center;">PROM</th>
                                        </tr>
                                        
                                        <tr>
                                            <?php
                                            $cargas = mysqli_query($conexion, "SELECT * FROM ".$BD.".academico_cargas WHERE car_curso='".$cursoV."' AND car_grupo='".$grupoV."' AND car_activa=1"); 
                                            while($carga = mysqli_fetch_array($cargas, MYSQLI_BOTH)){
                                                $p = 1;
                                                //PERIODOS DE CADA MATERIA
												while($p<=$config[19]){
                                                    echo '<th style="text-align:center;">'.$p.'</th>';
                                                    $p++;
                                                }
												//DEFINITIVA DE CADA MATERIA
												echo '<th style="text-align:center; background:#FFC">DEF</th>';
                                            }
                                            ?>
                                        </tr>
									<?php
									 $filtroAdicional = "";
									 if(!empty($_REQUEST["curso"]) and !empty($_REQUEST["grupo"])){
									 	$filtroAdicional= "AND mat_grado='".$cursoV."' AND mat_grupo='".$grupoV."' AND (mat_estado_matricula=1 OR mat_estado_matricula=2)";
									 }
									 $cursoActual=GradoServicios::consultarCurso($cursoV);
									 $consulta =Estudiantes::listarEstudiantesEnGrados($filtroAdicional,"",$cursoActual,$bdConsulta);
									 while($resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH)){
									 $defPorEstudiante = 0;
									 ?>
									<tr style="border-color:<?=$Plataforma->colorDos;?>;">
										<td style="font-size:9px;"><?=$resultado['mat_matricula'];?></td>
                                        <td style="font-size:9px;"><?=Estudiantes::NombreCompletoDelEstudiante($resultado);?></td>
                                        <?php
										$cargas = mysqli_query($conexion, "SELECT * FROM ".$BD.".academico_cargas WHERE car_curso='".$cursoV."' AND car_grupo='".$grupoV."' AND car_activa=1"); 
										while($carga = mysqli_fetch_array($cargas, MYSQLI_BOTH)){
											$consultaMaterias=mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_materias WHERE mat_id='".$carga[4]."' AND institucion={$config['conf_id_institucion']} AND year={$year}");
											$materia = mysqli_fetch_array($consultaMaterias, MYSQLI_BOTH);
											$p = 1;
                                            $porcPeriodo = array("",0.25,0.15,0.35,0.25);
											$defPorMateria = 0;
											//PERIODOS DE CADA MATERIA
											while($p<=$config[19]){
												$consultaBoletin=mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_boletin WHERE bol_carga='".$carga[0]."' AND bol_estudiante='".$resultado['mat_id']."' AND bol_periodo='".$p."' AND institucion={$config['conf_id_institucion']} AND year={$year}");
												$boletin = mysqli_fetch_array($consultaBoletin, MYSQLI_BOTH);
												if(!empty($boletin['bol_nota']) and $boletin['bol_nota']<$config[5] and $boletin['bol_nota']!="")$color = $config[6]; elseif(!empty($boletin['bol_nota']) and $boletin['bol_nota']>=$config[5]) $color = $config[7];
												
												$notaBoletinFinal="";
												$title='';
												if(!empty($boletin['bol_nota'])){
													$notaBoletinFinal=$boletin['bol_nota'];
													if($config['conf_forma_mostrar_notas'] == CUALITATIVA){
														$title='title="Nota Cuantitativa: '.$boletin['bol_nota'].'"';
														$estiloNota = Boletin::obtenerDatosTipoDeNotas($config['conf_notas_categoria'], $boletin['bol_nota'], $BD);
														$notaBoletinFinal= !empty($estiloNota['notip_nombre']) ? $estiloNota['notip_nombre'] : "";
													}

													$defPorMateria += ($boletin['bol_nota']*$porcPeriodo[$p]);
												}
												//DEFINITIVA DE CADA PERIODO
											?>	
												<td style="text-align:center; color:<?=$color;?>" <?=$title;?>><?=$notaBoletinFinal?></td>
                                            <?php
												$p++;
                                            }
											//$defPorMateria = round($defPorMateria/$config[19],2);
											$defPorMateria = round($defPorMateria,2);
												//DEFINITIVA DE CADA MATERIA
												if($defPorMateria<$config[5] and $defPorMateria!="")$color = $config[6]; elseif($defPorMateria>=$config[5]) $color = $config[7];
												$defPorMateriaFinal=$defPorMateria;
												$title='';
												if($config['conf_forma_mostrar_notas'] == CUALITATIVA){
													$title='title="Nota Cuantitativa: '.$defPorMateria.'"';
													$estiloNota = Boletin::obtenerDatosTipoDeNotas($config['conf_notas_categoria'], $defPorMateria, $BD);
													$defPorMateriaFinal= !empty($estiloNota['notip_nombre']) ? $estiloNota['notip_nombre'] : "";
												}
											?>
                                            	<td style="text-align:center; background:#FFC; color:<?=$color;?>; text-decoration:underline;" <?=$title;?>><?=$defPorMateriaFinal;?></td>
                                        <?php
											//DEFINITIVA POR CADA ESTUDIANTE DE TODAS LAS MATERIAS Y PERIODOS
											$defPorEstudiante += $defPorMateria;   
										}
										if($numCargasPorCurso > 0){
											$defPorEstudiante = round($defPorEstudiante/$numCargasPorCurso,2);
										}	
										
											
											if($defPorEstudiante<$config[5] and $defPorEstudiante!="")$color = $config[6]; elseif($defPorEstudiante>=$config[5]) $color = $config[7];
										?>
                                        	<td style="text-align:center; width:40px; font-weight:bold; color:<?=$color;?>"><?=$defPorEstudiante;?></td>
                                      </tr>
                                      <?php }?>
  </table>
  <?php include("../compartido/footer-informes.php") ?>;
</body>
</html>