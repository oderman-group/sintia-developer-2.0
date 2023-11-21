<?php

if (!Modulos::validarSubRol([$idPaginaInterna])) {
    echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
    exit();
} ?>


<!--select2-->
<link href="../../config-general/assets/plugins/select2/css/select2.css" rel="stylesheet" type="text/css" />
<link href="../../config-general/assets/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
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
                            $c = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_grados WHERE institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
                        } catch (Exception $e) {
                            include("../compartido/error-catch-to-report.php");
                        }
                        while ($r = mysqli_fetch_array($c, MYSQLI_BOTH)) {
                        ?>
                            <option value="<?php echo $r['gra_id']; ?>"><?php echo $r['gra_nombre']; ?></option>
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
                            $c = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_grupos WHERE institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
                        } catch (Exception $e) {
                            include("../compartido/error-catch-to-report.php");
                        }
                        while ($r = mysqli_fetch_array($c, MYSQLI_BOTH)) {
                        ?>
                            <option value="<?php echo $r['gru_id']; ?>"><?php echo $r['gru_nombre']; ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
            </div>

            <input type="submit" class="btn btn-info" value="Consultar Informe" name="consultas">
        </form>
    </div>
</div>
<!--select2-->
<script src="../../config-general/assets/plugins/select2/js/select2.js"></script>
<script src="../../config-general/assets/js/pages/select2/select2-init.js"></script>