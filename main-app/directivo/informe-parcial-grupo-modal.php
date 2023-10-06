<?php

if (!Modulos::validarSubRol([$idPaginaInterna])) {
    echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
    exit();
} ?>

<div class="panel">
    <header class="panel-heading panel-heading-purple">Informe parcial</header>
    <div class="panel-body">


        <form action="../compartido/informe-parcial-grupo-detalle.php" method="post" class="form-horizontal" enctype="multipart/form-data" target="_blank">

            <div class="form-group row">
                <label class="col-sm-2 control-label">Curso</label>
                <div class="col-sm-10">
                    <select class="form-control  select2" name="curso">
                        <option value=""></option>
                        <?php
                        try {
                            $c = mysqli_query($conexion, "SELECT * FROM academico_grados");
                        } catch (Exception $e) {
                            include("../compartido/error-catch-to-report.php");
                        }
                        while ($r = mysqli_fetch_array($c, MYSQLI_BOTH)) {
                        ?>
                            <option value="<?php echo $r[0]; ?>"><?php echo $r[2]; ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 control-label">Grupos</label>
                <div class="col-sm-10">
                    <select class="form-control  select2" name="grupo">
                        <option value=""></option>
                        <?php
                        try {
                            $c = mysqli_query($conexion, "SELECT * FROM academico_grupos");
                        } catch (Exception $e) {
                            include("../compartido/error-catch-to-report.php");
                        }
                        while ($r = mysqli_fetch_array($c, MYSQLI_BOTH)) {
                        ?>
                            <option value="<?php echo $r[0]; ?>"><?php echo $r[2]; ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
            </div>

            <input type="submit" class="btn btn-success" value="Consultar Informe" name="consultas">
        </form>
    </div>
</div>