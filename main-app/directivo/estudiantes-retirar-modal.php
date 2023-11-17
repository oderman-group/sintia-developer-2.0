<?php


if (!Modulos::validarSubRol([$idPaginaInterna])) {
    echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
    exit();
}
require_once("../class/Estudiantes.php");
require_once("../class/UsuariosPadre.php");

$id = "";
if (!empty($_GET["id"])) {
    $id = base64_decode($_GET["id"]);
}

try {
    $consultaE = mysqli_query($conexion, "SELECT academico_matriculas.*, matret_motivo, matret_fecha, uss_nombre, uss_nombre2, uss_apellido1, uss_apellido2, uss_usuario FROM academico_matriculas
    LEFT JOIN (SELECT * FROM ".BD_ACADEMICA.".academico_matriculas_retiradas WHERE institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]} ORDER BY matret_id DESC LIMIT 1) AS tabla_retiradas ON tabla_retiradas.matret_estudiante=academico_matriculas.mat_id
    LEFT JOIN usuarios ON uss_id=matret_responsable
    WHERE mat_id='" . $id . "'");
} catch (Exception $e) {
    include("../compartido/error-catch-to-report.php");
}
$e = mysqli_fetch_array($consultaE, MYSQLI_BOTH);

$nombreBoton = 'Restaurar Matrícula';
$colorBoton = 'success';
$readonly = "readonly";
$tituloFormulario = 'Restaurar Estudiante';

if ($e['mat_estado_matricula'] == 1) {
    $nombreBoton = 'Retirar y cancelar matrícula';
    $colorBoton = 'danger';
    $readonly = "";
    $tituloFormulario = 'Retirar Estudiante';
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
<?php include("../compartido/body.php"); ?>
<div class="panel">
    <header class="panel-heading panel-heading-purple"><?= $tituloFormulario ?></header>
    <div class="panel-body">

        <form action="estudiantes-retirar-actualizar.php" method="post" class="form-horizontal" enctype="multipart/form-data">
            <input type="hidden" value="<?= $e['mat_id']; ?>" name="estudiante">
            <input type="hidden" value="<?= $e['mat_estado_matricula']; ?>" name="estadoMatricula">


            <div class="form-group row">
                <label class="col-sm-2 control-label">Estudiante</label>

                <div class="col-sm-4">
                    <input type="text" name="nombre" class="form-control" autocomplete="off" value="<?= $e['mat_documento'] . " - " . Estudiantes::NombreCompletoDelEstudiante($e); ?>" readonly>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 control-label">Estado Actual</label>

                <div class="col-sm-4">
                    <input type="text" name="estadoNombre" class="form-control" autocomplete="off" value="<?= $estadosMatriculasEstudiantes[$e['mat_estado_matricula']]; ?>" readonly>
                </div>
            </div>

            <?php if (!empty($e['matret_fecha'])) { ?>
                <div class="form-group row">
                    <label class="col-sm-2 control-label">Última actualización</label>

                    <div class="col-sm-4">
                        <input type="text" name="ultimaActualizacion" class="form-control" autocomplete="off" value="<?= $e['matret_fecha']; ?>" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 control-label">Último responsable</label>

                    <div class="col-sm-4">
                        <input type="text" name="responsable" class="form-control" autocomplete="off" value="<?= $e['uss_usuario'] . " - " . UsuariosPadre::nombreCompletoDelUsuario($e); ?>" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 control-label">Motivo de retiro</label>
                    <div class="col-sm-10">
                        <textarea cols="80" id="editor1" name="motivo" rows="10" <?php echo $readonly; ?>><?= $e['matret_motivo']; ?></textarea>
                    </div>
                </div>
            <?php } else { ?>
                <div class="alert alert-block alert-warning">
                    <p>Este estudiante no tiene historial de retiros.</p>
                </div>
            <?php } ?>

            <input type="submit" class="btn btn-<?= $colorBoton; ?>" value="<?= $nombreBoton; ?>" name="consultas">

        </form>
    </div>
  
    </body>

    <!-- Mirrored from radixtouch.in/templates/admin/smart/source/light/advance_form.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 18 May 2018 17:32:54 GMT -->

    </html>