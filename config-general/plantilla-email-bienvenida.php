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
				<h3>¡Bienvenido a la Plataforma SINTIA!</h3>
			</div>

			<div style="font-family:arial; background:#FAFAFA; width:600px; color:#000; text-align:justify; padding:15px;">
				<p>
					Estimado <b><?=strtoupper($data['usuario_nombre'])?></b>, es un gusto darle la bienvenida a la plataforma sintia, a continuación te compartiremos las credenciales de acceso y un instructivo incial, recuerda que cualquier inquietud estaremos para apoyarte.<br>
					<b>Usuario:</b> <?=$data['usuario_usuario']?><br>
					<b>Contraseña:</b> <?=$data['usuario_clave']?><br>
				</p>

				<p>
					<a href="https://www.loom.com/embed/8eac333b167c48d98ca3b459e78faeac" target="_blank">
						<img src="https://main.plataformasintia.com/app-sintia/main-app/files/images/paso-a-paso-directivos.png" style="width: auto; height: auto;">
					</a>
				</p>

				<p>
					<h3 style="text-align: center;">
					<a href="https://demo.plataformasintia.com/app-sintia/main-app/index.php?inst='.$data['institucion_id'].'" target="_blank" style="color: #41c4c4; font-weight:bold;">ACCEDER A MI CUENTA DE DIRECTIVO AHORA</a>
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