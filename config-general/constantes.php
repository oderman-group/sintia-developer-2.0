<?php
define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'].'/app-sintia');
include(ROOT_PATH."/sensitive.php");

define('EMAIL_SENDER', 'info@plataformasintia.com');
define('NAME_SENDER', 'Plataforma Sintia');

define('HEADER_EMAIL_BACKGROUND', '#6017dc');

define('JOBS_ESTADO_PENDIENTE', 'Pendiente');
define('JOBS_ESTADO_FINALIZADO', 'Finalizado');
define('JOBS_ESTADO_ERROR', 'Error');

define('JOBS_TIPO_IMPORTAR_ESTUDIANTES_EXCEL', 'importar_estudiantes');
define('JOBS_TIPO_GENERAR_INFORMES', 'generar_informes');

define('JOBS_PRIORIDAD_ALTA', 'Alta');
define('JOBS_PRIORIDAD_MEDIA', 'Media');
define('JOBS_PRIORIDAD_BAJA', 'Baja');

define('MENU', 'menu');
define('MENU_PADRE', 'menu-padre');
define('SUB_MENU', 'sub-menu');

define('ESTADO_EMAIL_ENVIADO', 'enviado');
define('ESTADO_EMAIL_ERROR', 'error');


define('SOLICITUD_CANCELACION_PENDIENTE', 'Pendiente');
define('SOLICITUD_CANCELACION_APROBADO', 'Aprobado');
define('SOLICITUD_CANCELACION_CANCELADO', 'Cancelado');
switch (ENVIROMENT) {
        case 'LOCAL':
	include(ROOT_PATH."/conexion-datos.php");
        define('BD_PREFIX', 'odermangroup_');
	break;

	case 'TEST':
	include(ROOT_PATH."/conexion-datos-developer.php");
        define('BD_PREFIX', 'mobiliar_');
	break;

        case 'PROD':
        include(ROOT_PATH."/conexion-datos-production.php");
        define('BD_PREFIX', 'mobiliar_');
        break;

        default:
        include(ROOT_PATH."/conexion-datos.php");
        define('BD_PREFIX', 'odermangroup_');
        break;
}

switch($_SERVER['HTTP_HOST']){
	case 'localhost':
        define('REDIRECT_ROUTE', 'http://localhost/app-sintia/main-app');
        //error_reporting (E_ALL ^ E_NOTICE ^ E_WARNING);
        break;

	case 'developer.plataformasintia.com':
        define('REDIRECT_ROUTE', 'https://developer.plataformasintia.com/app-sintia/main-app');
        // error_reporting (E_ALL ^ E_NOTICE ^ E_WARNING);
        break;

	case 'main.plataformasintia.com':
        define('REDIRECT_ROUTE', 'https://main.plataformasintia.com/app-sintia/main-app');
        error_reporting (E_ALL ^ E_NOTICE ^ E_WARNING);
        break;

        case 'mt.plataformasintia.com':
        define('REDIRECT_ROUTE', 'https://mt.plataformasintia.com/app-sintia/main-app');
        error_reporting (E_ALL ^ E_NOTICE ^ E_WARNING);
        break;

        default:
        define('REDIRECT_ROUTE', 'https://main.plataformasintia.com/app-sintia/main-app');
        error_reporting (E_ALL ^ E_NOTICE ^ E_WARNING);
        break;
}