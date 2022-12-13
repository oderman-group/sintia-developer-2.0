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
                                <header>Paginas</header>
                                <div class="tools">
                                    <a class="fa fa-repeat btn-color box-refresh" href="javascript:;"></a>
                                    <a class="t-collapse btn-color fa fa-chevron-down" href="javascript:;"></a>
                                    <a class="t-close btn-color fa fa-times" href="javascript:;"></a>
                                </div>
                            </div>
                            <div class="card-body">
                                <!--
                                <div class="row" style="margin-bottom: 10px;">
                                    <div class="col-sm-12">
                                        <div class="btn-group">
                                            <a href="paginas-agregar.php" id="addRow" class="btn deepPink-bgcolor">
                                                Agregar nuevo <i class="fa fa-plus"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                -->
                            <span id="respuestaGuardar"></span>	
                            
                                <div class="table-scrollable">
                                <table id="example1" class="display" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>ID</th>
                                            <th>Nombre</th>
                                            <th>Ruta</th>
                                            <th><?=$frases[54][$datosUsuarioActual[8]];?></th>
                                        </tr>
                                    </thead>
                                    
                                    <?php
                                        $filtro = '';
                                        if(isset($_GET["query"])){$filtro .= " AND (pagp_pagina LIKE '%".$_GET["query"]."%' OR pagp_ruta LIKE '%".$_GET["query"]."%' OR pagp_palabras_claves LIKE '%".$_GET["query"]."%')";}

                                        $tipoUsuario=$datosUsuarioActual['uss_tipo'];
                                        if($datosUsuarioActual['uss_tipo']==1){
                                            $tipoUsuario=5;
                                        }
                                        $consulta = mysql_query("SELECT * FROM ".$baseDatosServicios.".paginas_publicidad WHERE pagp_tipo_usuario='".$tipoUsuario."' $filtro ORDER BY pagp_id",$conexion);
                                        $contReg = 1;
                                        while($resultado = mysql_fetch_array($consulta)){
                                            ?>
                                        <tr>
                                            <td><?=$contReg;?></td>
                                            <td><?=$resultado['pagp_id'];?></td>
                                            <td><?=$resultado['pagp_pagina'];?></td>
                                            <td>
                                                <a href="<?=$resultado['pagp_ruta'];?>">
                                                    <?=$resultado['pagp_ruta'];?>
                                                </a>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                        <button type="button" class="btn btn-primary">Acciones <?php //echo $frases[54][$datosUsuarioActual[8]];?></button>
                                                        <button type="button" class="btn btn-primary dropdown-toggle m-r-20" data-toggle="dropdown">
                                                            <i class="fa fa-angle-down"></i>
                                                        </button>
                                                        <ul class="dropdown-menu" role="menu">
                                                            <!--<li><a href="paginas-editar.php?id=<?=$resultado['pagp_id'];?>">Editar</a></li>-->
                                                        </ul>
                                                    </div>
                                            </td>
                                        </tr>
                                        <?php 
                                                $contReg++;
                                            }
                                            ?>
                                    
                                </table>
                                    
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>