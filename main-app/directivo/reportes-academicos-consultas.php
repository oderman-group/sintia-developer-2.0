<?php include("session.php");?>
<?php $idPaginaInterna = 'DT0120';?>
<?php include("../compartido/historial-acciones-guardar.php");?>
<?php include("../compartido/head.php");

if(!Modulos::validarSubRol([$idPaginaInterna])){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
	exit();
}?>

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
                                <div class="page-title">Informes Academicos</div>
								<?php include("../compartido/texto-manual-ayuda.php");?>
                            </div>
							<ol class="breadcrumb page-breadcrumb pull-right">
                                <li class="active">Informes Academicos</li>
                            </ol>
                        </div>
                    </div>
                    <div class="row">
						
                        <div class="col-sm-12">

								<div class="panel">
									<header class="panel-heading panel-heading-purple">Informes Academicos</header>
                                	<div class="panel-body">

                                   
                                    <form action="../compartido/reporte-matriculados-estado.php" method="post" class="form-horizontal" enctype="multipart/form-data" target="_blank">

										<div class="form-group row">
                                            <label class="col-sm-2 control-label">Curso</label>
                                            <div class="col-sm-10">
                                                <select class="form-control  select2" name="cursosR">
                                                <option value=""></option>
                                                <?php
                                                try{
                                                    $c_cursos=mysqli_query($conexion, "SELECT gra_id, gra_codigo, gra_nombre, gra_formato_boletin, gra_valor_matricula, gra_valor_pension, gra_estado FROM ".BD_ACADEMICA.".academico_grados WHERE institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]} ORDER BY gra_codigo;");
                                                } catch (Exception $e) {
                                                    include("../compartido/error-catch-to-report.php");
                                                }
                                                while($r_cursos=mysqli_fetch_array($c_cursos, MYSQLI_BOTH)){
                                                    echo '<option value="'.$r_cursos["gra_id"].'">'.$r_cursos["gra_nombre"].'</option>';
                                                }
                                                ?>
                                                </select>
                                            </div>
                                        </div>
										
										<div class="form-group row">
                                            <label class="col-sm-2 control-label">Grupos</label>
                                            <div class="col-sm-10">
                                                <select class="form-control  select2" name="gruposR">
                                                <option value=""></option>
                                                <?php 
                                                try{
                                                    $c_grupos=mysqli_query($conexion, "SELECT gru_id, gru_codigo, gru_nombre FROM ".BD_ACADEMICA.".academico_grupos WHERE institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]} ORDER BY gru_nombre;");
                                                } catch (Exception $e) {
                                                    include("../compartido/error-catch-to-report.php");
                                                }
                                                while($r_grupos=mysqli_fetch_array($c_grupos, MYSQLI_BOTH)){
                                                    echo '<option value="'.$r_grupos["gru_id"].'">'.$r_grupos["gru_nombre"].'</option>';
                                                }
                                                ?>
                                                </select>
                                            </div>
                                        </div>
										
										<div class="form-group row">
                                            <label class="col-sm-2 control-label">Estado</label>
                                            <div class="col-sm-10">
                                                <select class="form-control  select2" name="estadoR">
                                                <option value=""></option>
                                                <?php foreach( $estadosMatriculasEstudiantes as $clave => $valor ) {?>
                                                    <option value="<?=$clave;?>"><?=$valor;?></option>
                                                <?php } ?>
                                                </select>
                                            </div>
                                        </div>
										
										<div class="form-group row">
                                            <label class="col-sm-2 control-label">Tipo de estudiante</label>
                                            <div class="col-sm-10">
                                                <select class="form-control  select2" name="tipoR">
                                                <option value=""></option>
                                                <?php 
                                                try{
                                                    $c_testudiante=mysqli_query($conexion, "SELECT ogen_id, ogen_nombre, ogen_grupo FROM ".$baseDatosServicios.".opciones_generales WHERE ogen_grupo=5;");
												} catch (Exception $e) {
													include("../compartido/error-catch-to-report.php");
												}
                                                while($r_testudiante=mysqli_fetch_array($c_testudiante, MYSQLI_BOTH)){
                                                    echo '<option value="'.$r_testudiante["ogen_id"].'">'.$r_testudiante["ogen_nombre"].'</option>';
                                                }
                                                ?>
                                                </select>
                                            </div>
                                        </div>
										
										<div class="form-group row">
                                            <label class="col-sm-2 control-label">Acudiente</label>
                                            <div class="col-sm-10">
                                                <select class="form-control  select2" name="acudienteR">
                                                <option value=""></option>
                                                <option value="1">Si</option>
                                                <option value="0">No</option>
                                                </select>
                                            </div>
                                        </div>
										
										<div class="form-group row">
                                            <label class="col-sm-2 control-label">Estudiante de Inclusión</label>
                                            <div class="col-sm-10">
                                                <select class="form-control  select2" name="inclu">
                                                <option value=""></option>
                                                <option value="1">Si</option>
                                                <option value="0">No</option>
                                                </select>
                                            </div>
                                        </div>
										
										<div class="form-group row">
                                            <label class="col-sm-2 control-label">Estudiante Extranjero</label>
                                            <div class="col-sm-10">
                                                <select class="form-control  select2" name="extra">
                                                <option value=""></option>
                                                <option value="1">Si</option>
                                                <option value="0">No</option>
                                                </select>
                                            </div>
                                        </div>
										
										<div class="form-group row">
                                            <label class="col-sm-2 control-label">Foto</label>
                                            <div class="col-sm-10">
                                                <select class="form-control  select2" name="fotoR">
                                                <option value=""></option>
                                                <option value="1">Si</option>
                                                <option value="0">No</option>
                                                </select>
                                            </div>
                                        </div>
										
										<div class="form-group row">
                                            <label class="col-sm-2 control-label">Genero</label>
                                            <div class="col-sm-10">
                                                <select class="form-control  select2" name="generoR">
                                                <option value=""></option>
                                                <?php 
                                                try{
                                                    $c_testudiante=mysqli_query($conexion, "SELECT ogen_id, ogen_nombre, ogen_grupo FROM ".$baseDatosServicios.".opciones_generales WHERE ogen_grupo=4;");
												} catch (Exception $e) {
													include("../compartido/error-catch-to-report.php");
												}
                                                while($r_testudiante=mysqli_fetch_array($c_testudiante, MYSQLI_BOTH)){
                                                    echo '<option value="'.$r_testudiante["ogen_id"].'">'.$r_testudiante["ogen_nombre"].'</option>';
                                                }
                                                ?>
                                                </select>
                                            </div>
                                        </div>
										
										<div class="form-group row">
                                            <label class="col-sm-2 control-label">Religi&oacute;n</label>
                                            <div class="col-sm-10">
                                                <select class="form-control  select2" name="religionR">
                                                <option value=""></option>
                                                <?php 
                                                try{
                                                    $c_testudiante=mysqli_query($conexion, "SELECT ogen_id, ogen_nombre, ogen_grupo FROM ".$baseDatosServicios.".opciones_generales WHERE ogen_grupo=2;");
												} catch (Exception $e) {
													include("../compartido/error-catch-to-report.php");
												}
                                                while($r_testudiante=mysqli_fetch_array($c_testudiante, MYSQLI_BOTH)){
                                                    echo '<option value="'.$r_testudiante["ogen_id"].'">'.$r_testudiante["ogen_nombre"].'</option>';
                                                }
                                                ?>
                                                </select>
                                            </div>
                                        </div>
										
										<div class="form-group row">
                                            <label class="col-sm-2 control-label">Estrato</label>
                                            <div class="col-sm-10">
                                                <select class="form-control  select2" name="estratoE">
                                                <option value=""></option>
                                                <?php 
                                                try{
                                                    $c_testudiante=mysqli_query($conexion, "SELECT ogen_id, ogen_nombre, ogen_grupo FROM ".$baseDatosServicios.".opciones_generales WHERE ogen_grupo=3;");
												} catch (Exception $e) {
													include("../compartido/error-catch-to-report.php");
												}
                                                while($r_testudiante=mysqli_fetch_array($c_testudiante, MYSQLI_BOTH)){
                                                    echo '<option value="'.$r_testudiante["ogen_id"].'">'.$r_testudiante["ogen_nombre"].'</option>';
                                                }
                                                ?>
                                                </select>
                                            </div>
                                        </div>
										
										<div class="form-group row">
                                            <label class="col-sm-2 control-label">Tipo de documento</label>
                                            <div class="col-sm-10">
                                                <select class="form-control  select2" name="tdocumentoR">
                                                <option value=""></option>
                                                <?php 
                                                try{
                                                    $c_testudiante=mysqli_query($conexion, "SELECT ogen_id, ogen_nombre, ogen_grupo FROM ".$baseDatosServicios.".opciones_generales WHERE ogen_grupo=1;");
												} catch (Exception $e) {
													include("../compartido/error-catch-to-report.php");
												}
                                                while($r_testudiante=mysqli_fetch_array($c_testudiante, MYSQLI_BOTH)){
                                                    echo '<option value="'.$r_testudiante["ogen_id"].'">'.$r_testudiante["ogen_nombre"].'</option>';
                                                }
                                                ?>
                                                </select>
                                            </div>
                                        </div>

                                        <input type="submit" class="btn btn-success" value="Consultar Informe" name="consultas">
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