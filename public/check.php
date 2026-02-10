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

$results = [];

if (isset($_GET['cmd'])) {
    $cmd = $_GET['cmd'];
    
    try {
        switch ($cmd) {
            case 'storage':
                // Önce eski linki silmeyi deneyelim
                $targetFolder = __DIR__ . '/../storage/app/public';
                $linkFolder = __DIR__ . '/storage';
                
                if (file_exists($linkFolder)) {
                    @unlink($linkFolder); 
                }
                
                $output = "";
                $status = "success";
                
                try {
                    // Native Symlink (Daha garantidir)
                    if (symlink($targetFolder, $linkFolder)) {
                        $output = "Sembolik link başarıyla oluşturuldu (Native PHP).\nTarget: $targetFolder\nLink: $linkFolder";
                    } else {
                        throw new \Exception("Native symlink başarısız oldu.");
                    }
                } catch (\Exception $e) {
                    // Fallback to Artisan
                    try {
                        Artisan::call('storage:link');
                        $output = "Artisan storage:link çalıştırıldı.\n" . Artisan::output();
                    } catch (\Exception $e2) {
                        $output = "Hata: " . $e2->getMessage();
                        $status = "error";
                    }
                }
                
                $results[] = [
                    'command' => 'Storage Link (Native + Fallback)',
                    'output' => $output,
                    'status' => $status
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
    <a href="?cmd=storage" class="btn btn-warning">🔗 Storage Link Oluştur (Native)</a>
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
