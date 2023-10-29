<?php
$valorInscripcion = !empty($datosConfig['cfgi_valor_inscripcion'])     ? $datosConfig['cfgi_valor_inscripcion']    : 0;
$fondoBarra       = !empty($datosConfig['cfgi_color_barra_superior'])  ? $datosConfig['cfgi_color_barra_superior'] : '#6017dc';
$colorTexto       = !empty($datosConfig['cfgi_color_texto'])           ? $datosConfig['cfgi_color_texto']          : '#FFF';
?>




<nav class="navbar navbar-expand-lg navbar-dark mb-4" style="background-color:<?=$fondoBarra;?>;">

            <a class="navbar-brand" href="#">ADMISIONES</a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">

                <span class="navbar-toggler-icon"></span>

            </button>

            <?php
                $idInst='';
                if(!empty($_REQUEST['idInst'])){
                    $idInst=$_REQUEST['idInst'];
            ?>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">

                <div class="navbar-nav">

                    <a class="nav-link" href="admision.php?idInst=<?=$idInst?>" style="color:<?=$colorTexto;?>;">Registro</a>

                    <a class="nav-link" href="consultar-estado.php?idInst=<?=$idInst?>" style="color:<?=$colorTexto;?>;">Consultar estado de solicitud</a>

                </div>

            </div>
            <?php
                }
            ?>

        </nav>