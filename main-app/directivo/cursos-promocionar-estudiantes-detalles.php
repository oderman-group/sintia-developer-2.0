<?php include("session.php");?>
<?php $idPaginaInterna = 'DT0145';?>
<?php include("../compartido/historial-acciones-guardar.php");?>
<?php include("../compartido/head.php");?>
<?php
require_once(ROOT_PATH."/main-app/class/Estudiantes.php");
require_once(ROOT_PATH."/main-app/class/Grados.php");
$consultaCursoActual = Grados::obtenerDatosGrados($_GET["curso"]);
$cursoActual = mysqli_fetch_array($consultaCursoActual, MYSQLI_BOTH);
$consultaCursoSiguiente = Grados::obtenerDatosGrados($cursoActual['gra_grado_siguiente']);
$cursoSiguiente = mysqli_fetch_array($consultaCursoSiguiente, MYSQLI_BOTH);

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

    <script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>

    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById('all').addEventListener('change', function(e) {
                document.querySelectorAll('#formularioPromocionar #check').forEach(function(checkElement) {
                    if(document.getElementById('all').checked){
                        checkElement.checked = true;
                    }else{
                        checkElement.checked = false;
                    }
                });
            });
        });
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
                                <div class="page-title">Promocionar Estudiantes</div>
                                <div>
									<b>Desde El Curso:</b> <?php if(isset($cursoActual['gra_nombre'])){echo $cursoActual['gra_nombre'];}?> → <b>Se Moverán Al Curso:</b> <?php if(isset($cursoSiguiente['gra_nombre'])){echo $cursoSiguiente['gra_nombre'];}?>
								</div>
								<?php include("../compartido/texto-manual-ayuda.php");?>
                            </div>
							<ol class="breadcrumb page-breadcrumb pull-right">
                                <li><a class="parent-item" href="#" name="cursos.php" onClick="deseaRegresar(this)">Cursos</a>&nbsp;<i class="fa fa-angle-right"></i></li>
                                <li class="active">Promocionar Estudiantes</li>
                            </ol>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <?php include("../../config-general/mensajes-informativos.php"); ?>
                            <div class="card card-topline-purple">
                                <div class="card-head">
                                    <header>Estudiantes</header>
                                    <div class="tools">
                                        <a class="fa fa-repeat btn-color box-refresh" href="javascript:;"></a>
                                        <a class="t-collapse btn-color fa fa-chevron-down" href="javascript:;"></a>
                                        <a class="t-close btn-color fa fa-times" href="javascript:;"></a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <form name="formularioGuardar" id="formularioPromocionar" action="cursos-promocionar-estudiantes.php" method="post">
                                        <input type="hidden" name="curso" value="<?=$_GET["curso"];?>">
                                        <div class="table-scrollable">
                                            <table id="example1" class="display" style="width:100%;">
                                                <thead>
                                                    <tr>
                                                        <th style="text-align: center; padding: 10px;">
                                                        <input type="checkbox" checked="checked" id="all" <?=$disabledPermiso;?>>
                                                        </th>
                                                        <th>DOCUMENTO</th>
                                                        <th>NOMBRES Y APELLIDOS</th>
                                                        <th>GRUPO</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        $filtro = " AND mat_grado=".$_GET['curso']." AND (mat_promocionado=0 OR mat_promocionado=NULL) AND mat_estado_matricula=1";
                                                        $consultaEstudiantes = Estudiantes::listarEstudiantesEnGrados($filtro, '');
                                                        $numeroEstudiantes=mysqli_num_rows($consultaEstudiantes);
                                                        while($datosEstudiante = mysqli_fetch_array($consultaEstudiantes, MYSQLI_BOTH)){
                                                            $nombre = Estudiantes::NombreCompletoDelEstudiante($datosEstudiante);
                                                    ?>
                                                    <tr>
                                                        <td style="text-align: center; padding: 10px;">
                                                            <input type="checkbox" checked="checked" id="check" name="id<?=$datosEstudiante['mat_id'];?>" value="<?=$datosEstudiante['mat_id'];?>" <?=$disabledPermiso;?>>
                                                        </td>
                                                        <td><?=$datosEstudiante['mat_documento'];?></td>
                                                        <td><?=$nombre;?></td>
                                                        <td>
                                                            <div class="form-group row">
                                                                <div class="col-sm-4">
                                                                    <?php
                                                                    try{
                                                                        $opcionesConsulta = mysqli_query($conexion, "SELECT * FROM academico_grupos");
                                                                    } catch (Exception $e) {
                                                                        include("../compartido/error-catch-to-report.php");
                                                                    }
                                                                    ?>
                                                                    <select class="form-control  select2" name="grupo<?=$datosEstudiante['mat_id'];?>" <?=$disabledPermiso;?>>
                                                                        <option value="">Seleccione una opción</option>
                                                                        <?php
                                                                        while($opcionesDatos = mysqli_fetch_array($opcionesConsulta, MYSQLI_BOTH)){
                                                                            $selected="";
                                                                            if($datosEstudiante['mat_grupo']==$opcionesDatos['gru_id']){
                                                                                $selected="selected";
                                                                            }
                                                                        ?>
                                                                            <option value="<?=$opcionesDatos[0];?>" <?=$selected;?>><?=$opcionesDatos['gru_id'].". ".strtoupper($opcionesDatos['gru_nombre']);?></option>
                                                                        <?php }?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                        }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <?php
                                            if($numeroEstudiantes>0 && Modulos::validarPermisoEdicion()){
                                        ?>
                                        <input type="submit" class="btn btn-primary" value="Realizar promoción">
                                        <?php
                                            }
                                        ?>
                                        <a href="#" name="cursos.php" class="btn btn-secondary" onClick="deseaRegresar(this)"><i class="fa fa-long-arrow-left"></i>Regresar</a>
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
<!-- <script type="application/javascript">
print();
</script>  -->

<!-- Mirrored from radixtouch.in/templates/admin/smart/source/light/advance_form.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 18 May 2018 17:32:54 GMT -->
</html>