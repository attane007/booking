<?php
include_once('../../../load.php');
$id = $_GET['id'];
$sql_reserve = select("select * from reserve where id_table='$id' ");
$data_r = $sql_reserve[0];
$status = count($sql_reserve) <= 0 ? "ว่าง" : $status_table[$data_r['status']];
$status_css = count($sql_reserve) <= 0 ? $status_table_css[0] : $status_table_css[$data_r['status']];
?>
<div class="alert alert-<?= $status_css; ?>" role="alert">สถานะ <?= $status; ?></div>
<?php
if (count($sql_reserve) <= 0) {
}
?>

<div class="row">
    <form id="add_" novalidate>
        <input type="hidden" id="id_t" name="id_t">
        <input type="hidden" id="name_t" name="name_t">
        <?php if (!empty($data_r['payment'])) { ?>
            <div class="mb-3 text-center">
                <img class="" style="width: 50%;" src="../datas/payment/<?=$data_r['id_table'];?>_<?=decryptCookie($data_r['payment']);?>.jpg">
            </div>
        <?php } ?>
        <div class="col-md mb-3">
            <small class="fw-semibold d-block">ประเภท</small>
            <div class="form-check form-check-inline mt-0">
                <input class="form-check-input" type="radio" name="status" id="inlineRadio1" value="2" <?= checked('2', $data_r['status']); ?> required>
                <label class="form-check-label" for="inlineRadio1"> จอง</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="status" id="inlineRadio2" value="4" <?= checked('4', $data_r['status']); ?> required>
                <label class="form-check-label" for="inlineRadio2"> ซื้อ</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="status" id="inlineRadio3" value="3" <?= checked('3', $data_r['status']); ?> required>
                <label class="form-check-label" for="inlineRadio3"> รอตรวจสอบ</label>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">ชื่อผู้ซื้อ</label>
            <input type="text" class="form-control" name="name_b" value="<?= $data_r['name'] ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">เบอร์ติดต่อ</label>
            <input type="text" class="form-control" name="tel" value="<?= $data_r['tel'] ?>" maxlength="10" >
        </div>
        <div class="mb-3">
            <label class="form-label">อีเมล</label>
            <input type="email" class="form-control" name="email" value="<?= $data_r['email'] ?>" >
        </div>
        <div class="mb-3">
            <label class="form-label">ชื่อผู้ขาย</label>
            <input type="text" class="form-control" name="seller" value="<?= $data_r['seller'] ?>" required>
        </div>
        <div class="form-check form-switch mb-2">
            <input class="form-check-input" type="checkbox" name="cal_" id="flexSwitchCheckDefault">
            <label class="form-check-label" for="flexSwitchCheckDefault">ยกเลิกโต๊ะ</label>
        </div>
        <div class="mb-3 text-center">
            <button type="submit" class="btn btn-info">บันทึก</button>
        </div>
    </form>
</div>

<link href="../assets/css/toastr.min.css" rel="stylesheet" />
<script src="../assets/js/toastr.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
    $("#add_").submit(function(e) {
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
                     $('.modal-backdrop').fadeOut();
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