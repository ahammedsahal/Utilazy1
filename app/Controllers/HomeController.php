<?php
namespace App\Controllers;
use App\Helpers\DB;
use App\Helpers\Session;
use App\Helpers\View;
use App\Helpers\Response;
use App\Services\TokenService;
class HomeController {
    public function index() {
        $featured = DB::fetchAll("SELECT * FROM tools WHERE enabled = 1 AND featured = 1 ORDER BY sort_order ASC LIMIT 6");
        $popular = DB::fetchAll("SELECT * FROM tools WHERE enabled = 1 ORDER BY sort_order ASC LIMIT 6");
        $categories = DB::fetchAll("SELECT * FROM categories ORDER BY sort_order ASC");
        return View::render('home', [
            'featured' => $featured,
            'popular' => $popular,
            'categories' => $categories
        ]);
    }
    public function tools() {
        $categories = DB::fetchAll("SELECT * FROM categories ORDER BY sort_order ASC");
        $catFilter = trim($_GET['category'] ?? 'all');
        $accessFilter = trim($_GET['access'] ?? 'all');
        $search = trim($_GET['search'] ?? '');
        $sql = "SELECT * FROM tools WHERE enabled = 1";
        $params = [];
        if ($catFilter !== 'all') {
            $sql .= " AND category_id = (SELECT id FROM categories WHERE slug = ?)";
            $params[] = $catFilter;
        }
        if ($accessFilter === 'free') {
            $sql .= " AND access_type = 'free'";
        } elseif ($accessFilter === 'premium') {
            $sql .= " AND access_type IN ('token_required', 'subscription_required')";
        }
        if (!empty($search)) {
            $sql .= " AND (name LIKE ? OR description LIKE ?)";
            $params[] = '%' . $search . '%';
            $params[] = '%' . $search . '%';
        }
        $sql .= " ORDER BY category_id, sort_order ASC";
        $tools = DB::fetchAll($sql, $params);
        return View::render('tools/directory', [
            'tools' => $tools,
            'categories' => $categories,
            'selectedCategory' => $catFilter,
            'selectedAccess' => $accessFilter,
            'searchQuery' => $search
        ]);
    }
    public function tool($slug) {
        $tool = DB::fetch("SELECT * FROM tools WHERE slug = ? AND enabled = 1", [$slug]);
        if (!$tool) {
            http_response_code(404);
            $controller = new ErrorController();
            return $controller->show404();
        }
        $userId = Session::userId();
        $userBalance = $userId ? TokenService::getBalance($userId) : 0;
        $isUnlimited = $userId ? TokenService::hasUnlimited($userId) : false;
        $category = DB::fetch("SELECT * FROM categories WHERE id = ?", [$tool['category_id']]);
        $related = DB::fetchAll("SELECT * FROM tools WHERE category_id = ? AND slug != ? AND enabled = 1 LIMIT 4", [$tool['category_id'], $slug]);
        $requiresLogin = ($tool['access_type'] !== 'free');
        if ($requiresLogin && !Session::check()) {
            $_SESSION['flash_error'] = "The '{$tool['name']}' tool requires a registered account. Sign up for 100 free tokens!";
            return Response::redirect('/login');
        }
        return View::render('tools/workspace', [
            'tool' => $tool,
            'category' => $category,
            'related' => $related,
            'userBalance' => $userBalance,
            'isUnlimited' => $isUnlimited
        ]);
    }
    public function categories() {
        $categories = DB::fetchAll("SELECT * FROM categories ORDER BY sort_order ASC");
        return View::render('categories', ['categories' => $categories]);
    }
    public function pricing() {
        $packages = DB::fetchAll("SELECT * FROM token_packages WHERE active = 1 ORDER BY sort_order ASC");
        return View::render('pricing', ['packages' => $packages]);
    }
    public function howItWorks() { return View::render('pages/how_it_works'); }
    public function about() { return View::render('pages/about'); }
    public function contact() { return View::render('pages/contact'); }
    public function faq() { return View::render('pages/faq'); }
    public function privacy() { return View::render('pages/privacy'); }
    public function terms() { return View::render('pages/terms'); }
}