<?php include("session.php");?>
<?php $idPaginaInterna = 'DT0069';?>
<?php include("../compartido/historial-acciones-guardar.php");?>
<?php include("../compartido/head.php");

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
                                <div class="page-title">Categorías</div>
								<?php include("../compartido/texto-manual-ayuda.php");?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
								
								<div class="col-md-12">
                                <?php include("../../config-general/mensajes-informativos.php"); ?>
                                    <div class="card card-topline-purple">
                                        <div class="card-head">
                                            <header>Categorías</header>
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
                                                        <?php if(Modulos::validarPermisoEdicion() && Modulos::validarSubRol(['DT0071'])){?>
                                                            <a href="disciplina-categorias-agregar.php" id="addRow" class="btn deepPink-bgcolor">
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
                                                        <th>#</th>
														<th>ID</th>
														<th>Categoría</th>
														<th>Faltas</th>
                                                        <?php if(Modulos::validarPermisoEdicion()){?>
														    <th><?=$frases[54][$datosUsuarioActual[8]];?></th>
                                                        <?php }?>
                                                    </tr>
                                                </thead>
                                                <tbody>
													<?php	
                                                    try{												
													    $consulta = mysqli_query($conexion, "SELECT * FROM disciplina_categorias");
                                                    } catch (Exception $e) {
                                                        include("../compartido/error-catch-to-report.php");
                                                    }
													 $contReg = 1;
													 while($resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH)){
                                                        try{
                                                            $consultaNumFaltas=mysqli_query($conexion, "SELECT COUNT(dfal_id) FROM ".BD_DISCIPLINA.".disciplina_faltas
                                                            WHERE dfal_id_categoria='".$resultado['dcat_id']."' AND dfal_institucion={$config['conf_id_institucion']} AND dfal_year={$_SESSION["bd"]}");
                                                        } catch (Exception $e) {
                                                            include("../compartido/error-catch-to-report.php");
                                                        }
														 $numFaltas = mysqli_fetch_array($consultaNumFaltas, MYSQLI_BOTH);
													 ?>
													<tr>
                                                        <td><?=$contReg;?></td>
														<td><?=$resultado['dcat_id'];?></td>
														<td><?=$resultado['dcat_nombre'];?></td>
														<td>
                                                        <?php if(Modulos::validarSubRol(['DT0066'])) {?>
                                                            <a href="disciplina-faltas.php?cat=<?=base64_encode($resultado['dcat_id']);?>" style="text-decoration: underline;"><?=$numFaltas[0];?></a>
                                                        <?php } else {?>
                                                            <?=$numFaltas[0];?>
                                                        <?php }?>
                                                        </td>
														
                                                        <?php if(Modulos::validarPermisoEdicion()){?>
                                                            <td>
                                                                <?php if(Modulos::validarSubRol(['DT0070', 'DT0159'])) {?>
                                                                    <div class="btn-group">
                                                                        <button type="button" class="btn btn-primary"><?=$frases[54][$datosUsuarioActual[8]];?></button>
                                                                        <button type="button" class="btn btn-primary dropdown-toggle m-r-20" data-toggle="dropdown">
                                                                            <i class="fa fa-angle-down"></i>
                                                                        </button>
                                                                        <ul class="dropdown-menu" role="menu">
                                                                            <?php if(Modulos::validarSubRol(['DT0070'])) {?>
                                                                                <li><a href="disciplina-categorias-editar.php?idR=<?=base64_encode($resultado['dcat_id']);?>"><?=$frases[165][$datosUsuarioActual[8]];?></a></li>
                                                                            <?php }?>
                                                                            
                                                                            <?php if(Modulos::validarSubRol(['DT0159'])) {?>
                                                                                <li><a href="javascript:void(0);" onClick="sweetConfirmacion('Alerta!','Desea eliminar este registro?','question','disciplina-categoria-eliminar.php?id=<?=base64_encode($resultado[0]);?>')">Eliminar</a></li>
                                                                            <?php }?>
                                                                        </ul>
                                                                    </div>
                                                                <?php }?>
                                                            </td>
                                                        <?php }?>
                                                    </tr>
													<?php 
														 $contReg++;
													  }
													  ?>
                                                </tbody>
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