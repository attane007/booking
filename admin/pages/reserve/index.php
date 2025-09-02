<?php
//จำนวนแถวตอนลึก
$alphabet = array('', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
//,'K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'
$num_row = 11;
$num_call = 11;
?>

<div class="row">
    <div class="col-lg-9">
        <div class="card mb-4">
            <div class="card-body">

                <div class="row mb-3">
                    <div class="col text-center explain d-flex justify-content-center">
                        <div class=""><button type="button" class="btn status0"></button> ว่าง </div>
                        <div class="mx-4"><button type="button" class="btn status1"></button> ติดจอง </div>
                        <div class=""><button type="button" class="btn status2"></button> ขายแล้ว</div>

                    </div>
                </div>

                <div class="seatmap-wrapper">
                    <div class="seatmap table-overflow">
                        <table id="table_work" class="table-seatmap" style="width: 100%;">
                            <tbody>
                                <tr>
                                    <td colspan="13" class="text-center">
                                        <img src="../assets/img/stage.png" width="100%" alt="">
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="13" align="center">
                                        <div class="empty walkway"></div>
                                    </td>
                                </tr>
                                <tr class="row-id">
                                    <td>

                                    </td>
                                    <?php
                                    for ($i = 1; $i <= $num_row; $i++) {
                                    ?>
                                        <td>
                                            <?= $i; ?>
                                        </td>
                                    <?php } ?>

                                    <td>

                                    </td>
                                </tr>
                                <?php
                                $t_num = 0;
                                for ($r = 1; $r < count($alphabet); $r++) {
                                    # code...

                                ?>
                                    <tr class="row-id">
                                        <td>
                                            <?= $alphabet[$r]; ?>
                                        </td>
                                        <?php
                                        $num_table = 1 + $t_num;
                                        for ($i = 1; $i <= $num_call; $i++) {
                                            # code...
                                            $id_table = $alphabet[$r] . $i;
                                            $status_reserve = status_reserve($id_table);
                                            $sql_reserve = select("select * from reserve where id_table='$id_table' ");
                                            $data_r = $sql_reserve[0];
                                            switch ($data_r['status']) {
                                                case '1':
                                                    $caption = "<b>ติดจอง</b><br><i class='bx bx-heart bx-xs' ></i> <span>" . $data_r['name'] . "</span>";
                                                    break;

                                                case '2':
                                                    $caption = "<b>ขายแล้ว</b><br><i class='bx bx-heart bx-xs' ></i> <span>" . $data_r['name'] . "</span>";
                                                    break;
                                            }
                                            //$t_num +=10;
                                            $num_table = str_pad($num_table, 3, "0", STR_PAD_LEFT);


                                        ?>
                                            <td>
                                                <?php if ($data_r['status'] > 0) { ?>
                                                    <button type="button" class="btn <?= $status_reserve; ?>"
                                                        data-bs-toggle="tooltip"
                                                        data-bs-offset="0,4"
                                                        data-bs-placement="bottom"
                                                        data-bs-html="true"
                                                        title="<?= $caption; ?>"><?= $num_table; ?></button>
                                                <?php } else { ?>
                                                    <button type="button" id="<?= $id_table; ?>" class="btn <?= $status_reserve; ?>" data-table="<?= $num_table; ?>">
                                                        <span><?= $num_table; ?></span>
                                                    </button>
                                                <?php } ?>
                                            </td>
                                        <?php
                                            $num_table = $num_table + 10;
                                        } ?>

                                        <td>
                                            <?= $alphabet[$r]; ?>
                                        </td>
                                    </tr>
                                <?php
                                    $t_num = $t_num + 1;
                                } ?>



                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-3 box_pay">
        <div class="card mb-4">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col col-lg-12 mb-5">
                        <p class="text1">โต๊ะนั่งที่เลือก</p>
                        <p id="LargeInventory" class="text2"></p>
                    </div>
                    <div class="col col-lg-12 text-end">
                        <p class="text1">ราคารวม</p>
                        <p id='total' class="text2 money">x,xxx บาท</p>
                    </div>
                </div>
                <div class="row">
                    <form id="add_reserve" novalidate>
                        <p id="input_id"></p>
                        <div class="col-md mb-3">
                            <small class="fw-semibold d-block">ประเภท</small>
                            <div class="form-check form-check-inline mt-0">
                                <input class="form-check-input" type="radio" name="status" id="inlineRadio1" value="1" required>
                                <label class="form-check-label" for="inlineRadio1"> จอง</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="inlineRadio2" value="2" required>
                                <label class="form-check-label" for="inlineRadio2"> ซื้อ</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="defaultFormControlInput" class="form-label">ชื่อผู้ซื้อ</label>
                            <input type="text" class="form-control" name="name_b" id="defaultFormControlInput">
                        </div>
                        <div class="mb-3">
                            <label for="defaultFormControlInput" class="form-label">ชื่อผู้ขาย</label>
                            <input type="text" class="form-control" name="seller" id="defaultFormControlInput">
                        </div>
                        <div class="mb-3 text-center">
                            <button type="submit" class="btn btn-info">เพิ่มข้อมูล</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Page JS -->
<link href="../assets/css/toastr.min.css" rel="stylesheet" />
<script src="../assets/js/toastr.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
    $(document).ready(function() {
        var current = 0;
        $('.status0').click(function() {
            //var id = $(this).attr("id").substr($(this).attr("id").length - 1);
            var id = $(this).attr("id");
            var id2 = $(this).attr("data-table");

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
                $('#LargeInventory').append('<span id="v_' + id + '" class="badge bg-success mx-1 my-1">' + id + '</span>');
                $('#input_id').append('<input type="hidden" name="id_table[]" id="idt_' + id + '" value="' + id + '-' + id2 + '"></input>');
            }

        });
    });


    $("#add_reserve").on("submit", function(e) {
        e.preventDefault();
        var form = $(this)[0];
        if (form.checkValidity() === false) {
            toastr.error('กรุณากรอกข้อมูลให้ครบถ้วน');
        } else {
            $('.preloader').fadeIn();
            $.ajax({
                type: 'POST',
                url: 'pages/reserve/save.php?zone=add',
                data: new FormData(this),
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(data) {
                    $('.preloader').hide();
                    Swal.fire({
                        html: data.mess,
                        icon: 'success',
                        confirmButtonText: 'ตกลง',
                    }).then((result) => {
                        $('.preloader').fadeIn();
                        location.reload();
                    });
                }
            });
        }
        form.classList.add('was-validated');

    });
</script>