<?php
define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'].'/app-sintia');
include(ROOT_PATH."/sensitive.php");

define('EMAIL_SENDER', 'info@plataformasintia.com');
define('NAME_SENDER', 'Plataforma Sintia');

define('HEADER_EMAIL_BACKGROUND', '#6017dc');
define('GRADO_INDIVIDUAL', 'individual');
define('GRADO_GRUPAL', 'grupal');


define('JOBS_ESTADO_PENDIENTE', 'Pendiente');
define('JOBS_ESTADO_PROCESO', 'Proceso');
define('JOBS_ESTADO_FINALIZADO', 'Finalizado');
define('JOBS_ESTADO_ERROR', 'Error');

define('JOBS_TIPO_IMPORTAR_ESTUDIANTES_EXCEL', 'importar_estudiantes');
define('JOBS_TIPO_GENERAR_INFORMES', 'generar_informes');

define('JOBS_PRIORIDAD_ALTA', '1');
define('JOBS_PRIORIDAD_MEDIA', '2');
define('JOBS_PRIORIDAD_BAJA', '3');

define('MENU', 'menu');
define('MENU_PADRE', 'menu-padre');
define('SUB_MENU', 'sub-menu');

define('ESTADO_EMAIL_ENVIADO', 'enviado');
define('ESTADO_EMAIL_ERROR', 'error');


define('SOLICITUD_CANCELACION_PENDIENTE', 'Pendiente');
define('SOLICITUD_CANCELACION_APROBADO', 'Aprobado');
define('SOLICITUD_CANCELACION_CANCELADO', 'Cancelado');


define('CHAT_TIPO_MENSAJE', 1);
define('CHAT_TIPO_IMAGEN', 2);
define('CHAT_TIPO_DOCUMENTO', 3);
define('CHAT_TIPO_AUDIO', 4);


/* TIPOS DE USUARIO DE LA PLATAFORMA */
define('TIPO_DEV', 1);
define('TIPO_DOCENTE', 2);
define('TIPO_ACUDIENTE', 3);
define('TIPO_ESTUDIANTE', 4);
define('TIPO_DIRECTIVO', 5);

/* CLIENTES CON CAMBIOS PARTICULARES*/
define('ICOLVEN', 1);
define('DEVELOPER', 22);
define('DEVELOPER_PROD', 30);

define('PORCENTAJE_MINIMO_GENERAR_INFORME', 99);

/* CONSTANTES ACADEMICAS */
define('CONFIG_MANUAL_INDICADOR', 1);
define('CONFIG_AUTOMATICO_INDICADOR', 0);
define('CONFIG_MANUAL_CALIFICACIONES', 1);
define('CONFIG_AUTOMATICO_CALIFICACIONES', 0);
define('PERMISO_EDICION_PERIODOS_DIFERENTES', true); //Diferentes al actual

/* FORMAS DE VER NOTAS */
define('CUALITATIVA', 'CUALITATIVA');
define('CUANTITATIVA', 'CUANTITATIVA');

define('CLAVE_SUGERIDA', 'sherman1298');

/* SINTIA PLATFORM MODULES */
define('MODULO_ACADEMICO', 1);
define('MODULO_FINANCIERO', 2);
define('MODULO_DISCIPLINARIO', 3);
define('MODULO_ADMINISTRATIVO', 4);
define('MODULO_COMUNICATIVO', 5);
define('MODULO_MERCADEO', 6);
define('MODULO_GENERAL', 7);
define('MODULO_ADMISIONES', 8);
define('MODULO_RESERVA_CUPO', 9);
define('MODULO_MEDIA_TECNICA', 10);


switch($_SERVER['HTTP_HOST']){
	case 'localhost':
        define('REDIRECT_ROUTE', 'http://localhost/app-sintia/main-app');
        define('ENVIROMENT', 'TEST');
        break;

	case 'developer.plataformasintia.com':
        define('REDIRECT_ROUTE', 'https://developer.plataformasintia.com/app-sintia/main-app');
        define('ENVIROMENT', 'TEST');
        break;

	case 'main.plataformasintia.com':
        define('REDIRECT_ROUTE', 'https://main.plataformasintia.com/app-sintia/main-app');
        define('ENVIROMENT', 'PROD');
        error_reporting (E_ALL ^ E_NOTICE ^ E_WARNING);
        break;

        case 'copyprod.plataformasintia.com':
        define('REDIRECT_ROUTE', 'https://copyprod.plataformasintia.com/app-sintia/main-app');
        define('ENVIROMENT', 'PROD');
        error_reporting (E_ALL ^ E_NOTICE ^ E_WARNING);
        break;

        default:
        define('REDIRECT_ROUTE', 'https://'.$_SERVER['HTTP_HOST'].'/app-sintia/main-app');
        define('ENVIROMENT', 'TEST');
        error_reporting (E_ALL ^ E_NOTICE ^ E_WARNING);
        break;
}

switch (ENVIROMENT) {
        case 'LOCAL':
	include(ROOT_PATH."/conexion-datos.php");
        define('BD_PREFIX', 'odermangroup_');
        define('EPAYCO_TEST', 'TRUE');
	break;

	case 'TEST':
	include(ROOT_PATH."/conexion-datos-developer.php");
        define('BD_PREFIX', 'mobiliar_');
        define('EPAYCO_TEST', 'FALSE');
	break;

        case 'PROD':
        include(ROOT_PATH."/conexion-datos-production.php");
        define('BD_PREFIX', 'mobiliar_');
        define('EPAYCO_TEST', 'FALSE');
        break;

        default:
        include(ROOT_PATH."/conexion-datos.php");
        define('BD_PREFIX', 'odermangroup_');
        break;
}