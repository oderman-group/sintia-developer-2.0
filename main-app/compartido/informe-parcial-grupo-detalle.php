<?php
include("session-compartida.php");
$idPaginaInterna = 'DT0229';

if($datosUsuarioActual['uss_tipo'] == TIPO_DIRECTIVO && !Modulos::validarSubRol([$idPaginaInterna])){
	echo '<script type="text/javascript">window.location.href="../directivo/page-info.php?idmsg=301";</script>';
	exit();
}
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");
require_once(ROOT_PATH."/main-app/class/Estudiantes.php");
require_once(ROOT_PATH."/main-app/class/servicios/GradoServicios.php");
require_once(ROOT_PATH."/main-app/class/Boletin.php");
require_once(ROOT_PATH."/main-app/class/CargaAcademica.php");
?>
<head>
	<title>SINTIA - INFORME PARCIAL</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="shortcut icon" href="<?=$Plataforma->logo;?>">
</head>
<body style="font-family:Arial;">
<?php
$nombreInforme =  "INFORME PARCIAL "."<br>"." PERIODO:".Utilidades::getToString($config[2])."<br>".Utilidades::getToString($config["conf_fecha_parcial"]);
include("../compartido/head-informes.php") ?>


<?php
$filtroAdicional= "AND mat_grado='".$_REQUEST["curso"]."' AND mat_grupo='".$_REQUEST["grupo"]."' AND (mat_estado_matricula=1 OR mat_estado_matricula=2)";
$cursoActual=GradoServicios::consultarCurso($_REQUEST["curso"]);
$matriculadosPorCurso =Estudiantes::listarEstudiantesEnGrados($filtroAdicional,"",$cursoActual,$_REQUEST["grupo"]);

while($matriculadosDatos = mysqli_fetch_array($matriculadosPorCurso, MYSQLI_BOTH)){
	$nombre = Estudiantes::NombreCompletoDelEstudiante($matriculadosDatos);

  $filtroOR='';
  if($cursoActual["gra_tipo"]==GRADO_INDIVIDUAL){
    $filtroOR=" OR (car_curso='".$matriculadosDatos['matcur_id_curso']."' AND car_grupo='".$matriculadosDatos['matcur_id_grupo']."')";
  }
?>
<div align="center" style="margin-bottom:20px;">
    ESTUDIANTE: <?=$nombre;?></br>
</div>  



                                  
                                  <!-- BEGIN TABLE DATA -->
                                  <table width="100%" cellspacing="2" cellpadding="2" rules="all" style="border:solid; border-color:<?=$Plataforma->colorUno;?>; font-size:10px;" >
                                      <tr style="font-weight:bold; height:30px; background:<?=$Plataforma->colorUno;?>; color:#FFF;">
                                        <th style="text-align:center;">Cod</th>
                                        <th style="text-align:center;">Docente</th>
                                        <th style="text-align:center;">Asignatura</th>
                                        <th style="text-align:center;">%</th>
                                        <th style="text-align:center;">Nota</th>
                                      </tr>
                                    <!-- END -->
                                    <!-- BEGIN -->
                                    <tbody>
                                    <?php
                  $cCargas = CargaAcademica::datosRelacionadosCargaPorCursoGrupo($config, $matriculadosDatos['mat_grado'], $matriculadosDatos['mat_grupo'], $filtroOR);
									$nCargas = mysqli_num_rows($cCargas);
									$materiasDividir = 0;
									$promedioG = 0;
									while($rCargas = mysqli_fetch_array($cCargas, MYSQLI_BOTH)){
										//DEFINITIVAS
										$carga = $rCargas['car_id'];
										$periodo = $config[2];
										$estudiante = $matriculadosDatos['mat_id'];
										include("../definitivas.php");
										if($definitiva>=$config[5] or $porcentajeActual==0) continue;
										//SOLO SE CUENTAN LAS MATERIAS QUE TIENEN NOTAS.
										if($porcentajeActual>0){$materiasDividir++;}

                    $definitivaFinal=$definitiva;
                    if($config['conf_forma_mostrar_notas'] == CUALITATIVA){
                      $estiloNota = Boletin::obtenerDatosTipoDeNotas($config['conf_notas_categoria'], $definitiva);
                      $definitivaFinal= !empty($estiloNota['notip_nombre']) ? $estiloNota['notip_nombre'] : "";
                    }
									?>
                                    <tr id="data1" class="odd gradeX">
                                        <td style="text-align:center;"><?=$rCargas['car_id'];?></td>
                                        <td><?=UsuariosPadre::nombreCompletoDelUsuario($rCargas);?></td>
                                        <td><?=$rCargas['mat_nombre'];?></td>
                                        <td style="text-align:center;"><?=$porcentajeActual;?>%</td>
                                        <td style="color:<?=$colorDefinitiva;?>; text-align:center; font-weight:bold;"><?=$definitivaFinal;?></td>
                                      </tr>
                                   <?php 
								   		$promedioG += $definitiva;
								   }
								   		if($nn>0 and $materiasDividir>0){
											$promedioG = round(($promedioG / $materiasDividir),1);
										}	
								   ?>   
                                    </tbody>
                                  </table>
<?php 
}
include("../compartido/footer-informes.php");
include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php"); ?>	
</body>

</html>
