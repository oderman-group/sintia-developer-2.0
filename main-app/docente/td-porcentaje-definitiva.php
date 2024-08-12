<?php
if ($definitiva < $config[5] && $definitiva!="") $colorDef = $config[6]; 
elseif ($definitiva >= $config[5]) $colorDef = $config[7]; 
else $colorDef = "black";

$definitivaFinal = Utilidades::setFinalZero($definitiva);
$atributosA      = 'style="text-decoration:underline; color:'.$colorDef.';"';

if ($config['conf_forma_mostrar_notas'] == CUALITATIVA) {
    $atributosA='tabindex="0" role="button" data-toggle="popover" data-trigger="hover" title="Nota Cuantitativa: '.$definitiva.'" data-content="<b>Nota Cuantitativa:</b><br>'.$definitiva.'" data-html="true" data-placement="top" style="border-bottom: 1px dotted #000; color:'.$colorDef.';"';

    $estiloNota      = Boletin::obtenerDatosTipoDeNotas($config['conf_notas_categoria'], $definitiva);
    $definitivaFinal = !empty($estiloNota['notip_nombre']) ? $estiloNota['notip_nombre'] : "";
}
?>

<td style="text-align:center;"><?=$porcentajeActual;?><p>&nbsp;</p></td>

<td 
    style="
        color:<?php 
            if($definitiva < $config[5] && $definitiva!="") echo $config[6]; 
            elseif($definitiva >= $config[5]) echo $config[7]; 
            else echo "black";?>; 
        text-align:center; 
        font-weight:bold;
    "
>
    <a 
        id="definitiva_<?=$resultado['mat_id'];?>" 
        href="calificaciones-estudiante.php?usrEstud=<?=base64_encode($resultado['mat_id_usuario']);?>&periodo=<?=base64_encode($periodoConsultaActual);?>&carga=<?=base64_encode($cargaConsultaActual);?>" 
        <?=$atributosA;?>
    >
        <?php echo $definitivaFinal;?>
    </a>
    <p>&nbsp;</p>
</td>