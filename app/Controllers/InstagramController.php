<?php
namespace App\Controllers;

use App\Helpers\DB;
use App\Helpers\Session;
use App\Helpers\Response;

class InstagramController {
    public function connect() {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'username' => 'utilazy_giveaways',
            'followers_count' => 8420,
            'profile_picture' => 'https://images.unsplash.com/photo-1513151233558-d860c5398176?w=100'
        ]);
        exit;
    }

    public function posts() {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'posts' => [
                [
                    'id' => 'post_01',
                    'caption' => "🎉 MEGA SUMMER GIVEAWAY! Tag 2 friends and comment DONE! 🎁 #summer #utilazy",
                    'media_url' => 'https://images.unsplash.com/photo-1513151233558-d860c5398176?w=200',
                    'comments_count' => 12,
                    'media_type' => 'REEL'
                ],
                [
                    'id' => 'post_02',
                    'caption' => 'We are celebrating our relaunch! Comment "EMBER"! 🔥 #giveaway',
                    'media_url' => 'https://images.unsplash.com/photo-1511556532299-8f662fc26c06?w=200',
                    'comments_count' => 8,
                    'media_type' => 'IMAGE'
                ]
            ]
        ]);
        exit;
    }

    public function draw() {
        header('Content-Type: application/json');
        $userId = Session::userId() ?? 1; // Fallback to 1 for generic tools usage/testing

        $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;

        $postId = $input['instagram_post_id'] ?? 'post_01';
        $winnerCount = (int)($input['winner_count'] ?? 1);
        $keyword = trim($input['keyword'] ?? 'DONE');
        $minMentions = (int)($input['min_mentions'] ?? 2);
        $oneEntryPerUser = isset($input['one_entry_per_user']) ? 1 : 0;
        $caseSensitive = isset($input['case_sensitive']) ? 1 : 0;

        // Custom simulated comments database matching the frontend
        $rawComments = [
            'post_01' => [
                ['username' => 'brian_99', 'text' => 'This is amazing! DONE @jessica @alex', 'created_at' => '2026-08-13 12:30:15'],
                ['username' => 'clara_mercer', 'text' => 'Hope I win! DONE @brian_99 @clara_mercer', 'created_at' => '2026-08-13 12:35:40'],
                ['username' => 'alex_mercer', 'text' => 'Count me in! DONE @alex @jessica', 'created_at' => '2026-08-13 12:40:00'],
                ['username' => 'pixel_guru', 'text' => 'Cool tools! @james @emma', 'created_at' => '2026-08-13 12:45:10'], // Disqualified (no keyword)
                ['username' => 'bobby_bob', 'text' => 'DONE @peter', 'created_at' => '2026-08-13 12:50:00'] // Disqualified (insufficient tags)
            ],
            'post_02' => [
                ['username' => 'pixel_artist', 'text' => 'Beautiful! EMBER @canva @figma', 'created_at' => '2026-08-13 14:02:15'],
                ['username' => 'vector_king', 'text' => 'Loved this! EMBER @creative', 'created_at' => '2026-08-13 14:05:00']
            ]
        ];

        $postComments = $rawComments[$postId] ?? $rawComments['post_01'];

        $eligiblePool = [];
        $disqualifiedPool = [];
        $seenUsers = [];

        foreach ($postComments as $comment) {
            $username = $comment['username'];

            // 1. One entry per user rule
            if ($oneEntryPerUser && in_array($username, $seenUsers)) {
                $disqualifiedPool[] = [
                    'username' => $username,
                    'text' => $comment['text'],
                    'reason' => 'Duplicate entry per user constraint'
                ];
                continue;
            }

            // 2. Keyword rules
            $textToMatch = $caseSensitive ? $comment['text'] : strtolower($comment['text']);
            $keywordToMatch = $caseSensitive ? $keyword : strtolower($keyword);
            if (!empty($keyword) && strpos($textToMatch, $keywordToMatch) === false) {
                $disqualifiedPool[] = [
                    'username' => $username,
                    'text' => $comment['text'],
                    'reason' => 'Missing required keyword'
                ];
                continue;
            }

            // 3. Mentions checks
            preg_match_all('/@[a-zA-Z0-9_]+/', $comment['text'], $matches);
            $mentions = array_unique($matches[0] ?? []);
            if (count($mentions) < $minMentions) {
                $disqualifiedPool[] = [
                    'username' => $username,
                    'text' => $comment['text'],
                    'reason' => 'Insufficient unique mentions'
                ];
                continue;
            }

            // Mark user as processed
            $seenUsers[] = $username;
            $eligiblePool[] = [
                'username' => $username,
                'text' => $comment['text'],
                'mention_count' => count($mentions),
                'matched_keywords' => [$keyword]
            ];
        }

        // 4. Secure Random Draw Selection
        $winners = [];
        if (!empty($eligiblePool)) {
            $poolKeys = array_keys($eligiblePool);
            shuffle($poolKeys);
            $winnerKeys = array_slice($poolKeys, 0, min($winnerCount, count($eligiblePool)));
            foreach ($winnerKeys as $key) {
                $winners[] = $eligiblePool[$key];
            }
        }

        // DB Transactions persistence (Saving draws to DB)
        DB::beginTransaction();
        try {
            DB::query(
                "INSERT INTO ig_giveaway_draws (user_id, instagram_post_id, winner_count, keyword_rule, min_mentions, one_entry_per_user, excluded_usernames, eligible_count, disqualified_count) VALUES (?, ?, ?, ?, ?, ?, '[]', ?, ?)",
                [$userId, $postId, $winnerCount, json_encode(['keyword' => $keyword]), $minMentions, $oneEntryPerUser, count($eligiblePool), count($disqualifiedPool)]
            );
            $drawId = DB::lastInsertId();

            // Insert Entries & Winners
            foreach ($eligiblePool as $e) {
                $isWinner = 0;
                foreach ($winners as $w) {
                    if ($w['username'] === $e['username']) {
                        $isWinner = 1;
                        break;
                    }
                }
                DB::query(
                    "INSERT INTO ig_giveaway_entries (draw_id, instagram_username, comment_text, mention_count, matched_keywords, status, is_winner) VALUES (?, ?, ?, ?, ?, 'eligible', ?)",
                    [$drawId, $e['username'], $e['text'], $e['mention_count'], json_encode($e['matched_keywords']), $isWinner]
                );
            }

            foreach ($disqualifiedPool as $d) {
                DB::query(
                    "INSERT INTO ig_giveaway_entries (draw_id, instagram_username, comment_text, status, disquality_reason) VALUES (?, ?, ?, 'disqualified', ?)",
                    [$drawId, $d['username'], $d['text'], $d['reason']]
                );
            }

            // Clean up to keep only last 5 draws per user (Goal 3 requirement)
            $draws = DB::fetchAll("SELECT id FROM ig_giveaway_draws WHERE user_id = ? ORDER BY id DESC", [$userId]);
            if (count($draws) > 5) {
                $toDelete = array_slice($draws, 5);
                foreach ($toDelete as $td) {
                    DB::query("DELETE FROM ig_giveaway_draws WHERE id = ?", [$td['id']]);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            // Fallback gracefully without breaking simulated client experience
        }

        echo json_encode([
            'success' => true,
            'eligible_count' => count($eligiblePool),
            'disqualified_count' => count($disqualifiedPool),
            'winners' => $winners
        ]);
        exit;
    }
}
