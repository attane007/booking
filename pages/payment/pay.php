<?php
$sql_reserve = select("select * from reserve where cookie_='$_COOKIE[payment]' ");
if (!isset($_COOKIE['payment']) or count($sql_reserve) <= 0) { ?>
    <script>
    location.assign('?op=index');
    </script>
<?php } ?>
<?php include('pages/payment/tab-step.php'); ?>
<div class="row">
    <div class="col-12 box_time">
        <div class="card  mb-4">
            <div class="card-body justify-content-center text-center">
                <span>เหลือเวลาชำระเงินอีก </span>
                <strong><span id="div_date">00:00</span></strong>
            </div>
            <div style="clear: both;"></div>
        </div>
    </div>
</div>
<div class="row ">
    <div class="col-12 box_pay2">
        <div class="card mb-4">
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-12 col-lg-4">
                        <div class="row mb-3">
                            <div class=" mb-3 text-center">
                                <?php
                                // prefer QR set in custom website settings (datas/payment/<file>), fall back to assets image
                                $cw = function_exists('load_custom_website') ? load_custom_website() : array();
                                $qr_path = 'assets/img/qr-code.png';
                                if (!empty($cw['qr']) && file_exists(__DIR__ . '/../../datas/payment/' . $cw['qr'])) {
                                    $qr_path = 'datas/payment/' . $cw['qr'];
                                }
                                ?>
                                <img class="" style="width: 80%;" src="<?php echo htmlspecialchars($qr_path, ENT_QUOTES, 'UTF-8'); ?>"><br>
                                <b>โอนชำระเงินค่าโต๊ะ</b>
                                <?php
                                $cw = function_exists('load_custom_website') ? load_custom_website() : array();
                                $bank_name = !empty($cw['bank_name']) ? $cw['bank_name'] : 'ธนาคารไทยพาณิชย์';
                                $account_number = !empty($cw['account_number']) ? $cw['account_number'] : '401-831327-1';
                                $promptpay = !empty($cw['promptpay']) ? $cw['promptpay'] : '089-4961507';
                                $account_holder = !empty($cw['account_holder']) ? $cw['account_holder'] : 'นิศากร ห้องกระจก';
                                ?>
                                <p><?php echo htmlspecialchars($bank_name, ENT_QUOTES, 'UTF-8'); ?><br>
                                    เลขบัญชี <?php echo htmlspecialchars($account_number, ENT_QUOTES, 'UTF-8'); ?><br>
                                    พร้อมเพย์ : <?php echo htmlspecialchars($promptpay, ENT_QUOTES, 'UTF-8'); ?><br>
                                    ชื่อบัญชี : <?php echo htmlspecialchars($account_holder, ENT_QUOTES, 'UTF-8'); ?></p>
                            </div>
                            <hr>
                            <div class="col-7  col-lg-mb-5">
                                <p class="text1">โต๊ะนั่งที่เลือก</p>
                                <p class="text2">
                                    <?php
                                    $money = number_format($table_money * count($sql_reserve));
                                    for ($i = 0; $i < count($sql_reserve); $i++) {
                                        $data_ = $sql_reserve[$i];
                                    ?>
                                        <span class="badge bg-success mx-1 my-1"><?= $data_['name_table']; ?></span>
                                    <?php } ?>
                                </p>
                            </div>
                            <div class="col-5 text-end">
                                <p class="text1">ราคารวม</p>
                                <p id='total' class="text2 money"><?= $money; ?> บาท</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6">
                        <div class="row ">
                            <form id="step2" novalidate>
                                <?php
                                for ($i = 0; $i < count($sql_reserve); $i++) {
                                    $data_ = $sql_reserve[$i];
                                ?>
                                    <input type="hidden" name="id_table[]" value="<?= $data_['id_table']; ?>-<?= $data_['name_table']; ?>"></input>
                                <?php } ?>
                                <div class="mb-3">
                                    <label class="form-label">ชื่อผู้ซื้อ</label>
                                    <input type="text" class="form-control" name="name_b" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">เบอร์ติดต่อ</label>
                                    <!-- enforce numeric input on the frontend: inputmode and pattern help mobile keyboards and HTML validation -->
                                    <input type="tel" inputmode="numeric" pattern="\d+" class="form-control" name="tel" maxlength="10" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">อีเมล</label>
                                    <input type="email" class="form-control" name="email" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">แนบสลิป</label>
                                    <input class="form-control" type="file" name="file_slip" required>
                                </div>
                                <div class="col-lg-mb-3 text-center">
                                    <button id="btn_add" type="submit" class="btn btn-info">ยืนยันการจองโต๊ะ</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!----- Countdown ----->
<?php
$data_date = $sql_reserve[0];

list($t_date, $t_time) = explode(" ", $data_date['date_del']);
list($y, $m, $d) = explode("-", $t_date);
list($h, $i, $s) = explode(":", $t_time);
$int_y = $y;
$int_m = ($m - 1);
$int_d = ($d - 0);
$int_h = ($h - 0);
$int_i = ($i - 0);
$int_s = ($s - 0);

?>

<script type="text/javascript">
    function countDown() {
        var timeA = new Date(); // วันเวลาปัจจุบัน
        var timeB = new Date(<?= $int_y; ?>, <?= $int_m; ?>, <?= $int_d; ?>, <?= $int_h; ?>, <?= $int_i; ?>, <?= $int_s; ?>, 0);
        var timeDifference = timeB.getTime() - timeA.getTime();
        if (timeDifference >= 0) {
            timeDifference = timeDifference / 1000;
            timeDifference = Math.floor(timeDifference);
            var wan = Math.floor(timeDifference / 86400);
            var l_wan = timeDifference % 86400;
            var hour = Math.floor(l_wan / 3600);
            var l_hour = l_wan % 3600;
            var minute = Math.floor(l_hour / 60);
            var second = l_hour % 60;
            if (second < 10) {
                var c = "0";
            } else {
                var c = "";
            }
            var text = "0" + minute + ":" + c + second;
            var div_date = document.getElementById('div_date').innerHTML = text;

            if (minute == 0 && second == 0) {

                setInterval(function() {
                    //window.location.reload()
                    window.location.assign('?op=index');
                }, 1000);
                //$('#list_club').addClass("hidden");
                clearInterval(iCountDown); // ยกเลิกการนับถอยหลังเมื่อครบ

            }
        }
    }
    // การเรียกใช้
    var iCountDown = setInterval("countDown()", 1000);
</script>

<!-- Page JS -->
<script src="assets/vendor/libs/jquery/jquery.js"></script>
<link href="assets/css/toastr.min.css" rel="stylesheet" />
<script src="assets/js/toastr.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
    $("#step2").on("submit", function(e) {
        e.preventDefault();
        var form = $(this)[0];
                if (form.checkValidity() === false) {
            toastr.error('กรุณากรอกข้อมูลให้ครบถ้วน');
        } else {
            // frontend validation: ensure tel contains only digits
            var telVal = $.trim($('[name="tel"]').val());
            // strip non-digits for extra safety
            var digits = telVal.replace(/\D+/g, '');
            if (digits.length === 0) {
                toastr.error('กรุณากรอกเบอร์โทรศัพท์เป็นตัวเลข');
                return false;
            }
            // optionally enforce length (e.g., 9-10 digits)
            if (digits.length < 9 || digits.length > 10) {
                toastr.error('กรุณากรอกเบอร์โทรศัพท์ให้ถูกต้อง (9-10 ตัวเลข)');
                return false;
            }
            // ensure the input contains only digits before submitting
            $('[name="tel"]').val(digits);
            $('.preloader').fadeIn();
            $.ajax({
                type: 'POST',
                url: 'pages/payment/save.php?zone=step2',
                data: new FormData(this),
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(data) {
                    $('.preloader').hide();
                    if (data.status == 'success') {
                        Swal.fire({
                            html: data.mess,
                            icon: 'success',
                            confirmButtonText: 'ตกลง',
                        }).then((result) => {
                            $('.preloader').fadeIn();
                            location.assign('?op=payment-success');
                        });
                    } else {
                        Swal.fire({
                            html: data.mess,
                            icon: 'error',
                            confirmButtonText: 'ตกลง',
                        }).then((result) => {
                            $('.preloader').fadeIn();
                            location.reload();
                        });
                    }

                }
            });
        }
        form.classList.add('was-validated');

    });
</script>