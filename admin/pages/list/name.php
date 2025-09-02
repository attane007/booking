
<div class="row">
	<div class="col-12 col-lg-12 order-2 order-md-3 order-lg-2 mb-4">
		<div class="card">
			<div class="row row-bordered g-0">
				<div class="col-md-12">
					<h5 class="card-header m-0 me-2 pb-3">รายการจำหน่าย/จองโต๊ะ</h5>
					<div class="table-responsive text-nowrap">
						<table class="table table-striped">
							<thead>
								<tr>
									<th>ไอดี/เลขโต๊ะ</th>
									<th>ผู้จอง</th>
									<th>ผู้ขาย</th>
									<th>วันที่</th>
								</tr>
							</thead>
							<tbody class="table-border-bottom-0">
								<?php
								$sql_reserve = select("select * from reserve where (status!='0') order by name_table+0 asc");
								for ($i = 0; $i < count($sql_reserve); $i++) {
									$data_r = $sql_reserve[$i];
								?>
									<tr>
										<td><strong>โต๊ะ <?= str_pad($data_r['name_table'], 3, "0", STR_PAD_LEFT);; ?> <span class="badge rounded-pill bg-label-<?= $status_table_css[$data_r['status']]; ?>"><?= $status_table[$data_r['status']]; ?></span></strong></td>
										<td><?= $data_r['name']; ?></td>
										<td><?= $data_r['seller']; ?></td>
										<td><?=DatetoThai($data_r['date_buy'],'1995-02-20 00:00:00>>20ก.พ.2538 00:00:00'); ?></td>
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