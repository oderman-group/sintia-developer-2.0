<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/app-sintia/config-general/constantes.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Formulario para OpenAI GPT</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
        <h2>Consulta a OpenAI GPT</h2>
        <form action="" method="post">
            <textarea id="idTexto" name="inputTexto" rows="4" placeholder="Escribe tu mensaje aquí..."><?php echo isset($_POST["inputTexto"]) ? $_POST["inputTexto"] : ''; ?></textarea>
            <button type="button" onclick="enviar2()">Enviar a OpenAI</button>
            <div id="divResponse"> </div>

        </form>
        <script>
            function enviar2() {
                var formData = new FormData();
                btnIniciar = document.getElementById('idTexto');
                divResponse = document.getElementById('divResponse');
                formData.append("texto", btnIniciar.value);
                $.ajax({
                    type: "POST",
                    url: "thread.php",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        divResponse.innerHTML = data;
                    }
                });
            }
        </script>
    </div>
</body>

</html>