<?php
include_once("session-compartida.php");
require_once(ROOT_PATH . "/main-app/class/componentes/Excel/ExcelUtil.php");
require_once(ROOT_PATH . "/main-app/class/Utilidades.php");
require_once(ROOT_PATH . "/vendor/autoload.php");
require_once(ROOT_PATH . "/main-app/class/CargaAcademica.php");
require_once(ROOT_PATH . "/main-app/class/Boletin.php");

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
// use PhpOffice\PhpSpreadsheet\Reader\Html;
require_once("../class/Informes.php");

$num = 0;
try {
    $curos[0] = $_POST["grado"];
    $listagrupos = $_POST["grupos"];
    $listaMaterias = $_POST["materias"];
    $consulta = Informes::informePeriodico($curos, $listagrupos, $listaMaterias);
    $estilosNota = Boletin::listarTipoDeNotas($config["conf_notas_categoria"], $_SESSION["bd"]);
    $listaTipoNotas = $estilosNota->fetch_all(MYSQLI_ASSOC);
    // colores
    $ColorSuperior   = '14bd09';
    $ColorAlto       = 'f1d909';
    $ColorBasico     = '09adbd';
    $ColorBajo       = 'e71208';
    $ColorTextoClaro = 'f9f1e8';
    $ColorTextoOscuro = '302e2c';
    $ColorCabecera   = '71f6e6';
    $ColorCabecera2  = 'f6e871';

    if (!empty($listaMaterias)) {
        $excelUtil = new ExcelUtil("uno");


        $indice = 0;
        $inicio = false;
        $sheet = $excelUtil->sheet[0];
        foreach ($listaMaterias as $idMateria) {
            $materia = CargaAcademica::consultarMateria($idMateria);
            if (!$inicio) {
                $sheet->setTitle($materia["mat_nombre"]);
                $inicio = true;
            } else {
                $excelUtil->agregarHoja($materia["mat_nombre"]);
            }

            // /////////////////////////////ENCABEZADO////////////////////////////////////////////////
            // Style del cuadro de las cabecera
            $excelUtil->sheet[$indice]->getStyle('A1:K5')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN); //Todos los bordes sencillos
            $excelUtil->sheet[$indice]->getStyle('K1:K5')->getBorders()->getRight()->setBorderStyle(Border::BORDER_MEDIUM);     //bordes de la derecha gruesos
            $excelUtil->sheet[$indice]->getStyle('A1:A5')->getBorders()->getLeft()->setBorderStyle(Border::BORDER_MEDIUM);    //bordes de la Izquierda gruesos
            $excelUtil->sheet[$indice]->getStyle('A5:k5')->getBorders()->getBottom()->setBorderStyle(Border::BORDER_MEDIUM);   //bordes de abajo gruesos

            //celdas de imagen de la institucion 
            $excelUtil->sheet[$indice]->mergeCells('A1:B5');
            $excelUtil->sheet[$indice]->getColumnDimension('A')->setWidth(10);
            $excelUtil->sheet[$indice]->getColumnDimension('B')->setWidth(15);
            $excelUtil->sheet[$indice]->getRowDimension('5')->setRowHeight(20);

            $urlImage = '../files/images/logo/' . $informacion_inst["info_logo"];
            if (!Utilidades::ArchivoExiste($urlImage)) {
                $urlImage = '../files/images/logo/sintia-logo-2023.png';
            }
            $excelUtil->agregarImagenLogo('A1', $urlImage, 25, 2);
            // //celdas de Nombre del reporte
            $excelUtil->agregarTitulo('C1', 'INFORME DE EVALUACIÓN INTERNA DE ESTUDIANTES', 14);
            $excelUtil->sheet[$indice]->mergeCells('C1:H5'); // Combinar celdas
            // // columna de datos cabecera
            $excelUtil->textoNegrita('I1:I5');
            $num = 1;
            $excelUtil->agregarTexto('I' . $num++, 'CODIGO');
            $excelUtil->agregarTexto('I' . $num++, 'VERSIÓN');
            $excelUtil->agregarTexto('I' . $num++, 'FECHA');
            $excelUtil->agregarTexto('I' . $num++, 'Página');
            // columna de datos cabecera valores
            $num = 1;

            $excelUtil->sheet[$indice]->mergeCells('J' . $num . ':K' . $num);   // Combinar celdas   
            $excelUtil->agregarTexto('J' . $num++, '');

            $excelUtil->sheet[$indice]->mergeCells('J' . $num . ':K' . $num);   // Combinar celdas
            $excelUtil->agregarTexto('J' . $num++, '');

            $excelUtil->sheet[$indice]->mergeCells('J' . $num . ':K' . $num);   // Combinar celdas  
            $excelUtil->agregarTexto('J' . $num++, '');

            $excelUtil->sheet[$indice]->mergeCells('J' . $num . ':K' . $num);   // Combinar celdas  
            $excelUtil->agregarTexto('J' . $num++, ($indice + 1) . ' de ' . count($listaMaterias));
            // // // /////////////////////////////DATOS INSTITUCION////////////////////////////////////////////////
            $excelUtil->textoNegrita('A7:B9'); // colocamos negrita un rango de celdas
            $excelUtil->sheet[$indice]->mergeCells('A7:B7');   // Combinar celdas                                            
            $excelUtil->sheet[$indice]->mergeCells('A8:B8');  // Combinar celdas                                          
            $excelUtil->sheet[$indice]->mergeCells('A9:B9'); // Combinar celdas
            $num = 7;
            $excelUtil->agregarTexto('A' . $num++, 'INSTITUCIÓN EDUCATIVA:');
            $excelUtil->agregarTexto('A' . $num++, 'CÓDIGO DANE:');
            $excelUtil->agregarTexto('A' . $num++, 'MUNICIPIO:');
            $num = 7;
            $excelUtil->sheet[$indice]->mergeCells('C'.$num.':H'.$num); // Combinar celdas
            $excelUtil->agregarTexto('C' . $num++,  $informacion_inst["info_nombre"]); 
            $excelUtil->sheet[$indice]->mergeCells('C'.$num.':H'.$num); // Combinar celdas          
            $excelUtil->agregarTexto('C' . $num++,  $informacion_inst["info_dane"] . ' ');
            $excelUtil->sheet[$indice]->mergeCells('C'.$num.':H'.$num); // Combinar celdas
            $excelUtil->agregarTexto('C' . $num++, $informacion_inst["info_direccion"].' ');
            // // // /////////////////////////////CUADRO DE DESEMPENO///////////////////////////////////////////////
            $num = 6;
            $excelUtil->sheet[$indice]->getStyle('I6:K11')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN); //Todos los bordes sencillos
            $excelUtil->sheet[$indice]->mergeCells('I6:K7');   // Combinar celdas 
            $excelUtil->agregarTexto('I' . $num, 'DESEMPEÑO');
            $excelUtil->ajustarTexto('I' . $num);
            $num = 8;
            $excelUtil->sheet[$indice]->mergeCells('J' . $num . ':K' . $num);   // Combinar celdas
            $excelUtil->agregarTexto('I' . $num, ' de '.$listaTipoNotas[3]["notip_desde"] . ' hasta ' . $listaTipoNotas[3]["notip_hasta"], $ColorTextoOscuro, $ColorSuperior);
            $excelUtil->agregarTexto('J' . $num++, $listaTipoNotas[3]["notip_nombre"]);

            $excelUtil->sheet[$indice]->mergeCells('J' . $num . ':K' . $num);   // Combinar celdas  
            $excelUtil->agregarTexto('I' . $num, ' de '.$listaTipoNotas[3]["notip_desde"] . ' hasta ' . $listaTipoNotas[3]["notip_hasta"], $ColorTextoOscuro, $ColorAlto);
            $excelUtil->agregarTexto('J' . $num++,  $listaTipoNotas[2]["notip_nombre"]);

            $excelUtil->sheet[$indice]->mergeCells('J' . $num . ':K' . $num);   // Combinar celdas 
            $excelUtil->agregarTexto('I' . $num, ' de '.$listaTipoNotas[3]["notip_desde"] . ' hasta ' . $listaTipoNotas[3]["notip_hasta"], $ColorTextoOscuro, $ColorBasico);
            $excelUtil->agregarTexto('J' . $num++,  $listaTipoNotas[1]["notip_nombre"]);

            $excelUtil->sheet[$indice]->mergeCells('J' . $num . ':K' . $num);   // Combinar celdas 
            $excelUtil->agregarTexto('I' . $num, ' de '.$listaTipoNotas[3]["notip_desde"] . ' hasta ' . $listaTipoNotas[3]["notip_hasta"], $ColorTextoClaro, $ColorBajo);
            $excelUtil->agregarTexto('J' . $num++,  $listaTipoNotas[0]["notip_nombre"]);
            // // // /////////////////////////////DATOS ASIGNADTURA////////////////////////////////////////////////
            $excelUtil->sheet[$indice]->mergeCells('A10:H11'); // Combinar celdas
            $num = 10;
            $excelUtil->agregarTitulo('A' . $num++, 'ASIGNATURA: ' . $materia["mat_nombre"]);
            // // // /////////////////////////////CABECERA DATOS////////////////////////////////////////////////
            $num = 12;
            $excelUtil->sheet[$indice]->mergeCells('A12:K12'); // Combinar celdas 
            $excelUtil->agregarTitulo('A' . $num++, 'ANO: '. $_SESSION["bd"], null, null, $ColorCabecera);
            // // // /////////////////////////////CABECERA DATOS CAMPOS////////////////////////////////////////////////
            $excelUtil->sheet[$indice]->getStyle('A12:K14')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN); //Todos los bordes sencillos
            $excelUtil->sheet[$indice]->mergeCells('A' . $num . ':A' . $num + 1); // Combinar celdas 
            $excelUtil->agregarTitulo('A' . $num, 'No.', null, null, $ColorCabecera2);

            $excelUtil->sheet[$indice]->mergeCells('B' . $num . ':B' . $num + 1); // Combinar celdas 
            $excelUtil->agregarTitulo('B' . $num, 'TIPO DOC', null, null, $ColorCabecera2);

            $excelUtil->sheet[$indice]->mergeCells('C' . $num . ':C' . $num + 1); // Combinar celdas 
            $excelUtil->agregarTitulo('C' . $num, 'N° DOCUMENTO', null, null, $ColorCabecera2);

            $excelUtil->sheet[$indice]->mergeCells('D' . $num . ':D' . $num + 1); // Combinar celdas 
            $excelUtil->agregarTitulo('D' . $num, 'NOMBRE DEL ESTUDIANTE', null, null, $ColorCabecera2);

            $excelUtil->sheet[$indice]->mergeCells('E' . $num . ':E' . $num + 1); // Combinar celdas 
            $excelUtil->agregarTitulo('E' . $num, 'GRADO', null, null, $ColorCabecera2);

            $excelUtil->sheet[$indice]->mergeCells('F' . $num . ':F' . $num + 1); // Combinar celdas 
            $excelUtil->agregarTitulo('F' . $num, 'SEDE', null, null, $ColorCabecera2);

            $excelUtil->sheet[$indice]->mergeCells('G' . $num . ':K' . $num); // Combinar celdas 
            $excelUtil->agregarTitulo('G' . $num++, 'PERIODOS', null, null, $ColorCabecera2);
            $excelUtil->agregarTitulo('G' . $num, '1', null, null, $ColorCabecera2);
            $excelUtil->agregarTitulo('H' . $num, '2', null, null, $ColorCabecera2);
            $excelUtil->agregarTitulo('I' . $num, '3', null, null, $ColorCabecera2);
            $excelUtil->agregarTitulo('J' . $num, '4', null, null, $ColorCabecera2);
            $excelUtil->agregarTitulo('K' . $num, 'FINAL', null, null, $ColorCabecera2);

            $indice++;
        }
        // // // /////////////////////////////RESULTADOS DE CONSULTA////////////////////////////////////////////////

        $mat_id = "";
        $car_materia = "";
        $cont = 0;
        $numInicial = 15;
        $num = 15;
        foreach ($consulta as $registro) {
            if ($mat_id != $registro["mat_id"] . '-' . $registro["car_materia"]) {
                $indice = array_search($registro["car_materia"], $listaMaterias);
                if ($car_materia != $registro["car_materia"]) {
                    $car_materia = $registro["car_materia"];
                    $cont = 1;
                    $num = 15;
                } else {
                    $cont++;
                    $num++;
                }
                $excelUtil->sheet[$indice]->getStyle('A' . $num . ':K' . $num)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN); //Todos los bordes sencillos 
                $excelUtil->indice = $indice;

                $excelUtil->agregarTexto('A' . $num, $cont);
                $excelUtil->ajustarTexto('A' . $num);
                $excelUtil->agregarTexto('B' . $num, $registro["ogen_nombre"]);
                if (!empty($registro["mat_documento"])) {
                    $excelUtil->agregarTexto('C' . $num, $registro["mat_documento"]);
                    $excelUtil->ajustarTexto('C' . $num);
                }
                if (!empty($registro["mat_primer_apellido"])) {
                    // 
                    $excelUtil->agregarTexto('D' . $num, $registro["mat_primer_apellido"] . ' ' . $registro["mat_segundo_apellido"] . ' ' . $registro["mat_nombres"]);
                    // $excelUtil->sheet[0]->mergeCells('D'.$num.':E'.$num); // Combinar celdas 
                }
                if (!empty($registro["gra_nombre"])) {
                    $excelUtil->agregarTexto('E' . $num, $registro["gra_nombre"] . '-' . $registro["gru_nombre"]);
                    $excelUtil->ajustarTexto('E' . $num);
                }
                $mat_id = $registro["mat_id"] . '-' . $registro["car_materia"];
            }
            $estiloNota = Boletin::obtenerDatosTipoDeNotasCargadas($listaTipoNotas, $registro["bol_nota"]);
            $color = "";
            $texto = "";
            switch ($estiloNota['notip_nombre']) {
                case $listaTipoNotas[3]["notip_nombre"]:
                    $color = $ColorSuperior;
                    $texto = $ColorTextoOscuro;
                    break;
                case $listaTipoNotas[2]["notip_nombre"]:
                    $color = $ColorAlto;
                    $texto = $ColorTextoOscuro;
                    break;
                case $listaTipoNotas[1]["notip_nombre"]:
                    $color = $ColorBasico;
                    $texto = $ColorTextoOscuro;
                    break;
                case $listaTipoNotas[0]["notip_nombre"]:
                    $color = $ColorBajo;
                    $texto = $ColorTextoClaro;
                    break;
            }
            if ($registro["bol_periodo"] == '1') {
                $excelUtil->agregarTexto('G' . $num, $registro["bol_nota"], $texto, $color);
                $excelUtil->centrarTexto('G' . $num);
                continue;
            }
            if ($registro["bol_periodo"] == '2') {
                $excelUtil->agregarTexto('H' . $num, $registro["bol_nota"], $texto, $color);
                $excelUtil->centrarTexto('H' . $num);
                continue;
            }
            if ($registro["bol_periodo"] == '3') {
                $excelUtil->agregarTexto('I' . $num, $registro["bol_nota"], $texto, $color);
                $excelUtil->centrarTexto('I' . $num);
                continue;
            }
            if ($registro["bol_periodo"] == '4') {
                $excelUtil->agregarTexto('J' . $num, $registro["bol_nota"], $texto, $color);
                $excelUtil->centrarTexto('J' . $num);
                continue;
            }
            if ($registro["bol_periodo"] == '5') {
                $excelUtil->agregarTexto('K' . $num, $registro["bol_nota"], $texto, $color);
                $excelUtil->centrarTexto('K' . $num);
                continue;
            }
        }
        // 
        // "Informe_periodico_".date("d/m/Y")."-SINTIA.xls"
        $excelUtil->descargarExcel("Informe_periodico_" . date("d/m/Y") . "-SINTIA.xlsx");
    }

    exit;
} catch (Exception $e) {
    echo "Excepción catpurada: " . $e->getMessage();
    exit();
}
