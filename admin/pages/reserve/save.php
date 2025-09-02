<?php

include_once('../../../load.php');
$result = array();
switch ($zone) {
        //เพิ่มรายการ
    case 'add':
        //list($id_table, $name_table) = explode("-", $_POST["id_table"][$i]);
        $date_buy = Date("Y-m-d H:i:s");

        $name_t = $_POST['name_t'];

        $sql_reserve = select("select * from reserve where id_table='$_POST[id_t]' ");
        $status_pay = $_POST['status'] == 4 ? 1 : "0";

        if (count($sql_reserve) <= 0) {
            $data = array(
                'id_table'      => $_POST['id_t'],
                'name_table'    => $name_t,
                'name'          => $_POST['name_b'],
                'tel'           => $_POST['tel'],
                'email'         => $_POST['email'],
                'status'        => $_POST['status'],
                'status_pay'    =>  $status_pay,
                'date_buy'      => $date_buy,
                'seller'        => $_POST['seller']
            );
            insert('reserve', $data);
            $result['status'] = "success";
            $result['mess'] = "โต๊ะ " . $name_t . " บันทึกข้อมูลสำเร็จ<br>";
        } else {
            if (empty($_POST['cal_'])) {
                $data2 = array(
                    'name_table'    => $name_t,
                    'name'          => $_POST['name_b'],
                    'tel'           => $_POST['tel'],
                    'email'         => $_POST['email'],
                    'status'        => $_POST['status'],
                    'status_pay'    =>  $status_pay,
                    'seller'        => $_POST['seller']
                );
                $result['mess'] = "โต๊ะ " . $name_t . " แก้ไขข้อมูลสำเร็จ";
                update('reserve', $data2, "id_table='" . $_POST['id_t'] . "'");
            } else {
                @delete('reserve', "id_table='" . $_POST['id_t'] . "' ");
                $result['mess'] = "โต๊ะ " . $name_t . " ยกเลิกสำเร็จ";
            }

            $result['status'] = "success";
        }
        echo json_encode($result);
        break;
}
