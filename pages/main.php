<?php
//ลบโต๊ะที่ไม่ชำระเงิน
$sql_delreserve = select("select * from reserve where cookie_!='' and status_pay='0' ");
for ($i = 0; $i < count($sql_delreserve); $i++) {
    $data_del = $sql_delreserve[$i];
    //ถ้าเกินเวลาที่กำหนด
    if (Overdue($data_del['date_del']) == 0) {
        @delete('reserve', "id_table='" . $data_del['id_table'] . "' and status='1' ");
    }
}
//ถ้าไม่ดำเนินการใดๆ
@delete('reserve', "cookie_='" . $_COOKIE['payment'] . "' and status='1'");

?>
<div class="row">
    <div class="col text-center">
        <?php
        $cw = function_exists('load_custom_website') ? load_custom_website() : [];
        $cover_file = !empty($cw['cover']) ? $cw['cover'] : '';
        // prefer datas/cover/<file> if exists
        $cover_path = 'assets/img/title.png';
        if ($cover_file && file_exists(__DIR__ . '/../datas/cover/' . $cover_file)) {
            $cover_path = 'datas/cover/' . $cover_file;
        } elseif (file_exists(__DIR__ . '/assets/img/title.png')) {
            $cover_path = 'assets/img/title.png';
        }
        ?>
        <img src="<?php echo htmlspecialchars($cover_path, ENT_QUOTES, 'UTF-8'); ?>" class="img_title" alt="">
    </div>
</div>

<?php include('pages/payment/tab-step.php'); ?>
<div class="row">
    <div class="col-lg-9">
        <div class="card mb-4">
            <div class="card-body">
                <center>
                    <?php
                    $cw = load_custom_website();
                    $map_heading = !empty($cw['map_heading']) ? $cw['map_heading'] : 'ผังโต๊ะ งาน 48 ปี ราตรีม่วง-เหลือง';
                    ?>
                    <h3 class="mt-3 mb-5"><?php echo htmlspecialchars($map_heading, ENT_QUOTES, 'UTF-8'); ?></h3>
                </center>
                <div class="row mb-3">
                    <div class="col text-center explain d-flex justify-content-center">
                        <div class="col p-0"><button type="button" class="btn status0"></button> <br><?= $status_table[0]; ?> </div>
                        <div class="col p-0"><button type="button" class="btn status1"></button> <br><?= $status_table[1]; ?> </div>
                        <div class="col p-0"><button type="button" class="btn status2"></button> <br><?= $status_table[2]; ?> </div>
                        <div class="col p-0"><button type="button" class="btn status3"></button> <br><?= $status_table[3]; ?></div>
                        <div class="col p-0"><button type="button" class="btn status4"></button> <br><?= $status_table[4]; ?></div>

                    </div>
                </div>

                <div class="seatmap-wrapper">
                    <div class="seatmap table-overflow">
                        <table id="table_work" class="table-seatmap" style="width: 100%;">
                            <tbody>
                                <tr>
                                    <td colspan="14" class="text-center">
                                        <img src="assets/img/stage.png" width="100%" alt="">
                                    </td>
                                </tr>
                                <tr class="row-id">
                                    <td></td>
                                    <?php
                                    //แสดงเลขแถว
                                    for ($i = 1; $i <= $num_call; $i++) {
                                    ?>
                                        <td>
                                            <?= $i; ?>
                                        </td>
                                        <?php if ($i == 6) { ?><td></td><?php } ?>
                                    <?php } ?>
                                    <td></td>
                                </tr>

                                <?php
                                for ($i = 1; $i <= $num_row; $i++) {
                                    # code...
                                    $r += 1;
                                ?>
                                    <tr class="row-id">
                                        <td>
                                            <?= $alphabet[$r]; ?>
                                        </td>
                                        <?php
                                        //แสดงเลขแถว
                                        for ($i2 = 1; $i2 <= $num_call; $i2++) {
                                            //เลขโต๊ะ
                                            $t_number = $i2 + ($i * $num_call) - $num_call;

                                            if ($alphabet[$r] == 'Q') {
                                                $t_number = $t_number - 2;
                                                if ($t_number >= 191 && $t_number <= 192 or $t_number > 200) {
                                                    $css_h = "d-none";
                                                } else {
                                                    $css_h = "";
                                                }
                                            }
                                            $id_table = "T_" . $t_number;
                                            $sql_reserve = select("select * from reserve where id_table='$id_table' ");
                                            $data_r = $sql_reserve[0];
                                            $caption = "<b>" . $status_table[$data_r['status']] . "</b><br><span>" . $data_r['name'] . "</span>";



                                        ?>
                                            <td>
                                                <?php if ($data_r['status'] != 0) {
                                                    $name_table = ($data_r['name'] === "VIP") ? "VIP" : $t_number;
                                                ?>
                                                    <button type="button" class="<?= $css_h; ?> btn btn_table status<?= $data_r['status']; ?>"
                                                        data-bs-toggle="tooltip"
                                                        data-bs-offset="0,4"
                                                        data-bs-placement="bottom"
                                                        data-bs-html="true"
                                                        title="<?= $caption; ?>"><?= $name_table; ?></button>
                                                <?php } else {
                                                ?>
                                                    <button type="button" id="<?= $id_table; ?>" class="<?= $css_h; ?> btn btn_table status0" data-number="<?= $t_number; ?>">
                                                        <span><?= $t_number; ?></span>
                                                    </button>
                                                <?php } ?>
                                            </td>
                                            <?php if ($i2 == 6) { ?><td class="spaspace"></td><?php } ?>
                                        <?php } ?>
                                        <td>
                                            <?= $alphabet[$r]; ?>
                                        </td>
                                    </tr>
                                <?php } ?>



                            </tbody>
                        </table>

                        <p class="text-center text-danger">**ผังโต๊ะอาจเปลี่ยนแปลงได้ตามความเหมาะสม**</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-3 box_pay">
        <div class="card mb-4">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-7 col-lg-12 col-lg-mb-5">
                        <p class="text1">โต๊ะนั่งที่เลือก</p>
                        <p id="LargeInventory" class="text2"></p>
                    </div>
                    <div class="col-5 col-lg-12 text-end">
                        <p class="text1">ราคารวม</p>
                        <p id='total' class="text2 money">x,xxx บาท</p>
                    </div>
                </div>
                <div class="row">
                    <form id="add_reserve" class="mb-0">
                        <p id="input_id" class="mb-0"></p>

                        <div class="col-lg-mb-3 text-center">
                            <button id="btn_add" type="submit" class="btn btn-info" disabled>ดำเนินการต่อ</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Page JS -->
<script src="assets/vendor/libs/jquery/jquery.js"></script>
<link href="assets/css/toastr.min.css" rel="stylesheet" />
<script src="assets/js/toastr.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
    $(document).ready(function() {
        var current = 0;
        $('.status0').click(function() {
            //var id = $(this).attr("id").substr($(this).attr("id").length - 1);
            var id = $(this).attr("data-number");
            //var id2 = $(this).attr("idt");

            $(this).toggleClass("select");
            var x = $('[id=v_' + id + ']').length;
            if (x > 0) //there is one there
            {
                $('[id=v_' + id + ']').remove();
                $('[id=idt_' + id + ']').remove();
                    current -= <?= $table_money; ?>;
                $('#total').html(current.toLocaleString() + " บาท");
            } else {
                    current += <?= $table_money; ?>;
                $('#total').html(current.toLocaleString() + " บาท");
                $('#LargeInventory').append('<span id="v_' + id + '" class="badge bg-success mx-1 my-1">โต๊ะ ' + id + '</span>');
                $('#input_id').append('<input type="hidden" name="id_table[]" id="idt_' + id + '" value="' + id +  '"></input>');
            }
            if (current > 1) {
                document.getElementById('btn_add').disabled = false;
            } else {
                document.getElementById('btn_add').disabled = true;
            }
        });

    });


    $("#add_reserve").on("submit", function(e) {
        e.preventDefault();
        // require at least one table selected
        if ($('[name="id_table[]"]').length == 0) {
            toastr.error('กรุณาเลือกโต๊ะก่อนดำเนินการต่อ');
            return false;
        }
        $('.preloader').fadeIn();
        $.ajax({
            type: 'POST',
            url: 'pages/payment/save.php?zone=step1',
            data: new FormData(this),
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
                success: function(data) {
                    $('.preloader').hide();
                    if (data.status == 'error') {
                        toastr.error(data.mess);
                    } else {
                        // if server returned cookie value, set it client-side too (path=/)
                        if (data.cookie) {
                            document.cookie = 'payment=' + data.cookie + '; path=/; max-age=' + (15 * 60) + ';';
                        }
                        location.assign('?op=payment-pay');
                        $('.preloader').fadeIn();
                    }

                },
                error: function(xhr, status, err) {
                    // Show server response for debugging (PHP fatal / parse errors etc.)
                    $('.preloader').hide();
                    console.error('AJAX error:', status, err);
                    console.error('Response text:', xhr.responseText);
                    try {
                        var json = JSON.parse(xhr.responseText);
                        if (json && json.mess) toastr.error(json.mess);
                        else toastr.error('Server returned invalid response (see console)');
                    } catch (e) {
                        toastr.error('Server error — check console for details');
                    }
                }
        });

    });
</script>