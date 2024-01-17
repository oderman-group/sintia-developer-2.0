<?php
include("session.php");
$idPaginaInterna = 'DT0272';

if(!Modulos::validarSubRol([$idPaginaInterna])){
	echo '<script type="text/javascript">window.location.href="../directivo/page-info.php?idmsg=301";</script>';
	exit();
}
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");
require_once(ROOT_PATH."/main-app/class/UsuariosPadre.php");
require_once(ROOT_PATH."/main-app/class/Usuarios.php");
require_once(ROOT_PATH."/main-app/class/Movimientos.php");

$id = "";
if (!empty($_GET["id"])) {
    $id = base64_decode($_GET["id"]);
}
$resultado = Movimientos::traerDatosCotizacion($conexion, $config, $id);

// Crear un objeto DateTime a partir de la cadena de fecha y hora
$fechaBD = new DateTime($resultado['quote_date']);
$fechaExpedicion = $fechaBD->format('d/m/Y');

$fechaConvertir = DateTime::createFromFormat('d/m/Y', $fechaExpedicion);
$fechaConvertir->add(new DateInterval('P30D'));
$fechaVencimiento = $fechaConvertir->format('d/m/Y');
?>
<!doctype html>
<html class="no-js" lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta name="description" content="Plataforma Educativa SINTIA | Para Colegios y Universidades" />
        <meta name="author" content="ODERMAN" />
        <title>COTIZACIÓN</title>
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
                border-left: 1px solid #a8a8a8;
                border-right: 1px solid #a8a8a8;
            }

            .table_items tbody {
                border-bottom: 1px solid #a8a8a8;
            }

            .table_items tfoot td {
                border: none;
            }

            .table_datos {
                border-collapse: collapse;
            }

            .borde_superior_izquierdo {
                border-top-left-radius: 10px !important; /* Ajusta el radio según tus preferencias */
            }

            .borde_superior_derecho {
                border-top-right-radius: 10px !important; /* Ajusta el radio según tus preferencias */
            }

            .borde_inferior_izquierdo {
                border-bottom-left-radius: 10px !important; /* Ajusta el radio según tus preferencias */
            }

            .borde_inferior_derecho {
                border-bottom-right-radius: 10px !important; /* Ajusta el radio según tus preferencias */
            }

            .altura-especifica {
                height: 50px; /* Ajusta la altura según tus necesidades */
            }
        </style>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
    </head>
    <body style="font-family:Arial; font-size: 13px;">
        <div style="margin: 15px 0;">
            <table width="100%">
                <tr>
                    <td align="left" width="30%">
                        <img src="../files/images/logo/<?=$informacion_inst["info_logo"]?>" width="100%"><br><br>
                    </td>
                    <td align="center" width="40%">
                        <span style="font-weight:bold; margin: 0"><?=strtoupper($informacion_inst["info_nombre"])?></span><br>
                        NIT: <?=$informacion_inst["info_nit"]?><br>
                        <?=$informacion_inst["info_telefono"]?><br>
                        <?=$informacion_inst["info_direccion"]?>
                    </td>
                    <td align="left" width="30%">
                        <h3 style="margin: 0px; padding-left: 30px;">Cotización</h3>
                        <h2 style="margin: 0px; font-weight:bold; padding-left: 30px;"><b>No. <?=$resultado["id"]?></b></h2>
                    </td>
                </tr>
            </table>
            <table class="table_datos" style="font-size: 15px; margin-bottom: 5px;" width="100%">
                <tr>
                    <td align="right" width="20%" class="borde_superior_izquierdo" style="background-color: #a8a8a8; font-weight:bold;">SEÑOR(ES) </td>
                    <td align="left" colspan="3" style="padding-left: 10px; border: 1px solid #a8a8a8;"><?=UsuariosPadre::nombreCompletoDelUsuario($resultado)?></td>
                    <td align="center" width="20%" class="borde_superior_derecho" style="background-color: #a8a8a8; font-weight:bold;">FECHA DE EXPEDICIÓN</td>
                </tr>
                <tr>
                    <td align="right" width="20%" style="background-color: #a8a8a8; font-weight:bold;">DIRECCIÓN</td>
                    <td align="left" colspan="3" style="padding-left: 10px; border: 1px solid #a8a8a8;"><?=$resultado['uss_direccion']?></td>
                    <td align="center" style="border: 1px solid #a8a8a8;"><?=$fechaExpedicion?></td>
                </tr>
                <tr>
                    <td align="right" width="20%" style="background-color: #a8a8a8; font-weight:bold;">CIUDAD</td>
                    <td align="left" colspan="3" style="padding-left: 10px; border: 1px solid #a8a8a8;"><?=$resultado['ciu_nombre']?></td>
                    <td align="center" width="20%" style="background-color: #a8a8a8; font-weight:bold;">FECHA DE VENCIMIENTO</td>
                </tr>
                <tr>
                    <td align="right" width="20%" class="borde_inferior_izquierdo" style="background-color: #a8a8a8; font-weight:bold;">TELÉFONO</td>
                    <td align="left" style="padding-left: 10px; border: 1px solid #a8a8a8;"><?php echo $resultado['uss_celular']; if (!empty($resultado['uss_celular']) && !empty($resultado['uss_telefono'])) { echo "-"; } echo $resultado['uss_telefono']; ?></td>
                    <td align="right" width="20%" style="background-color: #a8a8a8; font-weight:bold;">CC/NIT</td>
                    <td align="left" style="padding-left: 10px; border: 1px solid #a8a8a8;"><?=$resultado['uss_documento']?></td>
                    <td align="center" style="border: 1px solid #a8a8a8;"><?=$fechaVencimiento?></td>
                </tr>
            </table>
            <table class="table_items" width="100%" style="font-size: 15px;">
                <thead style="background-color: #a8a8a8;" align="center">
                    <tr>
                        <th>Item</th>
                        <th>Precio</th>
                        <th  width="10%">Cant.</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                                                                
                        $itemsConsulta = Movimientos::listarItemsTransaction($conexion, $config, $id, TIPO_COTIZACION);

                        $subtotal=0;
                        $numItems=mysqli_num_rows($itemsConsulta);
                        if($numItems>0){
                            while ($fila = mysqli_fetch_array($itemsConsulta, MYSQLI_BOTH)) {
                    ?>
                        <tr>
                            <td><?=$fila['name'];?><?php if ( !empty($fila['description']) ){ echo "(".$fila['description'].")"; } ?></td>
                            <td align="right">$<?=number_format($fila['priceTransaction'], 0, ",", ".")?></td>
                            <td align="center"><?=$fila['cantity'];?></td>
                            <td align="right">$<?=number_format($fila['subtotal'], 0, ",", ".")?></td>
                        </tr>
                    <?php 
                            $subtotal += $fila['subtotal'];
                            }
                        }
                        $total= $subtotal;
                        $height = 500;
                        if ($numItems>=10){
                            $height = 200;
                        }                        
                    ?>
                    <tr>
                        <td><div style="height: <?=$height?>px;"></div></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td align="right" colspan="3" style="font-weight:bold;">Subtotal</td>
                        <td align="right"><?="$".number_format($subtotal, 0, ",", ".");?></td>
                    </tr>
                    <tr style="font-weight:bold;">
                        <td colspan="2"></td>
                        <td align="right" style="background-color: #a8a8a8;">Total</td>
                        <td align="right" style="background-color: #a8a8a8;"><?="$".number_format($total, 0, ",", ".");?></td>
                    </tr>
                </tfoot>
            </table> 
            <p>&nbsp;</p>
            <!--******FIRMAS******-->
            <table width="50%" cellspacing="0" cellpadding="0" rules="none" border="0" style="text-align:center; font-size:10px;">
                <tr>
                    <td align="center">
                        <?php
                            $responsable = Usuarios::obtenerDatosUsuario($resultado['responsible_user']);
                            if(!empty($responsable["uss_firma"])){
                                echo '<img src="../files/fotos/'.$responsable["uss_firma"].'" width="100"><br>';
                            }else{
                                echo '<p>&nbsp;</p>
                                    <p>&nbsp;</p>
                                    <p>&nbsp;</p>';
                            }
                        ?>
                        <p style="height:0px;"></p>__________________________________________<br>
                        ELABORADO POR
                    </td>
                </tr>
            </table>
        </div>
        <?php include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php"); ?>
        <script type="application/javascript">
            print();
        </script>
    </body>
</html>