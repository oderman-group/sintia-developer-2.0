<?php if(isset($_GET["msg"]) and $_GET["msg"]==1){?>
    <div class="alert alert-success" role="alert">
    Su registro fue creado correctamente. A su correo electrónico hemos enviado el número de su solicitud para que pueda consultar el estado de la misma.
    </div>
<?php }?>

<?php if(isset($_GET["msg"]) and $_GET["msg"]==2){?>
    <div class="alert alert-success" role="alert">
    El comprobante fue enviado correctamente. Esté pendiente al resultado cuando lo validemos para que pueda continuar a llenar el formulario.
    </div>
<?php }?>

<?php if(isset($_GET["msg"]) and $_GET["msg"]==3){?>
    <div class="alert alert-success" role="alert">
    Los datos fueron guardados correctamente.
    </div>
<?php }?>







<!-- ERRORES -->

<?php if(isset($_GET["error"]) and $_GET["error"]==1){?>
    <div class="alert alert-danger" role="alert">
    Hubo un error al detectar la Institución a la que aspira el estudiante. Intente nuevamente.
    </div>
<?php }?>



<?php if(isset($_GET["error"]) and $_GET["error"]==2){?>
    <div class="alert alert-danger" role="alert">
        <p>Ya existe una solicitud con este número de documento <b><?=$_GET["documento"];?></b>. Por favor intente consultar el estado de la solicitud para verificar el paso a seguir.</p>
        <p><a href="consultar-estado.php" class="btn btn-primary">Consultar estado de solicitud</a></p>
    </div>
<?php }?>

<?php if(isset($_GET["error"]) and $_GET["error"]==3){?>
    <div class="alert alert-danger" role="alert">
        <p>Ocurrión un error al registrar los datos.</p>
    </div>
<?php }?>

<?php if(isset($_GET["error"]) and $_GET["error"]==4){?>
    <div class="alert alert-danger" role="alert">
        Hubo un error al detectar el número de su solicitud. Intente nuevamente.
    </div>
<?php }?>