<?php include("session.php");?>
<?php $idPaginaInterna = 'DT0128';?>
<?php include("../compartido/historial-acciones-guardar.php");?>
<?php include("../compartido/head.php");?>
<?php
try{
    $consulta = mysqli_query($conexion, "SELECT * FROM finanzas_cuentas WHERE fcu_id='".$_GET['idU']."'");
} catch (Exception $e) {
    include("../compartido/error-catch-to-report.php");
}
$resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH);

$disabledPermiso = "";
if(!Modulos::validarPermisoEdicion()){
	$disabledPermiso = "disabled";
}
?>

	<!--bootstrap -->
    <link href="../../config-general/assets/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
    <link href="../../config-general/assets/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.css" rel="stylesheet" media="screen">
	<!-- Theme Styles -->
    <link href="../../config-general/assets/css/pages/formlayout.css" rel="stylesheet" type="text/css" />
	<!-- dropzone -->
    <link href="../../config-general/assets/plugins/dropzone/dropzone.css" rel="stylesheet" media="screen">
    <!--tagsinput-->
    <link href="../../config-general/assets/plugins/jquery-tags-input/jquery-tags-input.css" rel="stylesheet">
    <!--select2-->
    <link href="../../config-general/assets/plugins/select2/css/select2.css" rel="stylesheet" type="text/css" />
    <link href="../../config-general/assets/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
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
                                <div class="page-title">Editar Movimientos</div>
								<?php include("../compartido/texto-manual-ayuda.php");?>
                            </div>
							<ol class="breadcrumb page-breadcrumb pull-right">
                                <li><a class="parent-item" href="#" name="movimientos.php" onClick="deseaRegresar(this)"><?=$frases[95][$datosUsuarioActual[8]];?></a>&nbsp;<i class="fa fa-angle-right"></i></li>
                                <li class="active">Editar Movimientos</li>
                            </ol>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-sm-12">


								<div class="panel">
									<header class="panel-heading panel-heading-purple"><?=$frases[95][$datosUsuarioActual[8]];?> </header>
                                	<div class="panel-body">

                                   
									<form name="formularioGuardar" action="movimientos-actualizar.php" method="post">
										<input type="hidden" value="<?=$resultado['fcu_id'];?>" name="idU">
										
										<div class="form-group row">
													<label class="col-sm-2 control-label">Fecha</label>
													<div class="col-sm-4">
														<input type="date" name="fecha" class="form-control" autocomplete="off" required value="<?=$resultado['fcu_fecha'];?>" <?=$disabledPermiso;?>>
													</div>
											</div>

											<div class="form-group row">
												<label class="col-sm-2 control-label">Detalle</label>
												<div class="col-sm-10">
													<input type="text" name="detalle" class="form-control" autocomplete="off" value="<?=$resultado['fcu_detalle'];?>" required <?=$disabledPermiso;?>>
												</div>
											</div>
											
											
										
										<div class="form-group row">
													<label class="col-sm-2 control-label">Valor</label>
													<div class="col-sm-6">
														<input type="text" name="valor" class="form-control" autocomplete="off" value="<?=$resultado['fcu_valor'];?>" required <?=$disabledPermiso;?>>
													</div>
											</div>
										
										
										<div class="form-group row">
                                            <label class="col-sm-2 control-label">Tipo de movimiento</label>
                                            <div class="col-sm-10">
                                                <select class="form-control  select2" name="tipo" required <?=$disabledPermiso;?>>
                                                    <option value="">Seleccione una opción</option>
													<option value="1" <?php if($resultado['fcu_tipo']==1){ echo "selected";}?>>Ingreso</option>
													<option value="2" <?php if($resultado['fcu_tipo']==2){ echo "selected";}?>>Egreso</option>
													<option value="3" <?php if($resultado['fcu_tipo']==3){ echo "selected";}?>>Cobro (CPC)</option>
													<option value="4" <?php if($resultado['fcu_tipo']==4){ echo "selected";}?>>Deuda (CPP)</option>
                                                </select>
                                            </div>
                                        </div>
										
										
										<div class="form-group row">
                                            <label class="col-sm-2 control-label">Anulado</label>
                                            <div class="col-sm-10">
                                                <select class="form-control  select2" name="anulado" required <?=$disabledPermiso;?>>
                                                    <option value="">Seleccione una opción</option>
													<option value="0" <?php if($resultado['fcu_anulado']==0){ echo "selected";}?>>No</option>
													<option value="1" <?php if($resultado['fcu_anulado']==1){ echo "selected";}?>>Si</option>
                                                </select>
                                            </div>
                                        </div>
										
										
										<div class="form-group row">
                                            <label class="col-sm-2 control-label">Estado</label>
                                            <div class="col-sm-10">
                                                <select class="form-control  select2" name="estado" required <?=$disabledPermiso;?>>
                                                    <option value="">Seleccione una opción</option>
													<option value="0" <?php if($resultado['fcu_cerrado']==0){ echo "selected";}?>>Abierto</option>
													<option value="1" <?php if($resultado['fcu_cerrado']==1){ echo "selected";}?>>Cerrado</option>
                                                </select>
                                            </div>
                                        </div>
										
										<div class="form-group row">
                                            <label class="col-sm-2 control-label">Forma de pago</label>
                                            <div class="col-sm-10">
                                                <select class="form-control  select2" name="forma" required <?=$disabledPermiso;?>>
                                                    <option value="">Seleccione una opción</option>
													<option value="1" <?php if($resultado['fcu_forma_pago']==1){ echo "selected";}?>>Efectivo</option>
													<option value="2" <?php if($resultado['fcu_forma_pago']==2){ echo "selected";}?>>Cheque</option>
													<option value="3" <?php if($resultado['fcu_forma_pago']==3){ echo "selected";}?>>T. Débito</option>
													<option value="4" <?php if($resultado['fcu_forma_pago']==4){ echo "selected";}?>>T. Crédito</option>
													<option value="5" <?php if($resultado['fcu_forma_pago']==5){ echo "selected";}?>>No aplica</option>
                                                </select>
                                            </div>
                                        </div>
										
											
										<div class="form-group row">
                                            <label class="col-sm-2 control-label">Usuario</label>
                                            <div class="col-sm-10">
												<?php
                                                try{
                                                    $datosConsulta = mysqli_query($conexion, "SELECT * FROM usuarios
                                                    INNER JOIN ".$baseDatosServicios.".general_perfiles ON pes_id=uss_tipo");
												} catch (Exception $e) {
													include("../compartido/error-catch-to-report.php");
												}
												?>
                                                <select class="form-control  select2" name="usuario" required <?=$disabledPermiso;?>>
                                                    <option value="">Seleccione una opción</option>
													<?php
													while($resultadosDatos = mysqli_fetch_array($datosConsulta, MYSQLI_BOTH)){
													?>
                                                    	<option value="<?=$resultadosDatos[0];?>" <?php if($resultado['fcu_usuario']==$resultadosDatos[0]){ echo "selected";}?>><?=UsuariosPadre::nombreCompletoDelUsuario($resultadosDatos)." (".$resultadosDatos['pes_nombre'].")";?></option>
													<?php }?>
                                                </select>
                                            </div>
                                        </div>

											<div class="form-group row">
												<label class="col-sm-2 control-label">Notificar al usuario</label>
												<div class="input-group spinner col-sm-10">
													<label class="switchToggle">
														<input type="checkbox" name="compartir" value="1" checked <?=$disabledPermiso;?>>
														<span class="slider red round"></span>
													</label>
												</div>
											 </div>
										
										<div class="form-group row">
												<label class="col-sm-2 control-label">Observaciones</label>
												<div class="col-sm-10">
                                                    <textarea cols="80" id="editor1" name="obs" class="form-control" rows="8" placeholder="Escribe tu mensaje" style="margin-top: 0px; margin-bottom: 0px; height: 100px; resize: none;" required <?=$disabledPermiso;?>><?=$resultado['fcu_observaciones'];?></textarea>
												</div>
											</div>
										


                                        <?php if(Modulos::validarPermisoEdicion()){?>
										    <input type="submit" class="btn btn-primary" value="Guardar cambios">&nbsp;
                                        <?php }?>
										
										<a href="#" name="movimientos.php" class="btn btn-secondary" onClick="deseaRegresar(this)"><i class="fa fa-long-arrow-left"></i>Regresar</a>
                                    </form>
                                </div>
                            </div>
                        </div>
						
						<div class="col-sm-3">
							<?php include("../compartido/publicidad-lateral.php");?>
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
    <script src="../../config-general/assets/plugins/bootstrap-inputmask/bootstrap-inputmask.min.js" ></script>
    <script src="../../config-general/assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js"  charset="UTF-8"></script>
    <script src="../../config-general/assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker-init.js"  charset="UTF-8"></script>
    <script src="../../config-general/assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"  charset="UTF-8"></script>
    <script src="../../config-general/assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker-init.js"  charset="UTF-8"></script>
    <!-- Common js-->
	<script src="../../config-general/assets/js/app.js" ></script>
    <script src="../../config-general/assets/js/layout.js" ></script>
	<script src="../../config-general/assets/js/theme-color.js" ></script>
	<!-- notifications -->
	<script src="../../config-general/assets/plugins/jquery-toast/dist/jquery.toast.min.js" ></script>
	<script src="../../config-general/assets/plugins/jquery-toast/dist/toast.js" ></script>	
	<!-- Material -->
	<script src="../../config-general/assets/plugins/material/material.min.js"></script>
	<!-- dropzone -->
    <script src="../../config-general/assets/plugins/dropzone/dropzone.js" ></script>
    <!--tags input-->
    <script src="../../config-general/assets/plugins/jquery-tags-input/jquery-tags-input.js" ></script>
    <script src="../../config-general/assets/plugins/jquery-tags-input/jquery-tags-input-init.js" ></script>
    <!--select2-->
    <script src="../../config-general/assets/plugins/select2/js/select2.js" ></script>
    <script src="../../config-general/assets/js/pages/select2/select2-init.js" ></script>
    <!-- end js include path -->
    <script src="../ckeditor/ckeditor.js"></script>

    <script>
        // Replace the <textarea id="editor1"> with a CKEditor 4
        // instance, using default configuration.
        CKEDITOR.replace( 'editor1' );
    </script>
</body>

<!-- Mirrored from radixtouch.in/templates/admin/smart/source/light/advance_form.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 18 May 2018 17:32:54 GMT -->
</html>