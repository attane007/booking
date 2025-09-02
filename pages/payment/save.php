<?php

include_once('../../load.php');
$result = array();
switch ($zone) {
        //เพิ่มรายการ
    case 'step1':
        $date_buy = Date("Y-m-d H:i:s");
        $date_del = Date("Y-m-d H:i:s", strtotime('+5 minutes'));
        $time_cookie = time();
        $cookie_ = encryptCookie($time_cookie);
        for ($i = 0; $i < count($_POST["id_table"]); $i++) {

            // build id_table and name_table for this iteration
            $id_table = "T_" . $_POST["id_table"][$i];
            $name_table = $_POST["id_table"][$i];

            //ตรวจสอบว่ามีคนจองหรือยัง
            $sql_reserve = select("select * from reserve where id_table='$id_table' ");
            if (count($sql_reserve) <= 0) {
                $result['status'] = "success";
                $data = array(
                    'id_table'      => $id_table,
                    'name_table'    => $name_table,
                    'name'          => $_POST['name_b'],
                    'tel'           => isset($_POST['tel']) ? $_POST['tel'] : '',
                    'status'        => '1',
                    'status_pay'    => '0',
                    'date_buy'      => $date_buy,
                    'seller'        => "ระบบออนไลน์",
                    'cookie_'        => $cookie_,
                    'date_del'      => $date_del
                );
                insert('reserve', $data);
                // make cookie available site-wide (use root path)
                setcookie("payment", $cookie_, time() + (15 * 60), '/');  //15 นาที
                // also return cookie value in JSON so client can set it immediately if needed
                $result['cookie'] = $cookie_;

            } else {
                $result['status'] = "error";
                $result['mess'] .= "โต๊ะ ".$name_table . " ถูกจองไปแล้ว หรือมีผู้อื่นกำลังจองอยู่<br>";
            }
        }
        echo json_encode($result);
        break;

    case 'step2':
        if (!isset($_COOKIE['payment'])) {
            $result['status'] = "error";
            $result['mess'] = "คุณไม่ได้ยืนยันการซื้อในเวลาที่กำหนด";
        } else {

            $sql_reserve = select("select * from reserve where cookie_='$_COOKIE[payment]' ");
            if (count($sql_reserve) <= 0) {
                $result['status'] = "error";
                $result['mess'] = "คุณไม่ได้ยืนยันการซื้อในเวลาที่กำหนด";
            } else {
                $date_buy = Date("Y-m-d H:i:s");
                $date_buy2 = Date("YmdHis");
                $payment = encryptCookie($date_buy2);
                // อัพโหลดรูป — เพิ่มการตรวจสอบเพื่อดีบักปัญหาอัพโหลด
                if (!isset($_FILES['file_slip'])) {
                    $result['status'] = 'error';
                    $result['mess'] = 'ไม่พบไฟล์ที่ส่งมา (input name="file_slip")';
                    echo json_encode($result);
                    exit;
                }

                $file = $_FILES['file_slip'];
                if ($file['error'] !== UPLOAD_ERR_OK) {
                    $errCode = $file['error'];
                    $errMsg = 'เกิดข้อผิดพลาดในการอัพโหลดไฟล์ (error code: ' . $errCode . ')';
                    // Map common PHP upload errors to friendly messages
                    $map = [
                        UPLOAD_ERR_INI_SIZE => 'ไฟล์ใหญ่เกิน upload_max_filesize ใน php.ini',
                        UPLOAD_ERR_FORM_SIZE => 'ไฟล์ใหญ่เกิน MAX_FILE_SIZE ที่ฟอร์มกำหนด',
                        UPLOAD_ERR_PARTIAL => 'ไฟล์อัพโหลดไม่สมบูรณ์',
                        UPLOAD_ERR_NO_FILE => 'ไม่มีไฟล์ถูกส่งมา',
                        UPLOAD_ERR_NO_TMP_DIR => 'ไม่พบโฟลเดอร์ชั่วคราว',
                        UPLOAD_ERR_CANT_WRITE => 'เขียนไฟล์ลงดิสก์ไม่สำเร็จ',
                        UPLOAD_ERR_EXTENSION => 'ถูกบล็อกโดยส่วนขยายของ PHP'
                    ];
                    if (isset($map[$errCode])) $errMsg .= '\n' . $map[$errCode];

                    $result['status'] = 'error';
                    $result['mess'] = $errMsg;
                    echo json_encode($result);
                    exit;
                }

                $tmp = $file['tmp_name'];
                if (!is_uploaded_file($tmp)) {
                    $result['status'] = 'error';
                    $result['mess'] = 'ไฟล์ที่รับมาไม่ใช่การอัพโหลดจากฟอร์ม (is_uploaded_file() ล้มเหลว)';
                    echo json_encode($result);
                    exit;
                }

                // เตรียมพาธจัดเก็บ
                $uploadDir = __DIR__ . '/../../datas/payment/';
                if (!is_dir($uploadDir)) {
                    @mkdir($uploadDir, 0755, true);
                }

                $fileNewName = $date_buy2 . ".jpg";
                $filePath = $uploadDir . $fileNewName;
                $moved = move_uploaded_file($tmp, $filePath);
                if ($moved !== true) {
                    $result['status'] = 'error';
                    $result['mess'] = 'การย้ายไฟล์ล้มเหลว (move_uploaded_file คืนค่า false)';
                    // include a small debug hint
                    $result['debug'] = [
                        'tmp_name' => $file['tmp_name'],
                        'size' => $file['size'],
                        'upload_dir_exists' => is_dir($uploadDir),
                        'upload_dir_writable' => is_writable($uploadDir)
                    ];
                    echo json_encode($result);
                    exit;
                }

                for ($i = 0; $i < count($sql_reserve); $i++) {
                    $data_ = $sql_reserve[$i];
                    $fileNewName = $data_['id_table'] . "_" . $date_buy2 . ".jpg";
                    $filePath2 = $uploadDir . $fileNewName;
                    @copy($filePath, $filePath2);
                    $table .= $data_['name_table']." ";
                    $name_p = $data_['name'];
                    $tel    = $_POST['tel'];

                    $data = array(
                        'name'          => $_POST['name_b'],
                        'tel'           => $_POST['tel'],
                        'email'         => $_POST['email'],
                        'status'        => '3',
                        'status_pay'    => '1',
                        'date_buy'      => $date_buy,
                        'payment'      => $payment,
                        'cookie_'       => '',
                        'date_del'      => ''
                    );
                    update('reserve', $data, "id_table='" . $data_['id_table'] . "'");
                    
                }
                

                $result['status'] = "success";
                $result['mess'] = "ยืนยันการซื้อสำเร็จ<br>รอการตรวจสอบจากเจ้าหน้าที่<br>หากถูกต้องสถานะโต๊ะจะเปลี่ยนเป็นสีแดง";
                // make cookie available site-wide (use root path)
                setcookie("payment_success", $payment, time() + 3600, '/');  //1 ชั่วโมง
                @unlink($filePath);
            }
        }
        echo json_encode($result);
        break;




    case 'add':
        for ($i = 0; $i < count($_POST["id_table"]); $i++) {
            list($id_table, $name_table) = explode("-", $_POST["id_table"][$i]);
            $date_buy = Date("Y-m-d H:i:s");
            $data = array(
                'id_table'      => $id_table,
                'name_table'    => $name_table,
                'name'          => $_POST['name_b'],
                'tel'           => isset($_POST['tel']) ? $_POST['tel'] : '',
                'status'        => $_POST['status'],
                'status_pay'    => '0',
                'date_buy'      => $date_buy,
                'seller'        => $_POST['seller']
            );

            $sql_reserve = select("select * from reserve where id_table='$id_table' ");
            if (count($sql_reserve) <= 0) {
                insert('reserve', $data);
                $result['mess'] .= $id_table . " บันทึกสำเร็จ<br>";
            } else {
                $result['mess'] .= "<span class='text-danger'>" . $id_table . " ไม่สามารถจองได้</span><br>";
            }
        }
        echo json_encode($result);
        break;


}
