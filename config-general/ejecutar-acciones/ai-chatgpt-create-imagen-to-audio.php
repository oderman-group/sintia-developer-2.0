<!DOCTYPE html>
<html lang="en">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<head>
    <meta charset="UTF-8">
    <title>Formulario para OpenAI GPT</title>
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
        <h2>Consulta a OpenAI GPT parar generar una imagen desde un audio</h2>
        <form action="" method="post" enctype="multipart/form-data">
            <div>
                <button  type="button" id="btnIniciar" onclick="iniciarGrabacion()" >Iniciar Grabación</button>
                <button  type="button" id="btnDetener" onclick="detenerGrabacion()" >Detener Grabación</button>
            </div>
            <!-- Elemento de audio para reproducir la grabación -->
            <audio controls id="audioReprodutor"  ></audio>
            <input type="file" id="cargarAudio" name="audio" style="display: none;" accept="audio/*"></input>
            <button type="button" onclick="enviarAudio()">Enviar a OpenAI</button>
            <div id="divResponse"> </div>

        </form>
    </div>

    <script>
        audioReprodutor = document.getElementById('audioReprodutor');
        btnIniciar = document.getElementById('btnIniciar');
        btnDetener = document.getElementById('btnDetener');
        const constraints = {
            audio: true
        };

        navigator.mediaDevices.getUserMedia(constraints)
            .then(function(stream) {
                mediaRecorder = new MediaRecorder(stream);
                mediaRecorder.ondataavailable = function(e) {
                    if (e.data.size > 0) {
                        audioChunks.push(e.data);
                    }
                };

                mediaRecorder.onstop = function() {
                    audioBlob = new Blob(audioChunks, {
                        type: 'audio/wav'
                    });
                    document.getElementById('audioReprodutor').src = URL.createObjectURL(audioBlob);
                    const audioURL = URL.createObjectURL(audioBlob);
                    audioReprodutor.src = audioURL;
                    inputAudio = document.getElementById('cargarAudio');
                    inputAudio.src = audioURL
                };


            })
            .catch(function(err) {
                console.error('No se puede acceder al micrófono: ' + err);
            });

        function iniciarGrabacion() {
            limpiar();
            audioChunks = [];
            mediaRecorder.start();
            console.log('Grabación iniciada.');
            btnIniciar.disabled = true;
            btnIniciar.style.backgroundColor = "#cd1616";
            btnDetener.disabled = false;
            // Establecemos un temporizador para detener la grabación después de 60 segundos.
            tiempoDeGrabacion = setTimeout(function() {
                detenerGrabacion(); // Simula hacer clic en el botón "Detener Grabación".
            }, 69000);
        };

        function detenerGrabacion() {
            mediaRecorder.stop();
            console.log('Grabación detenida.');
            btnIniciar.disabled = false;
            btnIniciar.style.backgroundColor = "#007bff";
            btnDetener.disabled = true;
        };

        function limpiar() {
            inputAudio = document.getElementById('cargarAudio');
            inputAudio.value = '';
            audioReprodutor.src = "";
        };

        function enviarAudio() {
            var formData = new FormData();
            formData.append("audio", audioBlob, 'audio.mp3');
            $.ajax({
                type: "POST",
                url: "converter-audio-to-imagen.php",
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    divResponse.innerHTML = data;
                }
            });
        }
    </script>

</body>

</html>