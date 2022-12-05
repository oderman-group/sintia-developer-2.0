<?php 
if( $datosUsuarioActual[3]==1 || isset($_SESSION['admin']) ){
  $archivo = explode("/", $_SERVER['PHP_SELF']);
  $nombre_fichero = $archivo[4];
?>

	<div style="
        position:relative;
        background-color: #fbbd01; 
        color:#000; 
        height: 50px; 
        width: 100%; 
        margin-bottom: 20px; 
        padding: 7px;
        display:flex; 
        justify-content: center; 
        align-items: center;
        font-family:Arial;
        font-size:16px;
    ">
    <b>Id Inst:</b>&nbsp;<?php echo $config['conf_id_institucion'];?>&nbsp;|&nbsp;
    <b>Id pagina:</b>&nbsp;<?php echo $idPaginaInterna;?>&nbsp;|&nbsp;
    <b>Archivo de ruta:</b>&nbsp;<?php echo $archivo[4];?>&nbsp;|&nbsp;
    <b>Usuario actual:</b>&nbsp;<?php echo $datosUsuarioActual[0];?>&nbsp;|&nbsp;
    <b>Tipo de Usuario:</b>&nbsp;<?php echo $datosUsuarioActual[3];?>&nbsp;|&nbsp;
		<b>Versión PHP:&nbsp;</b> <?=phpversion(); ?>&nbsp;|&nbsp; 
		<b>Server:&nbsp;</b> <?=$_SERVER['SERVER_NAME']; ?>&nbsp;|&nbsp;
    <b>Peso página:&nbsp;</b> <?php //echo number_format(filesize($nombre_fichero)) . ' bytes'; ?>&nbsp;|&nbsp;

    <?php if( isset($_SESSION['admin']) ){?>
			<b>User Admin:&nbsp;</b> <?=$_SESSION['admin']; ?>&nbsp;|&nbsp;
			<a href="../compartido/return-admin-panel.php" style="color:white; text-decoration:underline;">RETURN TO ADMIN PANEL</a>
		<?php }?>

	</div>

<?php }?>