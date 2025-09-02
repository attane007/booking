
<?php
include_once('../load.php');
define('IS_ADMIN', true);
if (!isset($_COOKIE['login_'])) {
    include 'pages/login/signin.php';
} else {
    include 'includes/header.php';
    include 'pages/main.php';
    include 'includes/footer.php';
}

?>

