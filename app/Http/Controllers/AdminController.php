<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Comic;
use App\Models\Category;
use App\Models\Chapter;
use App\Models\ChapterImage;
use App\Models\Order;
use App\Models\CoinTransaction;
use App\Models\Product;

class AdminController extends Controller
{
    // Dashboard Overview
    public function dashboard()
    {
        $totalUsers = User::count();
        $totalComics = Comic::count();
        $totalChapters = Chapter::count();
        $totalOrders = Order::count();
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total_amount');
        $totalCoinRecharge = CoinTransaction::where('status', 'completed')->sum('amount_vnd');

        $recentOrders = Order::with('user')->orderBy('created_at', 'desc')->take(5)->get();
        $recentTransactions = CoinTransaction::with('user')->orderBy('created_at', 'desc')->take(5)->get();
        $topComics = Comic::orderBy('views_count', 'desc')->take(5)->get();

        return view('admin.dashboard', compact(
            'totalUsers', 'totalComics', 'totalChapters', 'totalOrders',
            'totalRevenue', 'totalCoinRecharge', 'recentOrders', 'recentTransactions', 'topComics'
        ));
    }

    // Comics Management
    public function comicsIndex()
    {
        $comics = Comic::with(['categories', 'uploader'])->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.comics.index', compact('comics'));
    }

    public function comicsCreate()
    {
        $categories = Category::all();
        return view('admin.comics.create', compact('categories'));
    }

    public function comicsStore(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'nullable|string|max:255',
            'artist' => 'nullable|string|max:255',
            'status' => 'required|in:ongoing,completed',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|max:3072',
            'cover_url' => 'nullable|url',
            'is_featured' => 'nullable|boolean',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
        ], [
            'title.required' => 'Vui lòng nhập tên truyện.',
            'categories.required' => 'Vui lòng chọn ít nhất một thể loại.',
        ]);

        $coverPath = 'https://images.unsplash.com/photo-1578632767115-351597cf2477?auto=format&fit=crop&w=600&q=80';
        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $filename = time() . '_' . Str::slug($validated['title']) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/covers'), $filename);
            $coverPath = asset('uploads/covers/' . $filename);
        } elseif ($request->filled('cover_url')) {
            $coverPath = $validated['cover_url'];
        }

        $comic = Comic::create([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']) . '-' . Str::random(4),
            'author' => $validated['author'] ?? 'Đang cập nhật',
            'artist' => $validated['artist'] ?? 'Đang cập nhật',
            'status' => $validated['status'],
            'description' => $validated['description'] ?? '',
            'cover_image' => $coverPath,
            'is_featured' => $request->has('is_featured'),
            'uploader_id' => auth()->id(),
        ]);

        $comic->categories()->sync($validated['categories']);

        return redirect()->route('admin.comics.index')->with('success', "Đã thêm mới bộ truyện '{$comic->title}' thành công!");
    }

    public function comicsEdit($id)
    {
        $comic = Comic::with('categories')->findOrFail($id);
        $categories = Category::all();
        return view('admin.comics.edit', compact('comic', 'categories'));
    }

    public function comicsUpdate(Request $request, $id)
    {
        $comic = Comic::findOrFail($id);
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'nullable|string|max:255',
            'artist' => 'nullable|string|max:255',
            'status' => 'required|in:ongoing,completed',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|max:3072',
            'cover_url' => 'nullable|url',
            'is_featured' => 'nullable|boolean',
            'categories' => 'required|array',
        ]);

        $coverPath = $comic->cover_image;
        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $filename = time() . '_' . Str::slug($validated['title']) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/covers'), $filename);
            $coverPath = asset('uploads/covers/' . $filename);
        } elseif ($request->filled('cover_url')) {
            $coverPath = $validated['cover_url'];
        }

        $comic->update([
            'title' => $validated['title'],
            'author' => $validated['author'] ?? $comic->author,
            'artist' => $validated['artist'] ?? $comic->artist,
            'status' => $validated['status'],
            'description' => $validated['description'] ?? $comic->description,
            'cover_image' => $coverPath,
            'is_featured' => $request->has('is_featured'),
        ]);

        $comic->categories()->sync($validated['categories']);

        return redirect()->route('admin.comics.index')->with('success', "Cập nhật bộ truyện '{$comic->title}' thành công!");
    }

    public function comicsDestroy($id)
    {
        $comic = Comic::findOrFail($id);
        $title = $comic->title;
        $comic->delete();
        return redirect()->route('admin.comics.index')->with('success', "Đã xóa bộ truyện '{$title}'.");
    }

    // Chapters Management
    public function chaptersIndex($comicId)
    {
        $comic = Comic::with(['chapters' => fn($q) => $q->orderBy('chapter_number', 'desc')->withCount('images')])->findOrFail($comicId);
        return view('admin.chapters.index', compact('comic'));
    }

    public function chaptersCreate($comicId)
    {
        $comic = Comic::findOrFail($comicId);
        $nextChapterNum = ($comic->chapters()->max('chapter_number') ?? 0) + 1;
        return view('admin.chapters.create', compact('comic', 'nextChapterNum'));
    }

    public function chaptersStore(Request $request, $comicId)
    {
        $comic = Comic::findOrFail($comicId);
        $validated = $request->validate([
            'chapter_number' => 'required|numeric|min:0.1',
            'title' => 'nullable|string|max:255',
            'is_vip' => 'nullable|boolean',
            'price_coins' => 'required_if:is_vip,1|numeric|min:0',
            'images.*' => 'image|max:5120',
        ]);

        $isVip = $request->has('is_vip');
        $price = $isVip ? (int)$request->input('price_coins', 10) : 0;

        $chapter = Chapter::create([
            'comic_id' => $comic->id,
            'chapter_number' => $validated['chapter_number'],
            'title' => $validated['title'] ?? 'Chương ' . $validated['chapter_number'],
            'slug' => 'chapter-' . $validated['chapter_number'],
            'is_vip' => $isVip,
            'price_coins' => $price,
            'published_at' => now(),
        ]);

        // Batch upload images
        if ($request->hasFile('images')) {
            $page = 1;
            $destDir = public_path("uploads/chapters/{$comic->id}_{$chapter->id}");
            if (!file_exists($destDir)) {
                mkdir($destDir, 0777, true);
            }
            foreach ($request->file('images') as $file) {
                $filename = "page_{$page}." . $file->getClientOriginalExtension();
                $file->move($destDir, $filename);
                ChapterImage::create([
                    'chapter_id' => $chapter->id,
                    'image_url' => asset("uploads/chapters/{$comic->id}_{$chapter->id}/{$filename}"),
                    'page_number' => $page,
                ]);
                $page++;
            }
        } else {
            // Placeholder pages
            for ($p = 1; $p <= 5; $p++) {
                ChapterImage::create([
                    'chapter_id' => $chapter->id,
                    'image_url' => "https://picsum.photos/seed/{$comic->id}_{$chapter->id}_{$p}/800/1200",
                    'page_number' => $p,
                ]);
            }
        }

        $comic->touch(); // updates updated_at

        return redirect()->route('admin.chapters.index', $comic->id)->with('success', "Đã tạo Chương {$chapter->chapter_number} thành công!");
    }

    public function chaptersDestroy($comicId, $chapterId)
    {
        $chapter = Chapter::where('comic_id', $comicId)->findOrFail($chapterId);
        $num = $chapter->chapter_number;
        $chapter->delete();
        return back()->with('success', "Đã xóa Chương {$num}.");
    }

    // Orders Management
    public function ordersIndex()
    {
        $orders = Order::with(['items.product', 'user'])->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.orders.index', compact('orders'));
    }

    public function ordersUpdateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $validated = $request->validate([
            'shipping_status' => 'required|in:pending,processing,shipping,completed,cancelled',
            'payment_status' => 'required|in:unpaid,paid',
        ]);

        $order->update($validated);
        return back()->with('success', "Cập nhật trạng thái Đơn hàng #{$order->order_code} thành công!");
    }

    // Users Management
    public function usersIndex()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function usersUpdate(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $validated = $request->validate([
            'role' => 'required|in:admin,moderator,uploader,user',
            'status' => 'required|in:active,banned',
            'coin_balance' => 'required|integer|min:0',
        ]);

        $user->update($validated);
        return back()->with('success', "Cập nhật thông tin thành viên '{$user->name}' thành công!");
    }
}
