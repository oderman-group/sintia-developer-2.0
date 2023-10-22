<?php
include("session.php");

$idPaginaInterna = 'DT0205';

include("../compartido/historial-acciones-guardar.php");

include("../compartido/head.php");

if(!Modulos::validarSubRol([$idPaginaInterna])){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
	exit();
}

$id="";
if(!empty($_GET["id"])){ $id=base64_decode($_GET["id"]);}

require_once("../class/SubRoles.php");
$rolActual = SubRoles::consultar($id);
$activasTodas=empty($_GET["activas"]) ?"0":"1";
$checkActivas=($activasTodas=="0")?"":"checked";
$listaPaginas = SubRoles::listarPaginas($id,"5",$activasTodas);

?>
<!-- Theme Styles -->
<link href="../../config-general/assets/css/pages/formlayout.css" rel="stylesheet" type="text/css" />
<!--tagsinput-->
<link href="../../config-general/assets/plugins/jquery-tags-input/jquery-tags-input.css" rel="stylesheet">
<!-- data tables -->
<link href="../../config-general/assets/plugins/datatables/plugins/bootstrap/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
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
                        <?php include("../compartido/texto-manual-ayuda.php");?>
                        <div class=" pull-left">
                            <div class="page-title"><?= $frases[17][$datosUsuarioActual['uss_idioma']]; ?> Sub Rol</div>
                        </div>
                        <ol class="breadcrumb page-breadcrumb pull-right">
                            <li><a class="parent-item" href="javascript:void(0);" name="sub-roles.php?cantidad=10" onClick="deseaRegresar(this)">Sub Roles</a>&nbsp;<i class="fa fa-angle-right"></i></li>
                            <li class="active"><?= $frases[17][$datosUsuarioActual['uss_idioma']]; ?> Sub Rol</li>
                        </ol>
                    </div>
                </div>
                <?php include("../../config-general/mensajes-informativos.php"); ?>
                <div class="panel">
                    <header class="panel-heading panel-heading-purple"><?= $frases[17][$datosUsuarioActual['uss_idioma']]; ?></header>
                    <div class="panel-body">
                        <form action="sub-roles-actualizar.php" method="post" enctype="multipart/form-data">
                            <i class="bi bi-eye-slash"></i>
                            <div class="form-group row">
                                <label class="col-sm-2 "><?= $frases[187][$datosUsuarioActual['uss_idioma']]; ?> Sub Rol:</label>
                                <div class="col-sm-1">
                                    <input type="text" name="subr_id" class="form-control" value="<?= $rolActual['subr_id']; ?>" readonly>
                                </div>
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="material-icons">group</i></span>
                                        </div>
                                        <input type="text" class="form-control" name="nombre" value="<?= $rolActual['subr_nombre']; ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2">Usuarios:</label>
                                <div class="col-sm-6">
                                    <select class="form-control select2" name="directivos[]" multiple>
                                        <option value="">Seleccione una opción</option>
                                        <?php 
                                            $consultaDirectivos = UsuariosPadre::obtenerTodosLosDatosDeUsuarios(" AND uss_tipo=".TIPO_DIRECTIVO." AND uss_bloqueado=0");
                                            while($directivos=mysqli_fetch_array($consultaDirectivos, MYSQLI_BOTH)){
                                                $selected="";
                                                if(SubRoles::validarExistenciaUsuarioRol($directivos["uss_id"],$id)>0){
                                                    $selected="selected";
                                                }
                                        ?>
                                            <option value="<?=$directivos["uss_id"];?>" <?=$selected?>><?=UsuariosPadre::nombreCompletoDelUsuario($directivos);?></option>
                                        <?php
                                            }
                                        ?>
                                    </select>
                                </div>
                                
                                <div class="col-sm-3">
                                    <button type="submit" class="btn btn-success"><?=$frases[331][$datosUsuarioActual['uss_idioma']];?> </button>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <div class="card card-topline-purple">
                                        <div class="card-head">
                                            <header><?= $frases[370][$datosUsuarioActual['uss_idioma']]; ?> ( <label style="font-weight: bold;" id="cantSeleccionadas"></label>/<?= mysqli_num_rows($listaPaginas) ?> )
                                                <label class="switchToggle">
                                                    <input type="checkbox" <?= $checkActivas; ?> onchange="mostrarActivas(this.checked,'<?= $_GET['id']; ?>')">
                                                    <span class="slider red round"></span>
                                                </label>
                                            </header>
                                            Mostrar solo activas
                                        </div>
                                        <div class="card-body">
                                            <div>
                                                <table id="example3" class="display" name="tabla1" style="width:100%;">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>
                                                                <div class="input-group spinner col-sm-10">
                                                                    <label class="switchToggle">
                                                                        <input type="checkbox" id="all">
                                                                        <span class="slider red round"></span>
                                                                    </label>
                                                                </div>
                                                            </th>
                                                            <th>Id</th>
                                                            <th><?= $frases[115][$datosUsuarioActual['uss_idioma']]; ?></th>
                                                            <th>Modulo</th>
                                                            <th><?= $frases[228][$datosUsuarioActual['uss_idioma']]; ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $contReg = 1;
                                                        while ($pagina = mysqli_fetch_array($listaPaginas, MYSQLI_BOTH)) {
                                                            $cheked = '';
                                                            if (!empty($rolActual["paginas"])) {
                                                                $selecionado = array_key_exists($pagina["pagp_id"], $rolActual["paginas"]);
                                                                if ($selecionado) {
                                                                    $cheked = 'checked';
                                                                }
                                                            }

                                                        ?>
                                                            <tr>
                                                                <td><?= $contReg; ?></td>
                                                                <td>
                                                                    <div class="input-group spinner col-sm-10">
                                                                        <label class="switchToggle">
                                                                            <input type="checkbox" class="check" id="<?= $pagina['pagp_paginas_dependencia']; ?>" onchange="validarPaginasDependencia(this)" value="<?= $pagina['pagp_id']; ?>" <?= $cheked; ?>>
                                                                            <span class="slider red round"></span>
                                                                        </label>
                                                                    </div>
                                                                </td>
                                                                <td><?= $pagina['pagp_id']; ?></td>
                                                                <td><?= $pagina['pagp_pagina']; ?></td>
                                                                <td><?= $pagina['mod_nombre']; ?></td>
                                                                <td><?= $pagina['pagp_palabras_claves']; ?></td>

                                                            </tr>
                                                        <?php $contReg++;
                                                        } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <select id="paginasSeleccionadas" style="width: 100% !important" name="paginas[]" multiple hidden>
                                <?php
                                foreach ($rolActual["paginas"] as $page) {
                                    echo '<option value="' . $page["pagp_id"] . '"  selected >' . $page["pagp_id"] . '</option>';
                                }
                                ?>
                            </select>
                    </div>
                    <div class="form-group">
                        <div class="col-md-9">
                            <button type="submit" class="btn btn-warning"><?= $frases[331][$datosUsuarioActual['uss_idioma']]; ?></button>
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
<!-- end page container -->
<?php include("../compartido/footer.php"); ?>
<script src="../js/Subroles.js" ></script>
<!-- start js include path -->
<script src="../../config-general/assets/plugins/jquery/jquery.min.js"></script>
<script src="../../config-general/assets/plugins/popper/popper.js"></script>
<script src="../../config-general/assets/plugins/jquery-blockui/jquery.blockui.min.js"></script>
<script src="../../config-general/assets/plugins/jquery-slimscroll/jquery.slimscroll.js"></script>
<!-- bootstrap -->
<script src="../../config-general/assets/plugins/bootstrap/js/bootstrap.min.js"></script>
<script src="../../config-general/assets/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
<!-- data tables -->
<script src="../../config-general/assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../../config-general/assets/plugins/datatables/plugins/bootstrap/dataTables.bootstrap4.min.js"></script>
<script src="../../config-general/assets/js/pages/table/table_data.js"></script>
<!-- Common js-->
<script src="../../config-general/assets/js/app.js"></script>
<script src="../../config-general/assets/js/layout.js"></script>
<script src="../../config-general/assets/js/theme-color.js"></script>
<!-- notifications -->
<script src="../../config-general/assets/plugins/jquery-toast/dist/jquery.toast.min.js"></script>
<script src="../../config-general/assets/plugins/jquery-toast/dist/toast.js"></script>
<!-- Material -->
<script src="../../config-general/assets/plugins/material/material.min.js"></script>
<!--select2-->
<script src="../../config-general/assets/plugins/select2/js/select2.js" ></script>
<script src="../../config-general/assets/js/pages/select2/select2-init.js" ></script>
<!-- end js include path -->

</body>

</html>