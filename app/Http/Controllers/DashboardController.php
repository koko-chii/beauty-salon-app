<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. ログイン中の美容師（ユーザー）のIDを取得
        $userId = Auth::id();

        // 2. 「賞美期限切れ（前回来店から60日以上経過）」の基準日を計算（現在の2026年5月26日から60日前）
        $thresholdDate = Carbon::now()->subDays(60);

        // 3. 条件に合う顧客をデータベースから賢く抽出！
        $reminderCustomers = Customer::where('user_id', $userId)
            ->whereHas('visitHistories', function ($query) use ($thresholdDate) {
                // 最新の来店日が基準日より前のカルテを対象にする
                $query->where('visited_at', '<', $thresholdDate);
            })
            ->with(['visitHistories' => function ($query) {
                // 各顧客の最新の来店履歴を1件だけ一緒に取得する
                $query->latest('visited_at');
            }])
            ->get();

        // 4. 計算した顧客データをダッシュボード画面（Blade）に送る
        return view('dashboard', compact('reminderCustomers'));
    }
}
