<x-app-layout>
    <x-slot name="header">
        <h1>
            {{ $customer->name }} 様のカルテ編集
        </h1>
    </x-slot>

    @push('styles')
        @vite(['resources/css/customer-edit-history.css'])
    @endpush

    <div class="customer-container">
        <div class="form-card">
            
            <!-- 💡 画像送信のために enctype="multipart/form-data" を追加 -->
            <form action="{{ route('customers.updateHistory', [$customer, $visitHistory]) }}" method="POST" enctype="multipart/form-data">
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

                <!-- 📸 施術写真のアップロード・変更欄を追加 -->
                <div class="form-group image-upload-section">
                    <label class="form-label font-bold">📸 施術写真（最大3枚）</label>
                    
                    <div class="image-input-wrapper">
                        <input type="file" name="image_1" accept="image/*" class="form-control">
                        @error('image_1') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                    
                    <div class="image-input-wrapper">
                        <input type="file" name="image_2" accept="image/*" class="form-control">
                        @error('image_2') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                    
                    <div class="image-input-wrapper">
                        <input type="file" name="image_3" accept="image/*" class="form-control">
                        @error('image_3') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex-between">
                    <a href="{{ route('customers.show', $customer) }}" class="text-gray fs-sm">キャンセル</a>
                    <button type="submit" class="btn-primary">変更を保存</button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
