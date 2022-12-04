<?php include("session.php");?>
<?php include("../estudiante/verificar-usuario.php");?>
<?php $idPaginaInterna = 16;?>
<?php include("../compartido/historial-acciones-guardar.php");?>
<?php include("verificar-carga.php");?>
<?php include("../compartido/head.php");?>
<script src="../../../config-general/assets/plugins/chart-js/Chart.bundle.js"></script>
</head>
<!-- END HEAD -->
<?php include("../compartido/body.php");?>
	
    <div class="page-wrapper">
        <?php include("../compartido/encabezado.php");?>
		
        <?php include("../compartido/panel-color.php");?>
        <!-- start page container -->
        <div class="page-container">
 			<?php include("../compartido/menu.php");?>
			
			<?php include("../compartido/evaluaciones-ver-contenido.php");?>
			
        	<?php include("../compartido/footer.php");?>    
		</div>
	</div>	
        <!-- start js include path -->
        
        <script src="../../../config-general/assets/plugins/popper/popper.js" ></script>
        <script src="../../../config-general/assets/plugins/jquery-blockui/jquery.blockui.min.js" ></script>
		<script src="../../../config-general/assets/plugins/jquery-slimscroll/jquery.slimscroll.js"></script>
        <!-- bootstrap -->
        <script src="../../../config-general/assets/plugins/bootstrap/js/bootstrap.min.js" ></script>
        <script src="../../../config-general/assets/plugins/bootstrap-switch/js/bootstrap-switch.min.js" ></script>
        <!-- Common js-->
		<script src="../../../config-general/assets/js/app.js" ></script>
		<!-- notifications -->
		<script src="../../../config-general/assets/plugins/jquery-toast/dist/jquery.toast.min.js" ></script>
		<script src="../../../config-general/assets/plugins/jquery-toast/dist/toast.js" ></script>
        
        <script src="../../../config-general/assets/js/layout.js" ></script>
		<script src="../../../config-general/assets/js/theme-color.js" ></script>
		<!-- Material -->
		<script src="../../../config-general/assets/plugins/material/material.min.js"></script>
		<script src="../../../config-general/assets/js/pages/material-select/getmdl-select.js" ></script>
		<script  src="../../../config-general/assets/plugins/material-datetimepicker/moment-with-locales.min.js"></script>
		<script  src="../../../config-general/assets/plugins/material-datetimepicker/bootstrap-material-datetimepicker.js"></script>
		<script  src="../../../config-general/assets/plugins/material-datetimepicker/datetimepicker.js"></script>
		<!-- end js include path -->
		
		
</body>

</html>