<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comic;
use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $featuredComics = Comic::where('is_featured', true)
            ->with(['categories', 'latestChapters' => fn($q) => $q->take(2)])
            ->take(5)
            ->get();

        $latestComics = Comic::with(['categories', 'latestChapters' => fn($q) => $q->take(3)])
            ->orderBy('updated_at', 'desc')
            ->paginate(12);

        $topComics = Comic::orderBy('views_count', 'desc')
            ->take(10)
            ->get();

        $categories = Category::withCount('comics')->get();

        $featuredProducts = Product::where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();

        return view('pages.home', compact('featuredComics', 'latestComics', 'topComics', 'categories', 'featuredProducts'));
    }
}
