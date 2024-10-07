<?php
require_once "sensitive.php";
// Update the path below to your autoload.php,
// see https://getcomposer.org/doc/01-basic-usage.md
require_once "vendor/twilio/sdk/src/Twilio/autoload.php";

use Twilio\Rest\Client;

// Find your Account SID and Auth Token at twilio.com/console
// and set the environment variables. See http://twil.io/secure
$sid = TWILIO_ACCOUNT_SID;
$token = TWILIO_AUTH_TOKEN;
$twilio = new Client($sid, $token);

$message = $twilio->messages->create(
    "+573006075800", // To
    [
        "body" => "Hola desde SINTIA, esto es un mensaje de prueba!",
        "from" => TWILIO_FROM_PHONE_NUMBER,
    ]
);

print $message->body;