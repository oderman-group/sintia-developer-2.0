<?php
include("session.php");

$idPaginaInterna = 'DV0004';

include("../compartido/historial-acciones-guardar.php");
include("../compartido/head.php");

$Plataforma = new Plataforma;
?>
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
                            <div class="page-title">Historial de acciones</div>
                            <?php include("../compartido/texto-manual-ayuda.php"); ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <?php
                                $filtro = '';
                                if (is_numeric($_GET["insti"])) {
                                    $filtro .= " AND hil_institucion='" . $_GET["insti"] . "'";
                                }
                                if (!empty($_GET["fFecha"]) || (!empty($_GET["desde"]) || !empty($_GET["hasta"]))) {
                                    $filtro .= " AND (hil_fecha BETWEEN '" . $_GET["desde"] . "' AND '" . $_GET["hasta"] . "' OR hil_fecha LIKE '%" . $_GET["hasta"] . "%')";
                                }
                                if (!empty($_GET["year"])) {
                                    $filtro .= " AND YEAR(hil_fecha) =".$_GET["year"];
                                }                         
                            ?>

                            <div class="col-md-12">
                                <?php
                                include("../../config-general/mensajes-informativos.php");
                                include("includes/barra-superior-dev-historial-acciones.php");
                                ?>

                                <div class="card card-topline-purple">
                                    <div class="card-head">
                                        <header>Historial de acciones</header>
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
                                                        <th>Fecha</th>
                                                        <th>Pagina</th>
                                                        <th>Responsable</th>
                                                        <th>Tiempo de carga</th>
                                                        <th>Institución</th>
                                                        <th>Autologin</th>
                                                        <th><?= $frases[54][$datosUsuarioActual[8]]; ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
													include("includes/consulta-paginacion-dev-historial-acciones.php");

                                                    $consulta = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".seguridad_historial_acciones
                                                    LEFT JOIN ".$baseDatosServicios.".instituciones ON ins_id=hil_institucion
                                                    LEFT JOIN ".$baseDatosServicios.".paginas_publicidad ON pagp_id=hil_titulo
                                                    LEFT JOIN usuarios ON uss_id=hil_usuario
                                                    WHERE hil_id=hil_id $filtro
                                                    ORDER BY hil_id DESC
                                                    LIMIT $inicio,$registros;");
                                                    $contReg = 1;
                                                    while ($resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH)) {

                                                        $responsable=UsuariosPadre::nombreCompletoDelUsuario($resultado);
                                                        
                                                        $ussAutologin="NO";
                                                        if($resultado['hil_usuario_autologin']!=0){

                                                            $consultaUssAutologin= mysqli_query($conexion, "SELECT * FROM usuarios WHERE uss_id='".$resultado['hil_usuario_autologin']."'");
                                                            $datosUssAutologin = mysqli_fetch_array($consultaUssAutologin, MYSQLI_BOTH);
                                                            $ussAutologin=UsuariosPadre::nombreCompletoDelUsuario($datosUssAutologin);

                                                        }
                                                    ?>
                                                        <tr>
                                                            <td><?= $contReg; ?></td>
                                                            <td><?= $resultado['hil_id']; ?></td>
                                                            <td><?= $resultado['hil_fecha']; ?></td>
                                                            <td><?= $resultado['pagp_pagina']; ?></td>
                                                            <td><?= $responsable; ?></td>
                                                            <td><?= $resultado['hil_tiempo_carga']; ?></td>
                                                            <td><?= $resultado['ins_siglas']; ?></td>
                                                            <td><?= $ussAutologin; ?></td>
                                                            <td>
                                                                <div class="btn-group">
                                                                    <button type="button" class="btn btn-primary"><?= $frases[54][$datosUsuarioActual[8]]; ?></button>
                                                                    <button type="button" class="btn btn-primary dropdown-toggle m-r-20" data-toggle="dropdown">
                                                                        <i class="fa fa-angle-down"></i>
                                                                    </button>
                                                                    <ul class="dropdown-menu" role="menu">
                                                                        <li><a href="#<?= $resultado['hil_id']; ?>">Ver Reporte</a></li>
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