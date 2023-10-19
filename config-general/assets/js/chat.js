
// funcion para mostrar las conversacion
function mostrarChat(datos) {
	// cerrmaos la sala que este abierta
	// socket.emit("leave", "sala_chat_" + chat_remite_usuario + "_" + chat_destino_usuario);
	// socket.emit("leave", "listar_chat_" + chat_remite_usuario + "_" + chat_destino_usuario);
	var liId = datos.id;
	var splitid = liId.split("_");
	var id = splitid[0];
	// console.log("id--->" + id);
	$("#contenedorChat").empty().hide();
	if (id !== '') {
		$.ajax({
			type: "POST",
			url: "ajax-chat.php",
			data: {
				id: id
			},
			success: function (response) {
				$("#contenedorChat").show();

				$.each(response, function (index, item) {
					estado = item.datosUsuarios['uss_estado'] == "1" ? "online" : "offline";
					var html =
						'<div class="div-superior">' +
						'<div class="row">' +
						'<div class="col-lg-6">' +
						'<a href="javascript:void(0);" data-toggle="modal" >' +
						'<img src="' + item.fotoPerfil + '" height="70px" alt="avatar">' +
						'</a>' +
						'<div class="chat-about">' +
						'<h6 class="m-b-0">' + item.nombre + '</h6>' +
						'<div class="status"> <i class="fa fa-circle ' + estado + '"></i> ' + estado + ' </div>' +
						'</div>' +
						'</div>' +
						'</div>' +
						'</div>' +
						'<div class="chat-history div-medio scrollable" id="chatHistory">' +
						'<ul class="m-b-0" id="contenido_chat">' +

						'</ul>' +
						'</div>' +
						'		<div id="div-flotante" class="div-flotante">' +
						'             <div id="contenedorImagen"></div>' +
						'             <audio controls id="audioReprodutor" style="display: none;"></audio>' +
						'             <button type="button" class="btn btn-outline-secondary" onclick="limpiar()">' +
						'                 <i class="fa-solid fa-trash"></i>' +
						'             </button>' +
						'             <button type="button" class="btn btn-outline-secondary" onClick="enviarMensaje()">' +
						'                 <i class="fa fa-send"></i>' +
						'             </button>' +
						'         </div>' +
						'         <div class="div-inferior">' +
						'             <div class="row">' +
						'					<div class="col-12">' +
						'						<div class="input-group">' +
						'							<textarea class="form-control" id="imputMensaje" class="form-control" onkeydown="ejecutarEnter(event)" placeholder="Escriba su mensaje aqui..." rows="2" aria-label="With textarea"></textarea>' +
						'							<div class="input-group-prepend">' +
						'								<button class="input-group-text btn btn-primary " type="button" onClick="enviarMensaje()">' +
						'									<i class="fa fa-send"></i>' +
						'								</button>' +
						'							</div>' +
						'						</div>' +
						'					</div>' +
						'					<div class="col-12">' +
						'                     <div class="btn-group me-2" role="group" aria-label="First group">' +
						'                         <button type="button" onclick="cargarFile(&apos;' + _chatTipoImagen + '&apos;,cargarImagen)" class="btn btn-sm  btn-outline-secondary"><i class="fa fa-image"></i></button>' +
						'                         <button type="button" onclick="cargarFile(&apos;' + _chatTipoDocumento + '&apos;,cargarArchivo)" class="btn btn-outline-secondary"><i class="fa-solid fa-paperclip"></i></button>' +
						'                         <button type="button" onclick="iniciarGrabacion()" class="btn btn-outline-secondary"><i id="iconGrabar" class="fa-solid fa-microphone"></i></button>' +
						'                         <button type="button" onclick="detenerGrabacion()" class="btn btn-outline-secondary" id="btnDetener" style="display: none;"><i class="fa-solid fa-stop"></i></button>' +
						'                     </div>' +
						'					</div>' +

						'             </div>' +
						'          </div>' +
						'             <input type="file" id="cargarImagen" name="imagen" style="display: none;" accept="image/*">' +
						'             <input type="file" id="cargarArchivo" name="archivo" style="display: none;">' +
						'             <input type="file" id="cargarAudio" name="audio" style="display: none;" accept="audio/*"></input>';


					$("#contenedorChat").append(html);
					var chatElement = document.getElementById("chatHistory");
					var contenido_chat = document.getElementById("contenido_chat");
					audioReprodutor = document.getElementById('audioReprodutor');
					btnDetener = document.getElementById('btnDetener');
					iconGrabar = document.getElementById('iconGrabar');
					imputmensaje = document.getElementById("imputMensaje");
					chat_destino_usuario = item.datosUsuarios["uss_id"];
					destino_foto_url_uss = item.fotoPerfil;
					destino_nombre_uss = item.nombre;

					listarChat(chat_remite_usuario, chat_destino_usuario);


					divNombre = document.getElementById("nombre_" + chat_destino_usuario);
					spanNotificacion = document.getElementById("notificacion_" + chat_destino_usuario);

					divNombre.style.fontWeight = "400";
					spanNotificacion.className = "";
					spanNotificacion.innerHTML = "";
				});
			}
		});
	}
};

socket.on("sala_" + chat_remite_usuario, (data) => {
	// console.log(data);
	miUsuario = (data["chat_remite_usuario"] == chat_remite_usuario);
	actualizarChat(miUsuario, data);
	if (!miUsuario) {
		if (chat_destino_usuario + "" != data["chat_remite_usuario"] + "") {
			divNombre = document.getElementById("nombre_" + uss_id);
			divNombre.style.fontWeight = "700";
			spanNotificacion = document.getElementById("notificacion_" + uss_id);
			spanNotificacion.className = "badge headerBadgeColor2";
			spanNotificacion.innerHTML = data["cantidad"];
		}
	} else {
		if (chat_destino_usuario == data["chat_destino_usuario"]) {
			contenido_chat.innerHTML += htmlEmisor(data["chat_id"], data["chat_mensaje"], verificarFecha(Date.parse(data["chat_fecha_registro"])), data["chat_tipo"], data["chat_url_file"]);
			limpiar();
			chatElement.scrollTop = chatElement.scrollHeight;
			imputMensaje.focus();
		}
	}

});


function actualizarChat(miUsuario, data) {
	if (miUsuario) { //preguntamos si es el mismo usuario 
		uss_id = data["chat_destino_usuario"];
		nombre_uss_notifica = data["destino_nombre_uss"];
		foto_url_uss_notifica = data["destino_foto_uss"];
	} else {
		uss_id = data["chat_remite_usuario"];
		nombre_uss_notifica = data["remite_nombre_uss"];
		foto_url_uss_notifica = data["remite_foto_uss"];
	}
	listaUsuarios = document.getElementById('listaChat');
	liUsuario = document.getElementById(uss_id);
	// si existe se elimina de la lista
	if (liUsuario !== null) {
		listaUsuarios.removeChild(liUsuario);
	}
	if (data["chat_mensaje"].length > 20) {
		mensaje = extraerCaracteres(data["chat_mensaje"], 0, 20) + "...";
	} else {
		mensaje = data["chat_mensaje"];
	}

	// Crea un nuevo elemento li
	const elementoHTML = notificacionUsuario(uss_id, nombre_uss_notifica, foto_url_uss_notifica, mensaje);
	const nuevoElemento = document.createElement('li');
	nuevoElemento.id = uss_id;
	nuevoElemento.className = "clearfix";
	nuevoElemento.innerHTML = elementoHTML;
	nuevoElemento.onclick = function () {
		mostrarChat(nuevoElemento);
	};
	// Agrega el nuevo elemento li al principio de la lista
	listaUsuarios.insertBefore(nuevoElemento, listaUsuarios.firstChild);
}

function pintarUsuarios(response) {
	// Limpiar los resultados anteriores
	$("#listaUsuario").empty();
	listaUsuarios = document.getElementById('listaUsuario');
	response.forEach(elemento => {
		// console.log(elemento);
		uss_id = elemento["datosUsuarios"]["uss_id"];
		uss_estado = elemento["datosUsuarios"]["uss_estado"] == "1" ? "online" : "offline";
		nombre = elemento["nombre"];
		fotoPerfil = elemento["fotoPerfil"];
		const elementoHTML = pintarUsuario(uss_id, nombre, fotoPerfil, uss_estado);
		const nuevoElemento = document.createElement('li');
		nuevoElemento.id = uss_id + "_uss";
		nuevoElemento.className = "clearfix";
		nuevoElemento.innerHTML = elementoHTML;
		nuevoElemento.onclick = function () {
			mostrarChat(nuevoElemento);
		};
		listaUsuarios.appendChild(nuevoElemento);
	});

}

function mostrarListarUsuarios(valor) {
	switch (valor) {
		case 1:
			btnChat.classList.remove("btn-secondary");
			btnAll.classList.remove("btn-success");

			btnChat.classList.add("btn-primary");
			btnAll.classList.add("btn-secondary");
			plist.style.display = "none";
			chatList.style.display = "block";
			break;
		case 2:
			btnAll.classList.remove("btn-secondary");
			btnChat.classList.remove("btn-primary");

			btnChat.classList.add("btn-secondary");
			btnAll.classList.add("btn-success");
			plist.style.display = "block";
			chatList.style.display = "none";
			break;
	}
}

function ejecutarEnter(event) {
	if (event.key === "Enter") {
		enviarMensaje();
	}
};

function notificacionUsuario(id, nombreCompleto, fotoPerfil, estado) {
	Html = "";
	// '<li class="clearfix" onclick="mostrarChat(this)" id="'+id+'"   >' +
	Html = '<img src="' + fotoPerfil + '" alt="avatar" />' +
		'<div class="about">' +
		'<div class="name" id="nombre_' + id + '"  >' + nombreCompleto + '</div>' +
		'<div class="status" > <i class="fa fa-circle online"></i> ' + estado + ' <span id="notificacion_' + id + '"> </div>' +
		'</div>';
	return Html;
};
function pintarUsuario(id, nombreCompleto, fotoPerfil, estado) {
	Html = "";
	// '<li class="clearfix" onclick="mostrarChat(this)" id="'+id+'"   >' +
	Html = '<img src="' + fotoPerfil + '" alt="avatar" />' +
		'<div class="about">' +
		'<div class="name" id="nombre_' + id + '"  >' + nombreCompleto + '</div>' +
		'<div class="status" > <i class="fa fa-circle ' + estado + '"></i> ' + estado + '</div>' +
		'</div>';
	return Html;
};

function htmlEmisor(id, mensaje, hora, tipo = "1", url = "", visto = 1) {
	liHtml = "";
	imageHtml = "";
	vistoHtml = "";
	if (visto == 0) {
		vistoHtml = '<img src="../files/iconos/check1.png">'
	}
	switch (tipo) {
		case _chatTipoImagen:
			imageHtml = '<img class="cursor-mano" src="../files/chat/imagen/' + url + '" onclick="mostarModal(this.src)" alt="avatar" style="height: 400px;"  data-toggle="modal" data-target="#modalImagen">';
			mensaje = '<div class="cols-12">' + mensaje + '</div>';
			break;
		case _chatTipoDocumento:
			imageHtml = '<a href="../files/chat/documento/' + url + '" download="' + url + '">' +
				'<img src="../files/iconos/file.png" style="height: 30px;" >' +
				' descargar documento</a>'
			mensaje = '<div class="cols-12">' + mensaje + '</div>';
			break;
		case _chatTipoAudio:
			imageHtml = '<audio controls src="../files/chat/audio/' + url + '" ></audio>';
			mensaje = '<div class="cols-12">' + mensaje + '</div>';
			break;
		case _chatTipoMensaje:
			break;
	}
	liHtml = '<li class="clearfix" >' +
		'<div class="message-data text-right">' +
		'<span class="message-data-time">' + hora + '</span>' +
		'</div>' +
		'<div class="message my-message float-right" id="div_visto_' + id + '">' +
		imageHtml +
		vistoHtml +
		'<img src="../files/iconos/check1.png">' +
		mensaje +
		'</div>' +
		'</li>';

	return liHtml;
};

function htmlDestino(id, mensaje, hora, imagenUrl, tipo = "1", url = "") {
	liHtml = "";
	imageHtml = "";
	switch (tipo) {
		case _chatTipoImagen:
			imageHtml = '<img class="cursor-mano" src="../files/chat/imagen/' + url + '" alt="avatar" onclick="mostarModal(this.src)" style="height: 400px;"  data-toggle="modal" data-target="#modalImagen" >';
			mensaje = '<div class="cols-12">' + mensaje + '</div>';
			break;
		case _chatTipoDocumento:
			imageHtml = '<a href="../files/chat/documento/' + url + '" download="' + url + '">' +
				'<img src="../files/iconos/file.png" style="height: 30px;" >' +
				' descargar documento</a>'
			mensaje = '<div class="cols-12">' + mensaje + '</div>';
			break;
		case _chatTipoAudio:
			imageHtml = '<audio controls src="../files/chat/audio/' + url + '" ></audio>';
			mensaje = '<div class="cols-12">' + mensaje + '</div>';
			break;
		case _chatTipoMensaje:
			break;
	}
	imageHtml = '<li class="clearfix" id="chat_li_' + id + '">' +
		'<div class="message-data">' +
		'<img src="' + imagenUrl + '" alt="avatar">' +
		'<span class="message-data-time">' + hora + '</span>' +
		'</div>' +
		'<div class="message other-message"> ' +
		imageHtml +
		mensaje +
		'</div>' +
		'</li>';
	return imageHtml;
};

function listarChat(uss_remite, uss_detino) {
	chatElement = document.getElementById("chatHistory");
	var formData = new FormData();
	formData.append("chat_remite_usuario", uss_remite);
	formData.append("chat_destino_usuario", uss_detino);
	$.ajax({
		type: "GET",
		url: "ajax-chat-lista.php",
		data: {
			chat_remite_usuario: uss_remite,
			chat_destino_usuario: uss_detino
		},
		success: function (response) {
			dato = response[0]["data"];
			dato.forEach(elemento => {
				if (chat_remite_usuario == elemento.chat_remite_usuario) {
					fechaCompleta = verificarFecha(elemento.chat_fecha_registro);
					contenido_chat.innerHTML += htmlEmisor(elemento.chat_id, elemento.chat_mensaje, fechaCompleta, elemento.chat_tipo + '', elemento.chat_url_file, elemento.chat_visto);
				} else {
					fechaCompleta = verificarFecha(elemento.chat_fecha_registro);
					contenido_chat.innerHTML += htmlDestino(elemento.chat_id, elemento.chat_mensaje, fechaCompleta, destino_foto_url_uss, elemento.chat_tipo + '', elemento.chat_url_file);
					if (elemento.chat_visto == 1) {
						socket.emit("ver_mensaje", {
							salaChat: "sala_chat_" + chat_destino_usuario + "_" + chat_remite_usuario,
							chat_id: elemento.chat_id
						});
					}
				}
				chatElement.scrollTop = chatElement.scrollHeight;
			});
			var sala_chat = "sala_chat_" + chat_remite_usuario + "_" + chat_destino_usuario;
			console.log(salasCreadas);
			if (salasCreadas.indexOf(sala_chat) === -1) {
				salasCreadas.push(sala_chat);
				console.log("-----" + sala_chat);
				socket.on(sala_chat, (data) => {
					actualizarChat(false, data["body"]);
					remite = data["body"]["chat_remite_usuario"];
					if (remite == chat_destino_usuario) {
						chatElement = document.getElementById("chatHistory");
						bajar = false;
						if (chatElement.scrollTop + chatElement.clientHeight >= chatElement.scrollHeight) {
							bajar = true;
						}
						mensaje = data["body"]["chat_mensaje"];
						fecha = data["body"]["chat_fecha_registro"];
						tipo = data["body"]["chat_tipo"];
						url = data["body"]["chat_url_file"];
						fechaCompleta = verificarFecha(fecha);
						contenido_chat.innerHTML += htmlDestino(data["body"]["chat_id"], mensaje, fechaCompleta, destino_foto_url_uss, tipo + '', url);
						if (bajar) {
							chatElement.scrollTop = chatElement.scrollHeight;
						}
						socket.emit("ver_mensaje", {
							salaChat: "sala_chat_" + chat_destino_usuario + "_" + chat_remite_usuario,
							chat_id: data["body"]["chat_id"]
						});
					};
				});
				socket.on("poner_visto_" + sala_chat, (data) => {
					divVisto = document.getElementById("div_visto_" + data);
					const nuevocheck = document.createElement('img');
					nuevocheck.src = "../files/iconos/check1.png";
					divVisto.insertBefore(nuevocheck, divVisto.lastChild);
				});

			}
			console.log("Emite actualizar_notificaciones");
			socket.emit("actualizar_notificaciones", {
				miSala: "sala_" + chat_remite_usuario,
				chat_destino_usuario: chat_remite_usuario
			});
		}
	});

};

function enviarMensaje() {
	mensaje = imputMensaje.value;
	chatElement = document.getElementById("chatHistory");
	switch (tipoMensaje) {
		case _chatTipoImagen:
			enviarArchivo(tipoMensaje, "cargarImagen");
			break;
		case _chatTipoDocumento:
			enviarArchivo(tipoMensaje, "cargarArchivo");
			break;
		case _chatTipoAudio:
			enviarArchivo(tipoMensaje, "cargarAudio");
			break;
		case _chatTipoMensaje:
			if (mensaje.trim() === "" || mensaje === null || typeof mensaje === 'undefined') {
				Swal.fire(
					'',
					'Ingrese un mensaje',
					'info'
				)
			} else {
				enviarArchivo(tipoMensaje, "");
			};

			break;

	}

};

function cargarFile(tipo, idImput) {
	limpiar();
	const inputImagen = idImput;
	tipoMensaje = tipo;
	inputImagen.click();
	inputImagen.addEventListener('change', mostrarImagen);
	mostrarImagen(tipo);
	// imputMensaje.focus();
};

function enviarArchivo(tipo, idImput) {
	var formData = new FormData();
	var inputFile = document.getElementById(idImput);
	mensaje = imputMensaje.value;
	formData.append("tipo", tipo);
	if (tipoMensaje != _chatTipoMensaje) {
		formData.append(tipo, (tipoMensaje == _chatTipoAudio) ? audioBlob : inputFile.files[0]);
		$.ajax({
			type: "POST",
			url: "../compartido/chat-guardar-imagen-ajax.php",
			data: formData,
			contentType: false,
			processData: false,
			success: function (data) {
				// console.log(data);
				try {
					JSON.parse(data);
					jsonValido = true; // La cadena es un JSON válido
				} catch (error) {
					jsonValido = false; // La cadena no es un JSON válido
				}
				if (jsonValido) {
					datos = JSON.parse(data);
					estado = datos["status"];
					if (estado === "OK") {
						nombreFile = datos["nombre"];
						socket.emit("enviar_mensaje_chat", {
							remite_nombre_uss: remite_nombre_uss,       // nombre de quien remite
							remite_foto_uss: remite_foto_url_uss,       // foto de quien remite 
							miSala: "sala_" + chat_remite_usuario,  // sala origen
							chat_tipo: tipoMensaje,					// tipo del mensaje enviad 1): texto 2): imagen 3): documento 4): audio
							chat_url_file: nombreFile,				// nombre del archivo que se envia 
							destino_foto_uss: destino_foto_url_uss,				// foto aquien se le envia el mensaje
							destino_nombre_uss: destino_nombre_uss,					// nombre a quien se envia el mensaje
							chat_fecha_registro: new Date(),
							chat_remite_usuario: chat_remite_usuario,
							chat_destino_usuario: chat_destino_usuario,
							sala: "sala_" + chat_destino_usuario,									   // sala destino
							salaChat: "sala_chat_" + chat_destino_usuario + "_" + chat_remite_usuario,// sala chat si esta abierta
							chat_mensaje: mensaje													 // mensaje a enviar
						});

					} else {
						limpiar();
						Swal.fire({
							title: 'Ojo!',
							text: data,
							icon: 'error'
						})
					}
				} else {
					limpiar();
					Swal.fire({
						title: 'Ojo!',
						text: data,
						icon: 'error'
					})
				}
			}
		});
	} else {
		socket.emit("enviar_mensaje_chat", {
			remite_nombre_uss: remite_nombre_uss,       								// nombre de quien remite
			remite_foto_uss: remite_foto_url_uss,       								// foto de quien remite 
			miSala: "sala_" + chat_remite_usuario,  									// sala origen
			chat_tipo: tipoMensaje,														// tipo del mensaje enviad 1):texto 2):imagen 3):documento 4): audio
			destino_foto_uss: destino_foto_url_uss,											// foto aquien se le envia el mensaje
			destino_nombre_uss: destino_nombre_uss,	 									// nombre a quien se envia el mensaje
			chat_fecha_registro: new Date(),
			chat_remite_usuario: chat_remite_usuario,
			chat_destino_usuario: chat_destino_usuario,
			sala: "sala_" + chat_destino_usuario,									   // sala destino
			salaChat: "sala_chat_" + chat_destino_usuario + "_" + chat_remite_usuario,// sala chat si esta abierta
			chat_mensaje: mensaje
		});


	}

};

function mostrarImagen(tipo) {
	const divFlotante = document.getElementById("div-flotante");
	const contenedorImagen = document.getElementById('contenedorImagen');
	const inputImagen = document.getElementById('cargarImagen');
	const inputarchivo = document.getElementById('cargarArchivo');
	const imagenSeleccionada = inputImagen.files[0];
	const archivoSeleccionado = inputarchivo.files[0];

	if (tipo == _chatTipoImagen) {
		inputarchivo.value = '';
	} else if (tipo == _chatTipoDocumento) {
		inputImagen.value = '';
	}

	contenedorImagen.innerHTML = "";
	const elemento = document.getElementById('imputMensaje'); 
	const rect = elemento.getBoundingClientRect();
	const x = rect.left + window.scrollX; // Posición X absoluta
	const y = rect.top + window.scrollY; // Posición Y absoluta
	console.log(`Posición X: ${x}, Posición Y: ${y}`);
	if (imagenSeleccionada) {
		const imagenURL = URL.createObjectURL(imagenSeleccionada);
		// Crear un elemento de imagen y asignar la URL
		const imagen = document.createElement('img');
		imagen.src = imagenURL;
		imagen.classList.add("img-thumbnail");
		imagen.style.height = "300px";
		// Agregar la imagen al contenedor
		contenedorImagen.innerHTML = '';
		contenedorImagen.appendChild(imagen);
		divFlotante.style.top = (y-340)+"px";
		divFlotante.style.lef = x+"px";
		divFlotante.style.display = "block";

	} else if (archivoSeleccionado) {
		// Crear un elemento de imagen y asignar la URL

		const imagen = document.createElement('img');
		const nombre = document.createElement('p');
		imagen.src = "../files/iconos/file.png";
		imagen.style.height = "50px";
		imagen.style.width = "50px";
		nombre.textContent = " " + archivoSeleccionado.name
		contenedorImagen.innerHTML = '';
		contenedorImagen.appendChild(imagen);
		contenedorImagen.appendChild(nombre);
		divFlotante.style.top = "73vh";
		divFlotante.style.display = "block";

	} else {
		contenedorImagen.innerHTML = 'No se ha seleccionado una imagen.';
	}
};

function iniciarGrabacion() {
	limpiar();
	audioChunks = [];
	tipoMensaje = _chatTipoAudio;
	mediaRecorder.start();
	console.log('Grabación iniciada.');
	btnDetener.style.display = 'block';
	iconGrabar.classList.remove("fa-microphone");
	iconGrabar.classList.add("fa-record-vinyl", "fa-beat-fade");
	// Establecemos un temporizador para detener la grabación después de 60 segundos.
	tiempoDeGrabacion = setTimeout(function () {
		detenerGrabacion(); // Simula hacer clic en el botón "Detener Grabación".
	}, 69000);
};

function detenerGrabacion() {
	mediaRecorder.stop();
	divFlotante.style.display = "block";
	divFlotante.style.top = "77vh";
	console.log('Grabación detenida.');
	iconGrabar.classList.remove("fa-record-vinyl", "fa-beat-fade");
	iconGrabar.classList.add("fa-microphone");
	// imputMensaje.focus();
};

function limpiar() {
	tipoMensaje = _chatTipoMensaje;
	imputMensaje.value = "";
	inputImagen = document.getElementById('cargarImagen');
	inputArchivo = document.getElementById('cargarArchivo');
	inputAudio = document.getElementById('cargarAudio');
	divFlotante = document.getElementById("div-flotante");
	inputArchivo.value = '';
	inputImagen.value = '';
	inputAudio.value = '';
	contenedorImagen.innerHTML = '';
	audioReprodutor.src = "";
	audioReprodutor.style.display = 'none';
	btnDetener.style.display = 'none';
	divFlotante.style.display = 'none';
};

function obtenerDiaDeLaSemana(fecha) {
	const diasDeLaSemana = ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"];
	const fechaIngresada = new Date(fecha);
	const numeroDeDia = fechaIngresada.getDay();
	const nombreDelDia = diasDeLaSemana[numeroDeDia];
	return nombreDelDia;
}

function validarFachasIguales(fechaInicial, fechaFinal) {
	if (
		fechaInicial.getDate() === fechaFinal.getDate() &&
		fechaInicial.getMonth() === fechaFinal.getMonth() &&
		fechaInicial.getFullYear() === fechaFinal.getFullYear()
	) {
		return true;
	} else {
		return false;
	}
}

function verificarFecha(fecha) {
	fechaActual = new Date(); // Obtener la fecha actual
	fechaIngresada = new Date(fecha); // Convertir la fecha ingresada en un objeto Date
	fechahora = fechaIngresada.toLocaleTimeString('en-US');
	const [month, day, year] = [
		fechaIngresada.getMonth(),
		fechaIngresada.getDate(),
		fechaIngresada.getFullYear(),
	];

	// Verificar si es hoy
	if (validarFachasIguales(fechaIngresada, fechaActual)) {
		return fechahora;
	}

	// Calcular la fecha de ayer
	const ayer = new Date(fechaActual);
	ayer.setDate(ayer.getDate() - 1);
	// Verificar si es ayer
	if (validarFachasIguales(fechaIngresada, ayer)) {
		return fechahora + ',Ayer';
	}
	// Calcular la fecha de la semana pasada
	const semanaPasada = new Date(fechaActual);
	semanaPasada.setDate(semanaPasada.getDate() - 7);
	// Verificar si es la semana pasada
	if (fechaIngresada > semanaPasada && fechaIngresada < ayer) {
		return fechahora + ' ,' + obtenerDiaDeLaSemana(fecha);
	}

	return "(" + day + "/" + month + "/" + year + ")";
}
// Función para mostrar la imagen seleccionada en el modal
function mostarModal(src) {
	$('#imagenModal').attr('src', src);
}
// validar imagen
function validarImagen(url, callback) {
	const img = new Image();
	img.onload = function () {
		// La imagen se ha cargado con éxito
		callback(true);
	};
	img.onerror = function () {
		// La imagen no se ha cargado
		callback(false);
	};
	img.src = url; // Establecer la URL de la imagen
	// Asegúrate de que la URL de la imagen sea accesible
	// Esto es importante, ya que si la URL no es válida o no existe, el manejador de errores se activará inmediatamente
}

function extraerCaracteres(cadena, inicio, longitud) {
	return cadena.slice(inicio, inicio + longitud);
}

function buscarUsuario(valor) {
	// console.log(valor.length);
	if (valor.length > 3) {
		mostrarListarUsuarios(2);
		$.ajax({
			type: "POST",
			url: "ajax-buscador.php",
			data: {
				search: valor
			},
			success: function (response) {
				if (response.length > 0) {
					pintarUsuarios(response);
				}
			}
		});
	} else if (valor.length == 0) {
		$.ajax({
			type: "POST",
			url: "ajax-chat-usuarios.php",
			data: {
				search: valor
			},
			success: function (response) {
				if (response.length > 0) {
					pintarUsuarios(response);
				}
			}
		});
	}
}



