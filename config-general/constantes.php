<?php
define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'].'/app-sintia');
include(ROOT_PATH."/sensitive.php");

define('EMAIL_SENDER', 'info@plataformasintia.com');
define('NAME_SENDER', 'Plataforma Sintia');

define('HEADER_EMAIL_BACKGROUND', '#6017dc');
define('GRADO_INDIVIDUAL', 'individual');
define('GRADO_GRUPAL', 'grupal');

switch (ENVIROMENT) {
        case 'LOCAL':
	include(ROOT_PATH."/conexion-datos.php");
	break;

	case 'TEST':
	include(ROOT_PATH."/conexion-datos-developer.php");
	break;

        default:
        include(ROOT_PATH."/conexion-datos.php");
        break;
}

switch($_SERVER['HTTP_HOST']){
	case 'localhost':
        define('REDIRECT_ROUTE', 'http://localhost/app-sintia/main-app');
        define('BD_PREFIX', 'odermangroup_');
        error_reporting (E_ALL ^ E_NOTICE ^ E_WARNING);
        break;

	case 'developer.plataformasintia.com':
        define('REDIRECT_ROUTE', 'https://developer.plataformasintia.com/app-sintia/main-app');
        define('BD_PREFIX', 'mobiliar_');
        break;

	case 'main.plataformasintia.com':
        define('REDIRECT_ROUTE', 'https://main.plataformasintia.com/app-sintia/main-app');
        define('BD_PREFIX', 'mobiliar_');
        error_reporting (E_ALL ^ E_NOTICE ^ E_WARNING);
        break;
}