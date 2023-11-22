<?php

if (!Modulos::validarSubRol([$idPaginaInterna])) {
    echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
    exit();
} ?>


<!--select2-->
<link href="../../config-general/assets/plugins/select2/css/select2.css" rel="stylesheet" type="text/css" />
<link href="../../config-general/assets/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
<div class="col-sm-12">

    <div class="panel">
        <header class="panel-heading panel-heading-purple">Informes Academicos</header>
        <div class="panel-body">
            <form action="../compartido/reporte-matriculados-estado.php" method="post" class="form-horizontal" enctype="multipart/form-data" target="_blank">

                <div class="form-group row">
                    <label class="col-sm-2 control-label">Curso</label>
                    <div class="col-sm-10">
                        <select class="form-control  select2" name="cursosR">
                            <option value=""></option>
                            <?php
                            try {
                                $c_cursos = mysqli_query($conexion, "SELECT gra_id, gra_codigo, gra_nombre, gra_formato_boletin, gra_valor_matricula, gra_valor_pension, gra_estado FROM ".BD_ACADEMICA.".academico_grados WHERE institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]} ORDER BY gra_codigo;");
                            } catch (Exception $e) {
                                include("../compartido/error-catch-to-report.php");
                            }
                            while ($r_cursos = mysqli_fetch_array($c_cursos, MYSQLI_BOTH)) {
                                echo '<option value="' . $r_cursos["gra_id"] . '">' . $r_cursos["gra_nombre"] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 control-label">Grupos</label>
                    <div class="col-sm-10">
                        <select class="form-control  select2" name="gruposR">
                            <option value=""></option>
                            <?php
                            try {
                                $c_grupos = mysqli_query($conexion, "SELECT gru_id, gru_codigo, gru_nombre FROM ".BD_ACADEMICA.".academico_grupos WHERE institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]} ORDER BY gru_nombre;");
                            } catch (Exception $e) {
                                include("../compartido/error-catch-to-report.php");
                            }
                            while ($r_grupos = mysqli_fetch_array($c_grupos, MYSQLI_BOTH)) {
                                echo '<option value="' . $r_grupos["gru_id"] . '">' . $r_grupos["gru_nombre"] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 control-label">Estado</label>
                    <div class="col-sm-10">
                        <select class="form-control  select2" name="estadoR">
                            <option value=""></option>
                            <option value="1">Matriculado</option>
                            <option value="2">Asistente</option>
                            <option value="3">Cancelado</option>
                            <option value="4">No matriculado</option>
                            <option value="5">En inscripción</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 control-label">Tipo de estudiante</label>
                    <div class="col-sm-10">
                        <select class="form-control  select2" name="tipoR">
                            <option value=""></option>
                            <?php
                            try {
                                $c_testudiante = mysqli_query($conexion, "SELECT ogen_id, ogen_nombre, ogen_grupo FROM " . $baseDatosServicios . ".opciones_generales WHERE ogen_grupo=5;");
                            } catch (Exception $e) {
                                include("../compartido/error-catch-to-report.php");
                            }
                            while ($r_testudiante = mysqli_fetch_array($c_testudiante, MYSQLI_BOTH)) {
                                echo '<option value="' . $r_testudiante["ogen_id"] . '">' . $r_testudiante["ogen_nombre"] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 control-label">Acudiente</label>
                    <div class="col-sm-10">
                        <select class="form-control  select2" name="acudienteR">
                            <option value=""></option>
                            <option value="1">Si</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 control-label">Estudiante de Inclusión</label>
                    <div class="col-sm-10">
                        <select class="form-control  select2" name="inclu">
                            <option value=""></option>
                            <option value="1">Si</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 control-label">Estudiante Extranjero</label>
                    <div class="col-sm-10">
                        <select class="form-control  select2" name="extra">
                            <option value=""></option>
                            <option value="1">Si</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 control-label">Foto</label>
                    <div class="col-sm-10">
                        <select class="form-control  select2" name="fotoR">
                            <option value=""></option>
                            <option value="1">Si</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 control-label">Genero</label>
                    <div class="col-sm-10">
                        <select class="form-control  select2" name="generoR">
                            <option value=""></option>
                            <?php
                            try {
                                $c_testudiante = mysqli_query($conexion, "SELECT ogen_id, ogen_nombre, ogen_grupo FROM " . $baseDatosServicios . ".opciones_generales WHERE ogen_grupo=4;");
                            } catch (Exception $e) {
                                include("../compartido/error-catch-to-report.php");
                            }
                            while ($r_testudiante = mysqli_fetch_array($c_testudiante, MYSQLI_BOTH)) {
                                echo '<option value="' . $r_testudiante["ogen_id"] . '">' . $r_testudiante["ogen_nombre"] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 control-label">Religi&oacute;n</label>
                    <div class="col-sm-10">
                        <select class="form-control  select2" name="religionR">
                            <option value=""></option>
                            <?php
                            try {
                                $c_testudiante = mysqli_query($conexion, "SELECT ogen_id, ogen_nombre, ogen_grupo FROM " . $baseDatosServicios . ".opciones_generales WHERE ogen_grupo=2;");
                            } catch (Exception $e) {
                                include("../compartido/error-catch-to-report.php");
                            }
                            while ($r_testudiante = mysqli_fetch_array($c_testudiante, MYSQLI_BOTH)) {
                                echo '<option value="' . $r_testudiante["ogen_id"] . '">' . $r_testudiante["ogen_nombre"] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 control-label">Estrato</label>
                    <div class="col-sm-10">
                        <select class="form-control  select2" name="estratoE">
                            <option value=""></option>
                            <?php
                            try {
                                $c_testudiante = mysqli_query($conexion, "SELECT ogen_id, ogen_nombre, ogen_grupo FROM " . $baseDatosServicios . ".opciones_generales WHERE ogen_grupo=3;");
                            } catch (Exception $e) {
                                include("../compartido/error-catch-to-report.php");
                            }
                            while ($r_testudiante = mysqli_fetch_array($c_testudiante, MYSQLI_BOTH)) {
                                echo '<option value="' . $r_testudiante["ogen_id"] . '">' . $r_testudiante["ogen_nombre"] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 control-label">Tipo de documento</label>
                    <div class="col-sm-10">
                        <select class="form-control  select2" name="tdocumentoR">
                            <option value=""></option>
                            <?php
                            try {
                                $c_testudiante = mysqli_query($conexion, "SELECT ogen_id, ogen_nombre, ogen_grupo FROM " . $baseDatosServicios . ".opciones_generales WHERE ogen_grupo=1;");
                            } catch (Exception $e) {
                                include("../compartido/error-catch-to-report.php");
                            }
                            while ($r_testudiante = mysqli_fetch_array($c_testudiante, MYSQLI_BOTH)) {
                                echo '<option value="' . $r_testudiante["ogen_id"] . '">' . $r_testudiante["ogen_nombre"] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <input type="submit" class="btn btn-info" value="Consultar Informe" name="consultas">
            </form>
        </div>
    </div>
</div>
<!--select2-->
<script src="../../config-general/assets/plugins/select2/js/select2.js"></script>
<script src="../../config-general/assets/js/pages/select2/select2-init.js"></script>