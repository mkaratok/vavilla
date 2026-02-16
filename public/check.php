<?php

define('LARAVEL_START', microtime(true));

// Güvenlik için basit bir IP kontrolü veya şifre mekanizması eklenebilir.
// Şimdilik herkesin erişimine açık ancak dikkatli kullanılmalı.
// Örn: if($_GET['key'] != 'gizlisifre') die('Erişim engellendi');

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Artisan;

// Helper function to recursively remove a directory
function removeDirectory($dir) {
    if (!is_dir($dir)) {
        if (is_link($dir)) {
            return unlink($dir);
        }
        return false;
    }
    
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS)
    );
    
    foreach ($iterator as $file) {
        if ($file->isLink()) {
            unlink($file->getPathname());
        } elseif ($file->isDir()) {
            rmdir($file->getPathname());
        } else {
            unlink($file->getPathname());
        }
    }
    
    return rmdir($dir);
}

// Helper function to copy directory contents (alternative to junction for Windows)
function copyDirectoryContents($source, $destination) {
    if (!is_dir($source)) {
        throw new \Exception("Source directory does not exist: $source");
    }
    
    // Create destination directory if it doesn't exist
    if (!is_dir($destination)) {
        if (!mkdir($destination, 0755, true)) {
            throw new \Exception("Cannot create destination directory: $destination");
        }
    }
    
    // Get directory iterator for source
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS)
    );
    
    foreach ($iterator as $item) {
        $destPath = $destination . DIRECTORY_SEPARATOR . $iterator->getSubPathName();
        
        if ($item->isDir()) {
            // Create directory in destination
            if (!is_dir($destPath)) {
                mkdir($destPath, 0755, true);
            }
        } else {
            // Copy file to destination
            if (!copy($item->getPathname(), $destPath)) {
                throw new \Exception("Failed to copy file: " . $item->getPathname());
            }
        }
    }
    
    return "Directory contents copied successfully from $source to $destination";
}

$results = [];

if (isset($_GET['cmd'])) {
    $cmd = $_GET['cmd'];
    
    try {
        switch ($cmd) {
            case 'storage':
                $targetFolder = realpath(__DIR__ . '/../storage/app/public');
                $linkFolder = __DIR__ . '/storage';
                
                $output = "";
                $status = "success";
                $method = "";
                
                // Eski linki veya dizini sil
                if (file_exists($linkFolder) || is_link($linkFolder)) {
                    if (is_link($linkFolder)) {
                        @unlink($linkFolder);
                    } elseif (is_dir($linkFolder)) {
                        removeDirectory($linkFolder);
                    }
                }
                
                // Yöntem 1: symlink (en iyi yöntem)
                $linked = false;
                if (function_exists('symlink')) {
                    // Hata bastırarak dene
                    set_error_handler(function() {});
                    $linked = @symlink($targetFolder, $linkFolder);
                    restore_error_handler();
                    
                    if ($linked && is_link($linkFolder)) {
                        $method = "symlink";
                        $output = "✅ Sembolik link başarıyla oluşturuldu (symlink).\n";
                    } else {
                        $linked = false;
                    }
                }
                
                // Yöntem 2: Relative symlink dene
                if (!$linked && function_exists('symlink')) {
                    $relativeTarget = '../storage/app/public';
                    set_error_handler(function() {});
                    $linked = @symlink($relativeTarget, $linkFolder);
                    restore_error_handler();
                    
                    if ($linked && is_link($linkFolder)) {
                        $method = "relative symlink";
                        $output = "✅ Sembolik link başarıyla oluşturuldu (relative symlink).\n";
                    } else {
                        $linked = false;
                        @unlink($linkFolder); // temizle
                    }
                }
                
                // Yöntem 3: PHP router dosyası ile proxy (en güvenilir fallback)
                if (!$linked) {
                    // storage klasörünü oluştur
                    if (!is_dir($linkFolder)) {
                        mkdir($linkFolder, 0755, true);
                    }
                    
                    // .htaccess ile rewrite kuralı yaz
                    $htaccess = $linkFolder . '/.htaccess';
                    $htaccessContent = <<<'HTACCESS'
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ serve.php?file=$1 [L,QSA]
</IfModule>
HTACCESS;
                    file_put_contents($htaccess, $htaccessContent);
                    
                    // serve.php - dosyaları storage'dan sunan proxy
                    $serveFile = $linkFolder . '/serve.php';
                    $serveContent = <<<'SERVE'
<?php
$file = isset($_GET['file']) ? $_GET['file'] : '';
$file = str_replace(['..', "\0"], '', $file); // güvenlik

$storagePath = realpath(__DIR__ . '/../../storage/app/public');
$filePath = $storagePath . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $file);

if (!$storagePath || !file_exists($filePath) || !is_file($filePath)) {
    http_response_code(404);
    exit('File not found');
}

// Dosyanın storage içinde olduğunu doğrula (path traversal koruması)
if (strpos(realpath($filePath), $storagePath) !== 0) {
    http_response_code(403);
    exit('Forbidden');
}

$mime = mime_content_type($filePath);
$size = filesize($filePath);

// Cache headers
$lastModified = filemtime($filePath);
$etag = md5($filePath . $lastModified);

header('Content-Type: ' . $mime);
header('Content-Length: ' . $size);
header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $lastModified) . ' GMT');
header('ETag: "' . $etag . '"');
header('Cache-Control: public, max-age=31536000');

// 304 Not Modified kontrolü
if (isset($_SERVER['HTTP_IF_NONE_MATCH']) && trim($_SERVER['HTTP_IF_NONE_MATCH'], '"') === $etag) {
    http_response_code(304);
    exit;
}

readfile($filePath);
exit;
SERVE;
                    file_put_contents($serveFile, $serveContent);
                    
                    // Ayrıca mevcut dosyaları da kopyala (htaccess çalışmazsa diye)
                    if (is_dir($targetFolder)) {
                        copyDirectoryContents($targetFolder, $linkFolder);
                    }
                    
                    $method = "PHP proxy + dosya kopyalama";
                    $output = "✅ Storage bağlantısı oluşturuldu (PHP proxy + dosya kopyalama).\n";
                    $output .= "📁 .htaccess rewrite kuralı yazıldı.\n";
                    $output .= "📁 serve.php proxy dosyası oluşturuldu.\n";
                    $output .= "📁 Mevcut dosyalar kopyalandı.\n";
                    $output .= "\n⚠️ NOT: Yeni dosya yüklendiğinde 'Storage Sync' butonuna basın.\n";
                    $linked = true;
                }
                
                $output .= "\nTarget: $targetFolder\nLink: $linkFolder\nYöntem: $method";
                
                $results[] = [
                    'command' => "Storage Link ($method)",
                    'output' => $output,
                    'status' => $status
                ];
                break;
                
            case 'storage_sync':
                // Dosyaları storage'dan public/storage'a senkronize et
                $targetFolder = realpath(__DIR__ . '/../storage/app/public');
                $linkFolder = __DIR__ . '/storage';
                
                if (!$targetFolder || !is_dir($targetFolder)) {
                    $results[] = [
                        'command' => 'Storage Sync',
                        'output' => 'Storage klasörü bulunamadı: ' . __DIR__ . '/../storage/app/public',
                        'status' => 'error'
                    ];
                    break;
                }
                
                // Eğer symlink ise sync'e gerek yok
                if (is_link($linkFolder)) {
                    $results[] = [
                        'command' => 'Storage Sync',
                        'output' => 'Storage zaten symlink olarak bağlı, sync gerekmiyor.',
                        'status' => 'success'
                    ];
                    break;
                }
                
                if (!is_dir($linkFolder)) {
                    mkdir($linkFolder, 0755, true);
                }
                
                copyDirectoryContents($targetFolder, $linkFolder);
                
                $results[] = [
                    'command' => 'Storage Sync',
                    'output' => "✅ Dosyalar senkronize edildi.\nKaynak: $targetFolder\nHedef: $linkFolder",
                    'status' => 'success'
                ];
                break;
                
            case 'migrate':
                Artisan::call('migrate', ['--force' => true]);
                $results[] = [
                    'command' => 'php artisan migrate --force',
                    'output' => Artisan::output(),
                    'status' => 'success'
                ];
                break;

            case 'seed':
                Artisan::call('db:seed', ['--force' => true]);
                $results[] = [
                    'command' => 'php artisan db:seed --force',
                    'output' => Artisan::output(),
                    'status' => 'success'
                ];
                break;

            case 'optimize':
                Artisan::call('optimize:clear');
                $results[] = [
                    'command' => 'php artisan optimize:clear',
                    'output' => Artisan::output(),
                    'status' => 'success'
                ];
                break;

            case 'cache':
                Artisan::call('cache:clear');
                Artisan::call('config:clear');
                Artisan::call('view:clear');
                Artisan::call('route:clear');
                $results[] = [
                    'command' => 'Clear All Caches',
                    'output' => Artisan::output(),
                    'status' => 'success'
                ];
                break;
        }
    } catch (\Exception $e) {
        $results[] = [
            'command' => $cmd,
            'output' => $e->getMessage(),
            'status' => 'error'
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Server Maintenance Tool</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; background: #f4f6f9; padding: 20px; display: flex; justify-content: center; }
        .container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); width: 100%; max-width: 600px; }
        h1 { margin-top: 0; border-bottom: 2px solid #eee; padding-bottom: 15px; color: #333; }
        .btn { display: block; width: 100%; padding: 15px; margin-bottom: 10px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; text-align: center; font-weight: bold; transition: background 0.3s; box-sizing: border-box; }
        .btn:hover { background: #0056b3; }
        .btn.btn-warning { background: #ffc107; color: #000; }
        .btn.btn-warning:hover { background: #e0a800; }
        .btn.btn-danger { background: #dc3545; }
        .btn.btn-danger:hover { background: #c82333; }
        .btn.btn-success { background: #28a745; }
        .btn.btn-success:hover { background: #218838; }
        .output { background: #2d2d2d; color: #f8f8f2; padding: 15px; border-radius: 5px; margin-top: 20px; font-family: monospace; white-space: pre-wrap; font-size: 14px; }
        .success { border-left: 5px solid #28a745; }
        .error { border-left: 5px solid #dc3545; }
    </style>
</head>
<body>

<div class="container">
    <h1>🛠️ Server Bakım Aracı</h1>
    
    <a href="?cmd=optimize" class="btn">🧹 Cache Temizle (Optimize:clear)</a>
    <a href="?cmd=cache" class="btn">🗑️ Tüm Cache Temizle</a>
    <a href="?cmd=storage" class="btn btn-warning">🔗 Storage Link Oluştur</a>
    <a href="?cmd=storage_sync" class="btn btn-warning" style="background:#e6a817;">🔄 Storage Sync (Dosyaları Güncelle)</a>
    <a href="?cmd=migrate" class="btn btn-danger" onclick="return confirm('Veritabanı tablolarını güncellemek istediğinize emin misiniz?');">🗄️ Veritabanı Güncelle (Migrate)</a>
    <a href="?cmd=seed" class="btn btn-success" onclick="return confirm('Veritabanına örnek verileri yüklemek istediğinize emin misiniz?');">🌱 Veritabanı Seed (Örnek Veri)</a>
    
    <?php if (!empty($results)): ?>
        <?php foreach ($results as $result): ?>
            <div class="output <?= $result['status'] ?>">
                <strong>Komut:</strong> <?= htmlspecialchars($result['command']) ?><br>
                <hr style="border-color: #444;">
                <?= htmlspecialchars($result['output']) ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <div style="margin-top: 30px; text-align: center; color: #666; font-size: 12px;">
        <p>Bu dosya public klasöründe bulunmaktadır. İşiniz bittiğinde güvenliğiniz için silmeniz önerilir.</p>
    </div>
</div>

</body>
</html>
