<?php
require_once("Servicios.php");
$Servicio = new Servicios;

class CargaServicios
{
    public static function cantidadCursos($idCurso = 1)
    {
        return Servicios::getSql("SELECT COUNT(*) AS cargas_curso FROM academico_cargas WHERE car_curso=" . $idCurso);
    }
}
