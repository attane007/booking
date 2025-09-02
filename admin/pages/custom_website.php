<?php
// Admin: Custom Website editor
// Saves settings to ../../settings/custom_website.json and uploads logo to ../../assets/img/custom_logo.png

// require admin context: if this page is requested directly by the public, redirect to admin login
if (!defined('IS_ADMIN') || IS_ADMIN !== true) {
  // redirect to admin index (login)
  header('Location: ../index.php');
  exit;
}

$msg = '';
$settingsFile = __DIR__ . '/../../settings/custom_website.json';
$logoPath = __DIR__ . '/../../assets/img/custom_logo.png';
$imgDir = __DIR__ . '/../../assets/img/';

// default (legacy) cover filename
$defaultCover = 'title.png';

// new cover directory under booking/datas/cover
$coverDir = __DIR__ . '/../../datas/cover/';
// payment QR directory
$qrDir = __DIR__ . '/../../datas/payment/';
// e-card / checklist directory
$ecardDir = __DIR__ . '/../../datas/e-card/';


// Load existing settings
$settings = array('site_title' => '', 'logo' => '', 'cover' => '', 'map_heading' => '', 'qr' => '',
  'bank_name' => '', 'account_number' => '', 'promptpay' => '', 'account_holder' => '', 'all_tables' => 200);
if (file_exists($settingsFile)) {
  $raw = file_get_contents($settingsFile);
  $decoded = json_decode($raw, true);
  if (is_array($decoded)) $settings = array_merge($settings, $decoded);
}

// determine current table money: prefer JSON settings, else fallback to config.php value or 2500
$current_table_money = 2500;
if (!empty($settings['table_money']) && is_numeric($settings['table_money'])) {
  $current_table_money = intval($settings['table_money']);
} else {
  $cfg_file = __DIR__ . '/../../settings/config.php';
  if (file_exists($cfg_file)) {
    $cfg = @file_get_contents($cfg_file);
    if (preg_match('/\$table_money\s*=\s*([0-9]+)/', $cfg, $m)) {
      $current_table_money = intval($m[1]);
    }
  }
}
// determine current all_tables: prefer JSON settings, else read from config.php or default 200
$current_all_tables = 200;
if (!empty($settings['all_tables']) && is_numeric($settings['all_tables'])) {
  $current_all_tables = intval($settings['all_tables']);
} else {
  $cfg_file = __DIR__ . '/../../settings/config.php';
  if (file_exists($cfg_file)) {
    $cfg = @file_get_contents($cfg_file);
    if (preg_match('/\$All_tables\s*=\s*([0-9]+)/', $cfg, $m2)) {
      $current_all_tables = intval($m2[1]);
    }
  }
}
// determine current table layout (rows/columns): prefer JSON settings, else config.php defaults
$current_num_row = 17;
$current_num_call = 12;
if (!empty($settings['num_row']) && is_numeric($settings['num_row'])) {
  $current_num_row = intval($settings['num_row']);
}
if (!empty($settings['num_call']) && is_numeric($settings['num_call'])) {
  $current_num_call = intval($settings['num_call']);
} else {
  // try read from config.php as fallback
  $cfg_file = __DIR__ . '/../../settings/config.php';
  if (file_exists($cfg_file)) {
    $cfg = @file_get_contents($cfg_file);
    if (preg_match('/\$num_row\s*=\s*([0-9]+)/', $cfg, $m3)) {
      $current_num_row = intval($m3[1]);
    }
    if (preg_match('/\$num_call\s*=\s*([0-9]+)/', $cfg, $m4)) {
      $current_num_call = intval($m4[1]);
    }
  }
}

// Handle POST save (only site_title and logo)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $site_title = isset($_POST['site_title']) ? $_POST['site_title'] : '';
  $map_heading = isset($_POST['map_heading']) ? $_POST['map_heading'] : '';
  $table_money = isset($_POST['table_money']) ? intval($_POST['table_money']) : $current_table_money;
  $all_tables = isset($_POST['all_tables']) ? intval($_POST['all_tables']) : $current_all_tables;
  $num_row = isset($_POST['num_row']) ? intval($_POST['num_row']) : $current_num_row;
  $num_call = isset($_POST['num_call']) ? intval($_POST['num_call']) : $current_num_call;
  $bank_name = isset($_POST['bank_name']) ? trim($_POST['bank_name']) : '';
  $account_number = isset($_POST['account_number']) ? trim($_POST['account_number']) : '';
  $promptpay = isset($_POST['promptpay']) ? trim($_POST['promptpay']) : '';
  $account_holder = isset($_POST['account_holder']) ? trim($_POST['account_holder']) : '';

  // Save logo if uploaded (keeps legacy filename custom_logo.png)
  $savedLogo = '';
  if (!empty($_FILES['logo']['tmp_name'])) {
    $tmp = $_FILES['logo']['tmp_name'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $tmp);
    finfo_close($finfo);
    $allowed = array('image/png','image/jpeg','image/gif');
    if (in_array($mime, $allowed)) {
      if (!is_dir($imgDir)) @mkdir($imgDir, 0755, true);
      $savedLogo = 'custom_logo.' . (strpos($mime, 'png') !== false ? 'png' : (strpos($mime,'jpeg')!==false ? 'jpg' : 'gif'));
      $dest = $imgDir . $savedLogo;
      if (!@move_uploaded_file($tmp, $dest)) {
        $msg = 'ไม่สามารถอัปโหลดรูปภาพได้';
        $savedLogo = '';
      }
    } else {
      $msg = 'ไฟล์โลโก้ต้องเป็นภาพ (png/jpg/gif)';
    }
  }

  // Save cover if uploaded — store with unique timestamped filename and persist path
  $savedCover = '';
  if (!empty($_FILES['cover']['tmp_name'])) {
    $tmpc = $_FILES['cover']['tmp_name'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimec = finfo_file($finfo, $tmpc);
    finfo_close($finfo);
    $allowedCover = array('image/png','image/jpeg','image/gif');
    if (in_array($mimec, $allowedCover)) {
      if (!is_dir($coverDir)) @mkdir($coverDir, 0755, true);
      $ext = strpos($mimec, 'png') !== false ? 'png' : (strpos($mimec,'jpeg')!==false ? 'jpg' : 'gif');
      // create filename with timestamp
      $savedCover = 'cover_' . time() . '.' . $ext;
      $destc = $coverDir . $savedCover;
      if (!@move_uploaded_file($tmpc, $destc)) {
        $msg = 'ไม่สามารถอัปโหลดภาพปกได้';
        $savedCover = '';
      }
    } else {
      $msg = 'ไฟล์ภาพปกต้องเป็นภาพ (png/jpg/gif)';
    }
  }

    // Save payment QR if uploaded — store with timestamp and persist filename
    $savedQR = '';
    if (!empty($_FILES['qr']['tmp_name'])) {
      $tmpq = $_FILES['qr']['tmp_name'];
      $finfo = finfo_open(FILEINFO_MIME_TYPE);
      $mimeq = finfo_file($finfo, $tmpq);
      finfo_close($finfo);
      $allowedQR = array('image/png','image/jpeg','image/gif');
      if (in_array($mimeq, $allowedQR)) {
        if (!is_dir($qrDir)) @mkdir($qrDir, 0755, true);
        $extq = strpos($mimeq, 'png') !== false ? 'png' : (strpos($mimeq,'jpeg')!==false ? 'jpg' : 'gif');
        $savedQR = 'qr_' . time() . '.' . $extq;
        $destq = $qrDir . $savedQR;
        if (!@move_uploaded_file($tmpq, $destq)) {
          $msg = 'ไม่สามารถอัปโหลดรูป QR ได้';
          $savedQR = '';
        }
      } else {
        $msg = 'ไฟล์ QR ต้องเป็นภาพ (png/jpg/gif)';
      }
    }

      // Save e-card checklist image if uploaded — store with timestamp and persist filename
      $savedChecklist = '';
      if (!empty($_FILES['checklist']['tmp_name'])) {
        $tmpch = $_FILES['checklist']['tmp_name'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimech = finfo_file($finfo, $tmpch);
        finfo_close($finfo);
        $allowedCh = array('image/png','image/jpeg','image/gif');
        if (in_array($mimech, $allowedCh)) {
          if (!is_dir($ecardDir)) @mkdir($ecardDir, 0755, true);
          $extch = strpos($mimech, 'png') !== false ? 'png' : (strpos($mimech,'jpeg')!==false ? 'jpg' : 'gif');
          $savedChecklist = 'checklist_' . time() . '.' . $extch;
          $destch = $ecardDir . $savedChecklist;
          if (!@move_uploaded_file($tmpch, $destch)) {
            $msg = 'ไม่สามารถอัปโหลดรูป Checklist ได้';
            $savedChecklist = '';
          }
        } else {
          $msg = 'ไฟล์ Checklist ต้องเป็นภาพ (png/jpg/gif)';
        }
      }

  // persist settings to JSON
  // choose saved filenames if newly uploaded, else keep existing settings
  $payload = array(
    'site_title' => $site_title,
    'logo' => $savedLogo ? $savedLogo : (!empty($settings['logo']) ? $settings['logo'] : ''),
    'cover' => $savedCover ? $savedCover : (!empty($settings['cover']) ? $settings['cover'] : $defaultCover),
    'qr' => $savedQR ? $savedQR : (!empty($settings['qr']) ? $settings['qr'] : ''),
  'checklist' => $savedChecklist ? $savedChecklist : (!empty($settings['checklist']) ? $settings['checklist'] : ''),
    'map_heading' => $map_heading,
  'table_money' => $table_money,
  'all_tables' => $all_tables,
  'num_row' => $num_row,
  'num_call' => $num_call,
    'bank_name' => $bank_name,
    'account_number' => $account_number,
    'promptpay' => $promptpay,
    'account_holder' => $account_holder
  );
  if (!is_dir(dirname($settingsFile))) @mkdir(dirname($settingsFile), 0755, true);
  if (file_put_contents($settingsFile, json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
    $msg = $msg ? $msg : 'บันทึกสำเร็จ';
    $settings = array_merge($settings, $payload);
  } else {
    $msg = 'ไม่สามารถบันทึกการตั้งค่าได้';
  }
}

?>

  <form method="post" enctype="multipart/form-data">
      <!-- General settings -->
      <div class="card mb-3">
        <div class="card-header"><strong>General</strong></div>
        <div class="card-body">
          <div class="mb-3">
            <label class="form-label">Site title</label>
            <input type="text" name="site_title" class="form-control" value="<?php echo e($settings['site_title']); ?>" />
          </div>

          <div class="mb-3">
            <label class="form-label">ข้อความหัวผังโต๊ะ (Map heading)</label>
            <input type="text" name="map_heading" class="form-control" value="<?php echo e(!empty($settings['map_heading']) ? $settings['map_heading'] : 'ผังโต๊ะ งาน 48ปี ราตรีม่วง-เหลือง'); ?>" />
          </div>

          <div class="mb-3">
            <label class="form-label">Logo (png/jpg/gif)</label>
            <input type="file" name="logo" class="form-control" accept="image/*" />
            <?php if (!empty($settings['logo']) && file_exists(__DIR__ . '/../../assets/img/' . $settings['logo'])): ?>
              <div style="margin-top:8px">
                <img src="../../assets/img/<?php echo e($settings['logo']); ?>" alt="logo" style="max-height:80px;" />
              </div>
            <?php endif; ?>
          </div>

          <div class="mb-3">
            <label class="form-label">ภาพปก (cover) - อัปโหลดเพื่อใช้อ้างอิงบนหน้าสาธารณะ</label>
            <input type="file" name="cover" class="form-control" accept="image/*" />
            <?php
              $showCover = '';
              if (!empty($settings['cover']) && file_exists($coverDir . $settings['cover'])) {
                $showCover = 'datas/cover/' . $settings['cover'];
              } elseif (file_exists(__DIR__ . '/../../assets/img/' . $defaultCover)) {
                $showCover = 'assets/img/' . $defaultCover;
              }
            ?>
            <?php if ($showCover): ?>
              <div style="margin-top:8px">
                <img src="../../<?php echo e($showCover); ?>" alt="cover" style="max-height:120px; width:auto;" />
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- E-Card & QR -->
      <div class="card mb-3">
        <div class="card-header"><strong>E-Card / QR</strong></div>
        <div class="card-body">
          <div class="mb-3">
            <label class="form-label">QR ชำระเงิน (png/jpg/gif) - อัปโหลดเพื่อเปลี่ยน</label>
            <input type="file" name="qr" class="form-control" accept="image/*" />
            <?php
              $showQR = '';
              if (!empty($settings['qr']) && file_exists($qrDir . $settings['qr'])) {
                $showQR = 'datas/payment/' . $settings['qr'];
              }
            ?>
            <?php if ($showQR): ?>
              <div style="margin-top:8px">
                <img src="../../<?php echo e($showQR); ?>" alt="qr" style="max-height:120px; width:auto;" />
              </div>
            <?php endif; ?>
          </div>

          <div class="mb-3">
            <label class="form-label">ภาพ Checklist / E-Card (png/jpg/gif) - อัปโหลดเพื่อเปลี่ยน</label>
            <input type="file" name="checklist" class="form-control" accept="image/*" />
            <?php
              $showChecklist = '';
              if (!empty($settings['checklist']) && file_exists($ecardDir . $settings['checklist'])) {
                $showChecklist = 'datas/e-card/' . $settings['checklist'];
              }
            ?>
            <?php if ($showChecklist): ?>
              <div style="margin-top:8px">
                <img src="../../<?php echo e($showChecklist); ?>" alt="checklist" style="max-height:120px; width:auto;" />
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- Pricing & Inventory -->
      <div class="card mb-3">
        <div class="card-header"><strong>Pricing & Inventory</strong></div>
        <div class="card-body">
          <div class="mb-3">
            <label class="form-label">ราคาโต๊ะ (ต่อโต๊ะ)</label>
            <input type="number" name="table_money" class="form-control" value="<?php echo e($current_table_money); ?>" min="0" />
            <div class="form-text">กำหนดราคาต่อโต๊ะ (หน่วย: บาท)</div>
          </div>

          <div class="mb-3">
            <label class="form-label">จำนวนโต๊ะทั้งหมด</label>
            <input type="number" name="all_tables" class="form-control" value="<?php echo e(!empty($settings['all_tables']) ? $settings['all_tables'] : $current_all_tables); ?>" min="1" />
            <div class="form-text">กำหนดจำนวนโต๊ะทั้งหมดที่ระบบจะถือเป็นขีดจำกัด</div>
          </div>
        </div>
      </div>

      <!-- Layout -->
      <div class="card mb-3">
        <div class="card-header"><strong>Layout</strong></div>
        <div class="card-body">
          <div class="mb-3">
            <label class="form-label">จำนวนแถว (rows)</label>
            <input type="number" name="num_row" class="form-control" value="<?php echo e(!empty($settings['num_row']) ? $settings['num_row'] : $current_num_row); ?>" min="1" />
          </div>
          <div class="mb-3">
            <label class="form-label">จำนวนคอลัมน์ต่อแถว (columns)</label>
            <input type="number" name="num_call" class="form-control" value="<?php echo e(!empty($settings['num_call']) ? $settings['num_call'] : $current_num_call); ?>" min="1" />
          </div>
        </div>
      </div>

      <!-- Payment Info -->
      <div class="card mb-3">
        <div class="card-header"><strong>ข้อมูลการชำระเงิน</strong></div>
        <div class="card-body">
          <div class="mb-3">
            <label class="form-label">ชื่อธนาคาร</label>
            <input type="text" name="bank_name" class="form-control" value="<?php echo e(!empty($settings['bank_name']) ? $settings['bank_name'] : 'ธนาคารไทยพาณิชย์'); ?>" />
          </div>
          <div class="mb-3">
            <label class="form-label">เลขบัญชี</label>
            <input type="text" name="account_number" class="form-control" value="<?php echo e(!empty($settings['account_number']) ? $settings['account_number'] : '401-831327-1'); ?>" />
          </div>
          <div class="mb-3">
            <label class="form-label">พร้อมเพย์</label>
            <input type="text" name="promptpay" class="form-control" value="<?php echo e(!empty($settings['promptpay']) ? $settings['promptpay'] : '089-4961507'); ?>" />
          </div>
          <div class="mb-3">
            <label class="form-label">ชื่อบัญชี</label>
            <input type="text" name="account_holder" class="form-control" value="<?php echo e(!empty($settings['account_holder']) ? $settings['account_holder'] : 'นิศากร ห้องกระจก'); ?>" />
          </div>
        </div>
      </div>

      <button class="btn btn-primary" type="submit">บันทึก</button>
    </form>
        <label class="form-label">เลขบัญชี</label>
        <input type="text" name="account_number" class="form-control" value="<?php echo e(!empty($settings['account_number']) ? $settings['account_number'] : '401-831327-1'); ?>" />
      </div>
      <div class="mb-3">
        <label class="form-label">พร้อมเพย์</label>
        <input type="text" name="promptpay" class="form-control" value="<?php echo e(!empty($settings['promptpay']) ? $settings['promptpay'] : '089-4961507'); ?>" />
      </div>
      <div class="mb-3">
        <label class="form-label">ชื่อบัญชี</label>
        <input type="text" name="account_holder" class="form-control" value="<?php echo e(!empty($settings['account_holder']) ? $settings['account_holder'] : 'นิศากร ห้องกระจก'); ?>" />
      </div>

      <button class="btn btn-primary" type="submit">บันทึก</button>
    </form>
  </div>
</div>
