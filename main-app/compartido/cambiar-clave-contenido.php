<div class="col-sm-4">
    <div class="panel">
        <header class="panel-heading panel-heading-blue">Instrucciones</header>
        <div class="panel-body">
            <h4>Especificaciones</h4>
            <p><b>1.</b> Debe tener entre 8 y 20 caracteres.</p>
            <p><b>2.</b> Solo se admiten caracteres de la a-z, A-Z, números(0-9) y los siguientes simbolos <mark>(. y $)</mark>.</p>
        </div>
    </div>

    
</div>  

<div class="col-sm-8">
    <?php include("../../config-general/mensajes-informativos.php"); ?>
    <div class="card card-box">
        <div class="card-head">
            <header><?=$frases[253][$datosUsuarioActual[8]];?></header>
        </div>
        <div class="card-body " id="bar-parent6">
            <form action="../compartido/clave-actualizar.php" method="post" enctype="multipart/form-data">

                <div class="form-group row">
                    <label class="col-sm-2 control-label">Contraseña actual</label>
                    <div class="col-sm-4">
                        <input type="password" name="claveActual" class="form-control" required>
                    </div>
                </div>
                
                <div class="form-group row">
                    <label class="col-sm-2 control-label">Contraseña nueva</label>
                    <div class="col-sm-4">
                        <input type="password" name="claveNueva" class="form-control" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 control-label">Confirmar contraseña nueva</label>
                    <div class="col-sm-4">
                        <input type="password" name="claveNuevaDos" class="form-control" required>
                    </div>
                </div>

                <button type="submit" class="btn  btn-info">
                    <i class="fa fa-save" aria-hidden="true"></i> Guardar cambios 
                </button>
            </form>
        </div>
    </div>
</div>