<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/app-sintia/config-general/constantes.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Formulario para OpenAI GPT</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 40%;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        textarea {
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            resize: vertical;
        }

        button {
            padding: 10px;
            border: none;
            border-radius: 4px;
            background-color: #007bff;
            color: white;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Consulta a OpenAI GPT para generar un Audio</h2>
        <form action="" method="post">
            <textarea name="inputTexto" rows="4" placeholder="Escribe tu mensaje aquí..."><?php echo isset($_POST["inputTexto"]) ? $_POST["inputTexto"] : ''; ?></textarea>
            <button type="submit">Enviar a OpenAI</button>
        </form>
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["inputTexto"])) {
            // Tu clave API de OpenAI
            $apiKey = KEY_CHATGPT;

            // Crear el cuerpo de la solicitud
            $data = [
                'model' => 'tts-1-hd',
                'input' => $_POST["inputTexto"],
                'voice' => 'echo'
            ];

            // Inicializar cURL
            $ch = curl_init();

            // Configurar opciones de cURL
            curl_setopt($ch, CURLOPT_URL, "https://api.openai.com/v1/audio/speech");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $apiKey
            ]);

            // Ejecutar la solicitud y obtener la respuesta
            $response = curl_exec($ch);
            // Cerrar la conexión cURL
            curl_close($ch);
            $result = json_decode($response, true);

            // Manejar la respuesta
            if ($response === FALSE) {
                // Manejar el error
                echo "Error al realizar la solicitud a la API";
            } else {
                // Manejar la respuesta exitosa
                // Guardar el audio en un archivo
                file_put_contents('audio_generado.mp3', $response);
                // Redirigir a la página HTML que reproducirá el audio
                echo "<audio controls><source src='audio_generado.mp3' type='audio/mpeg'></audio>";
            }
        }
        ?>
    </div>
    
</body>

</html>