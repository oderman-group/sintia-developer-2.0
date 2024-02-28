<?php
$input = json_decode(file_get_contents("php://input"), true);
require_once($_SERVER['DOCUMENT_ROOT'] . "/app-sintia/config-general/constantes.php");
include($_SERVER['DOCUMENT_ROOT'] . "/app-sintia/main-app/compartido/sintia-funciones.php");
if (!empty($_POST["texto"])) {
    // Tu clave API de OpenAI
    $apiKey = KEY_CHATGPT;
    $llave = [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apiKey,
        'OpenAI-Beta: assistants=v1',
    ];
    $asistenteId = 'asst_Cj4vKc0xaNA6C9k5QO9GGSPr';
    $threadId = 'thread_dgkI4TkGHNr9IRdz0IhtyBod'; // Creado previamente
    // CREAMOS ASISTENTE SINTIA
    // $ch = curl_init();
    // curl_setopt($ch, CURLOPT_URL, 'https://api.openai.com/v1/assistants');
    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    // curl_setopt($ch, CURLOPT_HTTPHEADER, [
    //     'Content-Type: application/json',
    //     'Authorization: Bearer ' . $apiKey,
    //     'OpenAI-Beta: assistants=v1',
    // ]);
    // // Crear el cuerpo de la solicitud
    // $data = [
    //     'model' => 'gpt-3.5-turbo-0125',
    //     'name' => 'SintIA',
    //     'instructions' => 'eres un asistente de un software educativo, cunado te preguntes respondes como si fueras un profesor'
    // ];
    // curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    // $response = curl_exec($ch); // Ejecutar la solicitud y obtener la respuesta           
    // curl_close($ch); // Cerrar la conexión cURL
    // CREAMOS EL HILO
    // $ch = curl_init();
    // curl_setopt($ch, CURLOPT_URL, 'https://api.openai.com/v1/threads');
    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    // curl_setopt($ch, CURLOPT_HTTPHEADER, [
    //     'Content-Type: application/json',
    //     'Authorization: Bearer ' . $apiKey,
    //     'OpenAI-Beta: assistants=v1',
    // ]);
    // curl_setopt($ch, CURLOPT_POSTFIELDS,'');
    // $response = curl_exec($ch); // Ejecutar la solicitud y obtener la respuesta           
    // curl_close($ch); // Cerrar la conexión cURL

    // CREAMOS EL MENSAJE
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.openai.com/v1/threads/' . $threadId . '/messages');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_HTTPHEADER, $llave);
    // // Crear el cuerpo de la solicitud
    $data = [
        'role' => 'user',
        'content' => $_POST["texto"]
    ];
    $jsonData = json_encode($data);
    curl_setopt($ch, CURLOPT_POSTFIELDS,  $jsonData);
    $response = curl_exec($ch); // Ejecutar la solicitud y obtener la respuesta           
    curl_close($ch); // Cerrar la conexión cURL
    $result = json_decode($response, true); // Extraer y mostrar solo la última respuesta del asistente
    if (isset($result['id'])) {
        $idMensage = $result['id'];
        // SE LO ASOCIAMOS A NUESTRO ASISTENTE
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.openai.com/v1/threads/' . $threadId . '/runs');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $llave);
        // // Crear el cuerpo de la solicitud
        $data = [
            'assistant_id' => $asistenteId
        ];
        $jsonData = json_encode($data);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        $response = curl_exec($ch); // Ejecutar la solicitud y obtener la respuesta           
        curl_close($ch); // Cerrar la conexión cURL
        $result = json_decode($response, true); // Extraer y mostrar solo la última respuesta del asistente
        if (isset($result['id'])) {
            $idRuns = $result['id'];
            //LISTAMOS EL MENSAJES PARA ESTE HILO
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://api.openai.com/v1/threads/" . $threadId . "/messages");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_HTTPHEADER, $llave);
            sleep(3);
            $response = curl_exec($ch); // Ejecutar la solicitud y obtener la respuesta  
            $result = json_decode($response, true); // Extraer y mostrar solo la última respuesta del asistente         
            curl_close($ch); // Cerrar la conexión cURL
            if (isset($result['data'])) {
                // Recorrer el array asociativo con foreach
                $texto = '';
                foreach ($result['data'] as $clave => $valor) {
                    if ($valor['run_id'] == $idRuns) {
                        $texto = $valor['content'][0]['text']['value'];
                    }
                   
                }
                if (isset($texto)) {
                    echo "<p>Respuesta de OpenAI: </p>";
                    echo "<div style='background-color: #eee; padding: 10px; border-radius: 5px; margin-top: 10px;'>" . htmlspecialchars($texto) . "</div>";
                } else {
                    echo "<p>No se recibió una respuesta válida.</p>";
                }
            } else {
                echo "<p>No se recibió una respuesta válida.</p>";
            }
        }
    }
}
