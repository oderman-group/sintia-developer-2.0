<?php
include("bd-conexion.php");
?>
<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Consultar estado | Plataforma sintia</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

    <!-- favicon -->
    <link rel="shortcut icon" href="../sintia-icono.png" />

</head>

<body>

    <div class="container">

        <?php include("menu.php"); ?>

        <div class="row justify-content-md-center">

            <div class="col col-lg-12 mb-4">

                <?php include("alertas.php"); ?>

                <?php
                $solicitud = '';
                $documento = '';
                if (!empty($_GET["solicitud"])) {
                    $solicitud = base64_decode($_GET['solicitud']);
                    $documento = base64_decode($_GET['documento']);
                }elseif(!empty($_POST["solicitud"])) {
                    $solicitud = $_POST['solicitud'];
                    $documento = $_POST['documento'];
                }
                ?>

                <form action="consultar-estado.php" method="post">
                    <input type="hidden" name="idInst" value="<?php if(!empty($_REQUEST['idInst'])) echo $_REQUEST['idInst']; ?>">

                    <div class="form-group">
                        <label for="solicitud">Número de solicitud</label>
                        <input type="number" class="form-control" id="solicitud" name="solicitud" aria-describedby="solicitudHelp" autocomplete="off" required value="<?= $solicitud; ?>">
                        <small id="solicitudHelp" class="form-text text-muted">El número de solicitud fue enviada a su correo en el momento que se registró.</small>
                    </div>

                    <div class="form-group">
                        <label>Número de documento del aspirante</label>
                        <input type="text" class="form-control" name="documento" autocomplete="off" required value="<?= $documento; ?>">
                    </div>

                    <button type="submit" class="btn" style="background-color:<?=$fondoBarra;?>; color:<?=$colorTexto;?>;">Consultar estado</button>
                </form>

                <?php
                if (!empty($_REQUEST["solicitud"])) {
                    $estQuery = "SELECT * FROM aspirantes WHERE asp_id = :id AND asp_documento = :documento";
                    $est = $pdo->prepare($estQuery);
                    $est->bindParam(':id', $solicitud, PDO::PARAM_INT);
                    $est->bindParam(':documento', $documento, PDO::PARAM_INT);
                    $est->execute();
                    $num = $est->rowCount();
                    $datos = $est->fetch();

                    if ($num > 0) {
                ?>
                        <nav class="navbar navbar-expand-lg navbar-dark mt-4 rounded-top" style="background-color:<?=$fondoBarra;?>;">
                            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                                <div class="navbar-nav">
                                    <?php 
                                    foreach($ordenReal as $clave) {
                                        $active = '';
                                        if($datos['asp_estado_solicitud'] == $clave) {
                                            $active = 'active';
                                        }
                                    ?>
                                        <a class="nav-link <?=$active;?>" href="#" style="font-size: 9px;"><?=$estadosSolicitud[$clave];?></a>
                                    <?php }?>
                                </div>
                            </div>
                        </nav>

                        <div class="progress">
                            <div class="progress-bar progress-bar-striped border" role="progressbar" style="width: <?= $progresoSolicitud[$datos['asp_estado_solicitud']]; ?>; background-color:<?=$fondoSolicitud[$datos['asp_estado_solicitud']];?>" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"><?= $progresoSolicitud[$datos['asp_estado_solicitud']]; ?></div>
                        </div>
                        <div class="jumbotron jumbotron-fluid">
                            <div class="container">
                                <h1 class="display-4"><?= $estadosSolicitud[$datos['asp_estado_solicitud']]; ?></h1>
                                <!--
                                <p class="lead">This is a modified jumbotron that occupies the entire horizontal space of its parent.</p>-->

                                <?php if ($datos['asp_estado_solicitud'] == 3 or $datos['asp_estado_solicitud'] == 4) { ?>
                                    <a class="btn btn-lg" style="background-color:<?=$fondoBarra;?>; color:<?=$colorTexto;?>;" href="formulario.php?token=<?= md5($datos['asp_id']); ?>&id=<?= base64_encode($datos['asp_id']); ?>&idInst=<?= $_REQUEST['idInst']; ?>" role="button">Ir al formulario</a>
                                <?php } ?>

                                <hr class="my-4">

                                <p>
                                    <b>Documento del aspirante:</b> <?= $datos['asp_documento']; ?><br>
                                    <b>Nombre del aspirante:</b> <?= $datos['asp_nombre']; ?><br>
                                </p>
                            </div>
                        </div>

                        <?php if ($datos['asp_estado_solicitud'] == 1 or $datos['asp_estado_solicitud'] == 2) { ?>

                            <h3>Adjuntar comprobante</h3>

                            <p class="h6 text-info">
                                El formulario de inscripción tiene un costo de $<?= number_format($valorInscripcion,0,".","."); ?>, el cual debe ser pagado a la cuenta corriente de <b>Banclombia #00411865393</b> y debe ser adjuntado el soporte para que les habiliten el formulario.
                            </p>

                            <form action="enviar-comprobante.php" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="solicitud" value="<?= $solicitud; ?>">
                                <input type="hidden" name="idInst" value="<?= $_REQUEST['idInst']; ?>">

                                <div class="form-group">
                                    <label for="comprobante">Adjuntar comprobante de pago</label>
                                    <input type="file" class="form-control" id="comprobante" name="comprobante" aria-describedby="comprobanteHelp" required>
                                    <small id="comprobanteHelp" class="form-text text-muted">Si hizo el pago en efectivo o transferencia, por favor adjunte su comprobante.</small>
                                </div>

                                <button type="submit" class="btn" style="background-color:<?=$fondoBarra;?>; color:<?=$colorTexto;?>;">Enviar comprobante</button>
                            </form>
                        <?php } ?>

                        <?php if ($datos['asp_estado_solicitud'] == 6 && $datosConfig['cfgi_activar_boton_pagar_prematricula'] == 1) { ?>
                            <a href="<?=$datosConfig['cfgi_link_boton_pagar_prematricula'];?>" class="btn" style="background-color:<?=$fondoBarra;?>; color:<?=$colorTexto;?>;" target="_blank">Pagar prematricula</a>
                        <?php } ?>

                    <?php
                    } else { ?>
                        <div class="alert alert-danger mt-4" role="alert">
                            No se encontraron coincidencias.
                        </div>
                <?php }
                }

                ?>



            </div>





        </div>



    </div>





    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>



</body>



</html>