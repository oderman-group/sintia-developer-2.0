<?php
include_once("socket.php");
$consultaFinanzas=mysqli_query($conexion, "SELECT
(SELECT sum(fcu_valor) FROM ".BD_FINANCIERA.".finanzas_cuentas WHERE fcu_usuario='".$_SESSION["id"]."' AND fcu_anulado=0 AND fcu_tipo=1 AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}),
(SELECT sum(fcu_valor) FROM ".BD_FINANCIERA.".finanzas_cuentas WHERE fcu_usuario='".$_SESSION["id"]."' AND fcu_anulado=0 AND fcu_tipo=3 AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]})
");
$resumenEC = mysqli_fetch_array($consultaFinanzas, MYSQLI_BOTH);
$saldoEC = ($resumenEC[0] - $resumenEC[1]) * -1;
?>

<body class="page-header-fixed sidemenu-closed-hidelogo page-content-white page-md 
<?=$datosUsuarioActual['uss_tema_header'];?>  
<?=$datosUsuarioActual['uss_tema_sidebar'];?>  
<?=$datosUsuarioActual['uss_tema_logo'];?> 
<?=$datosUsuarioActual['uss_tipo_menu'];?> 
"> <!-- chat-sidebar-open-->
	
<script src="../js/Mensajes.js" ></script>
<div class="loader"></div>
 
<?php include("../compartido/overlay.php");?>

<?php include("../compartido/ComponenteModal.php");?>

<?php include("../compartido/modal-centralizado.php");?>

<?php include("../compartido/modal-general.php");?>

<?php include("../compartido/modal-licencia.php");?>

<?php include("../compartido/modal-anuncios.php");?>
	
<?php include("../compartido/modal-acciones.php");?>

<?php include("../compartido/modal-terminos.php");?>

<?php include("../compartido/modal-contrato.php");?>

<?php include("../compartido/modal-asignaciones.php");?>

<?php include("../compartido/modal-comprar-modulo.php");?>

<?php include("../compartido/modal-comprar-paquete.php");?>