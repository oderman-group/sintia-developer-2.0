<?php
define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'].'/app-sintia');

define('EMAIL_SERVER', 'jemima.dongee.com');
define('EMAIL_USER', 'info@plataformasintia.com');
define('EMAIL_PASSWORD', 'B=XKY?y{VWiH');
define('EMAIL_SENDER', 'info@plataformasintia.com');
define('NAME_SENDER', 'Nombre constante');

define('HEADER_EMAIL_BACKGROUND', '#6017dc');

switch($_SERVER['HTTP_HOST']){
	case 'localhost':
        define('REDIRECT_ROUTE', 'http://localhost/app-sintia/main-app');
        error_reporting (E_ALL ^ E_NOTICE ^ E_WARNING);
        break;

	case 'developer.plataformasintia.com':
        define('REDIRECT_ROUTE', 'https://developer.plataformasintia.com/app-sintia/main-app');
        break;

	case 'main.plataformasintia.com':
        define('REDIRECT_ROUTE', 'https://main.plataformasintia.com/app-sintia/main-app');
        error_reporting (E_ALL ^ E_NOTICE ^ E_WARNING);
        break;
}