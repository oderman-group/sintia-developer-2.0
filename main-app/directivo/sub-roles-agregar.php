<?php
include("session.php");

$idPaginaInterna = 'DT0206';

include("../compartido/historial-acciones-guardar.php");
Modulos::verificarPermisoDev();
include("../compartido/head.php");

require_once("../class/SubRoles.php");
$listaRoles=SubRoles::listar();


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
                                <div class="page-title">Crear Sub Rol</div>
								<?php include("../compartido/texto-manual-ayuda.php");?>
                            </div>
							<ol class="breadcrumb page-breadcrumb pull-right">
                                <li><a class="parent-item" href="#" name="sub-roles.php?cantidad=10" onClick="deseaRegresar(this)">Sub Roles</a>&nbsp;<i class="fa fa-angle-right"></i></li>
                                <li class="active">Crear Sub Rol</li>
                            </ol>
                        </div>
                    </div>

                <div class="panel">
                    <header class="panel-heading panel-heading-purple">Configuracion</header>
                    <div class="panel-body">
                        <form action="sub-roles-guardar.php" method="post" enctype="multipart/form-data">
                            <i class="bi bi-eye-slash"></i>

                            <div class="form-group row">
                                <label class="col-sm-2 ">Nombre del sub rol:</label>

                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="material-icons">group</i></span>
                                        </div>
                                        <input type="text" class="form-control" name="nombre" required>
                                    </div>

                                </div>
                                <div class="col-sm-3">
                                    <button type="submit" class="btn btn-success">Guardar</button>
                                    

                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <div class="card card-topline-purple">
                                        <div class="card-head">
                                            <header>Paginas Disponibles</header>
                                        </div>
                                        <div class="card-body">

                                            <div>  
                                                <table id="example3" class="display" name="tabla1" style="width:100%;">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Activa</th>
                                                            <th>Id</th>
                                                            <th>Pagina</th>
                                                            <th>Modulo</th>
                                                            <th>Palabras Claves</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $contReg = 1;
                                                        $listaPaginas = SubRoles::listarPaginas();
                                                        while ($pagina = mysqli_fetch_array($listaPaginas, MYSQLI_BOTH)) {

                                                        ?>
                                                            <tr >
                                                                <td><?= $contReg; ?></td>
                                                                <td>
                                                                    <div class="input-group spinner col-sm-10">
                                                                        <label class="switchToggle">
                                                                            <input type="checkbox" name="paginas[]" value="<?= $pagina['pagp_id']; ?>">
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
                    </div>
                    <div class="form-group">
                        <div class="offset-md-3 col-md-9">
                            <button type="submit" class="btn btn-success">Guardar</button>
                            <a href="#" name="sub-roles.php" onClick="deseaRegresar(this)" class="btn btn-round btn-danger">Regresar</a>
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
<script type="text/javascript">
    function agregarPagina(datos) {

        var idR = datos.id;
        alert("iniivio" + idR);
        // Valor que deseas agregar al array
        var nuevoValor = "Nuevo valor desde JavaScript";

        // Crear una instancia de XMLHttpRequest
        var xhttp = new XMLHttpRequest();

        // Definir el método y la URL del archivo PHP
        xhttp.open("POST", "add_value.php", true);

        // Configurar el encabezado de la solicitud
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        // Enviar la solicitud con el valor
        xhttp.send("valor=" + nuevoValor);
        var valor = 0;

        if (document.getElementById(idR).checked) {
            valor = 1;
            document.getElementById("Reg" + idR).style.backgroundColor = "#ff572238";
        } else {
            valor = 0;
            document.getElementById("Reg" + idR).style.backgroundColor = "white";
        }
        var operacion = 3;
        alert(datos.id);

    }
</script>
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