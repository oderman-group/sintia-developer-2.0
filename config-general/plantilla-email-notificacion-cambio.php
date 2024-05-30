<?php
require_once($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/constantes.php");
require_once(ROOT_PATH."/main-app/class/Plataforma.php");
$Plataforma = new Plataforma;

?>

<html>
    <body style="background-color:#FFF;">

		<div style="width: 100%; display: grid; place-content: center; justify-content: center;">

            <div style="width:600px; text-align:justify; padding:15px;">
				<img src="<?=$Plataforma->logo;?>" width="100">
			</div>

			<div style="font-family:arial; background:<?=$Plataforma->colorUno;?>; width:600px; color:#FFF; text-align:center; padding:15px;">
				<h3>¡Hemos modificado tu usuario de acceso!</h3>
			</div>

			<div style="font-family:arial; background:#FAFAFA; width:600px; color:#000; text-align:justify; padding:15px;">
				<p>
					Estimado usuario <b><?=strtoupper($data['usuario_nombre'])?></b>,<br>
					<br>
					Queremos informarte que por motivos de seguridad hemos realizado un cambio en tu cuenta para garantizar la protección de tu información personal y mantener la integridad de nuestra plataforma.<br>
					<br>
					Tu institución es: <b> <?=$data['institucion_nombre']?></b><br>
					Tu nuevo nombre de usuario es: <b> <?=$data['usuario_usuario']?></b><br>
					<b>Tu contraseña sigue siendo la misma que usas habitualmente.</b><br>
					<br>
					Este cambio ha sido implementado como parte de nuestras medidas proactivas para fortalecer la seguridad de todos nuestros usuarios. Te pedimos que utilices este nuevo nombre de usuario para acceder a tu cuenta a partir de ahora.<br>
					<br>
					Recuerda que tu contraseña y demás detalles de acceso permanecen sin cambios. Si tienes alguna pregunta o necesitas asistencia, no dudes en contactar a nuestro equipo de soporte técnico.<br>
					<br>
					Agradecemos tu comprensión y colaboración en esta importante medida de seguridad.<br>
					<br>
					¡Gracias por ser parte de nuestra comunidad!<br>
					<br>
					Atentamente,<br>
					<br>
					Equipo de Soporte<br>
					PLATAFORMA SINTIA
				</p>

				<p>
					<h3 style="text-align: center;">
					<a href="<?=REDIRECT_ROUTE;?>" target="_blank" style="color: #41c4c4; font-weight:bold;">ACCEDER A MI CUENTA AHORA</a>
					</h3>
				</p>
			</div>

			<div style="width:600px; color:#000; text-align:center; padding:15px;">
				<img src="<?=$Plataforma->logo;?>" width="50"><br>
				¡Que tengas un excelente d&iacute;a!<br>
				<a href="https://plataformasintia.com/">www.plataformasintia.com</a>
			</div>

        </div>
		<p>&nbsp;</p>

    <body>
<html>