<?php

if (!Modulos::validarSubRol([$idPaginaInterna])) {
    echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
    exit();
} ?>

<!--select2-->
<link href="../../config-general/assets/plugins/select2/css/select2.css" rel="stylesheet" type="text/css" />
<link href="../../config-general/assets/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />

<div class="panel">
    <header class="panel-heading panel-heading-purple">POR CURSO </header>
    <div class="panel-body">
        <form name="formularioGuardar" action="../compartido/planilla-asistencia.php" method="post" target="_blank">

            <div class="form-group row">
                <label class="col-sm-2 control-label">Curso</label>
                <div class="col-sm-8">
                    <?php
                    try {
                        $opcionesConsulta = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_grados WHERE institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]} ORDER BY gra_vocal");
                    } catch (Exception $e) {
                        include("../compartido/error-catch-to-report.php");
                    }
                    ?>
                    <select class="form-control  select2" name="grado" required>
                        <option value="">Seleccione una opción</option>
                        <?php
                        while ($opcionesDatos = mysqli_fetch_array($opcionesConsulta, MYSQLI_BOTH)) {
                            $disabled = '';
                            if ($opcionesDatos['gra_estado'] == '0') $disabled = 'disabled';
                        ?>
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
                <label class="col-sm-2 control-label">Año</label>
                <div class="col-sm-4">
                    <select class="form-control  select2" name="agno" required>
                        <option value="">Seleccione una opción</option>
                        <?php
                        while ($yearStart <= $yearEnd) {
                            if ($_SESSION["bd"] == $yearStart)
                                echo "<option value='" . $yearStart . "' selected style='color:blue;'>" . $yearStart . "</option>";
                            else
                                echo "<option value='" . $yearStart . "'>" . $yearStart . "</option>";
                            $yearStart++;
                        }
                        ?>
                    </select>
                </div>
            </div>

            <input type="submit" class="btn btn-primary" value="Generar informe">&nbsp;
        </form>
    </div>
</div>
<!--select2-->
<script src="../../config-general/assets/plugins/select2/js/select2.js"></script>
<script src="../../config-general/assets/js/pages/select2/select2-init.js"></script>