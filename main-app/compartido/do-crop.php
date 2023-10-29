<?php
extract($_POST);
$dir = "../files/fotos/";
if(!is_dir($dir))
mkdir($dir);
$img = str_replace('data:image/png;base64,', '', $img);
$img = str_replace(' ', '+', $img);
$img = base64_decode($img);
$save = file_put_contents($dir.$fname, $img);
if($save){
    $resp['status'] = 'success';
}else{
    $resp['status'] = 'failed';

}
echo json_encode($resp);