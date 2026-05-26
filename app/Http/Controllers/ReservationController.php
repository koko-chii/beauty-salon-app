<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    /**
     * 📅 予約カレンダー画面の表示
     */
        /**
     * 📅 予約カレンダー画面の表示
     */
    public function index()
    {
        // 1. ログイン中のユーザー（美容師）情報を100%確実に取得して同期させる
        $user = Auth::user();

        // 2. 予約フォームの選択肢として使うために、このユーザーに紐づく顧客一覧を取得
        $customers = Customer::where('user_id', $user->id)->orderBy('kana')->get();
        
        // 💡 修正の正解：共通レイアウト（Breeze）がパニックを起こさないよう、compactに 'user' を追加して確実に引き渡します
        return view('reservations.index', compact('customers', 'user'));
    }


    /**
     * 📊 FullCalendar用に予約データをJSON形式で返す
     */
    public function getEvents(Request $request)
    {
        // カレンダーが表示している期間（開始日〜終了日）をFullCalendarから自動取得
        $start = $request->input('start');
        $end = $request->input('end');

        // ログインユーザーの予約で、期間内のものを取得
        $reservations = Reservation::where('user_id', Auth::id())
            ->where('start_at', '>=', $start)
            ->where('end_at', '<=', $end)
            ->with('customer')
            ->get();

        // FullCalendarが認識できるデータ構造に変換
        $events = $reservations->map(function ($reservation) {
            $customerName = $reservation->customer ? $reservation->customer->name . ' 様' : 'ご新規様';
            return [
                'id' => $reservation->id,
                'title' => "💇‍♂️ {$customerName} [{$reservation->menu}]",
                'start' => \Carbon\Carbon::parse($reservation->start_at)->format('c'),
                'end' => \Carbon\Carbon::parse($reservation->end_at)->format('c'),
                'description' => $reservation->memo,
                'backgroundColor' => '#8b5cf6', // 統一感のあるパープル
                'borderColor' => '#7c3aed',
                // 💡 JavaScript側で中身を取り出せるようにカスタムデータを追加
                'customerId' => $reservation->customer_id,
                'menuOnly' => $reservation->menu,
            ];
        });

        return response()->json($events);
    }

    /**
     * 📥 予約の新規登録
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'start_at' => 'required|date|before:end_at',
            'end_at' => 'required|date|after:start_at',
            'menu' => 'required|string|max:255',
            'memo' => 'nullable|string',
        ]);

        // ログインユーザーのIDをセットして予約を作成
        Reservation::create(array_merge($validated, [
            'user_id' => Auth::id()
        ]));

        return redirect()->route('reservations.index')->with('success', '予約を登録しました。');
    }

    /**
     * 🔄 予約データの更新処理
     */
    public function update(Request $request, Reservation $reservation)
    {
        // 他人の予約を勝手に編集できないようにセキュリティチェック
        if ($reservation->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'start_at' => 'required|date|before:end_at',
            'end_at' => 'required|date|after:start_at',
            'menu' => 'required|string|max:255',
            'memo' => 'nullable|string',
        ]);

        // データを上書き更新
        $reservation->update($validated);

        return redirect()->route('reservations.index')->with('success', '予約内容を変更しました。');
    }

    public function destroy(Reservation $reservation)
    {
        // 他人の予約を勝手に削除できないようにセキュリティチェック
        if ($reservation->user_id !== Auth::id()) {
            abort(403);
        }

        // データを削除
        $reservation->delete();

        return redirect()->route('reservations.index')->with('success', '予約を削除しました。');
    }
}
