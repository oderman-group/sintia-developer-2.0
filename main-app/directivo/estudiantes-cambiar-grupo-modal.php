<?php

if (!Modulos::validarSubRol([$idPaginaInterna])) {
    echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
    exit();
}
require_once("../class/Estudiantes.php");
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

<style>
    .select2 {
        width: 100% !important;
    }
</style>
<?php
$id = "";
if (!empty($_GET["id"])) {
    $id = base64_decode($_GET["id"]);
}
$e = Estudiantes::obtenerDatosEstudiante($id);
?>

<div class="panel">
    <header class="panel-heading panel-heading-purple">Cambiar de grupo</header>
    <div class="panel-body">


        <form action="estudiantes-cambiar-grupo-estudiante.php" method="post" class="form-horizontal" enctype="multipart/form-data">
            <input type="hidden" value="<?= $e[0]; ?>" name="estudiante">


            <div class="form-group row">
                <label class="col-sm-2 control-label">Estudiante</label>
                <div class="col-sm-1">
                    <input type="text" name="codigoE" class="form-control" autocomplete="off" value="<?= $e['mat_id']; ?>" readonly>
                </div>

                <div class="col-sm-9">
                    <input type="text" name="nombre" class="form-control" autocomplete="off" value="<?= Estudiantes::NombreCompletoDelEstudiante($e); ?>" readonly>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 control-label">Curso</label>

                <?php
                $gradoActual = Grados::obtenerGrado($e["mat_grado"]);
                ?>
                <div class="col-sm-1">
                    <input type="text" name="cursoNuevo" class="form-control" autocomplete="off" value="<?= $gradoActual["gra_id"] ?>" readonly>
                </div>
                <div class="col-sm-9">
                    <input type="text" class="form-control" autocomplete="off" value="<?= $gradoActual["gra_nombre"] ?>" readonly>
                </div>

            </div>

            <div class="form-group row">
                <label class="col-sm-2 control-label">Grupo</label>
                <?php
                try {
                    $consulta_cargas = mysqli_query($conexion, "SELECT * FROM academico_grupos");
                } catch (Exception $e) {
                    include("../compartido/error-catch-to-report.php");
                }
                ?>
                <div class="col-sm-9">
                    <select class="form-control  select2" name="grupoNuevo" required>
                        <option value="0"></option>
                        <?php
                        while ($c = mysqli_fetch_array($consulta_cargas, MYSQLI_BOTH)) {
                            if ($c["gru_id"] == $e[7])
                                echo '<option value="' . $c["gru_id"] . '" selected style="color:blue; font-weight:bold;">Actual: ' . $c["gru_nombre"] . '</option>';
                            else
                                echo '<option value="' . $c["gru_id"] . '">' . $c["gru_nombre"] . '</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>

            <input type="submit" class="btn btn-success" value="Hacer cambio" name="consultas">
        </form>
    </div>
</div>