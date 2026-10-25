<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Comic;
use App\Models\Chapter;
use App\Models\ReadingHistory;
use App\Models\UnlockedChapter;

class ReaderController extends Controller
{
    public function read($comicSlug, $chapterSlug)
    {
        $comic = Comic::where('slug', $comicSlug)->firstOrFail();
        $chapter = Chapter::where('comic_id', $comic->id)
            ->where('slug', $chapterSlug)
            ->with(['images', 'comic'])
            ->firstOrFail();

        // Increment chapter views
        $chapter->increment('views_count');
        $comic->increment('views_count');

        // Check Access Permission
        $hasAccess = true;
        $isVipLocked = false;

        if ($chapter->is_vip || $chapter->price_coins > 0) {
            if (!auth()->check()) {
                $hasAccess = false;
                $isVipLocked = true;
            } else {
                $user = auth()->user();
                $hasAccess = $user->hasUnlocked($chapter->id);
                if (!$hasAccess) {
                    $isVipLocked = true;
                }
            }
        }

        // Save Reading History if authenticated
        if (auth()->check()) {
            ReadingHistory::updateOrCreate(
                ['user_id' => auth()->id(), 'comic_id' => $comic->id],
                ['chapter_id' => $chapter->id, 'last_read_at' => now()]
            );
        }

        // Navigation
        $allChapters = Chapter::where('comic_id', $comic->id)
            ->orderBy('chapter_number', 'asc')
            ->select('id', 'chapter_number', 'title', 'slug', 'is_vip', 'price_coins')
            ->get();

        $prevChapter = $chapter->getPreviousChapter();
        $nextChapter = $chapter->getNextChapter();

        return view('pages.reader', compact('comic', 'chapter', 'hasAccess', 'isVipLocked', 'allChapters', 'prevChapter', 'nextChapter'));
    }

    // Ajax Unlock Chapter with Coins
    public function unlockAjax(Request $request, $chapterId)
    {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'Vui lòng đăng nhập để mở khóa chương.'], 401);
        }

        $user = auth()->user();
        $chapter = Chapter::with('comic')->findOrFail($chapterId);

        // Check if already unlocked
        if ($user->hasUnlocked($chapter->id)) {
            return response()->json(['success' => true, 'message' => 'Bạn đã có quyền đọc chương này!']);
        }

        $cost = $chapter->price_coins;
        if ($user->coin_balance < $cost) {
            return response()->json([
                'success' => false,
                'message' => "Số dư không đủ ({$user->coin_balance} Xu). Chương này cần {$cost} Xu. Vui lòng nạp thêm Xu!",
                'need_coins' => $cost - $user->coin_balance
            ], 400);
        }

        // Deduct coins & unlock in a safe ACID transaction
        DB::transaction(function () use ($user, $chapter, $cost) {
            $user->decrement('coin_balance', $cost);
            UnlockedChapter::create([
                'user_id' => $user->id,
                'chapter_id' => $chapter->id,
                'coins_spent' => $cost,
                'unlocked_at' => now(),
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => "Mở khóa thành công chương {$chapter->chapter_number}! Đã trừ {$cost} Xu.",
            'new_balance' => $user->fresh()->coin_balance
        ]);
    }
}
