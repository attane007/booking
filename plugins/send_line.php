<?php
$sToken = "Fms4hDqXUAEk8RoYDOjTNfW05i1CxU1E0qwU3OIn5WS";

$data  = array(
    'message' => $sMessage,
    'imageFile' => $imageFile
);
$chOne = curl_init();
curl_setopt($chOne, CURLOPT_URL, "https://notify-api.line.me/api/notify");
curl_setopt($chOne, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($chOne, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($chOne, CURLOPT_POST, 1);
curl_setopt( $chOne, CURLOPT_POSTFIELDS, "message=".$sMessage); 
$headers = array('Content-type: multipart/form-data', 'Authorization: Bearer ' . $sToken . '',);
curl_setopt($chOne, CURLOPT_HTTPHEADER, $headers);
curl_setopt($chOne, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec($chOne);
