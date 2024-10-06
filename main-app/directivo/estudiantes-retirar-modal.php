<?php
$idPaginaInterna = 'DT0074';

if (empty($_SESSION["id"])) {
    include("session.php");
    $input = json_decode(file_get_contents("php://input"), true);
    if (!empty($input)) {
        $_GET = $input;
    }
}

if (!Modulos::validarSubRol([$idPaginaInterna])) {
    echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
    exit();
}
require_once(ROOT_PATH . "/main-app/class/Estudiantes.php");
require_once(ROOT_PATH . "/main-app/class/UsuariosPadre.php");

$id = "";
if (!empty($_GET["id"])) {
    $id = base64_decode($_GET["id"]);
}

$e = Estudiantes::traerDatosEstudiantesretirados($conexion, $config, $id);

$nombreBoton = 'Restaurar Matrícula';
$colorBoton = 'success';
$readonly = "readonly";
$tituloFormulario = 'Restaurar Estudiante';

if ($e['mat_estado_matricula'] == MATRICULADO || $e['mat_estado_matricula'] == ASISTENTE || $e['mat_estado_matricula'] == NO_MATRICULADO || $e['mat_estado_matricula'] == EN_INSCRIPCION) {
    $nombreBoton = 'Retirar y cancelar matrícula';
    $colorBoton = 'danger';
    $readonly = "";
    $tituloFormulario = 'Retirar Estudiante';
}
?>
<form action="estudiantes-retirar-actualizar.php" method="post" class="form-horizontal" enctype="multipart/form-data" id="form-<?= $idModal ?>">
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
    <?php } else { ?>
        <div class="alert alert-block alert-warning">
            <p>Este estudiante no tiene historial de retiros.</p>
        </div>
    <?php } ?>

    <?php if ($e['mat_estado_matricula'] == MATRICULADO || $e['mat_estado_matricula'] == ASISTENTE || $e['mat_estado_matricula'] == NO_MATRICULADO || $e['mat_estado_matricula'] == EN_INSCRIPCION || !empty($e['matret_fecha'])) { ?>
        <div class="form-group row">
            <label class="col-sm-2 control-label">Motivo de retiro</label>
            <div class="col-sm-10">
                <textarea cols="80" id="editor1" name="motivo" rows="10" <?php echo $readonly; ?>><?= $e['matret_motivo']; ?></textarea>
            </div>
        </div>
    <?php } ?>

    <input type="submit" class="btn btn-<?= $colorBoton; ?>" value="<?= $nombreBoton; ?>" name="consultas">

</form>
<p>&nbsp;</p>
<div class="form-group row" style="margin-left: 20px;">
    <?php
    $consultaHistorial = Estudiantes::listarDatosEstudiantesretirados($conexion, $config, $id);
    $numHistorial = mysqli_num_rows($consultaHistorial);
    if ($numHistorial > 0) {
        $cont = 1;
        echo "<span style='font-weight:bold;'>Historial de retiro:</span><br><br>";
        while ($datosHistorial = mysqli_fetch_array($consultaHistorial)) {
            $motivo = str_replace(['<p>', '</p>'], '', $datosHistorial['matret_motivo']);

            echo $cont . ") Fecha: " . $datosHistorial['matret_fecha'] . " Motivo: " . $motivo . " Responsable: " . UsuariosPadre::nombreCompletoDelUsuario($datosHistorial) . "<br>";

            $cont++;
        }
    }
    ?>
</div>