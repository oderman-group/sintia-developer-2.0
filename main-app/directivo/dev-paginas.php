<?php
include("session.php");

$idPaginaInterna = 'DV0006';

include("../compartido/historial-acciones-guardar.php");

Modulos::verificarPermisoDev($datosUsuarioActual['uss_permiso1']);

include("../compartido/head.php");

$Plataforma = new Plataforma;
?>
<!-- Theme Styles -->
    <link href="../../config-general/assets/css/pages/formlayout.css" rel="stylesheet" type="text/css" />
<!--tagsinput-->
    <link href="../../config-general/assets/plugins/jquery-tags-input/jquery-tags-input.css" rel="stylesheet">
<!-- data tables -->
<link href="../../config-general/assets/plugins/datatables/plugins/bootstrap/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
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
                            <div class="page-title">Modulos</div>
                            <?php include("../compartido/texto-manual-ayuda.php"); ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <?php
                                $filtro = '';
                                if (is_numeric($_GET["uss"])) {
                                    $filtro .= " AND pagp_tipo_usuario='" . $_GET["uss"] . "'";
                                }
                                if (is_numeric($_GET["modulo"])) {
                                    $filtro .= " AND pagp_modulo='" . $_GET["modulo"] . "'";
                                }
                            ?>

                            <div class="col-md-12">
                                <?php
                                include("../../config-general/mensajes-informativos.php");
                                include("includes/barra-superior-dev-paginas.php");
                                ?>
                                <span id="respuestaGuardar"></span>

                                <div class="card card-topline-purple">
                                    <div class="card-head">
                                        <header>Modulos</header>
                                        <div class="tools">
                                            <a class="fa fa-repeat btn-color box-refresh" href="javascript:;"></a>
                                            <a class="t-collapse btn-color fa fa-chevron-down" href="javascript:;"></a>
                                            <a class="t-close btn-color fa fa-times" href="javascript:;"></a>
                                        </div>
                                    </div>
                                    <div class="card-body">

                                        <div class="table-scrollable">
                                            <table id="example1" class="display" style="width:100%;">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Cod</th>
                                                        <th>Pagina</th>
                                                        <th>Archivo</th>
                                                        <th>Modulo</th>
                                                        <th>Usuario</th>
                                                        <th><?= $frases[54][$datosUsuarioActual[8]]; ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
													include("includes/consulta-paginacion-dev-paginas.php");

                                                    $consulta = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".paginas_publicidad
                                                    LEFT JOIN ".$baseDatosServicios.".modulos ON mod_id=pagp_modulo
                                                    LEFT JOIN ".$baseDatosServicios.".general_perfiles ON pes_id=pagp_tipo_usuario
                                                    WHERE pagp_id=pagp_id $filtro
                                                    ORDER BY pagp_id
                                                    LIMIT $inicio,$registros;");
                                                    $contReg = 1;
                                                    while ($resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH)) {
                                                    ?>
                                                        <tr>
                                                            <td><?= $contReg; ?></td>
                                                            <td><?= $resultado['pagp_id']; ?></td>
                                                            <td><?= $resultado['pagp_pagina']; ?></td>
                                                            <td><?= $resultado['pagp_ruta']; ?></td>
                                                            <td><?= $resultado['mod_nombre']; ?></td>
                                                            <td><?= $resultado['pes_nombre']; ?></td>
                                                            <td>
                                                                <div class="btn-group">
                                                                    <button type="button" class="btn btn-primary"><?= $frases[54][$datosUsuarioActual[8]]; ?></button>
                                                                    <button type="button" class="btn btn-primary dropdown-toggle m-r-20" data-toggle="dropdown">
                                                                        <i class="fa fa-angle-down"></i>
                                                                    </button>
                                                                    <ul class="dropdown-menu" role="menu">
                                                                        <li><a href="#<?= $resultado['pagp_id']; ?>">Editar</a></li>
                                                                    </ul>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php $contReg++;
                                                    } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <?php include("enlaces-paginacion.php");?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end page container -->
    <?php include("../compartido/footer.php"); ?>
</div>
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
<!-- end js include path -->
</body>

</html>