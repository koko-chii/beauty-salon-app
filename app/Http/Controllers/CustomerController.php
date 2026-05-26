<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest; // 💡 更新用のリクエスト
use App\Http\Requests\StoreVisitHistoryRequest;
use App\Models\VisitHistory;
use Illuminate\Support\Facades\Storage;


class CustomerController extends Controller
{
    /**
     * 顧客一覧画面を表示する
     */
    public function index()
    {
        $customers = Customer::where('user_id', Auth::id())
            ->orderBy('kana', 'asc')
            ->get();

        return view('customers.index', compact('customers'));
    }

    /**
     * 顧客の新規登録画面を表示する
     */
    public function create()
    {
        return view('customers.create');
    }

    /**
     * 画面から送られてきた顧客情報をデータベースに保存する
     */
    public function store(StoreCustomerRequest $request)
    {
        $validated = $request->validated();

        Customer::create([
            'user_id' => Auth::id(),
            'name'    => $validated['name'],
            'kana'    => $validated['kana'],
            'phone'   => $validated['phone'],
            'gender'  => $validated['gender'],
        ]);

        return redirect()->route('customers.index')->with('success', '顧客を登録しました。');
    }

    /**
     * 顧客情報の編集画面を表示する
     */
    public function edit(Customer $customer)
    {
        // 🔒 他の美容師の顧客データを勝手に編集させないセキュリティ
        if ($customer->user_id !== Auth::id()) {
            abort(403);
        }

        return view('customers.edit', compact('customer'));
    }

    /**
     * 編集画面から送られてきたデータで、顧客情報を更新する
     */
    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        // 🔒 セキュリティチェック
        if ($customer->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validated();
        $customer->update($validated);

        return redirect()->route('customers.index')->with('success', '顧客情報を更新しました。');
    }

    public function destroy(Customer $customer)
    {
        // 🔒 セキュリティチェック：他の美容師の顧客データを勝手に削除させない
        if ($customer->user_id !== Auth::id()) {
            abort(403);
        }

        // データベースからこのお客様のデータを削除
        $customer->delete();

        // 一覧画面に「削除しました」というメッセージ付きで戻る
        return redirect()->route('customers.index')->with('success', '顧客データを削除しました。');
    }

    public function show(Customer $customer)
    {
        // 🔒 セキュリティチェック：他の美容師の顧客データを勝手に盗み見させない
        if ($customer->user_id !== Auth::id()) {
            abort(403);
        }

        // 💡 リレーションを使って、このお客様に紐づくカルテ履歴を来店日が新しい順（降順）で取得
        $histories = $customer->visitHistories()
            ->orderBy('visited_at', 'desc')
            ->get();

        // resources/views/customers/show.blade.php に顧客データとカルテ履歴を渡して表示
        return view('customers.show', compact('customer', 'histories'));
    }

     public function storeHistory(StoreVisitHistoryRequest $request, Customer $customer)
    {
        // 🔒 セキュリティチェック
        if ($customer->user_id !== Auth::id()) {
            abort(403);
        }

        // 1. まずバリデーション済みの安全な基本データを取得（visited_at, menu, memoなど）
        $data = $request->validated();

        // 2. 送信された画像ファイルをループで処理して保存し、データ配列に直接追加する
        for ($i = 1; $i <= 3; $i++) {
            if ($request->hasFile("image_{$i}")) {
                // ストレージの public/visit_images フォルダに保存
                $path = $request->file("image_{$i}")->store('visit_images', 'public');
                
                // 💡 重要：一度取り出したデータ配列（$data）に直接パスを書き込みます（これで消されません！）
                $data["image_path_{$i}"] = $path;
            }
        }

        // 3. 🔗 リレーションを使って、画像パス（image_path）もすべて含んだデータで一発保存！
        $customer->visitHistories()->create($data);

        // 登録が終わったら、元の詳細画面にメッセージ付きで戻る
        return redirect()->route('customers.show', $customer)->with('success', 'カルテ履歴を追加しました。');
    }

    public function editHistory(Customer $customer, VisitHistory $visitHistory)
    {
        // 🔒 他の美容師のデータ、または別のお客様のカルテをいじらせないセキュリティ
        if ($customer->user_id !== Auth::id() || $visitHistory->customer_id !== $customer->id) {
            abort(403);
        }

        // resources/views/customers/edit_history.blade.php を表示
        return view('customers.edit_history', compact('customer', 'visitHistory'));
    }

    /**
     * 💡 カルテを更新する
     */
    // 💡 引き数を「StoreVisitHistoryRequest」に書き換えました
    public function updateHistory(StoreVisitHistoryRequest $request, Customer $customer, VisitHistory $visitHistory)
    {
        // 🔒 セキュリティチェック
        if ($customer->user_id !== Auth::id()) {
            abort(403);
        }

        // 1. バリデーション済みの安全な基本データを取得（visited_at, menu, memo）
        $data = $request->validated();

        // 2. 新しい画像ファイルが送られてきた場合、古い画像を削除して入れ替える
        for ($i = 1; $i <= 3; $i++) {
            if ($request->hasFile("image_{$i}")) {
                // 古いファイルがすでに登録されている場合は、ストレージから物理削除
                if ($visitHistory->{"image_path_{$i}"}) {
                    Storage::disk('public')->delete($visitHistory->{"image_path_{$i}"});
                }
                
                // 新しいファイルを保存し、データ配列に直接パスを書き込む
                $data["image_path_{$i}"] = $request->file("image_{$i}")->store('visit_images', 'public');
            }
        }

        // 3. データベースのカルテ情報を更新
        $visitHistory->update($data);

        // 更新が終わったら、カルテ詳細画面にメッセージ付きで戻る
        return redirect()->route('customers.show', $customer)->with('success', 'カルテを更新しました。');
    }
    
    /**
     * 💡 カルテを削除する
     */
    public function destroyHistory(Customer $customer, VisitHistory $visitHistory)
    {
        if ($customer->user_id !== Auth::id() || $visitHistory->customer_id !== $customer->id) {
            abort(403);
        }

        $visitHistory->delete();

        return redirect()->route('customers.show', $customer)->with('success', 'カルテを削除しました。');
    }
}
