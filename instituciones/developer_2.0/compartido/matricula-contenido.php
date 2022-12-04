						
<?php
$acudiente = mysql_fetch_array(mysql_query("SELECT * FROM usuarios WHERE uss_id='".$datosEstudianteActual["mat_acudiente"]."'",$conexion));
?>
						<div class="col-sm-9">
						    <div class="card card-box">
						        <div class="card-head">
						            <header><?= $frases[60][$datosUsuarioActual[8]]; ?></header>
						        </div>
						        <div class="card-body " id="bar-parent6">
						            <form action="../compartido/guardar.php" method="post" enctype="multipart/form-data">
						                <input type="hidden" name="id" value="19">

						               

										<div class="form-group row">
						                    <label class="col-sm-2 control-label">Cod. Tesorería</label>
						                    <div class="col-sm-4">
						                        <input type="text" value="<?= $datosEstudianteActual["mat_codigo_tesoreria"]; ?>" name="codTes" class="form-control" disabled>
						                    </div>
						                </div>

						                <div class="form-group row">
						                    <label class="col-sm-2 control-label"><?= $frases[60][$datosUsuarioActual[8]]; ?></label>
						                    <div class="col-sm-4">
						                        <input type="text" value="<?= $datosEstudianteActual["mat_matricula"]; ?>" name="matricula" class="form-control" disabled>
						                    </div>
						                </div>

						                <div class="form-group row">
						                    <label class="col-sm-2 control-label">Primer apellido</label>
						                    <div class="col-sm-10">
						                        <input type="text" value="<?= $datosEstudianteActual["mat_primer_apellido"]; ?>" name="nombre" class="form-control" readonly>
						                    </div>
						                </div>

						                <div class="form-group row">
						                    <label class="col-sm-2 control-label">Segundo apellido</label>
						                    <div class="col-sm-10">
						                        <input type="text" value="<?= $datosEstudianteActual["mat_segundo_apellido"]; ?>" name="nombre" class="form-control" readonly>
						                    </div>
						                </div>

						                <div class="form-group row">
						                    <label class="col-sm-2 control-label">Nombres</label>
						                    <div class="col-sm-10">
						                        <input type="text" value="<?= $datosEstudianteActual["mat_nombres"]; ?>" name="nombre" class="form-control" readonly>
						                    </div>
						                </div>

						                <div class="form-group row">
						                    <label class="col-sm-2 control-label"><?= $frases[164][$datosUsuarioActual[8]]; ?></label>
						                    <div class="col-sm-10">
						                        <select class="form-control  select2" name="lNacimiento">
						                            <option value="">Seleccione una opción</option>
						                            <?php
                                                    $opcionesG = mysql_query("SELECT * FROM academico_grados
													", $conexion);
                                                    while ($opg = mysql_fetch_array($opcionesG)) {
                                                    ?>
						                                <option value="<?= $opg['gra_id']; ?>" <?php if ($opg['gra_id'] == $datosEstudianteActual["mat_grado"]) {
                                                                                                    echo "selected";
                                                                                                } ?> disabled><?= $opg['gra_nombre']; ?></option>
						                            <?php } ?>
						                        </select>
						                    </div>
						                </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 control-label"><?=$frases[189][$datosUsuarioActual[8]];?></label>
                                            <div class="col-sm-4">
                                                <div class="input-group date form_date" data-date-format="dd MM yyyy" data-link-field="dtp_input1" data-link-format="yyyy-mm-dd">
                                                <input class="form-control" size="16" type="text" value="<?=$datosUsuarioActual["uss_fecha_nacimiento"];?>" disabled>
                                                <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                            	</div>
                                            </div>
											<input type="hidden" id="dtp_input1" value="<?=$datosUsuarioActual["uss_fecha_nacimiento"];?>" name="fechaN" disabled>
                                        </div>

						                <hr>

                                        <p style="color: navy;">La dirección se debe colocar con abreviaciones como (CL, DG, AV, CR) y no puede contener ningún tipo de carácter como (-,#,.).</p>

						                <div class="form-group row">
						                    <label class="col-sm-2 control-label">Dirección</label>
						                    <div class="col-sm-10">
						                        <input type="text" value="<?= $datosEstudianteActual["mat_direccion"]; ?>" name="dir" class="form-control">
						                    </div>
						                </div>

						                <div class="form-group row">
						                    <label class="col-sm-2 control-label">Barrio</label>
						                    <div class="col-sm-10">
						                        <input type="text" value="<?= $datosEstudianteActual["mat_barrio"]; ?>" name="barrio" class="form-control">
						                    </div>
						                </div>

						                <div class="form-group row">
						                    <label class="col-sm-2 control-label">Estrato</label>
						                    <div class="col-sm-4">
						                        <select class="form-control  select2" name="estrato" required>
						                            <option value="">Seleccione una opción</option>
						                            <?php
                                                    $opcionesG = mysql_query("SELECT * FROM " . $baseDatosServicios . ".opciones_generales WHERE ogen_grupo=3", $conexion);
                                                    while ($opg = mysql_fetch_array($opcionesG)) {
                                                    ?>
						                                <option value="<?= $opg[0]; ?>" <?php if ($opg[0] == $datosEstudianteActual["mat_estrato"]) {
                                                                                            echo "selected";
                                                                                        } ?>><?= $opg[1]; ?></option>
						                            <?php } ?>
						                        </select>
						                    </div>
						                </div>

						                <div class="form-group row">
						                    <label class="col-sm-2 control-label">Teléfono</label>
						                    <div class="col-sm-4">
						                        <input type="text" value="<?= $datosEstudianteActual["mat_telefono"]; ?>" name="telefono" class="form-control">
						                    </div>
						                </div>

						                <div class="form-group row">
						                    <label class="col-sm-2 control-label">Celular</label>
						                    <div class="col-sm-4">
						                        <input type="text" value="<?= $datosEstudianteActual["mat_celular"]; ?>" name="celular" class="form-control">
						                    </div>
						                </div>

						                <div class="form-group row">
						                    <label class="col-sm-2 control-label">ID. Sistema</label>
						                    <div class="col-sm-3">
						                        <input type="text" value="<?= $datosEstudianteActual["mat_id"]; ?>" name="cod" class="form-control" disabled>
						                    </div>
						                </div>

						                <input type="hidden" name="modalidad" value="2">
						                <!--
										<div class="form-group row">
						                    <label class="col-sm-2 control-label">Modalidad de estudio</label>
						                    <div class="col-sm-4">
						                        <select class="form-control  select2" name="modalidad" required>
						                            <option value="">Seleccione una opción</option>
													<option value="1" <?php if ($datosEstudianteActual["mat_modalidad_estudio"] == 1) { echo "selected";} ?>>Virtual</option>
													<option value="2" <?php if ($datosEstudianteActual["mat_modalidad_estudio"] == 2) { echo "selected";} ?>>Presencial- alternancia</option>
						                            
						                        </select>
						                    </div>
						                </div>
						            -->

                                        <h3>Datos Acudiente</h3>
                                        <p style="color: navy;">Si necesita hacer un cambio de acudiente debe comunicarse vía WhatsApp, al número 318 347 9394.</p>

                                        <input type="hidden" name="idAcudiente" value="<?= $acudiente["uss_id"]; ?>">

                                        <div class="form-group row">
						                    <label class="col-sm-2 control-label">ID</label>
						                    <div class="col-sm-4">
						                        <input type="text" value="<?= $acudiente["uss_id"]; ?>" name="idA" class="form-control" readonly>
						                    </div>
						                </div>

                                        <div class="form-group row">
						                    <label class="col-sm-2 control-label">Documento</label>
						                    <div class="col-sm-4">
						                        <input type="text" value="<?= $acudiente["uss_usuario"]; ?>" name="documentoA" class="form-control" readonly>
						                    </div>
						                </div>

                                        <div class="form-group row">
						                    <label class="col-sm-2 control-label">Nombre</label>
						                    <div class="col-sm-10">
						                        <input type="text" value="<?= $acudiente["uss_nombre"]; ?>" name="nombreA" class="form-control" readonly>
						                    </div>
						                </div>

                                        <div class="form-group row">
						                    <label class="col-sm-2 control-label">Email</label>
						                    <div class="col-sm-10">
						                        <input type="text" value="<?= $acudiente["uss_email"]; ?>" name="emailA" class="form-control">
						                    </div>
						                </div>

                                        <div class="form-group row">
						                    <label class="col-sm-2 control-label">Celular</label>
						                    <div class="col-sm-4">
						                        <input type="text" value="<?= $acudiente["uss_celular"]; ?>" name="celularA" class="form-control">
						                    </div>
						                </div>

                                        <div class="form-group row">
						                    <label class="col-sm-2 control-label">Ocupación</label>
						                    <div class="col-sm-10">
						                        <input type="text" value="<?= $acudiente["uss_ocupacion"]; ?>" name="ocupacion" class="form-control">
						                    </div>
						                </div>





						                <?php if ($datosEstudianteActual["mat_iniciar_proceso"] == 1 AND $datosEstudianteActual["mat_actualizar_datos"] == '0') { ?>
						                    <input type="submit" class="btn btn-primary" value="Guardar cambios">&nbsp;
						                <?php } ?>

						            </form>
						        </div>
						    </div>
						</div>

						<div class="col-sm-3">

                            <?php include("../compartido/matricula-pasos.php"); ?>

						    <?php include("../compartido/modulo-frases-lateral.php"); ?>

						    <?php //include("../compartido/publicidad-lateral.php"); ?>
						</div>