@extends('layouts.app')

@section('content')
<div class="container">
    <h1>ショッピングカート</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(count($cart) > 0)
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>商品画像</th>
                        <th>商品名</th>
                        <th>価格</th>
                        <th>数量</th>
                        <th>小計</th>
                        <th>操作</th>
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
                        <td>
                            <form action="{{ route('cart.update', $productId) }}" method="POST" class="d-flex">
                                @csrf
                                <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="form-control" style="width: 80px;">
                                <button type="submit" class="btn btn-sm btn-primary ms-2">更新</button>
                            </form>
                        </td>
                        <td>{{ number_format($item['price'] * $item['quantity']) }}円</td>
                        <td>
                            <form action="{{ route('cart.remove', $productId) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-danger">削除</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="text-end"><strong>合計金額:</strong></td>
                        <td colspan="2"><strong>{{ number_format($total) }}円</strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('products.index') }}" class="btn btn-secondary">買い物を続ける</a>
            <a href="{{ route('order.confirm') }}" class="btn btn-primary">注文に進む</a>
        </div>
    @else
        <div class="alert alert-info">
            カートに商品がありません。
        </div>
    @endif
</div>
@endsection 