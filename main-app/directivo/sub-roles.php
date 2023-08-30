<?php
include("session.php");
$idPaginaInterna = 'DT0204';
require_once("../class/SubRoles.php");
include("../compartido/historial-acciones-guardar.php");
Modulos::verificarPermisoDev();
$listaRoles=SubRoles::listar();
include("../compartido/head.php");


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
                            <div class="page-title">Sub Roles</div>
                            <?php include("../../config-general/mensajes-informativos.php"); ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            

                            <div class="col-md-12">
                                <div class="card card-topline-purple">
                                    <div class="card-head">
                                        <header>Sub Roles</header>
                                        <div class="tools">
                                            <a class="fa fa-repeat btn-color box-refresh" href="javascript:;"></a>
                                            <a class="t-collapse btn-color fa fa-chevron-down" href="javascript:;"></a>
                                            <a class="t-close btn-color fa fa-times" href="javascript:;"></a>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                    <div class="row" style="margin-bottom: 10px;">
												<div class="col-sm-12">
													<div class="btn-group">
														<?php if(Modulos::validarPermisoEdicion()){?>
															<a href="sub-roles-agregar.php" id="addRow" class="btn deepPink-bgcolor">
																Agregar nuevo <i class="fa fa-plus"></i>
															</a>
														<?php }?>
													</div>

													
													
												</div>
											</div>
                                        <div >
                                            <table id="example1"  style="width:100%;">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Cod</th>
                                                        <th>Rol</th>
                                                        <th>Institucion</th>
                                                        <th>Paginas</th>
                                                        <th>Año</th>
                                                        <th  style="width:10%;"><?= $frases[54][$datosUsuarioActual[8]]; ?> </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
													
                                                    $contReg = 1;
                                                    while ($resultado = mysqli_fetch_array($listaRoles, MYSQLI_BOTH)) {
                                                        $subRol=SubRoles::consultar($resultado['subr_id']);
                                                        ?>
                                                        <tr>
                                                            <td><?= $contReg; ?></td>
                                                            <td><?= $resultado['subr_id']; ?></td>
                                                            <td><?= $resultado['subr_nombre']; ?></td>
                                                            <td><?= $resultado['ins_nombre']; ?></td>
                                                            <td><?php if(!empty($subRol['paginas']) && $subRol['paginas']!=null){
                                                                    echo count($subRol['paginas']);
                                                                  }else{echo 0;}?></td>
                                                            <td><?= $resultado['subr_year']; ?></td>
                                                            <td>
                                                                <div class="btn-group">
                                                                    <button type="button" class="btn btn-primary"><?= $frases[54][$datosUsuarioActual[8]]; ?></button>
                                                                    <button type="button" class="btn btn-primary dropdown-toggle m-r-20" data-toggle="dropdown">
                                                                        <i class="fa fa-angle-down"></i>
                                                                    </button>
                                                                    <ul class="dropdown-menu" role="menu">
                                                                        <li><a href="sub-roles-editar.php?id=<?= $resultado['subr_id'];?>"><?= $frases[165][$datosUsuarioActual[8]]; ?></a></li>
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