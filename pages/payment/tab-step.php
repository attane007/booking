<style type="text/css">
    .wrapper-progressBar {
        width: 100%
    }

    .progressBar {
        padding-left: 0px;
    }

    .progressBar li {
        list-style-type: none;
        float: left;
        width: 33.33%;
        position: relative;
        text-align: center;
        color: #222;
    }

    .progressBar li:before {
        content: " ";
        line-height: 30px;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        border: 1px solid #fff;
        display: block;
        text-align: center;
        margin: 0 auto 10px;
        background-color: white
    }

    .progressBar li:after {
        content: "";
        position: absolute;
        width: 100%;
        height: 4px;
        background-color: #fff;
        top: 14px;
        left: -50%;
        z-index: -1;
    }

    .progressBar li:first-child:after {
        content: none;
    }

    .progressBar li.active {
        color: #222;
    }

    .progressBar li.active:before {
        border-color: #FFF;
        background-color: #ffab00
    }

    .progressBar .active:after {
        background-color: #ffab00;
    }
</style>
<?php

switch ($op) {
    case '':
    case 'index':
        $tap_active1 = "active";
        break;
    case 'payment-pay':
        $tap_active1 = "active";
        $tap_active2 = "active";
        break;
    case 'payment-success':
        $tap_active1 = "active";
        $tap_active2 = "active";
        $tap_active3 = "active";
        break;
}

?>

<div class="row mb-3">
    <div class="col-lg-12 col-12  block">
        <div class="wrapper-progressBar">
            <ul class="progressBar">
                <li class="<?= $tap_active1; ?>" onclick="location.href='?op=index'">เลือกโต๊ะ</li>
                <li class="<?= $tap_active2; ?>">ชำระเงิน</li>
                <li class="<?= $tap_active3; ?>">ลิ้นสุด</li>
            </ul>
        </div>
    </div>
</div>