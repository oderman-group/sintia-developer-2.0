<?php $idPaginaInterna = 'DT0100'; ?>
<?php

if (!Modulos::validarSubRol([$idPaginaInterna])) {
    echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
    exit();
}
require_once("../class/Estudiantes.php");
?>

<!--select2-->
<link href="../../config-general/assets/plugins/select2/css/select2.css" rel="stylesheet" type="text/css" />
<link href="../../config-general/assets/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
<style>
	.select2 {
        width: 100% !important;
    }
</style>

<!-- END HEAD -->
<div class="col-sm-12">
    <?php include("../../config-general/mensajes-informativos.php"); ?>
    <div class="panel">
        <header class="panel-heading panel-heading-purple">POR CURSO </header>
        <div class="panel-body">
            <form name="formularioGuardar" action="informes-formato-boletin.php" method="post" target="_blank">
                <div class="form-group row">
                    <label class="col-sm-2 control-label">Curso</label>
                    <div class="col-sm-8">
                        <?php
                        try {
                            $opcionesConsulta = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_grados WHERE gra_estado=1 AND gra_tipo='".GRADO_GRUPAL."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]} ORDER BY gra_vocal");
                        } catch (Exception $e) {
                            include("../compartido/error-catch-to-report.php");
                        }
                        ?>
                        <select class="form-control  select2"  name="curso" required>
                            <option value="">Seleccione una opción</option>
                            <?php
                            while ($opcionesDatos = mysqli_fetch_array($opcionesConsulta, MYSQLI_BOTH)) {
                                $disabled = '';
                                if ($opcionesDatos['gra_estado'] == '0') $disabled = 'disabled'; ?>
                                <option value="<?= $opcionesDatos['gra_id']; ?>" <?= $disabled; ?>><?= $opcionesDatos['gra_id'] . ". " . strtoupper($opcionesDatos['gra_nombre']); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 control-label">Grupo</label>
                    <div class="col-sm-4">
                        <?php
                        try {
                            $opcionesConsulta = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_grupos WHERE institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
                        } catch (Exception $e) {
                            include("../compartido/error-catch-to-report.php");
                        }
                        ?>
                        <select class="form-control  select2" name="grupo">
                            <option value="">Seleccione una opción</option>
                            <?php
                            while ($opcionesDatos = mysqli_fetch_array($opcionesConsulta, MYSQLI_BOTH)) {
                            ?>
                                <option value="<?= $opcionesDatos['gru_id']; ?>"><?= $opcionesDatos['gru_id'] . ". " . strtoupper($opcionesDatos['gru_nombre']); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>


                <div class="form-group row">
                    <label class="col-sm-2 control-label">Periodo</label>
                    <div class="col-sm-4">
                        <select class="form-control  select2" name="periodo" required>
                            <option value="">Seleccione una opción</option>
                            <?php
                            $p = 1;
                            while ($p <= $config[19]) {
                                echo '<option value="' . $p . '">Periodo ' . $p . '</option>';
                                $p++;
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 control-label">Año</label>
                    <div class="col-sm-4">
                        <select class="form-control  select2" name="year" required>
                            <option value="">Seleccione una opción</option>
                            <?php
                            $yearStartC = $yearStart;
                            $yearEndC = $yearEnd;
                            while ($yearStartC <= $yearEndC) {
                                if ($_SESSION["bd"] == $yearStartC)
                                    echo "<option value='" . $yearStartC . "' selected style='color:blue;'>" . $yearStartC . "</option>";
                                else
                                    echo "<option value='" . $yearStartC . "'>" . $yearStartC . "</option>";
                                $yearStartC++;
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <input type="submit" class="btn btn-primary" value="Generar Boletin">&nbsp;

            </form>
        </div>
    </div>
    <div class="panel">
        <header class="panel-heading panel-heading-red">POR ESTUDIANTE </header>
        <div class="panel-body">
            <form name="formularioGuardar" action="informes-formato-boletin.php" method="post" target="_blank">
                <div class="form-group row">
                    <label class="col-sm-2 control-label">Estudiante</label>
                    <div class="col-sm-8">

                        <select id="selectEstudiantes" class="form-control  select2" name="estudiante" multiple required>
                            <option value="">Seleccione una opción</option>
                            <?php
                            try {
                                $grados = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_grados WHERE gra_estado=1 AND gra_tipo='".GRADO_GRUPAL."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]} ORDER BY gra_vocal ");
                            } catch (Exception $e) {
                                include("../compartido/error-catch-to-report.php");
                            }
                            while ($grado = mysqli_fetch_array($grados, MYSQLI_BOTH)) {
                            ?>

                                <optgroup label="<?= $grado['gra_nombre']; ?>">
                                    <?php
                                    $filtro = ' AND mat_grado=' . $grado['gra_id'];
                                    $opcionesConsulta = Estudiantes::listarEstudiantesEnGrados($filtro, '');
                                    while ($opcionesDatos = mysqli_fetch_array($opcionesConsulta, MYSQLI_BOTH)) {
                                    ?>

                                        <option value="<?= $opcionesDatos[0]; ?>">
                                            <?= "[" . $opcionesDatos['mat_id'] . "] " . strtoupper($opcionesDatos['mat_primer_apellido'] . " " . $opcionesDatos['mat_segundo_apellido'] . " " . $opcionesDatos['mat_nombres'] . " " . $opcionesDatos['mat_nombre2']); ?>
                                            - <?= strtoupper($opcionesDatos['gra_nombre'] . " " . $opcionesDatos['gru_nombre']); ?>
                                        </option>

                                    <?php } ?>

                                </optgroup>
                            <?php } ?>

                        </select>
                        <span style="color: darkblue;">Seleccione solo una opción de este listado.</span>
                    </div>
                </div>


                <div class="form-group row">
                    <label class="col-sm-2 control-label">Periodo</label>
                    <div class="col-sm-4">
                        <select class="form-control  select2" name="periodo" required>
                            <option value="">Seleccione una opción</option>
                            <?php
                            $p = 1;
                            while ($p <= $config[19]) {
                                echo '<option value="' . $p . '">Periodo ' . $p . '</option>';
                                $p++;
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 control-label">Año</label>
                    <div class="col-sm-4">
                        <select class="form-control  select2" name="year" required>
                            <option value="">Seleccione una opción</option>
                            <?php
                            $yearStartE = $yearStart;
                            $yearEndE = $yearEnd;
                            while ($yearStartE <= $yearEndE) {
                                if ($_SESSION["bd"] == $yearStartE)
                                    echo "<option value='" . $yearStartE . "' selected style='color:blue;'>" . $yearStartE . "</option>";
                                else
                                    echo "<option value='" . $yearStartE . "'>" . $yearStartE . "</option>";
                                $yearStartE++;
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <input type="submit" class="btn btn-primary" value="Generar Boletin">&nbsp;
            </form>
        </div>
    </div>
</div>

<script>
// Agregar el evento onchange al select
var miSelect = document.getElementById('selectEstudiantes');
miSelect.onchange = function() {
    limitarSeleccion(this);
};
</script>



