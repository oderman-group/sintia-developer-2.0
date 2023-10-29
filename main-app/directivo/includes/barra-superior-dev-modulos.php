<?php
if (isset($_GET['busqueda'])) {
    $busqueda = $_GET['busqueda'];
    $filtro .= " AND (
        mod_id LIKE '%" . $busqueda . "%' 
        OR mod_nombre LIKE '%" . $busqueda . "%' 
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
                <a class="nav-link dropdown-toggle" href="javascript:void(0);" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color:#FFF;">
                    Filtrar Por Modulo Padre
                    <span class="fa fa-angle-down"></span>
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <?php
                    try{
                        $consultaFiltro = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".modulos");
                    } catch (Exception $e) {
                        include("../compartido/error-catch-to-report.php");
                    }
                    while ($datosFiltro = mysqli_fetch_array($consultaFiltro, MYSQLI_BOTH)) {
                        $estiloResaltado = '';
                        if ($datosFiltro['mod_id'] == $_GET["modPadre"]) $estiloResaltado = 'style="color: ' . $Plataforma->colorUno . ';"';
                    ?>
                        <a class="dropdown-item" href="<?= $_SERVER['PHP_SELF']; ?>?modPadre=<?= $datosFiltro['mod_id']; ?>&estado=<?= $_GET["estado"]; ?>" <?= $estiloResaltado; ?>><?= $datosFiltro['mod_nombre']; ?></a>
                    <?php } ?>
                    <a class="dropdown-item" href="<?= $_SERVER['PHP_SELF']; ?>" style="font-weight: bold; text-align: center;">VER TODO</a>
                </div>
            </li>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="javascript:void(0);" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color:#FFF;">
                    Filtrar Por Estado
                    <span class="fa fa-angle-down"></span>
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="<?= $_SERVER['PHP_SELF']; ?>?estado=1&modPadre=<?= $_GET["modPadre"]; ?>">Activo</a>
                    <a class="dropdown-item" href="<?= $_SERVER['PHP_SELF']; ?>?estado=0&modPadre=<?= $_GET["modPadre"]; ?>">Inactivo</a>
                    <a class="dropdown-item" href="<?= $_SERVER['PHP_SELF']; ?>" style="font-weight: bold; text-align: center;">VER TODO</a>
                </div>
            </li>

        </ul>

        <form class="form-inline my-2 my-lg-0" action="<?= $_SERVER['PHP_SELF']; ?>" method="get">
            <?php
                if (!empty($_GET['modPadre'])){
            ?>
                <input type="hidden" name="modPadre" value="<?= $_GET['modPadre']; ?>"/>
            <?php
                }
                if (!empty($_GET['estado'])){
            ?>
                <input type="hidden" name="estado" value="<?= $_GET['estado']; ?>"/>
            <?php
                }
            ?>
            <input class="form-control mr-sm-2" type="search" placeholder="Búsqueda..." aria-label="Search" name="busqueda" value="<?php if (isset($_GET['busqueda'])) echo $_GET['busqueda']; ?>">
            <button class="btn deepPink-bgcolor my-2 my-sm-0" type="submit">Buscar</button>
        </form>

    </div>
</nav>