<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\CoinPackage;
use App\Models\CoinTransaction;

class WalletController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $packages = CoinPackage::where('is_active', true)->orderBy('price_vnd', 'asc')->get();
        $transactions = $user->coinTransactions()->orderBy('created_at', 'desc')->take(10)->get();

        return view('pages.wallet', compact('user', 'packages', 'transactions'));
    }

    public function processTopup(Request $request)
    {
        $validated = $request->validate([
            'package_id' => 'required|exists:coin_packages,id',
            'payment_method' => 'required|in:momo,vnpay,bank_qr',
        ]);

        $package = CoinPackage::findOrFail($validated['package_id']);
        $user = auth()->user();
        $transactionCode = 'TOPUP_' . strtoupper(Str::random(10));

        $transaction = CoinTransaction::create([
            'user_id' => $user->id,
            'amount_vnd' => $package->price_vnd,
            'coins' => $package->total_coins,
            'payment_method' => $validated['payment_method'],
            'transaction_code' => $transactionCode,
            'status' => 'pending',
        ]);

        return view('pages.payment-checkout', compact('transaction', 'package'));
    }

    // Simulation Gateway Callback (Immediate instant credit demo for teacher / defense)
    public function completeSimulationPayment(Request $request, $code)
    {
        $transaction = CoinTransaction::where('transaction_code', $code)->firstOrFail();

        if ($transaction->status === 'completed') {
            return redirect()->route('wallet.index')->with('info', 'Giao dịch này đã được xử lý trước đó.');
        }

        DB::transaction(function () use ($transaction) {
            $transaction->update(['status' => 'completed']);
            $transaction->user->increment('coin_balance', $transaction->coins);
        });

        return redirect()->route('wallet.index')->with('success', "Nạp thành công +{$transaction->coins} Xu vào ví tài khoản!");
    }

    // Buy 30-day VIP Membership
    public function buyVipSubscription(Request $request)
    {
        $user = auth()->user();
        $vipCostCoins = 500; // 500 coins for 30 days VIP

        if ($user->coin_balance < $vipCostCoins) {
            return back()->with('error', "Bạn không đủ Xu (cần {$vipCostCoins} Xu). Vui lòng nạp thêm Xu vào ví.");
        }

        DB::transaction(function () use ($user, $vipCostCoins) {
            $user->decrement('coin_balance', $vipCostCoins);
            $currentExpiry = ($user->vip_expires_at && $user->vip_expires_at->isFuture())
                ? $user->vip_expires_at
                : now();
            $user->update([
                'vip_expires_at' => $currentExpiry->addDays(30)
            ]);
        });

        return back()->with('success', 'Chúc mừng bạn đã nâng cấp gói Hội viên VIP 30 ngày! Bạn có thể đọc không giới hạn mọi chương VIP.');
    }
}
