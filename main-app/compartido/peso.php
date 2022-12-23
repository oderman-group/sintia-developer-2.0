<?php
$dir = ('../files/archivos');

 "Total : " . Fsize($dir);
function Fsize($dir)
{
    clearstatcache();
    $cont = 0;
    if (is_dir($dir)) {
        if ($gd = opendir($dir)) {
            while (($archivo = readdir($gd)) !== false) {
                if ($archivo != "." && $archivo != "..") {
                    if (is_dir($archivo)) {
                        $cont += Fsize($dir . "/" . $archivo);
                    } else {
                        $nombreArchivo= "archivo : " . $dir . "/" . $archivo . "&nbsp;&nbsp;" . filesize($dir . "/" . $archivo) . "<br />";
                        if (strpos($nombreArchivo, 'mobiliar_coalst')){
                            $cont += sprintf("%u", filesize($dir . "/" . $archivo));
                              $nombreArchivo;
                        }
                    }
                }
            }
            closedir($gd);
        }
    }
    $disponible=0.1;
    $gb= $cont/1073741824;
    "Carpeta: ".$dir."<br>";
    $porcentaje = ($gb/$disponible)*100;
    return "<div class='work-monitor work-progress'>
    <div class='states'>
        <div class='info'>
            <div class='desc pull-left'><b>".round($gb, 2.)." GB/</b></div>
            <div class='desc pull-left'><b>".round($disponible, 2.)." GB</b></div>
            <div class='percent pull-right'>".round($porcentaje, 2)."%</div>
        </div>
    
        <div class='progress progress-xs'>
            <div class='progress-bar progress-bar-".$colorGrafico." progress-bar-striped' role='progressbar' aria-valuenow='40' aria-valuemin='0' aria-valuemax='100' style='width: ".$porcentaje."%'>
                <span class='sr-only'>100% </span>
            </div>
        </div>
    </div>
    </div>";
}

?>

<div class="card">
<td> Uso Del Disco </td>
   <?=Fsize($dir)?>

</div>

