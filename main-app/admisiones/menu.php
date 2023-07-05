<?php 
$valorInscripcion = 55000;
?>




<nav class="navbar navbar-expand-lg navbar-dark bg-success mb-4">

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

                    <a class="nav-link" href="admision.php?idInst=<?=$idInst?>">Registro</a>

                    <a class="nav-link" href="consultar-estado.php?idInst=<?=$idInst?>">Consultar estado de solicitud</a>

                </div>

            </div>
            <?php
                }
            ?>

        </nav>