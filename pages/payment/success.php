<?php
if (!isset($_COOKIE['payment_success'])) { ?>
    <script>
    location.assign('?op=index');
    </script>
<?php } ?>
<div class="row">
    <div class="col text-center">
        <img src="assets/img/title.png" class="img_title" alt="">
    </div>
</div>
<?php include('pages/payment/tab-step.php'); ?>
<?php
$sql_reserve = select("select * from reserve where payment='$_COOKIE[payment_success]' "); //
for ($i = 0; $i < count($sql_reserve); $i++) {
    $pay = $sql_reserve[$i];
    if ($i >= 1) {
        $name_t .= ", ";
    }
    $name_t .= $pay['name_table'];
    $name = $pay['name'];
    $buy = $pay['date_buy'];
    $payment = $pay['payment'];
}

?>



<div class="card mb-4">
    <div class="card-body">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-4">
                <div id="photo" class="lg-p-5">
                    <div class="row">
                        <div class="col text-center">
                            <img src="assets/img/banner.png" class="w-100" alt="">
                        </div>
                    </div>
                    <div class="card mb-4 slip_box">
                        <div class="card-body">
                            <div class="row">
                                <div class="col text-center">
                                    <img src="assets/img/checklist.png" style="width: 70px;" alt="">
                                    <h2 class="fw-bold pb-0 mb-0">E-Card Online</h2>
                                    <h4 class="fw-bold py-3 mb-0">งาน 48ปี ราตรีม่วง-เหลือง</h4>
                                </div>
                            </div>
                            <hr>
                            <div class="row text-center">
                                <?php
                                include('plugins/phpqrcode/qrlib.php');
                                // build a URL that points to the site's examine.php (use current host)
                                $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
                                $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
                                // assume examine.php lives at the site root
                                $content = $scheme . '://' . $host . '/examine.php?no=' . urlencode($_COOKIE['payment_success']);
                                $file_name = 'datas/qrcode/' . decryptCookie($_COOKIE['payment_success']) . '.png';
                                QRcode::png($content, $file_name, QR_ECLEVEL_H, 10);
                                echo "<p><img src='{$file_name}' class='qrcode'></p>";
                                ?>
                            </div>
                            <h2 class="text-center">เลขโต๊ะ <?= $name_t; ?></h2>
                            <div class="row">
                                <div class="col">
                                    <div class="payment">
                                        <div class="row">
                                            <div class="col-4 mb-1">
                                                ผู้ซื้อ
                                            </div>
                                            <div class="col text-end">
                                                <?= $name; ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4 mb-1">
                                                วันที่ซื้อ
                                            </div>
                                            <div class="col text-end">
                                                <?= DatetoThai($buy, "1995-02-20 00:00:00>>20ก.พ.2538 00:00:00"); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center text-danger">แคปหรือบันทึกภาพไว้เพื่อเป็นหลังฐาน</div>
        <div class="row mt-2 text-center">
            <center><button id="open-image" onclick="window.open('datas/e-card/<?= $_COOKIE['payment_success']; ?>.png')" class="btn btn-info w-50">บันทึกภาพ</button></center>
        </div>
        <div class="row mt-2 text-center">
            <center><a href="?op=index" class="btn btn-secondary w-50">กลับหน้าหลัก</a></center>
        </div>
    </div>
</div>

<script src="https://html2canvas.hertzen.com/dist/html2canvas.js"></script>
<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
<script>
    $('#open-image').hide()
    // to screenshot the div
    function takeshot() {
        let div = document.querySelector('#photo');
        html2canvas(div).then(
            function(canvas) {
                //document.getElementById('output').appendChild(canvas);
                clearInterval(cap_img);
                // Get base64URL
                var base64URL = canvas.toDataURL('image/jpeg').replace('image/jpeg', 'image/octet-stream');

                // AJAX request
                $.ajax({
                    url: 'pages/payment/ajaxfile.php?name=<?=$payment; ?>',
                    type: 'post',
                    data: {
                        image: base64URL
                    },
                    success: function(data) {
                        console.log('Upload successfully');
                        $('#open-image').fadeIn()

                    }
                });
            })
    }





    var cap_img = setInterval("takeshot()", 1000);
</script>