<!DOCTYPE html>
<html
  lang="en"
  class="light-style layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="../assets/"
  data-template="vertical-menu-template-free">

<head>
  <meta charset="utf-8" />
  <meta
    name="viewport"
    content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

  <title>Admin - ระบบจองโต๊ะ</title>
  <meta name="author" content="พัฒนาระบบโดย นายพงศธร แสงม่วง">

  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="../assets/img/favicon.png" />

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
    rel="stylesheet" />

  <!-- Icons -->
  <link rel="stylesheet" href="../assets/vendor/fonts/boxicons.css" />

  <!-- Core CSS -->
  <link rel="stylesheet" href="../assets/vendor/css/core.css" class="template-customizer-core-css" />
  <link rel="stylesheet" href="../assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
  <link rel="stylesheet" href="../assets/css/demo.css" />

  <!-- Vendors CSS -->
  <link rel="stylesheet" href="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

  <!-- Page CSS -->
  <link rel="stylesheet" href="../assets/css/loader.css" />
  <link rel="stylesheet" href="../assets/css/style_add.css" />

  <!-- Helpers -->
  <script src="../assets/vendor/js/helpers.js"></script>
  <script src="../assets/vendor/libs/jquery/jquery.js"></script>

  <!-- Config -->
  <script src="../assets/js/config.js"></script>
  <?php
  // Debug: output existence of key asset files so user can view page source and check paths
  $debug_assets = [
    'core_css' => __DIR__ . '/../../assets/vendor/css/core.css',
    'theme_css' => __DIR__ . '/../../assets/vendor/css/theme-default.css',
    'helpers_js' => __DIR__ . '/../../assets/vendor/js/helpers.js',
    'jquery_js' => __DIR__ . '/../../assets/vendor/libs/jquery/jquery.js',
    'main_js' => __DIR__ . '/../../assets/js/main.js'
  ];
  echo "<!-- ASSET DEBUG START -->\n";
  foreach ($debug_assets as $k => $p) {
    $exists = file_exists($p) ? 'yes' : 'no';
    $real = file_exists($p) ? realpath($p) : $p;
    echo "<!-- $k: exists={$exists}; path={$real} -->\n";
  }
  echo "<!-- ASSET DEBUG END -->\n";
  ?>
  <?php if (defined('IS_ADMIN') && IS_ADMIN): ?>
  <link rel="stylesheet" href="assets/css/admin.css" />
  <?php endif; ?>
</head>

<body id="body_admin" class="admin">
  <div class="preloader">
    <div>
      <span class="loader"></span>
      <span class="text">กำลังโหลด...</span>
    </div>
  </div>

  <!-- Layout wrapper -->
  <div class="layout-wrapper layout-content-navbar layout-without-menu">
    <div class="layout-container">
      <!-- Layout container -->
      <div class="layout-page">
        <!-- Content wrapper -->
        <div class="content-wrapper">
          <div class="container-xxl flex-grow-1 container-p-y">
