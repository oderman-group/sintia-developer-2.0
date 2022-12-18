<?php include("session.php");?>
<?php $idPaginaInterna = 'ES0037';?>
<?php include("../compartido/historial-acciones-guardar.php");?>
<?php include("../compartido/head.php");?>


  <link rel="stylesheet" type="text/css" href="../../librerias/recortar-foto/crop_style.css">
  <script src="../../config-general/assets/plugins/jquery/jquery.min.js" ></script>
  <script type="text/javascript" src="../../librerias/recortar-foto/jquery-ui.js"></script>

  <script type="text/javascript">

    $(function() {
      $( "#crop_div" ).draggable({ containment: "parent" });
    });
   
    function crop()
    {
      var posi = document.getElementById('crop_div');
      document.getElementById("top").value=posi.offsetTop;
      document.getElementById("left").value=posi.offsetLeft;
      document.getElementById("right").value=posi.offsetWidth;
      document.getElementById("bottom").value=posi.offsetHeight;
      return true;
    }

  </script>

</head>
<!-- END HEAD -->
<?php include("../compartido/body.php");?>

			<!-- start page content -->
            <div class="page-content-wrapper">
                <div class="page-content">
                    <div class="row">
                       <?php include("../compartido/perfil-recortar-foto-contenido.php");?>	
                    </div>
					
				</div>
           </div>

</body>

</html>