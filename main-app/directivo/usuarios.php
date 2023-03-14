<?php include("session.php");?>
<?php $idPaginaInterna = 'DT0126';?>
<?php include("../compartido/historial-acciones-guardar.php");?>
<?php include("../compartido/head.php");?>
<?php
$Plataforma = new Plataforma;
?>
<!-- Theme Styles -->
    <link href="../../config-general/assets/css/pages/formlayout.css" rel="stylesheet" type="text/css" />
<!--tagsinput-->
    <link href="../../config-general/assets/plugins/jquery-tags-input/jquery-tags-input.css" rel="stylesheet">

	<!-- data tables -->
    <link href="../../config-general/assets/plugins/datatables/plugins/bootstrap/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css"/>


<script type="text/javascript">
function guardarAjax(datos){ 
  var idR = datos.id;
  var valor = 0;

	if(document.getElementById(idR).checked){
		valor = 1;
		document.getElementById("Reg"+idR).style.backgroundColor="#ff572238";
	}else{
		valor = 0;
		document.getElementById("Reg"+idR).style.backgroundColor="white";
	}
  var operacion = 1;	

$('#respuestaGuardar').empty().hide().html("").show(1);
	datos = "idR="+(idR)+
			"&valor="+(valor)+
			"&operacion="+(operacion);
		   $.ajax({
			   type: "POST",
			   url: "ajax-guardar.php",
			   data: datos,
			   success: function(data){
			   	$('#respuestaGuardar').empty().hide().html(data).show(1);
		   	   }
		  });
}
</script>
</head>
<!-- END HEAD -->
<?php include("../compartido/body.php");?>
    <div class="page-wrapper">
        <?php include("../compartido/encabezado.php");?>
		
        <?php include("../compartido/panel-color.php");?>
        <!-- start page container -->
        <div class="page-container">
 			<?php include("../compartido/menu.php");?>
			<!-- start page content -->
            <div class="page-content-wrapper">
                <div class="page-content">
                    <div class="page-bar">
                        <div class="page-title-breadcrumb">
                            <div class=" pull-left">
                                <div class="page-title"><?=$frases[75][$datosUsuarioActual['uss_idioma']];?></div>
								<?php include("../compartido/texto-manual-ayuda.php");?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
								
							<?php
							$filtro = '';
							if(is_numeric($_GET["tipo"])){$filtro .= " AND uss_tipo='".$_GET["tipo"]."'";}
							?>
								
								<div class="col-md-12">
									<?php include("../../config-general/mensajes-informativos.php"); ?>

									<?php include("includes/usuarios-buscador.php");?>
									
                                    <div class="card card-topline-purple">
                                        <div class="card-head">
                                            <header><?=$frases[75][$datosUsuarioActual['uss_idioma']];?></header>
                                            <div class="tools">
                                                <a class="fa fa-repeat btn-color box-refresh" href="javascript:;"></a>
			                                    <a class="t-collapse btn-color fa fa-chevron-down" href="javascript:;"></a>
			                                    <a class="t-close btn-color fa fa-times" href="javascript:;"></a>
                                            </div>
                                        </div>
                                        <div class="card-body">
											
											<div class="row" style="margin-bottom: 10px;">
												<div class="col-sm-12">
													<div class="btn-group">
														<a href="usuarios-agregar.php" id="addRow" class="btn deepPink-bgcolor">
															Agregar nuevo <i class="fa fa-plus"></i>
														</a>
													</div>

													
													
												</div>
											</div>
											
										<span id="respuestaGuardar"></span>	
                                        
											<div class="table-scrollable">
											<table id="example1" class="display" style="width:100%;">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
														<th>Bloq.</th>
														<th>ID</th>
														<th>Usuario (REP)<br>Clave</th>
														<th>Nombre</th>
														<th>Tipo</th>
														<th>Sesión</th>
														<th><?=$frases[54][$datosUsuarioActual[8]];?></th>
                                                    </tr>
                                                </thead>
												
												<?php
													
													$filtroLimite = '';
													if(is_numeric($_GET["cantidad"])){$filtroLimite = "LIMIT 0,".$_GET["cantidad"];}
													
													 $consulta = mysqli_query($conexion, "SELECT * FROM usuarios
													 INNER JOIN ".$baseDatosServicios.".general_perfiles ON pes_id=uss_tipo
													 WHERE uss_id=uss_id $filtro
													 ORDER BY uss_id
													 $filtroLimite");
													 $contReg = 1;
													$bloqueado = array("NO","SI");
													 while($resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH)){
														 $bgColor = '';
														 if($resultado['uss_bloqueado']==1) $bgColor = '#ff572238';
														 
														$cheked = '';
														if($resultado['uss_bloqueado']==1){$cheked = 'checked';}

														$consultaNumCarga=mysqli_query($conexion, "SELECT * FROM academico_cargas WHERE car_docente='".$resultado[0]."'");
														$numCarga = mysqli_num_rows($consultaNumCarga);

														$consultaUsuariosRepetidos = mysqli_query($conexion, "SELECT count(uss_usuario) as rep 
														FROM usuarios 
														WHERE uss_usuario='".$resultado['uss_usuario']."'
														GROUP BY uss_usuario
														");
														$usuarioRepetido = mysqli_fetch_array($consultaUsuariosRepetidos, MYSQLI_BOTH);
														$avisoRepetido = null;
														if($usuarioRepetido['rep']>1) $avisoRepetido = 'style="background-color:gold;"';
													 ?>
													<tr id="Reg<?=$resultado['uss_id'];?>" style="background-color:<?=$bgColor;?>;">
                                                        <td><?=$contReg;?></td>
														<td>
															<div class="input-group spinner col-sm-10">
																<label class="switchToggle">
																	<input type="checkbox" id="<?=$resultado['uss_id'];?>" name="bloqueado" value="1" onChange="guardarAjax(this)" <?=$cheked;?>>
																	<span class="slider red round"></span>
																</label>
															</div>
														</td>
														<td><?=$resultado['uss_id'];?></td>
														<td <?=$avisoRepetido?>>
															<?=$resultado['uss_usuario'];?>
															<?php if($usuarioRepetido['rep']>1){echo " (".$usuarioRepetido['rep'].")";}?>
															<br><pre><?=$resultado['uss_clave'];?></pre>
														</td>
														<td><?=UsuariosPadre::nombreCompletoDelUsuario($resultado);?></td>
														<td><?=$resultado['pes_nombre'];?></td>
														<td>
															<?=$resultado['uss_estado'];?><br>
															<span style="font-size: 11px;"><?=$resultado['uss_ultimo_ingreso'];?></span>
														</td>

														<td>
															<div class="btn-group">
																  <button type="button" class="btn btn-primary">Acciones</button>
																  <button type="button" class="btn btn-primary dropdown-toggle m-r-20" data-toggle="dropdown">
																	  <i class="fa fa-angle-down"></i>
																  </button>
																  <ul class="dropdown-menu" role="menu">
																  <?php if($resultado['uss_tipo']==1 and $datosUsuarioActual['uss_tipo']==5){}else{?>
																  	<li><a href="usuarios-editar.php?id=<?=$resultado['uss_id'];?>">Editar</a></li>
																  <?php }?>	

																	  <?php if($resultado['uss_tipo'] != 1 and $resultado['uss_tipo'] != 5){?>
																	  	<li><a href="auto-login.php?user=<?=$resultado['uss_id'];?>&tipe=<?=$resultado['uss_tipo'];?>">Autologin</a></li>
																	  <?php }?>
																	  
																	<?php if($resultado['uss_tipo']==3){?>
																		<li><a href="usuarios-acudidos.php?id=<?=$resultado['uss_id'];?>">Acudidos</a></li>
																	<?php }?>
																	  
																	  <?php if($resultado['uss_tipo']==2){?>
																	  	<li><a href="../compartido/planilla-docentes.php?docente=<?=$resultado['uss_id'];?>" target="_blank">Planillas Docentes</a></li>
																	  <?php }?>

																	  <?php if(($numCarga == 0 and $resultado['uss_tipo']==2) or $resultado['uss_tipo']==3){?>
																	  	<li><a href="#" name="guardar.php?id=<?=$resultado['uss_id'];?>&get=6" onClick="deseaEliminar(this)" id="<?=$resultado['not_id'];?>">Eliminar</a></li>
																	  <?php }?>

																  </ul>
															  </div>
														</td>
                                                    </tr>
													<?php 
														 $contReg++;
													  }
													  ?>
												
											</table>
												
											</div>
											
                                        </div>
                                    </div>
                                </div>
								
								
								
							
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end page content -->
             <?php // include("../compartido/panel-configuracion.php");?>
        </div>
        <!-- end page container -->
        <?php include("../compartido/footer.php");?>
    </div>
    <!-- start js include path -->
    <script src="../../config-general/assets/plugins/jquery/jquery.min.js" ></script>
    <script src="../../config-general/assets/plugins/popper/popper.js" ></script>
    <script src="../../config-general/assets/plugins/jquery-blockui/jquery.blockui.min.js" ></script>
	<script src="../../config-general/assets/plugins/jquery-slimscroll/jquery.slimscroll.js"></script>
    <!-- bootstrap -->
    <script src="../../config-general/assets/plugins/bootstrap/js/bootstrap.min.js" ></script>
    <script src="../../config-general/assets/plugins/bootstrap-switch/js/bootstrap-switch.min.js" ></script>
	<!-- data tables -->
    <script src="../../config-general/assets/plugins/datatables/jquery.dataTables.min.js" ></script>
 	<script src="../../config-general/assets/plugins/datatables/plugins/bootstrap/dataTables.bootstrap4.min.js" ></script>
    <script src="../../config-general/assets/js/pages/table/table_data.js" ></script>
    <!-- Common js-->
	<script src="../../config-general/assets/js/app.js" ></script>
    <script src="../../config-general/assets/js/layout.js" ></script>
	<script src="../../config-general/assets/js/theme-color.js" ></script>
	<!-- notifications -->
	<script src="../../config-general/assets/plugins/jquery-toast/dist/jquery.toast.min.js" ></script>
	<script src="../../config-general/assets/plugins/jquery-toast/dist/toast.js" ></script>
	<!-- Material -->
	<script src="../../config-general/assets/plugins/material/material.min.js"></script>
    <!-- end js include path -->
</body>

</html>