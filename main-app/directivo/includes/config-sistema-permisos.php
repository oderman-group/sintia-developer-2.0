
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
            <header class="panel-heading panel-heading-purple">Permisos</header>
            <div class="panel-body">
                <form name="formularioGuardar" action="configuracion-sistema-guardar.php" method="post">
                    <input type="hidden" name="configDEV" value="<?= $configDEV; ?>">
                    <input type="hidden" name="id" value="<?= $datosConfiguracion['conf_id']; ?>">
                    <input type="hidden" name="configTab" value="<?=BDT_Configuracion::CONFIG_SISTEMA_PERMISOS;?>">

                    <?php if($datosUsuarioActual['uss_tipo'] == TIPO_DEV){ ?>
                        <p class="h3">Directivos</p>

                        <div class="form-group row">
                            <label class="col-sm-3 control-label">Permitir cambiar el usuario de acceso <span style="color: red;">(*)</span></label>
                            <div class="col-sm-2">
                                <select class="form-control  select2" name="cambiarNombreUsuario">
                                    <option value="SI" <?php if ($datosConfiguracion['conf_cambiar_nombre_usuario'] == 'SI') {
                                                            echo "selected";
                                                        } ?>>SI</option>
                                    <option value="NO" <?php if ($datosConfiguracion['conf_cambiar_nombre_usuario'] == 'NO') {
                                                            echo "selected";
                                                        } ?>>NO</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 control-label">Editar información en años anteriores <span style="color: red;">(*)</span>
                            <button type="button" class="btn btn-sm" data-toggle="tooltip" data-placement="right" title="Esta acción permite a los directivos editar registros en años anteriores al actual."><i class="fa fa-info"></i></button></label>
                            <div class="col-sm-8">
                                <select class="form-control col-sm-2 select2" name="editarInfoYears">
                                    <option value="1" <?php if($datosConfiguracion['conf_permiso_edicion_years_anteriores']==1){ echo "selected";} ?>>SI</option>
                                    <option value="0" <?php if($datosConfiguracion['conf_permiso_edicion_years_anteriores']==0){ echo "selected";} ?>>NO</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 control-label">Doble buscador en listado de registros <span style="color: red;">(*)</span>
                            <button type="button" class="btn btn-sm" data-toggle="tooltip" data-placement="right" title="Esta acción permite que las instituciones tengan doble buscador en paginas donde se listan los registros."><i class="fa fa-info"></i></button></label>
                            <div class="col-sm-2">
                                <select class="form-control  select2" name="dobleBuscador">
                                    <option value="SI" <?php if ($datosConfiguracion['conf_doble_buscador'] == 'SI') {
                                                            echo "selected";
                                                        } ?>>SI</option>
                                    <option value="NO" <?php if ($datosConfiguracion['conf_doble_buscador'] == 'NO') {
                                                            echo "selected";
                                                        } ?>>NO</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 control-label">Permiso de actualizar las definitivas en consolidado final <span style="color: red;">(*)</span>
                            <button type="button" class="btn btn-sm" data-toggle="tooltip" data-placement="right" title="Esta acción permite o no actualizar las definitivas, en el consolidado final, en cualquier momento."><i class="fa fa-info"></i></button> 
                            </label>
                            <div class="col-sm-8">
                                <select class="form-control col-sm-2 select2" name="permisoConsolidado" <?=$disabledPermiso;?>>
                                    <option value="1" <?php if($datosConfiguracion['conf_editar_definitivas_consolidado']==1){ echo "selected";} ?>>SI</option>
                                    <option value="0" <?php if($datosConfiguracion['conf_editar_definitivas_consolidado']==0){ echo "selected";} ?>>NO</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 control-label">Permiso de eliminar cargas académicas <span style="color: red;">(*)</span></label>
                            <div class="col-sm-8">
                                <select class="form-control col-sm-2 select2" name="permisoEliminarCargas" <?=$disabledPermiso;?>>
                                    <option value="SI" <?php if($datosConfiguracion['conf_permiso_eliminar_cargas'] == 'SI'){ echo "selected";} ?>>SI</option>
                                    <option value="NO" <?php if($datosConfiguracion['conf_permiso_eliminar_cargas'] == 'NO'){ echo "selected";} ?>>NO</option>
                                </select>
                            </div>
                        </div>
                    <?php }else{ ?>
                        <input type="hidden" name="cambiarNombreUsuario" value="<?= $datosConfiguracion['conf_cambiar_nombre_usuario']; ?>">
                        <input type="hidden" name="editarInfoYears" value="<?= $datosConfiguracion['conf_permiso_edicion_years_anteriores']; ?>">
                        <input type="hidden" name="dobleBuscador" value="<?= $datosConfiguracion['conf_doble_buscador']; ?>">
                        <input type="hidden" name="permisoEliminarCargas" value="<?= $datosConfiguracion['conf_permiso_eliminar_cargas']; ?>">
                    <?php } ?>

                    <p class="h3">Docentes</p>

                    <div class="form-group row">
                        <label class="col-sm-3 control-label">Permitir a docentes ver puestos en el informe de sábanas <span style="color: red;">(*)</span>
                        <button type="button" class="btn btn-sm" data-toggle="tooltip" data-placement="right" title="Esta acción permite o no a los docentes ver el listado de los puestos de los estudiantes, por periodo, en el informe de sábanas."><i class="fa fa-info"></i></button> 
                        </label>
                        <div class="col-sm-8">
                            <select class="form-control col-sm-2 select2" name="permisoDocentesPuestosSabanas" <?=$disabledPermiso;?>>
                                <option value="1" <?php if($datosConfiguracion['conf_ver_promedios_sabanas_docentes']==1){ echo "selected";} ?>>SI</option>
                                <option value="0" <?php if($datosConfiguracion['conf_ver_promedios_sabanas_docentes']==0){ echo "selected";} ?>>NO</option>
                            </select>
                        </div>
                    </div>
                    
                    
                    <p class="h3">Acudientes</p>

                    <div class="form-group row">
                        <label class="col-sm-3 control-label">Mostrar calificaciones a los acudientes <span style="color: red;">(*)</span></label>
                        <div class="col-sm-2">
                            <select class="form-control  select2" name="caliAcudientes" <?=$disabledPermiso;?>>
                                <option value="1" <?php if($datosConfiguracion['conf_calificaciones_acudientes']==1){ echo "selected";} ?>>SI</option>
                                <option value="0" <?php if($datosConfiguracion['conf_calificaciones_acudientes']==0){ echo "selected";} ?>>NO</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 control-label">Permitir descargar informe parcial a acudientes <span style="color: red;">(*)</span></label>
                        <div class="col-sm-2">
                            <select class="form-control  select2" name="informeParcial" <?=$disabledPermiso;?>>
                                <option value="1" <?php if($datosConfiguracion['conf_informe_parcial']==1){ echo "selected";} ?>>SI</option>
                                <option value="0" <?php if($datosConfiguracion['conf_informe_parcial']==0){ echo "selected";} ?>>NO</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 control-label">Permitir a acudientes descargar el boletín <span style="color: red;">(*)</span>
                        <button type="button" class="btn btn-sm" data-toggle="tooltip" data-placement="right" title="Esta acción permite a los acudientes descargar el boletín de sus acudidos."><i class="fa fa-info"></i></button> 
                        </label>
                        <div class="col-sm-8">
                            <select class="form-control col-sm-2 select2" name="descargarBoletin" <?=$disabledPermiso;?>>
                                <option value="1" <?php if($datosConfiguracion['conf_permiso_descargar_boletin']==1){ echo "selected";} ?>>SI</option>
                                <option value="0" <?php if($datosConfiguracion['conf_permiso_descargar_boletin']==0){ echo "selected";} ?>>NO</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 control-label">Activar encuesta de reserva de cupos <span style="color: red;">(*)</span>
                        <button type="button" class="btn btn-sm" data-toggle="tooltip" data-placement="right" title="Esta acción permite a los acudientes responder si desean reservar o no el cupo para sus acudidos para el siguiente año."><i class="fa fa-info"></i></button> 
                        </label>
                        <div class="col-sm-8">
                            <select class="form-control col-sm-2 select2" name="activarEncuestaReservaCupo" <?=$disabledPermiso;?>>
                                <option value="1" <?php if($datosConfiguracion['conf_activar_encuesta']==1){ echo "selected";} ?>>SI</option>
                                <option value="0" <?php if($datosConfiguracion['conf_activar_encuesta']==0){ echo "selected";} ?>>NO</option>
                            </select>
                        </div>
                    </div>

                    <p class="h3">Estudiantes</p>

                    <div class="form-group row">
                        <label class="col-sm-3 control-label">Mostrar calificaciones a los estudiantes <span style="color: red;">(*)</span></label>
                        <div class="col-sm-2">
                            <select class="form-control  select2" name="caliEstudiantes" <?=$disabledPermiso;?>>
                                <option value="1" <?php if($datosConfiguracion['conf_mostrar_calificaciones_estudiantes']==1){ echo "selected";} ?>>SI</option>
                                <option value="0" <?php if($datosConfiguracion['conf_mostrar_calificaciones_estudiantes']==0){ echo "selected";} ?>>NO</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 control-label">Permitir a estudiantes cambiar su clave <span style="color: red;">(*)</span></label>
                        <div class="col-sm-2">
                            <select class="form-control  select2" name="cambiarClaveEstudiantes">
                                <option value="SI" <?php if ($datosConfiguracion['conf_cambiar_clave_estudiantes'] == 'SI') {
                                                        echo "selected";
                                                    } ?>>SI</option>
                                <option value="NO" <?php if ($datosConfiguracion['conf_cambiar_clave_estudiantes'] == 'NO') {
                                                        echo "selected";
                                                    } ?>>NO</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 control-label">Mostrar paso a paso de matricula <span style="color: red;">(*)</span>
                        <button type="button" class="btn btn-sm" data-toggle="tooltip" data-placement="right" title="Esta acción permite ver a los estudiantes el paso a paso del proceso de matricula."><i class="fa fa-info"></i></button></label>
                        <div class="col-sm-2">
                            <select class="form-control  select2" name="pasosMatricula">
                                <option value="1" <?php if ($datosConfiguracion['conf_mostrar_pasos_matricula'] == 1) {
                                                        echo "selected";
                                                    } ?>>SI</option>
                                <option value="0" <?php if ($datosConfiguracion['conf_mostrar_pasos_matricula'] == 0) {
                                                        echo "selected";
                                                    } ?>>NO</option>
                            </select>
                        </div>
                    </div>

                    <?php $botones = new botonesGuardar("dev-instituciones.php",Modulos::validarPermisoEdicion() || $datosUsuarioActual['uss_tipo'] == TIPO_DEV); ?>
                </form>
            </div>
        </div>
    </div>
</div>