<div class="card card-topline-purple">
    <div class="card-head">
        <header><?= $frases[254][$datosUsuarioActual['uss_idioma']]; ?></header>
        <div class="tools">
            <a class="fa fa-repeat btn-color box-refresh" href="javascript:;"></a>
            <a class="t-collapse btn-color fa fa-chevron-down" href="javascript:;"></a>
            <a class="t-close btn-color fa fa-times" href="javascript:;"></a>
        </div>
    </div>
    <div class="card-body">

        <div class="row" style="margin-bottom: 10px;">
            <div class="col-sm-12">
                <div class="btn-group">

                    <a href="grupos.php?contenido=grupos-agregar" id="addRow" class="btn deepPink-bgcolor">
                        Agregar nuevo <i class="fa fa-plus"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="table-scrollable">
            <table id="example1" class="display" style="width:100%;">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Codigo</th>
                        <th><?= $frases[254][$datosUsuarioActual[8]]; ?></th>
                        <th style="width:10%;"><?= $frases[54][$datosUsuarioActual[8]]; ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $consulta = Grupos::listarGrupos();
                    $contReg = 1;
                    while ($resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH)) {
                    ?>
                        <tr>
                            <td><?= $contReg; ?></td>
                            <td><?= $resultado["gru_codigo"]; ?></td>
                            <td><?= $resultado['gru_nombre']; ?></td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary"><?= $frases[54][$datosUsuarioActual[8]]; ?></button>
                                    <button type="button" class="btn btn-primary dropdown-toggle m-r-20" data-toggle="dropdown">
                                        <i class="fa fa-angle-down"></i>
                                    </button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href="grupos.php?contenido=grupos-agregar&id=<?=$resultado["gru_id"];?>"><?= $frases[165][$datosUsuarioActual[8]]; ?></a></li>
                                     </ul>
                                </div>
                            </td>
                        </tr>
                    <?php
                        $contReg++;
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>