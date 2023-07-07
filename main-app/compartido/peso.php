<?php

try{
    $pesoInstituciones=mysqli_query($conexion, "SELECT plns_espacio_gb FROM $baseDatosServicios.instituciones 
    INNER JOIN $baseDatosServicios.planes_sintia  
    ON plns_id=ins_id_plan
    WHERE ins_id='".$config['conf_id_institucion']."' AND ins_enviroment='".ENVIROMENT."'");
}catch(Exception $e){
    echo 'Caught exception: ',  $e->getMessage(), "\n";
    exit();
}
$peso=mysqli_fetch_array($pesoInstituciones, MYSQLI_BOTH);


 function calcularFile($dir)
{   clearstatcache();
    $cont = 0;
    if (is_dir($dir)) {
        if ($gd = opendir($dir)) {
            while (($archivo = readdir($gd)) !== false) {
                if ($archivo != "." && $archivo != "..") {
                    if (is_dir($archivo)) {
                        $cont += calcularFile($dir . "/" . $archivo);
                    } else {
                        // se valida que dentro de la direcion se encuentren archivos con el nombre de la institucion
                        if (strpos("/".$archivo,$_SESSION["inst"])){ 
                        $cont += sprintf("%u", filesize($dir . "/" . $archivo));
                        }
                    }
                }
            }
            closedir($gd);
        }
    }
    
    return $cont;
}
 function crearContenido()
{
    $contadorByte = 0;
    $contadorByte = calcularFile('../files/archivos');
    $contadorByte +=calcularFile('../files/clases');
    $contadorByte +=calcularFile('../files/evaluaciones');
    $contadorByte +=calcularFile('../files/firmas');
    $contadorByte +=calcularFile('../files/fotos');
    $contadorByte +=calcularFile('../files/pclase');
    $contadorByte +=calcularFile('../files/publicaciones');
    $contadorByte +=calcularFile('../files/tareas');
    $contadorByte +=calcularFile('../files/tareas-entregadas');
    
    $gb= $contadorByte/1073741824;
    global $peso;
    if(!empty($peso[0])){
        $porcentaje = ($gb/$peso[0])*100;
    }
    
    if($porcentaje<=50){$colorGrafico='info';}
    elseif($porcentaje>50 and $porcentaje<=80){$colorGrafico='warning';}
    else{$colorGrafico='danger';}
    return "<div class='work-monitor work-progress'>
    <div class='states'>
        <div class='info'>
            <div class='desc pull-left'><b>".round($gb, 2.)." GB/</b></div>
            <div class='desc pull-left'><b>".round($peso[0], 3.)." GB</b></div>
            <div class='percent pull-right'>".round($porcentaje, 3.)."%</div>
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
<div class="card" style="margin-left:5px; margin-right:5px; padding:5px;">
<td> Uso Del Disco </td>
   <?=crearContenido()?>
</div>

