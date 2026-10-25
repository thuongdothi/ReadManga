<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Vui lòng nhập địa chỉ email.',
            'email.email' => 'Email không đúng định dạng.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            if (Auth::user()->status === 'banned') {
                Auth::logout();
                return back()->withErrors(['email' => 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ Admin.']);
            }
            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'redirect' => route('home')]);
            }
            return redirect()->intended(route('home'))->with('success', 'Đăng nhập thành công! Chào mừng trở lại ' . Auth::user()->name);
        }

        if ($request->expectsJson()) {
            return response()->json(['success' => false, 'message' => 'Email hoặc mật khẩu không chính xác.'], 422);
        }

        return back()->withErrors([
            'email' => 'Email hoặc mật khẩu không chính xác.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'name.required' => 'Vui lòng nhập họ và tên.',
            'email.required' => 'Vui lòng nhập email.',
            'email.unique' => 'Email này đã được sử dụng.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu phải từ 6 ký tự trở lên.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'user',
            'coin_balance' => 50, // Bonus 50 xu chào mừng thành viên mới
            'avatar' => 'https://api.dicebear.com/7.x/bottts/svg?seed=' . urlencode($validated['name']),
        ]);

        Auth::login($user);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'redirect' => route('home')]);
        }

        return redirect()->route('home')->with('success', 'Đăng ký thành công! Bạn nhận được 50 xu thưởng khởi nghiệp.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home')->with('success', 'Đã đăng xuất tài khoản thành công.');
    }

    public function profile()
    {
        $user = Auth::user();
        $favorites = $user->favoriteComics()->with(['latestChapters'])->get();
        $readingHistories = $user->readingHistories()->with(['comic', 'chapter'])->orderBy('last_read_at', 'desc')->take(10)->get();
        $coinTransactions = $user->coinTransactions()->orderBy('created_at', 'desc')->take(10)->get();
        $unlockedChapters = $user->unlockedChapters()->with(['chapter.comic'])->orderBy('unlocked_at', 'desc')->take(15)->get();
        $orders = $user->orders()->with('items.product')->orderBy('created_at', 'desc')->get();

        return view('auth.profile', compact('user', 'favorites', 'readingHistories', 'coinTransactions', 'unlockedChapters', 'orders'));
    }
}
