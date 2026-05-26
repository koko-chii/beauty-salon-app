<x-app-layout>
    <x-slot name="header">
        <!-- 💡 修正ポイント：h1タグに変更し、Tailwindクラスを削除してスッキリさせました -->
        <h1>{{ __('顧客情報の編集') }}</h1>
    </x-slot>

    @push('styles')
        @vite(['resources/css/customer-edit.css'])
    @endpush

    <!-- 💡 修正ポイント：すべての style 属性を削除し、クラス名のみに整理しました -->
    <div class="customer-container">
        <div class="form-card">
            
            <form action="{{ route('customers.update', $customer) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label class="form-label">お名前 <span class="required-star">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $customer->name) }}">
                    @error('name') <p class="error-text">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">フリガナ <span class="required-star">*</span></label>
                    <input type="text" name="kana" class="form-control" value="{{ old('kana', $customer->kana) }}">
                    @error('kana') <p class="error-text">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">電話番号</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $customer->phone) }}">
                    @error('phone') <p class="error-text">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">性別</label>
                    <select name="gender" class="form-control">
                        <option value="">選択してください</option>
                        <option value="女性" {{ old('gender', $customer->gender) == '女性' ? 'selected' : '' }}>女性</option>
                        <option value="男性" {{ old('gender', $customer->gender) == '男性' ? 'selected' : '' }}>男性</option>
                        <option value="その他" {{ old('gender', $customer->gender) == 'その他' ? 'selected' : '' }}>その他</option>
                    </select>
                    @error('gender') <p class="error-text">{{ $message }}</p> @enderror
                </div>

                <div class="flex-between">
                    <a href="{{ route('customers.index') }}" class="text-gray fs-sm">キャンセル</a>
                    <button type="submit" class="btn-primary">変更を保存する</button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
