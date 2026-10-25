<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comic;
use App\Models\Category;

class ComicController extends Controller
{
    public function index(Request $request)
    {
        $query = Comic::query()->with(['categories', 'latestChapters' => fn($q) => $q->take(2)]);

        // Filter by category
        if ($request->filled('category')) {
            $catSlug = $request->query('category');
            $query->whereHas('categories', function ($q) use ($catSlug) {
                $q->where('slug', $catSlug);
            });
        }

        // Filter by status
        if ($request->filled('status') && in_array($request->query('status'), ['ongoing', 'completed'])) {
            $query->where('status', $request->query('status'));
        }

        // Sorting
        $sort = $request->query('sort', 'latest');
        if ($sort === 'views') {
            $query->orderBy('views_count', 'desc');
        } elseif ($sort === 'rating') {
            $query->orderBy('rating_score', 'desc');
        } elseif ($sort === 'name') {
            $query->orderBy('title', 'asc');
        } else {
            $query->orderBy('updated_at', 'desc');
        }

        $comics = $query->paginate(16)->withQueryString();
        $categories = Category::withCount('comics')->get();

        return view('pages.comics-list', compact('comics', 'categories', 'sort'));
    }

    public function show($slug)
    {
        $comic = Comic::where('slug', $slug)
            ->with([
                'categories',
                'chapters' => fn($q) => $q->orderBy('chapter_number', 'asc'),
                'uploader',
                'comments.user',
                'comments.replies'
            ])
            ->firstOrFail();

        // Increment views count
        $comic->increment('views_count');

        // Check if user has favorited
        $isFavorited = false;
        if (auth()->check()) {
            $isFavorited = auth()->user()->favorites()->where('comic_id', $comic->id)->exists();
        }

        // Related comics by category
        $catIds = $comic->categories->pluck('id');
        $relatedComics = Comic::where('id', '!=', $comic->id)
            ->whereHas('categories', fn($q) => $q->whereIn('categories.id', $catIds))
            ->take(5)
            ->get();

        return view('pages.comic-detail', compact('comic', 'isFavorited', 'relatedComics'));
    }

    // Ajax Live Search
    public function searchAjax(Request $request)
    {
        $keyword = trim($request->query('q', ''));
        if (empty($keyword)) {
            return response()->json([]);
        }

        $results = Comic::where('title', 'LIKE', "%{$keyword}%")
            ->orWhere('alternative_names', 'LIKE', "%{$keyword}%")
            ->orWhere('author', 'LIKE', "%{$keyword}%")
            ->with(['latestChapters' => fn($q) => $q->take(1)])
            ->take(6)
            ->get()
            ->map(function ($c) {
                return [
                    'id' => $c->id,
                    'title' => $c->title,
                    'slug' => $c->slug,
                    'cover_image' => $c->cover_image,
                    'author' => $c->author ?? 'Đang cập nhật',
                    'latest_chapter' => $c->latestChapters->first() ? 'Chương ' . $c->latestChapters->first()->chapter_number : 'Chưa có',
                    'url' => route('comics.show', $c->slug),
                ];
            });

        return response()->json($results);
    }
}
