<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function confirm()
    {
        $cart = Session::get('cart', []);
        $total = 0;

        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('order.confirm', [
            'cart' => $cart,
            'total' => $total
        ]);
    }

    public function complete(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|string',
            'shipping_address' => 'required|string|min:10',
        ]);

        $cart = Session::get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.show')->with('error', 'カートが空です。');
        }

        try {
            DB::beginTransaction();

            // 注文の作成
            $order = new Order();
            $order->payment_method = $request->payment_method;
            $order->shipping_address = $request->shipping_address;
            $order->total_amount = 0;
            $order->save();

            $totalAmount = 0;

            // 注文詳細の作成
            foreach ($cart as $productId => $item) {
                $product = Product::findOrFail($productId);

                // 在庫チェック
                if ($product->stock < $item['quantity']) {
                    throw new \Exception("{$product->name}の在庫が不足しています。");
                }

                // 在庫を減らす
                $product->stock -= $item['quantity'];
                $product->save();

                // 注文詳細を作成
                $orderDetail = new OrderDetail();
                $orderDetail->order_id = $order->id;
                $orderDetail->product_id = $productId;
                $orderDetail->quantity = $item['quantity'];
                $orderDetail->price = $item['price'];
                $orderDetail->save();

                $totalAmount += $item['price'] * $item['quantity'];
            }

            // 合計金額を更新
            $order->total_amount = $totalAmount;
            $order->save();

            // カートをクリア
            Session::forget('cart');

            DB::commit();

            return view('order.complete', [
                'order' => $order
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
} 