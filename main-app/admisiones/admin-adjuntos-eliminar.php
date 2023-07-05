<?php
session_start();
if ($_SESSION["id"] == "") {
    header("Location:index.php?sesion=0");
    exit();
}
?>
<?php
include("bd-conexion.php");
include("php-funciones.php");

if($_GET["adj"] == 1){
    $aspQuery = 'UPDATE aspirantes SET asp_archivo1 = "" WHERE asp_id = :id';
}

if($_GET["adj"] == 2){
    $aspQuery = 'UPDATE aspirantes SET asp_archivo2 = "" WHERE asp_id = :id';
}

$asp = $pdo->prepare($aspQuery);
$asp->bindParam(':id', $_GET['solicitud'], PDO::PARAM_INT);
$asp->execute();

$ruta = 'files/adjuntos';
if(file_exists($ruta."/".$_GET["file"])){	unlink($ruta."/".$_GET["file"]);	}


 echo '<script type="text/javascript">window.location.href="admin-formulario-editar.php?msg=3&token='.md5($_GET["solicitud"]).'&id='.$_GET["solicitud"].'&idInst='.$_REQUEST['idInst'].'";</script>';

