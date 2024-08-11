<?php
include("session.php");
$idPaginaInterna = 'DC0067';
include("../compartido/historial-acciones-guardar.php");
include("verificar-carga.php");
include("../compartido/head.php");
require_once("../class/Estudiantes.php");
require_once(ROOT_PATH."/main-app/class/Boletin.php");
require_once(ROOT_PATH."/main-app/class/Actividades.php");
require_once(ROOT_PATH."/main-app/class/Calificaciones.php");

$valores = Actividades::consultarValores($config, $cargaConsultaActual, $periodoConsultaActual);
$porcentajeRestante = 100 - $valores[0];
include("../compartido/sintia-funciones-js.php");
?>
	<title>Resumen de notas</title>


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
	
											<table width="100%" border="1" rules="rows" id="tabla_notas">
                                                <thead>
												  <tr>
													<th style="width: 50px;">#</th>
													<th style="width: 400px;"><?=$frases[61][$datosUsuarioActual['uss_idioma']];?></th>
													<?php
													$cA = Actividades::traerActividadesCarga($config, $cargaConsultaActual, $periodoConsultaActual);
													 while($rA = mysqli_fetch_array($cA, MYSQLI_BOTH)){
														echo '<th style="text-align:center; font-size:11px; width:100px;"><a href="calificaciones-editar.php?idR='.base64_encode($rA['act_id']).'" title="'.$rA['act_descripcion'].'">'.$rA['act_id'].'<br>
														'.$rA['act_descripcion'].'<br>
														('.$rA['act_valor'].'%)</a><br>
														<a href="#" name="calificaciones-eliminar.php?idR='.base64_encode($rA['act_id']).'&idIndicador='.base64_encode($rA['act_id_tipo']).'&carga='.base64_encode($cargaConsultaActual).'&periodo='.base64_encode($periodoConsultaActual).'" onClick="deseaEliminar(this)" '.$deleteOculto.'><i class="fa fa-times"></i></a><br>
														<input 
															type="text" 
															style="text-align: center; font-weight: bold;"
															size="10" 
															title="0" 
															name="'.$rA['act_id'].'" 
															onChange="notasMasiva(this)" 
														'.$habilitado.'
														>
														</th>';
													 }
													?>
													<th style="text-align:center; width:60px;">%</th>
													<th style="text-align:center; width:60px;"><?=$frases[118][$datosUsuarioActual['uss_idioma']];?></th>
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
                                                    
													<tr style="background-color: <?=$colorFondo;?>" id="fila_<?=$resultado['mat_id'];?>">
                                                        <td style="text-align:center;" style="width: 100px;"><?=$contReg;?></td>
														<td style="color: <?=$colorEstudiante;?>">
														<?=$nombreCompleto?>
														</td>

														<?php
														$cA = Actividades::traerActividadesCarga($config, $cargaConsultaActual, $periodoConsultaActual);
														 while($rA = mysqli_fetch_array($cA, MYSQLI_BOTH)){
															//LAS CALIFICACIONES
															$notasResultado = Calificaciones::traerCalificacionActividadEstudiante($config, $rA['act_id'], $resultado['mat_id']);
															
															$arrayEnviar = [
																"tipo"=>5, 
																"descripcionTipo"=>"Para ocultar la X y limpiar valor, cuando son diferentes actividades.", 
																"idInput"=>$resultado['mat_id']."-".$rA['act_id']
															];
															$arrayDatos = json_encode($arrayEnviar);
															$objetoEnviar = htmlentities($arrayDatos);

															if(!empty($notasResultado) && $notasResultado['cal_nota']<$config[5]) $colorNota= $config[6]; elseif(!empty($notasResultado) && $notasResultado['cal_nota']>=$config[5]) $colorNota= $config[7]; else $colorNota= "black";
                        
															$estiloNotaFinal="";
															if(!empty($notasResultado) && $config['conf_forma_mostrar_notas'] == CUALITATIVA){		
																$estiloNota = Boletin::obtenerDatosTipoDeNotas($config['conf_notas_categoria'], $notasResultado['cal_nota']);
																$estiloNotaFinal= !empty($estiloNota['notip_nombre']) ? $estiloNota['notip_nombre'] : "";
															}
														?>

															<?php include("td-calificaciones.php");?>

														<?php		
														}

														include("td-porcentaje-definitiva.php");
														?>

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
<script src="../../config-general/assets/plugins/popper/popper.js" ></script>
<!-- bootstrap -->
<script src="../../config-general/assets/plugins/bootstrap/js/bootstrap.min.js" ></script>
<!-- end js include path -->
<script>
		$(function () {
			$('[data-toggle="popover"]').popover();
		});

		$('.popover-dismiss').popover({trigger: 'focus'});
	</script>
</body>

</html>