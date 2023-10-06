<!--select2-->
<link href="../../config-general/assets/plugins/select2/css/select2.css" rel="stylesheet" type="text/css" />
<link href="../../config-general/assets/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
<!--tagsinput-->
<link href="../../config-general/assets/plugins/jquery-tags-input/jquery-tags-input.css" rel="stylesheet">

<div class="col-sm-12">
        <div class="card card-box">
            <div class="card-head">
                <header><?= $frases[212][$datosUsuarioActual[8]]; ?></header>
            </div>
            <div class="card-body " id="bar-parent6">
                <form class="form-horizontal" action="../compartido/guardar.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="2">

                    <div class="form-group row">
                        <label class="col-sm-2 control-label"><?= $frases[127][$datosUsuarioActual[8]]; ?></label>
                        <div class="col-sm-10">
                            <input type="text" name="titulo" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 control-label"><?= $frases[50][$datosUsuarioActual[8]]; ?></label>
                        <div class="col-sm-10">
                            <textarea name="contenido" id="editor1" class="form-control" rows="5" style="margin-top: 0px; margin-bottom: 0px; height: 100px; resize: none;" required></textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 control-label"><?= $frases[211][$datosUsuarioActual[8]]; ?></label>
                        <div class="col-sm-6">
                            <input type="file" name="imagen" class="form-control">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 control-label"><?= $frases[213][$datosUsuarioActual[8]]; ?></label>
                        <div class="col-sm-10">
                            <input type="text" name="urlImagen" class="form-control">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 control-label"><?= $frases[214][$datosUsuarioActual[8]]; ?></label>
                        <div class="col-sm-10">
                            <input type="text" name="video" class="form-control">
                        </div>
                    </div>


                    <div class="form-group row">
                        <label class="col-sm-2 control-label"><?= $frases[224][$datosUsuarioActual[8]]; ?></label>
                        <div class="col-sm-10">
                            <?php
                            $datosConsulta = mysqli_query($conexion, "SELECT * FROM " . $baseDatosServicios . ".general_categorias
												WHERE gcat_activa=1
												");
                            ?>
                            <select class="form-control  select2" style="width: 100%" name="categoriaGeneral" required>
                                <option value="">Seleccione una opción</option>
                                <?php
                                while ($datos = mysqli_fetch_array($datosConsulta, MYSQLI_BOTH)) {
                                ?>
                                    <option value="<?= $datos['gcat_id']; ?>" <?php if ($datos['gcat_id'] == 15) echo "selected"; ?>><?= $datos['gcat_nombre'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>


                    <div class="form-group row">
                        <label class="col-sm-2 control-label"><?= $frases[228][$datosUsuarioActual[8]]; ?></label>
                        <div class="col-sm-10">
                            <input type="text" name="keyw" class="tags tags-input" data-type="tags" />
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 control-label"><?= $frases[128][$datosUsuarioActual[8]]; ?></label>
                        <div class="col-sm-6">
                            <input type="file" name="archivo" class="form-control">
                        </div>
                    </div>

                    <h4 align="center" style="font-weight: bold;"><?= $frases[205][$datosUsuarioActual[8]]; ?></h4>

                    <div class="form-group row">
                        <label class="col-sm-2 control-label"><?= $frases[75][$datosUsuarioActual[8]]; ?></label>
                        <div class="col-sm-10">
                            <select id="multiple" style="width: 100%" class="form-control select2-multiple" multiple>
                                <option value="5">Directivos</option>
                                <option value="2">Docentes</option>
                                <option value="3">Acudientes</option>
                                <option value="4">Estudiantes</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 control-label"><?= $frases[5][$datosUsuarioActual[8]]; ?></label>
                        <div class="col-sm-10">
                            <select style="width: 100%" id="multiple" class="form-control select2-multiple" multiple name="cursos[]">
                                <?php
                                $infoConsulta = mysqli_query($conexion, "SELECT * FROM academico_grados");
                                while ($infoDatos = mysqli_fetch_array($infoConsulta, MYSQLI_BOTH)) {
                                ?>
                                    <option value="<?= $infoDatos['gra_id']; ?>"><?= strtoupper($infoDatos['gra_nombre']); ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>



                    <input type="submit" class="btn btn-primary" value="<?= $frases[41][$datosUsuarioActual[8]]; ?>">&nbsp;

                </form>
            </div>
        </div>
    </div>

<script src="../ckeditor/ckeditor.js"></script>

<script>
    // Replace the <textarea id="editor1"> with a CKEditor 4
    // instance, using default configuration.
    CKEDITOR.replace('editor1');
</script>
<script src="../../config-general/assets/plugins/jquery-tags-input/jquery-tags-input.js"></script>
<script src="../../config-general/assets/plugins/jquery-tags-input/jquery-tags-input-init.js"></script>
<!--select2-->
<script src="../../config-general/assets/plugins/select2/js/select2.js"></script>
<script src="../../config-general/assets/js/pages/select2/select2-init.js"></script>
<!-- end js include path -->
</body>