<div class="page-content-wrapper">
    <div class="page-content">
        <div class="page-bar">
            <div class="page-title-breadcrumb">
                <div class=" pull-left">
                    <div class="page-title">Paginas</div>
                    <?php include("../compartido/texto-manual-ayuda.php");?>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    
                    <div class="col-md-8 col-lg-12">
                        
                        <div class="card card-topline-purple">
                            <div class="card-head">
                                <header>Busqueda</header>
                                <div class="tools">
                                    <a class="fa fa-repeat btn-color box-refresh" href="javascript:;"></a>
                                    <a class="t-collapse btn-color fa fa-chevron-down" href="javascript:;"></a>
                                    <a class="t-close btn-color fa fa-times" href="javascript:;"></a>
                                </div>
                            </div>
                            <div class="card-body">
                                <?php
                                    $filtro = '';
                                    if(isset($_GET["query"])){$filtro .= " AND (pagp_pagina LIKE '%".$_GET["query"]."%' OR pagp_ruta LIKE '%".$_GET["query"]."%' OR pagp_palabras_claves LIKE '%".$_GET["query"]."%')";}
                                    
                                    $tipoUsuario=$datosUsuarioActual['uss_tipo'];
                                    if($datosUsuarioActual['uss_tipo']==1){
                                        $tipoUsuario=5;
                                    }
                                    $dato=1;
                                    $consulta = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".paginas_publicidad WHERE pagp_tipo_usuario='".$tipoUsuario."' $filtro ORDER BY pagp_id");
                                    $numDatos=mysqli_num_rows($consulta);
                                    //buscador usuarios
                                    if ($numDatos<=0){
                                        $dato=2;
                                        $consulta = mysqli_query($conexion, "SELECT * FROM usuarios WHERE uss_usuario LIKE '%".$_GET["query"]."%' OR uss_nombre LIKE '%".$_GET["query"]."%' OR uss_email LIKE '%".$_GET["query"]."%'"); 
                                    }
                                    $numDatos=mysqli_num_rows($consulta);
                                    if ($numDatos<=0) {
                                        $dato=3;
                                        $consulta = mysqli_query($conexion, "SELECT * FROM academico_materias WHERE mat_nombre LIKE '%".$_GET["query"]."%' OR mat_siglas LIKE '%".$_GET["query"]."%'");
                                    }
                                    $numDatos=mysqli_num_rows($consulta);
                                    if ($numDatos<=0) {
                                        $dato=4;
                                        $consulta = mysqli_query($conexion, "SELECT * FROM academico_grados WHERE gra_nombre LIKE '%".$_GET["query"]."%' OR gra_codigo LIKE '%".$_GET["query"]."%'");
                                    }
                                    
                                    while($resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH)){
                                        switch ($dato) {
                                            case 1:
                                                $nombre=$resultado['pagp_pagina'];
                                                $descripcion=$resultado['pagp_descripcion'];
                                                $ruta=$resultado['pagp_ruta'];
                                                $nombreRuta=$resultado['pagp_ruta'];
                                                if ($resultado['pagp_parametro']!=1) {
                                                    $ruta="page-info.php?idmsg=303&idPagina='".$resultado['pagp_id']."'";
                                                }
                                                break;
                                            
                                            case 2:
                                                $nombre=$resultado['uss_nombre'];
                                                $descripcion="";
                                                $ruta="usuarios-editar.php?id".$resultado['uss_id'];
                                                $nombreRuta="";
                                                break;

                                            case 3:
                                                $nombre=$resultado['mat_nombre'];
                                                $descripcion="";
                                                $ruta="asignaturas-editar.php?id".$resultado['mat_id'];
                                                $nombreRuta="";
                                                break;

                                            case 4:
                                                $nombre=$resultado['gra_nombre'];
                                                $descripcion="";
                                                $ruta="cursos-editar.php?id".$resultado['gra_id'];
                                                $nombreRuta="";
                                                break;  

                                        }
 
                                ?>
                                <p>
                                    <h3 style="margin: 0px";><a href="<?=$ruta;?>"><?=$nombre;?></a></h3>
                                    <h6 style="margin: 0px";><a href="<?=$ruta;?>"><?=$nombreRuta;?></a></h6>
                                    <p><?=$descripcion;?></P>
                                </p>

                                    
                                <?php
                                    }
                                ?>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>