<?php
include_once('../../../load.php');
$result = array();
if ($_POST['pass'] == 'fame1234') {
    // Set cookie variables
    $value = encryptCookie(9999);
    setcookie("login_", $value, time() + 3600 * (15 * 24), '/'.$url); //อยู่ในระบบ 15 วัน
    $result['status'] = "success";  // เมื่อล็อกอินผ่าน
} else {
    $result['status'] = "error";
}
echo json_encode($result);
