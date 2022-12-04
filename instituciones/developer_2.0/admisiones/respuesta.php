<?php
include("bd-conexion.php");

try {
    $sql = "INSERT INTO pasarela_respuestas(psr_cliente, psr_ref, psr_transaccion, psr_respuesta_nombre, psr_respuesta_codigo, psr_documento, psr_nombre, psr_email, psr_error_codigo, psr_error_nombre, psr_celular, psr_ref_epayco, psr_factura)VALUES (:cliente, :ref, :transaccion, :respname, :respcode, :documento, :nombre, :email, :errocod, :razonname, :celular, :epayco, :factura)";
    $stmt = $pdo->prepare($sql);


    $stmt->bindParam(':cliente', $_POST['x_cust_id_cliente'], PDO::PARAM_STR);
    $stmt->bindParam(':ref', $_POST['x_ref_payco'], PDO::PARAM_STR);
    $stmt->bindParam(':transaccion', $_POST['x_transaction_id'], PDO::PARAM_STR);
    $stmt->bindParam(':respname', $_POST['x_response'], PDO::PARAM_INT);
    $stmt->bindParam(':respcode', $_POST['x_cod_response'], PDO::PARAM_INT);
    $stmt->bindParam(':documento', $_POST['x_customer_document'], PDO::PARAM_STR);
    $stmt->bindParam(':nombre', $_POST['x_customer_name'], PDO::PARAM_STR);
    $stmt->bindParam(':email', $_POST['x_customer_email'], PDO::PARAM_STR);
    $stmt->bindParam(':errocod', $_POST['x_errorcode'], PDO::PARAM_INT);
    $stmt->bindParam(':razonname', $_POST['x_response_reason_text'], PDO::PARAM_STR);
    $stmt->bindParam(':celular', $_POST['x_customer_movil'], PDO::PARAM_STR);
    $stmt->bindParam(':epayco', $_POST['x_ref_payco'], PDO::PARAM_STR);
    $stmt->bindParam(':factura', $_POST['x_id_factura'], PDO::PARAM_STR);

    $stmt->execute();
    $newId = $pdo->lastInsertId();


    switch ($_POST['x_cod_response']) {
        case 1:
            $estado = 3;
            break;
        case 2:
            $estado = 2;
            break;
        case 3:
            $estado = 1;
            break;
        case 4:
            $estado = 2;
            break;
        case 9:
            $estado = 2;
            break;
        case 10:
            $estado = 2;
            break;

        default:
            $estado = 1;
            break;
    }

    $aspQuery = 'UPDATE aspirantes SET asp_estado_solicitud = :estado WHERE asp_id = :id';
    $asp = $pdo->prepare($aspQuery);
    $asp->bindParam(':id', $_POST['x_id_factura'], PDO::PARAM_INT);
    $asp->bindParam(':estado', $estado, PDO::PARAM_STR);
    $asp->execute();
    echo "tODO BIEN";
} catch (PDOException $Exception) {
    // Note The Typecast To An Integer!
    echo "Error!: " . $e->getMessage() . "<br/>";
    die();
}
