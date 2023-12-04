<?php
include("session.php");
$idPaginaInterna = 'DV0043';
include("../compartido/historial-acciones-guardar.php");
Modulos::verificarPermisoDev();
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
    <?php
        include("../compartido/encabezado.php");
        include("../compartido/panel-color.php");
    ?>
    <!-- start page container -->
    <div class="page-container">
        <?php include("../compartido/menu.php"); ?>
        <!-- start page content -->
        <div class="page-content-wrapper">
            <div class="page-content">
                <div class="page-bar">
                    <div class="page-title-breadcrumb">
                        <div class=" pull-left">
                            <div class="page-title">Categorias Productos</div>
                            <?php include("../compartido/texto-manual-ayuda.php"); ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12">
                                <?php
                                    include("../../config-general/mensajes-informativos.php");
                                    include("includes/barra-superior-mps-categorias-productos.php");
                                ?>
                                <span id="respuestaGuardar"></span>

                                <div class="card card-topline-purple">
                                    <div class="card-head">
                                        <header>Categorias Productos</header>
                                    </div>
                                    <div class="card-body">

                                        <div class="row" style="margin-bottom: 10px;">
                                            <div class="col-sm-12">
                                                <div class="btn-group">
                                                    <a href="mps-categorias-productos-agregar.php" id="addRow" class="btn deepPink-bgcolor">
                                                        Agregar nuevo <i class="fa fa-plus"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="table-scrollable">
                                            <table id="example1" class="display" style="width:100%;">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Nombre</th>
                                                        <th><?= $frases[54][$datosUsuarioActual['uss_idioma']]; ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        include("includes/consulta-paginacion-mps-categorias-productos.php");

                                                        try {
                                                            $consulta = mysqli_query($conexion, "SELECT * FROM " . $baseDatosMarketPlace . ".categorias_productos
                                                            WHERE catp_eliminado!=1 $filtro
                                                            ORDER BY catp_id
                                                            LIMIT $inicio,$registros;");
                                                        } catch (Exception $e) {
                                                            include("../compartido/error-catch-to-report.php");
                                                        }
                                                        $arrayEnviar = array("tipo" => 1, "descripcionTipo" => "Para ocultar fila del registro.");
                                                        $arrayDatos = json_encode($arrayEnviar);
                                                        $objetoEnviar = htmlentities($arrayDatos);

                                                        $contReg = 1;
                                                        while ($resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH)) {
                                                    ?>
                                                        <tr id="reg<?= $resultado['catp_id']; ?>">
                                                            <td><?= $contReg; ?></td>
                                                            <td><?= $resultado['catp_nombre']; ?></td>
                                                            <td>
                                                                <div class="btn-group">
                                                                    <button type="button" class="btn btn-primary"><?= $frases[54][$datosUsuarioActual['uss_idioma']]; ?></button>
                                                                    <button type="button" class="btn btn-primary dropdown-toggle m-r-20" data-toggle="dropdown">
                                                                        <i class="fa fa-angle-down"></i>
                                                                    </button>
                                                                    <ul class="dropdown-menu" role="menu">
                                                                        <li><a href="mps-categorias-productos-aditar.php?idR=<?= base64_encode($resultado['catp_id']); ?>">Editar</a></li>
                                                                        <li><a href="javascript:void(0);" title="<?= $objetoEnviar; ?>" id="<?= $resultado['catp_id']; ?>" name="mps-categorias-productos-eliminar.php?idR=<?= base64_encode($resultado['catp_id']); ?>" onClick="deseaEliminar(this)">Eliminar</a></li>
                                                                    </ul>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php $contReg++; } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <?php include("enlaces-paginacion.php"); ?>
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