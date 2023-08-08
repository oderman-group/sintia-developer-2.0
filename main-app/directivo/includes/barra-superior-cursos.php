
<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #41c4c4;">
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">

      <?php if(Modulos::validarPermisoEdicion()){?>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color:<?=$Plataforma->colorUno;?>;">
              Más opciones
            <span class="fa fa-angle-down"></span>
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="cursos-intensidad.php">I.H por curso</a>
          <a class="dropdown-item" href="cursos-cambiar-matricula.php" onClick="if(!confirm('Desea ejecutar esta accion?')){return false;}">Poner en $0 la matricula</a>
          
          <a class="dropdown-item" href="cursos-cambiar-pension.php" onClick="if(!confirm('Desea ejecutar esta accion?')){return false;}">Poner en $0 la pensión</a>
          <div class="dropdown-divider"></div>
          </div>
        </li>
        <li class="nav-item"> <a class="nav-link" href="#">|</a></li>
      <?php }?>

      <?php if(array_key_exists(10,$arregloModulos) ){?>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color:#FFF;">
          Filtrar por tipo
        <span class="fa fa-angle-down"></span>
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">	
          <a class="dropdown-item" href="<?=$_SERVER['PHP_SELF'];?>?tipo=<?=GRADO_GRUPAL;?>" <?=$estiloResaltado;?>>Grupal</a>
          <a class="dropdown-item" href="<?=$_SERVER['PHP_SELF'];?>?tipo=<?=GRADO_INDIVIDUAL;?>" <?=$estiloResaltado;?>>Individual</a>
          <a class="dropdown-item" href="<?=$_SERVER['PHP_SELF'];?>" <?=$estiloResaltado;?>>Todos</a>
          </div>
        </li>
      <?php }?>

    </ul>


  </div>
</nav>