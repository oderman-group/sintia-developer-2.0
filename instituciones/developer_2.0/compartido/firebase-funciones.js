function arrayJSON(id, contenido, fecha, institucion, usuario, tipoUsuario, nombreUsuario){
	var data = {
		id,
		contenido,
		fecha,
		institucion,
		usuario,
		tipoUsuario,
		nombreUsuario
	};
	return data;
}

function insertarFirebase(){
	var id = uuid.v1();
	var contenidoInput = document.getElementById("contenidoSugerencia");
	var contenido = contenidoInput.value;
	var fecha = new Date().getTime();
	var institucion = document.getElementById("institucionSug").value;
	var usuario = document.getElementById("usuarioSug").value;
	var tipoUsuario = document.getElementById("tipoUsuarioSug").value;
	var nombreUsuario = document.getElementById("usuarioNombreSug").value;


	var arrayData = arrayJSON(id, contenido, fecha, institucion, usuario, tipoUsuario, nombreUsuario);
	
	var sugerencias = database.ref("sugerencias/"+id);
	
	sugerencias.set(arrayData)
		.then(function() {
            console.log('dato almacenado correctamente');
			contenidoInput.value="";
     })
		.catch(function(error) {
		console.log('detectado un error', error);
     });
}

function notifica(){
	$.toast({
		heading: 'Nueva sugerencia',  
		text: 'Se ha agregado una nueva sugerencia a la lista.',
		position: 'top-right',
		loaderBg:'#ff6849',
		icon: 'success',
		hideAfter: 3000, 
		stack: 6
	});
}

localStorage.setItem("intoDesktop", 1);
function listarTareas(){
	
	var sugerencias = database.ref("sugerencias/").orderByChild("fecha");
	document.getElementById("listarDatos").innerHTML="";
	sugerencias.on("child_added",function(data){
		var taskValue = data.val();

		var date = new Date(taskValue.fecha); 
        var fechaSug = date.toString(); 

		document.getElementById("listarDatos").innerHTML+=`
		<li class="diactive-feed">
			<div class="feed-user-img">
				<img src="../../../config-general/assets/img/std/std2.jpg" class="img-radius "
					alt="User-Profile-Image">
			</div>
			<h6>
				<span class="label label-sm label-primary">${taskValue.nombreUsuario}</span> 
				${taskValue.contenido} 
				<small class="text-muted">${fechaSug} </small>
			</h6>
		</li>
		`;

		if(localStorage.getItem("showSug") == 1 && localStorage.getItem("intoDesktop") == 2){
			notifica();
		}else{
			localStorage.setItem("showSug", 1);
			localStorage.setItem("intoDesktop", 2);
		}
		

		
	});
}