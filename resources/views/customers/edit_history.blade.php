<x-app-layout>
    <x-slot name="header">
        <!-- 💡 修正ポイント：h1からスタート！ -->
        <h1 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $customer->name }} 様のカルテ編集
        </h1>
    </x-slot>

    @push('styles')
        @vite(['resources/css/customer-edit-history.css'])
    @endpush

    <div class="customer-container">
        <div class="form-card">
            
            <form action="{{ route('customers.updateHistory', [$customer, $visitHistory]) }}" method="POST">
                @csrf
                @method('PUT')
                
                <input type="hidden" name="customer_id" value="{{ $customer->id }}">

                <div class="form-group">
                    <label class="form-label">来店日 <span class="text-danger-star">*</span></label>
                    <input type="date" name="visited_at" class="form-control" value="{{ old('visited_at', $visitHistory->visited_at) }}">
                    @error('visited_at') <p class="error-text">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">メニュー <span class="text-danger-star">*</span></label>
                    <input type="text" name="menu" class="form-control" value="{{ old('menu', $visitHistory->menu) }}">
                    @error('menu') <p class="error-text">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">施術メモ・カラー調合など</label>
                    <textarea name="memo" class="form-control" rows="6">{{ old('memo', $visitHistory->memo) }}</textarea>
                    @error('memo') <p class="error-text">{{ $message }}</p> @enderror
                </div>

                <div class="flex-between">
                    <a href="{{ route('customers.show', $customer) }}" class="text-gray fs-sm">キャンセル</a>
                    <button type="submit" class="btn-primary">変更を保存</button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>

