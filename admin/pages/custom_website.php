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

// Load existing settings if available
$settings = array(
    'site_title' => '',
    'header_html' => '',
    'footer_html' => '',
    'custom_css' => ''
);
if (file_exists($settingsFile)) {
    $raw = file_get_contents($settingsFile);
    $decoded = json_decode($raw, true);
    if (is_array($decoded)) $settings = array_merge($settings, $decoded);
}

// Handle POST save
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // minimal permission check: require admin cookie (site already protects admin area)

    $site_title = isset($_POST['site_title']) ? $_POST['site_title'] : '';
    $header_html = isset($_POST['header_html']) ? $_POST['header_html'] : '';
    $footer_html = isset($_POST['footer_html']) ? $_POST['footer_html'] : '';
    $custom_css = isset($_POST['custom_css']) ? $_POST['custom_css'] : '';

    // Save logo if uploaded
    if (!empty($_FILES['logo']['tmp_name'])) {
        $tmp = $_FILES['logo']['tmp_name'];
        // basic mime check
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $tmp);
        finfo_close($finfo);
        $allowed = array('image/png','image/jpeg','image/gif');
        if (in_array($mime, $allowed)) {
            if (!is_dir(dirname($logoPath))) @mkdir(dirname($logoPath), 0755, true);
            if (@move_uploaded_file($tmp, $logoPath)) {
                // ok
            } else {
                $msg = 'ไม่สามารถอัปโหลดรูปภาพได้';
            }
        } else {
            $msg = 'ไฟล์โลโก้ต้องเป็นภาพ (png/jpg/gif)';
        }
    }

    // persist settings to JSON
    $payload = array(
        'site_title' => $site_title,
        'header_html' => $header_html,
        'footer_html' => $footer_html,
        'custom_css' => $custom_css,
        'logo' => file_exists($logoPath) ? basename($logoPath) : ''
    );
    if (!is_dir(dirname($settingsFile))) @mkdir(dirname($settingsFile), 0755, true);
    if (file_put_contents($settingsFile, json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
        $msg = $msg ? $msg : 'บันทึกสำเร็จ';
        // reload settings
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
        <label class="form-label">Header HTML</label>
        <textarea name="header_html" rows="6" class="form-control"><?php echo e($settings['header_html']); ?></textarea>
        <div class="form-text">พื้นที่สำหรับใส่ HTML ขนาดเล็ก (เช่น meta tags หรือ banner)</div>
      </div>

      <div class="mb-3">
        <label class="form-label">Footer HTML</label>
        <textarea name="footer_html" rows="4" class="form-control"><?php echo e($settings['footer_html']); ?></textarea>
      </div>

      <div class="mb-3">
        <label class="form-label">Custom CSS</label>
        <textarea name="custom_css" rows="6" class="form-control"><?php echo e($settings['custom_css']); ?></textarea>
        <div class="form-text">ใส่ CSS ที่จะถูกฝังในหน้าหลัก</div>
      </div>

      <div class="mb-3">
        <label class="form-label">Logo (png/jpg/gif)</label>
        <input type="file" name="logo" class="form-control" accept="image/*" />
        <?php if (file_exists($logoPath)): ?>
          <div style="margin-top:8px">
            <img src="../../assets/img/<?php echo e(basename($logoPath)); ?>" alt="logo" style="max-height:80px;" />
          </div>
        <?php endif; ?>
      </div>

      <button class="btn btn-primary" type="submit">บันทึก</button>
    </form>
  </div>
</div>
