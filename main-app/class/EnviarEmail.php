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
    public static function enviar($data, $asunto,$bodyTemplateRoute,$body, $archivos): void
    {
        global $mail;

        $mail = new PHPMailer(true);

        try {
            
            if(!is_null($bodyTemplateRoute)){
                ob_start();
                include($bodyTemplateRoute);
                $body = ob_get_clean();
            }
            

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
            $correrocopia='soporte@plataformasintia.com';
            $destinatario=$data['usuario_email'];
            $destinatario2=empty($data['usuario2_email'])?null:$data['usuario2_email'];
            $validarRemitente = self::validarEmail(EMAIL_SENDER);
            $validarDestinatario = self::validarEmail($destinatario);
            $validarDestinatario2 = is_null($destinatario2)?true:self::validarEmail($destinatario2);
            $validarcopia = self::validarEmail($correrocopia);

            if($validarRemitente && $validarDestinatario &&  $validarDestinatario2 && $validarcopia){
                    //Destinatarios
                    $mail->addAddress($correrocopia, 'Soporte Plataforma SINTIA');
                    $mail->addAddress($destinatario, $data['usuario_nombre']);
                    if(!is_null($destinatario2)){
                        $mail->addAddress($destinatario2, $data['usuario2_nombre']);
                    }
                    
                    // Content
                    $mail->isHTML(true);                                   // Set email format to HTML
                    $mail->Subject = $asunto;
                    $mail->Body = $body;
                    $mail->CharSet = 'UTF-8';
                    if($archivos && !empty($archivos)){
                        $index =1;
                        foreach ($archivos as &$valor) {
                            $valor ;
                            $mail->AddAttachment($valor);
                        }                         
                    }
                    $mail->send();
                    self::enviarReporte($data['institucion_id'],$mail,EMAIL_SENDER,$destinatario,$asunto,$body,ESTADO_EMAIL_ENVIADO,'');  
            }else{                 
                    if(!$validarRemitente){
                        self::enviarReporte($data['institucion_id'],$mail,EMAIL_SENDER,$destinatario,$asunto,$body,ESTADO_EMAIL_ERROR,'Error remitente'.EMAIL_SENDER);  
                        self::mensajeError(EMAIL_SENDER);        
                    } 
                    if(!$validarDestinatario){
                        self::enviarReporte($data['institucion_id'],$mail,EMAIL_SENDER,$destinatario,$asunto,$body,ESTADO_EMAIL_ERROR,'Error destinatario'.$destinatario); 
                        self::mensajeError($destinatario);        
                    }
                    if(!$validarDestinatario2){
                        self::enviarReporte($data['institucion_id'],$mail,EMAIL_SENDER,$destinatario,$asunto,$body,ESTADO_EMAIL_ERROR,'Error destinatario 2'.$destinatario2); 
                        self::mensajeError($destinatario2);        
                    }    
                    if(!$validarcopia){
                        self::enviarReporte($data['institucion_id'],$mail,EMAIL_SENDER,$destinatario,$asunto,$body,ESTADO_EMAIL_ERROR,'Error destinatario'.$correrocopia); 
                        self::mensajeError($correrocopia);        
                    } 
            }

        } catch (Exception $e) {
            self::enviarReporte($data['institucion_id'],$mail,EMAIL_SENDER,$destinatario,$asunto,$body,ESTADO_EMAIL_ERROR,$e->getMessage());
            include("../compartido/error-catch-to-report.php");
        }

    }
    /**
     * Este función valida un correo electrónico que tenga la estructura correcta emplo@dominio.com
     * 
     * @param string $email
     * 
     * @return boolean
     */
    public static function validarEmail($email) 
    {
        $matches = null;
        // Expresion regular
        $regex = "/^[A-z0-9\\._-]+@[A-z0-9][A-z0-9-]*(\\.[A-z0-9_-]+)*\\.([A-z]{2,6})$/";
       return (1 === preg_match($regex, $email, $matches));      
    }

    /**
     * Este función envia el mensaje de error a la pagina de donde se llamo
     * 
     * @param string $email
     * 
     * @return void
     */
    private static function mensajeError($email) 
    {
        $msj=' el Correo '.$email.' no cumple con la estructura de un correo valido';
        $url=$_SERVER["HTTP_REFERER"];
        $pos = strpos($url, "?");
        $simbolConcatenar=$pos===false?"?":"&";
        $url=$url.$simbolConcatenar.'error=ER_DT_15&msj='.$msj;
        echo '<script type="text/javascript">window.location.href="'.$url.'";</script>';
        exit();      
    }

    
    /**
     * Este función envia el mensaje a la tabla de historial de correos enviados 
     * 
     * @param string $institucion
     * @param Object $mail
     * @param string $remitente
     * @param string $destinatario
     * @param string $asunto
     * @param string $body
     * @param string $estado
     * @param string $descripcion
     * 
     * @return void
     */
    private static function enviarReporte($institucion,$mail,$remitente,$destinatario,$asunto,$body,$estado,$descripcion){
        global $conexion;
        global $baseDatosServicios;
        if(is_null($conexion)){
            global $servidorConexion;
            global $usuarioConexion;
            global $claveConexion;
            $conexion = mysqli_connect($servidorConexion, $usuarioConexion, $claveConexion, $baseDatosServicios);
        }
        
        $adjunto=$mail->attachmentExists();
        try{
            $sql="INSERT INTO ".$baseDatosServicios.".historial_correos_enviados(
                hisco_fecha,
                hisco_remitente,
                hisco_destinatario,
                hisco_asunto,
                hisco_contenido,
                hisco_adjunto,
                hisco_archivo_salida,
                hisco_estado,
                hisco_descripcion_error,
                hisco_id_institucion
                )VALUES(
                now(),
                '".$remitente."',
                '".$destinatario."',
                '".$asunto."',
                '".$body."',
                '".$adjunto."',
                '".$_SERVER["HTTP_REFERER"]."',
                '".$estado."',
                '".$descripcion."',
                '".$institucion."')";
            mysqli_query($conexion,$sql );
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }
    }

}