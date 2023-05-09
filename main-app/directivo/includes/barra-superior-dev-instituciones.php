<?php
if (isset($_GET['busqueda'])) {
    $busqueda = $_GET['busqueda'];
    $filtro .= " AND (
        ins_id LIKE '%" . $busqueda . "%' 
        OR ins_nombre LIKE '%" . $busqueda . "%' 
        OR ins_fecha_inicio LIKE '%" . $busqueda . "%' 
        OR ins_contacto_principal LIKE '%" . $busqueda . "%' 
        OR ins_email_contacto LIKE '%".$busqueda."%' 
        OR ins_siglas LIKE '%".$busqueda."%' 
        OR plns_nombre LIKE '%".$busqueda."%' 
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
                    Filtrar por Plan
                    <span class="fa fa-angle-down"></span>
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <?php
                    $instituciones = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".planes_sintia");
                    while ($datosInsti = mysqli_fetch_array($instituciones, MYSQLI_BOTH)) {
                        $estiloResaltado = '';
                        if ($datosInsti['plns_id'] == $_GET["plan"]) $estiloResaltado = 'style="color: ' . $Plataforma->colorUno . ';"';
                    ?>
                        <a class="dropdown-item" href="<?= $_SERVER['PHP_SELF']; ?>?plan=<?= $datosInsti['plns_id']; ?>" <?= $estiloResaltado; ?>><?= $datosInsti['plns_nombre']; ?></a>
                    <?php } ?>
                    <a class="dropdown-item" href="<?= $_SERVER['PHP_SELF']; ?>" style="font-weight: bold; text-align: center;">VER TODO</a>
                </div>
            </li>

        </ul>

        <form class="form-inline my-2 my-lg-0" action="<?= $_SERVER['PHP_SELF']; ?>" method="get">
            <?php
                if (!empty($_GET['plan'])){
            ?>
                <input type="hidden" name="plan" value="<?= $_GET['plan']; ?>"/>
            <?php
                }
            ?>
            <input class="form-control mr-sm-2" type="search" placeholder="Búsqueda..." aria-label="Search" name="busqueda" value="<?php if (isset($_GET['busqueda'])) echo $_GET['busqueda']; ?>">
            <button class="btn deepPink-bgcolor my-2 my-sm-0" type="submit">Buscar</button>
        </form>

    </div>
</nav>