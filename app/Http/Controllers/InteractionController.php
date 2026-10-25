<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comic;
use App\Models\Comment;
use App\Models\Favorite;

class InteractionController extends Controller
{
    // Toggle Favorite (Ajax)
    public function toggleFavorite(Request $request, $comicId)
    {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'Vui lòng đăng nhập để theo dõi truyện.'], 401);
        }

        $userId = auth()->id();
        $favorite = Favorite::where('user_id', $userId)->where('comic_id', $comicId)->first();

        if ($favorite) {
            $favorite->delete();
            return response()->json(['success' => true, 'is_favorited' => false, 'message' => 'Đã hủy theo dõi truyện.']);
        } else {
            Favorite::create(['user_id' => $userId, 'comic_id' => $comicId]);
            return response()->json(['success' => true, 'is_favorited' => true, 'message' => 'Đã thêm truyện vào danh sách Theo dõi!']);
        }
    }

    // Post Comment (Ajax)
    public function postComment(Request $request, $comicId)
    {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'Vui lòng đăng nhập để bình luận.'], 401);
        }

        $validated = $request->validate([
            'content' => 'required|string|min:2|max:1000',
            'chapter_id' => 'nullable|exists:chapters,id',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $comment = Comment::create([
            'user_id' => auth()->id(),
            'comic_id' => $comicId,
            'chapter_id' => $validated['chapter_id'] ?? null,
            'parent_id' => $validated['parent_id'] ?? null,
            'content' => clean_html($validated['content'] ?? '') ?: htmlspecialchars($validated['content']),
            'likes_count' => 0,
        ]);

        $comment->load('user');

        return response()->json([
            'success' => true,
            'message' => 'Bình luận thành công!',
            'comment' => [
                'id' => $comment->id,
                'user_name' => $comment->user->name,
                'avatar' => $comment->user->avatar ?? 'https://api.dicebear.com/7.x/bottts/svg?seed=' . urlencode($comment->user->name),
                'content' => $comment->content,
                'created_at' => $comment->created_at->diffForHumans(),
            ]
        ]);
    }

    // Rate Comic (Ajax)
    public function rateComic(Request $request, $comicId)
    {
        $comic = Comic::findOrFail($comicId);
        $score = (float)$request->input('score', 5);
        if ($score < 1 || $score > 5) {
            return response()->json(['success' => false, 'message' => 'Điểm đánh giá không hợp lệ.'], 400);
        }

        $newRatingCount = $comic->rating_count + 1;
        $newScore = (($comic->rating_score * $comic->rating_count) + $score) / $newRatingCount;

        $comic->update([
            'rating_count' => $newRatingCount,
            'rating_score' => round($newScore, 2),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cảm ơn bạn đã đánh giá truyện!',
            'new_score' => $comic->rating_score,
            'rating_count' => $comic->rating_count,
        ]);
    }
}

if (!function_exists('clean_html')) {
    function clean_html($text) {
        return strip_tags($text, '<b><i><strong><em>');
    }
}
