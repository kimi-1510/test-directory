@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<div class="container">
    <h1>商品一覧</h1>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>画像</th>
                <th>商品名</th>
                <th>価格</th>
                <th>説明</th>
                <th>在庫数</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td>{{ $product->id }}</td>
                <td>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#productModal{{ $product->id }}">
                        <img src="{{ asset('storage/images/' . $product->image) }}" alt="{{ $product->name }}" style="max-width: 100px;">
                    </a>
                </td>
                <td>{{ $product->name }}</td>
                <td>{{ number_format($product->price) }}円</td>
                <td>{{ $product->description }}</td>
                <td>{{ $product->stock }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@foreach($products as $product)
<!-- モーダルウィンドウ -->
<div class="modal fade" id="productModal{{ $product->id }}" tabindex="-1" aria-labelledby="productModalLabel{{ $product->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalLabel{{ $product->id }}">{{ $product->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img src="{{ asset('storage/images/' . $product->image) }}" alt="{{ $product->name }}" class="img-fluid mb-3">
                <p>{{ $product->description }}</p>
                <p>価格: {{ number_format($product->price) }}円</p>
                <p>在庫数: {{ $product->stock }}個</p>
                <div class="input-group mb-3">
                    <input type="number" class="form-control" id="quantity{{ $product->id }}" value="1" min="1" max="{{ $product->stock }}">
                    <button class="btn btn-primary" onclick="addToCart({{ $product->id }})">カートに入れる</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection

@section('js')
<script>
function addToCart(productId) {
    const quantity = document.getElementById('quantity' + productId).value;
    
    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: quantity
        })
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        // モーダルを閉じる
        const modal = bootstrap.Modal.getInstance(document.getElementById('productModal' + productId));
        modal.hide();
    })
    .catch(error => {
        console.error('Error:', error);
        alert('カートへの追加に失敗しました。');
    });
}
</script>
@endsection
