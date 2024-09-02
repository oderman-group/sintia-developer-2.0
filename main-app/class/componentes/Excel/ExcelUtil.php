<?php
include_once("session-compartida.php");
require_once(ROOT_PATH . "/vendor/autoload.php");

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Reader\Html;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExcelUtil extends Spreadsheet
{
    public $sheet = [];
    public $indice = 0;
    public $spreadsheet;
    public const IMG_DEFAULT = ROOT_PATH . "/main-app/files/images/logo/sintia-logo-2023.png";

    public function __construct($nombreLibro)
    {
        // Inicializar la instancia de Spreadsheet   
        parent::__construct();
        $this->spreadsheet = $this;
        // // Obtener la hoja activa
        $this->sheet[$this->indice] = $this->spreadsheet->getActiveSheet();
        // // Establecer el título de la hoja
        $this->sheet[$this->indice]->setTitle($nombreLibro);
    }

    public function agregarHoja($nombreHoja)
    {
        $this->indice++;
        // Crear una nueva hoja
        $nuevaHoja = $this->spreadsheet->createSheet();
        $this->sheet[$this->indice] =  $nuevaHoja;        
        $this->sheet[$this->indice] ->setTitle($nombreHoja);
    }

    public function ajustarTexto($celda)
    {
        if (self::validarFormatoCelda($celda)) {
            // Ajustar el texto a la celda
            $this->sheet[$this->indice]->getStyle($celda)->getAlignment()->setWrapText(true);
            self::centrarTexto($celda);
        }
    }

    public function agregarTitulo($celda, $titulo, $size = 12, $colorTexto = '000000',$Colorfondo = 'ffffff')
    {
        $this->sheet[$this->indice]->setCellValue($celda, $titulo);                                                            // asignamos los valores
        self::textoNegrita($celda);
        self::textoTamano($celda, $size);
        self::centrarTexto($celda);
        self::establecerColorTexto($celda,$colorTexto);
        self::establecerColorFondo($celda,$Colorfondo);
        
    }
    public function agregarTexto($celda, $titulo,$colorTexto = '000000',$Colorfondo = 'ffffff')
    {
        $this->sheet[$this->indice]->setCellValue($celda, $titulo);                                                           // asignamos los valores
        $this->sheet[$this->indice]->getColumnDimension(substr($celda, 0, 1))->setAutoSize(true);                            //auto tamaño de la columna J 
        self::establecerColorTexto($celda,$colorTexto);
        self::establecerColorFondo($celda,$Colorfondo);
    }
    public function textoTamano($celda, $size = 12)
    {
        $this->sheet[$this->indice]->getStyle($celda)->getFont()->setSize($size);                                               // cambiamos el tamño de la fuente
    }

    public function textoNegrita($celda)
    {
        $this->sheet[$this->indice]->getStyle($celda)->getFont()->setBold(true);                                                 // colocamos  la celda con style negrita
    }

    public function centrarTexto($rangoCeldas)
    {
        $this->sheet[$this->indice]->getStyle($rangoCeldas)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);       // alinieamos al centro de forma horizontal
        $this->sheet[$this->indice]->getStyle($rangoCeldas)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);           // alinieamos al centro de forma Vertical
    }

    public function establecerBordes($rangoCeldas)
    {
        $this->sheet[$this->indice]->getStyle($rangoCeldas)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
    }

    public function establecerColorFondo($rangoCeldas, $colorHex)
    {
        $this->sheet[$this->indice]->getStyle($rangoCeldas)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB($colorHex);
    }
    public function establecerColorTexto($rangoCeldas, $colorHex)
    {
        $this->sheet[$this->indice]->getStyle($rangoCeldas)->getFont()->getColor()->setARGB($colorHex);
    }

    public function establecerColorBordes($rangoCeldas, $colorHex)
    {
        $this->sheet[$this->indice]->getStyle($rangoCeldas)->getBorders()->getAllBorders()->getColor()->setARGB($colorHex);
    }


    public function agregarImagenLogo($celda = 'A1', $rutaImagen = self::IMG_DEFAULT, $offsetX = 0, $offsetY = 0)
    {
        $drawing = new Drawing();
        $drawing->setPath($rutaImagen);                       // Aquí coloca la ruta de tu imagen
        $drawing->setCoordinates($celda);                     // Celda donde se colocará la imagen
        $drawing->setOffsetX($offsetX);                       // Desplazamiento horizontal
        $drawing->setOffsetY($offsetY);                       // Desplazamiento vertical
        $drawing->setWidthAndHeight(100, 100);                // Ajusta el tamaño de la imagen
        $drawing->setWorksheet($this->sheet[$this->indice]);
    }

    function validarFormatoCelda($celda)
    {
        // Expresión regular para validar el formato de celda
       $patron = '/^[A-Z][1-9][0-9]*$/';
        // Verifica si el parámetro cumple con el patrón
        return preg_match($patron, $celda) === 1;
    }

    function validarRangosCelda($rangos)
    {
        // Expresión regular para validar los rangos de una celda
       $patron = '/^[A-Z]+[1-9]\d*:[A-Z]+[1-9]\d*$/';
        // Verifica si el parámetro cumple con el patrón
        return preg_match($patron, $rangos) === 1;
    }


    public function descargarExcel($nombreArchivo = "archivo.xlsx")
    {

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=\"$nombreArchivo\"");
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($this);
        $writer->save('php://output');
    }
}
