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

                                    $consulta = mysql_query("SELECT * FROM ".$baseDatosServicios.".paginas_publicidad WHERE pagp_tipo_usuario='".$tipoUsuario."' $filtro ORDER BY pagp_id",$conexion);
                                    while($resultado = mysql_fetch_array($consulta)){

                                        $ruta=$resultado['pagp_ruta'];
                                        if($resultado['pagp_parametro']!=1){
                                            $ruta="page-info.php?idmsg=303&idPagina='".$resultado['pagp_id']."'";
                                        }
                                ?>
                                <p>
                                    <h3><a href="<?=$ruta;?>"><?=$resultado['pagp_pagina'];?></a></h3>
                                    <p><?=$resultado['pagp_descripcion'];?></P>
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