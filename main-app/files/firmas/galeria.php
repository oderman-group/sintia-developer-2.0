<?php include("head.php");?>
    <!-- Page Title -->
    <title>Galería | Jhon Oderman</title>
    
    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta name="keywords" content="Jhon Oderman" />
    <meta name="description" content="Jhon Oderman | Emprendedor y conferencista">
    
</head>
<body>
<?php include("google-analytics.php");?>
    <div id="page-wrapper">
        <?php include("menu.php");?>

        <section id="content">
            <div class="container">
                <div id="main">
                    <!--
                    <div class="gallery-filter box">
                        <a href="#" class="button btn-medium active" data-filter="filter-all">All</a>
                        <a href="#" class="button btn-medium" data-filter="filter-countries">Countries</a>
                        <a href="#" class="button btn-medium" data-filter="filter-adventure">Adventure</a>
                        <a href="#" class="button btn-medium" data-filter="filter-island">Island</a>
                        <a href="#" class="button btn-medium" data-filter="filter-beach">Beach</a>
                        <a href="#" class="button btn-medium" data-filter="filter-ocean">Ocean Park</a>
                    </div>
                    -->
                    <div class="items-container isotope row image-box style9">
                        <?php
						$fotos = mysqli_query($conexion,"SELECT * FROM portafolio");
						while($fot = mysqli_fetch_array($fotos, MYSQLI_BOTH)){
						?>
                        <div class="iso-item col-xs-12 col-sm-4 filter-all filter-island filter-beach">
                            <article class="box">
                                <figure>
                                    <a class="hover-effect" title="" href="#"><img width="570" height="250" alt="" src="files/portafolio/<?=$fot[3];?>"></a>
                                </figure>
                                <div class="details">
                                    <h4 class="box-title"><?=$fot[1];?></h4>
                                </div>
                            </article>
                        </div>
                        <?php }?>
                        
                    </div>
                    
                </div>
            </div>
        </section>
        
        <?php include("footer.php");?>
    </div>


    <!-- Javascript -->
    <script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="js/jquery.noconflict.js"></script>
    <script type="text/javascript" src="js/modernizr.2.7.1.min.js"></script>
    <script type="text/javascript" src="js/jquery-migrate-1.2.1.min.js"></script>
    <script type="text/javascript" src="js/jquery.placeholder.js"></script>
    <script type="text/javascript" src="js/jquery-ui.1.10.4.min.js"></script>
    
    <!-- Twitter Bootstrap -->
    <script type="text/javascript" src="js/bootstrap.js"></script>
    
    <!-- parallax -->
    <script type="text/javascript" src="js/jquery.stellar.min.js"></script>
    
    <!-- waypoint -->
    <script type="text/javascript" src="js/waypoints.min.js"></script>

    <!-- Isotope -->
    <script type="text/javascript" src="js/isotope.pkgd.min.js"></script>

    <!-- load page Javascript -->
    <script type="text/javascript" src="js/theme-scripts.js"></script>
    <script type="text/javascript" src="js/scripts.js"></script>
    
</body>
</html>