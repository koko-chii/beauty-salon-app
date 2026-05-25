<x-app-layout>
    <x-slot name="header">
        <!-- 💡 修正ポイント：h1からスタート！ -->
        <h1 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $customer->name }} 様のカルテ詳細
        </h1>
    </x-slot>

    @push('styles')
        @vite(['resources/css/customer-show.css'])
    @endpush

    <div class="customer-container">
        @if (session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-1">
            <a href="{{ route('customers.index') }}" class="text-gray fs-sm">← 顧客一覧に戻る</a>
        </div>

        <div class="carte-flex">
            <!-- 左側：顧客情報 -->
            <div class="carte-sidebar">
                <h2 class="sidebar-title">顧客情報</h2>
                <p class="fs-sm text-muted mb-1">フリガナ</p>
                <p class="fw-600 mb-1">{{ $customer->kana }}</p>
                
                <p class="fs-sm text-muted mb-1">電話番号</p>
                <p class="mb-1">{{ $customer->phone ?? '未登録' }}</p>
                
                <p class="fs-sm text-muted mb-1">性別</p>
                <p class="mb-1">{{ $customer->gender ?? '未登録' }}</p>
                
                <p class="fs-sm text-muted mb-1">カルテ登録数</p>
                <p class="fw-700 link-primary">{{ $histories->count() }} 件</p>
            </div>

            <!-- 右側：カルテ履歴 ＆ 登録フォーム -->
            <div class="carte-main">
                <div class="carte-card form-card-dashed">
                    <h2 class="fw-700 mb-1 text-gray">📝 新しい施術カルテを追加</h2>
                    
                    <form action="{{ route('customers.storeHistory', $customer) }}" method="POST">
                        @csrf
                        <input type="hidden" name="customer_id" value="{{ $customer->id }}">

                        <div class="form-group">
                            <label class="form-label">来店日 <span class="text-danger-star">*</span></label>
                            <input type="date" name="visited_at" class="form-control" value="{{ old('visited_at', date('Y-m-d')) }}">
                            @error('visited_at') <p class="error-text">{{ $message }}</p> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">メニュー <span class="text-danger-star">*</span></label>
                            <input type="text" name="menu" class="form-control" value="{{ old('menu') }}" placeholder="例: カット＋縮毛矯正">
                            @error('menu') <p class="error-text">{{ $message }}</p> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">施術メモ・カラー調合など</label>
                            <textarea name="memo" class="form-control" rows="4" placeholder="例: 根元は6トーンのブラウン。毛先は乾燥しやすいのでトリートメント多めに塗布。">{{ old('memo') }}</textarea>
                            @error('memo') <p class="error-text">{{ $message }}</p> @enderror
                        </div>

                        <div class="text-right">
                            <button type="submit" class="btn-primary">カルテを保存</button>
                        </div>
                    </form>
                </div>

                <h2 class="fs-lg fw-700 mb-1 text-dark">📜 過去の施術履歴</h2>
                
                @forelse ($histories as $history)
                    <div class="carte-card">
                        <div class="carte-date">🗓️ 来店日: {{ \Carbon\Carbon::parse($history->visited_at)->format('Y年m月d日') }}</div>
                        <h3 class="carte-menu">✂️ {{ $history->menu }}</h3>
                        <hr class="hr-line">
                        <div class="carte-memo">{{ $history->memo ?? 'メモはありません。' }}</div>
                        
                        <div class="carte-action-area">
                            <a href="{{ route('customers.editHistory', [$customer, $history]) }}" class="link-primary fs-sm">
                                カルテ編集
                            </a>
                            <form action="{{ route('customers.destroyHistory', [$customer, $history]) }}" method="POST" onsubmit="return confirm('このカルテ履歴を本当に削除してもよろしいですか？');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete fs-sm">
                                    カルテ削除
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="carte-card text-center-padding text-muted">
                        過去の施術カルテがまだありません。上のフォームから最初のカルテを登録してみましょう！
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
