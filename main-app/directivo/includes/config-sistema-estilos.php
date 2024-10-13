
<div class="row">

    <div class="col-sm-12">
        <?php
        include("../../config-general/mensajes-informativos.php");
        if($idPaginaInterna == 'DV0032'){
            include("includes/barra-superior-dev-instituciones-configuracion-informacion.php");
        }
        ?>
        <br>
        <div class="panel">
            <header class="panel-heading panel-heading-purple">Estilos y apariencia</header>
            <div class="panel-body">
                <form name="formularioGuardar" action="configuracion-sistema-guardar.php" method="post">
                    <input type="hidden" name="configDEV" value="<?= $configDEV; ?>">
                    <input type="hidden" name="id" value="<?= $datosConfiguracion['conf_id']; ?>">
                    <input type="hidden" name="configTab" value="<?=BDT_Configuracion::CONFIG_SISTEMA_ESTILOS;?>">

                    <div class="form-group row">
                        <label class="col-sm-3 control-label">Color de las notas (Perdidas -  Ganadas) <span style="color: red;">(*)</span></label>
                        <div class="col-sm-9">
                            <input type="color"style="margin-top: 20px;" name="perdida" class="col-sm-1" value="<?=$datosConfiguracion['conf_color_perdida'];?>" <?=$disabledPermiso;?>>
                            <input type="color"style="margin-top: 20px;" name="ganada" class="col-sm-1" value="<?=$datosConfiguracion['conf_color_ganada'];?>" <?=$disabledPermiso;?>>
                        </div>
                    </div>

                    <?php $botones = new botonesGuardar("dev-instituciones.php",Modulos::validarPermisoEdicion() || $datosUsuarioActual['uss_tipo'] == TIPO_DEV); ?>
                </form>
            </div>
        </div>
    </div>
</div>