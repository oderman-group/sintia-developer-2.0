<?php
$consultaFinanzas=mysqli_query($conexion, "SELECT
(SELECT sum(fcu_valor) FROM finanzas_cuentas WHERE fcu_usuario='".$_SESSION["id"]."' AND fcu_anulado=0 AND fcu_tipo=1),
(SELECT sum(fcu_valor) FROM finanzas_cuentas WHERE fcu_usuario='".$_SESSION["id"]."' AND fcu_anulado=0 AND fcu_tipo=3)
");
$resumenEC = mysqli_fetch_array($consultaFinanzas, MYSQLI_BOTH);
$saldoEC = ($resumenEC[0] - $resumenEC[1]) * -1;
?>
<script src="https://cdn.socket.io/3.1.3/socket.io.min.js" integrity="sha384-cPwlPLvBTa3sKAgddT6krw0cJat7egBga3DJepJyrLl4Q9/5WLra3rrnMcyTyOnh" crossorigin="anonymous"></script>
<body class="page-header-fixed sidemenu-closed-hidelogo page-content-white page-md <?=$datosUsuarioActual['uss_tema_header'];?> <?=$datosUsuarioActual['uss_tema_sidebar'];?> 
			 <?=$datosUsuarioActual['uss_tema_logo'];?>"> <!-- chat-sidebar-open-->
	
  <script>
		var urlApi = 'http://plataformasintia.com:3500';
		var socket = io(urlApi, {
			transports: ['websocket', 'polling', 'flashsocket']
		});
		var chat_remite_usuario = <?php echo $idSession ?>;
		socket.emit('join', "sala_" + chat_remite_usuario);
		
	</script>
<div class="loader"></div>

<div id="overlay">
	<div id="loader"></div>
	<div id="loading-text">Cargando...</div>
</div>

<?php include("../compartido/modal-general.php");?>

<?php include("../compartido/modal-licencia.php");?>

<?php include("../compartido/modal-anuncios.php");?>
	
<?php include("../compartido/modal-acciones.php");?>

<?php include("../compartido/modal-terminos.php");?>

<?php include("../compartido/modal-contrato.php");?>