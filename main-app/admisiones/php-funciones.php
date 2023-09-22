<?php
function pagarOnline($p_id_invoice, $p_email, $p_amount, $p_billing_document, $p_billing_name, $p_billing_phone)
{
?>
    <form id="frm_botonePayco" name="frm_botonePayco" method="post" action="https://secure.payco.co/checkout.php">
        <input name="p_cust_id_cliente" type="hidden" value="89586">
        <input name="p_key" type="hidden" value="858421538f2b39ed156451490dfefb798b11bc98">

        <input name="p_id_invoice" type="hidden" value="<?= $p_id_invoice; ?>">
        <input name="p_description" type="hidden" value="Pago de formulario de inscripcion">
        <input name="p_currency_code" type="hidden" value="COP">
        <input name="p_email" type="hidden" id="p_email" value="<?= $p_email; ?>" />
        <input name="p_amount" id="p_amount" type="hidden" value="<?= $p_amount; ?>">
        <input name="p_tax" id="p_tax" type="hidden" value="0">
        <input name="p_amount_base" id="p_amount_base" type="hidden" value="<?= $p_amount; ?>">

        <input name="p_test_request" type="hidden" value="FALSE">
        <input name="p_url_response" type="hidden" value="https://plataformasintia.com/admisiones/recibo.php">
        <input name="p_url_confirmation" type="hidden" value="https://plataformasintia.com/admisiones/respuesta.php">
        <input name="p_signature" type="hidden" id="signature" value="<?php echo md5('89586' . '^' . '858421538f2b39ed156451490dfefb798b11bc98' . '^' . $p_id_invoice . '^' . $p_amount . '^' . 'COP'); ?>" />

        <input name="p_billing_document" type="hidden" id="p_billing_document" value="<?= $p_billing_document; ?>" />
        <input name="p_billing_name" type="hidden" id="p_billing_name" value="<?= $p_billing_name; ?>" />
        <input name="p_billing_country" type="hidden" id="p_billing_country" value="CO" />
        <input name="p_billing_email" type="hidden" id="p_billing_email" value="<?= $p_email; ?>" />
        <input name="p_billing_address" type="hidden" id="p_billing_address" value="n/a" />
        <input name="p_billing_phone" type="hidden" id="p_billing_phone" value="<?= $p_billing_phone; ?>" />
        <input name="p_billing_cellphone" type="hidden" id="p_billing_cellphone" value="<?= $p_billing_phone; ?>" />
    </form>

    <script type="text/javascript">
        document.frm_botonePayco.submit();
    </script>
<?php
}

function redireccionBien($pagina, $msg)
{
    header("Location:".$pagina."?msg=".base64_encode($msg));
    exit();
}

function redireccionMal($pagina, $error)
{
    header("Location:".$pagina."?error=".base64_encode($error));
    exit();
}
