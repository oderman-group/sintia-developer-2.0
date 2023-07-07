<?php include("session.php");?>
<?php $idPaginaInterna = 'DT0124';?>
<?php include("../compartido/historial-acciones-guardar.php");?>
<?php include("../compartido/head.php");?>
<?php
try{
	$consultaDatos=mysqli_query($conexion, "SELECT * FROM usuarios WHERE uss_id='".$_GET["id"]."'");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
$datosEditar = mysqli_fetch_array($consultaDatos, MYSQLI_BOTH);
if($datosEditar['uss_tipo'] == 1 and $datosUsuarioActual['uss_tipo']!=1){
	echo '<script type="text/javascript">window.location.href="usuarios.php?error=ER_DT_2&usuario='.$_GET["id"].'";</script>';
	exit();
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
                                <div class="page-title">Editar usuarios</div>
								<?php include("../compartido/texto-manual-ayuda.php");?>
                            </div>
							<ol class="breadcrumb page-breadcrumb pull-right">
                                <li><a class="parent-item" href="#" name="usuarios.php?cantidad=10" onClick="deseaRegresar(this)">Usuarios</a>&nbsp;<i class="fa fa-angle-right"></i></li>
                                <li class="active">Editar usuarios</li>
                            </ol>
                        </div>
                    </div>
                    <div class="row">
						
						
						
                        <div class="col-sm-12">
						<?php include("../../config-general/mensajes-informativos.php"); ?>
							<div class="panel">
								<header class="panel-heading panel-heading-purple"><?=$frases[119][$datosUsuarioActual[8]];?> </header>
								<div class="panel-body">
									<form name="formularioGuardar" action="usuarios-update.php" method="post" enctype="multipart/form-data">

										<input type="hidden" value="<?=$datosEditar['uss_id'];?>" name="idR">
										
										<div class="form-group row">
                                            <div class="col-sm-4" style="margin: 0 auto 10px">
												<div class="item">
													<img src="../files/fotos/<?=$datosEditar['uss_foto'];?>" width="300" height="300" />
												</div>
                                            </div>
                                        </div>
										
										<div class="form-group row">
                                            <label class="col-sm-2 control-label"><?=$frases[219][$datosUsuarioActual[8]];?></label>
                                            <div class="col-sm-4">
                                                <input type="file" name="fotoUss" class="form-control">
                                                <span style="color: #6017dc;">La foto debe estar en formato JPG o PNG.</span>
                                            </div>
                                        </div>
										<hr>

										<div class="form-group row">
											<label class="col-sm-2 control-label">ID</label>
											<div class="col-sm-2">
												<input type="text" name="idRegistro" class="form-control" value="<?=$datosEditar['uss_id'];?>" readonly>
											</div>
										</div>

										<?php
										$readonlyUsuario = 'readonly';
										if($config['conf_cambiar_nombre_usuario'] == 'SI') {
											$readonlyUsuario = '';
										}
										?>
										<div class="form-group row">
											<label class="col-sm-2 control-label">Usuario</label>
											<div class="col-sm-4">
												<input type="text" name="usuario" class="form-control" value="<?=$datosEditar['uss_usuario'];?>" <?=$readonlyUsuario;?>>
											</div>
										</div>

										<div class="form-group row">
                                            <label class="col-sm-2 control-label">Tipo de usuario</label>
                                            <div class="col-sm-3">
												<?php
												try{
													$opcionesConsulta = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".general_perfiles");
												} catch (Exception $e) {
													include("../compartido/error-catch-to-report.php");
												}
												?>
                                                <select class="form-control  select2" name="tipoUsuario" required>
                                                    <option value="">Seleccione una opción</option>
													<?php
													while($opcionesDatos = mysqli_fetch_array($opcionesConsulta, MYSQLI_BOTH)){
														if(
														($opcionesDatos[0] == 1 || $opcionesDatos[0] == 6) 
														and $datosUsuarioActual['uss_tipo']==5){continue;}
														$select = '';
														if($opcionesDatos[0]==$datosEditar['uss_tipo']) $select = 'selected';
													?>
                                                    	<option value="<?=$opcionesDatos[0];?>" <?=$select;?> ><?=$opcionesDatos['pes_nombre'];?></option>
													<?php }?>
                                                </select>
                                            </div>
                                        </div>
										
										<script>
										function habilitarClave() {
											var cambiarClave = document.getElementById("cambiarClave");
											var clave = document.getElementById("clave");
											
											if (cambiarClave.checked) {
											clave.disabled = false;
											clave.required = 'required';
											} else {
											clave.disabled = true;
											clave.required = '';
											clave.value = '';
											}
										}
										</script>

										<div class="form-group row">
											<label class="col-sm-2 control-label">Contraseña</label>
											<div class="col-sm-4">
												<input type="password" name="clave" id="clave" class="form-control" disabled>
											</div>
											<div class="col-sm-2">
											<div class="input-group spinner col-sm-10">
												<label class="switchToggle">
													<input type="checkbox" name="cambiarClave" id="cambiarClave" value="1" onchange="habilitarClave()">
													<span class="slider red round"></span>
												</label>
												<label class="col-sm-2 control-label">Cambiar Contraseña</label>
												</div>
											</div>
										</div>
										<hr>
										
										<?php
										$readOnly = '';
										$leyenda = '';
										if($datosEditar['uss_tipo']==4){
											$readOnly='readonly'; 
											$leyenda = 'El nombre de los estudiantes solo es editable desde la matrícula. <a href="estudiantes-editar.php?idR='.$datosEditar['uss_id'].'" style="text-decoration:underline;">IR A LA MATRÍCULA</a>';
										}
										?>
										
										<div class="form-group row">
											<label class="col-sm-2 control-label">Nombre</label>
											<div class="col-sm-4">
												<input type="text" name="nombre" class="form-control" value="<?=$datosEditar['uss_nombre'];?>" <?=$readOnly;?> pattern="^[A-Za-zñÑ]+$">
											<span style="color: tomato;"><?=$leyenda;?></span>
											</div>
											
										</div>

										<div class="form-group row">
											<label class="col-sm-2 control-label">Otro Nombre</label>
											<div class="col-sm-4">
												<input type="text" name="nombre2" class="form-control" value="<?=$datosEditar['uss_nombre2'];?>" <?=$readOnly;?>>
											</div>
										</div>

										<div class="form-group row">
											<label class="col-sm-2 control-label">Primer Apellido</label>
											<div class="col-sm-4">
												<input type="text" name="apellido1" class="form-control" value="<?=$datosEditar['uss_apellido1'];?>" <?=$readOnly;?> pattern="^[A-Za-zñÑ]+$">
											</div>
										</div>

										<div class="form-group row">
											<label class="col-sm-2 control-label">Segundo Apellido</label>
											<div class="col-sm-4">
												<input type="text" name="apellido2" class="form-control" value="<?=$datosEditar['uss_apellido2'];?>" <?=$readOnly;?>>
											</div>
										</div>

										<div class="form-group row">
											<label class="col-sm-2 control-label">Tipo de documento</label>
											<div class="col-sm-4">
												<?php
												try{
													$opcionesConsulta = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".opciones_generales
													WHERE ogen_grupo=1");
												} catch (Exception $e) {
													include("../compartido/error-catch-to-report.php");
												}
												?>
												<select class="form-control  select2" name="tipoD">
													<option value="">Seleccione una opción</option>
													<?php while($o = mysqli_fetch_array($opcionesConsulta, MYSQLI_BOTH)){
														if($o[0]==$datosEditar['uss_tipo_documento'])
														echo '<option value="'.$o[0].'" selected>'.$o[1].'</option>';
													else
														echo '<option value="'.$o[0].'">'.$o[1].'</option>';	
													}?>
												</select>
											</div>
										</div>

										<div class="form-group row">
											<label class="col-sm-2 control-label">Documento</label>
											<div class="col-sm-4">
												<input type="text" name="documento" class="form-control" value="<?=$datosEditar['uss_documento'];?>" <?=$readOnly;?>>
											</div>
										</div>
										
										<div class="form-group row">
											<label class="col-sm-2 control-label">Email</label>
											<div class="col-sm-4">
												<input type="email" name="email" class="form-control" value="<?=$datosEditar['uss_email'];?>">
											</div>
										</div>
										
										<div class="form-group row">
											<label class="col-sm-2 control-label">Celular</label>
											<div class="col-sm-4">
												<input type="text" name="celular" class="form-control" value="<?=$datosEditar['uss_celular'];?>">
											</div>
										</div>

										<div class="form-group row">
											<label class="col-sm-2 control-label">Teléfono</label>
											<div class="col-sm-4">
												<input type="text" name="telefono" class="form-control" value="<?=$datosEditar['uss_telefono'];?>">
											</div>
										</div>

										<div class="form-group row">
											<label class="col-sm-2 control-label">Dirección</label>
											<div class="col-sm-4">
												<input type="text" name="direccion" class="form-control" value="<?=$datosEditar['uss_direccion'];?>">
											</div>
										</div>

										<div class="form-group row">
											<label class="col-sm-2 control-label">Ocupacion</label>
											<div class="col-sm-4">
												<input type="text" name="ocupacion" class="form-control" value="<?=$datosEditar['uss_ocupacion'];?>">
											</div>
										</div>

										<div class="form-group row">
											<label class="col-sm-2 control-label">Lugar de expedición del documento</label>
											<div class="col-sm-4">
												<input type="text" name="lExpedicion" class="form-control" value="<?=$datosEditar['uss_lugar_expedicion'];?>">
											</div>
										</div>
										
										
										
										<div class="form-group row">
                                            <label class="col-sm-2 control-label">Género</label>
                                            <div class="col-sm-3">
												<?php
												try{
													$opcionesConsulta = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".opciones_generales WHERE ogen_grupo=4");
												} catch (Exception $e) {
													include("../compartido/error-catch-to-report.php");
												}
												?>
                                                <select class="form-control  select2" name="genero" required>
                                                    <option value="">Seleccione una opción</option>
													<?php
													while($opcionesDatos = mysqli_fetch_array($opcionesConsulta, MYSQLI_BOTH)){
														$select = '';
														if($opcionesDatos[0]==$datosEditar['uss_genero']) $select = 'selected';
													?>
                                                    	<option value="<?=$opcionesDatos[0];?>" <?=$select;?> ><?=$opcionesDatos['ogen_nombre'];?></option>
													<?php }?>
                                                </select>
                                            </div>
                                        </div>
										
										
										
										<hr>
										<div class="form-group row">
											<label class="col-sm-2 control-label">Intentos de acceso fallidos</label>
											<div class="col-sm-1">
												<input type="number" name="intentosFallidos" class="form-control" value="<?=$datosEditar['uss_intentos_fallidos'];?>">
											</div>
										</div>

										<div class="form-group row">
											<label class="col-sm-2 control-label">Usuario bloqueado</label>
											<div class="col-sm-1">
												<input type="number" name="bloqueado" class="form-control" value="<?=$datosEditar['uss_bloqueado'];?>" readonly>
											</div>
										</div>

										<div class="form-group row">
											<label class="col-sm-2 control-label">Última actualización</label>
											<div class="col-sm-4">
												<input type="text"  class="form-control" value="<?=$datosEditar['uss_ultima_actualizacion'];?>" readonly>
											</div>
										</div>
										
										<div class="form-group row">
											<label class="col-sm-2 control-label">Último ingreso</label>
											<div class="col-sm-4">
												<input type="text"  class="form-control" value="<?=$datosEditar['uss_ultimo_ingreso'];?>" readonly>
											</div>
										</div>
										
										<div class="form-group row">
											<label class="col-sm-2 control-label">Última salida</label>
											<div class="col-sm-4">
												<input type="text"  class="form-control" value="<?=$datosEditar['uss_ultima_salida'];?>" readonly>
											</div>
										</div>


										<input type="submit" class="btn btn-primary" value="Guardar cambios">&nbsp;
										
										<a href="#" name="usuarios.php?cantidad=10" class="btn btn-secondary" onClick="deseaRegresar(this)"><i class="fa fa-long-arrow-left"></i>Regresar</a>
                                    </form>
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
</body>

<!-- Mirrored from radixtouch.in/templates/admin/smart/source/light/advance_form.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 18 May 2018 17:32:54 GMT -->
</html>