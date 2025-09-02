<?php
date_default_timezone_set('Asia/Bangkok');
//MySQL Connect
define("DB_HOST","localhost");
define("DB_NAME","bw_booking");
define("DB_USERNAME","root");
define("DB_PASSWORD","pass");


define("ISO","utf-8");
error_reporting(1);

// MySQL-only connection. Fail fast if MySQL is unavailable.
$mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
if ($mysqli->connect_errno) {
	// Fail early so issues are visible during deployment.
	die('MySQL connection error: ' . $mysqli->connect_error);
}
$mysqli->query("SET NAMES UTF8");

list($x,$url) = explode('/',$_SERVER["REQUEST_URI"]);


//รับค่า
$op = !empty($_GET['op']) ? $_GET['op'] : "index";
// Support legacy or pretty URLs like /payment-pay.html -> map to ?op=payment-pay
if (empty($_GET['op'])) {
	$requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
	$last = basename($requestPath);
	if (preg_match('/^([a-zA-Z0-9\-_]+)\.html$/', $last, $m)) {
		$op = $m[1];
	}
}
@list($g_fol,$g_file,$get1,$get2,$get3) = explode("-",$op);
@$zone = !empty($_GET['zone']) ? $_GET['zone'] : "";

//จำนวนแถวตอนลึก
$alphabet = array('', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J','K','L','M','N','O','P','Q');
//,'R','S','T','U','V','W','X','Y','Z'
$num_row = 17;
$num_call = 12;
$table_money = 2500; //ราคาโต๊ะ (default)
$All_tables = 200;

// Read overrides from settings/custom_website.json (single read)
$cw_file = __DIR__ . '/custom_website.json';
if (file_exists($cw_file)) {
	$cw_raw = @file_get_contents($cw_file);
	$cw = @json_decode($cw_raw, true);
	if (is_array($cw)) {
		if (!empty($cw['table_money']) && is_numeric($cw['table_money'])) $table_money = intval($cw['table_money']);
		if (!empty($cw['num_row']) && is_numeric($cw['num_row'])) $num_row = intval($cw['num_row']);
		if (!empty($cw['num_call']) && is_numeric($cw['num_call'])) $num_call = intval($cw['num_call']);
		if (!empty($cw['all_tables']) && is_numeric($cw['all_tables'])) $All_tables = intval($cw['all_tables']);
	}
}

$status_table = array(
	'0' => "ว่าง",
	'1' => "กำลังจอง",
	'2' => "ติดจอง",
	'3' => "รอตรวจสอบ",
	'4' => "ขายแล้ว",
);
$status_table_css = array(
	'0' => "success",
	'1' => "primary",
	'2' => "warning",
	'3' => "primary",
	'4' => "danger",
);

$status_pay = array(
	'0' => "ยังไม่ชำระเงิน",
	'1' => "ชำระเงินแล้ว",
	'2' => "ชำระหน้างาน",
);
$status_pay_css = array(
	'0' => "danger",
	'1' => "success",
	'2' => "primery",
)
?>