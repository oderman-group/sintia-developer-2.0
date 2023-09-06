<?php
session_start();
include($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/constantes.php");

$conexionBaseDatosServicios = mysqli_connect($servidorConexion, $usuarioConexion, $claveConexion, $baseDatosServicios);
$institucionesConsulta = mysqli_query($conexionBaseDatosServicios, "SELECT * FROM ".$baseDatosServicios.".instituciones 
WHERE ins_estado = 1 AND ins_enviroment='".ENVIROMENT."'");
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admisiones | Plataforma sintia</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    </head>

    <body>
        <div class="container">
            <?php include("menu.php"); ?>
            <div class="row justify-content-md-center">
                <div class="col col-lg-12">
                    <hr class="my-4">
                    <?php include("alertas.php"); ?>
                    <h3 style="text-align: center;">ESCOGE LA INSTITUCIÓN A LA QUE DESEAS INGRESAR</h3>
                    <div class="col col-lg-12">
                        <form action="admision.php" method="post">
                            <div class="form-row justify-content-md-center">
                                <div class="form-group col-md-3">
                                    <select id="idInst" name="idInst" class="form-control" required>
                                        <option value="" selected>Escoja una Institución...</option>
                                        <?php
                                        while($instituciones = mysqli_fetch_array($institucionesConsulta, MYSQLI_BOTH)){
                                        $selected = (isset($_GET['inst']) and $_GET['inst']==$instituciones['ins_id']) ? 'selected' : '';
                                        ?>
                                        <option value="<?=$instituciones['ins_id'];?>" <?=$selected;?>><?=$instituciones['ins_siglas'];?></option>
                                        <?php }?>
                                    </select>
                                </div>
                            </div>
                            <div style="text-align: center;">
                                <button type="submit" class="btn btn-success btn-lg">INICIAR PROCESO</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
    </body>
</html>