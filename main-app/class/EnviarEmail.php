<?php
require_once($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/constantes.php");
require_once(ROOT_PATH."/main-app/class/Usuarios.php");
require_once(ROOT_PATH."/main-app/class/UsuariosPadre.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require ROOT_PATH.'/librerias/phpmailer/Exception.php';
require ROOT_PATH.'/librerias/phpmailer/PHPMailer.php';
require ROOT_PATH.'/librerias/phpmailer/SMTP.php';

class EnviarEmail {

    /**
     * Este función envía un correo electrónico
     * 
     * @param array $data
     * @param string $asunto
     * @param string $bodyTemplateRoute
     * 
     * @return void
     */
    public static function enviar($data, $asunto, $bodyTemplateRoute): void
    {
        global $mail;

        $mail = new PHPMailer(true);

        try {

            ob_start();
            include($bodyTemplateRoute);
            $body = ob_get_clean();

            //Server settings
            $mail->SMTPDebug = 0;                                       // Enable verbose debug output
            $mail->isSMTP();                                            // Set mailer to use SMTP
            $mail->Host       = EMAIL_SERVER;  	                        // Specify main and backup SMTP servers
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = EMAIL_USER;              
            $mail->Password   = EMAIL_PASSWORD;                     
            $mail->SMTPSecure = 'ssl';                                  // Enable TLS encryption, `ssl` also accepted
            $mail->Port       = 465;

            //Remitente
            $mail->setFrom(EMAIL_SENDER, NAME_SENDER);

            //Destinatarios
            $mail->addAddress('soporte@plataformasintia.com', 'Soporte Plataforma SINTIA');
            $mail->addAddress($data['usuario_email'], $data['usuario_nombre']);

            // Content
            $mail->isHTML(true);                                   // Set email format to HTML
            $mail->Subject = $asunto;
            $mail->Body = $body;
            $mail->CharSet = 'UTF-8';

            $mail->send();

        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }

    }

}