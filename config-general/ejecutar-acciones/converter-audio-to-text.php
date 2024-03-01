<?php
$input = json_decode(file_get_contents("php://input"), true);
require_once($_SERVER['DOCUMENT_ROOT'] . "/app-sintia/config-general/constantes.php");
include($_SERVER['DOCUMENT_ROOT'] . "/app-sintia/main-app/compartido/sintia-funciones.php");
if (!empty($_FILES["audio"])) {
    // Tu clave API de OpenAI
    $apiKey = KEY_CHATGPT;
    $archivoSubido = new Archivos;
    // Leer el archivo MP3 como datos binarios
    $explode = explode(".", $_FILES["audio"]['name']);
    $extension = end($explode);
    $nombre_archivo = "audio_prueaba." . $extension;
    move_uploaded_file($_FILES["audio"]['tmp_name'],  $nombre_archivo);
    
    // Generated by curl-to-PHP: 
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://api.openai.com/v1/audio/transcriptions');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    $post = array(
        'file' => new CURLFile($nombre_archivo),
        'timestamp_granularities[]' => 'segment',
        'model' => 'whisper-1',
        'response_format' => 'verbose_json',
    );
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

    $headers = array();
    $headers[] = 'Authorization: Bearer ' . $apiKey;
    $headers[] = 'Content-Type: multipart/form-data';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    // Ejecutar la solicitud y obtener la respuesta
    $response = curl_exec($ch);
    unlink($nombre_archivo);
    // Cerrar la conexión cURL
    curl_close($ch);
    // Convertir la respuesta JSON en un array de PHP
    $result = json_decode($response, true);

    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }
    curl_close($ch);
    // Extraer y mostrar solo la última respuesta del asistente
    if (isset($result['text'])) {
        echo "<p>Respuesta de OpenAI:</p>";
        echo "<div style='background-color: #eee; padding: 10px; border-radius: 5px; margin-top: 10px;'>" . htmlspecialchars($result['text']) . "</div>";
    } else {
        echo "<p>No se recibió una respuesta válida.</p>";
    }
}
