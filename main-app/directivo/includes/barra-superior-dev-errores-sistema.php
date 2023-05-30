<?php
if (!empty($_GET['busqueda'])) {
    $busqueda = $_GET['busqueda'];
    $filtro .= " AND (
        rperr_id LIKE '%" . $busqueda . "%' 
        OR rperr_numero LIKE '%" . $busqueda . "%' 
        OR rperr_error LIKE '%" . $busqueda . "%' 
        OR rperr_pagina_referencia LIKE '%" . $busqueda . "%' 
        OR ins_nombre LIKE '%" . $busqueda . "%' 
        OR ins_siglas LIKE '%" . $busqueda . "%' 
        OR uss_nombre LIKE '%".$busqueda."%' 
        OR uss_nombre2 LIKE '%".$busqueda."%' 
        OR uss_apellido1 LIKE '%".$busqueda."%' 
        OR uss_apellido2 LIKE '%".$busqueda."%' 
        OR CONCAT(TRIM(uss_nombre), ' ',TRIM(uss_apellido1), ' ', TRIM(uss_apellido2)) LIKE '%".$busqueda."%'
        OR CONCAT(TRIM(uss_nombre), TRIM(uss_apellido1), TRIM(uss_apellido2)) LIKE '%".$busqueda."%'
        OR CONCAT(TRIM(uss_nombre), ' ', TRIM(uss_apellido1)) LIKE '%".$busqueda."%'
        OR CONCAT(TRIM(uss_nombre), TRIM(uss_apellido1)) LIKE '%".$busqueda."%'
        )";
}
?>
<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #41c4c4;">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color:#FFF;">
                    Filtrar por institución
                    <span class="fa fa-angle-down"></span>
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <?php
                    $instituciones = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".instituciones 
                    WHERE ins_estado = 1 AND ins_enviroment='".ENVIROMENT."'");
                    while ($datosInsti = mysqli_fetch_array($instituciones, MYSQLI_BOTH)) {
                        $estiloResaltado = '';
                        if ($datosInsti['ins_id'] == $_GET["insti"]) $estiloResaltado = 'style="color: ' . $Plataforma->colorUno . ';"';
                    ?>
                        <a class="dropdown-item" href="<?= $_SERVER['PHP_SELF']; ?>?insti=<?= $datosInsti['ins_id']; ?>&desde=<?= $_GET['desde']; ?>&hasta=<?= $_GET['hasta']; ?>&busqueda=<?= $_GET['busqueda']; ?>&year=<?= $_GET['year']; ?>" <?= $estiloResaltado; ?>><?= $datosInsti['ins_siglas']; ?></a>
                    <?php } ?>
                    <a class="dropdown-item" href="<?= $_SERVER['PHP_SELF']; ?>" style="font-weight: bold; text-align: center;">VER TODO</a>
                </div>
            </li>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color:#FFF;">
                    Filtrar por Fecha
                    <span class="fa fa-angle-down"></span>
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    
                    <form class="dropdown-item" method="get" action="<?= $_SERVER['PHP_SELF']; ?>">
                        <?php
                            if (!empty($_GET['insti'])){
                        ?>
                            <input type="hidden" name="insti" value="<?= $_GET['insti']; ?>"/>
                        <?php
                            }
                            if (!empty($_GET['busqueda'])){
                        ?>
                            <input type="hidden" name="busqueda" value="<?= $_GET['busqueda']; ?>"/>
                        <?php
                            }
                            if (!empty($_GET['year'])){
                        ?>
                            <input type="hidden" name="year" value="<?= $_GET['year']; ?>"/>
                        <?php
                            }
                        ?>
                        <label>Fecha Desde:</label>
                        <input type="date" class="form-control" placeholder="desde"  name="desde" value="<?php if (!empty($_GET['desde'])) echo $_GET['desde']; ?>"/>

                        <label>Hasta</label>
                        <input type="date" class="form-control" placeholder="hasta"  name="hasta" value="<?php if (!empty($_GET['hasta'])) echo $_GET['hasta']; ?>"/>
                        
                        <input type="submit" class="btn deepPink-bgcolor" name="fFecha" value="Filtrar" style="margin: 5px;">
                    </form>
                    <a class="dropdown-item" href="<?= $_SERVER['PHP_SELF']; ?>" style="font-weight: bold; text-align: center;">VER TODO</a>
                </div>
            </li>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color:#FFF;">
                    Filtrar por año
                    <span class="fa fa-angle-down"></span>
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <?php
                        $yearStartC=$yearStart;
                        $yearEndC=$yearEnd;
                        while($yearStartC <= $yearEndC){
                            $estiloResaltado = '';
                            if ($yearStartC == $_GET["year"]){ 
                                $estiloResaltado = 'style="color: ' . $Plataforma->colorUno . ';"';
                            }
                    ?>
                        <a class="dropdown-item" href="<?= $_SERVER['PHP_SELF']; ?>?insti=<?= $datosInsti['ins_id']; ?>&desde=<?= $_GET['desde']; ?>&hasta=<?= $_GET['hasta']; ?>&busqueda=<?= $_GET['busqueda']; ?>&year=<?= $yearStartC; ?>" <?= $estiloResaltado; ?>><?= $yearStartC; ?></a>
                    <?php 
                            $yearStartC++;
                        } 
                    ?>
                    <a class="dropdown-item" href="<?= $_SERVER['PHP_SELF']; ?>" style="font-weight: bold; text-align: center;">VER TODO</a>
                </div>
            </li>

        </ul>

        <form class="form-inline my-2 my-lg-0" action="<?= $_SERVER['PHP_SELF']; ?>" method="get">
            <?php
                if (!empty($_GET['insti'])){
            ?>
                <input type="hidden" name="insti" value="<?= $_GET['insti']; ?>"/>
            <?php
                }
                if (!empty($_GET['desde']) || !empty($_GET['hasta'])){
            ?>
                <input type="hidden" name="desde" value="<?= $_GET['desde']; ?>"/>
                <input type="hidden" name="hasta" value="<?= $_GET['hasta']; ?>"/>
            <?php
                }
                if (!empty($_GET['year'])){
            ?>
                <input type="hidden" name="year" value="<?= $_GET['year']; ?>"/>
            <?php
                }
            ?>
            <input class="form-control mr-sm-2" type="search" placeholder="Búsqueda..." aria-label="Search" name="busqueda" value="<?php if (isset($_GET['busqueda'])) echo $_GET['busqueda']; ?>">
            <button class="btn deepPink-bgcolor my-2 my-sm-0" type="submit">Buscar</button>
        </form>

    </div>
</nav>