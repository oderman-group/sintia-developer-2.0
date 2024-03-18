<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/app-sintia/config-general/constantes.php");
$input = json_decode(file_get_contents("php://input"), true);
$metodo = $input['metodo'];
$apiKey = KEY_CHATGPT;
if (!empty($metodo)) {
    $response = array();
    $valor = $input['valor'];
    // Inicializar cURL
    $ch = curl_init();
    // Configurar opciones de cURL
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apiKey
    ]);
    // Configurar opciones de cURL de acuerdo al metodo solicitado
    if ($metodo == TEXT_TO_IMAGE) {        
        $data = [
            'model' => 'dall-e-3',
            'prompt' => $valor,
            'n' => 1,
            'quality' => 'standard',
            'size' => '1024x1024'
        ];       
        curl_setopt($ch, CURLOPT_URL, "https://api.openai.com/v1/images/generations");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));        
    } else if ($metodo == TEXT_TO_TEXT) {
        $data = [
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                [
                    "role" => "user",
                    "content" => $valor
                ]
            ]
        ];        
        curl_setopt($ch, CURLOPT_URL, "https://api.openai.com/v1/chat/completions");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }

    // Ejecutar la solicitud y obtener la respuesta
    $result = curl_exec($ch);
    // Cerrar la conexión cURL
    curl_close($ch);
    // Convertir la respuesta JSON en un array de PHP
    $response = json_decode($result, true);

    // se arma el resultado dependiento el tipo de metodo solicitado
    if ($metodo == TEXT_TO_IMAGE) {
        // $response["ok"] = true;
        // $response["url"] ="https://media.istockphoto.com/id/1386341272/es/foto/tecnolog%C3%ADa-abstracta-moderna-del-desarrollador-de-pantalla-de-c%C3%B3digo-de-programaci%C3%B3n.jpg?s=612x612&w=0&k=20&c=moCWq03zIDyRb2PsBGTvQ7MO1lnEegXZLBjDI3CwLes=";
        if (isset($response['data'][0])) {
            $response["ok"] = true;
            $response["url"] = $response['data'][0]["url"];
        } else {
            $response["ok"] = false;
            $response["url"] = "<p>No se recibió una respuesta válida.</p>";
        }
    } else if ($metodo == TEXT_TO_TEXT) {
        if (isset($response['choices'][0]['message']['content'])) {
            $response["ok"] = true;
            $response["valor"] = $response['choices'][0]['message']['content'];
        } else {
            $response["ok"] = false;
            $response["valor"] = "<p>No se recibió una respuesta válida.</p>";
        }
    }


    echo json_encode($response);
}
