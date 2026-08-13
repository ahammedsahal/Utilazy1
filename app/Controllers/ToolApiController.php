<?php
namespace App\Controllers;
use App\Helpers\DB;
use App\Helpers\Session;
use App\Helpers\Response;
use App\Services\TokenService;
use Exception;
class ToolApiController {
    public function process() {
        if (!Session::check()) return Response::json(['error' => 'Authentication required.'], 401);
        $userId = Session::userId();
        $toolId = (int)($_POST['tool_id'] ?? 0);
        $metadata = $_POST['metadata'] ?? [];
        if ($toolId <= 0) return Response::json(['error' => 'Invalid Tool ID.'], 400);
        $tool = DB::fetch("SELECT * FROM tools WHERE id = ? AND enabled = 1", [$toolId]);
        if (!$tool) return Response::json(['error' => 'Tool disabled.'], 404);
        $cost = ($tool['access_type'] === 'token_required') ? (int)$tool['token_cost'] : 0;
        try {
            TokenService::deduct($userId, $toolId, $cost, $metadata);
            $newBalance = TokenService::getBalance($userId);
            return Response::json([
                'success' => true,
                'message' => $cost > 0 ? "Charged {$cost} tokens." : "Launched.",
                'cost' => $cost,
                'new_balance' => $newBalance
            ]);
        } catch (Exception $e) {
            return Response::json(['error' => $e->getMessage()], 400);
        }
    }
}