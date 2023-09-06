<?php
require_once($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/constantes.php");
require_once(ROOT_PATH."/main-app/class/Plataforma.php");
$Plataforma = new Plataforma;
?>

<html>
	<body style="background-color:#FFF;">

		<div style="width: 100%; display: grid; place-content: center;">

			<div style="width:600px; text-align:justify; padding:15px;">
				<img src="<?=$Plataforma->logo;?>" width="100">
			</div>

			<div style="font-family:arial; background:<?=$Plataforma->colorUno;?>; width:600px; color:#FFF; text-align:center; padding:15px;">
				<h3>Proceso de admisión</h3>
			</div>

			<div style="font-family:arial; background:#FAFAFA; width:600px; color:#000; text-align:justify; padding:15px;">
				Cordial saludo <?=$data['usuario_nombre']?>, a su solicitud <b>#<?=$data['solicitud_id'];?></b> se la ha añadido la siguiente observación:<br><br>
				<b><?=$data['observaciones'];?></b><br><br>
				Puede consultar el estado de su solicitud en el siguiente enlace:<br>
				<a href="<?=REDIRECT_ROUTE;?>/admisiones/consultar-estado.php?idInst=<?=$_REQUEST['idInst']?>">CONSULTAR ESTADO DE SOLICITUD</a>
				<p style="text-align:center;">
					Gracias por preferirnos, que tenga un feliz día.
				</p>
			</div>

			<div style="width:600px; color:#000; text-align:center; padding:15px;">
				<img src="<?=$Plataforma->logo;?>" width="50"><br>
				¡Que tengas un excelente día!<br>
				<a href="https://plataformasintia.com/">www.plataformasintia.com</a>
			</div>

		</div>
		<p>&nbsp;</p>
	</body>
</html>
    