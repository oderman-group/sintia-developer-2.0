<?php include("session.php");?>
<?php $idPaginaInterna = 'DT0077';?>
<?php include("../compartido/historial-acciones-guardar.php");?>
<?php include("../compartido/head.php");?>
<?php require_once("../class/Sysjobs.php");?>


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
                                <div class="page-title">Importar estudiantes desde Excel</div>
								<?php include("../compartido/texto-manual-ayuda.php");?>
                            </div>
							<ol class="breadcrumb page-breadcrumb pull-right">
                                <li><a class="parent-item" href="#" name="estudiantes.php?cantidad=10" onClick="deseaRegresar(this)"><?=$frases[78][$datosUsuarioActual[8]];?></a>&nbsp;<i class="fa fa-angle-right"></i></li>
                                <li class="active">Importar estudiantes desde Excel</li>
                            </ol>
                        </div>
                    </div>
                    
                    <div class="row">
						
						<div class="col-sm-3">
							<div class="panel">
								<header class="panel-heading panel-heading-blue">Paso a paso</header>
									<div class="panel-body">
                                        <p><b>1.</b> Solicite la plantilla de excel (Google Sheet) a la administración de la Plataforma SINTIA.</p>
                                        <p><b>2.</b> Llene los campos de los estudiantes y acudientes en el orden que la plantilla los solicita.</p>
                                        <p><b>3.</b> Finalmente descargue la plantilla ya completada, cargue la plantilla en el campo que dice <mark>Subir la planilla lista</mark> y dele click al botón importar matrículas.</p>
									</div>

                                    <header class="panel-heading panel-heading-blue">Consideraciones</header>
									<div class="panel-body">
                                        <p><b>-></b> Tenga en cuenta, para importar los estudiantes, los campos del Nro. de documento, Primer Nombre, Primer Apellido y grado, son obligatorios.</p>
                                        <p><b>-></b> Si el estudiante ya existe en la plataforma, usted puede seleccionar los campos que desea actualizar en el campo que dice <mark>Campos a actualizar</mark>. Si no selecciona ningun campo entonces los estudiantes ya existentes se omitirán y solo se ingresarán los que no existan en la plataforma.</p>
									</div>
							 </div>

                        </div>
                        <div  class="col-sm-9" >
                            <div class="col-sm-12">
                                    <?php include("../../config-general/mensajes-informativos.php"); ?>
                                    <div class="panel">
                                        <header class="panel-heading panel-heading-purple"><?=$frases[119][$datosUsuarioActual[8]];?> </header>
                                        <div class="panel-body">

                                    
                                        <form name="formularioGuardar" action="job-excel-importar-estudiantes.php" method="post" enctype="multipart/form-data">
                                            
                                            <div class="form-group row">
                                                <label class="col-sm-3 control-label">Subir la planilla lista</label>
                                                <div class="col-sm-6">
                                                    <input type="file" class="form-control" name="planilla" required>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-3 control-label">Coloque el número de la última fila hasta donde quiere que el archivo sea leido</label>
                                                <div class="col-sm-4">
                                                    <input type="number" class="form-control" name="filaFinal" value="200" required><br>
                                                    <span style="font-size: 12px; color:#6017dc;">Fila hasta donde hay información de los estudiantes y acudientes. Esto se usa para evitar que se lean filas que no tienen información.</span>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group row">
                                                <label class="col-sm-3 control-label">Campos a actualizar</label>
                                                <div class="col-sm-9">
                                                    <select id="multiple" class="form-control select2-multiple" name="actualizarCampo[]" multiple>
                                                        <option value="">Seleccione una opción</option>
                                                        <option value="1">Grado</option>
                                                        <option value="2">Grupo</option>
                                                        <option value="3">Tipo de Documento</option>
                                                        <option value="4">Acudiente</option>
                                                        <option value="5">Segundo nombre del estudiante</option>
                                                        <option value="6">Fecha de nacimiento</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <input type="submit" class="btn btn-primary" value="Importar matrículas">&nbsp;
                                            
                                            <a href="#" name="estudiantes.php" class="btn btn-secondary" onClick="deseaRegresar(this)"><i class="fa fa-long-arrow-left"></i>Regresar</a>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">                                   
                                    <div class="panel">
                                        <header class="panel-heading panel-heading-purple">Solicitudes de Importacion </header>
                                        <?php
												
                                                $parametrosBuscar = array(
                                                    "tipo" =>JOBS_TIPO_IMPORTAR_ESTUDIANTES_EXCEL,
                                                    "responsable" => $_SESSION['id'],
                                                    "agno"=>$config['conf_agno'],
                                                    "estado" =>JOBS_ESTADO_PENDIENTE
                                                );										
                                                $listadoCrobjobs=SysJobs::listar($parametrosBuscar);
                                        ?>
                                               
                                        <div class="card-body">

                                                        <div >
                                                            <table id="example1"  style="width:100%;">
                                                                <thead>
                                                                    <tr>
                                                                        <th>#</th>
                                                                        <th>Cod</th>
                                                                        <th>Fecha</th>
                                                                        <th>mensaje</th>
                                                                        <th>Estado</th>
                                                                    </tr>
                                                                </thead>
                                                        <tbody>
                                                           <?php $contReg = 1;
                                                                    while ($resultado = mysqli_fetch_array($listadoCrobjobs, MYSQLI_BOTH)) {?>
                                                                        <tr>
                                                                            <td><?= $contReg; ?></td>
                                                                            <td><?= $resultado['job_id']; ?></td>
                                                                            <td><?= $resultado['job_fecha_creacion']; ?></td>
                                                                            <td><?= $resultado['job_mensaje']; ?></td> 
                                                                            <td>
                                                                                <div class="btn-group">
                                                                                    <button type="button" class="btn btn-primary dropdown-toggle m-r-20" data-toggle="dropdown">
                                                                                         <?= $resultado['job_estado']; ?>
                                                                                    </button>
                                                                                   
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    <?php $contReg++;
                                                                    } ?>
                                                         </tbody>
                                                     </table>
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