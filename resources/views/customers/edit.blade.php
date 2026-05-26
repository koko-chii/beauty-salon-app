<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('顧客情報の編集') }}
        </h2>
    </x-slot>

    @push('styles')
        @vite(['resources/css/customer-edit.css'])
    @endpush

    <div class="customer-container" style="max-width: 600px;">
        <div style="background-color: white; padding: 2rem; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            
            {{-- 💡 更新するときは、routeに「どのお客様(id)」を更新するかを渡します --}}
            <form action="{{ route('customers.update', $customer) }}" method="POST">
                @csrf
                {{-- 💡 Laravelで更新(Update)を行うときは、必ずPUTメソッドを指定するルールです --}}
                @method('PUT')

                <div class="form-group">
                    <label class="form-label">お名前 <span style="color: red;">*</span></label>
                    {{-- oldの第2引数に $customer->name を入れることで、最初は現在登録されている名前が自動で入ります --}}
                    <input type="text" name="name" class="form-control" value="{{ old('name', $customer->name) }}">
                    @error('name') <p class="error-text">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">フリガナ <span style="color: red;">*</span></label>
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

                <div style="margin-top: 2rem; display: flex; justify-content: space-between; align-items: center;">
                    <a href="{{ route('customers.index') }}" style="color: #4b5563; font-size: 0.875rem;">キャンセル</a>
                    <button type="submit" class="btn-primary">変更を保存する</button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
