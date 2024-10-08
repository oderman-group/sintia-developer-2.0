<?php
require_once($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/constantes.php");

require_once(ROOT_PATH."/main-app/class/Notificacion.php");
require_once(ROOT_PATH."/main-app/class/Sms.php");

$notificacion = new Notificacion();

$data = [
    'usuario_nombre'      => $_GET['nombre']??='Usuario de SINTIA', //ALL
    'institucion_id'      => 22, //ALL
    'usuario_id'          => 1, //ALL
    'year'                => 2024,  //ALL
    'asunto'              => 'Código de verificación', //EMAIL
    'body_template_route' => ROOT_PATH .'/config-general/template-email-recuperar-clave-codigo.php', //EMAIL
    'usuario_email'       => 'jhonodoe@gmail.com', //EMAIL
    'telefono'            => $_GET['telefono']??='3006075800', //SMS
];

$canal = Notificacion::CANAL_SMS;

try {
    $notificacion->enviarCodigoNotificacion($data, $canal, Notificacion::PROCESO_RECUPERAR_CLAVE);
} catch (Exception $e) {
    echo "Error al enviar el código: ".$e->getMessage();
}

echo '<h1>Mensajes enviados</h1>';
(new Sms)->listarMensajes();