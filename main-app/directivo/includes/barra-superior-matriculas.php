<?php
  if (isset($_GET['busqueda'])) {
      $busqueda = $_GET['busqueda'];
      $filtro .= " AND (
      mat_id LIKE '%".$busqueda."%' 
      OR mat_nombres LIKE '%".$busqueda."%' 
      OR mat_nombre2 LIKE '%".$busqueda."%' 
      OR mat_primer_apellido LIKE '%".$busqueda."%' 
      OR mat_segundo_apellido LIKE '%".$busqueda."%' 
      OR mat_documento LIKE '%".$busqueda."%' 
      OR mat_email LIKE '%".$busqueda."%'
      OR CONCAT(TRIM(mat_primer_apellido), ' ', TRIM(mat_segundo_apellido), ' ', TRIM(mat_nombres)) LIKE '%".$busqueda."%'
      OR CONCAT(TRIM(mat_primer_apellido), TRIM(mat_segundo_apellido), TRIM(mat_nombres)) LIKE '%".$busqueda."%'
      OR CONCAT(TRIM(mat_primer_apellido), ' ', TRIM(mat_nombres)) LIKE '%".$busqueda."%'
      OR CONCAT(TRIM(mat_primer_apellido), TRIM(mat_nombres)) LIKE '%".$busqueda."%'
      )";
      
  }
?>

<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #41c4c4;">
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
 
      <li class="nav-item"> <a class="nav-link" href="estudiantes-promedios.php" style="color:<?=$Plataforma->colorUno;?>;">Promedios estudiantiles</a></li>

      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color:<?=$Plataforma->colorUno;?>;">
            Menú matrículas
		  <span class="fa fa-angle-down"></span>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
        <a class="dropdown-item" href="estudiantes-importar-excel.php">Importar matrículas desde Excel</a>
        <a class="dropdown-item" href="estudiantes-consolidado-final.php">Consolidado final</a>
        <a class="dropdown-item" href="estudiantes-nivelaciones.php">Nivelaciones</a>
        </div>
      </li>

      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color:<?=$Plataforma->colorUno;?>;">
            Más opciones
		  <span class="fa fa-angle-down"></span>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
        <a class="dropdown-item" href="estudiantes-matricular-todos.php" onClick="if(!confirm('Desea ejecutar esta accion?')){return false;}">Matricular a todos</a>
        <a class="dropdown-item" href="estudiantes-matriculas-cancelar.php" onClick="if(!confirm('Desea ejecutar esta accion?')){return false;}">Cancelar a todos</a>
        <a class="dropdown-item" href="estudiantes-grupoa-todos.php" onClick="if(!confirm('Desea ejecutar esta accion?')){return false;}">Asignar a todos al grupo A</a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="estudiantes-documento-usuario-actualizar.php" onClick="if(!confirm('Desea ejecutar esta accion?')){return false;}">Colocar documento como usuario de acceso</a>
        <a class="dropdown-item" href="estudiantes-crear-usuarios.php" onClick="if(!confirm('Desea ejecutar esta accion?')){return false;}">Verificar y generar credenciales a estudiantes</a>
        <a class="dropdown-item" href="filtro-general-folio.php">Generar Folios</a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="estudiantes-inactivos-remover.php" onClick="if(!confirm('Esta opción removerá a todos lo estudiantes que no estén en estado Matriculado, desea continuar?')){return false;}">Remover estudiantes Inactivos este año</a></a>
        </div>
      </li>

      <li class="nav-item"> <a class="nav-link" href="#">|</a></li>

      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color:#FFF;">
            Filtrar por curso
		  <span class="fa fa-angle-down"></span>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
        <?php
        $grados = Grados::listarGrados(1);
        while($grado = mysqli_fetch_array($grados, MYSQLI_BOTH)){
            $estiloResaltado = '';
            if($grado['gra_id'] == $_GET["curso"]) $estiloResaltado = 'style="color: '.$Plataforma->colorUno.';"';
        ?>	
            <a class="dropdown-item" href="<?=$_SERVER['PHP_SELF'];?>?estadoM=<?=$_GET["estadoM"];?>&curso=<?=$grado['gra_id'];?>&busqueda=<?=$_GET["busqueda"];?>" <?=$estiloResaltado;?>><?=$grado['gra_nombre'];?></a>
        <?php }?>
          <a class="dropdown-item" href="<?=$_SERVER['PHP_SELF'];?>" style="font-weight: bold; text-align: center;">VER TODO</a>
        </div>
      </li>

      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color:#FFF;">
        Filtrar por estados
		  <span class="fa fa-angle-down"></span>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">	
        <a class="dropdown-item" href="<?=$_SERVER['PHP_SELF'];?>?estadoM=1&curso=<?=$_GET["curso"];?>&busqueda=<?=$_GET["busqueda"];?>" <?=$estiloResaltado;?>>Matriculados</a>
        <a class="dropdown-item" href="<?=$_SERVER['PHP_SELF'];?>?estadoM=2&curso=<?=$_GET["curso"];?>&busqueda=<?=$_GET["busqueda"];?>" <?=$estiloResaltado;?>>Asistentes</a>
        <a class="dropdown-item" href="<?=$_SERVER['PHP_SELF'];?>?estadoM=3&curso=<?=$_GET["curso"];?>&busqueda=<?=$_GET["busqueda"];?>" <?=$estiloResaltado;?>>Cancelados</a>
        <a class="dropdown-item" href="<?=$_SERVER['PHP_SELF'];?>?estadoM=4&curso=<?=$_GET["curso"];?>&busqueda=<?=$_GET["busqueda"];?>" <?=$estiloResaltado;?>>No Matriculados</a>
        <a class="dropdown-item" href="<?=$_SERVER['PHP_SELF'];?>" style="font-weight: bold; text-align: center;">VER TODO</a>
        </div>
      </li>


    </ul>

    <form class="form-inline my-2 my-lg-0" action="estudiantes.php" method="get">
        <?php
          if(!empty($_GET["curso"])){
        ?>
          <input type="hidden" name="curso" value="<?= $_GET['curso']; ?>"/>
        <?php
          }
          if(!empty($_GET["estadoM"])){
        ?>
          <input type="hidden" name="estadoM" value="<?= $_GET['estadoM']; ?>"/>
        <?php
          }
        ?>
        <input class="form-control mr-sm-2" type="search" placeholder="Búsqueda..." aria-label="Search" name="busqueda" value="<?php if(isset($_GET['busqueda'])) echo $_GET['busqueda'];?>">
      <button class="btn deepPink-bgcolor my-2 my-sm-0" type="submit">Buscar</button>
    </form>

  </div>
</nav>