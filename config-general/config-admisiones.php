<?php
switch($_SERVER['HTTP_HOST']){
	case 'localhost':
	$BD_ADMISIONES_MOCK = 'odermangroup_dev_2023';
	break;

	case 'developer.plataformasintia.com':
    $BD_ADMISIONES_MOCK = 'mobiliar_dev_2023';
	break;

	case 'main.plataformasintia.com':
    $BD_ADMISIONES_MOCK = 'mobiliar_dev_2023';
	break;
}

#VARIABLES PARA SUBMODULO DE ADMISIONES
$estadosSolicitud = array(
	1 => 'VERIFICACIÓN DE PAGO', 
	2 => 'PAGO RECHAZADO', 
	3 => 'PENDIENTE POR DILIGENCIAR EL FORMULARIO',
	4 => 'EN PROCESO',
	5 => 'EXAMEN Y ENTREVISTA', 
	6 => 'APROBADO', 
	7 => 'NO APROBADO',
	8 => 'VERIFICACIÓN DE CUPO DISPONIBLE',
	9 => 'MOVIDO AL AÑO SIGUIENTE'
);
$progresoSolicitud = array(
	1 => '15%', 
	2 => '15%', 
	3 => '30%', 
	4 => '60%',
	5 => '75%', 
	6 => '90%',
	7 => '100%',
	8 => '15%',
	9 => '100%',
);