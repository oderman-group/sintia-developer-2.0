<?php
include("session.php");

$year = !empty($_GET['year']) ? $_GET['year'] : $_SESSION["bd"];

if (!empty($_GET['term'])) {
    try {
        $lista = UsuariosPadre::listarUsuariosCompartir($_GET['term'], $year, $year);

        if (!$lista) {
            echo "No se encontraron resultados.";
            exit();
        }

        while ($dato = mysqli_fetch_array($lista, MYSQLI_BOTH)) {
            $nombre = UsuariosPadre::nombreCompletoDelUsuario($dato)." - ".$dato["pes_nombre"];

            $response[] = [
                "value" => $dato["uss_id"],
                "label" => $nombre
            ];
        }

        echo json_encode($response);
    } catch (Exception $e) {
        echo "Excepción catpurada: " . $e->getMessage();
        exit();
    }
}