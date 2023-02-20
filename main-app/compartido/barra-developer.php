<?php if( 
$datosUsuarioActual[3]==1 
|| isset($_SESSION['admin']) 
|| isset($_SESSION['docente']) 
|| isset($_SESSION['acudiente']) 
){?>
<div style="
        position:relative;
        background-color: #41c4c4; 
        color:#fff; 
        height: 40px; 
        width: 100%; 
        margin-bottom: 20px; 
        padding: 6px;
        display:flex; 
        justify-content: center; 
        align-items: center;
        font-family:Arial;
        font-size:14px;
">

<?php 
if( $datosUsuarioActual[3]==1 || isset($_SESSION['admin']) ){
  $archivo = explode("/", $_SERVER['PHP_SELF']);
  $nombre_fichero = $archivo[4];

  $lines = file('../../.git/HEAD');
  foreach ($lines as $line_num => $line) {
  }
  $ramaActual = substr($line, 16);
?>

    <b>Rama GIT:</b>&nbsp;<?php echo $ramaActual;?>&nbsp;|&nbsp;
    <b>ID Inst:</b>&nbsp;<?php echo $config['conf_id_institucion'];?>&nbsp;|&nbsp;
    <b>Id pagina:</b>&nbsp;<?php echo $idPaginaInterna;?>&nbsp;|&nbsp;
    <b>Usuario actual:</b>&nbsp;<?php echo $datosUsuarioActual[0];?>&nbsp;|&nbsp;
    <b>Tipo de Usuario:</b>&nbsp;<?php echo $datosUsuarioActual[3];?>&nbsp;|&nbsp;
		<b>V PHP:&nbsp;</b> <?=phpversion(); ?>&nbsp;|&nbsp; 
		<b>Host:&nbsp;</b> <?=$_SERVER['HTTP_HOST']." (".http_response_code().")"; ?>&nbsp;|&nbsp;
    <b>Peso página:&nbsp;</b> <?php echo number_format(filesize($nombre_fichero)) . ' bytes'; ?>&nbsp;|&nbsp;

    <?php if( isset($_SESSION['admin']) ){?>
			<b>User Admin:&nbsp;</b> <?=$_SESSION['admin']; ?>&nbsp;|&nbsp;
			<a href="../compartido/return-admin-panel.php?tipo=<?=$datosUsuarioActual[3];?>" style="color:white; text-decoration:underline;">VOLVER A MI PANEL</a>
		<?php }?>

<?php }?>


<?php
/* AUTOLOGIN DE DOCENTES */
if( isset($_SESSION['docente']) ){
?>
    <b>Usuario actual:</b>&nbsp;<?php echo $datosUsuarioActual[0];?>&nbsp;|&nbsp;
    <b>Tipo de Usuario:</b>&nbsp;<?php echo $datosUsuarioActual[3];?>&nbsp;|&nbsp;
    <b>User Docente:&nbsp;</b> <?=$_SESSION['docente']; ?>&nbsp;|&nbsp;
		<a href="../compartido/return-docente-panel.php" style="color:white; text-decoration:underline;">VOLVER AL PANEL DOCENTE</a>

<?php }?>

<?php
/* AUTOLOGIN DE ACUDIENTES */
if( isset($_SESSION['acudiente']) ){
?>
    <b>Usuario actual:</b>&nbsp;<?php echo $datosUsuarioActual[0];?>&nbsp;|&nbsp;
    <b>Tipo de Usuario:</b>&nbsp;<?php echo $datosUsuarioActual[3];?>&nbsp;|&nbsp;
    <b>User Acudiente:&nbsp;</b> <?=$_SESSION['acudiente']; ?>&nbsp;|&nbsp;
		<a href="../compartido/return-acudiente-panel.php" style="color:white; text-decoration:underline;">VOLVER AL PANEL ACUDIENTE</a>

<?php }?>

</div>
<?php }?>