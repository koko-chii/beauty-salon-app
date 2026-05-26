<x-app-layout>
    <x-slot name="header">
        <h1>{{ __('新規顧客登録') }}</h1>
    </x-slot>

    @push('styles')
        @vite(['resources/css/customer-edit.css'])
    @endpush

    <div class="customer-container">
        <div class="form-card">
            
            <form action="{{ route('customers.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label class="form-label">お名前 <span class="required-star">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="山田 花子">
                    @error('name') <p class="error-text">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">フリガナ <span class="required-star">*</span></label>
                    <input type="text" name="kana" class="form-control" value="{{ old('kana') }}" placeholder="ヤマダ ハナコ">
                    @error('kana') <p class="error-text">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">電話番号</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="09012345678">
                    @error('phone') <p class="error-text">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">性別</label>
                    <select name="gender" class="form-control">
                        <option value="">選択してください</option>
                        <option value="女性" {{ old('gender') == '女性' ? 'selected' : '' }}>女性</option>
                        <option value="男性" {{ old('gender') == '男性' ? 'selected' : '' }}>男性</option>
                        <option value="その他" {{ old('gender') == 'その他' ? 'selected' : '' }}>その他</option>
                    </select>
                    @error('gender') <p class="error-text">{{ $message }}</p> @enderror
                </div>

                <div class="flex-between">
                    <a href="{{ route('customers.index') }}" class="text-gray fs-sm">戻る</a>
                    <button type="submit" class="btn-primary">登録する</button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
