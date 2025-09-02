<!DOCTYPE html>
<html
    lang="en"
    class="light-style layout-menu-fixed"
    dir="ltr"
    data-theme="theme-default"
    data-assets-path="assets/"
    data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>ระบบจองโต๊ะงาน 48ปี ราตรีม่วง-เหลือง โรงเรียนหนองม่วงวิทยา</title>
    <meta name="author" content="พัฒนาระบบโดย นายพงศธร แสงม่วง">
    <meta name="keywords" content="ระบบจองโต๊ะงาน 48ปี ราตรีม่วง-เหลือง, โรงเรียนหนองม่วงวิทยา, หนองม่วงวิทยา, " />
    <meta name="description" content="ระบบจองโต๊ะงาน 48ปี ราตรีม่วง-เหลือง โรงเรียนหนองม่วงวิทยา" />
    <meta property="og:url" content="https://www.nmwit.ac.th/ratree48/" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="ระบบจองโต๊ะงาน 48ปี ราตรีม่วง-เหลือง โรงเรียนหนองม่วงวิทยา" />
    <meta property="og:image" content="assets/img/profile.png" />
    <meta property="fb:app_id" content="406977186010752" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.png" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="assets/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <!-- Page CSS -->
    <link rel="stylesheet" href="assets/css/loader.css" />
    <link rel="stylesheet" href="assets/css/style_add.css" />

    <!-- Helpers -->
    <script src="assets/vendor/js/helpers.js"></script>
    <script src="assets/vendor/libs/jquery/jquery.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="assets/js/config.js"></script>
</head>

<body id="">
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
                    <!-- Content -->

                    <div class="container-xxl flex-grow-1 container-p-y">
                        <?php
                        include_once('load.php');
                        $id = $_GET['no'];
                        $sql_reserve = select("select * from reserve where payment='$id' ");
                        $data_r = $sql_reserve[0];
                        $status = count($sql_reserve) <= 0 ? "ว่าง" : $status_table[$data_r['status']];
                        $status_css = count($sql_reserve) <= 0 ? $status_table_css[0] : $status_table_css[$data_r['status']];
                        ?>

                        <?php
                        if (count($sql_reserve) <= 0) {
                        }
                        ?>

                        <div class="row">

                            <?php if (!empty($data_r['payment'])) { ?>
                                <div class="mb-3 text-center">
                                    <img class="" style="width: 50%;" src="datas/payment/<?= $data_r['id_table']; ?>_<?= decryptCookie($data_r['payment']); ?>.jpg">
                                </div>
                            <?php } ?>
                            <div class="mb-3">
                                <div class="alert alert-<?= $status_css; ?>" role="alert">สถานะ <?= $status; ?></div>
                                <h4>เลขที่โต๊ะ : <?= str_pad($data_r['name_table'], 3, "0", STR_PAD_LEFT); ?></h4>
                                <h4>ชื่อผู้ซื้อ : <?= $data_r['name'] ?></h4>
                                <h4>เบอร์ติดต่อ : <?= $data_r['tel'] ?></h4>
                                <h4>ชื่อผู้ขาย : <?= $data_r['seller'] ?></h4>
                                <h4>เวลา : <?= DatetoThai($data_r['date_buy'], "1995-02-20 00:00:00>>20ก.พ.2538 00:00:00"); ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="assets/vendor/libs/jquery/jquery.js"></script>
    <script src="assets/vendor/libs/popper/popper.js"></script>
    <script src="assets/vendor/js/bootstrap.js"></script>
    <script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

    <script src="assets/vendor/js/menu.js"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->
    <script src="assets/js/main.js"></script>

    <!-- Page JS -->

    <script>
        $('.preloader').fadeOut();
    </script>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
</body>

</html>