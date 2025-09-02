<?php
date_default_timezone_set('Asia/Bangkok');
//MySQL Connect
define("DB_HOST","localhost");
define("DB_NAME","bw_booking");
define("DB_USERNAME","root");
define("DB_PASSWORD","pass");


define("ISO","utf-8");
error_reporting(1);

// Try MySQL first; if it fails, fall back to a local SQLite file for dev
$use_sqlite = false;
try {
	// construct mysqli inside try so we can catch mysqli_sql_exception (thrown on some PHP configs)
	$mysqli = new mysqli(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_NAME);
	if ($mysqli->connect_errno) {
		// can't connect to MySQL
		$use_sqlite = true;
	} else {
		// try a lightweight query to ensure the connection is usable
		// mysqli::ping is deprecated in PHP 8.4 — use a simple SELECT instead
		try {
			$res = $mysqli->query('SELECT 1');
			if ($res === false) {
				$use_sqlite = true;
			} else {
				$mysqli->query("SET NAMES UTF8");
				// free result if it's a mysqli_result
				if ($res instanceof mysqli_result) $res->free();
			}
		} catch (mysqli_sql_exception $e) {
			$use_sqlite = true;
		}
	}
} catch (mysqli_sql_exception $e) {
	// mysqli raised an exception (e.g., connection refused) — fall back to SQLite
	$use_sqlite = true;
} catch (Exception $e) {
	// any other exception
	$use_sqlite = true;
}

if ($use_sqlite) {
	// SQLite fallback (for local dev)
	$dbFile = __DIR__ . '/../datas/booking.sqlite';
	if (!is_dir(dirname($dbFile))) mkdir(dirname($dbFile), 0755, true);
	if (!file_exists($dbFile)) {
		// create empty sqlite file; you can import booking_sqlite.sql into it
		touch($dbFile);
	}
	try {
		$pdo = new PDO('sqlite:' . $dbFile);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch (Exception $e) {
		die('SQLite open error: ' . $e->getMessage());
	}

	if (!class_exists('SimpleSQLiteResult')) {
		class SimpleSQLiteResult {
			private $stmt;
			public function __construct($stmt) { $this->stmt = $stmt; }
			public function fetch_assoc() { $row = $this->stmt->fetch(PDO::FETCH_ASSOC); return $row ? $row : null; }
			public function fetch_all_assoc() { return $this->stmt->fetchAll(PDO::FETCH_ASSOC); }
			public function num_rows() { $rows = $this->stmt->fetchAll(PDO::FETCH_ASSOC); return is_array($rows) ? count($rows) : 0; }
		}
	}

	if (!class_exists('SimpleSQLite')) {
		class SimpleSQLite {
			private $pdo;
			public function __construct($pdo) { $this->pdo = $pdo; }
			public function query($sql) {
				$stmt = $this->pdo->query($sql);
				return new SimpleSQLiteResult($stmt);
			}
			public function real_escape_string($s) { return str_replace("'", "''", $s); }
			public function insert_id() { return $this->pdo->lastInsertId(); }
			public function prepare($sql) { return $this->pdo->prepare($sql); }
		}
	}

	$mysqli = new SimpleSQLite($pdo);
}

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
$table_money = 2800; //ราคาโต๊ะ
$All_tables = 200;

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