<?php
session_start();
include("../../config-general/config.php");
include("../../config-general/consulta-usuario-actual.php");
include("../class/Estudiantes.php");
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
$filtroAdicional= "AND mat_grado='".$_REQUEST["curso"]."' AND mat_grupo='".$_REQUEST["grupo"]."' AND (mat_estado_matricula=1)";
$matriculadosPorCurso =Estudiantes::listarEstudiantesEnGrados($filtroAdicional,"");

while($matriculadosDatos = mysqli_fetch_array($matriculadosPorCurso, MYSQLI_BOTH)){
	$nombre = Estudiantes::NombreCompletoDelEstudiante($matriculadosDatos);	  
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
									$cCargas = mysqli_query($conexion, "SELECT * FROM academico_cargas 
									INNER JOIN academico_materias ON mat_id=car_materia
									INNER JOIN academico_grados ON gra_id=car_curso
									INNER JOIN usuarios ON uss_id=car_docente
									WHERE car_curso='".$matriculadosDatos[6]."' AND car_grupo='".$matriculadosDatos[7]."'");
									$nCargas = mysqli_num_rows($cCargas);
									$materiasDividir = 0;
									$promedioG = 0;
									while($rCargas = mysqli_fetch_array($cCargas, MYSQLI_BOTH)){
										//DEFINITIVAS
										$carga = $rCargas[0];
										$periodo = $config[2];
										$estudiante = $matriculadosDatos[0];
										include("../definitivas.php");
										if($definitiva>=$config[5] or $porcentajeActual==0) continue;
										//SOLO SE CUENTAN LAS MATERIAS QUE TIENEN NOTAS.
										if($porcentajeActual>0){$materiasDividir++;}
									?>
                                    <tr id="data1" class="odd gradeX">
                                        <td style="text-align:center;"><?=$rCargas[0];?></td>
                                        <td><?=$rCargas['uss_nombre'];?></td>
                                        <td><?=$rCargas['mat_nombre'];?></td>
                                        <td style="text-align:center;"><?=$porcentajeActual;?>%</td>
                                        <td style="color:<?=$colorDefinitiva;?>; text-align:center; font-weight:bold;"><?=$definitiva;?></td>
                                      </tr>
                                   <?php 
								   		$promedioG += $definitiva;
								   }
								   		if($nn>0 and $materiasDividir>0){
											$promedioG = round(($promedioG / $materiasDividir),1);
										}	
								   ?>   
                                    </tbody>
                                    <!-- END --
                                     <tfoot>
                                      <tr style="font-weight:bold;">
                                        <td colspan="4" style="text-align:right;">PROMEDIO GENERAL</td>
                                        <td style="text-align:center;"><?php echo $promedioG;?></td>
                                      </tr>
                                    </tfoot>
                                    -->
                                  </table>
                                  
    
<p>&nbsp;</p>                              
<!--                                  
<div style="float:left; margin-left:20px; position:relative; max-width:200px; margin-top:-20px; font-size:12px;" align="center">
_________________________<br>
Coordinador(a) Acad&eacute;mico(a)
</div>

<div style="position:relative; float:right; margin-right:20px; max-width:200px; margin-top:-20px; font-size:12px;" align="center">
_________________________<br>
Director(a) De Grupo
</div>

<div style="position:relative; margin-top:60px; font-size:12px;" align="center">
Yo__________________________________________________________________<br>

Doy constancia de haber recibido del INSTITUTO COLOMBO VENEZOLANO el<br>
informe acad&eacute;mico parcial de mi acudido y a la vez la citaci&oacute;n<br>
respectiva para la reuni&oacute;n en donde se me informar&aacute; las causas y<br>
recomendaciones del bajo demsempe&ntilde;o, establecidas pora la comisi&oacute;n de<br>
evaluaci&oacute;n y promocion.
</div>

<div style="margin-top:10px; position:relative; font-size:12px;" align="center">
_______________________________<br>
Firma Del Padre Y/O Acudiente
</div>

<div align="center" style="margin-top:20px; font-size:12px;">En el Se&ntilde;or, pon tu confianza. Salmos 11:01</div>  
 -->                                 
                                  
                                  
                                    
 <?php }?>
 <?php include("../compartido/footer-informes.php") ?>;	
</body>

</html>
