<?php
require_once("../class/Estudiantes.php");

if (!Modulos::validarSubRol([$idPaginaInterna])) {
    echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
    exit();
}
?>


<?php
    $ext = '';
    if ($config['conf_certificado'] == 2) {
        $ext = '-2';
    }
    ?>
    <div class="panel">
        <header class="panel-heading panel-heading-purple">Certificado por áreas</header>
        <div class="panel-body">
            <form action="../compartido/matricula-certificado-areas<?= $ext; ?>.php" method="post" class="form-horizontal" enctype="multipart/form-data" target="_blank">

                <div class="form-group row">
                    <label class="col-sm-2 control-label">Estudiante</label>
                    <div class="col-sm-9">
                        <select class="form-control  select2" name="id" required>
                            <option value=""></option>
                            <?php
                            $c = Estudiantes::listarEstudiantesEnGrados('', '');
                            while ($r = mysqli_fetch_array($c, MYSQLI_BOTH)) {
                            ?>
                                <option value="<?php echo $r['mat_id']; ?>"><?= Estudiantes::NombreCompletoDelEstudiante($r) ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 control-label">Desde que año</label>
                    <div class="col-sm-9">
                        <select class="form-control  select2" name="desde" required>
                            <option value=""></option>
                            <?php
                            $yearStartTemp=$yearStart;
                            while ($yearStartTemp <= $yearEnd) {
                                if ($_SESSION["bd"] == $yearStartTemp)
                                    echo "<option value='" . $yearStartTemp . "' selected style='color:blue;'>" . $yearStartTemp . "</option>";
                                else
                                    echo "<option value='" . $yearStartTemp . "'>" . $yearStartTemp . "</option>";
                                $yearStartTemp++;
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 control-label">Hasta que año</label>
                    <div class="col-sm-9">
                        <select class="form-control  select2" name="hasta" required>
                            <option value=""></option>
                            <?php
                            $yearStartTemp = $yearArray[0];
                            while ($yearStartTemp <= $yearEnd) {
                                if ($_SESSION["bd"] == $yearStartTemp)
                                    echo "<option value='" . $yearStartTemp . "' selected style='color:blue;'>" . $yearStartTemp . "</option>";
                                else
                                    echo "<option value='" . $yearStartTemp . "'>" . $yearStartTemp . "</option>";
                                $yearStartTemp++;
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <input type="submit" class="btn btn-primary" value="Generar Certificado">
            </form>
        </div>
    </div>

    <div class="panel">
        <header class="panel-heading panel-heading-purple">Certificado por materias</header>
        <div class="panel-body">
            <form action="../compartido/matricula-certificado.php" method="post" class="form-horizontal" enctype="multipart/form-data" target="_blank">

                <div class="form-group row">
                    <label class="col-sm-2 control-label">Estudiante</label>
                    <div class="col-sm-9">
                        <select class="form-control  select2" name="id" required>
                            <option value=""></option>
                            <?php
                            $c = Estudiantes::listarEstudiantesEnGrados('', '');
                            while ($r = mysqli_fetch_array($c, MYSQLI_BOTH)) {
                            ?>
                                <option value="<?php echo $r['mat_id']; ?>"><?= Estudiantes::NombreCompletoDelEstudiante($r) ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 control-label">Desde que año</label>
                    <div class="col-sm-9">
                        <select class="form-control  select2" name="desde" required>
                            <option value=""></option>
                            <?php
                            $yearStartTemp = $yearArray[0];
                            while ($yearStartTemp <= $yearEnd) {
                                if ($_SESSION["bd"] == $yearStartTemp)
                                    echo "<option value='" . $yearStartTemp . "' selected style='color:blue;'>" . $yearStartTemp . "</option>";
                                else
                                    echo "<option value='" . $yearStartTemp . "'>" . $yearStartTemp . "</option>";
                                $yearStartTemp++;
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 control-label">Hasta que año</label>
                    <div class="col-sm-9">
                        <select class="form-control  select2" name="hasta" required>
                            <option value=""></option>
                            <?php
                            $yearStartTemp = $yearArray[0];
                            while ($yearStartTemp <= $yearEnd) {
                                if ($_SESSION["bd"] == $yearStartTemp)
                                    echo "<option value='" . $yearStartTemp . "' selected style='color:blue;'>" . $yearStartTemp . "</option>";
                                else
                                    echo "<option value='" . $yearStartTemp . "'>" . $yearStartTemp . "</option>";
                                $yearStartTemp++;
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <input type="submit" class="btn btn-primary" value="Generar Certificado">
            </form>
        </div>
    </div>