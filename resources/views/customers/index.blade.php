<x-app-layout>
    <x-slot name="header">
        <!-- 💡 修正ポイント：h1からスタート！ -->
        <h1 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('顧客一覧') }}
        </h1>
    </x-slot>

    @push('styles')
        @vite(['resources/css/customer-index.css'])
    @endpush

    <div class="customer-container">
        @if (session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="text-right">
            <a href="{{ route('customers.create') }}" class="btn-primary">新規顧客登録</a>
        </div>

        <table class="customer-table">
            <thead>
                <tr>
                    <th>フリガナ</th>
                    <th>お名前</th>
                    <th>電話番号</th>
                    <th>性別</th>
                    <th>登録日</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($customers as $customer)
                    <tr>
                        <td>{{ $customer->kana }}</td>
                        <td>
                            <a href="{{ route('customers.show', $customer) }}" class="link-primary">
                                {{ $customer->name }}
                            </a>
                        </td>
                        <td>{{ $customer->phone ?? '未登録' }}</td>
                        <td>{{ $customer->gender ?? '未登録' }}</td>
                        <td>{{ $customer->created_at->format('Y/m/d') }}</td>
                        <td>
                            <div class="action-flex">
                                <a href="{{ route('customers.edit', $customer) }}" class="link-primary">
                                    編集
                                </a>
                                <form action="{{ route('customers.destroy', $customer) }}" method="POST" onsubmit="return confirm('本当に削除してもよろしいですか？');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete">
                                        削除
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center-padding">顧客がまだ登録されていません。</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>
