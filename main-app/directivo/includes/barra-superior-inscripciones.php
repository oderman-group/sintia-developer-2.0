<?php
if (isset($_GET['busqueda'])) {
  $busqueda = $_GET['busqueda'];
  $filtro .= " AND (
  mat_id LIKE '%" . $busqueda . "%' 
  OR mat_nombres LIKE '%" . $busqueda . "%' 
  OR mat_nombre2 LIKE '%" . $busqueda . "%' 
  OR mat_primer_apellido LIKE '%" . $busqueda . "%' 
  OR mat_segundo_apellido LIKE '%" . $busqueda . "%' 
  OR mat_documento LIKE '%" . $busqueda . "%' 
  OR mat_email LIKE '%" . $busqueda . "%'
  OR CONCAT(TRIM(mat_primer_apellido), ' ', TRIM(mat_segundo_apellido), ' ', TRIM(mat_nombres)) LIKE '%" . $busqueda . "%'
  OR CONCAT(TRIM(mat_primer_apellido), TRIM(mat_segundo_apellido), TRIM(mat_nombres)) LIKE '%" . $busqueda . "%'
  OR CONCAT(TRIM(mat_primer_apellido), ' ', TRIM(mat_nombres)) LIKE '%" . $busqueda . "%'
  OR CONCAT(TRIM(mat_primer_apellido), TRIM(mat_nombres)) LIKE '%" . $busqueda . "%'
  OR gra_nombre LIKE '%" . $busqueda . "%'
  OR asp_email_acudiente LIKE '%" . $busqueda . "%'
  OR asp_nombre_acudiente LIKE '%" . $busqueda . "%'
  OR asp_nombre LIKE '%" . $busqueda . "%'
  OR asp_documento_acudiente LIKE '%" . $busqueda . "%'
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
          Filtrar por curso
          <span class="fa fa-angle-down"></span>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <?php
          $grados = Grados::listarGrados(1);
          while ($grado = mysqli_fetch_array($grados, MYSQLI_BOTH)) {
            $estiloResaltado = '';
            if ($grado['gra_id'] == $_GET["curso"]) $estiloResaltado = 'style="color: ' . $Plataforma->colorUno . ';"';
          ?>
            <a class="dropdown-item" href="<?= $_SERVER['PHP_SELF']; ?>?curso=<?= $grado['gra_id']; ?>&busqueda=<?= $_GET["busqueda"]; ?>" <?= $estiloResaltado; ?>><?= $grado['gra_nombre']; ?></a>
          <?php } ?>
          <a class="dropdown-item" href="<?= $_SERVER['PHP_SELF']; ?>" style="font-weight: bold; text-align: center;">VER TODO</a>
        </div>
      </li>


    </ul>

    <form class="form-inline my-2 my-lg-0" action="<?= $_SERVER['PHP_SELF']; ?>" method="get">
      <?php
      if (!empty($_GET["curso"])) {
      ?>
        <input type="hidden" name="curso" value="<?= $_GET['curso']; ?>" />
      <?php
      }
      ?>
      <input class="form-control mr-sm-2" type="search" placeholder="Búsqueda..." aria-label="Search" name="busqueda" value="<?php if (isset($_GET['busqueda'])) echo $_GET['busqueda']; ?>">
      <button class="btn deepPink-bgcolor my-2 my-sm-0" type="submit">Buscar</button>
    </form>

  </div>
</nav>