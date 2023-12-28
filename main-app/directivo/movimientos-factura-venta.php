<?php
include("session.php");
$idPaginaInterna = 'DT0255';

if(!Modulos::validarSubRol([$idPaginaInterna])){
	echo '<script type="text/javascript">window.location.href="../directivo/page-info.php?idmsg=301";</script>';
	exit();
}
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");
require_once(ROOT_PATH."/main-app/class/UsuariosPadre.php");
require_once(ROOT_PATH."/main-app/class/Movimientos.php");

$id = "";
if (!empty($_GET["id"])) {
    $id = base64_decode($_GET["id"]);
}

try{
    $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_FINANCIERA.".finanzas_cuentas fcu
    INNER JOIN ".BD_GENERAL.".usuarios uss ON uss_id=fcu_usuario AND uss.institucion={$config['conf_id_institucion']} AND uss.year={$_SESSION["bd"]}
    LEFT JOIN ".BD_ADMIN.".localidad_ciudades ON ciu_id=uss_lugar_nacimiento
    LEFT JOIN ".BD_ADMIN.".localidad_departamentos ON dep_id=ciu_departamento
    WHERE fcu_id='".$id."' AND fcu.institucion={$config['conf_id_institucion']} AND fcu.year={$_SESSION["bd"]}");
} catch (Exception $e) {
    include("../compartido/error-catch-to-report.php");
}
$resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH);

$fecha = explode ("-", $resultado['fcu_fecha']);
$dia   = $fecha[2];  
$mes = $fecha[1];  
$year  = $fecha[0];
$fechaReplace = $dia.'/'.$mes.'/'.$year;
?>
<!doctype html>
<html class="no-js" lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta name="description" content="Plataforma Educativa SINTIA | Para Colegios y Universidades" />
        <meta name="author" content="ODERMAN" />
        <title>INVOICE</title>
        <meta name="tipo_contenido" content="text/html;" http-equiv="content-type" charset="utf-8">
        <!-- favicon -->
        <link rel="shortcut icon" href="../sintia-icono.png" />
        <style>
            #saltoPagina {
                PAGE-BREAK-AFTER: always;
            }

            .table_items {
                border-collapse: collapse;
            }

            .table_items th, .table_items td {
                border: 1px solid #000;
            }

            .table_items tfoot td {
                border: none;
            }
        </style>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
        <link rel="stylesheet" href="../../config-general/assets/css/fuentes-factura.css" />
    </head>
    <body class="ff1" style="font-size: 13px;">
        <div style="margin: 15px 0;">
            <table width="100%">
                <tr>
                    <td align="left" width="55%">
                        <img src="../files/images/logo/<?=$informacion_inst["info_logo"]?>" width="70%"><br><br>
                        <span style="font-weight:bold; margin: 0"><?=strtoupper($informacion_inst["info_nombre"])?></span><br>
                        <?=$informacion_inst["info_direccion"]?><br>
                        Tel: <?=$informacion_inst["info_telefono"]?><br><br>
                        <table width="50%">
                            <tr>
                                <td style="border: 1px solid #000; padding: 5px; width: 35%; background-color: #e3e3e3;">Facturar A:</td>
                            </tr>
                            <tr>
                                <td>
                                    <?=UsuariosPadre::nombreCompletoDelUsuario($resultado)?><br>
                                    <b>C.C/NIT:</b> <?=$resultado['uss_documento']?><br>
                                    <b>TEL:</b> <?=$resultado['uss_telefono']?><br>
                                    <?=$resultado['uss_direccion']?><br>
                                    <?php if (!empty($resultado['uss_lugar_nacimiento'])) { echo $resultado['ciu_nombre'].", ".$resultado['dep_nombre']; }?>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td align="right" width="45%" style="vertical-align: top;">
                        <h1 style="margin: 0px; font-size: 50px;">FACTURA</h1>
                        <h3 style="margin: 0px; font-size: 13px;"><b>Número de factura: <?=$resultado["fcu_id"]?></b></h3>
                        <h3 style="margin: 0px; font-size: 13px;">No responsable de IVA</h3>
                        <h3 style="margin: 0px; font-size: 13px;">Factura de venta original</h3>
                    </td>
                </tr>
            </table>
            <p>&nbsp;</p>
            <table style="font-size: 15px; margin-bottom: 5px; border: 1px solid #000; border-collapse: collapse;" width="40%" align="right">
                <tr>
                    <td align="center" width="50%" style="background-color: #e3e3e3;">FECHA </td>
                    <td align="left" width="50%"><?=$fechaReplace?></td>
                </tr>
            </table>
            <table class="table_items" width="100%" style="font-size: 15px;">
                <thead style="background-color: #e3e3e3;" align="center">
                    <tr>
                        <th>Item Cod.</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th  width="10%">Cant.</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                                                                
                        $itemsConsulta = Movimientos::listarItemsTransaction($conexion, $config, $id);

                        $subtotal=0;
                        $numItems=mysqli_num_rows($itemsConsulta);
                        if($numItems>0){
                            while ($fila = mysqli_fetch_array($itemsConsulta, MYSQLI_BOTH)) {
                    ?>
                        <tr>
                            <td><?=$fila['idtx'];?></td>
                            <td><?=$fila['name'];?><?php if ( !empty($fila['description']) ){ echo "(".$fila['description'].")"; } ?></td>
                            <td align="right">$<?=number_format($fila['priceTransaction'], 0, ",", ".")?></td>
                            <td align="right"><?=$fila['cantity'];?></td>
                            <td align="right">$<?=number_format($fila['subtotal'], 0, ",", ".")?></td>
                        </tr>
                    <?php 
                            $subtotal += $fila['subtotal'];
                            }
                        }
                        if(empty($resultado['fcu_valor'])){ $resultado['fcu_valor']=0; }
                        $total= $subtotal+$resultado['fcu_valor'];
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" rowspan="3">
                            <table>
                                <tr style="font-weight:bold;">
                                    <td style="border: 1px solid #000;">
                                        DETALLE: <?=$resultado['fcu_detalle']?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <?=$config['conf_pie_factura']?>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td align="right" colspan="2" style="font-weight:bold;">SUBTOTAL:</td>
                        <td align="right" style="background-color: #e3e3e3; border: 1px solid #000; font-weight:bold;"><?="$".number_format($subtotal, 0, ",", ".");?></td>
                    </tr>
                    <tr>
                        <td align="right" colspan="2" style="font-weight:bold;">VLR. ADICIONAL:</td>
                        <td align="right" style="background-color: #e3e3e3; border: 1px solid #000; font-weight:bold;"><?="$".number_format($resultado['fcu_valor'], 0, ",", ".");?></td>
                    </tr>
                    <tr style="font-weight:bold;">
                        <td align="right" colspan="2">TOTAL NETO:</td>
                        <td align="right" style="background-color: #e3e3e3; border: 1px solid #000; "><?="$".number_format($total, 0, ",", ".");?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <?php include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php"); ?>
        <script type="application/javascript">
            print();
        </script>
    </body>
</html>