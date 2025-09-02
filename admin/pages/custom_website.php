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


// Load existing settings
$settings = array('site_title' => '', 'logo' => '', 'cover' => '', 'map_heading' => '');
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

// Handle POST save (only site_title and logo)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $site_title = isset($_POST['site_title']) ? $_POST['site_title'] : '';
  $map_heading = isset($_POST['map_heading']) ? $_POST['map_heading'] : '';
  $table_money = isset($_POST['table_money']) ? intval($_POST['table_money']) : $current_table_money;

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

  // persist settings to JSON
  // choose saved filenames if newly uploaded, else keep existing settings
  $payload = array(
    'site_title' => $site_title,
    'logo' => $savedLogo ? $savedLogo : (!empty($settings['logo']) ? $settings['logo'] : ''),
    'cover' => $savedCover ? $savedCover : (!empty($settings['cover']) ? $settings['cover'] : $defaultCover),
    'map_heading' => $map_heading
  , 'table_money' => $table_money
  );
  if (!is_dir(dirname($settingsFile))) @mkdir(dirname($settingsFile), 0755, true);
  if (file_put_contents($settingsFile, json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
    $msg = $msg ? $msg : 'บันทึกสำเร็จ';
    $settings = array_merge($settings, $payload);
  } else {
    $msg = 'ไม่สามารถบันทึกการตั้งค่าได้';
  }
}

// helper escape for output
function e($s) { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }

?>

<div class="card">
  <div class="card-header">
    <h5>แก้ไขเว็บไซต์ (Custom Website)</h5>
  </div>
  <div class="card-body">
    <?php if ($msg): ?>
      <div class="alert alert-info"><?php echo e($msg); ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
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

      <div class="mb-3">
        <label class="form-label">ราคาโต๊ะ (ต่อโต๊ะ)</label>
        <input type="number" name="table_money" class="form-control" value="<?php echo e($current_table_money); ?>" min="0" />
        <div class="form-text">กำหนดราคาต่อโต๊ะ (หน่วย: บาท)</div>
      </div>

      <button class="btn btn-primary" type="submit">บันทึก</button>
    </form>
  </div>
</div>
