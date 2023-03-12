<?php
if (isset($_GET['enviar'])) {
    $busqueda = $_GET['busqueda'];
    $filtro .= " AND (uss_id LIKE '%".$busqueda."%' OR uss_nombre LIKE '%".$busqueda."%' OR uss_usuario LIKE '%".$busqueda."%' OR uss_email LIKE '%".$busqueda."%')";
    
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

      <li class="nav-item"> <a class="nav-link" href="#">|</a></li>

      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color:#FFF;">
        Filtrar por tipo de usuario
		  <span class="fa fa-angle-down"></span>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
        <?php
            $tiposUsuarios = TipoUsuario::listarTiposUsuarios();
            while($tipoUsuario = mysqli_fetch_array($tiposUsuarios, MYSQLI_BOTH)){
                $estiloResaltado = '';
                if($tipoUsuario['pes_id'] == $_GET["tipo"]) $estiloResaltado = 'style="color: '.$Plataforma->colorUno.';"';
            ?>	
            <a class="dropdown-item" href="<?=$_SERVER['PHP_SELF'];?>?tipo=<?=$tipoUsuario['pes_id'];?>" <?=$estiloResaltado;?>><?=$tipoUsuario['pes_nombre'];?></a>
        <?php }?>
        </div>
      </li>
  </ul> 

    <form class="form-inline my-2 my-lg-0" action="usuarios.php?cantidad=10" method="get">
        <input type="hidden" name="cantidad" value="10">
        <input class="form-control mr-sm-2" type="search" placeholder="Búsqueda..." aria-label="Search" name="busqueda" value="<?php if(isset($_GET['busqueda'])) echo $_GET['busqueda'];?>">
      <button class="btn my-2 my-sm-0" type="submit" name="enviar">Buscar</button>
    </form>

  </div>
</nav>