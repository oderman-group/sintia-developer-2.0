/**
 *  Document   : theme-color.js
 *  Author     : redstar
 *  Description: Core script to handle the entire theme and core functions
 *
 **/

jQuery(document).ready(function() {
   jQuery(document).on("click",".sidebar-theme a",function() {
	   var sidebar_color = jQuery(this).attr('data-theme')+"-sidebar-color";
	   jQuery( "body" ).removeClass( "white-sidebar-color dark-sidebar-color blue-sidebar-color indigo-sidebar-color green-sidebar-color red-sidebar-color cyan-sidebar-color" );
	   jQuery( "body" ).addClass( sidebar_color );
	   
	   fetch('../compartido/guardar.php?get=3&temaSidebar='+sidebar_color, {
		method: 'GET'
		})
		.then(response => response.text()) // Convertir la respuesta a texto
		.then(data => {
			console.log(data);
			$.toast({

				heading: 'Proceso completado', 
				text: 'Hemos actualizado tu preferencia para los colores de la barra lateral.', 
				position: 'top-left',
				loaderBg:'#26c281', 
				icon: 'success', 
				hideAfter: 3000, 
				stack: 6

			});
		})
		.catch(error => {
			// Manejar errores
			console.error('Error:', error);
		});

   });
   jQuery(document).on("click",".logo-theme a",function() {
	   var logo_color = jQuery(this).attr('data-theme');
	   jQuery( "body" ).removeClass( "logo-white logo-dark logo-blue logo-indigo logo-red logo-cyan logo-green" );
	   jQuery( "body" ).addClass( logo_color );

	   fetch('../compartido/guardar.php?get=4&temaLogo='+logo_color, {
		method: 'GET'
		})
		.then(response => response.text()) // Convertir la respuesta a texto
		.then(data => {
			$.toast({

				heading: 'Proceso completado', 
				text: 'Hemos actualizado tu preferencia para los colores del encabezado del menú.', 
				position: 'top-left',
				loaderBg:'#26c281', 
				icon: 'success', 
				hideAfter: 3000, 
				stack: 6

			});
		})
		.catch(error => {
			// Manejar errores
			console.error('Error:', error);
		});
   });
   jQuery(document).on("click",".header-theme a",function() {
	   var header_color = jQuery(this).attr('data-theme');
	   jQuery( "body" ).removeClass( "header-white header-dark header-blue header-indigo header-red header-cyan header-green" );
	   jQuery( "body" ).addClass( header_color );

	   fetch('../compartido/guardar.php?get=2&temaHeader='+header_color, {
		method: 'GET'
		})
		.then(response => response.text()) // Convertir la respuesta a texto
		.then(data => {
			$.toast({

				heading: 'Proceso completado', 
				text: 'Hemos actualizado tu preferencia para los colores del encabezado del menú.', 
				position: 'top-left',
				loaderBg:'#26c281', 
				icon: 'success', 
				hideAfter: 3000, 
				stack: 6

			});
		})
		.catch(error => {
			// Manejar errores
			console.error('Error:', error);
		});
   });
});

