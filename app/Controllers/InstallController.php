<?php
namespace App\Controllers;
use App\Helpers\DB;
use App\Helpers\Response;
use App\Helpers\View;
class InstallController {
    public function showInstall() {
        try {
            $setting = DB::fetch("SELECT value FROM site_settings WHERE `key` = 'installed'");
            if ($setting && $setting['value'] == '1') {
                http_response_code(403);
                echo "<h3>Access Denied</h3><p>Already installed.</p>";
                exit;
            }
        } catch (\Exception $e) {}
        return View::render('install/index');
    }
    public function runInstall() {
        try {
            $setting = DB::fetch("SELECT value FROM site_settings WHERE `key` = 'installed'");
            if ($setting && $setting['value'] == '1') {
                return Response::json(['error' => 'Installation already completed.'], 403);
            }
        } catch (\Exception $e) {}
        $adminEmail = trim($_POST['admin_email'] ?? '');
        $adminPassword = $_POST['admin_password'] ?? '';
        $adminName = trim($_POST['admin_name'] ?? 'Super Admin');
        if (empty($adminEmail) || empty($adminPassword)) return Response::json(['error' => 'Fields are required.'], 400);
        try {
            $schemaSql = file_get_contents(dirname(__DIR__, 2) . '/database/schema.sql');
            $db = DB::connect();
            $db->exec($schemaSql);
            $seedSql = file_get_contents(dirname(__DIR__, 2) . '/database/seed.sql');
            $db->exec($seedSql);
            $hash = password_hash($adminPassword, PASSWORD_BCRYPT);
            DB::query("UPDATE users SET name = ?, email = ?, password_hash = ? WHERE id = 1", [$adminName, $adminEmail, $hash]);
            DB::query("INSERT INTO site_settings (\`key\`, \`value\`, \`type\`) VALUES ('installed', '1', 'boolean') ON DUPLICATE KEY UPDATE \`value\` = '1'");
            return Response::json(['success' => true, 'message' => 'Installed!']);
        } catch (\Exception $e) {
            return Response::json(['error' => $e->getMessage()], 500);
        }
    }
}