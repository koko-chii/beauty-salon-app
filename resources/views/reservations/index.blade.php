<x-app-layout>
    <x-slot name="header">
        <h1>{{ __('予約スケジュール管理') }}</h1>
    </x-slot>

    @push('styles')
        @vite(['resources/css/customer-edit.css'])
    @endpush

    <div class="reservation-container">
        @if (session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="reservation-layout">
            
            <!-- ➕ 左側：新規予約登録 ＆ 編集共通フォーム -->
            <div class="reservation-sidebar">
                <div class="form-card">
                    <h2 class="sidebar-title" id="form-title">📅 新規予約を登録</h2>
                    
                    <form action="{{ route('reservations.store') }}" method="POST" id="reservation-form">
                        @csrf
                        <div id="method-container"></div>

                        <div class="form-group">
                            <label class="form-label">顧客選択</label>
                            <select name="customer_id" class="form-control" id="input-customer">
                                <option value="">選択してください（ご新規様）</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }} 様（{{ $customer->kana }}）
                                    </option>
                                @endforeach
                            </select>
                            @error('customer_id') <p class="error-text">{{ $message }}</p> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">予約開始日時 <span class="required-star">*</span></label>
                            <input type="datetime-local" name="start_at" class="form-control" id="input-start" value="{{ old('start_at') }}">
                            @error('start_at') <p class="error-text">{{ $message }}</p> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">予約終了日時 <span class="required-star">*</span></label>
                            <input type="datetime-local" name="end_at" class="form-control" id="input-end" value="{{ old('end_at') }}">
                            @error('end_at') <p class="error-text">{{ $message }}</p> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">メニュー <span class="required-star">*</span></label>
                            <input type="text" name="menu" class="form-control" id="input-menu" value="{{ old('menu') }}" placeholder="例: カット＋カラー">
                            @error('menu') <p class="error-text">{{ $message }}</p> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">予約メモ</label>
                            <textarea name="memo" class="form-control" id="input-memo" rows="3" placeholder="例: 髪の状態を見てトリートメント案内">{{ old('memo') }}</textarea>
                            @error('memo') <p class="error-text">{{ $message }}</p> @enderror
                        </div>

                        <!-- 💡 ボタンエリア：削除ボタンを配置できるように構造を調整 -->
                        <div class="form-actions-wrapper">
                            <div class="left-actions">
                                <button type="button" class="btn-cancel hidden" id="btn-reset-form">クリア</button>
                                <!-- 🗑️ 編集モードの時だけJavaScriptで表示させる削除ボタン -->
                                <button type="button" class="btn-delete-trigger hidden" id="btn-delete-trigger">削除</button>
                            </div>
                            <button type="submit" class="btn-primary" id="btn-submit">予約を確定する</button>
                        </div>
                    </form>

                    <!-- 🗑️ 削除を裏側で実行するための見えないフォーム（セキュリティ対応） -->
                    <form action="" method="POST" id="delete-reservation-form" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>

            <!-- 🗓️ 右側：カレンダー本体 -->
            <div class="reservation-main">
                <div class="calendar-card">
                    <div id="calendar"></div>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
        @vite(['resources/js/reservations.js'])
    @endpush
</x-app-layout>
