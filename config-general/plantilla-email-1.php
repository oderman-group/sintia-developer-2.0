<?php
require_once($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/constantes.php");
require_once(ROOT_PATH."/main-app/class/Plataforma.php");
$Plataforma = new Plataforma;

$contenidoMsj = '
      Hola! <b>'.$data['usuario_nombre'].'</b><br>
      <b>'.$data['institucion_nombre'].'</b>, su licencia con la plataforma SINTIA esta por vencer<br>
      faltan <b>'.$data['falta'].'</b> para su vencimiento<br>
      puede hacer la renovacion atraves de la plataforma.';
?>

<html>
    <body style="background-color:#FFF;">

		<div style="width: 100%; display: grid; place-content: center; justify-content: center;">

            <div style="width:600px; text-align:justify; padding:15px;">
				<img src="<?=$Plataforma->logo;?>" width="100">
			</div>

			<div style="font-family:arial; background:<?=$Plataforma->colorUno;?>; width:600px; color:#FFF; text-align:center; padding:15px;">
				<h3>¡Faltan <?=$data['falta'];?> para vencer su licencia!</h3>
			</div>

			<div style="font-family:arial; background:#FAFAFA; width:600px; color:#000; text-align:justify; padding:15px;"><?=$contenidoMsj;?></div>

			<div style="width:600px; color:#000; text-align:center; padding:15px;">
				<img src="<?=$Plataforma->logo;?>" width="50"><br>
				¡Que tengas un excelente d&iacute;a!<br>
				<a href="https://plataformasintia.com/">www.plataformasintia.com</a>
			</div>

        </div>
		<p>&nbsp;</p>

    <body>
<html>