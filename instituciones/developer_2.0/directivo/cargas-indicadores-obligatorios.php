<?php include("session.php");?>
<?php $idPaginaInterna = 118;?>
<?php include("verificar-permiso-pagina.php");?>
<?php include("../compartido/historial-acciones-guardar.php");?>
<?php include("../compartido/head.php");?>
	<!-- data tables -->
    <link href="../../../config-general/assets/plugins/datatables/plugins/bootstrap/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css"/>
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
                                <div class="page-title">Indicadores Obligatorios</div>
								<?php include("../compartido/texto-manual-ayuda.php");?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">

								<div class="col-md-4 col-lg-3">
									<?php include("../compartido/publicidad-lateral.php");?>
								</div>
								  
                                <?php
                                $ind = mysql_fetch_array(mysql_query("SELECT sum(ind_valor) FROM academico_indicadores WHERE ind_obligatorio=1",$conexion));
                                $indGenerados = mysql_num_rows(mysql_query("SELECT * FROM academico_indicadores_carga WHERE ipc_creado=0",$conexion));
                                ?>
								<div class="col-md-8 col-lg-9">
                                    <div class="alert alert-block alert-warning">
                                        <h4 class="alert-heading">Información importante!</h4>
                                        <p>Una vez termine de crear los indicadores obligatorios, por favor dirijase a la Configuración del Sistema para colocar el porcentaje que tendrán a disposición los docentes.</p>
                                    </div>
                                    <div class="card card-topline-purple">
                                        <div class="card-head">
                                            <header>Indicadores Obligatorios</header>
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
														<a href="cargas-indicadores-obligatorios-agregar.php" id="addRow" class="btn deepPink-bgcolor">
															Agregar nuevo <i class="fa fa-plus"></i>
														</a>
													</div>
												</div>
											</div>
											
                                        <div class="table-scrollable">
                                    		<table id="example1" class="display" style="width:100%;">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>C&oacute;digo</th>
                                                        <th>Nombre</th>
                                                        <th>Valor</th>
														<th>Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
													<?php
													$consulta = mysql_query("SELECT * FROM academico_indicadores WHERE ind_obligatorio=1",$conexion);
													$contReg = 1;
													while($resultado = mysql_fetch_array($consulta)){
                                                        $sumaP = $sumaP + $resultado[3];
													?>
													<tr>
                                                        <td><?=$contReg;?></td>
                                                        <td><?=$resultado[0];?></td>
                                                        <td><?=$resultado[1];?></td>
                                                        <td><?=$resultado[3];?></td>														
														<td>
															<div class="btn-group">
																  <button type="button" class="btn btn-primary"><?=$frases[54][$datosUsuarioActual[8]];?></button>
																  <button type="button" class="btn btn-primary dropdown-toggle m-r-20" data-toggle="dropdown">
																	  <i class="fa fa-angle-down"></i>
																  </button>
																  <ul class="dropdown-menu" role="menu">
																	  <li><a href="cargas-indicadores-obligatorios-editar.php?id=<?=$resultado[0];?>"><?=$frases[165][$datosUsuarioActual[8]];?></a></li>
                                        							    <li><a href="cargas-indicadores-obligatorios-eliminar.php?idN=<?=$resultado[0];?>" onClick="if(!confirm('Desea eliminar este registro?')){return false;}">Eliminar</a></li>	
																	  <li><a href="cargas-indicadores-obligatorios-ver.php?ind=<?=$resultado[0];?>&indNombre=<?=$resultado[1];?>" title="Grados por asignaturas">Grados por asignaturas</a></li>
																  </ul>
															  </div>
														</td>
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
								
								<div class="col-md-4 col-lg-3">
									<?php include("../compartido/publicidad-lateral.php");?>
								</div>
								
								
								
							
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end page content -->
             <?php include("../compartido/panel-configuracion.php");?>
        </div>
        <!-- end page container -->
        <?php include("../compartido/footer.php");?>
    </div>
    <!-- start js include path -->
    <script src="../../../config-general/assets/plugins/jquery/jquery.min.js" ></script>
    <script src="../../../config-general/assets/plugins/popper/popper.js" ></script>
    <script src="../../../config-general/assets/plugins/jquery-blockui/jquery.blockui.min.js" ></script>
	<script src="../../../config-general/assets/plugins/jquery-slimscroll/jquery.slimscroll.js"></script>
    <!-- bootstrap -->
    <script src="../../../config-general/assets/plugins/bootstrap/js/bootstrap.min.js" ></script>
    <script src="../../../config-general/assets/plugins/bootstrap-switch/js/bootstrap-switch.min.js" ></script>
	<!-- data tables -->
    <script src="../../../config-general/assets/plugins/datatables/jquery.dataTables.min.js" ></script>
 	<script src="../../../config-general/assets/plugins/datatables/plugins/bootstrap/dataTables.bootstrap4.min.js" ></script>
    <script src="../../../config-general/assets/js/pages/table/table_data.js" ></script>
    <!-- Common js-->
	<script src="../../../config-general/assets/js/app.js" ></script>
    <script src="../../../config-general/assets/js/layout.js" ></script>
	<script src="../../../config-general/assets/js/theme-color.js" ></script>
	<!-- notifications -->
	<script src="../../../config-general/assets/plugins/jquery-toast/dist/jquery.toast.min.js" ></script>
	<script src="../../../config-general/assets/plugins/jquery-toast/dist/toast.js" ></script>
	<!-- Material -->
	<script src="../../../config-general/assets/plugins/material/material.min.js"></script>
    <!-- end js include path -->
</body>

</html>