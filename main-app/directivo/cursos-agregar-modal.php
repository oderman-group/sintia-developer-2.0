<?php
include("session.php");
$idPaginaInterna = 'DT0065';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");
require_once(ROOT_PATH."/main-app/class/Grados.php");

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


        <form name="formularioGuardar" action="cursos-guardar.php" method="post">

            <div class="form-group row">
                <label class="col-sm-2 control-label">Nombre Curso <span style="color: red;">(*)</span></label>
                <div class="col-sm-10">
                    <input type="text" name="nombreC" class="form-control" required <?= $disabledPermiso; ?>>
                </div>
            </div>

            <?php
            $opcionesConsulta = Grados::listarGrados(1);
            $numCursos = mysqli_num_rows($opcionesConsulta);
            if ($numCursos > 0) {
            ?>
                <div class="form-group row">
                    <label class="col-sm-2 control-label">Curso Siguiente</label>
                    <div class="col-sm-10">
                        <select class="form-control  select2" name="graSiguiente" <?= $disabledPermiso; ?>>
                            <option value="">Seleccione una opción</option>
                            <?php
                            while ($opcionesDatos = mysqli_fetch_array($opcionesConsulta, MYSQLI_BOTH)) {
                            ?>
                                <option value="<?= $opcionesDatos[0]; ?>"><?= strtoupper($opcionesDatos['gra_nombre']); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            <?php } ?>

            <div class="form-group row">
                <label class="col-sm-2 control-label">Valor Matricula</label>
                <div class="col-sm-10">
                    <input type="text" name="valorM" class="form-control" value="0" <?= $disabledPermiso; ?>>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 control-label">Valor Pension</label>
                <div class="col-sm-10">
                    <input type="text" name="valorP" class="form-control" value="0" <?= $disabledPermiso; ?>>
                </div>
            </div>


            <?php if (Modulos::validarPermisoEdicion()) { ?>
                <button type="submit" class="btn  btn-info">
                    <i class="fa fa-save" aria-hidden="true"></i> Guardar cambios 
                </button>
            <?php } ?>
        </form>
    </div>
</div>

<?php include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php"); ?>