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
$coverPath = __DIR__ . '/../../assets/img/title.png';

// Load existing settings
$settings = array('site_title' => '', 'logo' => '', 'cover' => '', 'map_heading' => '');
if (file_exists($settingsFile)) {
  $raw = file_get_contents($settingsFile);
  $decoded = json_decode($raw, true);
  if (is_array($decoded)) $settings = array_merge($settings, $decoded);
}

// Handle POST save (only site_title and logo)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $site_title = isset($_POST['site_title']) ? $_POST['site_title'] : '';
  $map_heading = isset($_POST['map_heading']) ? $_POST['map_heading'] : '';

  // Save logo if uploaded
  if (!empty($_FILES['logo']['tmp_name'])) {
    $tmp = $_FILES['logo']['tmp_name'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $tmp);
    finfo_close($finfo);
    $allowed = array('image/png','image/jpeg','image/gif');
    if (in_array($mime, $allowed)) {
      if (!is_dir(dirname($logoPath))) @mkdir(dirname($logoPath), 0755, true);
      if (!@move_uploaded_file($tmp, $logoPath)) {
        $msg = 'ไม่สามารถอัปโหลดรูปภาพได้';
      }
    } else {
      $msg = 'ไฟล์โลโก้ต้องเป็นภาพ (png/jpg/gif)';
    }
  }

  // Save cover if uploaded (fixed filename title.png)
  if (!empty($_FILES['cover']['tmp_name'])) {
    $tmpc = $_FILES['cover']['tmp_name'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimec = finfo_file($finfo, $tmpc);
    finfo_close($finfo);
    $allowedCover = array('image/png','image/jpeg','image/gif');
    if (in_array($mimec, $allowedCover)) {
      if (!is_dir(dirname($coverPath))) @mkdir(dirname($coverPath), 0755, true);
      if (!@move_uploaded_file($tmpc, $coverPath)) {
        $msg = 'ไม่สามารถอัปโหลดภาพปกได้';
      }
    } else {
      $msg = 'ไฟล์ภาพปกต้องเป็นภาพ (png/jpg/gif)';
    }
  }

  // persist settings to JSON
  $payload = array(
    'site_title' => $site_title,
    'logo' => file_exists($logoPath) ? basename($logoPath) : '',
    'cover' => file_exists($coverPath) ? basename($coverPath) : '',
    'map_heading' => $map_heading
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
        <label class="form-label">ภาพปก (cover) - จะเก็บเป็น assets/img/title.png</label>
        <input type="file" name="cover" class="form-control" accept="image/*" />
        <?php if (!empty($settings['cover']) && file_exists(__DIR__ . '/../../assets/img/' . $settings['cover'])): ?>
          <div style="margin-top:8px">
            <img src="../../assets/img/<?php echo e($settings['cover']); ?>" alt="cover" style="max-height:120px; width:auto;" />
          </div>
        <?php else: ?>
          <!-- show existing title.png if exists -->
          <?php if (file_exists(__DIR__ . '/../../assets/img/title.png')): ?>
            <div style="margin-top:8px">
              <img src="../../assets/img/title.png" alt="cover" style="max-height:120px; width:auto;" />
            </div>
          <?php endif; ?>
        <?php endif; ?>
      </div>

      <button class="btn btn-primary" type="submit">บันทึก</button>
    </form>
  </div>
</div>
