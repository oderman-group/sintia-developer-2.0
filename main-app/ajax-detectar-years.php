<?php 
include("../conexion.php");

$consultaYears=mysqli_query($conexion, "SELECT ins_years FROM instituciones WHERE ins_id ='".$_REQUEST["idInsti"]."'");
$years = mysqli_fetch_array($consultaYears, MYSQLI_BOTH);
$yearArray = explode(",", $years['ins_years']);
$yearStart = $yearArray[0];
$yearEnd = $yearArray[1];
while($yearStart <= $yearEnd){
    $selected = '';
    if(($yearStart == date("Y"))){ $selected = 'selected';}
    echo '<option value="'.$yearStart.'" '.$selected.'>'.$yearStart.'</option>';
    $yearStart ++;
}
?>