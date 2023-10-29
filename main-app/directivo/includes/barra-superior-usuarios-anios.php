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