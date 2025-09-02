<?php
// Encrypt cookie
function encryptCookie($value)
{

    $key = hex2bin(openssl_random_pseudo_bytes(4));

    $cipher = "aes-256-cbc";
    $ivlen = openssl_cipher_iv_length($cipher);
    $iv = openssl_random_pseudo_bytes($ivlen);

    $ciphertext = openssl_encrypt($value, $cipher, $key, 0, $iv);

    return (base64_encode($ciphertext . '::' . $iv . '::' . $key));
}

// Decrypt cookie
function decryptCookie($ciphertext)
{

    $cipher = "aes-256-cbc";

    list($encrypted_data, $iv, $key) = explode('::', base64_decode($ciphertext));
    return openssl_decrypt($encrypted_data, $cipher, $key, 0, $iv);
}
function clean($string)
{
    $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

    $string = preg_replace('/[^A-Za-z0-9ก-ฮ๐-๙เ.\-()]/', '', $string); // Removes special chars.
    return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
}

function base64($id, $type_)
{
    switch ($type_) {
        case 'en':
            $id = base64_encode($id);
            break;

        case 'de':
            $id = base64_decode($id);
            break;

        default:
            // code...
            break;
    }
    return $id;
}




//    ฟังก์ชันสำหรับการ update ข้อมูล
function update($table, $data, $where)
{
    global $mysqli;
    $modifs = "";
    $i = 1;
    // get column metadata for this table to handle numeric fields correctly
    $colMeta = array();
    $colsRes = $mysqli->query("SHOW COLUMNS FROM `$table`");
    if ($colsRes) {
        while ($col = $colsRes->fetch_assoc()) {
            $colMeta[$col['Field']] = $col;
        }
    }
    foreach ($data as $key => $val) {
        if ($i != 1) {
            $modifs .= ", ";
        }
        // If the column is numeric type, coerce/clean non-numeric input to a safe numeric value
        if (isset($colMeta[$key])) {
            $colType = strtolower($colMeta[$key]['Type']);
            if (preg_match('/int|decimal|numeric|float|double|bit/', $colType)) {
                // allow numeric values, otherwise strip non-digits (for phone numbers) or set 0
                if (is_numeric($val)) {
                    $modifs .= $key . '=' . $val;
                } else {
                    // strip non-digit characters; if empty, set to 0
                    $digits = preg_replace('/\D+/', '', (string)$val);
                    if ($digits === '') $digits = 0;
                    $modifs .= $key . '=' . $digits;
                }
                $i++;
                continue;
            }
        }
        // fallback: treat as string and escape
        $safe = $mysqli->real_escape_string($val);
        $modifs .= $key . ' = "' . $safe . '"';
        $i++;
    }
    $sql = ("UPDATE $table SET $modifs WHERE $where ");
    if ($mysqli->query($sql)) {
        return true;
    } else {
        die("SQL Error: <br>" . $sql . "<br>" . $mysqli->error);
        return false;
    }
}
//    ฟังก์ชัน select ข้อมูลในฐานข้อมูลมาแสดง
function select($sql)
{
    global $mysqli;
    $result = array();
    $res = $mysqli->query($sql) or die("SQL Error: <br>" . $sql . "<br>" . $mysqli->error);
    while ($data = $res->fetch_assoc()) {
        $result[] = $data;
    }
    return $result;
}
//    ฟังก์ชันสำหรับการ insert ข้อมูล
function insert($table, $data)
{
    global $mysqli;
    // Defensive: inspect table schema and auto-fill missing NOT NULL columns without a default
    // This prevents errors like "Field 'tel' doesn't have a default value" when callers omit fields.
    $colMeta = array();
    $colsRes = $mysqli->query("SHOW COLUMNS FROM `$table`");
    if ($colsRes) {
        while ($col = $colsRes->fetch_assoc()) {
            $field = $col['Field'];
            $colMeta[$field] = $col;
            $isNull = strtoupper($col['Null']) === 'YES';
            $hasDefault = !is_null($col['Default']);
            if (!$isNull && !$hasDefault && !array_key_exists($field, $data)) {
                // Provide a type-appropriate default for missing required fields to avoid SQL type errors
                $colType = strtolower($col['Type']);
                if (preg_match('/int|decimal|numeric|float|double|bit/', $colType)) {
                    $data[$field] = 0;
                } else {
                    // text/date/blob etc. use empty string as safe default
                    $data[$field] = '';
                }
            }
        }
    }
    $fields = "";
    $values = "";
    $i = 1;
    foreach ($data as $key => $val) {
        if ($i != 1) {
            $fields .= ", ";
            $values .= ", ";
        }
        $fields .= "$key";
        // If the column exists and is numeric, coerce empty strings to 0 to avoid SQL errors
        if (isset($colMeta[$key])) {
            $colType = strtolower($colMeta[$key]['Type']);
            if (($val === '' || is_null($val)) && preg_match('/int|decimal|numeric|float|double|bit/', $colType)) {
                $val = 0;
            }
        }
        $values .= "'" . $mysqli->real_escape_string($val) . "'";
        $i++;
    }
    $sql = "INSERT INTO $table ($fields) VALUES ($values)";
    if ($mysqli->query($sql)) {
        return true;
    } else {
        die("SQL Error: <br>" . $sql . "<br>" . $mysqli->error);
        return false;
    }
}
//    ฟังก์ชันสำหรับการ delete ข้อมูล
function delete($table, $where)
{
    global $mysqli;
    $sql = "DELETE FROM $table WHERE $where";
    if ($mysqli->query($sql)) {
        return true;
    } else {
        die("SQL Error: <br>" . $sql . "<br>" . $mysqli->error);
        return false;
    }
}









//เส้นทางหน้าเพจต่างๆ
function path_web($op)
{
    if ($op != 'index') {
        $url_page = explode("-", $op);
        $url_page = $url_page[0] . "/" . $url_page[1];
        $modpathfile = "pages/" . $url_page . ".php";
    } else {
        $modpathfile = "pages/main.php";
    }

    if (!file_exists($modpathfile)) {
        $modpathfile = "pages/404.php";
    }
    return $modpathfile;
}
//Active Menu Bar
function isActive($data)
{
    global $g_fol;
    return $g_fol === $data ? 'active open' : '';
}
function isActivePath($data)
{
    global $g_fol, $g_file;
    $path = $g_fol . '-' . $g_file . ".html";
    return $path === $data ? 'active' : '';
}

//แปลงวันที่
$dayTH = ['อาทิตย์', 'จันทร์', 'อังคาร', 'พุธ', 'พฤหัสบดี', 'ศุกร์', 'เสาร์'];
$monthTH = [null, 'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];
$monthTH_brev = [null, 'ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'];
function DatetoThai($strDate, $for)
{
    global $monthTH_brev, $monthTH;
    switch ($for) {

        case '2538-02-20>>20กุมภาพันธ์2538':
            @list($strYear, $strMonth, $strDay) = explode("-", $strDate);
            $strMonthThai = $monthTH[$strMonth - 0];
            $strDay = $strDay + 0;
            $strYear = $strYear;
            return "$strDay $strMonthThai $strYear";
            break;

        case '1995-02-20 00:00:00>>20ก.พ.2538 00:00:00':
            @list($date_, $time_) = explode(" ", $strDate);
            @list($strYear, $strMonth, $strDay) = explode("-", $date_);
            $strMonthThai = $monthTH[$strMonth - 0];
            $strDay = $strDay + 0;
            $strYear = $strYear+543;
            return "$strDay $strMonthThai $strYear $time_";
            break;
    }
}

//checked selected รายการ
function checked($text1, $text2)
{
    if ($text1 == $text2) {
        echo "checked";
    }
}
function selected($text1, $text2)
{
    if ($text1 == $text2) {
        echo "selected";
    }
}

// HTML-escape helper used in templates: e($str)
function e($str)
{
    if (is_null($str)) return '';
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

function status_reserve($id)
{
    $sql_reserve = select("select * from reserve where id_table='$id' ");
    if (count($sql_reserve) > 0) {
        $data = $sql_reserve[0];
        switch ($data['status']) {
            case '0':
                $sta = 'status0';
                break;

            case '1':
                $sta = 'status1';
                break;

            case '2':
                $sta = 'status2';
                break;
        }
    } else {
        $sta = 'status0';
    }

    return $sta;
}

//ตรวจสอบ วัน ว่าเลยกำหนดหรือยัง
function Overdue($date_end)
{
    $today = date("Y-m-d H:i:s");
    $num_day1 = strtotime($date_end) - strtotime($today);
    $system_offon = $num_day1 >= 0  ? "1" : "0";
    return $system_offon;
}

// Load custom website settings saved by admin
function load_custom_website()
{
    $settingsFile = __DIR__ . '/custom_website.json';
    if (file_exists($settingsFile)) {
        $raw = file_get_contents($settingsFile);
        $data = json_decode($raw, true);
        if (is_array($data)) return $data;
    }
    return array();
}
