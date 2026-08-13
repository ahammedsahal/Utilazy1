<?php
namespace App\Helpers;
use App\Helpers\DB;
use App\Helpers\Session;
class View {
    public static function render($viewPath, $data = []) {
        extract($data);
        $site_name = "Utilazy";
        $site_tagline = "Smart Tools for Everyday Tasks";
        $dbSettings = DB::fetchAll("SELECT `key`, `value` FROM site_settings");
        $settings = [];
        foreach ($dbSettings as $s) {
            $settings[$s['key']] = $s['value'];
        }
        if (isset($settings['site_name'])) $site_name = $settings['site_name'];
        if (isset($settings['site_tagline'])) $site_tagline = $settings['site_tagline'];
        $user = Session::user();
        $isAdmin = Session::isAdmin();
        $dbTools = DB::fetchAll("SELECT * FROM tools WHERE enabled = 1 ORDER BY category_id, sort_order ASC");
        $dbCategories = DB::fetchAll("SELECT * FROM categories ORDER BY sort_order ASC");
        $categories = [];
        foreach ($dbCategories as $cat) {
            $cat['tools'] = [];
            $categories[$cat['id']] = $cat;
        }
        foreach ($dbTools as $tool) {
            if (isset($categories[$tool['category_id']])) {
                $categories[$tool['category_id']]['tools'][] = $tool;
            }
        }
        $dbTokens = DB::fetchAll("SELECT * FROM design_tokens");
        $designTokensLight = [];
        $designTokensDark = [];
        foreach ($dbTokens as $tok) {
            if ($tok['scope'] === 'light') {
                $designTokensLight[$tok['token_key']] = $tok['token_value'];
            } else {
                $designTokensDark[$tok['token_key']] = $tok['token_value'];
            }
        }
        $injections = DB::fetchAll("SELECT * FROM code_injections WHERE enabled = 1");
        $headInjections = "";
        $bodyInjections = "";
        foreach ($injections as $inj) {
            if ($inj['location'] === 'header') {
                $headInjections .= $inj['code'] . "\n";
            } else {
                $bodyInjections .= $inj['code'] . "\n";
            }
        }
        $filePath = dirname(__DIR__, 2) . "/views/" . $viewPath . ".php";
        if (!file_exists($filePath)) {
            throw new \Exception("View not found: " . $viewPath);
        }
        ob_start();
        include $filePath;
        $content = ob_get_clean();
        include dirname(__DIR__, 2) . "/views/layout.php";
    }
}