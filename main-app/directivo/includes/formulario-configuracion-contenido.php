
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
            <header class="panel-heading panel-heading-purple">General</header>
            <div class="panel-body">
                <form name="formularioGuardar" action="configuracion-sistema-guardar.php" method="post">
                    <input type="hidden" name="configDEV" value="<?= $configDEV; ?>">
                    <input type="hidden" name="id" value="<?= $datosConfiguracion['conf_id']; ?>">
                    <input type="hidden" name="configTab" value="<?=BDT_Configuracion::CONFIG_SISTEMA_GENERAL;?>">

                    <div class="form-group row">
                        <label class="col-sm-2 control-label">Año Actual</label>
                        <div class="col-sm-8">
                            <input type="text" name="agno" class="form-control col-sm-2" value="<?=$year;?>" readonly <?=$disabledPermiso;?>>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 control-label">Periodo Actual <span style="color: red;">(*)</span>
                            <button type="button" class="btn btn-sm" data-toggle="tooltip" data-placement="right" title="Este valor solo se verá reflejado en los informes que obtienen los directivos."><i class="fa fa-info"></i></button>    
                        </label>
                        <div class="col-sm-3">
                            <select class="form-control  select2" name="periodo" required <?=$disabledPermiso;?>>
                                <option value="">Seleccione una opción</option>
                                <?php
                                $p = 1;
                                $pFinal = $config['conf_periodos_maximos'] + 1;
                                while($p <= $pFinal){
                                    $label = 'Periodo '.$p;
                                    if($p == $pFinal) {
                                        $label = 'AÑO FINALIZADO';
                                    }

                                    if($p == $datosConfiguracion['conf_periodo'])
                                        echo '<option value="'.$p.'" selected>'.$label.'</option>';
                                    else
                                        echo '<option value="'.$p.'">'.$label.'</option>';	
                                    $p++;
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <?php $botones = new botonesGuardar("dev-instituciones.php",Modulos::validarPermisoEdicion() || $datosUsuarioActual['uss_tipo'] == TIPO_DEV); ?>
                </form>
            </div>
        </div>
    </div>
</div>