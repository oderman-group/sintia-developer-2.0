<?php
include("session.php");
$idPaginaInterna = 'DC0067';
include("../compartido/historial-acciones-guardar.php");
include("verificar-carga.php");
include("../compartido/head.php");
require_once("../class/Estudiantes.php");

$consultaValores=mysqli_query($conexion, "SELECT
(SELECT sum(act_valor) FROM academico_actividades 
WHERE act_id_carga='".$cargaConsultaActual."' AND act_periodo='".$periodoConsultaActual."' AND act_estado=1),
(SELECT count(*) FROM academico_actividades 
WHERE act_id_carga='".$cargaConsultaActual."' AND act_periodo='".$periodoConsultaActual."' AND act_estado=1)
");
$valores = mysqli_fetch_array($consultaValores, MYSQLI_BOTH);
$porcentajeRestante = 100 - $valores[0];
include("../compartido/sintia-funciones-js.php");
?>
	<title>Resumen de notas</title>
<script type="application/javascript">
//CALIFICACIONES	
function notas(enviada){
	
  const idSplit = enviada.id.split('-');
  var codNota = enviada.name;	 
  var nota = enviada.value;
  var codEst = idSplit[0];
  var nombreEst = enviada.alt;
  var operacion = enviada.title;
  var notaAnterior = enviada.step;
 
if(operacion == 1 || operacion == 3){
	var flag = enviada.alt;
	if (alertValidarNota(nota)) {
		return false;
	}
}

if(operacion == 1) {
	aplicarColorNota(nota, enviada.id);
}

	  
$('#respRCT').empty().hide().html("Guardando información, espere por favor...").show(1);
	datos = "nota="+(nota)+
			"&codNota="+(codNota)+
			"&operacion="+(operacion)+
			"&nombreEst="+(nombreEst)+
			"&notaAnterior="+(notaAnterior)+
			"&flag="+(flag)+
			"&codEst="+(codEst);
		   $.ajax({
			   type: "POST",
			   url: "ajax-calificaciones-registrar.php",
			   data: datos,
			   success: function(data){
			   	$('#respRCT').empty().hide().html(data).show(1);
		   	   }
		  });
}
</script>


<style type="text/css">
body {
  margin: 0;
  padding: 2rem;
	font-family: Arial;
}

table {
  text-align: left;
  position: relative;
  border-collapse: collapse; 
}
th, td {
  padding: 0.25rem;
}

th {
  background-color:lightgrey;
  position: sticky;
  top: 0;
  box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4);
}

</style>

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
	
</head>
<!-- END HEAD -->

<body>
<?php include("../compartido/texto-manual-ayuda.php");?>
                                            <span id="respRCT"></span>
		
											<p>
												<a href="calificaciones.php?tab=2" type="button" class="btn btn-primary">Regresar</a>
												<!--
												<a href="calificaciones-agregar.php?carga=<?=$cargaConsultaActual;?>&periodo=<?=$periodoConsultaActual;?>" type="button" class="btn btn-danger">Agregar nuevo</a>
												-->
											</p>
	
											<?php 
											//Verificar si el periodo es anterior para que no modifique notas.
											$habilitado = 'disabled';
											$deleteOculto = 'style="display:none;"';
											if($periodoConsultaActual==$datosCargaActual['car_periodo'] or $datosCargaActual['car_permiso2']==1){
												$habilitado = '';
												$deleteOculto = 'style="display:block;"';
											}
											?>
	
											<table width="100%" border="1" rules="rows">
                                                <thead>
												  <tr>
													<th style="width: 50px;">#</th>
													<th style="width: 400px;"><?=$frases[61][$datosUsuarioActual[8]];?></th>
													<?php
													 $cA = mysqli_query($conexion, "SELECT * FROM academico_actividades WHERE act_id_carga='".$cargaConsultaActual."' AND act_estado=1 AND act_periodo='".$periodoConsultaActual."'");
													 while($rA = mysqli_fetch_array($cA, MYSQLI_BOTH)){
														echo '<th style="text-align:center; font-size:11px; width:100px;"><a href="calificaciones-editar.php?idR='.base64_encode($rA[0]).'" title="'.$rA[1].'">'.$rA[0].'<br>
														'.$rA[1].'<br>
														('.$rA[3].'%)</a><br>
														<a href="#" name="guardar.php?get='.base64_encode(12).'&idR='.base64_encode($rA[0]).'&idIndicador='.base64_encode($rA['act_id_tipo']).'&carga='.base64_encode($cargaConsultaActual).'&periodo='.base64_encode($periodoConsultaActual).'" onClick="deseaEliminar(this)" '.$deleteOculto.'><i class="fa fa-times"></i></a><br>
														<input type="text" style="text-align: center; font-weight: bold;" maxlength="3" size="10" title="3" name="'.$rA[0].'" alt="1" onChange="notas(this)" '.$habilitado.'>
														</th>';
													 }
													?>
													<th style="text-align:center; width:60px;">%</th>
													<th style="text-align:center; width:60px;"><?=$frases[118][$datosUsuarioActual[8]];?></th>
												  </tr>
												</thead>
                                                <tbody>
													<?php
													$contReg = 1; 
													$consulta = Estudiantes::escogerConsultaParaListarEstudiantesParaDocentes($datosCargaActual);
													while($resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH)){
														$nombreCompleto =Estudiantes::NombreCompletoDelEstudiante($resultado);
														//DEFINITIVAS
														$carga = $cargaConsultaActual;
														$periodo = $periodoConsultaActual;
														$estudiante = $resultado['mat_id'];
														include("../definitivas.php");
														
														$colorEstudiante = '#000;';
														if($resultado['mat_inclusion']==1){$colorEstudiante = 'blue;';}
														
														$colorFondo = '';
														if(!empty($_GET["idEst"]) && $resultado['mat_id']==$_GET["idEst"]){$colorFondo = 'yellow;';}
													?>
                                                    
													<tr style="background-color: <?=$colorFondo;?>">
                                                        <td style="text-align:center;" style="width: 100px;"><?=$contReg;?></td>
														<td style="color: <?=$colorEstudiante;?>">
														<?=$nombreCompleto?>
														</td>

														<?php
														 $cA = mysqli_query($conexion, "SELECT * FROM academico_actividades WHERE act_id_carga='".$cargaConsultaActual."' AND act_estado=1 AND act_periodo='".$periodoConsultaActual."'");
														 while($rA = mysqli_fetch_array($cA, MYSQLI_BOTH)){
															//LAS CALIFICACIONES
															$consultaNotasResultados=mysqli_query($conexion, "SELECT * FROM academico_calificaciones WHERE cal_id_estudiante='".$resultado['mat_id']."' AND cal_id_actividad=".$rA[0]);
															$notasResultado = mysqli_fetch_array($consultaNotasResultados, MYSQLI_BOTH);
															
															$arrayEnviar = [
																"tipo"=>5, 
																"descripcionTipo"=>"Para ocultar la X y limpiar valor, cuando son diferentes actividades.", 
																"idInput"=>$resultado['mat_id']."-".$rA[0]
															];
															$arrayDatos = json_encode($arrayEnviar);
															$objetoEnviar = htmlentities($arrayDatos);
														?>
															<td style="text-align:center;">
															<input size="5" maxlength="3" name="<?=$rA[0]?>" id="<?=$resultado['mat_id']."-".$rA[0];?>" value="<?php if(!empty($notasResultado[3])){ echo $notasResultado[3];}?>" title="1" alt="<?=$resultado['mat_nombres'];?>" step="<?=$notasResultado[3];?>" onChange="notas(this)" tabindex="2" style="font-size: 13px; text-align: center; color:<?php if($notasResultado[3]<$config[5] and $notasResultado[3]!="")echo $config[6]; elseif($notasResultado[3]>=$config[5]) echo $config[7]; else echo "black";?>;" <?=$habilitado;?>>
															<?php if(!empty($notasResultado[3])){?>
																<a href="#" title="<?=$objetoEnviar;?>" id="<?=$notasResultado['cal_id'];?>" name="guardar.php?get=<?=base64_encode(21);?>&id=<?=base64_encode($notasResultado['cal_id']);?>" onClick="deseaEliminar(this)" <?=$deleteOculto;?>><i class="fa fa-times"></i></a>
																<?php if($notasResultado[3]<$config[5]){?>
																	<br><br><input size="5" maxlength="3" name="<?=$rA[0]?>" id="<?=$resultado['mat_id'];?>" title="4" alt="<?=$resultado['mat_nombres'];?>" step="<?=$notasResultado[3];?>" onChange="notas(this)" tabindex="2" style="font-size: 13px; text-align: center; border-color:tomato;" placeholder="Recup" <?=$habilitado;?>>
																<?php }?>
															<?php }?>

															</td>
														<?php		
														 }
														if($definitiva<$config[5] and $definitiva!="") $colorDef = $config[6]; elseif($definitiva>=$config[5]) $colorDef = $config[7]; else $colorDef = "black";
														?>

														<td style="text-align:center;"><?=$porcentajeActual;?></td>
                                        				<td style="color:<?php if($definitiva<$config[5] and $definitiva!="")echo $config[6]; elseif($definitiva>=$config[5]) echo $config[7]; else echo "black";?>; text-align:center; font-weight:bold;"><a href="calificaciones-estudiante.php?usrEstud=<?=base64_encode($resultado['mat_id_usuario']);?>&periodo=<?=base64_encode($periodoConsultaActual);?>&carga=<?=base64_encode($cargaConsultaActual);?>" style="text-decoration:underline; color:<?=$colorDef;?>;"><?=$definitiva;?></a></td>
                                                    </tr>
													<?php
														$contReg++;
													  }
													  ?>
                                                </tbody>
                                            </table>
											

<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
<!-- notifications -->
<script src="../../config-general/assets/plugins/jquery-toast/dist/jquery.toast.min.js" ></script>
<script src="../../config-general/assets/plugins/jquery-toast/dist/toast.js" ></script>
    <!-- end js include path -->
</body>

</html>