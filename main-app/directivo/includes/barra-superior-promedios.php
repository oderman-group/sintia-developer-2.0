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
        while($grado = mysqli_fetch_array($grados, MYSQLI_BOTH)){
            $estiloResaltado = '';
            if($grado['gra_id'] == $_GET["curso"]) $estiloResaltado = 'style="color: '.$Plataforma->colorUno.';"';
        ?>	
            <a class="dropdown-item" href="<?=$_SERVER['PHP_SELF'];?>?curso=<?=$grado['gra_id'];?>" <?=$estiloResaltado;?>><?=$grado['gra_nombre'];?></a>
        <?php }?>
          <a class="dropdown-item" href="<?=$_SERVER['PHP_SELF'];?>" style="font-weight: bold; text-align: center;">VER TODO</a>
        </div>
      </li>

      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color:#FFF;">
            Filtrar por grupo
		  <span class="fa fa-angle-down"></span>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
        <?php
        $grupos = Grupos::listarGrupos();
        while($grupo = mysqli_fetch_array($grupos, MYSQLI_BOTH)){
            $estiloResaltado = '';
            if($grupo['gru_id'] == $_GET["grupo"]) $estiloResaltado = 'style="color: '.$Plataforma->colorUno.';"';
        ?>	
            <a class="dropdown-item" href="<?=$_SERVER['PHP_SELF'];?>?grupo=<?=$grupo['gru_id'];?>&curso=<?=$_GET["curso"];?>" <?=$estiloResaltado;?>><?=$grupo['gru_nombre'];?></a>
        <?php }?>
          <a class="dropdown-item" href="<?=$_SERVER['PHP_SELF'];?>" style="font-weight: bold; text-align: center;">VER TODO</a>
        </div>
      </li>

      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color:#FFF;">
            Filtrar por periodos
		  <span class="fa fa-angle-down"></span>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
        <?php
        $p = 1;
        while($p <= $config['conf_periodos_maximos']){
            $estiloResaltado = '';
            if($p == $_GET["periodo"]) $estiloResaltado = 'style="color: '.$Plataforma->colorUno.';"';
        ?>	
            <a class="dropdown-item" href="<?=$_SERVER['PHP_SELF'];?>?periodo=<?=$p;?>" <?=$estiloResaltado;?>>Periodo <?=$p;?></a>
        <?php $p++; }?>
          <a class="dropdown-item" href="<?=$_SERVER['PHP_SELF'];?>" style="font-weight: bold; text-align: center;">VER TODO</a>
        </div>
      </li>


      
      <li class="nav-item"> <a class="nav-link" href="#">|</a></li>

      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color:#FFF;">
        Cantidades
		  <span class="fa fa-angle-down"></span>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">	
        <a class="dropdown-item" href="<?=$_SERVER['PHP_SELF'];?>?cantidad=10" <?=$estiloResaltado;?>>10 estudiantes</a>
        <a class="dropdown-item" href="<?=$_SERVER['PHP_SELF'];?>?cantidad=20" <?=$estiloResaltado;?>>20 estudiantes</a>
        <a class="dropdown-item" href="<?=$_SERVER['PHP_SELF'];?>?cantidad=30" <?=$estiloResaltado;?>>30 estudiantes</a>
        <a class="dropdown-item" href="<?=$_SERVER['PHP_SELF'];?>?cantidad=40" <?=$estiloResaltado;?>>40 estudiantes</a>
        <a class="dropdown-item" href="<?=$_SERVER['PHP_SELF'];?>?cantidad=50" <?=$estiloResaltado;?>>50 estudiantes</a>
        </div>
      </li>

      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color:#FFF;">
        Orden
		  <span class="fa fa-angle-down"></span>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">	
        <a class="dropdown-item" href="<?=$_SERVER['PHP_SELF'];?>?orden=DESC" <?=$estiloResaltado;?>>De mayor a menor</a>
        <a class="dropdown-item" href="<?=$_SERVER['PHP_SELF'];?>?orden=ASC" <?=$estiloResaltado;?>>De menor a mayor</a>
        </div>
      </li>


    </ul>

  </div>
</nav>