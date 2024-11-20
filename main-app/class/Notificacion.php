<?php
require_once(ROOT_PATH."/main-app/class/Sms.php");
require_once(ROOT_PATH."/main-app/class/EnviarEmail.php");
require_once(ROOT_PATH."/main-app/class/Tables/BDT_codigo_verificacion.php");

class Notificacion {

    public const CANAL_SMS   = 'SMS';
    public const CANAL_EMAIL = 'EMAIL';

    public const CANALES_VALIDOS = [self::CANAL_SMS, self::CANAL_EMAIL];

    public const PROCESO_RECUPERAR_CLAVE = 'RECUPERAR_CLAVE';
    public const PROCESO_ACTIVAR_CUENTA  = 'ACTIVAR_CUENTA';

    public const PROCESOS_VALIDOS = [self::PROCESO_RECUPERAR_CLAVE, self::PROCESO_ACTIVAR_CUENTA];

    public function enviarCodigoNotificacion(array $data, $canal, $proceso) {

        if (!in_array($canal, self::CANALES_VALIDOS)) {
            throw new Exception("Canal de notificación inválido.");
        }

        if (!in_array($proceso, self::PROCESOS_VALIDOS)) {
            throw new Exception("Proceso de notificación inválido.");
        }

        $codigo  = $this->generarCodigoValido();
        $mensaje = 'Hola '.$data['usuario_nombre'].', tu código de verificación SINTIA es: '. $codigo;

        $datos = [
            'codv_usuario_asociado'    => $data['usuario_id'],
            'institucion'              => $data['institucion_id'],
            'year'                     => $data['year'],
            'codv_canal'               => $canal,
            'codv_tipo_proceso'        => $proceso,
            'codv_codigo_verificacion' => $codigo,
            'codv_activo'              => 1,
        ];

        $this->guardarCodigoValido($datos);

        switch ($canal) {
            case self::CANAL_SMS:
                $sms = new Sms();
                $data['mensaje'] = $mensaje;
                $sms->enviarSms($data);
                break;

            case self::CANAL_EMAIL:
                $asunto            = $data['asunto'] . $codigo;
                $bodyTemplateRoute = $data['body_template_route'];
                $data['codigo']    = $codigo; // Añadir el código al array de datos para el template de email.

                EnviarEmail::enviar($data, $asunto, $bodyTemplateRoute, null, null);
                break;
        }

    }

    public function generarCodigoValido($typeCode = 'numeric'): string {
        switch ($typeCode) {
            case 'numeric':
                return rand(100000, 999999);
            case 'alphanumeric':
                return substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'), 0, 6);
            default:
                throw new Exception("Tipo de código inválido.");
        }
    }

    public function guardarCodigoValido(array $datos) {
        BDT_CodigoVerificacion::Insert($datos, BD_ADMIN);
    }

}