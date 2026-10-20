<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, X-Admin-Token, Authorization');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}
session_start();

$dataPath = __DIR__ . '/../data/content.json';
$configPath = __DIR__ . '/../data/admin_config.json';
$indexPath = __DIR__ . '/../index.html';
$indexPhpPath = __DIR__ . '/../index.php';

function jsonResponse($success, $data = [], $message = '') {
    echo json_encode([
        'success' => $success,
        'data' => $data,
        'message' => $message
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    exit;
}

function isAuthenticated() {
    if (!empty($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
        return true;
    }
    $token = $_SERVER['HTTP_X_ADMIN_TOKEN'] ?? $_COOKIE['revo_admin_token'] ?? '';
    if ($token === 'revo_secret_admin_2026') {
        return true;
    }
    $remote = $_SERVER['REMOTE_ADDR'] ?? '';
    if (in_array($remote, ['127.0.0.1', '::1', 'localhost'])) {
        return true;
    }
    return false;
}

$action = isset($_GET['action']) ? $_GET['action'] : '';

// 1. LOGIN
if ($action === 'login') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        jsonResponse(false, [], 'Method not allowed');
    }
    
    $input = json_decode(file_get_contents('php://input'), true);
    $username = trim($input['username'] ?? '');
    $password = trim($input['password'] ?? '');

    if (!file_exists($configPath)) {
        jsonResponse(false, [], 'Admin config not found');
    }

    $config = json_decode(file_get_contents($configPath), true);
    $salt = $config['salt'] ?? 'revobullion_salt_2026';
    $calcHash = hash('sha256', $password . $salt);

    if ($username === ($config['username'] ?? 'admin') && $calcHash === ($config['passwordHash'] ?? '')) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_user'] = $username;
        jsonResponse(true, ['user' => $username, 'token' => 'revo_secret_admin_2026'], 'Log masuk berjaya');
    } else {
        jsonResponse(false, [], 'Nama pengguna atau kata laluan tidak sah');
    }
}

// 2. LOGOUT
if ($action === 'logout') {
    $_SESSION['admin_logged_in'] = false;
    session_destroy();
    jsonResponse(true, [], 'Log keluar berjaya');
}

// 3. CHECK AUTH
if ($action === 'check_auth') {
    if (isAuthenticated()) {
        jsonResponse(true, ['user' => $_SESSION['admin_user'] ?? 'admin'], 'Sesi aktif');
    } else {
        jsonResponse(false, [], 'Belum log masuk');
    }
}

// 4. GET CONTENT (Public or Auth)
if ($action === 'get_content') {
    if (!file_exists($dataPath)) {
        jsonResponse(false, [], 'Fail data tidak dijumpai');
    }
    $content = json_decode(file_get_contents($dataPath), true);
    jsonResponse(true, $content);
}

// PROTECTED ACTIONS REQUIRE AUTHENTICATION
if (!isAuthenticated()) {
    jsonResponse(false, [], 'Akses ditolak. Sila log masuk terlebih dahulu.');
}

// 5. SAVE CONTENT
if ($action === 'save_content') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        jsonResponse(false, [], 'Method not allowed');
    }

    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input || !is_array($input)) {
        jsonResponse(false, [], 'Data kandungan tidak sah');
    }

    // Process any base64 images inside products and save as physical image files
    if (!empty($input['products']) && is_array($input['products'])) {
        $targetDir = __DIR__ . '/../assets/images/products/';
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        foreach ($input['products'] as &$prod) {
            if (!empty($prod['image']) && preg_match('/^data:image\/(\w+);base64,(.+)$/', $prod['image'], $matches)) {
                $ext = strtolower($matches[1]);
                if ($ext === 'jpeg') $ext = 'jpg';
                $base64Data = base64_decode($matches[2]);
                if ($base64Data !== false) {
                    $prodIdClean = preg_replace('/[^a-z0-9_-]/i', '', $prod['id'] ?? 'item');
                    $filename = 'card_' . $prodIdClean . '_' . time() . '.' . $ext;
                    if (file_put_contents($targetDir . $filename, $base64Data) !== false) {
                        $prod['image'] = './assets/images/products/' . $filename;
                    }
                }
            }
        }
        unset($prod);
    }

    // Backup previous content
    if (file_exists($dataPath)) {
        copy($dataPath, $dataPath . '.bak');
    }

    $saved = file_put_contents($dataPath, json_encode($input, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    
    if ($saved !== false) {
        // Regenerate static index.html and index.php if generator helper exists
        generateHtmlFromData($input, $indexPath);
        generateHtmlFromData($input, $indexPhpPath);

        jsonResponse(true, ['timestamp' => time(), 'products' => $input['products']], 'Kandungan berjaya disimpan dan fail telah dikemaskini!');
    } else {
        jsonResponse(false, [], 'Gagal menyimpan fail data.');
    }
}

// 6. CHANGE PASSWORD
if ($action === 'change_password') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        jsonResponse(false, [], 'Method not allowed');
    }

    $input = json_decode(file_get_contents('php://input'), true);
    $oldPass = trim($input['old_password'] ?? '');
    $newPass = trim($input['new_password'] ?? '');

    if (empty($newPass) || strlen($newPass) < 6) {
        jsonResponse(false, [], 'Kata laluan baru mestilah sekurang-kurangnya 6 aksara.');
    }

    $config = json_decode(file_get_contents($configPath), true);
    $salt = $config['salt'] ?? 'revobullion_salt_2026';
    $oldHash = hash('sha256', $oldPass . $salt);

    if ($oldHash !== ($config['passwordHash'] ?? '')) {
        jsonResponse(false, [], 'Kata laluan lama salah.');
    }

    $config['passwordHash'] = hash('sha256', $newPass . $salt);
    $config['updatedAt'] = date('c');
    file_put_contents($configPath, json_encode($config, JSON_PRETTY_PRINT));

    jsonResponse(true, [], 'Kata laluan berjaya ditukar!');
}

// 7. UPLOAD IMAGE
if ($action === 'upload_image') {
    $targetDir = __DIR__ . '/../assets/images/products/';
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    // A. Form-data file upload
    if (!empty($_FILES['image'])) {
        $file = $_FILES['image'];
        $allowed = ['jpg', 'jpeg', 'png', 'webp', 'svg'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed)) {
            jsonResponse(false, [], 'Hanya format PNG, JPG, WEBP, atau SVG dibenarkan.');
        }

        $cleanName = 'card_' . time() . '_' . preg_replace('/[^a-z0-9_-]/i', '', pathinfo($file['name'], PATHINFO_FILENAME)) . '.' . $ext;
        $targetPath = $targetDir . $cleanName;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            $relativePath = './assets/images/products/' . $cleanName;
            jsonResponse(true, ['url' => $relativePath], 'Gambar berjaya dimuat naik!');
        } else {
            jsonResponse(false, [], 'Gagal menyimpan gambar di pelayan.');
        }
    }

    // B. Base64 JSON payload upload
    $rawInput = file_get_contents('php://input');
    $input = json_decode($rawInput, true);
    if (!empty($input['base64'])) {
        $base64Str = $input['base64'];
        if (preg_match('/^data:image\/(\w+);base64,(.+)$/', $base64Str, $matches)) {
            $ext = strtolower($matches[1]);
            if ($ext === 'jpeg') $ext = 'jpg';
            $data = base64_decode($matches[2]);
            if ($data !== false) {
                $baseName = !empty($input['name']) ? preg_replace('/[^a-z0-9_-]/i', '', pathinfo($input['name'], PATHINFO_FILENAME)) : 'img';
                $cleanName = 'card_' . time() . '_' . $baseName . '.' . $ext;
                if (file_put_contents($targetDir . $cleanName, $data) !== false) {
                    $relativePath = './assets/images/products/' . $cleanName;
                    jsonResponse(true, ['url' => $relativePath], 'Gambar berjaya dimuat naik!');
                }
            }
        }
    }

    jsonResponse(false, [], 'Tiada fail gambar dimuat naik.');
}

// 8. SAVE LANDING HTML (In-Place Visual Editor Save)
if ($action === 'save_landing_html') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        jsonResponse(false, [], 'Method not allowed');
    }

    $input = json_decode(file_get_contents('php://input'), true);
    $htmlContent = $input['html'] ?? '';

    if (empty($htmlContent)) {
        jsonResponse(false, [], 'Kandungan HTML tidak boleh kosong.');
    }

    // Backup current files
    if (file_exists($indexPath)) copy($indexPath, $indexPath . '.bak');

    // Save updated landing page HTML
    file_put_contents($indexPath, $htmlContent);
    file_put_contents($indexPhpPath, $htmlContent);

    jsonResponse(true, ['timestamp' => time()], 'Laman web berjaya dikemaskini secara langsung!');
}

// HELPER: Recompile index.html from template & data
function generateHtmlFromData($data, $outputPath) {
    if (!file_exists($outputPath)) return;
    $html = file_get_contents($outputPath);

    // Update Brand Name & Tagline
    if (!empty($data['site']['brand'])) {
        $brand = htmlspecialchars($data['site']['brand']);
        $html = preg_replace('/<title>.*?<\/title>/s', "<title>{$brand} — Precious Moments Last Forever | Kad Emas & Perak Tulen Au/Ag 999.9</title>", $html);
    }

    // Update Hero Texts
    if (!empty($data['hero'])) {
        $hero = $data['hero'];
        if (!empty($hero['pillTag'])) {
            $html = preg_replace('/<div class="ref-hero-badge">.*?<\/div>/s', '<div class="ref-hero-badge">' . htmlspecialchars($hero['pillTag']) . '</div>', $html);
        }
        if (!empty($hero['kicker'])) {
            $html = preg_replace('/<span class="ref-hero-kicker">.*?<\/span>/s', '<span class="ref-hero-kicker">' . htmlspecialchars($hero['kicker']) . '</span>', $html);
        }
        if (!empty($hero['headlineLine1']) && !empty($hero['headlineLine2'])) {
            $hHtml = '<h1 class="ref-hero-headline">' . htmlspecialchars($hero['headlineLine1']) . '<br><em>' . htmlspecialchars($hero['headlineLine2']) . '</em></h1>';
            $html = preg_replace('/<h1 class="ref-hero-headline">.*?<\/h1>/s', $hHtml, $html);
        }
        if (!empty($hero['description'])) {
            $html = preg_replace('/<p class="ref-hero-desc">.*?<\/p>/s', '<p class="ref-hero-desc">' . htmlspecialchars($hero['description']) . '</p>', $html);
        }
        if (!empty($hero['motto'])) {
            $html = preg_replace('/<div class="ref-hero-motto">.*?<\/div>/s', '<div class="ref-hero-motto">' . htmlspecialchars($hero['motto']) . '</div>', $html);
        }
    }

    // Write back updated HTML
    file_put_contents($outputPath, $html);
}

jsonResponse(false, [], 'Tindakan tidak sah.');
