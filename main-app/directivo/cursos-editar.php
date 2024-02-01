<?php
include("session.php");
$idPaginaInterna = 'DT0064';
include("../compartido/historial-acciones-guardar.php");
include("../compartido/head.php");
require_once("../class/Estudiantes.php");
require_once("../class/servicios/GradoServicios.php");
require_once("../class/servicios/CargaServicios.php");
require_once("../class/servicios/MatriculaServicios.php");

if (!Modulos::validarSubRol([$idPaginaInterna])) {
    echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
    exit();
}

try {
    $resultadoCurso = GradoServicios::consultarCurso(base64_decode($_GET["id"]));
    $resultadoCargaCurso = CargaServicios::cantidadCursos(base64_decode($_GET["id"]));
    $hidden = $resultadoCurso['gra_tipo'] == GRADO_INDIVIDUAL ? "" : "hidden";
} catch (Exception $e) {
    include("../compartido/error-catch-to-report.php");
}
$disabledPermiso = "";
if (!Modulos::validarPermisoEdicion()) {
    $disabledPermiso = "disabled";
}
?>

<!--bootstrap -->
<link href="../../config-general/assets/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
<link href="../../config-general/assets/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.css" rel="stylesheet" media="screen">
<!-- Theme Styles -->
<link href="../../config-general/assets/css/pages/formlayout.css" rel="stylesheet" type="text/css" />
<!-- dropzone -->
<link href="../../config-general/assets/plugins/dropzone/dropzone.css" rel="stylesheet" media="screen">
<!--tagsinput-->
<link href="../../config-general/assets/plugins/jquery-tags-input/jquery-tags-input.css" rel="stylesheet">
<!--select2-->
<link href="../../config-general/assets/plugins/select2/css/select2.css" rel="stylesheet" type="text/css" />
<link href="../../config-general/assets/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
</head>
<!-- END HEAD -->
<?php include("../compartido/body.php"); ?>
<div class="page-wrapper">
    <?php include("../compartido/encabezado.php"); ?>

    <?php include("../compartido/panel-color.php"); ?>
    <!-- start page container -->
    <div class="page-container">
        <?php include("../compartido/menu.php"); ?>
        <!-- start page content -->
        <div class="page-content-wrapper">
            <div class="page-content">
                <div class="page-bar">
                    <div class="page-title-breadcrumb">
                        <div class=" pull-left">
                            <div class="page-title">Editar Cursos</div>
                            <?php include("../compartido/texto-manual-ayuda.php"); ?>
                        </div>
                        <ol class="breadcrumb page-breadcrumb pull-right">
                            <li><a class="parent-item" href="javascript:void(0);" name="cursos.php" onClick="deseaRegresar(this)"><?= $frases[5][$datosUsuarioActual['uss_idioma']]; ?></a>&nbsp;<i class="fa fa-angle-right"></i></li>
                            <li class="active">Editar Cursos</li>
                        </ol>
                    </div>
                </div>
                <div class="row">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">





                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">

                        <?php include("../../config-general/mensajes-informativos.php"); ?>
                        <div class="col-md-12">
                            <nav>
                                <div class="nav nav-tabs" id="nav-tab" role="tablist">

                                    <a class="nav-item nav-link show active" id="nav-informacion-tab" data-toggle="tab" href="#nav-informacion" role="tab" aria-controls="nav-informacion" aria-selected="true">
                                        <h5> <?= $frases[119][$datosUsuarioActual['uss_idioma']]; ?> </h5>
                                    </a>
                                    <?php if (array_key_exists(10, $arregloModulos)) { ?>
                                        <a <?= $hidden ?> class="nav-item nav-link" id="nav-configuracion-tab" data-toggle="tab" href="#nav-configuracion" role="tab" aria-controls="nav-configuracion" aria-selected="false">
                                            <h5> Configuracion del curso </h5>
                                        </a>

                                        <a <?= $hidden ?> class="nav-item nav-link" id="nav-estudiantes-tab" data-toggle="tab" href="#nav-estudiantes" role="tab" aria-controls="nav-estudiantes" aria-selected="false">
                                            <h5>Estudiantes </h5>
                                        </a>
                                    <?php } ?>
                                </div>
                            </nav>
                            <form id="miFormulario" name="formularioGuardar" action="cursos-actualizar.php" method="post">
                                <div class="tab-content" id="nav-tabContent">

                                    <div class="tab-pane fade show active" id="nav-informacion" role="tabpanel" aria-labelledby="nav-informacion-tab">
                                        <div class="panel">
                                            <div class="panel-body">
                                                <input type="hidden" id="id_curso" name="id_curso" value="<?= base64_decode($_GET["id"]) ?>">

                                                <div class="form-group row">
                                                    <label class="col-sm-2 control-label">Codigo</label>
                                                    <div class="col-sm-2">
                                                        <input type="text" name="codigoC" readonly class="form-control" value="<?= $resultadoCurso["gra_codigo"]; ?>" <?= $disabledPermiso; ?>>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-sm-2 control-label">Nombre Curso</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" name="nombreC" class="form-control" required value="<?= $resultadoCurso["gra_nombre"]; ?>" <?= $disabledPermiso; ?>>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-2 control-label">Formato Boletin</label>
                                                    <div class="col-sm-2">
                                                        <select id="tipoBoletin" class="form-control  select2" name="formatoB" onchange="cambiarTipo()" required <?= $disabledPermiso; ?>>
                                                            <option value="">Seleccione una opción</option>
                                                            <?php
                                                            try {
                                                                $consultaBoletin = mysqli_query($conexion, "SELECT * FROM " . $baseDatosServicios . ".opciones_generales WHERE ogen_grupo=15");
                                                            } catch (Exception $e) {
                                                                include("../compartido/error-catch-to-report.php");
                                                            }
                                                            while ($datosBoletin = mysqli_fetch_array($consultaBoletin, MYSQLI_BOTH)) {
                                                            ?>
                                                                <option value="<?= $datosBoletin['ogen_id']; ?>" <?php if ($resultadoCurso["gra_formato_boletin"] == $datosBoletin['ogen_id']) {
                                                                                                                        echo 'selected';
                                                                                                                    } ?>><?= $datosBoletin['ogen_nombre']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                    <button type="button" titlee="Ver formato del boletin" class="btn btn-sm" data-toggle="popover"><i class="fa fa-eye"></i></button>
                                                    <script>
                                                        $(document).ready(function() {
                                                            $('[data-toggle="popover"]').popover({
                                                                html: true, // Habilitar contenido HTML
                                                                content: function() {
                                                                    valor = document.getElementById("tipoBoletin");
                                                                    return '<div id="myPopover" class="popover-content"><label id="lbl_tipo">Formato tipo ' + valor.value + '</label>' +
                                                                        '<img id="img-boletin" src="../files/images/boletines/tipo' + valor.value + '.png" class="w-100" />' +
                                                                        '</div>';
                                                                }
                                                            });
                                                        });

                                                        function cambiarTipo() {
                                                            var imagen_boletin = document.getElementById('img-boletin');
                                                            if (imagen_boletin) {
                                                                var valor = document.getElementById("tipoBoletin");
                                                                var lbl_tipo = document.getElementById('lbl_tipo');
                                                                imagen_boletin.src = "../files/images/boletines/tipo" + valor.value + ".png";
                                                                lbl_tipo.textContent = 'Formato tipo ' + valor.value;
                                                            }
                                                        }
                                                    </script>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-sm-2 control-label">Nota Minima</label>
                                                    <div class="col-sm-2">
                                                        <input type="text" name="notaMin" class="form-control" value="<?= $resultadoCurso["gra_nota_minima"]; ?>" <?= $disabledPermiso; ?>>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-sm-2 control-label">Periodos</label>
                                                    <div class="col-sm-2">
                                                        <input type="text" name="periodosC" class="form-control" value="<?= $resultadoCurso["gra_periodos"]; ?>" <?= $disabledPermiso; ?>>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-sm-2 control-label">Valor Matricula</label>
                                                    <div class="col-sm-2">
                                                        <input type="text" name="valorM" class="form-control" value="<?= $resultadoCurso["gra_valor_matricula"]; ?>" <?= $disabledPermiso; ?>>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-sm-2 control-label">Valor Pension</label>
                                                    <div class="col-sm-2">
                                                        <input type="text" name="valorP" class="form-control" value="<?= $resultadoCurso["gra_valor_pension"]; ?>" <?= $disabledPermiso; ?>>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-sm-2 control-label">Curso Siguiente</label>
                                                    <div class="col-sm-6">
                                                        <?php
                                                        $opcionesConsulta = Grados::listarGrados(1);
                                                        ?>
                                                        <select class="form-control  select2" name="graSiguiente" <?= $disabledPermiso; ?>>
                                                            <option value="">Seleccione una opción</option>
                                                            <?php
                                                            while ($opcionesDatos = mysqli_fetch_array($opcionesConsulta, MYSQLI_BOTH)) {
                                                                $select = '';
                                                                if ($resultadoCurso["gra_grado_siguiente"] == $opcionesDatos['gra_id']) {
                                                                    $select = 'selected';
                                                                }
                                                            ?>
                                                                <option value="<?= $opcionesDatos['gra_id']; ?>" <?= $select; ?>><?= strtoupper($opcionesDatos['gra_nombre']); ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-sm-2 control-label">Curso Anterior</label>
                                                    <div class="col-sm-6">
                                                        <?php
                                                        $opcionesConsulta = Grados::listarGrados(1);
                                                        ?>
                                                        <select class="form-control  select2" name="graAnterior" <?= $disabledPermiso; ?>>
                                                            <option value="">Seleccione una opción</option>
                                                            <?php
                                                            while ($opcionesDatos = mysqli_fetch_array($opcionesConsulta, MYSQLI_BOTH)) {
                                                                $select = '';
                                                                if ($resultadoCurso["gra_grado_anterior"] == $opcionesDatos['gra_id']) {
                                                                    $select = 'selected';
                                                                }
                                                            ?>
                                                                <option value="<?= $opcionesDatos['gra_id']; ?>" <?= $select; ?>><?= strtoupper($opcionesDatos['gra_nombre']); ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-sm-2 control-label">Nivel Educativo</label>
                                                    <div class="col-sm-6">
                                                        <select class="form-control  select2" name="nivel" <?= $disabledPermiso; ?>>
                                                            <option value="">Seleccione una opción</option>
                                                            <option value="1" <?php if ($resultadoCurso['gra_nivel'] == 1) {
                                                                                    echo 'selected';
                                                                                } ?>>Educación Precolar</option>
                                                            <option value="2" <?php if ($resultadoCurso['gra_nivel'] == 2) {
                                                                                    echo 'selected';
                                                                                } ?>>Educación Basica Primaria</option>
                                                            <option value="3" <?php if ($resultadoCurso['gra_nivel'] == 3) {
                                                                                    echo 'selected';
                                                                                } ?>>Educación Basica Secundaria</option>
                                                            <option value="4" <?php if ($resultadoCurso['gra_nivel'] == 4) {
                                                                                    echo 'selected';
                                                                                } ?>>Educación Media</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <?php if ($datosUsuarioActual['uss_tipo'] == 1) { ?>
                                                    <div class="form-group row">
                                                        <label class="col-sm-2 control-label">Estado</label>
                                                        <div class="col-sm-2">
                                                            <select class="form-control  select2" name="estado" <?= $disabledPermiso; ?>>
                                                                <option value="">Seleccione una opción</option>
                                                                <option value="1" <?php if ($resultadoCurso['gra_estado'] == 1) {
                                                                                        echo 'selected';
                                                                                    } ?>>Activo</option>
                                                                <option value="0" <?php if ($resultadoCurso['gra_estado'] == 0) {
                                                                                        echo 'selected';
                                                                                    } ?>>Inactivo</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                <?php
                                                }
                                                if (array_key_exists(10, $arregloModulos)) {
                                                ?>
                                                    <div class="form-group row">
                                                        <label class="col-sm-2 control-label">Tipo de grado</label>
                                                        <div class="col-sm-2">
                                                            <?php
                                                            if ($resultadoCargaCurso["cargas_curso"] < 1) {
                                                            ?>
                                                                <select class="form-control  select2" name="tipoG" id="tipoG" onchange="mostrarEstudiantes(this)">
                                                                    <option value="">Seleccione una opción</option>
                                                                    <option value=<?= GRADO_GRUPAL; ?> <?php if ($resultadoCurso['gra_tipo'] == GRADO_GRUPAL) {
                                                                                                            echo 'selected';
                                                                                                        } ?>>Grupal</option>
                                                                    <option value=<?= GRADO_INDIVIDUAL; ?> <?php if ($resultadoCurso['gra_tipo'] == GRADO_INDIVIDUAL) {
                                                                                                                echo 'selected';
                                                                                                            } ?>>Individual</option>
                                                                </select>
                                                            <?php
                                                            } else {
                                                            ?>
                                                                <select class="form-control  select2" name="tipoG" id="tipoG" disabled>
                                                                    <?php
                                                                    if ($resultadoCurso['gra_tipo'] == GRADO_GRUPAL) {
                                                                        echo '<option value="' . GRADO_GRUPAL . '" selected>Grupal</option>';
                                                                    } elseif ($resultadoCurso['gra_tipo'] == GRADO_INDIVIDUAL) {
                                                                        echo '<option value="' . GRADO_INDIVIDUAL . '" selected>Individual</option>';
                                                                    } else {
                                                                        echo ' ';
                                                                    }
                                                                    ?>
                                                                </select>
                                                            <?php } ?>
                                                        </div>
                                                    </div>


                                                <?php
                                                }
                                                ?>


                                            </div>
                                        </div>
                                    </div>

                                    <div <?= $hidden ?> class="tab-pane fade" id="nav-configuracion" role="tabpanel" aria-labelledby="nav-configuracion-tab">

                                        <div class="panel">
                                            <div class="panel-body">

                                                <div class="form-group row">
                                                    <label class="col-sm-10 control-label"></label>
                                                    <label class="col-sm-1 control-label">Vista Previa</label>
                                                    <div class="col-sm-1">
                                                        <a href="../guest/details.php?course=<?= $resultadoCurso["id_nuevo"] ?>" target="_blank">
                                                            <button type="button" titlee="Ver vista previsa" class="btn btn-sm btn-info"><i class="fa fa-eye"></i></button>
                                                        </a>

                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-2 control-label">URL imagen</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" name="imagen" value="<?= $resultadoCurso["gra_cover_image"]; ?>" class="form-control" <?= $disabledPermiso; ?>>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-2 control-label">Descripcion</label>
                                                    <div class="col-sm-10">
                                                        <textarea cols="80" id="editor1" name="descripcion" class="form-control" rows="8" placeholder="Escribe tu mensaje" style="margin-top: 0px; margin-bottom: 0px; height: 100px; resize: none;" <?= $disabledPermiso; ?>>
                                                        <?= $resultadoCurso["gra_overall_description"]; ?>
                                                        </textarea>

                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-2 control-label">Contenido</label>
                                                    <div class="col-sm-10">
                                                        <textarea cols="80" id="editor2" name="contenido" class="form-control" rows="8" placeholder="Escribe tu mensaje" style="margin-top: 0px; margin-bottom: 0px; height: 100px; resize: none;" <?= $disabledPermiso; ?>>
                                                        <?= $resultadoCurso["gra_course_content"]; ?>
                                                        </textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-2 control-label">Precio</label>
                                                    <div class="col-sm-10">
                                                        <input type="number" name="precio" class="form-control" value="<?= $resultadoCurso["gra_price"]; ?>" <?= $disabledPermiso; ?>>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-2 control-label">Minimo de estudiantes</label>
                                                    <div class="col-sm-10">
                                                        <input type="number" name="minEstudiantes" class="form-control" value="<?= $resultadoCurso["gra_minimum_quota"]; ?>" <?= $disabledPermiso; ?>>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-2 control-label">Maximo de estudiantes</label>
                                                    <div class="col-sm-10">
                                                        <input type="number" name="maxEstudiantes" class="form-control" value="<?= $resultadoCurso["gra_maximum_quota"]; ?>" <?= $disabledPermiso; ?>>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-2 control-label">Duracion en horas</label>
                                                    <div class="col-sm-10">
                                                        <input type="number" id="horas" name="horas" class="form-control" value="<?= $resultadoCurso["gra_duration_hours"]; ?>" min="1" max="10" <?= $disabledPermiso; ?>>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-2 control-label">Auto Enrollment</label>
                                                    <div class="col-sm-10">
                                                        <label class="switchToggle">
                                                            <input name="autoenrollment" type="checkbox" <?php if ($resultadoCurso['gra_auto_enrollment'] == 1) {
                                                                                                                echo "checked";
                                                                                                            } ?>>
                                                            <span class="slider green round"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-2 control-label">Activo</label>
                                                    <div class="col-sm-10">
                                                        <label class="switchToggle">
                                                            <input name="activo" type="checkbox" <?php if ($resultadoCurso['gra_active'] == 1) {
                                                                                                        echo "checked";
                                                                                                    } ?>>
                                                            <span class="slider green round"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div <?= $hidden ?> class="tab-pane fade" id="nav-estudiantes" role="tabpanel" aria-labelledby="nav-estudiantes-tab">

                                        <div class="panel">
                                            <div class="panel-body">

                                                <div class="form-group row">
                                                    <label class="col-sm-2 control-label">Agregar un estudainte:</label>
                                                    <div class="col-sm-6 ">
                                                        <div class="input-group">
                                                            <select id="select_estudiante" class="form-control select2-multiple" onchange="selecionarEstudiante(this.value)" style="width: 80% !important" name="estudiantesMT">
                                                                <option value="">Seleccione un estudiante</option>
                                                            </select>
                                                            <div class="input-group-append">
                                                                <!-- <div class="btn-group" role="group" > -->
                                                                <button style="display: none;" id="btnEliminar" type="button" class="input-group-text btn btn-danger btn-sm" onclick="limpiarSelecionEstudiante()"><i class="fa fa-trash"></i></button>
                                                                <button style="display: none;" id="btnAgregar" type="button" class="input-group-text btn btn-info btn-sm" onclick="agregarEstudainte()"><i class="fa fa-add"></i></button>
                                                                <!-- </div> -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php
                                                    $cv = mysqli_query($conexion, "SELECT gru_id, gru_nombre FROM " . BD_ACADEMICA . ".academico_grupos WHERE institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
                                                    ?>
                                                    <div style="display: none;">
                                                        <select id="grupoBase" multiple class="form-control select2-multiple">
                                                            <?php while ($rv = mysqli_fetch_array($cv, MYSQLI_BOTH)) {
                                                                echo '<option value="' . $rv[0] . '" selected >' . $rv[1] . '</option>';
                                                            } ?>
                                                        </select>
                                                        <select id="grupoEstado" multiple class="form-control select2-multiple">
                                                            <option value="<?= ESTADO_CURSO_ACTIVO ?>" selected><?= ESTADO_CURSO_ACTIVO ?></option>
                                                            <option value="<?= ESTADO_CURSO_INACTIVO ?>" selected><?= ESTADO_CURSO_INACTIVO ?></option>
                                                            <option value="<?= ESTADO_CURSO_PRE_INSCRITO ?>" selected><?= ESTADO_CURSO_PRE_INSCRITO ?></option>
                                                            <option value="<?= ESTADO_CURSO_NO_APROBADO ?>" selected><?= ESTADO_CURSO_NO_APROBADO ?></option>
                                                            <option value="<?= ESTADO_CURSO_APROBADO ?>" selected><?= ESTADO_CURSO_APROBADO ?></option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <table class="table" id="estudaintesRegistrados">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">#</th>
                                                            <th scope="col">Nombre</th>
                                                            <th scope="col" width="100px">Grupo</th>
                                                            <th scope="col" width="200px">Estado</th>
                                                            <th scope="col">Acciones</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $parametros = [
                                                            'matcur_id_curso' => base64_decode($_GET["id"]),
                                                            'matcur_id_institucion' => $config['conf_id_institucion'],
                                                            'matcur_years' => $config['conf_agno'],
                                                            'arreglo' => false
                                                        ];
                                                        $ListaEstudiantes = MediaTecnicaServicios::listarEstudiantes($parametros);
                                                        if (!is_null($ListaEstudiantes)) {
                                                            foreach ($ListaEstudiantes as $idEstudiante) {
                                                                $matricualaEstudiante = MatriculaServicios::consultar($idEstudiante["matcur_id_matricula"]);
                                                                $nombre = "";
                                                                if (!is_null($matricualaEstudiante)) {
                                                                    $nombre = Estudiantes::NombreCompletoDelEstudiante($matricualaEstudiante);
                                                                }
                                                        ?>
                                                                <tr id="fila<?=$idEstudiante["matcur_id_matricula"];?>">
                                                                    <td><?= $idEstudiante["matcur_id_matricula"]; ?></td>
                                                                    <td><?= $nombre; ?></td>
                                                                    <td>
                                                                        <?php
                                                                        $cv = mysqli_query($conexion, "SELECT gru_id, gru_nombre FROM " . BD_ACADEMICA . ".academico_grupos WHERE institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
                                                                        ?>
                                                                        <select class="form-control" name="grupo" <?= $disabledPermiso; ?>>
                                                                            <?php while ($rv = mysqli_fetch_array($cv, MYSQLI_BOTH)) {
                                                                                if ($rv[0] == $idEstudiante['matcur_id_grupo'])
                                                                                    echo '<option value="' . $rv[0] . '" selected>' . $rv[1] . '</option>';
                                                                                else
                                                                                    echo '<option value="' . $rv[0] . '">' . $rv[1] . '</option>';
                                                                            } ?>
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <?php
                                                                        $cv = mysqli_query($conexion, "SELECT gru_id, gru_nombre FROM " . BD_ACADEMICA . ".academico_grupos WHERE institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
                                                                        ?>
                                                                        <select class="form-control" name="grupo" <?= $disabledPermiso; ?>>
                                                                            <option value="<?= ESTADO_CURSO_ACTIVO ?>" 
                                                                            <?php echo $idEstudiante['matcur_estado'] == ESTADO_CURSO_ACTIVO?'selected':''; ?>
                                                                            >
                                                                            <?= ESTADO_CURSO_ACTIVO ?></option>
                                                                            <option value="<?= ESTADO_CURSO_INACTIVO ?>" 
                                                                            <?php echo $idEstudiante['matcur_estado'] == ESTADO_CURSO_INACTIVO?'selected':''; ?>
                                                                            ><?= ESTADO_CURSO_INACTIVO ?></option>
                                                                            <option value="<?= ESTADO_CURSO_PRE_INSCRITO ?>" 
                                                                            <?php echo $idEstudiante['matcur_estado'] == ESTADO_CURSO_PRE_INSCRITO?'selected':''; ?>
                                                                            ><?= ESTADO_CURSO_PRE_INSCRITO ?></option>
                                                                            <option value="<?= ESTADO_CURSO_NO_APROBADO ?>" 
                                                                            <?php echo $idEstudiante['matcur_estado'] == ESTADO_CURSO_NO_APROBADO?'selected':''; ?>
                                                                            ><?= ESTADO_CURSO_NO_APROBADO ?></option>
                                                                            <option value="<?= ESTADO_CURSO_APROBADO ?>" 
                                                                            <?php echo $idEstudiante['matcur_estado'] == ESTADO_CURSO_APROBADO?'selected':''; ?>
                                                                            ><?= ESTADO_CURSO_APROBADO ?></option>
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <button type="button" onclick="eliminarFila(<?=$idEstudiante["matcur_id_matricula"]?>)" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                                                                    </td>
                                                                </tr>
                                                        <?php  }
                                                        } ?>

                                                    </tbody>
                                                </table>

                                                <div id="escogerEstudiantes">


                                                    <div id="selectsContainer" style="display: none;">
                                                    </div>
                                                </div>
                                                <!-- end js include path -->
                                                <script src="../ckeditor/ckeditor.js"></script>
                                                <script type="text/javascript">
                                                    // Almacenar el estado actual de los grupos
                                                    var estadoActualGrupos = {};

                                                    CKEDITOR.replace('editor1');
                                                    CKEDITOR.replace('editor2');

                                                    function mostrarEstudiantes(data) {
                                                        const navInfo = document.getElementById("nav-informacion-tab");
                                                        const navConfig = document.getElementById("nav-configuracion-tab");
                                                        const contentInfo = document.getElementById("nav-informacion");
                                                        const contentConfigure = document.getElementById("nav-configuracion");
                                                        if (data.value == "<?= GRADO_INDIVIDUAL ?>") {
                                                            navConfig.style.display = "block";
                                                            contentConfigure.classList.add('show', 'active');
                                                            contentInfo.classList.remove('show', 'active');
                                                            navInfo.classList.remove('show', 'active');
                                                            navConfig.classList.add('show', 'active');
                                                            document.getElementById("horas").disabled = false;
                                                        } else {
                                                            navConfig.style.display = "none";
                                                            contentInfo.classList.add('show', 'active');
                                                            contentConfigure.classList.remove('show', 'active');
                                                            navConfig.classList.remove('show', 'active');
                                                            navInfo.classList.add('show', 'active');
                                                            document.getElementById("horas").disabled = true;
                                                        }
                                                    }

                                                    function limpiarSelecionEstudiante() {
                                                        $('#select_estudiante').val(null).trigger('change');
                                                        const btnEliminar = document.getElementById("btnEliminar");
                                                        const btnAgregar = document.getElementById("btnAgregar");
                                                        btnEliminar.style.display = "none";
                                                        btnAgregar.style.display = "none";
                                                    };

                                                    function eliminarFila(button) {
                                                        var fila = button.parentNode.parentNode; // Obtener la referencia a la fila actual                                                        
                                                        var tabla = fila.parentNode; // Obtener la referencia a la tabla                                                        
                                                        tabla.deleteRow(fila.rowIndex); // Eliminar la fila de la tabla
                                                    }

                                                    function agregarEstudainte() {
                                                        var seleccion = $('#select_estudiante').select2('data')[0];

                                                        if (seleccion) {
                                                            var valor = seleccion.id; // El valor de la opción
                                                            var etiqueta = seleccion.text; // La etiqueta de la opción
                                                            // se insertan los valores en la tabla
                                                            var tabla = document.getElementById("estudaintesRegistrados");
                                                            var filas = tabla.getElementsByTagName("tr");
                                                            // buscamos si ya se encuentra registrado                                                            
                                                            encontro = false;
                                                            for (var i = 0; i < filas.length; i++) { // Recorre las filas
                                                                var celdas = filas[i].getElementsByTagName("td"); // Obtén todas las celdas de la fila actual
                                                                for (var j = 0; j < celdas.length; j++) { // Recorre las celdas
                                                                    if (celdas[j].innerHTML == valor) {
                                                                        encontro = true; // cambio el estado de  a tru si encuentra un codigo igual
                                                                    }
                                                                }
                                                            }
                                                            if (!encontro) {
                                                                // creamos el select del grupo
                                                                var select1 = document.createElement("select");
                                                                select1.classList.add('form-control');
                                                                var opciones = $('#grupoBase').select2('data');
                                                                for (var i = 0; i < opciones.length; i++) {
                                                                    var opcion = document.createElement("option");
                                                                    opcion.text = opciones[i].text;
                                                                    opcion.value = opciones[i].id;
                                                                    select1.add(opcion);
                                                                }
                                                                // creamos el select del estado
                                                                var select2 = document.createElement("select");
                                                                select2.classList.add('form-control');
                                                                var opciones2 = $('#grupoEstado').select2('data');
                                                                for (var i = 0; i < opciones2.length; i++) {
                                                                    var opcion = document.createElement("option");
                                                                    opcion.text = opciones2[i].text;
                                                                    opcion.value = opciones2[i].id;
                                                                    select2.add(opcion);
                                                                }
                                                                // Crea un elemento de botón
                                                                var boton = document.createElement("button");
                                                                boton.classList.add('btn', 'btn-danger', 'btn-sm');
                                                                var icon = document.createElement('i'); // se crea la icono
                                                                icon.classList.add('fa', 'fa-trash');
                                                                boton.appendChild(icon);
                                                                // Agregar un evento al botón
                                                                boton.addEventListener('click', function() {
                                                                    eliminarFila(boton);
                                                                });


                                                                // Crear una nueva fila
                                                                var fila = tabla.insertRow();
                                                                // Agregar datos a las celdas
                                                                fila.insertCell(0).innerHTML = valor;
                                                                fila.insertCell(1).innerHTML = etiqueta;
                                                                fila.insertCell(2).appendChild(select1);
                                                                fila.insertCell(3).appendChild(select2);
                                                                fila.insertCell(4).appendChild(boton);
                                                            } else {
                                                                console.log('Estudainte ya se encuentra registrado');
                                                            }

                                                        } else {
                                                            console.log('No hay opción seleccionada.');
                                                        }
                                                        limpiarSelecionEstudiante();
                                                    };


                                                    function selecionarEstudiante(data) {
                                                        console.log(data);
                                                        const btnEliminar = document.getElementById("btnEliminar");
                                                        const btnAgregar = document.getElementById("btnAgregar");
                                                        btnEliminar.style.display = "block";
                                                        btnAgregar.style.display = "block";
                                                    };

                                                    function enviarDatos() {
                                                        var tabla = document.getElementById('estudaintesRegistrados'); // Obtener la referencia a la tabla                                                       
                                                        var formulario = document.getElementById('miFormulario'); // Obtener la referencia al formulario
                                                        // Crear un campo oculto para almacenar los datos de la tabla
                                                        var datosInput = document.createElement('input');
                                                        datosInput.type = 'hidden';
                                                        datosInput.name = 'estudiantesMT';
                                                        datosInput.value = obtenerDatosTabla(tabla); // Función para obtener los datos de la tabla
                                                        console.log('valores de la tabla' + datosInput.value);
                                                        formulario.appendChild(datosInput);
                                                        // Enviar el formulario
                                                        formulario.submit();
                                                    }

                                                    function obtenerDatosTabla(tabla) {
                                                        // Recorrer las filas de la tabla y obtener los datos
                                                        var cruso = document.getElementById('id_curso');
                                                        var datos = [];
                                                        for (var i = 1; i < tabla.rows.length; i++) {
                                                            var fila = tabla.rows[i];
                                                            var id = fila.cells[0].innerText;
                                                            var nombre = fila.cells[1].innerText;
                                                            var grupo = fila.cells[2].querySelector('select').value;
                                                            var estado = fila.cells[3].querySelector('select').value;
                                                            datos.push({
                                                                curso: cruso.value,
                                                                matricula: id,
                                                                nombre: nombre,
                                                                grupo: grupo,
                                                                estado:estado
                                                            });
                                                        }

                                                        // Convertir los datos a formato JSON
                                                        return JSON.stringify(datos);
                                                    }

                                                    $(document).ready(function() {

                                                        $('#select_estudiante').select2({
                                                            placeholder: 'Seleccione el estudiante agregar...',
                                                            theme: "bootstrap",
                                                            multiple: false,
                                                            ajax: {
                                                                type: 'GET',
                                                                url: 'ajax-listar-estudiantes.php',
                                                                processResults: function(data) {
                                                                    data = JSON.parse(data);
                                                                    return {
                                                                        results: $.map(data, function(item) {
                                                                            return {
                                                                                id: item.value,
                                                                                text: item.label,
                                                                                title: item.title
                                                                            }
                                                                        })
                                                                    };
                                                                }
                                                            }
                                                        });
                                                    });
                                                </script>

                                            </div>
                                        </div>

                                    </div>
                                    <a href="javascript:void(0);" name="cursos.php" class="btn btn-secondary" onClick="deseaRegresar(this)"><i class="fa fa-long-arrow-left"></i>Regresar</a>
                                    <?php if (Modulos::validarPermisoEdicion()) { ?>
                                        <button type="button" onclick="enviarDatos()" class="btn  btn-info">
                                            <i class="fa fa-save" aria-hidden="true"></i> Guardar cambios
                                        </button>
                                    <?php } ?>
                                </div>
                            </form>

                        </div>

                    </div>

                </div>

            </div>
            <!-- end page content -->
            <?php // include("../compartido/panel-configuracion.php");
            ?>
        </div>
        <!-- end page container -->
        <?php include("../compartido/footer.php"); ?>
    </div>
</div>

<script src="../../config-general/assets/plugins/jquery/jquery.min.js"></script>
<script src="../../config-general/assets/plugins/popper/popper.js"></script>
<script src="../../config-general/assets/plugins/jquery-blockui/jquery.blockui.min.js"></script>
<script src="../../config-general/assets/plugins/jquery-slimscroll/jquery.slimscroll.js"></script>
<!-- bootstrap -->
<script src="../../config-general/assets/plugins/bootstrap/js/bootstrap.min.js"></script>
<script src="../../config-general/assets/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
<script src="../../config-general/assets/plugins/bootstrap-inputmask/bootstrap-inputmask.min.js"></script>
<script src="../../config-general/assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js" charset="UTF-8"></script>
<script src="../../config-general/assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker-init.js" charset="UTF-8"></script>
<script src="../../config-general/assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script src="../../config-general/assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker-init.js" charset="UTF-8"></script>



<!--select2-->
<script src="../../config-general/assets/plugins/select2/js/select2.js"></script>
<script src="../../config-general/assets/js/pages/select2/select2-init.js"></script>

</body>

<!-- Mirrored from radixtouch.in/templates/admin/smart/source/light/advance_form.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 18 May 2018 17:32:54 GMT -->

</html>