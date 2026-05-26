<x-app-layout>
    <x-slot name="header">
        <h1>{{ __('Dashboard') }}</h1>
    </x-slot>

    @push('styles')
        @vite(['resources/css/dashboard.css'])
    @endpush

    <div class="dashboard-container">
        <!-- 👥 不要なボタンを削除し、ウェルカムメッセージのみにスッキリさせました -->
        <div class="action-card">
            <p class="welcome-text">{{ __("You're logged in!") }}</p>
        </div>

        <!-- ⏰ 賞美期限リマインダーエリア -->
        <div class="reminder-section">
            <h2 class="section-title">⏰ 前回の来店から60日以上経過しているお客様</h2>
            
            @if($reminderCustomers->isEmpty())
                <div class="empty-card">
                    該当するお客様はいません。定期的なご来店が維持されています！
                </div>
            @else
                <div class="reminder-grid">
                    @foreach($reminderCustomers as $customer)
                        @php
                            // このお客様の最新の来店履歴を1件取得
                            $latestHistory = $customer->visitHistories->first();
                            
                            // 💡 修正ポイント：startOfDay() を使って「時間」を切り捨て、純粋な「日付の差」のみを計算
                            $lastVisitDate = \Carbon\Carbon::parse($latestHistory->visited_at)->startOfDay();
                            $todayDate = \Carbon\Carbon::now()->startOfDay();
                            $daysPast = $lastVisitDate->diffInDays($todayDate);
                        @endphp
                        
                        <div class="reminder-card">
                            <div class="customer-info">
                                <h3 class="customer-name">{{ $customer->name }} 様</h3>
                                <p class="customer-kana">{{ $customer->kana }}</p>
                            </div>
                            
                            <hr class="card-divider">
                            
                            <div class="visit-detail">
                                <p class="detail-text">
                                    <span class="label">前回来店日:</span> 
                                    {{ \Carbon\Carbon::parse($latestHistory->visited_at)->format('Y年m月d日') }}
                                </p>
                                <p class="detail-text alert-text">
                                    <span class="label">経過日数:</span> 
                                    <span class="days-count">{{ $daysPast }}</span> 日前
                                </p>
                                <p class="detail-text">
                                    <span class="label">最後のメニュー:</span> 
                                    {{ $latestHistory->menu }}
                                </p>
                            </div>
                            
                            <div class="card-action">
                                <a href="{{ route('customers.show', $customer) }}" class="btn-secondary">
                                    カルテを開く
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
