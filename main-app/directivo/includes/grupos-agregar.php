<div class="panel">
    <header class="panel-heading panel-heading-purple"><?= $frases[119][$datosUsuarioActual[8]]; ?> </header>
    <div class="panel-body">


        <form name="formularioGuardar" action="grupos-guardar.php" method="post">
            <?php
            if (!is_null($id)) {
                $accion = ACTUALIZAR;
                $grupoActual = Grupos::obtenerGrupo($id);
                echo "<input type='hidden' name='accion' value='$accion'>";
                echo "<input type='hidden' name='id' value='$id'>";
            } else {
                $accion = GUARDAR;
            }; 
           
            ?>
            <div class="form-group row">
                <label class="col-sm-2 control-label">Codigo Gupo <span style="color: red;">(*)</span></label>
                <div class="col-sm-10">
                    <input type="text" value="<?= $grupoActual['gru_codigo']; ?>" name="codigoG" class="form-control" required>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 control-label">Nombre Gupo <span style="color: red;">(*)</span></label>
                <div class="col-sm-10">
                    <input type="text" value="<?= $grupoActual['gru_nombre']; ?>" name="nombreG" class="form-control" required>
                </div>
            </div>


            <input type="submit" class="btn btn-primary" value="Guardar cambios">&nbsp;
            <a href="#" name="grupos.php" class="btn btn-secondary" onClick="deseaRegresar(this)"><i class="fa fa-long-arrow-left"></i>Regresar</a>
        </form>
    </div>