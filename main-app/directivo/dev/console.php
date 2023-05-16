<?php
$file = fopen($archivo, "r");
fseek($file, 0, SEEK_END);
$size = 4096; // Tamaño del bloque de lectura
$buffer = '';
$lines = [];

while (count($lines) <= 20) {
    $pos = ftell($file);
    if ($pos <= 0) {
        break;
    }
    fseek($file, max($pos - $size, 0), SEEK_SET);
    $buffer = fread($file, $size) . $buffer;
    fseek($file, max($pos - $size * 2, 0), SEEK_SET);

    $lines = explode("\n", $buffer);
    $lines = array_filter($lines);
    $lines = array_slice($lines, -20);
}