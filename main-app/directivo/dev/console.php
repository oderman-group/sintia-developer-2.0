<?php
$fp = fopen('../error_log', "r");
while (!feof($fp)){
    $linea = fgets($fp);
    echo "<p>".$linea."</p>";
}
fclose($fp);