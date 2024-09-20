<?php include("session.php");?>
<?php $idPaginaInterna = 'DT0041';
require_once(ROOT_PATH."/main-app/class/CargaAcademica.php");?>
<?php include("../compartido/historial-acciones-guardar.php");?>
<?php include("../compartido/head.php");

Utilidades::validarParametros($_GET);

if(!Modulos::validarSubRol([$idPaginaInterna])){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
	exit();
}?>
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
                                <div class="page-title">Horarios</div>
								<?php include("../compartido/texto-manual-ayuda.php");?>
                            </div>
							<ol class="breadcrumb page-breadcrumb pull-right">
                                <li><a class="parent-item" href="javascript:void(0);" name="cargas.php" onClick="deseaRegresar(this)"><?=$frases[12][$datosUsuarioActual['uss_idioma']];?></a>&nbsp;<i class="fa fa-angle-right"></i></li>
                                <li class="active">Horarios</li>
                            </ol>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                           	<?php include("../compartido/publicidad-lateral.php");?>
								</div>
								
								<div class="col-md-12">
                                    <div class="card card-topline-purple">
                                        <div class="card-head">
                                            <header>Horarios</header>
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
														<?php if(Modulos::validarPermisoEdicion() && Modulos::validarSubRol(['DT0043'])){?>
															<a href="cargas-horarios-agregar.php?id=<?=$_GET["id"]?>" id="addRow" class="btn deepPink-bgcolor">
																Agregar nuevo <i class="fa fa-plus"></i>
															</a>
														<?php }?>
													</div>
												</div>
											</div>
											
                                        <div class="table-scrollable">
                                    		<table id="example1" class="display" style="width:100%;">
                                                <thead>
                                                    <tr>
														<th>Codigo</th>
														<th>Día</th>
														<th>Desde</th>
														<th>Hasta</th>
														<?php if(Modulos::validarPermisoEdicion() && Modulos::validarSubRol(['DT0042','DT0156'])){?>
															<th>Acciones</th>
														<?php }?>
                                                    </tr>
                                                </thead>
                                                <tbody>
													<?php
													$consulta = CargaAcademica::traerHorariosCargas($conexion, $config, $_GET["id"]);
													while($resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH)){
														switch($resultado['hor_dia']){
															case 1: $dia = 'Domingo'; break;

															case 2: $dia = 'Lunes'; break;

															case 3: $dia = 'Martes'; break;

															case 4: $dia = 'Miercoles'; break;

															case 5: $dia = 'Jueves'; break;

															case 6: $dia = 'Viernes'; break;

															case 7: $dia = 'Sabado'; break;
																
															default: $dia = 'Sin Dia'; break;	
														}
													?>
													<tr>
														<td><?=$resultado['hor_id'];?></td>
														<td><?=$dia;?></td>
														<td><?=$resultado['hor_desde'];?></td>
														<td><?=$resultado['hor_hasta'];?></td>
														<?php if(Modulos::validarPermisoEdicion() && Modulos::validarSubRol(['DT0042','DT0156'])){?>
															<td>
																<div class="btn-group">
																	<button type="button" class="btn btn-primary"><?=$frases[54][$datosUsuarioActual['uss_idioma']];?></button>
																	<button type="button" class="btn btn-primary dropdown-toggle m-r-20" data-toggle="dropdown">
																		<i class="fa fa-angle-down"></i>
																	</button>
																	<ul class="dropdown-menu" role="menu">
																		<?php if(Modulos::validarSubRol(['DT0042'])){?>
																		<li><a href="cargas-horarios-editar.php?id=<?=base64_encode($resultado['hor_id']);?>&idGH=<?=$_GET["id"]?>" data-toggle="popover" data-placement="top" data-content="Modificar los datos de la carga" title="Editar Horarios">Editar</a></li>
																		<?php } if(Modulos::validarSubRol(['DT0156'])){?>
																		<li><a href="cargas-horarios-eliminar.php?idH=<?=base64_encode($resultado['hor_id']);?>&idC=<?=base64_encode($resultado['hor_id_carga']);?>" data-toggle="popover" data-placement="top" data-content="Deshabilitar los datos de la carga" title="Eliminar Horarios">Eliminar</a></li>
                                                        				<?php }?>
																	</ul>
																</div>
															</td>
														<?php }?>
                                                    </tr>
                                      				<?php }?>
                                                </tbody>
                                            </table>
                                            </div>
											<?php $botones = new botonesGuardar("cargas.php",false); ?>
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