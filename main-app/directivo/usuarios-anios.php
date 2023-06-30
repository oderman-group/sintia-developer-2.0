<?php include("session.php");?>
<?php $idPaginaInterna = 'DT0201';?>
<?php include("../compartido/historial-acciones-guardar.php");?>
<?php include("../compartido/head.php");?>
<?php require_once("../class/UsuariosPadre.php");?>


<?php
$Plataforma = new Plataforma;
$busqueda = $_GET['busqueda'];
$msj = $_GET['msj'];
?>
<!-- Theme Styles -->
    <link href="../../config-general/assets/css/pages/formlayout.css" rel="stylesheet" type="text/css" />
<!--tagsinput-->
    <link href="../../config-general/assets/plugins/jquery-tags-input/jquery-tags-input.css" rel="stylesheet">

	<!-- data tables -->
    <link href="../../config-general/assets/plugins/datatables/plugins/bootstrap/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css"/>



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
								
							
								
								<div class="col-md-12">
								
								<div id="not_msj" class="alert alert-block alert-danger" style="display:none">
       							 <p><?=$msj;?></p>
    							</div>
									<?php include("includes/barra-superior-usuarios-anios.php");?>
									
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
											<div >
											<table  class="display" style="width:100%;">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
														<th>ID</th>
														<th>Usuario </th>
														<th>Nombre</th>
														<th>Tipo</th>
														<th>Año</th>
                                                    </tr>
                                                </thead>
												<?php												
												if($busqueda && strlen($busqueda)>= 4){
													$listaUsuarios = UsuariosPadre::listarUsuariosAnio($busqueda);														
												}else{
													if(!$msj &&  !is_null($busqueda)){
														 if(strlen($busqueda)< 4){
															$msj="El campo de busqueda debe tener más de 4 caracteres";
														}else if(!$listaUsuarios){
															$msj="No se encontraron resultados";
														}
													}
												}
												if(!$listaUsuarios && !$msj && !is_null($busqueda)){
													$msj="No se encontraron resultados";													
												}

												if($msj){
													echo '<script >			
														var divNotificacion = document.getElementById("not_msj");
														divNotificacion.textContent="'.$msj.'";
														divNotificacion.style.display="revert";
														</script>';
												}
																							
												?>	
												<?php $contReg=1; 												
												foreach ($listaUsuarios as $usuario) {?>
													<tr >
														<td><?=$contReg;?></td>
														<td><?=$usuario["uss_id"];?></td>
														<td><?=$usuario["uss_usuario"];?></td>
														<td><?=$usuario["uss_nombre"];?></td>
														<td><?=$usuario["pes_nombre"];?></td>
														<td><?=$usuario["anio"];?></td>
													</tr>												
												<?php $contReg++; }?>												
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