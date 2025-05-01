@extends('layouts.app')

@section('content')
<div class="container">
    <h1>注文確認</h1>

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">注文内容</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>商品画像</th>
                                    <th>商品名</th>
                                    <th>単価</th>
                                    <th>数量</th>
                                    <th>小計</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cart as $productId => $item)
                                <tr>
                                    <td>
                                        @if($item['image'])
                                            <img src="{{ asset('storage/images/' . $item['image']) }}" alt="{{ $item['name'] }}" style="max-width: 100px;">
                                        @else
                                            <span>画像なし</span>
                                        @endif
                                    </td>
                                    <td>{{ $item['name'] }}</td>
                                    <td>{{ number_format($item['price']) }}円</td>
                                    <td>{{ $item['quantity'] }}</td>
                                    <td>{{ number_format($item['price'] * $item['quantity']) }}円</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-end"><strong>合計金額:</strong></td>
                                    <td><strong>{{ number_format($total) }}円</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">お支払い情報</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('order.complete') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="payment_method" class="form-label">お支払い方法</label>
                            <select class="form-select" id="payment_method" name="payment_method" required>
                                <option value="credit_card">クレジットカード</option>
                                <option value="bank_transfer">銀行振込</option>
                                <option value="convenience">コンビニ決済</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="shipping_address" class="form-label">配送先住所</label>
                            <textarea class="form-control" id="shipping_address" name="shipping_address" rows="3" required></textarea>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">注文を確定する</button>
                            <a href="{{ route('cart.show') }}" class="btn btn-secondary">カートに戻る</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 