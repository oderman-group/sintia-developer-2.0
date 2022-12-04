<?php 
$servidorConexion = 'localhost';
$usuarioConexion = 'mobiliar';
$claveConexion = 'M1X32znd9l';

//Conexion con el Servidor
$conexion = mysql_connect($servidorConexion, $usuarioConexion, $claveConexion);
//seleccionamos la base de datos
mysql_select_db("mobiliar_ict_2019", $conexion);
?>

<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../librerias/phpmailer/Exception.php';
require '../../librerias/phpmailer/PHPMailer.php';
require '../../librerias/phpmailer/SMTP.php';
?>

<?php
//RECORDAR CLAVE
if($_POST["id"]==2){
	$usuario = mysql_query("SELECT * FROM usuarios WHERE uss_email='".$_POST["email"]."'",$conexion);
	$nU = mysql_num_rows($usuario);
	$dU = mysql_fetch_array($usuario);
	if($nU>0){
		//INICIO ENVÍO DE MENSAJE
		$tituloMsj = "¡".strtoupper($dU["uss_nombre"])." TUS CREDENCIALES!";
		$bgTitulo = "#4086f4";
		$contenidoMsj = '
			Hola!<br>
			<b>'.strtoupper($dU["uss_nombre"]).'</b>, tus credenciales de acceso a la plataforma SINTIA son:<br>
			Usuario: <b>'.$dU['uss_usuario'].'</b><br>
			Contraseña: <b>'.$dU['uss_clave'].'</b>
		';
				
				include("../../config-general/plantilla-email-1.php");
				// Instantiation and passing `true` enables exceptions
				$mail = new PHPMailer(true);
				echo '<div style="display:none;">';
					try {
						include("../../config-general/mail.php");

						$mail->addAddress(strtolower($dU['uss_email']), $dU['uss_nombre']);    
						$mail->addAddress('tecmejia2010@gmail.com', 'Plataforma SINTIA');

						// Content
						$mail->isHTML(true);                                  // Set email format to HTML
						$mail->Subject = 'TUS CREDENCIALES DE ACCESO A SINTIA';
						$mail->Body = $fin;
						$mail->CharSet = 'UTF-8';

						$mail->send();
						echo 'Mensaje enviado correctamente.';
					} catch (Exception $e) {echo "Error: {$mail->ErrorInfo}"; exit();}
				echo '</div>';
				//FIN ENVÍO DE MENSAJE
				
						
		echo '<script type="text/javascript">window.location.href="index.php?msj=1";</script>';
		exit();
	
	}else{
		echo '<script type="text/javascript">window.location.href="index.php?error=3";</script>';
		exit();	
	}
}
?>







