<?php
if (isset($_GET['busqueda'])) {
    $busqueda = $_GET['busqueda'];
}
?>

<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #41c4c4;">
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="navbar-collapse" id="navbarSupportedContent">

  <ul class="navbar-nav mr-auto">
  <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color:<?=$Plataforma->colorUno;?>;">
            Más acciones
		  <span class="fa fa-angle-down"></span>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
        <a class="dropdown-item" href="guardar.php?get=69" onClick="if(!confirm('Desea Bloquear a todos los estudiantes?')){return false;}">Bloquear estudiantes</a>
        <a class="dropdown-item" href="guardar.php?get=70" onClick="if(!confirm('Desea Desbloquear a todos los estudiantes?')){return false;}">Desbloquear estudiantes</a>
        <a class="dropdown-item" href="usuarios-importar-excel.php">Importar usuarios</a>
        <a class="dropdown-item" href="usuarios-generar-clave-filtros.php">Generar contraseña masiva</a>
        </div>
      </li>

      

  </ul> 
    <form class="form-inline my-2 my-lg-0" action="usuarios-anios.php" method="get">
        <?php
          if(!empty($_GET["tipo"])){
        ?>
          <input type="hidden" name="tipo" value="<?= $_GET['tipo']; ?>"/>
        <?php
          }
        ?>
        <input class="form-control mr-sm-2" type="search" placeholder="Búsqueda..." aria-label="Search" name="busqueda" value="<?php if(isset($_GET['busqueda'])) echo $_GET['busqueda'];?>">
      <button class="btn deepPink-bgcolor my-2 my-sm-0" type="submit">Buscar</button>
    </form>

  </div>
</nav>