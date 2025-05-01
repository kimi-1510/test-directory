@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h1 class="text-center mb-0">ご注文ありがとうございました</h1>
                </div>
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                    </div>
                    <p class="lead">注文が完了しました。</p>
                    <p>注文番号: {{ $order->id }}</p>
                    <p>合計金額: {{ number_format($order->total_amount) }}円</p>
                    <p>お支払い方法: {{ $order->payment_method_label }}</p>
                    <p>配送先: {{ $order->shipping_address }}</p>
                    <div class="mt-4">
                        <a href="{{ route('products.index') }}" class="btn btn-primary">買い物を続ける</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 