<?php
include("session.php");
$idPaginaInterna = 'DC0070';
include("../compartido/historial-acciones-guardar.php");
include("verificar-carga.php");
include("verificar-periodos-diferentes.php");
include("../compartido/head.php");

$idR="";
if(!empty($_GET["idR"])){ $idR=base64_decode($_GET["idR"]);}

$consultaDatos=mysqli_query($conexion, "SELECT * FROM academico_clases WHERE cls_id='".$idR."' AND cls_estado=1");
$datosConsulta = mysqli_fetch_array($consultaDatos, MYSQLI_BOTH);
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
                                <div class="page-title"><?=$frases[375][$datosUsuarioActual[8]];?> <?=$frases[7][$datosUsuarioActual[8]];?></div>
								<?php include("../compartido/texto-manual-ayuda.php");?>
                            </div>
							<ol class="breadcrumb page-breadcrumb pull-right">
                                <li><a class="parent-item" href="#" name="clases.php" onClick="deseaRegresar(this)"><?=$frases[7][$datosUsuarioActual[8]];?></a>&nbsp;<i class="fa fa-angle-right"></i></li>
                                <li class="active"><?=$frases[375][$datosUsuarioActual[8]];?> <?=$language['academico.clases'][$datosUsuarioActual[8]];?></li>
                            </ol>
                        </div>
                    </div>
                    <div class="row">
						
						<div class="col-sm-3">


						<?php include("info-carga-actual.php");?>

							
                            <div class="panel">
								<header class="panel-heading panel-heading-purple"><?=$frases[119][$datosUsuarioActual[8]];?> </header>
                                <div class="panel-body">
									<p><b>Banco de datos:</b> Tienes la opción de usar información que ya existe y así no tengas que escribir todo de nuevo. <mark>Sólo debes usar una de las 2 alternativas:</mark> o llenas la información desde cero o escoges la existente. Si usas las 2, <mark>el banco de datos tendrá prioridad</mark> y esta será lo que el sistema use.<br>
									<mark> - MIO :</mark> Significa que la información fue creada por ti.
									</p>
									<p><b>Compartir:</b> Compartir la información <mark>es una manera de colaborar con tus colegas.</mark> La información irá al banco de datos y podrá ser usada por ti o por otros colegas tuyos más adelante. En caso de que no desees compartirla puedes dar click sobre el botón para que se desactive y la información sólo puedas verla tú.</p>
								</div>
							</div>
                        </div>
						
                        <div class="col-sm-9">


								<div class="panel">
									<header class="panel-heading panel-heading-purple"><?=$frases[119][$datosUsuarioActual[8]];?> </header>
                                	<div class="panel-body">

                                   
									<form name="formularioGuardar" action="clases-actualizar.php?carga=<?=base64_encode($cargaConsultaActual);?>&periodo=<?=base64_encode($periodoConsultaActual);?>" method="post" enctype="multipart/form-data">
										<input type="hidden" value="<?=$idR;?>" name="idR">


											<div class="form-group row">
												<label class="col-sm-2 control-label">Tema <span style="color: red;">(*)</span></label>
												<div class="col-sm-10">
													<input type="text" name="contenido" class="form-control" autocomplete="off" value="<?=$datosConsulta['cls_tema'];?>" required>
												</div>
											</div>
										
											<div class="form-group row">
												<label class="col-sm-2 control-label">Descripción</label>
												<div class="col-sm-10">
													<textarea id="editor1" name="descripcion" class="form-control" rows="5" style="margin-top: 0px; margin-bottom: 0px; height: 100px; resize: none;"><?=$datosConsulta['cls_descripcion'];?></textarea>
												</div>
											</div>
											
											<div class="form-group row">
												<label class="col-sm-2 control-label">Fecha <span style="color: red;">(*)</span></label>
												<div class="col-sm-4">
													<input type="date" name="fecha" class="form-control" autocomplete="off" value="<?=$datosConsulta['cls_fecha'];?>"  required>
												</div>
											</div>

											<div class="form-group row">
												<label class="col-sm-2 control-label">Unidad</label>
												<div class="col-sm-10">
													<?php
													$unidadConsulta = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_unidades 
													WHERE uni_id_carga='" . $cargaConsultaActual . "' AND uni_periodo='" . $periodoConsultaActual . "' AND uni_eliminado!=1 AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
													?>
													<select class="form-control  select2" name="unidad">
														<option value="">Seleccione una opción</option>
														<?php
														while($unidadDatos = mysqli_fetch_array($unidadConsulta, MYSQLI_BOTH)){
															$selected='';
															if($unidadDatos['id_nuevo']==$datosConsulta['cls_unidad']){
																$selected='selected';
															}
														?>
															<option value="<?=$unidadDatos['id_nuevo'];?>" <?=$selected?>><?=$unidadDatos['uni_nombre']?></option>
														<?php }?>
													</select>
												</div>
											</div>
										
										<div class="form-group row">
												<label class="col-sm-2 control-label">Disponible para estudiantes <button type="button" class="btn btn-sm" data-toggle="tooltip" data-placement="right" title="Puede o no ser vista por los estudiantes."><i class="fa fa-question"></i></button></label>
												<div class="input-group spinner col-sm-4">
													<label class="switchToggle">
														<?php
														$cheked = '';
														if($datosConsulta['cls_disponible']==1){$cheked = 'checked';}
														?>
														<input type="checkbox" name="disponible" value="1" <?=$cheked;?>>
														<span class="slider yellow round"></span>
													</label>
												</div>
											 </div>
										
										<p class="text-warning">Opcional.</p>
											<div class="form-group row">
												<label class="col-sm-2 control-label">Hipervinculo <button type="button" class="btn btn-sm" data-toggle="tooltip" data-placement="right" title="Un link que quiere que los estudiantes tengan de referencia para esta clase en particular."><i class="fa fa-question"></i></button></label>
												<div class="col-sm-10">
													<input type="url" name="vinculo" class="form-control" autocomplete="off" placeholder="https://www.ejemplo.com" value="<?=$datosConsulta['cls_hipervinculo'];?>">
												</div>
											</div>
											
											<div class="form-group row">
												<label class="col-sm-2 control-label">Video de youtube <button type="button" class="btn btn-sm" data-toggle="tooltip" data-placement="right" title="Pegue la URL del video"><i class="fa fa-question"></i></button></label>
												<div class="col-sm-10">
													<input type="text" name="video" class="form-control" autocomplete="off" value="<?=$datosConsulta['cls_video_url'];?>">
												</div>
											</div>
										
											<div class="form-group row">
												<label class="col-sm-2 control-label">Archivo 1</label>
												<div class="col-sm-4">
													<input type="file" name="file" class="form-control" autocomplete="off" onChange="archivoPeso(this)">
												</div>
												<div class="col-sm-4">
													<input type="text" name="archivo1" class="form-control" autocomplete="off" placeholder="Nombre del archivo 1" value="<?=$datosConsulta['cls_nombre_archivo1'];?>">
												</div>
												<div class="col-sm-2">
													<?php if($datosConsulta['cls_archivo']!="" and file_exists('../files/clases/'.$datosConsulta['cls_archivo'])){?><a href="../files/clases/<?=$datosConsulta['cls_archivo'];?>" target="_blank">Descargar</a><?php }?>
												</div>
											</div>
										
										<div class="form-group row">
												<label class="col-sm-2 control-label">Archivo 2</label>
												<div class="col-sm-4">
													<input type="file" name="file2" class="form-control" autocomplete="off" onChange="archivoPeso(this)">
												</div>
												<div class="col-sm-4">
													<input type="text" name="archivo2" class="form-control" autocomplete="off" placeholder="Nombre del archivo 2" value="<?=$datosConsulta['cls_nombre_archivo2'];?>">
												</div>
												<div class="col-sm-2">
													<?php if($datosConsulta['cls_archivo2']!="" and file_exists('../files/clases/'.$datosConsulta['cls_archivo2'])){?><a href="../files/clases/<?=$datosConsulta['cls_archivo2'];?>" target="_blank">Descargar</a><?php }?>
												</div>
											</div>
										
										<div class="form-group row">
												<label class="col-sm-2 control-label">Archivo 3</label>
												<div class="col-sm-4">
													<input type="file" name="file3" class="form-control" autocomplete="off" onChange="archivoPeso(this)">
												</div>
												<div class="col-sm-4">
													<input type="text" name="archivo3" class="form-control" autocomplete="off" placeholder="Nombre del archivo 3" value="<?=$datosConsulta['cls_nombre_archivo3'];?>">
												</div>
												<div class="col-sm-2">
													<?php if($datosConsulta['cls_archivo3']!="" and file_exists('../files/clases/'.$datosConsulta['cls_archivo3'])){?><a href="../files/clases/<?=$datosConsulta['cls_archivo3'];?>" target="_blank">Descargar</a><?php }?>
												</div>
											</div>



										<button type="submit" class="btn  btn-info">
										<i class="fa fa-save" aria-hidden="true"></i> Guardar cambios 
									</button>
										
										<a href="#" name="clases.php" class="btn btn-secondary" onClick="deseaRegresar(this)"><i class="fa fa-long-arrow-left"></i>Regresar</a>
                                    </form>
                                </div>
                            </div>
							
							<?php if($datosConsulta['cls_video']!=""){?>
							<div class="panel">
								<header class="panel-heading panel-heading-red">VIDEO YOUTUBE</header>
                                <div class="panel-body">
									<p class="iframe-container">
										<iframe width="100%" height="415" src="https://www.youtube.com/embed/<?=$datosConsulta['cls_video'];?>" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
									</p>	
								</div>
							</div>
							<?php }?>
							
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