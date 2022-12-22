<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php
$dir = ('C:/Users/marvi/Downloads/Prueva');
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
                        if (strpos($nombreArchivo, 'Documentos')){
                            $cont += sprintf("%u", filesize($dir . "/" . $archivo));
                              $nombreArchivo;
                        }
                    }
                }
            }
            closedir($gd);
        }
    }
    $gb= $cont/1073741824;
    "Carpeta: ".$dir."<br>";
    return $cont." Byte - ".round($gb, 3)." GB";
}
$archivo;
$peso="archivo";
$gb="Total";

echo '<table border=1>
 <tr>
 <td> Uso Del Disco: </td>
      </tr>
      <tr>
           <td> '.Fsize($dir).' </td>
       </tr>
 </table>';
?>
</body>
</html>
