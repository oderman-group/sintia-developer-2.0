<?php

if (!Modulos::validarSubRol([$idPaginaInterna])) {
    echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
    exit();
} ?>

<div class="panel">
    <header class="panel-heading panel-heading-purple">POR CURSO </header>
    <div class="panel-body">
        <form name="formularioGuardar" action="../compartido/listado-estudiante.php" method="post" target="_blank">

            <div class="form-group row">
                <label class="col-sm-2 control-label">Curso</label>
                <div class="col-sm-8">
                    <?php
                    try {
                        $opcionesConsulta = mysqli_query($conexion, "SELECT * FROM academico_grados
                                                ORDER BY gra_vocal");
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
                            <option value="<?= $opcionesDatos[0]; ?>" <?= $disabled; ?>><?= $opcionesDatos['gra_id'] . ". " . strtoupper($opcionesDatos['gra_nombre']); ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 control-label">Grupo</label>
                <div class="col-sm-4">
                    <?php
                    try {
                        $opcionesConsulta = mysqli_query($conexion, "SELECT * FROM academico_grupos");
                    } catch (Exception $e) {
                        include("../compartido/error-catch-to-report.php");
                    }
                    ?>
                    <select class="form-control  select2" name="grupo">
                        <option value="">Seleccione una opción</option>
                        <?php
                        while ($opcionesDatos = mysqli_fetch_array($opcionesConsulta, MYSQLI_BOTH)) {
                        ?>
                            <option value="<?= $opcionesDatos[0]; ?>"><?= $opcionesDatos['gru_id'] . ". " . strtoupper($opcionesDatos['gru_nombre']); ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 control-label">Estados de matricula</label>
                <div class="col-sm-4">
                    <select class="form-control  select2" name="estadoM">
                        <option value="">Seleccione una opción</option>
                        <option value="1">Solo matrículados</option>
                        <option value="2">Todos</option>
                    </select>
                </div>
            </div>

            <input type="submit" class="btn btn-primary" value="Generar informe">&nbsp;
        </form>
    </div>
</div>