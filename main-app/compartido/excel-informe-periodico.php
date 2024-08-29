<?php
include_once("session-compartida.php");
require_once(ROOT_PATH."/main-app/class/componentes/Excel/ExcelUtil.php");
require_once(ROOT_PATH. "/vendor/autoload.php");
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
// use PhpOffice\PhpSpreadsheet\Reader\Html;

$num=0;

$excelUtil = new ExcelUtil('Reporte de Ventas');
$sheet = $excelUtil->sheet[0];
$spreadsheet = $excelUtil->spreadsheet;
// colores
$ColorSuperior = '14bd09';
$ColorAlto = 'f1d909';
$ColorBasico = '09adbd';
$ColorBajo = 'e71208';

$ColorCabecera = '71f6e6';
$ColorCabecera2 = 'f6e871';

// /////////////////////////////ENCABEZADO////////////////////////////////////////////////
// Style del cuadro de las cabecera
$sheet->getStyle('A1:L5')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN); //Todos los bordes sencillos
$sheet->getStyle('L1:L5')->getBorders()->getRight()->setBorderStyle(Border::BORDER_MEDIUM);     //bordes de la derecha gruesos
$sheet->getStyle('A1:A5')->getBorders()->getLeft()->setBorderStyle(Border::BORDER_MEDIUM);    //bordes de la Izquierda gruesos
$sheet->getStyle('A5:L5')->getBorders()->getBottom()->setBorderStyle(Border::BORDER_MEDIUM);   //bordes de abajo gruesos
//celdas de imagen de la institucion 
$sheet->mergeCells('A1:B5');
$sheet->getColumnDimension('A')->setWidth(10);
$sheet->getColumnDimension('B')->setWidth(15);
$sheet->getRowDimension('5')->setRowHeight(20);
$excelUtil->agregarImagenLogo('A1','logo_ek.jpeg',25,2);
// //celdas de Nombre del reporte
$excelUtil->agregarTitulo('C1','INFORME DE EVALUACIÓN INTERNA DE ESTUDIANTES',14);
$sheet->mergeCells('C1:I5'); // Combinar celdas
// // columna de datos cabecera
$excelUtil->textoNegrita('J1:J5');
$num=1;
$excelUtil->agregarTexto('J'.$num++, 'CODIGO');
$excelUtil->agregarTexto('J'.$num++, 'VERSIÓN');
$excelUtil->agregarTexto('J'.$num++, 'FECHA');
$excelUtil->agregarTexto('J'.$num++, 'Página');
// columna de datos cabecera valores
$num=1;
                                            
$sheet->mergeCells('k'.$num.':L'.$num);   // Combinar celdas   
$excelUtil->agregarTexto('K'.$num++, '');

$sheet->mergeCells('k'.$num.':L'.$num);   // Combinar celdas
$excelUtil->agregarTexto('K'.$num++, '');

$sheet->mergeCells('k'.$num.':L'.$num);   // Combinar celdas  
$excelUtil->agregarTexto('K'.$num++, '');

$sheet->mergeCells('k'.$num.':L'.$num);   // Combinar celdas  
$excelUtil->agregarTexto('K'.$num++, '1 de 1');
// // // /////////////////////////////DATOS INSTITUCION////////////////////////////////////////////////
$excelUtil->textoNegrita('A7:B9'); // colocamos negrita un rango de celdas
$sheet->mergeCells('A7:B7');   // Combinar celdas                                            
$sheet->mergeCells('A8:B8');  // Combinar celdas                                          
$sheet->mergeCells('A9:B9'); // Combinar celdas
$num=7;  
$excelUtil->agregarTexto('A'.$num++, 'INSTITUCIÓN EDUCATIVA:');
$excelUtil->agregarTexto('A'.$num++, 'CÓDIGO DANE:');
$excelUtil->agregarTexto('A'.$num++, 'MUNICIPIO:');
$num=7;                                             
$excelUtil->agregarTexto('C'.$num++, 'xxxxxxxxxxxxxxx');
$excelUtil->agregarTexto('C'.$num++, 'xxxxxxxxxxxxxxx');
$excelUtil->agregarTexto('C'.$num++, 'xxxxxxxxxxxxxxx');
// // // /////////////////////////////CUADRO DE DESEMPENO///////////////////////////////////////////////
$num=6;
$sheet->getStyle('J6:L11')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN); //Todos los bordes sencillos
$sheet->mergeCells('J6:L7');   // Combinar celdas 
$excelUtil->agregarTexto('J'.$num, 'DESEMPEÑO');
$excelUtil->ajustarTexto('J'.$num);
$num=8;
$sheet->mergeCells('k'.$num.':L'.$num);   // Combinar celdas
$excelUtil->establecerColorFondo('J'.$num, $ColorSuperior);
$excelUtil->agregarTexto('K'.$num++, '1xxxxxxxxxxxx');

$sheet->mergeCells('k'.$num.':L'.$num);   // Combinar celdas  
$excelUtil->establecerColorFondo('J'.$num, $ColorAlto);
$excelUtil->agregarTexto('K'.$num++, '2xxxxxxxxxxxx'); 

$sheet->mergeCells('k'.$num.':L'.$num);   // Combinar celdas 
$excelUtil->establecerColorFondo('J'.$num, $ColorBasico);
$excelUtil->agregarTexto('K'.$num++, '3xxxxxxxxxxxx');

$sheet->mergeCells('k'.$num.':L'.$num);   // Combinar celdas 
$excelUtil->establecerColorFondo('J'.$num, $ColorBajo);
$excelUtil->agregarTexto('K'.$num++, '4xxxxxxxxxxxx');    


// // // /////////////////////////////DATOS ASIGNADTURA////////////////////////////////////////////////
$sheet->mergeCells('A10:I11'); // Combinar celdas
$num=10;  
$excelUtil->agregarTitulo('A'.$num++, 'ASIGNATURA: ---------------------');
// // // /////////////////////////////CABECERA DATOS////////////////////////////////////////////////
$num=12;  
$sheet->mergeCells('A12:L12'); // Combinar celdas 
$excelUtil->agregarTitulo('A'.$num++, 'ANO: xxxx',null,null,$ColorCabecera);
// // // /////////////////////////////CABECERA DATOS CAMPOS////////////////////////////////////////////////
$sheet->getStyle('A12:L15')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN); //Todos los bordes sencillos
$sheet->mergeCells('A'.$num.':A'.$num+1); // Combinar celdas 
$excelUtil->agregarTitulo('A'.$num, 'No.',null,null,$ColorCabecera2);

$sheet->mergeCells('B'.$num.':B'.$num+1); // Combinar celdas 
$excelUtil->agregarTitulo('B'.$num, 'TIPO DOC',null,null,$ColorCabecera2);

$sheet->mergeCells('C'.$num.':C'.$num+1); // Combinar celdas 
$excelUtil->agregarTitulo('C'.$num, 'N° DOCUMENTO',null,null,$ColorCabecera2);

$sheet->mergeCells('D'.$num.':E'.$num+1); // Combinar celdas 
$excelUtil->agregarTitulo('D'.$num, 'NOMBRE DEL ESTUDIANTE',null,null,$ColorCabecera2);
$sheet->getColumnDimension('E')->setWidth(30);// Establecer un ancho específico para la columna E 

$sheet->mergeCells('F'.$num.':F'.$num+1); // Combinar celdas 
$excelUtil->agregarTitulo('F'.$num, 'GRADO',null,null,$ColorCabecera2);

$sheet->mergeCells('G'.$num.':G'.$num+1); // Combinar celdas 
$excelUtil->agregarTitulo('G'.$num, 'SEDE',null,null,$ColorCabecera2);

$sheet->mergeCells('H'.$num.':L'.$num); // Combinar celdas 
$excelUtil->agregarTitulo('H'.$num++, 'PERIODOS',null,null,$ColorCabecera2);
$excelUtil->agregarTitulo('H'.$num, '1',null,null,$ColorCabecera2);
$excelUtil->agregarTitulo('I'.$num, '2',null,null,$ColorCabecera2);
$excelUtil->agregarTitulo('J'.$num, '3',null,null,$ColorCabecera2);
$excelUtil->agregarTitulo('K'.$num, '4',null,null,$ColorCabecera2);
$excelUtil->agregarTitulo('L'.$num, 'FINAL',null,null,$ColorCabecera2);


$excelUtil->descargarExcel();

exit;

