<?php

if (!Modulos::validarSubRol([$idPaginaInterna])) {
    echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
    exit();
}

$disabledPermiso = "";
if (!Modulos::validarPermisoEdicion()) {
    $disabledPermiso = "disabled";
} ?>

<!--select2-->
<link href="../../config-general/assets/plugins/select2/css/select2.css" rel="stylesheet" type="text/css" />
<link href="../../config-general/assets/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />

<div class="panel">
    <header class="panel-heading panel-heading-purple"><?= $frases[119][$datosUsuarioActual[8]]; ?> </header>
    <div class="panel-body">


        <form name="formularioGuardar" action="asignaturas-guardar.php" method="post" enctype="multipart/form-data">

            <div class="form-group row">
                <label class="col-sm-2 control-label">Nombre de la Asignatura <span style="color: red;">(*)</span></label>
                <div class="col-sm-8">
                    <input type="text" name="nombreM" class="form-control" onchange="generarSiglas(this)" <?= $disabledPermiso; ?>>
                </div>
            </div>

            <script type="text/javascript">
                function generarSiglas(datos) {
                    var asignatura = datos.value;
                    var siglas = asignatura.substring(0, 3);
                    document.getElementById("siglasM").value = siglas.toUpperCase();
                }
            </script>

            <div class="form-group row">
                <label class="col-sm-2 control-label">Nombre corto, Abreviatura o Siglas de la asignatura</label>
                <div class="col-sm-4">
                    <input type="text" name="siglasM" id="siglasM" class="form-control" <?= $disabledPermiso; ?>>
                    <span style="color: #6017dc;">Este valor se usa para mostrar de forma abreviada el nombre de la asignatura en algunos informes.</span>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 control-label">Área académica a la cual pertenece esta asignatura <span style="color: red;">(*)</span></label>
                <div class="col-sm-8">
                    <select class="form-control  select2" name="areaM" required <?= $disabledPermiso; ?>>
                        <option value="">Seleccione una opción</option>
                        <?php
                        try {
                            $cAreas = mysqli_query($conexion, "SELECT ar_id, ar_nombre, ar_posicion FROM academico_areas;");
                        } catch (Exception $e) {
                            include("../compartido/error-catch-to-report.php");
                        }
                        while ($rA = mysqli_fetch_array($cAreas, MYSQLI_BOTH)) {
                            echo '<option value="' . $rA["ar_id"] . '">' . $rA["ar_nombre"] . '</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>

            <?php if ($config['conf_agregar_porcentaje_asignaturas'] == 'SI') { ?>
                <div class="form-group row">
                    <label class="col-sm-2 control-label">Porcentaje</label>
                    <div class="col-sm-4">
                        <input type="text" name="porcenAsigna" id="porcenAsigna" class="form-control" <?= $disabledPermiso; ?>>
                    </div>
                </div>
            <?php } ?>


            <?php if (Modulos::validarPermisoEdicion()) { ?>
                <input type="submit" class="btn btn-primary" value="Guardar cambios">&nbsp;
            <?php } ?>
        </form>
    </div>
</div>