<?php
include("session.php");

$idPaginaInterna = 'DV0032';

Modulos::verificarPermisoDev();

include("../compartido/historial-acciones-guardar.php");
include("../compartido/head.php");

$year = date("Y");
if (!empty($_GET['year'])) {
    $year = $_GET['year'];
}
$consultaConfiguracion = mysqli_query($conexion, "SELECT configuracion.*, ins_siglas, ins_years FROM " . $baseDatosServicios . ".configuracion 
INNER JOIN " . $baseDatosServicios . ".instituciones ON ins_id=conf_id_institucion
WHERE conf_id_institucion='" . $_GET["id"] . "' AND conf_agno='" . $year . "'");
$datosInstitucion = mysqli_fetch_array($consultaConfiguracion, MYSQLI_BOTH);
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
<?php include("../compartido/body.php"); ?>
<div class="page-wrapper">
    <?php include("../compartido/encabezado.php"); ?>

    <?php include("../compartido/panel-color.php"); ?>
    <!-- start page container -->
    <div class="page-container">
        <?php include("../compartido/menu.php"); ?>
        <!-- start page content -->
        <div class="page-content-wrapper">
            <div class="page-content">
                <div class="page-bar">
                    <div class="page-title-breadcrumb">
                        <div class=" pull-left">
                            <div class="page-title"><?= $frases[17][$datosUsuarioActual[8]]; ?> del Sistema de <b><?= $datosInstitucion['ins_siglas']; ?></b> (<?= $year; ?>)</div>
                            <?php include("../compartido/texto-manual-ayuda.php"); ?>
                        </div>
                        <ol class="breadcrumb page-breadcrumb pull-right">
                            <li><a class="parent-item" href="#" name="dev-instituciones.php" onClick="deseaRegresar(this)">Insituciones</a>&nbsp;<i class="fa fa-angle-right"></i></li>
                            <li class="active"><?= $frases[17][$datosUsuarioActual[8]]; ?> del Sistema de <b><?= $datosInstitucion['ins_siglas']; ?></b></li>
                        </ol>
                    </div>
                </div>
                <div class="row">

                    <div class="col-sm-12">
                        <?php
                        include("../../config-general/mensajes-informativos.php");
                        include("includes/barra-superior-dev-instituciones-configuracion-informacion.php");
                        ?>
                        <br>
                        <div class="panel">
                            <header class="panel-heading panel-heading-purple"><?= $frases[17][$datosUsuarioActual[8]]; ?> de <b><?= $datosInstitucion['ins_siglas']; ?></b></header>
                            <div class="panel-body">


                                <form name="formularioGuardar" action="dev-instituciones-configuracion-actualizar.php" method="post">
                                    <input type="hidden" name="id" value="<?= $_GET["id"] ?>">

                                    <div class="form-group row">
                                        <label class="col-sm-2 control-label">Año Actual</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="year" class="form-control col-sm-2" value="<?= $year; ?>" readonly>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 control-label">Periodos a trabajar <span style="color: red;">(*)</span></label>
                                        <div class="col-sm-8">
                                            <input type="text" name="periodoTrabajar" class="form-control col-sm-2" value="<?= $datosInstitucion[19]; ?>" required pattern="[0-9]+">
                                            <span style="color:#6017dc;">Las instituciones normalmente manejan 4 periodos. Los colegios semestralizados o de bachillerato acelerado manejan 2 periodos.</span>
                                        </div>
                                    </div>



                                    <div class="form-group row">
                                        <label class="col-sm-2 control-label">Periodo Actual <span style="color: red;">(*)</span></label>
                                        <div class="col-sm-3">
                                            <select class="form-control  select2" name="periodo" required>
                                                <option value="">Seleccione una opción</option>
                                                <?php
                                                $p = 1;
                                                $pFinal = $config[19] + 1;
                                                while ($p <= $pFinal) {
                                                    $label = 'Periodo ' . $p;
                                                    if ($p == $pFinal) {
                                                        $label = 'AÑO FINALIZADO';
                                                    }

                                                    if ($p == $datosInstitucion['conf_periodo'])
                                                        echo '<option value="' . $p . '" selected>' . $label . '</option>';
                                                    else
                                                        echo '<option value="' . $p . '">' . $label . '</option>';
                                                    $p++;
                                                }
                                                ?>
                                            </select>
                                            <span style="color:#6017dc;">Este valor solo se verá reflejado en los informes que obtiene los directivos.</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 control-label">Estilo de calificación <span style="color: red;">(*)</span></label>
                                        <div class="col-sm-4">
                                            <select class="form-control  select2" name="estiloNotas" required>
                                                <option value="">Seleccione una opción</option>
                                                <?php
                                                $opcionesGeneralesConsulta = mysqli_query($conexion, "SELECT * FROM academico_categorias_notas");
                                                while ($opcionesGeneralesDatos = mysqli_fetch_array($opcionesGeneralesConsulta, MYSQLI_BOTH)) {
                                                    if ($datosInstitucion[22] == $opcionesGeneralesDatos[0])
                                                        echo '<option value="' . $opcionesGeneralesDatos[0] . '" selected>' . $opcionesGeneralesDatos[1] . '</option>';
                                                    else
                                                        echo '<option value="' . $opcionesGeneralesDatos[0] . '">' . $opcionesGeneralesDatos[1] . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 control-label">Rango de las notas (Desde - Hasta) <span style="color: red;">(*)</span></label>
                                        <div class="col-sm-10">
                                            <input type="text" style="margin-top: 20px;" name="desde" class="col-sm-1" value="<?= $datosInstitucion[3]; ?>">
                                            <input type="text" style="margin-top: 20px;" name="hasta" class="col-sm-1" value="<?= $datosInstitucion[4]; ?>">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 control-label">Nota minima para aprobar <span style="color: red;">(*)</span></label>
                                        <div class="col-sm-2">
                                            <input type="text" name="notaMinima" class="form-control" value="<?= $datosInstitucion[5]; ?>">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 control-label">Cantidad de decimales en las notas <span style="color: red;">(*)</span></label>
                                        <div class="col-sm-2">
                                            <input type="text" name="decimalesNotas" class="form-control" value="<?= $datosInstitucion['conf_decimales_notas']; ?>">
                                            <span style="color:#6017dc;">Indica cuántos decimales aparecerán en los cálculos de las notas.</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 control-label">Color de las notas (Perdidas - Ganadas) <span style="color: red;">(*)</span></label>
                                        <div class="col-sm-10">
                                            <input type="color" style="margin-top: 20px;" name="perdida" class="col-sm-1" value="<?= $datosInstitucion[6]; ?>">
                                            <input type="color" style="margin-top: 20px;" name="ganada" class="col-sm-1" value="<?= $datosInstitucion[7]; ?>">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 control-label">Asignar porcentaje a las asignaturas?</label>
                                        <div class="col-sm-2">
                                            <select class="form-control  select2" name="porcenAsigna">
                                                <option value="SI" <?php if ($datosInstitucion['conf_agregar_porcentaje_asignaturas'] == 'SI') {
                                                                        echo "selected";
                                                                    } ?>>SI</option>
                                                <option value="NO" <?php if ($datosInstitucion['conf_agregar_porcentaje_asignaturas'] == 'NO') {
                                                                        echo "selected";
                                                                    } ?>>No</option>
                                            </select>
                                            <span style="color:#6017dc;">Indica si las asignaturas tendrán un porcentaje diferente dentro del área al momento de calcular las notas en el boletín.</span>
                                        </div>
                                    </div>

                                    <hr>
                                    <div class="form-group row">
                                        <label class="col-sm-2 control-label">En qué orden desea ver el nombre de los estudiantes?</label>
                                        <div class="col-sm-4">
                                            <select class="form-control  select2" name="ordenEstudiantes">
                                                <option value="1" <?php if ($datosInstitucion['conf_orden_nombre_estudiantes'] == 1) {
                                                                        echo "selected";
                                                                    } ?>>Nombres y Apellidos (Andres David Arias Pertuz)</option>
                                                <option value="2" <?php if ($datosInstitucion['conf_orden_nombre_estudiantes'] == 2) {
                                                                        echo "selected";
                                                                    } ?>>Apellidos y nombres (Arias Pertuz Andres David)</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 control-label">Mostrar calificaciones a los acudientes?</label>
                                        <div class="col-sm-2">
                                            <select class="form-control  select2" name="caliAcudientes">
                                                <option value="1" <?php if ($datosInstitucion['conf_calificaciones_acudientes'] == 1) {
                                                                        echo "selected";
                                                                    } ?>>SI</option>
                                                <option value="0" <?php if ($datosInstitucion['conf_calificaciones_acudientes'] == 0) {
                                                                        echo "selected";
                                                                    } ?>>No</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 control-label">Mostrar calificaciones a los estudiantes?</label>
                                        <div class="col-sm-2">
                                            <select class="form-control  select2" name="caliEstudiantes">
                                                <option value="1" <?php if ($datosInstitucion['conf_mostrar_calificaciones_estudiantes'] == 1) {
                                                                        echo "selected";
                                                                    } ?>>SI</option>
                                                <option value="0" <?php if ($datosInstitucion['conf_mostrar_calificaciones_estudiantes'] == 0) {
                                                                        echo "selected";
                                                                    } ?>>No</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 control-label">Permiso de actualizar las definitivas en consolidado final?</label>
                                        <div class="col-sm-8">
                                            <select class="form-control col-sm-2 select2" name="permisoConsolidado">
                                                <option value="1" <?php if ($datosInstitucion['conf_editar_definitivas_consolidado'] == 1) {
                                                                        echo "selected";
                                                                    } ?>>SI</option>
                                                <option value="0" <?php if ($datosInstitucion['conf_editar_definitivas_consolidado'] == 0) {
                                                                        echo "selected";
                                                                    } ?>>NO</option>
                                            </select>
                                            <span style="color:#6017dc;">Esta acción permite o no actualizar las definitivas, en el consolidado final, en cualquier momento.</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 control-label">Observaciones multiples en comportamiento?</label>
                                        <div class="col-sm-2">
                                            <select class="form-control  select2" name="observacionesMultiples">
                                                <option value="1" <?php if ($datosInstitucion['conf_observaciones_multiples_comportamiento'] == 1) {
                                                                        echo "selected";
                                                                    } ?>>SI</option>

                                                <option value="0" <?php if ($datosInstitucion['conf_observaciones_multiples_comportamiento'] == 0 || $datosInstitucion['conf_observaciones_multiples_comportamiento'] == null) {
                                                                        echo "selected";
                                                                    } ?>>No</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 control-label">Numero de registros en listados?</label>
                                        <div class="col-sm-8">
                                            <select class="form-control col-sm-2 select2" name="numRegistros">
                                                <option value="20" <?php if ($datosInstitucion['conf_num_registros'] == 20) {
                                                                        echo "selected";
                                                                    } ?>>20</option>
                                                <option value="30" <?php if ($datosInstitucion['conf_num_registros'] == 30) {
                                                                        echo "selected";
                                                                    } ?>>30</option>
                                                <option value="50" <?php if ($datosInstitucion['conf_num_registros'] == 50) {
                                                                        echo "selected";
                                                                    } ?>>50</option>
                                                <option value="100" <?php if ($datosInstitucion['conf_num_registros'] == 100) {
                                                                        echo "selected";
                                                                    } ?>>100</option>
                                            </select>
                                            <span style="color:#6017dc;">Esta acción permite escoger la cantidad de registros que desea que se listen al entrar a matrículas, cargas o usuarios.</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 control-label">Fecha que aparecerá en el proximo Informe Parcial</label>
                                        <div class="col-sm-2">
                                            <input type="text" name="fechapa" class="form-control" value="<?= $datosInstitucion[28]; ?>">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 control-label">Texto de arriba del informe parcial</label>
                                        <div class="col-sm-10">
                                            <textarea cols="80" id="editor1" name="descrip" rows="10"><?= $datosInstitucion[29]; ?></textarea>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 control-label">Permitir descargar informe parcial a acudientes</label>
                                        <div class="col-sm-2">
                                            <select class="form-control  select2" name="informeParcial">
                                                <option value="1" <?php if ($datosInstitucion['conf_informe_parcial'] == 1) {
                                                                        echo "selected";
                                                                    } ?>>SI</option>
                                                <option value="0" <?php if ($datosInstitucion['conf_informe_parcial'] == 0) {
                                                                        echo "selected";
                                                                    } ?>>No</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 control-label">Medidas del Logo en los informes (Ancho - Alto)</label>
                                        <div class="col-sm-10">
                                            <input type="text" style="margin-top: 20px;" name="logoAncho" class="col-sm-1" value="<?= $datosInstitucion[30]; ?>">
                                            <input type="text" style="margin-top: 20px;" name="logoAlto" class="col-sm-1" value="<?= $datosInstitucion[31]; ?>">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 control-label">Mostrar Nombre del colegio en el encabezado de los informes</label>
                                        <div class="col-sm-4">
                                            <select class="form-control  select2" name="mostrarNombre">
                                                <option value="1" <?php if ($datosInstitucion[32] == 1) {
                                                                        echo "selected";
                                                                    } ?>>SI</option>
                                                <option value="2" <?php if ($datosInstitucion[32] == 2) {
                                                                        echo "selected";
                                                                    } ?>>NO</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 control-label">Permitir cambiar el usuario de acceso</label>
                                        <div class="col-sm-2">
                                            <select class="form-control  select2" name="cambiarNombreUsuario">
                                                <option value="SI" <?php if ($datosInstitucion['conf_cambiar_nombre_usuario'] == 'SI') {
                                                                        echo "selected";
                                                                    } ?>>SI</option>
                                                <option value="NO" <?php if ($datosInstitucion['conf_cambiar_nombre_usuario'] == 'NO') {
                                                                        echo "selected";
                                                                    } ?>>NO</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 control-label">Permitir a estudiantes cambiar su clave</label>
                                        <div class="col-sm-2">
                                            <select class="form-control  select2" name="cambiarClaveEstudiantes">
                                                <option value="SI" <?php if ($datosInstitucion['conf_cambiar_clave_estudiantes'] == 'SI') {
                                                                        echo "selected";
                                                                    } ?>>SI</option>
                                                <option value="NO" <?php if ($datosInstitucion['conf_cambiar_clave_estudiantes'] == 'NO') {
                                                                        echo "selected";
                                                                    } ?>>NO</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 control-label">Permitir a acudientes descargar el boletín?</label>
                                        <div class="col-sm-8">
                                            <select class="form-control col-sm-2 select2" name="descargarBoletin">
                                                <option value="1" <?php if($datosInstitucion['conf_permiso_descargar_boletin']==1){ echo "selected";} ?>>SI</option>
                                                <option value="0" <?php if($datosInstitucion['conf_permiso_descargar_boletin']==0){ echo "selected";} ?>>NO</option>
                                            </select>
                                            <span style="color:#6017dc;">Esta acción permite a los acudientes descargar el boletín de sus acudidos.</span>
                                        </div>
                                    </div>


                                    <input type="submit" class="btn btn-primary" value="Guardar cambios">&nbsp;
                                </form>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
            <!-- end page content -->
            <?php // include("../compartido/panel-configuracion.php");
            ?>
        </div>
        <!-- end page container -->
        <?php include("../compartido/footer.php"); ?>
    </div>
    <!-- start js include path -->
    <script src="../../config-general/assets/plugins/jquery/jquery.min.js"></script>
    <script src="../../config-general/assets/plugins/popper/popper.js"></script>
    <script src="../../config-general/assets/plugins/jquery-blockui/jquery.blockui.min.js"></script>
    <script src="../../config-general/assets/plugins/jquery-slimscroll/jquery.slimscroll.js"></script>
    <!-- bootstrap -->
    <script src="../../config-general/assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="../../config-general/assets/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
    <script src="../../config-general/assets/plugins/bootstrap-inputmask/bootstrap-inputmask.min.js"></script>
    <script src="../../config-general/assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js" charset="UTF-8"></script>
    <script src="../../config-general/assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker-init.js" charset="UTF-8"></script>
    <script src="../../config-general/assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
    <script src="../../config-general/assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker-init.js" charset="UTF-8"></script>
    <!-- Common js-->
    <script src="../../config-general/assets/js/app.js"></script>
    <script src="../../config-general/assets/js/layout.js"></script>
    <script src="../../config-general/assets/js/theme-color.js"></script>
    <!-- notifications -->
    <script src="../../config-general/assets/plugins/jquery-toast/dist/jquery.toast.min.js"></script>
    <script src="../../config-general/assets/plugins/jquery-toast/dist/toast.js"></script>
    <!-- Material -->
    <script src="../../config-general/assets/plugins/material/material.min.js"></script>
    <!-- dropzone -->
    <script src="../../config-general/assets/plugins/dropzone/dropzone.js"></script>
    <!--tags input-->
    <script src="../../config-general/assets/plugins/jquery-tags-input/jquery-tags-input.js"></script>
    <script src="../../config-general/assets/plugins/jquery-tags-input/jquery-tags-input-init.js"></script>
    <!--select2-->
    <script src="../../config-general/assets/plugins/select2/js/select2.js"></script>
    <script src="../../config-general/assets/js/pages/select2/select2-init.js"></script>
    <!-- end js include path -->
    <script src="../ckeditor/ckeditor.js"></script>

    <script>
        // Replace the <textarea id="editor1"> with a CKEditor 4
        // instance, using default configuration.
        CKEDITOR.replace('editor1');
    </script>
    </body>

    <!-- Mirrored from radixtouch.in/templates/admin/smart/source/light/advance_form.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 18 May 2018 17:32:54 GMT -->

    </html>