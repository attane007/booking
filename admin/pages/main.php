
<?php
$sql_reserve1 = select("select * from reserve where status='2' ");
$sql_reserve2 = select("select * from reserve where status='4' ");
//ยอดจอง
$data_1 = count($sql_reserve1);
//ยอดขาย
$data_2 = count($sql_reserve2);
//ยอดรอขาย
$data_3 = $All_tables-($data_1+$data_2);
?>
<div class="row">
	<div class="col-lg-3 col-12 mb-4">
		<div class="card">
			<div class="card-body">
				<div class="d-flex align-items-center">
					<div class=" flex-shrink-0">
						<img src="../assets/img/chair.png" style="width: 100px;" />
					</div>
					<div class="p-3">
						<h5 class="fw-semibold d-block mb-1">จำนวนโต๊ะทั้งหมด</h5>
						<h5 class="card-title mb-2"><span class="text-primary h2"><?=$All_tables;?></span> โต๊ะ</h5>
					</div> 
				</div>
			</div>
		</div>
	</div>

	<div class="col-lg-3 col-12 mb-4">
		<div class="card">
			<div class="card-body">
				<div class="d-flex align-items-center">
					<div class=" flex-shrink-0">
						<img src="../assets/img/reserve.png" style="width: 100px;" />
					</div>
					<div class="p-3">
						<h5 class="fw-semibold d-block mb-1">จำนวนที่จอง</h5>
						<h5 class="card-title mb-2"><span class="text-warning h2"><?=$data_1;?></span> โต๊ะ</h5>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-lg-3 col-12 mb-4">
		<div class="card">
			<div class="card-body">
				<div class="d-flex align-items-center">
					<div class=" flex-shrink-0">
						<img src="../assets/img/salary.png" style="width: 100px;" />
					</div>
					<div class="p-3">
						<h5 class="fw-semibold d-block mb-1">จำนวนที่ขาย</h5>
						<h5 class="card-title mb-2"><span class="text-success h2"><?=$data_2;?></span> โต๊ะ</h5>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-lg-3 col-12 mb-4">
		<div class="card">
			<div class="card-body">
				<div class="d-flex align-items-center">
					<div class=" flex-shrink-0">
						<img src="../assets/img/shopping-online.png" style="width: 100px;" />
					</div>
					<div class="p-3">
						<h5 class="fw-semibold d-block mb-1">รอขาย</h5>
						<h5 class="card-title mb-2"><span class="text-dark h2"><?=$data_3;?></span> โต๊ะ</h5>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card mb-4">
            <div class="card-body">
                <center>
                <?php
                    // Load custom website settings and use map_heading when present
                    $cw = function_exists('load_custom_website') ? load_custom_website() : [];
                    $map_heading = !empty($cw['map_heading']) ? $cw['map_heading'] : 'ผังโต๊ะอาหาร "69ปี คืนสู่เหย้า ชาวเหลืองแดง"';
                ?>
                <h3 class="mt-3 mb-5"><?php echo htmlspecialchars($map_heading, ENT_QUOTES, 'UTF-8'); ?></h3>
                </center>
                <div class="row mb-3 explain text-center">
                    <div class="col p-0"><button type="button" class="btn status0"></button> <br><?= $status_table[0]; ?> </div>
                    <div class="col p-0"><button type="button" class="btn status1"></button> <br><?= $status_table[1]; ?> </div>
                    <div class="col p-0"><button type="button" class="btn status2"></button> <br><?= $status_table[2]; ?> </div>
                    <div class="col p-0"><button type="button" class="btn status3"></button> <br><?= $status_table[3]; ?></div>
                    <div class="col p-0"><button type="button" class="btn status4"></button> <br><?= $status_table[4]; ?></div>

                </div>

                <div class="seatmap-wrapper">
                    <div class="seatmap table-overflow">
                    <table id="table_work" class="table-seatmap" style="width: 100%;">
                            <tbody>
                                <tr>
                                    <td colspan="14" class="text-center">
                                        <img src="../assets/img/stage.png" width="100%" alt="">
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

                                            if($alphabet[$r]=='Q'){
                                                $t_number = $t_number -2;
                                                if($t_number>=191 && $t_number<=192 OR $t_number>200 ){
                                                    $css_h = "d-none";
                                                }else{
                                                    $css_h = "";
                                                }
                                            }
                                            $id_table = "T_" . $t_number;
                                            $sql_reserve = select("select * from reserve where id_table='$id_table' ");
                                            $data_r = $sql_reserve[0];
                                            $status = empty($data_r['status']) ? 'status0' : 'status' . $data_r['status'];
                                            $name_table = ($data_r['name']==="VIP") ? "VIP" : $t_number;

                                            

                                        ?>
                                            <td>
                                                    <button type="button" id="detail" class="<?= $css_h;?> btn  <?= $status; ?>"
                                                    data-id="<?= $id_table; ?>"
                                                    data-name="<?= $t_number; ?>"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalCenter"><?= $name_table; ?></button>
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



                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include('pages/list/name.php'); ?>


<div class="modal fade" id="modalCenter" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCenterTitle">รายละเอียด โต๊ะ <span id="num_t"></span></h5>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div id="body_detail" class="modal-body">
            </div>
        </div>
    </div>
</div>

<!-- Page JS -->
<!-- Page JS -->

<script type="text/javascript">
</script>
<script type="text/javascript">
    function initDetailHandler() {
        $(document).off('click', '#detail').on('click', '#detail', function() {
            let id = $(this).data('id');
            let name = $(this).data('name');
            $.ajax({
                url: 'pages/reserve/detail.php?id=' + id,
                cache: false,
                success: function(result) {
                    $("#body_detail").html(result);
                    $("#num_t").text(name);
                    $("#id_t").val(id);
                    $("#name_t").val(name);
                }
            });
        });
    }

    if (typeof window.jQuery !== 'undefined') {
        initDetailHandler();
    } else {
        // Fallback: dynamically load jQuery then initialize
        var script = document.createElement('script');
        script.src = '../assets/vendor/libs/jquery/jquery.js';
        script.onload = function() {
            initDetailHandler();
        };
        script.onerror = function() {
            console.error('Failed to load jQuery from ../assets/vendor/libs/jquery/jquery.js');
        };
        document.head.appendChild(script);
    }
</script>