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
				<h3>Solicitud de Cancelacion</h3>
			</div>

			<div style="font-family:arial; background:#FAFAFA; width:600px; color:#000; text-align:justify; padding:15px;">
				Cordial saludo <?=$data['usuario_nombre']?>, su solicitud de cancelacion del servicio fue realizada correctamente.<br>
				Nos contactaremos pronto para dar validacion a esta solicitud <br>
				
				<b>Número de solicitud:</b>          <pre><?=$data['solicitud_id'];?></pre>
				<b>Solicitada por:</b> <pre><?=$data['solicitud_usuario'];?></pre><br><br>
				<p style="text-align:center;">
					Gracias por su informacion
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
    