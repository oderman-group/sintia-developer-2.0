<?php
#VARIABLES PARA MODULO DE ADMISIONES
$estadosSolicitud = [
	8 	=> 'VERIFICACIÓN DE CUPO DISPONIBLE',
	1 	=> 'VERIFICACIÓN DE PAGO', 
	2 	=> 'PAGO RECHAZADO', 
	3 	=> 'PENDIENTE POR DILIGENCIAR EL FORMULARIO',
	4 	=> 'EN PROCESO',
	5 	=> 'EXAMEN Y ENTREVISTA', 
	6 	=> 'APROBADO', 
	7 	=> 'NO APROBADO',
	9 	=> 'MOVIDO AL AÑO SIGUIENTE',
	10 	=> 'NO CONTINUA CON EL PROCESO'
];

$ordenReal = [8, 3, 4, 1, 2, 5, 6, 7, 10, 9];

$progresoSolicitud = [
	1 	=> '30%', 
	2 	=> '30%', 
	3 	=> '45%', 
	4 	=> '60%',
	5 	=> '75%', 
	6 	=> '90%',
	7 	=> '100%',
	8 	=> '15%',
	9 	=> '100%',
	10 	=> '100%',
];

$fondoSolicitud = [
	1 	=> 'cadetblue',
	2 	=> '#DB6503',
	3 	=> 'cadetblue',
	4 	=> '#AFB372',
	5 	=> '#AFB372',
	6 	=> 'green',
	7 	=> 'red',
	8 	=> '#DA9E00',
	9 	=> '#009B7A',
	10 	=> 'red'
];