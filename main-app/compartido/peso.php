<?php

try{
    $pesoInstituciones=mysqli_query($conexion, "SELECT plns_espacio_gb FROM $baseDatosServicios.instituciones 
    INNER JOIN $baseDatosServicios.planes_sintia  
    ON plns_id=ins_id_plan
    WHERE ins_id='".$config['conf_id_institucion']."'");
}catch(Exception $e){
    echo 'Caught exception: ',  $e->getMessage(), "\n";
    exit();
}

 $peso=mysqli_fetch_array($pesoInstituciones, MYSQLI_BOTH);

$direccionArchivo = ('../files/archivos');

 "Total : " . Fsize($direccionArchivo);
 function Fsize($direccionArchivo)
{
    
    clearstatcache();
    $contadorByte = 0;
    if (is_dir($direccionArchivo)) {
        if ($gd = opendir($direccionArchivo)) {
            while (($archivo = readdir($gd)) !== false) {
                if ($archivo != "." && $archivo != "..") {
                    if (is_dir($archivo)) {
                        $contadorByte += Fsize($direccionArchivo . "/" . $archivo);
                    } else {
                        $nombreArchivo= "archivo : " . $direccionArchivo . "/" . $archivo . "&nbsp;&nbsp;" . filesize($direccionArchivo . "/" . $archivo) . "<br />";
                        if (strpos($nombreArchivo, $_SESSION["inst"])){
                            $contadorByte += sprintf("%u", filesize($direccionArchivo . "/" . $archivo));
                              $nombreArchivo;
                        }
                    }
                }
            }
            closedir($gd);
        }
    }
    $gb= $contadorByte/1073741824;
    "Carpeta: ".$direccionArchivo."<br>";
    global $peso;

    

    $porcentaje = ($gb/$peso[0])*100;

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
<?php sleep(20) ?>
<td> Uso Del Disco </td>
   <?=Fsize($direccionArchivo)?>

</div>

