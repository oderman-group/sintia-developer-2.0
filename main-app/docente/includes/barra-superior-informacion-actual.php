<nav class="navbar navbar-expand-lg navbar-dark mb-2" style="background-color: #41c4c4;">


  <div class="navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">


      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color:<?= $Plataforma->colorUno; ?>;">
          <b><?= strtoupper($frases[116][$datosUsuarioActual[8]]); ?>: </b> <?= strtoupper($datosCargaActual['mat_nombre']); ?>
          <b><?= strtoupper($frases[26][$datosUsuarioActual[8]]); ?>: </b> <?= strtoupper($datosCargaActual['gra_nombre'] . " " . $datosCargaActual['gru_nombre']); ?>
          <b><?= strtoupper($frases[27][$datosUsuarioActual[8]]); ?>: </b> <?= $periodoConsultaActual; ?>
          <span class="fa fa-angle-down"></span>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <?php include("info-carga-actual.php"); ?>
        </div>
      </li>



      <li class="nav-item"> <a class="nav-link" href="#">|</a></li>

      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color:#FFF;">
          Filtrar por periodos
          <span class="fa fa-angle-down"></span>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <?php
          $porcentaje = 0;
          for ($i = 1; $i <= $datosCargaActual['gra_periodos']; $i++) {
            $consultaPeriodosCursos = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_grados_periodos
												WHERE gvp_grado='" . $datosCargaActual['car_curso'] . "' AND gvp_periodo='" . $i . "' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}
												");
            $periodosCursos = mysqli_fetch_array($consultaPeriodosCursos, MYSQLI_BOTH);
            $numPeriodosCursos=mysqli_num_rows($consultaPeriodosCursos);
            $porcentaje=25;
            if($numPeriodosCursos>0){
              $porcentaje=$periodosCursos['gvp_valor'];
            }

            if ($i == $datosCargaActual['car_periodo']) $msjPeriodoActual = '- ACTUAL';
            else $msjPeriodoActual = '';
            if ($i == $periodoConsultaActual) $estiloResaltadoP = 'style="color: orange;"';
            else $estiloResaltadoP = '';
          ?>
            <a class="dropdown-item" href="<?= $_SERVER['PHP_SELF']; ?>?carga=<?= base64_encode($cargaConsultaActual); ?>&periodo=<?= base64_encode($i); ?>&get=<?= base64_encode(100); ?>" <?= $estiloResaltadoP; ?>><?= strtoupper($frases[27][$datosUsuarioActual['uss_idioma']]); ?> <?= $i; ?> (<?= $porcentaje; ?>%) <?= $msjPeriodoActual; ?></a>
          <?php } ?>

        </div>
      </li>

      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color:#FFF;">
          Filtrar por asignaturas
          <span class="fa fa-angle-down"></span>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <?php
          $cCargas = mysqli_query($conexion, "SELECT * FROM academico_cargas 
											INNER JOIN ".BD_ACADEMICA.".academico_materias am ON am.mat_id=car_materia AND am.institucion={$config['conf_id_institucion']} AND am.year={$_SESSION["bd"]}
											INNER JOIN ".BD_ACADEMICA.".academico_grados gra ON gra_id=car_curso AND gra.institucion={$config['conf_id_institucion']} AND gra.year={$_SESSION["bd"]} {$filtroMT}
											INNER JOIN ".BD_ACADEMICA.".academico_grupos gru ON gru.gru_id=car_grupo AND gru.institucion={$config['conf_id_institucion']} AND gru.year={$_SESSION["bd"]}
											WHERE car_docente='" . $_SESSION["id"] . "'
											ORDER BY car_posicion_docente, car_curso, car_grupo, mat_nombre
											");
          $nCargas = mysqli_num_rows($cCargas);
          while ($rCargas = mysqli_fetch_array($cCargas, MYSQLI_BOTH)) {
            if ($rCargas['car_id'] == $cargaConsultaActual) $estiloResaltado = 'style="color: orange;"';
            else $estiloResaltado = '';
            if ($rCargas['car_director_grupo'] == 1) {
              $estiloDG = 'style="font-weight: bold;"';
              $msjDG = ' - D.G';
            } else {
              $estiloDG = '';
              $msjDG = '';
            }
          ?>
            <a class="dropdown-item" href="<?= $_SERVER['PHP_SELF']; ?>?carga=<?= base64_encode($rCargas['car_id']); ?>&periodo=<?= base64_encode($periodoConsultaActual); ?>&get=<?= base64_encode(100); ?>" <?= $estiloResaltado; ?>><span <?= $estiloDG; ?>><?= $rCargas['car_posicion_docente']; ?>. <?= strtoupper($rCargas['mat_nombre']); ?> (<?= strtoupper($rCargas['gra_nombre'] . " " . $rCargas['gru_nombre']); ?>) <?= $msjDG; ?></span></a>
          <?php } ?>
        </div>
      </li>


    </ul>



  </div>
</nav>