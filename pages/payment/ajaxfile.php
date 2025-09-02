<?php 
include_once('../../load.php');
$image = $_POST['image'];
$name =$_GET['name'];

$location = "../../datas/e-card/";
$image_parts = explode(";base64,", $image);
$image_base64 = base64_decode($image_parts[1]);
$filename = $name.'.png';
$file = $location . $filename;
file_put_contents($file, $image_base64);
	
?>