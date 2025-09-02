
<div class="row">
    <div class="col text-center">
        <img src="assets/img/title.png" class="img_title" alt="">
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card mb-4">
            <div class="card-body">
                <center>
                    <h3 class="mt-3 mb-5">ผังโต๊ะอาหาร "69ปี คืนสู่เหย้า ชาวเหลืองแดง"</h3>
                </center>
                <div class="row mb-3">
                    <div class="col text-center explain d-flex justify-content-center">
                        <div class="col p-0"><button type="button" class="btn status0"></button> <br><?= $status_table[0]; ?> </div>
                        <!--<div class="col p-0"><button type="button" class="btn status1"></button> <br><?= $status_table[1]; ?> </div>--->
                        <div class="col p-0"><button type="button" class="btn status2"></button> <br><?= $status_table[2]; ?> </div>
                        <!--<div class="col p-0"><button type="button" class="btn status3"></button> <br><?= $status_table[3]; ?></div>--->
                        <div class="col p-0"><button type="button" class="btn status4"></button> <br><?= $status_table[4]; ?></div>

                    </div>
                </div>
                
                <div class="seatmap-wrapper d-none*">
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

                                            // adjust numbering for row Q (legacy layout)
                                            if ($alphabet[$r] == 'Q') {
                                                $t_number = $t_number - 2;
                                            }
                                            // hide tables that are outside the configured total ($All_tables)
                                            if (!empty($All_tables) && is_numeric($All_tables) && $t_number > intval($All_tables)) {
                                                $css_h = "d-none";
                                            } else {
                                                $css_h = "";
                                            }
                                            $id_table = "T_" . $t_number;
                                            $sql_reserve = select("select * from reserve where id_table='$id_table' ");
                                            $data_r = $sql_reserve[0];
                                            $caption = "<b>" . $status_table[$data_r['status']] . "</b><br><span>" . $data_r['name'] . "</span>";

                                            

                                        ?>
                                            <td>
                                                <?php if ($data_r['status'] != 0) { 
                                                     $name_table = ($data_r['name']==="VIP") ? "VIP" : $t_number;
                                                     ?>
                                                    <button type="button" class="<?= $css_h;?> btn btn_table status<?= $data_r['status']; ?>"
                                                        data-bs-toggle="tooltip"
                                                        data-bs-offset="0,4"
                                                        data-bs-placement="bottom"
                                                        data-bs-html="true"
                                                        title="<?= $caption; ?>"><?= $name_table; ?></button>
                                                <?php } else { 
                                                     ?>
                                                    <button type="button" id="<?= $id_table; ?>" class="<?= $css_h;?> btn btn_table status0">
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
                        <p class="text-center text-danger mt-3">**ผังโต๊ะอาจเปลี่ยนแปลงได้ตามความเหมาะสม**</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>