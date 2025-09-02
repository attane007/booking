
<?php
include_once('../load.php');
define('IS_ADMIN', true);
if (!isset($_COOKIE['login_'])) {
    include 'pages/login/signin.php';
} else {
    include 'includes/header.php';

    // Simple admin page router: use ?page=<name> to include admin/pages/<name>.php
    $page = isset($_GET['page']) ? $_GET['page'] : 'main';
    // allow only lowercase letters, numbers and underscore to avoid path traversal
    if (!preg_match('/^[a-z0-9_]+$/', $page)) {
        $page = 'main';
    }
    $candidate = __DIR__ . '/pages/' . $page . '.php';
    if (file_exists($candidate)) {
        include $candidate;
    } else {
        include 'pages/main.php';
    }

    include 'includes/footer.php';
}

?>

