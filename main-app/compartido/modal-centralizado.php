<script type="application/javascript">
	async function abrirModal(titulo, url, data) {
		const contenido = document.getElementById('contenidoCentralizado');
		var gifCarga = document.getElementById("gifCarga");

		if (gifCarga) {
			document.getElementById("gifCarga").style.display = "block";
		} 
		
		contenido.innerHTML = "";
		resultado = await metodoFetchAsync(url, data, 'html', false);
		resultData = resultado["data"];
		contenido.innerHTML = resultado["data"];
		document.getElementById('tituloModal').textContent = titulo;
		if (gifCarga) {
			document.getElementById("gifCarga").style.display = "none";
		} 		
		$('#ModalCentralizado').modal('show');
	}
</script>
<div class="modal fade" id="ModalCentralizado" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
	<div class="modal-dialog" style="max-width: 1350px!important;">
		<div class="modal-content" style="border-radius: 20px;max-width: 1350px!important; ">

			<div class="modal-header panel-heading-purple">
				<h4 class="modal-title " id="tituloModal">TITULO MODAL</h4>
				<a href="#" data-dismiss="modal" class="btn btn-danger" aria-label="Close" id="boton-cerrar-compra-modulo"><i class="fa fa-window-close"></i></a>
			</div>

			<div class="modal-body">
				<div id="contenidoCentralizado"></div>
			</div>

		</div>
	</div>
</div>